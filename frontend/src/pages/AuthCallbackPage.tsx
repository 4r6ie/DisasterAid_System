import { useEffect, useState } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { Activity } from 'lucide-react';
import { Spinner } from '@/components/ui';
import { TOKEN_KEY } from '@/services/http';
import { useAuthStore } from '@/stores/auth.store';

/**
 * Landing point after a Google / Facebook sign-in. The API appends the
 * Sanctum token to the redirect, we store it like any other login and then
 * continue into the application.
 */
export function AuthCallbackPage() {
  const [params] = useSearchParams();
  const navigate = useNavigate();
  const refresh = useAuthStore((state) => state.refresh);

  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const token = params.get('token');

    if (!token) {
      setError('This sign-in link is missing its token.');
      return;
    }

    localStorage.setItem(TOKEN_KEY, token);
    useAuthStore.setState({ token, isAuthenticated: true });

    refresh()
      .then(() => navigate('/dashboard', { replace: true }))
      .catch(() => setError('We could not complete the sign-in. Please try again.'));
  }, [params, refresh, navigate]);

  return (
    <div className="flex min-h-full items-center justify-center bg-slate-100 px-4 py-10">
      <div className="w-full max-w-md text-center">
        <div className="mb-6 flex flex-col items-center text-center">
          <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-600 text-white">
            <Activity className="h-6 w-6" />
          </div>
          <h1 className="text-xl font-semibold text-slate-900">Finishing sign-in</h1>
        </div>

        <div className="card px-6 py-6">
          {error ? (
            <>
              <p className="text-sm text-danger-600">{error}</p>
              <Link
                to="/login"
                className="mt-4 inline-block text-sm font-medium text-primary-600 hover:text-primary-700"
              >
                Back to sign in
              </Link>
            </>
          ) : (
            <div className="flex items-center justify-center gap-2 text-sm text-slate-600">
              <Spinner className="h-4 w-4" />
              Signing you in…
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
