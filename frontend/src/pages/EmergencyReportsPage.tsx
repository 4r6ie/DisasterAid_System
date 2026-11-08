import { useEffect, useMemo, useState } from 'react';
import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { disasterService, emergencyReportService } from '@/services/resources';
import { EMERGENCY_STATUSES, EMERGENCY_TYPES, PRIORITIES, toOptions } from '@/utils/constants';
import { formatDate, formatNumber } from '@/utils/format';
import type { EmergencyReport } from '@/types';

interface Option {
  value: string;
  label: string;
}

export function EmergencyReportsPage() {
  const [disasterOptions, setDisasterOptions] = useState<Option[]>([]);

  useEffect(() => {
    disasterService
      .list({ per_page: 100 })
      .then((result) =>
        setDisasterOptions(
          result.data.map((item) => ({ value: String(item.id), label: `${item.incident_id} — ${item.title}` })),
        ),
      )
      .catch(() => undefined);
  }, []);

  const config = useMemo<ResourceConfig<EmergencyReport>>(
    () => ({
      title: 'Emergency Report',
      description: 'Log incoming emergency reports and track their resolution.',
      searchPlaceholder: 'Search emergency reports…',
      list: emergencyReportService.list,
      create: emergencyReportService.create,
      update: emergencyReportService.update,
      remove: emergencyReportService.remove,
      keyExtractor: (row) => row.id,
      columns: [
        {
          key: 'emergency_type',
          header: 'Type',
          render: (row) => row.emergency_type,
        },
        {
          key: 'priority',
          header: 'Priority',
          render: (row) => <StatusBadge status={row.priority} />,
        },
        {
          key: 'status',
          header: 'Status',
          render: (row) => <StatusBadge status={row.status} />,
        },
        {
          key: 'people_needing_help',
          header: 'People',
          render: (row) => formatNumber((row as unknown as Record<string, number>).people_needing_help ?? 0),
        },
        { key: 'address', header: 'Address', render: (row) => row.address ?? '—' },
        {
          key: 'created_at',
          header: 'Reported',
          sortable: true,
          render: (row) => formatDate(row.created_at),
        },
      ],
      fields: [
        {
          name: 'emergency_type',
          label: 'Emergency type',
          type: 'select',
          options: toOptions(EMERGENCY_TYPES),
          required: true,
        },
        {
          name: 'priority',
          label: 'Priority',
          type: 'select',
          options: toOptions(PRIORITIES),
          required: true,
          defaultValue: 'medium',
        },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          options: toOptions(EMERGENCY_STATUSES),
          required: true,
          defaultValue: 'pending',
        },
        { name: 'description', label: 'Description', type: 'textarea', half: false },
        { name: 'people_needing_help', label: 'People needing help', type: 'number', defaultValue: 1 },
        { name: 'latitude', label: 'Latitude', type: 'number' },
        { name: 'longitude', label: 'Longitude', type: 'number' },
        { name: 'address', label: 'Address', half: false },
        { name: 'contact_name', label: 'Contact name' },
        { name: 'contact_phone', label: 'Contact phone' },
        {
          name: 'disaster_incident_id',
          label: 'Related incident',
          type: 'select',
          options: disasterOptions,
        },
      ],
      filters: [
        { name: 'emergency_type', label: 'Type', options: toOptions(EMERGENCY_TYPES) },
        { name: 'priority', label: 'Priority', options: toOptions(PRIORITIES) },
        { name: 'status', label: 'Status', options: toOptions(EMERGENCY_STATUSES) },
      ],
    }),
    [disasterOptions],
  );

  return <ResourcePage config={config} />;
}
