import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { volunteerService } from '@/services/resources';
import { GENDERS, VOLUNTEER_STATUSES, toOptions } from '@/utils/constants';
import { formatDate } from '@/utils/format';
import type { Volunteer } from '@/types';

const config: ResourceConfig<Volunteer> = {
  title: 'Volunteer',
  description: 'Manage volunteers, their skills and availability.',
  searchPlaceholder: 'Search volunteers…',
  list: volunteerService.list,
  create: volunteerService.create,
  update: volunteerService.update,
  remove: volunteerService.remove,
  keyExtractor: (row) => row.id,
  columns: [
    { key: 'name', header: 'Name', render: (row) => row.name },
    { key: 'email', header: 'Email' },
    { key: 'phone', header: 'Phone' },
    { key: 'address', header: 'Address', render: (row) => row.address ?? '—' },
    {
      key: 'status',
      header: 'Status',
      render: (row) => <StatusBadge status={row.status} />,
    },
    {
      key: 'joined_at',
      header: 'Joined',
      sortable: true,
      render: (row) => formatDate(row.joined_at),
    },
  ],
  fields: [
    { name: 'name', label: 'Full name', required: true },
    { name: 'email', label: 'Email', type: 'email', required: true },
    { name: 'phone', label: 'Phone' },
    { name: 'address', label: 'Address' },
    { name: 'date_of_birth', label: 'Date of birth', type: 'date' },
    {
      name: 'gender',
      label: 'Gender',
      type: 'select',
      options: toOptions(GENDERS),
    },
    { name: 'emergency_contact_name', label: 'Emergency contact' },
    { name: 'emergency_contact_phone', label: 'Emergency contact phone' },
    { name: 'notes', label: 'Notes', type: 'textarea', half: false },
  ],
  filters: [
    { name: 'status', label: 'Status', options: toOptions(VOLUNTEER_STATUSES) },
    { name: 'skill', label: 'Skill', options: [{ value: 'first_aid', label: 'First Aid' }] },
  ],
};

export function VolunteersPage() {
  return <ResourcePage config={config} />;
}
