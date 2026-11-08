import clsx from 'clsx';

export type ClassValue = Parameters<typeof clsx>[0];

export function cn(...inputs: ClassValue[]): string {
  return clsx(...inputs);
}
