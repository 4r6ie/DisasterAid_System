import http from './http';
import type { User } from '@/types';

export interface RegisterPayload {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  phone?: string;
  address?: string;
}

export interface ResetPayload {
  email: string;
  password: string;
  password_confirmation: string;
  token: string;
}

export const authService = {
  register: async (payload: RegisterPayload) => {
    const response = await http.post('/auth/register', payload);
    return response.data.data as { user: User; token: string };
  },

  login: async (payload: { email: string; password: string; remember?: boolean }) => {
    const response = await http.post('/auth/login', payload);
    return response.data.data as { user: User; token: string };
  },

  logout: async () => {
    await http.post('/auth/logout');
  },

  me: async () => {
    const response = await http.get('/auth/me');
    return response.data.data as User;
  },

  updateProfile: async (payload: { name?: string; phone?: string; address?: string }) => {
    const response = await http.put('/auth/profile', payload);
    return response.data.data as User;
  },

  changePassword: async (payload: { current_password: string; new_password: string; new_password_confirmation: string }) => {
    await http.post('/auth/change-password', payload);
  },

  forgotPassword: async (email: string) => {
    await http.post('/auth/forgot-password', { email });
  },

  resetPassword: async (payload: ResetPayload) => {
    await http.post('/auth/reset-password', payload);
  },
};
