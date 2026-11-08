import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { disasterService } from '@/services/resources';
import { DISASTER_TYPES, INCIDENT_STATUSES, SEVERITIES, toOptions } from '@/utils/constants';
import { formatDate, formatNumber } from '@/utils/format';
import type { DisasterIncident } from '@/types';

const config: ResourceConfig<DisasterIncident> = {
  title: 'Disaster Incident',
  description: 'Record and monitor disasters, casualties and response status.',
  searchPlaceholder: 'Search incidents…',
  list: disasterService.list,
  create: disasterService.create,
  update: disasterService.update,
  remove: disasterService.remove,
  keyExtractor: (row) => row.id,
  columns: [
    { key: 'incident_id', header: 'Incident ID', render: (row) => row.incident_id },
    { key: 'title', header: 'Title', render: (row) => row.title },
    {
      key: 'disaster_type',
      header: 'Type',
      render: (row) => row.disaster_type,
    },
    {
      key: 'severity',
      header: 'Severity',
      render: (row) => <StatusBadge status={row.severity} />,
    },
    {
      key: 'status',
      header: 'Status',
      render: (row) => <StatusBadge status={row.status} />,
    },
    {
      key: 'affected_count',
      header: 'Affected',
      render: (row) => formatNumber(row.affected_count),
    },
    {
      key: 'occurred_at',
      header: 'Occurred',
      sortable: true,
      render: (row) => formatDate(row.occurred_at),
    },
  ],
  fields: [
    { name: 'title', label: 'Title', required: true, half: false },
    {
      name: 'disaster_type',
      label: 'Disaster type',
      type: 'select',
      options: toOptions(DISASTER_TYPES),
      required: true,
    },
    {
      name: 'severity',
      label: 'Severity',
      type: 'select',
      options: toOptions(SEVERITIES),
      required: true,
      defaultValue: 'low',
    },
    {
      name: 'status',
      label: 'Status',
      type: 'select',
      options: toOptions(INCIDENT_STATUSES),
      required: true,
      defaultValue: 'reported',
    },
    { name: 'description', label: 'Description', type: 'textarea', half: false },
    { name: 'latitude', label: 'Latitude', type: 'number' },
    { name: 'longitude', label: 'Longitude', type: 'number' },
    { name: 'address', label: 'Address', half: false },
    { name: 'city', label: 'City' },
    { name: 'province', label: 'Province' },
    { name: 'occurred_at', label: 'Occurred at', type: 'date' },
  ],
  filters: [
    { name: 'disaster_type', label: 'Type', options: toOptions(DISASTER_TYPES) },
    { name: 'severity', label: 'Severity', options: toOptions(SEVERITIES) },
    { name: 'status', label: 'Status', options: toOptions(INCIDENT_STATUSES) },
  ],
};

export function DisasterIncidentsPage() {
  return <ResourcePage config={config} />;
}
