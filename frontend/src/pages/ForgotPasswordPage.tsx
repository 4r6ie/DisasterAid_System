import { FormEvent, useState } from 'react';
import { Link } from 'react-router-dom';
import { Activity, ArrowLeft, MailCheck } from 'lucide-react';
import { Button, Field, Input } from '@/components/ui';
import { authService } from '@/services/auth.service';
import { apiErrorMessage, validationErrors } from '@/services/http';

export function ForgotPasswordPage() {
  const [email, setEmail] = useState('');
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [formError, setFormError] = useState<string | null>(null);
  const [sent, setSent] = useState(false);
  const [submitting, setSubmitting] = useState(false);

  const submit = async (event: FormEvent) => {
    event.preventDefault();
    setSubmitting(true);
    setErrors({});
    setFormError(null);

    try {
      await authService.forgotPassword(email);
      setSent(true);
    } catch (cause) {
      setErrors(validationErrors(cause));
      setFormError(apiErrorMessage(cause, 'Unable to send the reset link.'));
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
          <h1 className="text-xl font-semibold text-slate-900">Reset your password</h1>
          <p className="mt-1 text-sm text-slate-500">
            Enter the address you registered with and we will send you a reset link
          </p>
        </div>

        <div className="card px-6 py-6">
          {sent ? (
            <div className="text-center">
              <div className="mb-3 flex h-11 w-11 items-center justify-center rounded-full bg-success-100 text-success-700 mx-auto">
                <MailCheck className="h-5 w-5" />
              </div>
              <p className="text-sm text-slate-700">
                If an account exists for <span className="font-medium">{email}</span>, a reset
                link is on its way.
              </p>
              <p className="mt-2 text-xs text-slate-500">
                Check the application logs for the link while mail delivery is not configured.
              </p>
              <Button
                variant="secondary"
                className="mt-4 w-full"
                onClick={() => setSent(false)}
              >
                Use a different address
              </Button>
            </div>
          ) : (
            <form onSubmit={submit} className="space-y-4" noValidate>
              {formError && (
                <p className="rounded-lg bg-danger-50 px-3 py-2 text-sm text-danger-700">
                  {formError}
                </p>
              )}

              <Field label="Email address" required error={errors.email?.[0]}>
                <Input
                  type="email"
                  value={email}
                  onChange={(event) => setEmail(event.target.value)}
                  placeholder="you@example.com"
                  autoComplete="email"
                />
              </Field>

              <Button type="submit" loading={submitting} className="w-full">
                Send reset link
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
