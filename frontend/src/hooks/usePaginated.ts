import { useCallback, useEffect, useState } from 'react';
import type { PaginatedMeta, QueryParams } from '@/types';

interface UsePaginatedOptions<T> {
  fetcher: (params: QueryParams) => Promise<{ data: T[]; meta: PaginatedMeta }>;
  initialParams?: QueryParams;
  debounceMs?: number;
}

const defaultMeta: PaginatedMeta = {
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0,
};

/**
 * Loads a paginated collection and exposes the query state used by the
 * table, pagination and filter components.
 */
export function usePaginated<T>({
  fetcher,
  initialParams = {},
  debounceMs = 300,
}: UsePaginatedOptions<T>) {
  const [data, setData] = useState<T[]>([]);
  const [meta, setMeta] = useState<PaginatedMeta>(defaultMeta);
  const [params, setParams] = useState<QueryParams>({ per_page: 15, ...initialParams });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const load = useCallback(
    async (next: QueryParams) => {
      setLoading(true);
      setError(null);

      try {
        const result = await fetcher(next);
        setData(result.data);
        setMeta(result.meta);
      } catch (cause) {
        setError(cause instanceof Error ? cause.message : 'Unable to load records.');
      } finally {
        setLoading(false);
      }
    },
    [fetcher],
  );

  useEffect(() => {
    const handle = window.setTimeout(() => {
      void load(params);
    }, debounceMs);

    return () => window.clearTimeout(handle);
  }, [params, load, debounceMs]);

  const setPage = useCallback((page: number) => {
    setParams((current) => ({ ...current, page }));
  }, []);

  const setSearch = useCallback((search: string) => {
    setParams((current) => ({ ...current, search, page: 1 }));
  }, []);

  const setSort = useCallback((sortBy: string, sortDir: 'asc' | 'desc') => {
    setParams((current) => ({ ...current, sort_by: sortBy, sort_dir: sortDir, page: 1 }));
  }, []);

  const setFilter = useCallback((key: string, value: string | undefined) => {
    setParams((current) => ({ ...current, [key]: value, page: 1 }));
  }, []);

  return {
    data,
    meta,
    params,
    loading,
    error,
    setPage,
    setSearch,
    setSort,
    setFilter,
    reload: () => load(params),
    setParams,
  };
}
