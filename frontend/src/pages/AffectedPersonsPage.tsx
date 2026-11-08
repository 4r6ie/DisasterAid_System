import { useEffect, useMemo, useState } from 'react';
import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { affectedPersonService, disasterService, evacuationCenterService } from '@/services/resources';
import { CONDITIONS, GENDERS, toOptions } from '@/utils/constants';
import { formatDate } from '@/utils/format';
import type { AffectedPerson } from '@/types';

interface Option {
  value: string;
  label: string;
}

export function AffectedPersonsPage() {
  const [disasterOptions, setDisasterOptions] = useState<Option[]>([]);
  const [centerOptions, setCenterOptions] = useState<Option[]>([]);

  useEffect(() => {
    disasterService
      .list({ per_page: 100 })
      .then((result) =>
        setDisasterOptions(
          result.data.map((item) => ({ value: String(item.id), label: `${item.incident_id} — ${item.title}` })),
        ),
      )
      .catch(() => undefined);

    evacuationCenterService
      .list({ per_page: 100 })
      .then((result) =>
        setCenterOptions(result.data.map((item) => ({ value: String(item.id), label: item.name }))),
      )
      .catch(() => undefined);
  }, []);

  const config = useMemo<ResourceConfig<AffectedPerson>>(
    () => ({
      title: 'Affected Person',
      description: 'Register and track people affected by disasters.',
      searchPlaceholder: 'Search affected persons…',
      list: affectedPersonService.list,
      create: affectedPersonService.create,
      update: affectedPersonService.update,
      remove: affectedPersonService.remove,
      keyExtractor: (row) => row.id,
      columns: [
        { key: 'name', header: 'Name', render: (row) => row.name },
        { key: 'age', header: 'Age', render: (row) => row.age ?? '—' },
        { key: 'gender', header: 'Gender', render: (row) => (row.gender ? row.gender : '—') },
        {
          key: 'condition',
          header: 'Condition',
          render: (row) => <StatusBadge status={row.condition} />,
        },
        {
          key: 'evacuation_center',
          header: 'Evacuation Center',
          render: (row) => row.evacuation_center?.name ?? '—',
        },
        {
          key: 'created_at',
          header: 'Registered',
          sortable: true,
          render: (row) => formatDate(row.created_at),
        },
      ],
      fields: [
        { name: 'name', label: 'Full name', required: true },
        { name: 'age', label: 'Age', type: 'number' },
        { name: 'gender', label: 'Gender', type: 'select', options: toOptions(GENDERS) },
        {
          name: 'condition',
          label: 'Condition',
          type: 'select',
          options: toOptions(CONDITIONS),
          required: true,
          defaultValue: 'safe',
        },
        { name: 'contact_phone', label: 'Contact phone' },
        { name: 'address', label: 'Address', type: 'textarea', half: false },
        {
          name: 'disaster_incident_id',
          label: 'Disaster incident',
          type: 'select',
          options: disasterOptions,
          required: true,
        },
        {
          name: 'evacuation_center_id',
          label: 'Evacuation center',
          type: 'select',
          options: centerOptions,
        },
        { name: 'medical_notes', label: 'Medical notes', type: 'textarea', half: false },
        { name: 'family_contact_name', label: 'Family contact' },
        { name: 'family_contact_phone', label: 'Family contact phone' },
        { name: 'notes', label: 'Notes', type: 'textarea', half: false },
      ],
      filters: [
        { name: 'condition', label: 'Condition', options: toOptions(CONDITIONS) },
      ],
    }),
    [disasterOptions, centerOptions],
  );

  return <ResourcePage config={config} />;
}
