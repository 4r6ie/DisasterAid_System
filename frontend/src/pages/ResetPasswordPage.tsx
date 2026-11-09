import { FormEvent, useState } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { Activity, ArrowLeft } from 'lucide-react';
import { Button, Field, Input } from '@/components/ui';
import { authService } from '@/services/auth.service';
import { apiErrorMessage, validationErrors } from '@/services/http';

export function ResetPasswordPage() {
  const [params] = useSearchParams();
  const navigate = useNavigate();
  const token = params.get('token') ?? '';
  const email = params.get('email') ?? '';

  const [values, setValues] = useState({ password: '', password_confirmation: '' });
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [formError, setFormError] = useState<string | null>(null);
  const [done, setDone] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  const update = (name: string, value: string) =>
    setValues((current) => ({ ...current, [name]: value }));

  const submit = async (event: FormEvent) => {
    event.preventDefault();
    setSubmitting(true);
    setErrors({});
    setFormError(null);

    try {
      await authService.resetPassword({
        token,
        email,
        password: values.password,
        password_confirmation: values.password_confirmation,
      });
      setDone(true);
    } catch (cause) {
      setErrors(validationErrors(cause));
      setFormError(apiErrorMessage(cause, 'Unable to reset the password.'));
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
          <h1 className="text-xl font-semibold text-slate-900">Choose a new password</h1>
          <p className="mt-1 text-sm text-slate-500">Your new password must be at least 8 characters</p>
        </div>

        <div className="card px-6 py-6">
          {!token || !email ? (
            <div className="text-center">
              <p className="text-sm text-slate-700">
                This reset link is missing its token or email address. Request a new one.
              </p>
              <Button
                variant="secondary"
                className="mt-4 w-full"
                onClick={() => navigate('/forgot-password')}
              >
                Request a new link
              </Button>
            </div>
          ) : done ? (
            <div className="text-center">
              <p className="text-sm text-slate-700">Your password has been reset.</p>
              <Button className="mt-4 w-full" onClick={() => navigate('/login')}>
                Sign in
              </Button>
            </div>
          ) : (
            <form onSubmit={submit} className="space-y-4" noValidate>
              {formError && (
                <p className="rounded-lg bg-danger-50 px-3 py-2 text-sm text-danger-700">
                  {formError}
                </p>
              )}

              <Field label="Email address" hint="Taken from your reset link.">
                <Input type="email" value={email} disabled />
              </Field>

              <Field label="New password" required error={errors.password?.[0]}>
                <Input
                  type="password"
                  value={values.password}
                  onChange={(event) => update('password', event.target.value)}
                  placeholder="Minimum 8 characters"
                  autoComplete="new-password"
                />
              </Field>

              <Field
                label="Confirm new password"
                required
                error={errors.password_confirmation?.[0]}
              >
                <Input
                  type="password"
                  value={values.password_confirmation}
                  onChange={(event) => update('password_confirmation', event.target.value)}
                  placeholder="Repeat your new password"
                  autoComplete="new-password"
                />
              </Field>

              <Button type="submit" loading={submitting} className="w-full">
                Reset password
              </Button>
            </form>
          )}
        </div>

        <p className="mt-4 text-center text-sm text-slate-500">
          <Link
            to="/login"
            className="inline-flex items-center gap-1 font-medium text-primary-600 hover:text-primary-700"
          >
            <ArrowLeft className="h-4 w-4" />
            Back to sign in
          </Link>
        </p>
           </div>
    </div>
  );
}
