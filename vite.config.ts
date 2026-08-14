import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'),
    },
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://nginx',
        changeOrigin: true,
      },
    },
  },
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: true,
  },
});