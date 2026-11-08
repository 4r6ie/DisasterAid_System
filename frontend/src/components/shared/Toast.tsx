import { ReactNode, useCallback, useMemo, useState } from 'react';
import { CheckCircle2, AlertTriangle, XCircle, Info, X } from 'lucide-react';
import { ToastContext, type ToastTone } from '@/hooks/useToast';
import { cn } from '@/utils/cn';

interface Toast {
  id: number;
  message: string;
  tone: ToastTone;
}

const toneStyles: Record<ToastTone, { className: string; icon: ReactNode }> = {
  success: { className: 'border-success-200 bg-success-50 text-success-800', icon: <CheckCircle2 className="h-5 w-5 text-success-600" /> },
  error: { className: 'border-danger-200 bg-danger-50 text-danger-800', icon: <XCircle className="h-5 w-5 text-danger-600" /> },
  info: { className: 'border-info-200 bg-info-50 text-info-800', icon: <Info className="h-5 w-5 text-info-600" /> },
  warning: { className: 'border-warning-200 bg-warning-50 text-warning-800', icon: <AlertTriangle className="h-5 w-5 text-warning-600" /> },
};

export function ToastProvider({ children }: { children: ReactNode }) {
  const [toasts, setToasts] = useState<Toast[]>([]);

  const dismiss = useCallback((id: number) => {
    setToasts((current) => current.filter((toast) => toast.id !== id));
  }, []);

  const notify = useCallback(
    (message: string, tone: ToastTone = 'success') => {
      const id = Date.now() + Math.random();
      setToasts((current) => [...current, { id, message, tone }]);
      window.setTimeout(() => dismiss(id), 4000);
    },
    [dismiss],
  );

  const value = useMemo(() => ({ notify }), [notify]);

  return (
    <ToastContext.Provider value={value}>
      {children}

      <div className="pointer-events-none fixed bottom-4 right-4 z-[60] flex w-full max-w-sm flex-col gap-2">
        {toasts.map((toast) => {
          const style = toneStyles[toast.tone];

          return (
            <div
              key={toast.id}
              className={cn(
                'pointer-events-auto flex items-start gap-3 rounded-lg border px-4 py-3 shadow-lg',
                style.className,
              )}
              role="status"
            >
              {style.icon}
              <p className="flex-1 text-sm font-medium">{toast.message}</p>
              <button
                type="button"
                onClick={() => dismiss(toast.id)}
                className="rounded p-0.5 opacity-60 transition hover:opacity-100"
                aria-label="Dismiss"
              >
                <X className="h-4 w-4" />
              </button>
            </div>
          );
        })}
      </div>
    </ToastContext.Provider>
  );
}
