import { FormEvent, useState } from 'react';
import { Button, Card, CardBody, CardHeader, Field, Input, Textarea } from '@/components/ui';
import { useAuthStore } from '@/stores/auth.store';
import { useToast } from '@/hooks/useToast';
import { authService } from '@/services/auth.service';
import { apiErrorMessage, validationErrors } from '@/services/http';

export function ProfilePage() {
  const { user, logout } = useAuthStore();
  const toast = useToast();

  const [profile, setProfile] = useState({
    name: user?.name ?? '',
    phone: user?.phone ?? '',
    address: user?.address ?? '',
  });
  const [profileErrors, setProfileErrors] = useState<Record<string, string[]>>({});
  const [savingProfile, setSavingProfile] = useState(false);

  const [password, setPassword] = useState({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
  });
  const [passwordErrors, setPasswordErrors] = useState<Record<string, string[]>>({});
  const [savingPassword, setSavingPassword] = useState(false);

  const saveProfile = async (event: FormEvent) => {
    event.preventDefault();
    setSavingProfile(true);
    setProfileErrors({});

    try {
      await authService.updateProfile(profile);
      toast.notify('Profile updated successfully');
    } catch (cause) {
      setProfileErrors(validationErrors(cause));
      toast.notify(apiErrorMessage(cause, 'Unable to update the profile.'), 'error');
    } finally {
      setSavingProfile(false);
    }
  };

  const changePassword = async (event: FormEvent) => {
    event.preventDefault();
    setSavingPassword(true);
    setPasswordErrors({});

    try {
      await authService.changePassword(password);
      toast.notify('Password changed successfully');
      setPassword({ current_password: '', new_password: '', new_password_confirmation: '' });
    } catch (cause) {
      setPasswordErrors(validationErrors(cause));
      toast.notify(apiErrorMessage(cause, 'Unable to change the password.'), 'error');
    } finally {
      setSavingPassword(false);
    }
  };

  return (
    <div className="mx-auto max-w-3xl space-y-6">
      <div>
        <h1 className="text-2xl font-semibold tracking-tight text-slate-900">My Profile</h1>
        <p className="mt-1 text-sm text-slate-500">Manage your account details and security.</p>
      </div>

      <Card>
        <CardHeader title="Account details" description="Your personal information" />
        <CardBody>
          <form onSubmit={saveProfile} className="space-y-4" noValidate>
            <Field label="Full name" required error={profileErrors.name?.[0]}>
              <Input
                value={profile.name}
                onChange={(event) => setProfile((current) => ({ ...current, name: event.target.value }))}
              />
            </Field>

            <Field label="Email address" hint="Email addresses cannot be changed.">
              <Input value={user?.email ?? ''} disabled />
            </Field>

            <Field label="Phone" error={profileErrors.phone?.[0]}>
              <Input
                value={profile.phone}
                onChange={(event) => setProfile((current) => ({ ...current, phone: event.target.value }))}
              />
            </Field>

            <Field label="Address" error={profileErrors.address?.[0]}>
              <Textarea
                value={profile.address}
                onChange={(event) => setProfile((current) => ({ ...current, address: event.target.value }))}
              />
            </Field>

            <div className="flex justify-end">
              <Button type="submit" loading={savingProfile}>
                Save changes
              </Button>
            </div>
          </form>
        </CardBody>
      </Card>

      <Card>
        <CardHeader title="Change password" description="Use a strong, unique password" />
        <CardBody>
          <form onSubmit={changePassword} className="space-y-4" noValidate>
            <Field label="Current password" required error={passwordErrors.current_password?.[0]}>
              <Input
                type="password"
                value={password.current_password}
                onChange={(event) =>
                  setPassword((current) => ({ ...current, current_password: event.target.value }))
                }
                autoComplete="current-password"
              />
            </Field>

            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <Field label="New password" required error={passwordErrors.new_password?.[0]}>
                <Input
                  type="password"
                  value={password.new_password}
                  onChange={(event) =>
                    setPassword((current) => ({ ...current, new_password: event.target.value }))
                  }
                  autoComplete="new-password"
                />
              </Field>

              <Field
                label="Confirm new password"
                required
                error={passwordErrors.new_password_confirmation?.[0]}
              >
                <Input
                  type="password"
                  value={password.new_password_confirmation}
                  onChange={(event) =>
                    setPassword((current) => ({
                      ...current,
                      new_password_confirmation: event.target.value,
                    }))
                  }
                  autoComplete="new-password"
                />
              </Field>
            </div>

            <div className="flex justify-end">
              <Button type="submit" loading={savingPassword}>
                Update password
              </Button>
            </div>
          </form>
        </CardBody>
      </Card>

      <Card>
        <CardHeader title="Session" description="End your current session on this device" />
        <CardBody>
          <div className="flex justify-end">
            <Button variant="secondary" onClick={() => void logout()}>
              Sign out
            </Button>
          </div>
        </CardBody>
      </Card>
    </div>
  );
}
