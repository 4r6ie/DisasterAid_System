import { ChevronLeft, ChevronRight } from 'lucide-react';
import type { PaginatedMeta } from '@/types';
import { cn } from '@/utils/cn';

interface PaginationProps {
  meta: PaginatedMeta;
  onPageChange: (page: number) => void;
}

function pageNumbers(current: number, last: number): (number | '…')[] {
  if (last <= 7) return Array.from({ length: last }, (_, index) => index + 1);

  const pages = new Set<number>([1, 2, current - 1, current, current + 1, last - 1, last]);
  const sorted = [...pages].filter((page) => page >= 1 && page <= last).sort((a, b) => a - b);

  const result: (number | '…')[] = [];
  let previous = 0;

  for (const page of sorted) {
    if (previous && page - previous > 1) result.push('…');
    result.push(page);
    previous = page;
  }

  return result;
}

export function Pagination({ meta, onPageChange }: PaginationProps) {
  const { current_page, last_page, total, per_page } = meta;

  if (last_page <= 1) return null;

  const start = total === 0 ? 0 : (current_page - 1) * per_page + 1;
  const end = Math.min(total, current_page * per_page);

  return (
    <div className="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 px-4 py-3">
      <p className="text-sm text-slate-500">
        Showing <span className="font-medium text-slate-700">{start}</span> to{' '}
        <span className="font-medium text-slate-700">{end}</span> of{' '}
        <span className="font-medium text-slate-700">{total}</span> results
      </p>

      <div className="flex items-center gap-1">
        <button
          type="button"
          onClick={() => onPageChange(current_page - 1)}
          disabled={current_page === 1}
          className="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-sm text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-40"
        >
          <ChevronLeft className="h-4 w-4" />
          Previous
        </button>

        {pageNumbers(current_page, last_page).map((page, index) =>
          page === '…' ? (
            <span key={`gap-${index}`} className="px-2 text-sm text-slate-400">
              …
            </span>
          ) : (
            <button
              key={page}
              type="button"
              onClick={() => onPageChange(page)}
              className={cn(
                'min-w-[32px] rounded-lg px-2.5 py-1.5 text-sm transition',
                page === current_page
                  ? 'bg-primary-600 font-medium text-white'
                  : 'text-slate-600 hover:bg-slate-100',
              )}
            >
              {page}
            </button>
          ),
        )}

        <button
          type="button"
          onClick={() => onPageChange(current_page + 1)}
          disabled={current_page === last_page}
          className="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-sm text-slate-600 transition hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-40"
        >
          Next
          <ChevronRight className="h-4 w-4" />
        </button>
      </div>
    </div>
  );
}
