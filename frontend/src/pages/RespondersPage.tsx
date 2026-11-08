import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { Badge } from '@/components/ui';
import { responderService } from '@/services/resources';
import { RESPONDER_STATUSES, SPECIALIZATIONS, toOptions } from '@/utils/constants';
import { formatDate, titleCase } from '@/utils/format';
import type { Responder } from '@/types';

const config: ResourceConfig<Responder> = {
  title: 'Responder',
  description: 'Manage response personnel, specializations and availability.',
  searchPlaceholder: 'Search responders…',
  list: responderService.list,
  create: responderService.create,
  update: responderService.update,
  remove: responderService.remove,
  keyExtractor: (row) => row.id,
  columns: [
    {
      key: 'employee_id',
      header: 'Employee ID',
      render: (row) => row.employee_id,
    },
    { key: 'name', header: 'Name', render: (row) => row.name },
    { key: 'phone', header: 'Phone' },
    {
      key: 'specialization',
      header: 'Specialization',
      render: (row) => (
        <Badge tone="info">{row.specialization ? titleCase(row.specialization) : '—'}</Badge>
      ),
    },
    {
      key: 'status',
      header: 'Status',
      render: (row) => <StatusBadge status={row.status} />,
    },
    {
      key: 'created_at',
      header: 'Added',
      sortable: true,
      render: (row) => formatDate(row.created_at),
    },
  ],
  fields: [
    { name: 'employee_id', label: 'Employee ID', required: true },
    { name: 'name', label: 'Full name', required: true },
    { name: 'email', label: 'Email', type: 'email', required: true },
    { name: 'phone', label: 'Phone' },
    {
      name: 'specialization',
      label: 'Specialization',
      type: 'select',
      options: toOptions(SPECIALIZATIONS),
      required: true,
    },
    { name: 'rank', label: 'Rank' },
    {
      name: 'status',
      label: 'Status',
      type: 'select',
      options: toOptions(RESPONDER_STATUSES),
      required: true,
      defaultValue: 'available',
    },
    { name: 'address', label: 'Address' },
    { name: 'date_of_birth', label: 'Date of birth', type: 'date' },
    { name: 'hired_at', label: 'Hired at', type: 'date' },
  ],
  filters: [
    { name: 'status', label: 'Status', options: toOptions(RESPONDER_STATUSES) },
    { name: 'specialization', label: 'Specialization', options: toOptions(SPECIALIZATIONS) },
  ],
};

export function RespondersPage() {
  return <ResourcePage config={config} />;
}
