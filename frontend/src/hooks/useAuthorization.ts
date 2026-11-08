import { useCallback, useMemo } from 'react';
import { useAuthStore } from '@/stores/auth.store';

export function useAuthorization() {
  const user = useAuthStore((state) => state.user);

  const permissions = useMemo(() => new Set(user?.permissions ?? []), [user]);
  const roles = useMemo(() => new Set(user?.roles ?? []), [user]);

  const can = useCallback(
    (permission?: string) => !permission || permissions.has(permission),
    [permissions],
  );

  const hasRole = useCallback((role: string) => roles.has(role), [roles]);

  return { can, hasRole, user };
}
