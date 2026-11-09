import http from './http';

export interface GeocodeResult {
  latitude: number;
  longitude: number;
  display_name: string;
}

/**
 * Address <-> coordinate lookups backed by OpenStreetMap Nominatim.
 * Both calls throw on failure; the caller decides how to present it.
 */
export const geocodingService = {
  /** Resolve a free-text address to coordinates. */
  geocode: async (address: string): Promise<GeocodeResult> => {
    const response = await http.get('/geocoding/geocode', { params: { address } });
    return response.data.data as GeocodeResult;
  },

  /** Resolve coordinates to a human-readable address. */
  reverse: async (latitude: number, longitude: number): Promise<string> => {
    const response = await http.get('/geocoding/reverse', {
      params: { latitude, longitude },
    });
    return response.data.data.address as string;
  },
};
