/**
 * Contact numbers are stored as exactly 11 digits (e.g. 09171234567).
 * Input is restricted to digits only so the rule on the API is never
 * violated by what the form lets through.
 */
export const PHONE_LENGTH = 11;

/** Strips everything that is not a digit and caps the length at 11. */
export function normalizePhone(value: string): string {
  return value.replace(/\D/g, '').slice(0, PHONE_LENGTH);
}
