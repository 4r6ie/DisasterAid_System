import { titleCase } from './format';

export const DISASTER_TYPES = [
  'earthquake', 'flood', 'typhoon', 'fire', 'landslide', 'volcanic', 'tsunami', 'other',
] as const;

export const SEVERITIES = ['low', 'moderate', 'high', 'critical'] as const;
export const PRIORITIES = ['low', 'medium', 'high', 'critical'] as const;
export const URGENCIES = ['low', 'medium', 'high', 'critical'] as const;

export const INCIDENT_STATUSES = [
  'reported', 'verified', 'active', 'responding', 'contained', 'resolved',
] as const;

export const EMERGENCY_TYPES = ['medical', 'fire', 'rescue', 'evacuation', 'other'] as const;
export const EMERGENCY_STATUSES = ['pending', 'assigned', 'in_progress', 'resolved', 'cancelled'] as const;

export const ASSISTANCE_TYPES = ['food', 'water', 'medicine', 'shelter', 'clothing', 'other'] as const;
export const ASSISTANCE_STATUSES = ['pending', 'approved', 'dispatched', 'completed', 'rejected', 'cancelled'] as const;

export const CENTER_STATUSES = ['active', 'inactive', 'full', 'maintenance'] as const;

export const INVENTORY_CATEGORIES = [
  'food', 'water', 'medicine', 'shelter', 'clothing', 'hygiene', 'tools', 'other',
] as const;
export const INVENTORY_STATUSES = ['active', 'inactive', 'discontinued'] as const;
export const INVENTORY_TRANSACTION_TYPES = [
  'in', 'out', 'distribute', 'adjustment', 'expired', 'damaged', 'returned',
] as const;
export const STOCK_IN_REFERENCES = ['purchase', 'donation', 'transfer', 'return'] as const;
export const STOCK_OUT_REFERENCES = ['usage', 'transfer', 'expired', 'damaged', 'adjustment'] as const;

export const RESPONDER_STATUSES = ['available', 'on_duty', 'off_duty', 'on_leave', 'inactive'] as const;
export const SPECIALIZATIONS = [
  'medical', 'fire', 'rescue', 'police', 'logistics', 'coordination', 'communications',
] as const;

export const VOLUNTEER_STATUSES = ['available', 'busy', 'inactive', 'on_leave'] as const;
export const ASSIGNMENT_ROLES = [
  'team_leader', 'medical', 'logistics', 'rescue', 'communication', 'general',
] as const;
export const ASSIGNMENT_STATUSES = ['assigned', 'active', 'completed', 'cancelled'] as const;

export const CONDITIONS = ['safe', 'injured', 'critical', 'missing', 'deceased', 'rescued'] as const;
export const GENDERS = ['male', 'female', 'other'] as const;

export const ANNOUNCEMENT_TYPES = ['alert', 'advisory', 'notice', 'update', 'evacuation', 'weather'] as const;
export const AUDIENCES = ['public', 'responders', 'staff', 'volunteers', 'affected'] as const;

export const ROLES = ['super_admin', 'admin', 'responder', 'volunteer', 'user'] as const;

export function toOptions(values: readonly string[]) {
  return values.map((value) => ({ value, label: titleCase(value) }));
}
