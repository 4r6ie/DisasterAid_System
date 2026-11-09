import { Chrome, Facebook } from 'lucide-react';
import { Button } from '@/components/ui';

/**
 * The gateway proxies /oauth/* to the API, which redirects the browser to
 * the provider and back to /auth/callback with a token.
 */
const providers = [
  { id: 'google', label: 'Continue with Google', Icon: Chrome },
  { id: 'facebook', label: 'Continue with Facebook', Icon: Facebook },
] as const;

export function SocialLoginButtons() {
  return (
    <div className="space-y-2">
      {providers.map(({ id, label, Icon }) => (
        <Button
          key={id}
          type="button"
          variant="secondary"
          className="w-full justify-center gap-2"
          onClick={() => {
            window.location.href = `/oauth/${id}/redirect`;
          }}
        >
          <Icon className="h-4 w-4" />
          {label}
        </Button>
      ))}
    </div>
  );
}
