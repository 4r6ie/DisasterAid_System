import { useEffect, useMemo, useState } from 'react';
import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { disasterService, volunteerAssignmentService, volunteerService } from '@/services/resources';
import { ASSIGNMENT_ROLES, ASSIGNMENT_STATUSES, toOptions } from '@/utils/constants';
import { formatDate } from '@/utils/format';
import type { VolunteerAssignment } from '@/types';

interface Option {
  value: string;
  label: string;
}

export function VolunteerAssignmentsPage() {
  const [volunteerOptions, setVolunteerOptions] = useState<Option[]>([]);
  const [disasterOptions, setDisasterOptions] = useState<Option[]>([]);

  useEffect(() => {
    volunteerService
      .list({ per_page: 100 })
      .then((result) =>
        setVolunteerOptions(
          result.data.map((item) => ({
            value: String(item.id),
            label: item.name,
          })),
        ),
      )
      .catch(() => undefined);

    disasterService
      .list({ per_page: 100 })
      .then((result) =>
        setDisasterOptions(
          result.data.map((item) => ({ value: String(item.id), label: `${item.incident_id} — ${item.title}` })),
        ),
      )
      .catch(() => undefined);
  }, []);

  const config = useMemo<ResourceConfig<VolunteerAssignment>>(
    () => ({
      title: 'Volunteer Assignment',
      description: 'Assign volunteers to disaster response operations.',
      searchPlaceholder: 'Search assignments…',
      list: volunteerAssignmentService.list,
      create: volunteerAssignmentService.create,
      update: volunteerAssignmentService.update,
      remove: volunteerAssignmentService.remove,
      keyExtractor: (row) => row.id,
      columns: [
        {
          key: 'volunteer',
          header: 'Volunteer',
          render: (row) => row.volunteer?.name ?? '—',
        },
        {
          key: 'disaster_incident',
          header: 'Incident',
          render: (row) => row.disaster_incident?.title ?? '—',
        },
        { key: 'role', header: 'Role', render: (row) => row.role ?? '—' },
        {
          key: 'status',
          header: 'Status',
          render: (row) => <StatusBadge status={row.status} />,
        },
        {
          key: 'created_at',
          header: 'Assigned',
          sortable: true,
          render: (row) => formatDate(row.created_at),
        },
      ],
      fields: [
        {
          name: 'volunteer_id',
          label: 'Volunteer',
          type: 'select',
          options: volunteerOptions,
          required: true,
        },
        {
          name: 'disaster_incident_id',
          label: 'Disaster incident',
          type: 'select',
          options: disasterOptions,
          required: true,
        },
        {
          name: 'role',
          label: 'Role',
          type: 'select',
          options: toOptions(ASSIGNMENT_ROLES),
          required: true,
          defaultValue: 'general',
        },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          options: toOptions(ASSIGNMENT_STATUSES),
          required: true,
          defaultValue: 'assigned',
        },
        { name: 'notes', label: 'Notes', type: 'textarea', half: false },
      ],
      filters: [
        { name: 'status', label: 'Status', options: toOptions(ASSIGNMENT_STATUSES) },
        { name: 'role', label: 'Role', options: toOptions(ASSIGNMENT_ROLES) },
      ],
    }),
    [volunteerOptions, disasterOptions],
  );

  return <ResourcePage config={config} />;
}
