import { useState } from 'react';
import { Crosshair, MapPin } from 'lucide-react';
import { geocodingService } from '@/services/geocoding.service';
import { apiErrorMessage } from '@/services/http';

interface FillCoordinatesButtonProps {
  address: string;
  onResolved: (latitude: number, longitude: number) => void;
}

/**
 * Resolves the address above into latitude/longitude and fills those fields.
 */
export function FillCoordinatesButton({ address, onResolved }: FillCoordinatesButtonProps) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const lookup = async () => {
    setLoading(true);
    setError(null);

    try {
      const result = await geocodingService.geocode(address);
      onResolved(result.latitude, result.longitude);
    } catch (cause) {
      setError(apiErrorMessage(cause, 'Unable to look up that address.'));
    } finally {
      setLoading(false);
    }
  };

  const ready = address.trim().length >= 3;

  return (
    <div className="mt-2">
      <button
        type="button"
        onClick={lookup}
        disabled={loading || !ready}
        className="inline-flex items-center gap-1.5 text-xs font-medium text-primary-600 hover:text-primary-700 disabled:cursor-not-allowed disabled:opacity-50"
      >
        <Crosshair className="h-3.5 w-3.5" />
        {loading ? 'Looking up…' : 'Fill coordinates from address'}
      </button>
      {error && <p className="mt-1 text-xs text-danger-600">{error}</p>}
    </div>
  );
}

interface FillAddressButtonProps {
  latitude: string | number;
  longitude: string | number;
  onResolved: (address: string) => void;
}

/**
 * Resolves the latitude/longitude fields into an address and fills the
 * address field.
 */
export function FillAddressButton({ latitude, longitude, onResolved }: FillAddressButtonProps) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const lat = typeof latitude === 'number' ? latitude : parseFloat(latitude);
  const lng = typeof longitude === 'number' ? longitude : parseFloat(longitude);
  const ready = Number.isFinite(lat) && Number.isFinite(lng);

  const lookup = async () => {
    setLoading(true);
    setError(null);

    try {
      const address = await geocodingService.reverse(lat, lng);
      onResolved(address);
    } catch (cause) {
      setError(apiErrorMessage(cause, 'Unable to look up those coordinates.'));
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="mt-2">
      <button
        type="button"
        onClick={lookup}
        disabled={loading || !ready}
        className="inline-flex items-center gap-1.5 text-xs font-medium text-primary-600 hover:text-primary-700 disabled:cursor-not-allowed disabled:opacity-50"
      >
        <MapPin className="h-3.5 w-3.5" />
        {loading ? 'Looking up…' : 'Fill address from coordinates'}
      </button>
      {error && <p className="mt-1 text-xs text-danger-600">{error}</p>}
    </div>
  );
}
