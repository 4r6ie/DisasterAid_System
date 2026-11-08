import { ReactNode, useCallback, useEffect, useMemo, useState } from 'react';
import { Pencil, Plus, Search, Trash2 } from 'lucide-react';
import {
  Button,
  Card,
  Field,
  Input,
  PageHeader,
  Select,
  Textarea,
} from '@/components/ui';
import { Modal } from '@/components/shared/Modal';
import { Column, DataTable } from '@/components/shared/DataTable';
import { Pagination } from '@/components/shared/Pagination';
import { usePaginated } from '@/hooks/usePaginated';
import { useToast } from '@/hooks/useToast';
import { apiErrorMessage, validationErrors } from '@/services/http';
import type { PaginatedResponse, QueryParams } from '@/types';

export interface ResourceField {
  name: string;
  label: string;
  type?: 'text' | 'email' | 'number' | 'textarea' | 'select' | 'date' | 'checkbox' | 'currency';
  options?: { value: string; label: string }[];
  required?: boolean;
  placeholder?: string;
  half?: boolean;
  hint?: string;
  defaultValue?: unknown;
}

export interface ResourceFilter {
  name: string;
  label: string;
  options: { value: string; label: string }[];
}

export interface ResourceConfig<T> {
  title: string;
  description?: string;
  createLabel?: string;
  searchPlaceholder?: string;
  list: (params: QueryParams) => Promise<PaginatedResponse<T>>;
  create: (payload: Record<string, unknown>) => Promise<unknown>;
  update: (id: string | number, payload: Record<string, unknown>) => Promise<unknown>;
  remove: (id: string | number) => Promise<void>;
  columns: Column<T>[];
  fields: ResourceField[];
  filters?: ResourceFilter[];
  keyExtractor: (row: T) => string | number;
  rowActions?: (row: T, reload: () => Promise<void>) => ReactNode;
  canCreate?: boolean;
  canEdit?: boolean;
  canDelete?: boolean;
}

function defaultValues(fields: ResourceField[]): Record<string, unknown> {
  return Object.fromEntries(fields.map((field) => [field.name, field.defaultValue ?? '']));
}

function fieldValue(row: Record<string, unknown>, name: string): unknown {
  return row[name] ?? '';
}

export function ResourcePage<T>({ config }: { config: ResourceConfig<T> }) {
  const toast = useToast();
  const [modalOpen, setModalOpen] = useState(false);
  const [editing, setEditing] = useState<T | null>(null);
  const [deleting, setDeleting] = useState<T | null>(null);
  const [values, setValues] = useState<Record<string, unknown>>(defaultValues(config.fields));
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [saving, setSaving] = useState(false);
  const [deleteError, setDeleteError] = useState<string | null>(null);

  const {
    data,
    meta,
    params,
    loading,
    error,
    setPage,
    setSearch,
    setSort,
    setFilter,
    reload,
  } = usePaginated<T>({ fetcher: config.list });

  const sorted = params.sort_by ?? 'created_at';
  const sortDir = (params.sort_dir as 'asc' | 'desc') ?? 'desc';

  const handleSort = useCallback(
    (key: string) => {
      const nextDir = sorted === key && sortDir === 'desc' ? 'asc' : 'desc';
      setSort(key, nextDir);
    },
    [sorted, sortDir, setSort],
  );

  const openCreate = useCallback(() => {
    setEditing(null);
    setValues(defaultValues(config.fields));
    setErrors({});
    setModalOpen(true);
  }, [config.fields]);

  const openEdit = useCallback(
    (row: T) => {
      const record = row as unknown as Record<string, unknown>;
      const next = Object.fromEntries(
        config.fields.map((field) => [field.name, fieldValue(record, field.name)]),
      );
      setEditing(row);
      setValues(next);
      setErrors({});
      setModalOpen(true);
    },
    [config.fields],
  );

  const closeModal = useCallback(() => {
    setModalOpen(false);
    setEditing(null);
    setErrors({});
  }, []);

  const updateValue = useCallback((name: string, value: unknown) => {
    setValues((current) => ({ ...current, [name]: value }));
    setErrors((current) => {
      if (!current[name]) return current;
      const next = { ...current };
      delete next[name];
      return next;
    });
  }, []);

  const submit = useCallback(async () => {
    setSaving(true);
    setErrors({});

    try {
      const payload = { ...values };

      if (editing) {
        await config.update(config.keyExtractor(editing), payload);
        toast.notify(`${config.title} updated successfully`);
      } else {
        await config.create(payload);
        toast.notify(`${config.title} created successfully`);
      }

      closeModal();
      await reload();
    } catch (cause) {
      setErrors(validationErrors(cause));
      toast.notify(apiErrorMessage(cause, 'Unable to save the record.'), 'error');
    } finally {
      setSaving(false);
    }
  }, [values, editing, config, toast, closeModal, reload]);

  const confirmDelete = useCallback(async () => {
    if (!deleting) return;

    setDeleteError(null);

    try {
      await config.remove(config.keyExtractor(deleting));
      toast.notify(`${config.title} deleted successfully`);
      setDeleting(null);
      await reload();
    } catch (cause) {
      setDeleteError(apiErrorMessage(cause, 'Unable to delete the record.'));
    }
  }, [deleting, config, toast, reload]);

  useEffect(() => {
    if (!modalOpen && !deleting) {
      setErrors({});
    }
  }, [modalOpen, deleting]);

  const filterOptions = useMemo(() => config.filters ?? [], [config.filters]);
  const canCreate = config.canCreate ?? true;
  const canEdit = config.canEdit ?? true;
  const canDelete = config.canDelete ?? true;

  return (
    <div>
      <PageHeader
        title={config.title}
        description={config.description}
        actions={
          canCreate ? (
            <Button onClick={openCreate}>
              <Plus className="h-4 w-4" />
              {config.createLabel ?? 'New'}
            </Button>
          ) : undefined
        }
      />

      {filterOptions.length > 0 && (
        <div className="mb-4 flex flex-wrap gap-3">
          {filterOptions.map((filter) => (
            <Select
              key={filter.name}
              value={(params[filter.name] as string) ?? ''}
              onChange={(event) => setFilter(filter.name, event.target.value || undefined)}
              className="w-44"
            >
              <option value="">{filter.label}: All</option>
              {filter.options.map((option) => (
                <option key={option.value} value={option.value}>
                  {option.label}
                </option>
              ))}
            </Select>
          ))}
        </div>
      )}

      <Card>
        <div className="flex flex-wrap items-center gap-3 border-b border-slate-200 px-4 py-3">
          <div className="relative w-full max-w-sm">
            <Search className="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
            <Input
              value={(params.search as string) ?? ''}
              onChange={(event) => setSearch(event.target.value)}
              placeholder={config.searchPlaceholder ?? 'Search…'}
              className="pl-9"
            />
          </div>
        </div>

        {error ? (
          <div className="px-4 py-3 text-sm text-danger-600">{error}</div>
        ) : null}

        <DataTable
          columns={[
            ...config.columns,
            ...(canEdit || canDelete || config.rowActions
              ? [
                  {
                    key: 'actions',
                    header: 'Actions',
                    className: 'text-right',
                    render: (row: T) => (
                      <div className="flex items-center justify-end gap-1">
                        {config.rowActions?.(row, reload)}
                        {canEdit && (
                          <Button variant="ghost" size="sm" onClick={() => openEdit(row)}>
                            <Pencil className="h-4 w-4" />
                          </Button>
                        )}
                        {canDelete && (
                          <Button variant="ghost" size="sm" onClick={() => setDeleting(row)}>
                            <Trash2 className="h-4 w-4 text-danger-600" />
                          </Button>
                        )}
                      </div>
                    ),
                  } as Column<T>,
                ]
              : []),
          ]}
          data={data}
          keyExtractor={config.keyExtractor}
          loading={loading}
          emptyTitle={`No ${config.title.toLowerCase()} found`}
          sortBy={sorted}
          sortDir={sortDir}
          onSort={handleSort}
        />

        <Pagination meta={meta} onPageChange={setPage} />
      </Card>

      <Modal
        open={modalOpen}
        onClose={closeModal}
        title={editing ? `Edit ${config.title}` : `New ${config.title}`}
        wide
      >
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          {config.fields.map((field) => (
            <div key={field.name} className={field.half === false ? 'sm:col-span-2' : ''}>
              <Field
                label={field.label}
                required={field.required}
                error={errors[field.name]?.[0]}
                hint={field.hint}
              >
                {field.type === 'textarea' ? (
                  <Textarea
                    value={(values[field.name] as string) ?? ''}
                    onChange={(event) => updateValue(field.name, event.target.value)}
                    placeholder={field.placeholder}
                  />
                ) : field.type === 'select' ? (
                  <Select
                    value={(values[field.name] as string) ?? ''}
                    onChange={(event) => updateValue(field.name, event.target.value)}
                  >
                    <option value="">Select…</option>
                    {field.options?.map((option) => (
                      <option key={option.value} value={option.value}>
                        {option.label}
                      </option>
                    ))}
                  </Select>
                ) : field.type === 'checkbox' ? (
                  <label className="flex items-center gap-2 pt-2 text-sm text-slate-700">
                    <input
                      type="checkbox"
                      checked={Boolean(values[field.name])}
                      onChange={(event) => updateValue(field.name, event.target.checked)}
                      className="h-4 w-4 rounded border-slate-300 text-primary-600"
                    />
                    {field.placeholder ?? 'Enabled'}
                  </label>
                ) : (
                  <Input
                    type={field.type ?? 'text'}
                    value={(values[field.name] as string | number) ?? ''}
                    onChange={(event) => updateValue(field.name, event.target.value)}
                    placeholder={field.placeholder}
                  />
                )}
              </Field>
            </div>
          ))}
        </div>

        <div className="mt-6 flex justify-end gap-2">
          <Button variant="secondary" onClick={closeModal}>
            Cancel
          </Button>
          <Button onClick={submit} loading={saving}>
            {editing ? 'Save changes' : 'Create'}
          </Button>
        </div>
      </Modal>

      <Modal
        open={Boolean(deleting)}
        onClose={() => setDeleting(null)}
        title={`Delete ${config.title}`}
      >
        <p className="text-sm text-slate-600">
          This action cannot be undone. Are you sure you want to delete this record?
        </p>

        {deleteError && (
          <p className="mt-3 rounded-lg bg-danger-50 px-3 py-2 text-sm text-danger-700">
            {deleteError}
          </p>
        )}

        <div className="mt-6 flex justify-end gap-2">
          <Button variant="secondary" onClick={() => setDeleting(null)}>
            Cancel
          </Button>
          <Button variant="danger" onClick={confirmDelete}>
            Delete
          </Button>
        </div>
      </Modal>
    </div>
  );
}
