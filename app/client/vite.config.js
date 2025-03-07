import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    host: 'localhost', // ou votre adresse IP locale
    port: 5173, // ou le port que vous utilisez
    strictPort: true,
    proxy: {
      '/api': {
        target: 'http://localhost', // URL de ton backend
        changeOrigin: true,
        secure: false, // Désactiver la vérification SSL auto-signé
      },
    },
  },
});