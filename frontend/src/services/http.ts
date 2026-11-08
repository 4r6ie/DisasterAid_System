import axios, { AxiosError } from 'axios';

export const TOKEN_KEY = 'disasteraid_token';

export const http = axios.create({
  baseURL: '/api',
  headers: {
    Accept: 'application/json',
  },
});

http.interceptors.request.use((config) => {
  const token = localStorage.getItem(TOKEN_KEY);
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

http.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    const url = error.config?.url ?? '';
    const isAuthRoute = url.includes('/auth/');

    if (error.response?.status === 401 && !isAuthRoute) {
      localStorage.removeItem(TOKEN_KEY);
      if (window.location.pathname !== '/login') {
        window.location.assign('/login');
      }
    }

    return Promise.reject(error);
  },
);

export default http;

interface ErrorPayload {
  message?: string;
  errors?: Record<string, string[]>;
}

export function apiErrorMessage(error: unknown, fallback = 'Something went wrong.'): string {
  if (axios.isAxiosError(error)) {
    const payload = error.response?.data as ErrorPayload | undefined;

    if (payload?.message) return payload.message;

    if (payload?.errors) {
      const first = Object.values(payload.errors).flat()[0];
      if (first) return first;
    }

    if (error.message && error.message !== 'Network Error') return error.message;
  }

  return fallback;
}

export function validationErrors(error: unknown): Record<string, string[]> {
  if (axios.isAxiosError(error)) {
    const payload = error.response?.data as ErrorPayload | undefined;
    if (payload?.errors) return payload.errors;
  }
  return {};
}
