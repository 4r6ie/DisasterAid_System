import { FormEvent, useState } from 'react';
import { Link, useLocation, useNavigate, useSearchParams } from 'react-router-dom';
import { Activity } from 'lucide-react';
import { Button, Field, Input } from '@/components/ui';
import { SocialLoginButtons } from '@/components/auth/SocialLoginButtons';
import { useAuthStore } from '@/stores/auth.store';
import { apiErrorMessage, validationErrors } from '@/services/http';

const OAUTH_ERRORS: Record<string, string> = {
  oauth_not_configured: 'That sign-in option is not configured yet. Please contact the administrator.',
  oauth_authentication_failed: 'The sign-in was cancelled or failed. Please try again.',
  oauth_email_not_shared: 'The provider did not share an email address, so we cannot sign you in.',
};

export function LoginPage() {
  const login = useAuthStore((state) => state.login);
  const navigate = useNavigate();
  const location = useLocation();
  const [searchParams] = useSearchParams();

  const [values, setValues] = useState({ email: '', password: '', remember: false });
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [formError, setFormError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  const from = (location.state as { from?: string } | null)?.from ?? '/dashboard';
  const oauthError = OAUTH_ERRORS[searchParams.get('error') ?? ''] ?? null;

  const submit = async (event: FormEvent) => {
    event.preventDefault();
    setSubmitting(true);
    setErrors({});
    setFormError(null);

    try {
      await login(values.email, values.password, values.remember);
      navigate(from, { replace: true });
    } catch (cause) {
      setErrors(validationErrors(cause));
      setFormError(apiErrorMessage(cause, 'Unable to sign in.'));
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="flex min-h-full items-center justify-center bg-slate-100 px-4 py-10">
      <div className="w-full max-w-md">
        <div className="mb-6 flex flex-col items-center text-center">
          <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-600 text-white">
            <Activity className="h-6 w-6" />
          </div>
          <h1 className="text-xl font-semibold text-slate-900">DisasterAid System</h1>
          <p className="mt-1 text-sm text-slate-500">Sign in to the emergency response platform</p>
        </div>

        <div className="card px-6 py-6">
          <form onSubmit={submit} className="space-y-4" noValidate>
            {(formError || oauthError) && (
              <p className="rounded-lg bg-danger-50 px-3 py-2 text-sm text-danger-700">
                {formError ?? oauthError}
              </p>
            )}

            <Field label="Email address" required error={errors.email?.[0]}>
              <Input
                type="email"
                value={values.email}
                onChange={(event) => setValues((current) => ({ ...current, email: event.target.value }))}
                placeholder="you@example.com"
                autoComplete="email"
              />
            </Field>

            <Field label="Password" required error={errors.password?.[0]}>
              <Input
                type="password"
                value={values.password}
                onChange={(event) => setValues((current) => ({ ...current, password: event.target.value }))}
                placeholder="••••••••"
                autoComplete="current-password"
              />
            </Field>

            <div className="flex items-center justify-between">
              <label className="flex items-center gap-2 text-sm text-slate-600">
                <input
                  type="checkbox"
                  checked={values.remember}
                  onChange={(event) => setValues((current) => ({ ...current, remember: event.target.checked }))}
                  className="h-4 w-4 rounded border-slate-300 text-primary-600"
                />
                Remember me
              </label>

              <Link
                to="/forgot-password"
                className="text-sm font-medium text-primary-600 hover:text-primary-700"
              >
                Forgot password?
              </Link>
            </div>

            <Button type="submit" loading={submitting} className="w-full">
              Sign in
            </Button>

            <div className="relative py-1">
              <div className="absolute inset-0 flex items-center">
                <div className="w-full border-t border-slate-200" />
              </div>
              <div className="relative flex justify-center text-xs">
                <span className="bg-white px-2 text-slate-500">or continue with</span>
              </div>
            </div>

            <SocialLoginButtons />
          </form>
        </div>

        <p className="mt-4 text-center text-sm text-slate-500">
          Don&apos;t have an account?{' '}
          <Link to="/register" className="font-medium text-primary-600 hover:text-primary-700">
            Create one
          </Link>
        </p>
      </div>
    </div>
  );
}

