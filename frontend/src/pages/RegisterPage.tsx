import { FormEvent, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Activity } from 'lucide-react';
import { Button, Field, Input, Textarea } from '@/components/ui';
import { useAuthStore } from '@/stores/auth.store';
import { apiErrorMessage, validationErrors } from '@/services/http';

export function RegisterPage() {
  const register = useAuthStore((state) => state.register);
  const navigate = useNavigate();

  const [values, setValues] = useState({
    name: '',
    email: '',
    phone: '',
    address: '',
    password: '',
    password_confirmation: '',
  });
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [formError, setFormError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  const update = (name: string, value: string) =>
    setValues((current) => ({ ...current, [name]: value }));

  const submit = async (event: FormEvent) => {
    event.preventDefault();
    setSubmitting(true);
    setErrors({});
    setFormError(null);

    try {
      await register(values);
      navigate('/dashboard', { replace: true });
    } catch (cause) {
      setErrors(validationErrors(cause));
      setFormError(apiErrorMessage(cause, 'Unable to create the account.'));
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="flex min-h-full items-center justify-center bg-slate-100 px-4 py-10">
      <div className="w-full max-w-lg">
        <div className="mb-6 flex flex-col items-center text-center">
          <div className="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-primary-600 text-white">
            <Activity className="h-6 w-6" />
          </div>
          <h1 className="text-xl font-semibold text-slate-900">Create your account</h1>
          <p className="mt-1 text-sm text-slate-500">Register to request or support emergency assistance</p>
        </div>

        <div className="card px-6 py-6">
          <form onSubmit={submit} className="space-y-4" noValidate>
            {formError && (
              <p className="rounded-lg bg-danger-50 px-3 py-2 text-sm text-danger-700">{formError}</p>
            )}

            <Field label="Full name" required error={errors.name?.[0]}>
              <Input
                value={values.name}
                onChange={(event) => update('name', event.target.value)}
                placeholder="Juan Dela Cruz"
                autoComplete="name"
              />
            </Field>

            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <Field label="Email address" required error={errors.email?.[0]}>
                <Input
                  type="email"
                  value={values.email}
                  onChange={(event) => update('email', event.target.value)}
                  placeholder="you@example.com"
                  autoComplete="email"
                />
              </Field>

              <Field label="Phone" error={errors.phone?.[0]}>
                <Input
                  value={values.phone}
                  onChange={(event) => update('phone', event.target.value)}
                  placeholder="+63 900 000 0000"
                  autoComplete="tel"
                />
              </Field>
            </div>

            <Field label="Address" error={errors.address?.[0]}>
              <Textarea
                value={values.address}
                onChange={(event) => update('address', event.target.value)}
                placeholder="Barangay, city, province"
              />
            </Field>

            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <Field label="Password" required error={errors.password?.[0]}>
                <Input
                  type="password"
                  value={values.password}
                  onChange={(event) => update('password', event.target.value)}
                  placeholder="Minimum 8 characters"
                  autoComplete="new-password"
                />
              </Field>

              <Field label="Confirm password" required error={errors.password_confirmation?.[0]}>
                <Input
                  type="password"
                  value={values.password_confirmation}
                  onChange={(event) => update('password_confirmation', event.target.value)}
                  placeholder="Repeat your password"
                  autoComplete="new-password"
                />
              </Field>
            </div>

            <Button type="submit" loading={submitting} className="w-full">
              Create account
            </Button>
          </form>
        </div>

        <p className="mt-4 text-center text-sm text-slate-500">
          Already have an account?{' '}
          <Link to="/login" className="font-medium text-primary-600 hover:text-primary-700">
            Sign in
          </Link>
        </p>
      </div>
    </div>
  );
}
