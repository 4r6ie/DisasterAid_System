import { create } from 'zustand';
import { authService, type RegisterPayload } from '@/services/auth.service';
import { TOKEN_KEY } from '@/services/http';
import type { User } from '@/types';

interface AuthState {
  user: User | null;
  token: string | null;
  initializing: boolean;
  isAuthenticated: boolean;
  initialize: () => Promise<void>;
  login: (email: string, password: string, remember?: boolean) => Promise<void>;
  register: (payload: RegisterPayload) => Promise<void>;
  logout: () => Promise<void>;
  refresh: () => Promise<void>;
}

function readToken(): string | null {
  return localStorage.getItem(TOKEN_KEY);
}

export const useAuthStore = create<AuthState>((set, get) => ({
  user: null,
  token: readToken(),
  initializing: true,
  isAuthenticated: Boolean(readToken()),

  initialize: async () => {
    if (!get().token) {
      set({ initializing: false });
      return;
    }

    try {
      await get().refresh();
    } catch {
      localStorage.removeItem(TOKEN_KEY);
      set({ user: null, token: null, isAuthenticated: false });
    } finally {
      set({ initializing: false });
    }
  },

  login: async (email, password, remember = false) => {
    const data = await authService.login({ email, password, remember });
    localStorage.setItem(TOKEN_KEY, data.token);
    set({ user: data.user, token: data.token, isAuthenticated: true });
  },

  register: async (payload) => {
    const data = await authService.register(payload);
    localStorage.setItem(TOKEN_KEY, data.token);
    set({ user: data.user, token: data.token, isAuthenticated: true });
  },

  logout: async () => {
    try {
      await authService.logout();
    } catch {
      // The token may already be invalid; clear local state regardless.
    } finally {
      localStorage.removeItem(TOKEN_KEY);
      set({ user: null, token: null, isAuthenticated: false });
    }
  },

  refresh: async () => {
    const user = await authService.me();
    set({ user });
  },
}));
