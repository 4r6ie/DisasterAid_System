import { useMemo, useRef, useState } from 'react';
import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { Button, Field, Textarea } from '@/components/ui';
import { Modal } from '@/components/shared/Modal';
import { assistanceRequestService } from '@/services/resources';
import { useToast } from '@/hooks/useToast';
import { apiErrorMessage } from '@/services/http';
import { ASSISTANCE_STATUSES, ASSISTANCE_TYPES, URGENCIES, toOptions } from '@/utils/constants';
import { formatDate, formatNumber } from '@/utils/format';
import type { AssistanceRequest } from '@/types';

export function AssistanceRequestsPage() {
  const toast = useToast();
  const [rejecting, setRejecting] = useState<AssistanceRequest | null>(null);
  const [reason, setReason] = useState('');
  const [processing, setProcessing] = useState(false);
  const [rejectError, setRejectError] = useState<string | null>(null);
  const reloadRef = useRef<() => Promise<void>>(() => Promise.resolve());

  const runAction = async (
    row: AssistanceRequest,
    action: (id: number | string) => Promise<unknown>,
    reload: () => Promise<void>,
    message: string,
  ) => {
    setProcessing(true);

    try {
      await action(row.id);
      toast.notify(message);
      await reload();
    } catch (cause) {
      toast.notify(apiErrorMessage(cause, 'Action failed.'), 'error');
    } finally {
      setProcessing(false);
    }
  };

  const submitRejection = async () => {
    if (!rejecting) return;

    setProcessing(true);
    setRejectError(null);

    try {
      await assistanceRequestService.reject(rejecting.id, reason);
      toast.notify('Assistance request rejected');
      setRejecting(null);
      setReason('');
      await reloadRef.current();
    } catch (cause) {
      setRejectError(apiErrorMessage(cause, 'Unable to reject the request.'));
    } finally {
      setProcessing(false);
    }
  };

  const config = useMemo<ResourceConfig<AssistanceRequest>>(
    () => ({
      title: 'Assistance Request',
      description: 'Receive, approve, dispatch and complete assistance requests.',
      searchPlaceholder: 'Search assistance requests…',
      list: assistanceRequestService.list,
      create: assistanceRequestService.create,
      update: assistanceRequestService.update,
      remove: assistanceRequestService.remove,
      keyExtractor: (row) => row.id,
      columns: [
        { key: 'request_number', header: 'Request #', render: (row) => row.request_number },
        {
          key: 'assistance_type',
          header: 'Type',
          render: (row) => row.assistance_type,
        },
        {
          key: 'quantity',
          header: 'Quantity',
          render: (row) => `${formatNumber(row.quantity)} ${row.unit}`,
        },
        {
          key: 'urgency',
          header: 'Urgency',
          render: (row) => <StatusBadge status={row.urgency} />,
        },
        {
          key: 'status',
          header: 'Status',
          render: (row) => <StatusBadge status={row.status} />,
        },
        {
          key: 'requester',
          header: 'Requester',
          render: (row) => row.requester?.name ?? '—',
        },
        {
          key: 'created_at',
          header: 'Requested',
          sortable: true,
          render: (row) => formatDate(row.created_at),
        },
      ],
      fields: [
        {
          name: 'assistance_type',
          label: 'Assistance type',
          type: 'select',
          options: toOptions(ASSISTANCE_TYPES),
          required: true,
        },
        { name: 'quantity', label: 'Quantity', type: 'number', required: true, defaultValue: 1 },
        { name: 'unit', label: 'Unit', required: true, defaultValue: 'units' },
        {
          name: 'urgency',
          label: 'Urgency',
          type: 'select',
          options: toOptions(URGENCIES),
          required: true,
          defaultValue: 'medium',
        },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          options: toOptions(ASSISTANCE_STATUSES),
          required: true,
          defaultValue: 'pending',
        },
        { name: 'description', label: 'Description', type: 'textarea', half: false },
        { name: 'latitude', label: 'Latitude', type: 'number' },
        { name: 'longitude', label: 'Longitude', type: 'number' },
        { name: 'address', label: 'Address', half: false },
        { name: 'contact_name', label: 'Contact name' },
        { name: 'contact_phone', label: 'Contact phone' },
      ],
      filters: [
        { name: 'assistance_type', label: 'Type', options: toOptions(ASSISTANCE_TYPES) },
        { name: 'urgency', label: 'Urgency', options: toOptions(URGENCIES) },
        { name: 'status', label: 'Status', options: toOptions(ASSISTANCE_STATUSES) },
      ],
      rowActions: (row, reload) => (
        <div className="flex flex-wrap items-center justify-end gap-1">
          {row.status === 'pending' && (
            <Button
              size="sm"
              variant="secondary"
              loading={processing}
              onClick={() =>
                runAction(row, assistanceRequestService.approve, reload, 'Request approved')
              }
            >
              Approve
            </Button>
          )}
          {row.status === 'approved' && (
            <Button
              size="sm"
              variant="secondary"
              loading={processing}
              onClick={() =>
                runAction(row, assistanceRequestService.dispatch, reload, 'Request dispatched')
              }
            >
              Dispatch
            </Button>
          )}
          {(row.status === 'approved' || row.status === 'dispatched') && (
            <Button
              size="sm"
              variant="secondary"
              loading={processing}
              onClick={() =>
                runAction(row, assistanceRequestService.complete, reload, 'Request completed')
              }
            >
              Complete
            </Button>
          )}
          {(row.status === 'pending' || row.status === 'approved') && (
            <Button
              size="sm"
              variant="ghost"
              loading={processing}
              onClick={() => {
                setRejecting(row);
                setReason('');
                setRejectError(null);
                reloadRef.current = reload;
              }}
            >
              Reject
            </Button>
          )}
        </div>
      ),
    }),
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [],
  );

  return (
    <>
      <ResourcePage config={config} />

      <Modal
        open={Boolean(rejecting)}
        onClose={() => setRejecting(null)}
        title="Reject assistance request"
        description={rejecting ? `Request ${rejecting.request_number}` : undefined}
      >
        <Field label="Reason for rejection" required error={rejectError ?? undefined}>
          <Textarea
            value={reason}
            onChange={(event) => setReason(event.target.value)}
            placeholder="Explain why this request is being rejected"
          />
        </Field>

        <div className="mt-6 flex justify-end gap-2">
          <Button variant="secondary" onClick={() => setRejecting(null)}>
            Cancel
          </Button>
          <Button variant="danger" onClick={submitRejection} loading={processing}>
            Reject request
          </Button>
        </div>
      </Modal>
    </>
  );
}
