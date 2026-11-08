import { useEffect, useMemo, useState } from 'react';
import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { announcementService, disasterService } from '@/services/resources';
import { ANNOUNCEMENT_TYPES, AUDIENCES, PRIORITIES, toOptions } from '@/utils/constants';
import { formatDateTime } from '@/utils/format';
import type { Announcement } from '@/types';

interface Option {
  value: string;
  label: string;
}

export function AnnouncementsPage() {
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

  const config = useMemo<ResourceConfig<Announcement>>(
    () => ({
      title: 'Announcement',
      description: 'Publish public advisories, alerts and updates.',
      searchPlaceholder: 'Search announcements…',
      list: announcementService.list,
      create: announcementService.create,
      update: announcementService.update,
      remove: announcementService.remove,
      keyExtractor: (row) => row.id,
      columns: [
        { key: 'title', header: 'Title', render: (row) => row.title },
        {
          key: 'type',
          header: 'Type',
          render: (row) => <StatusBadge status={row.type} />,
        },
        {
          key: 'priority',
          header: 'Priority',
          render: (row) => <StatusBadge status={row.priority} />,
        },
        { key: 'audience', header: 'Audience', render: (row) => row.audience },
        {
          key: 'is_published',
          header: 'Published',
          render: (row) => (row.is_published ? 'Yes' : 'Draft'),
        },
        {
          key: 'published_at',
          header: 'Published At',
          render: (row) => formatDateTime(row.published_at),
        },
      ],
      fields: [
        { name: 'title', label: 'Title', required: true, half: false },
        { name: 'content', label: 'Message', type: 'textarea', required: true, half: false },
        {
          name: 'type',
          label: 'Type',
          type: 'select',
          options: toOptions(ANNOUNCEMENT_TYPES),
          required: true,
          defaultValue: 'notice',
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
          name: 'audience',
          label: 'Audience',
          type: 'select',
          options: toOptions(AUDIENCES),
          required: true,
          defaultValue: 'public',
        },
        {
          name: 'disaster_incident_id',
          label: 'Related incident',
          type: 'select',
          options: disasterOptions,
        },
        {
          name: 'is_published',
          label: 'Publish immediately',
          type: 'checkbox',
          half: false,
        },
      ],
      filters: [
        { name: 'type', label: 'Type', options: toOptions(ANNOUNCEMENT_TYPES) },
        { name: 'priority', label: 'Priority', options: toOptions(PRIORITIES) },
        { name: 'audience', label: 'Audience', options: toOptions(AUDIENCES) },
      ],
    }),
    [disasterOptions],
  );

  return <ResourcePage config={config} />;
}
