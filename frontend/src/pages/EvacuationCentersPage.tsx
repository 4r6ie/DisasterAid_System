import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { evacuationCenterService } from '@/services/resources';
import { CENTER_STATUSES, toOptions } from '@/utils/constants';
import { formatDate, formatNumber } from '@/utils/format';
import type { EvacuationCenter } from '@/types';

const config: ResourceConfig<EvacuationCenter> = {
  title: 'Evacuation Center',
  description: 'Track shelters, capacity and current occupancy.',
  searchPlaceholder: 'Search evacuation centers…',
  list: evacuationCenterService.list,
  create: evacuationCenterService.create,
  update: evacuationCenterService.update,
  remove: evacuationCenterService.remove,
  keyExtractor: (row) => row.id,
  columns: [
    { key: 'name', header: 'Name', render: (row) => row.name },
    { key: 'address', header: 'Address', render: (row) => row.address ?? '—' },
    {
      key: 'capacity',
      header: 'Capacity',
      render: (row) => formatNumber(row.capacity),
    },
    {
      key: 'current_occupancy',
      header: 'Occupancy',
      render: (row) => formatNumber(row.current_occupancy),
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
    { name: 'name', label: 'Center name', required: true },
    { name: 'address', label: 'Address', type: 'textarea', required: true, half: false },
    { name: 'latitude', label: 'Latitude', type: 'number', half: true },
    { name: 'longitude', label: 'Longitude', type: 'number', half: true },
    { name: 'capacity', label: 'Capacity', type: 'number', required: true, half: true },
    {
      name: 'current_occupancy',
      label: 'Current occupancy',
      type: 'number',
      half: true,
      defaultValue: 0,
    },
    {
      name: 'status',
      label: 'Status',
      type: 'select',
      options: toOptions(CENTER_STATUSES),
      required: true,
      defaultValue: 'active',
    },
    { name: 'contact_person', label: 'Contact person', half: true },
    { name: 'contact_phone', label: 'Contact phone', half: true },
    { name: 'notes', label: 'Notes', type: 'textarea', half: false },
  ],
  filters: [
    { name: 'status', label: 'Status', options: toOptions(CENTER_STATUSES) },
    {
      name: 'has_availability',
      label: 'Availability',
      options: [
        { value: '1', label: 'Has availability' },
      ],
    },
  ],
};

export function EvacuationCentersPage() {
  return <ResourcePage config={config} />;
}
