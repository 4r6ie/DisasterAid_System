import { useCallback, useEffect, useState } from 'react';
import { Card, Input, PageHeader, Select, Spinner } from '@/components/ui';
import { Column, DataTable } from '@/components/shared/DataTable';
import { Pagination } from '@/components/shared/Pagination';
import { Modal } from '@/components/shared/Modal';
import { Badge } from '@/components/ui';
import { auditLogService } from '@/services/resources';
import { formatDateTime } from '@/utils/format';
import type { AuditLog, PaginatedMeta, QueryParams } from '@/types';

const defaultMeta: PaginatedMeta = { current_page: 1, last_page: 1, per_page: 15, total: 0 };

export function AuditLogsPage() {
  const [data, setData] = useState<AuditLog[]>([]);
  const [meta, setMeta] = useState<PaginatedMeta>(defaultMeta);
  const [params, setParams] = useState<QueryParams>({ per_page: 15 });
  const [loading, setLoading] = useState(true);
  const [selected, setSelected] = useState<AuditLog | null>(null);

  const load = useCallback(async (next: QueryParams) => {
    setLoading(true);

    try {
      const result = await auditLogService.list(next);
      setData(result.data);
      setMeta(result.meta);
    } catch {
      setData([]);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    const handle = window.setTimeout(() => void load(params), 250);

    return () => window.clearTimeout(handle);
  }, [params, load]);

  const columns: Column<AuditLog>[] = [
    {
      key: 'created_at',
      header: 'Timestamp',
      render: (row) => formatDateTime(row.created_at),
    },
    {
      key: 'event',
      header: 'Event',
      render: (row) => <Badge tone="info">{row.event ?? '—'}</Badge>,
    },
    { key: 'description', header: 'Description', render: (row) => row.description },
    {
      key: 'subject_type',
      header: 'Subject',
      render: (row) => (row.subject_type ? `${row.subject_type} #${row.subject_id ?? '—'}` : '—'),
    },
    {
      key: 'causer_id',
      header: 'Causer',
      render: (row) => (row.causer_id ? `User #${row.causer_id}` : 'System'),
    },
  ];

  return (
    <div>
      <PageHeader
        title="Audit Logs"
        description="Immutable record of actions performed across the platform."
      />

      <Card>
        <div className="flex flex-wrap items-center gap-3 border-b border-slate-200 px-4 py-3">
          <div className="w-full max-w-sm">
            <Input
              value={(params.search as string) ?? ''}
              onChange={(event) => setParams((current) => ({ ...current, search: event.target.value, page: 1 }))}
              placeholder="Search description…"
            />
          </div>

          <Select
            value={(params.event as string) ?? ''}
            onChange={(event) =>
              setParams((current) => ({ ...current, event: event.target.value || undefined, page: 1 }))
            }
            className="w-40"
          >
            <option value="">All events</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="deleted">Deleted</option>
          </Select>
        </div>

        {loading ? (
          <div className="flex items-center justify-center px-6 py-14">
            <Spinner />
          </div>
        ) : (
          <DataTable
            columns={columns}
            data={data}
            keyExtractor={(row) => row.id}
            emptyTitle="No audit entries found"
            onSort={undefined}
          />
        )}

        <Pagination
          meta={meta}
          onPageChange={(page) => setParams((current) => ({ ...current, page }))}
        />
      </Card>

      <Modal
        open={Boolean(selected)}
        onClose={() => setSelected(null)}
        title="Audit entry"
        wide
      >
        {selected && (
          <dl className="space-y-3 text-sm">
            <div>
              <dt className="font-medium text-slate-700">Description</dt>
              <dd className="text-slate-600">{selected.description}</dd>
            </div>
            <div>
              <dt className="font-medium text-slate-700">Event</dt>
              <dd className="text-slate-600">{selected.event ?? '—'}</dd>
            </div>
            <div>
              <dt className="font-medium text-slate-700">Subject</dt>
              <dd className="text-slate-600">
                {selected.subject_type ? `${selected.subject_type} #${selected.subject_id ?? '—'}` : '—'}
              </dd>
            </div>
            <div>
              <dt className="font-medium text-slate-700">Properties</dt>
              <dd>
                <pre className="mt-1 max-h-72 overflow-auto rounded-lg bg-slate-50 p-3 text-xs text-slate-700">
                  {JSON.stringify(selected.properties ?? {}, null, 2)}
                </pre>
              </dd>
            </div>
          </dl>
        )}
      </Modal>
    </div>
  );
}
