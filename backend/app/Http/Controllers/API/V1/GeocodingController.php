<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Address <-> coordinates lookups used by the resource forms.
 *
 * Backed by OpenStreetMap's Nominatim, which is free and needs no API key.
 * Results are cached for 24 hours and calls are spaced at least one second
 * apart, as Nominatim's usage policy requires.
 */
class GeocodingController extends Controller
{
    private const ENDPOINT = 'https://nominatim.openstreetmap.org';

    private const TIMEOUT = 8;

    /** Results are stable enough to cache for a day. */
    private const CACHE_TTL = 86400;

    /**
     * Turn a free-text address into coordinates.
     */
    public function geocode(Request $request): JsonResponse
    {
        $address = $request->validate([
            'address' => 'required|string|min:3|max:500',
        ])['address'];

        try {
            $result = Cache::remember(
                'geocode:'.md5($address),
                self::CACHE_TTL,
                fn () => $this->lookupAddress($address),
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 503);
        }

        if ($result === null) {
            return response()->json([
                'success' => false,
                'message' => 'No location matched that address.',
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    /**
     * Turn coordinates into a human-readable address.
     */
    public function reverse(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $key = sprintf('geocode-reverse:%s,%s', $validated['latitude'], $validated['longitude']);

        try {
            $address = Cache::remember(
                $key,
                self::CACHE_TTL,
                fn () => $this->lookupCoordinates($validated['latitude'], $validated['longitude']),
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 503);
        }

        if ($address === null) {
            return response()->json([
                'success' => false,
                'message' => 'No address found for those coordinates.',
            ], 404);
        }

        return response()->json(['success' => true, 'data' => ['address' => $address]]);
    }

    private function lookupAddress(string $address): ?array
    {
        $results = $this->request('/search', [
            'q' => $address,
            'format' => 'jsonv2',
            'limit' => 1,
        ]);

        $first = $results[0] ?? null;

        if ($first === null) {
            return null;
        }

        return [
            'latitude' => (float) $first['lat'],
            'longitude' => (float) $first['lon'],
            'display_name' => $first['display_name'] ?? $address,
        ];
    }

    private function lookupCoordinates(float $latitude, float $longitude): ?string
    {
        $result = $this->request('/reverse', [
            'lat' => $latitude,
            'lon' => $longitude,
            'format' => 'jsonv2',
        ]);

        return $result['display_name'] ?? null;
    }

    /**
     * Call Nominatim. Returns the decoded body, or throws when the service
     * cannot be reached — an empty body is a legitimate "no match".
     *
     * @param  array<string, mixed>  $query
     * @return array<mixed>
     */
    private function request(string $path, array $query): array
    {
        $this->throttle();

        try {
            $response = Http::withHeaders([
                // Nominatim rejects requests without a descriptive User-Agent.
                'User-Agent' => config('app.name', 'DisasterAid System').' ('.config('app.url').')',
                'Accept-Language' => app()->getLocale(),
            ])->timeout(self::TIMEOUT)->get(self::ENDPOINT.$path, $query);
        } catch (RuntimeException $exception) {
            throw new RuntimeException('The geocoding service is unreachable. Check your connection and try again.');
        }

        if (! $response->successful()) {
            throw new RuntimeException('The geocoding service returned an error. Please try again.');
        }

        return (array) $response->json();
    }

    /**
     * Nominatim's usage policy allows at most one request per second.
     */
    private function throttle(): void
    {
        $last = (float) Cache::get('nominatim:last_request', 0);
        $elapsed = microtime(true) - $last;

        if ($elapsed < 1.0) {
            usleep((int) ((1.0 - $elapsed) * 1_000_000));
        }

        Cache::put('nominatim:last_request', microtime(true), 10);
    }
}
