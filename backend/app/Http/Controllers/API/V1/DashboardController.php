<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\DashboardStatsResource;
use App\Http\Resources\API\V1\DashboardChartsResource;
use App\Http\Resources\API\V1\DashboardMapResource;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service
    ) {}

    public function stats(): JsonResponse
    {
        $this->authorize('viewDashboard');
        return response()->json([
            'success' => true,
            'data' => new DashboardStatsResource($this->service->getStats()),
        ]);
    }

    public function charts(): JsonResponse
    {
        $this->authorize('viewDashboard');
        return response()->json([
            'success' => true,
            'data' => new DashboardChartsResource($this->service->getCharts()),
        ]);
    }

    public function map(): JsonResponse
    {
        $this->authorize('viewDashboard');
        return response()->json([
            'success' => true,
            'data' => new DashboardMapResource($this->service->getMapData()),
        ]);
    }
}