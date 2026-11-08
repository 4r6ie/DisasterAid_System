import { Badge, BadgeTone } from '@/components/ui';
import { titleCase } from '@/utils/format';

const toneMap: Record<string, BadgeTone> = {
  active: 'success',
  available: 'success',
  resolved: 'success',
  completed: 'success',
  completed_at: 'success',
  verified: 'info',
  responding: 'info',
  dispatched: 'info',
  in_progress: 'warning',
  pending: 'warning',
  assigned: 'warning',
  full: 'warning',
  maintenance: 'warning',
  critical: 'danger',
  rejected: 'danger',
  cancelled: 'danger',
  deceased: 'danger',
  missing: 'danger',
  injured: 'warning',
  inactive: 'neutral',
  discontinued: 'neutral',
  off_duty: 'neutral',
  on_leave: 'neutral',
  busy: 'warning',
  reported: 'info',
  contained: 'info',
  safe: 'success',
};

export function StatusBadge({ status }: { status?: string | null }) {
  const key = (status ?? '').toLowerCase();

  return <Badge tone={toneMap[key] ?? 'default'}>{titleCase(status)}</Badge>;
}
