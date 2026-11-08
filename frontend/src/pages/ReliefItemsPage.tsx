import { useMemo, useRef, useState } from 'react';
import { ResourcePage, ResourceConfig } from '@/components/shared/ResourcePage';
import { StatusBadge } from '@/components/shared/StatusBadge';
import { Button, Field, Input, Select, Textarea } from '@/components/ui';
import { Modal } from '@/components/shared/Modal';
import { reliefItemService } from '@/services/resources';
import { useToast } from '@/hooks/useToast';
import { apiErrorMessage } from '@/services/http';
import {
  INVENTORY_CATEGORIES,
  INVENTORY_STATUSES,
  STOCK_IN_REFERENCES,
  STOCK_OUT_REFERENCES,
  toOptions,
} from '@/utils/constants';
import { formatDate, formatNumber } from '@/utils/format';
import type { ReliefItem } from '@/types';

type TransactionType = 'in' | 'out' | 'distribute';

const transactionLabels: Record<TransactionType, string> = {
  in: 'Stock In',
  out: 'Stock Out',
  distribute: 'Distribute',
};

export function ReliefItemsPage() {
  const toast = useToast();
  const reloadRef = useRef<() => Promise<void>>(() => Promise.resolve());

  const [transaction, setTransaction] = useState<{
    item: ReliefItem;
    type: TransactionType;
  } | null>(null);
  const [quantity, setQuantity] = useState('1');
  const [referenceType, setReferenceType] = useState('purchase');
  const [notes, setNotes] = useState('');
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const openTransaction = (item: ReliefItem, type: TransactionType) => {
    setTransaction({ item, type });
    setQuantity('1');
    setReferenceType(type === 'in' ? 'purchase' : type === 'out' ? 'usage' : 'distribution');
    setNotes('');
    setError(null);
  };

  const submitTransaction = async () => {
    if (!transaction) return;

    setSaving(true);
    setError(null);

    const payload = {
      quantity: Number(quantity),
      reference_type: referenceType,
      notes,
    };

    try {
      const { item, type } = transaction;

      if (type === 'in') {
        await reliefItemService.stockIn(item.id, payload);
      } else if (type === 'out') {
        await reliefItemService.stockOut(item.id, payload);
      } else {
        await reliefItemService.distribute(item.id, payload);
      }

      toast.notify(`${transactionLabels[type]} recorded successfully`);
      setTransaction(null);
      await reloadRef.current();
    } catch (cause) {
      setError(apiErrorMessage(cause, 'Unable to record the transaction.'));
    } finally {
      setSaving(false);
    }
  };

  const config = useMemo<ResourceConfig<ReliefItem>>(
    () => ({
      title: 'Relief Item',
      description: 'Manage relief goods, stock levels and expiry dates.',
      searchPlaceholder: 'Search relief items…',
      list: reliefItemService.list,
      create: reliefItemService.create,
      update: reliefItemService.update,
      remove: reliefItemService.remove,
      keyExtractor: (row) => row.id,
      columns: [
        { key: 'name', header: 'Name', render: (row) => row.name },
        {
          key: 'category',
          header: 'Category',
          render: (row) => row.category,
        },
        {
          key: 'quantity',
          header: 'Quantity',
          render: (row) => `${formatNumber(row.quantity)} ${row.unit}`,
        },
        {
          key: 'min_stock',
          header: 'Min. Stock',
          render: (row) => formatNumber(row.min_stock),
        },
        {
          key: 'expiry_date',
          header: 'Expires',
          render: (row) => formatDate(row.expiry_date),
        },
        {
          key: 'status',
          header: 'Status',
          render: (row) => <StatusBadge status={row.status} />,
        },
      ],
      fields: [
        { name: 'name', label: 'Item name', required: true, half: false },
        {
          name: 'category',
          label: 'Category',
          type: 'select',
          options: toOptions(INVENTORY_CATEGORIES),
          required: true,
        },
        { name: 'unit', label: 'Unit', required: true, defaultValue: 'units' },
        { name: 'quantity', label: 'Quantity', type: 'number', required: true, defaultValue: 0 },
        { name: 'min_stock', label: 'Minimum stock', type: 'number', required: true, defaultValue: 10 },
        { name: 'unit_cost', label: 'Unit cost', type: 'currency' },
        { name: 'location', label: 'Storage location' },
        { name: 'batch_number', label: 'Batch number' },
        { name: 'expiry_date', label: 'Expiry date', type: 'date' },
        {
          name: 'status',
          label: 'Status',
          type: 'select',
          options: toOptions(INVENTORY_STATUSES),
          required: true,
          defaultValue: 'active',
        },
        { name: 'description', label: 'Description', type: 'textarea', half: false },
      ],
      filters: [
        { name: 'category', label: 'Category', options: toOptions(INVENTORY_CATEGORIES) },
        { name: 'status', label: 'Status', options: toOptions(INVENTORY_STATUSES) },
      ],
      rowActions: (row, reload) => {
        reloadRef.current = reload;

        return (
          <div className="flex flex-wrap items-center justify-end gap-1">
            <Button size="sm" variant="secondary" onClick={() => openTransaction(row, 'in')}>
              Stock In
            </Button>
            <Button size="sm" variant="secondary" onClick={() => openTransaction(row, 'out')}>
              Stock Out
            </Button>
            <Button size="sm" variant="secondary" onClick={() => openTransaction(row, 'distribute')}>
              Distribute
            </Button>
          </div>
        );
      },
    }),
    [],
  );

  return (
    <>
      <ResourcePage config={config} />

      <Modal
        open={Boolean(transaction)}
        onClose={() => setTransaction(null)}
        title={
          transaction
            ? `${transactionLabels[transaction.type]} — ${transaction.item.name}`
            : 'Record inventory movement'
        }
        description="Record an inventory movement"
      >
        <div className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            <Field label="Quantity" required>
              <Input
                type="number"
                min={1}
                value={quantity}
                onChange={(event) => setQuantity(event.target.value)}
              />
            </Field>

            <Field label="Reference">
              <Select value={referenceType} onChange={(event) => setReferenceType(event.target.value)}>
                {toOptions(
                  transaction?.type === 'in'
                    ? STOCK_IN_REFERENCES
                    : STOCK_OUT_REFERENCES,
                ).map((option) => (
                  <option key={option.value} value={option.value}>
                    {option.label}
                  </option>
                ))}
              </Select>
            </Field>
          </div>

          <Field label="Remarks">
            <Textarea
              value={notes}
              onChange={(event) => setNotes(event.target.value)}
              placeholder="Optional notes about this transaction"
            />
          </Field>

          {error && (
            <p className="rounded-lg bg-danger-50 px-3 py-2 text-sm text-danger-700">{error}</p>
          )}
        </div>

        <div className="mt-6 flex justify-end gap-2">
          <Button variant="secondary" onClick={() => setTransaction(null)}>
            Cancel
          </Button>
          <Button onClick={submitTransaction} loading={saving}>
            Save transaction
          </Button>
        </div>
      </Modal>
    </>
  );
}
