import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import fs from 'fs';
import path from 'path';

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    https: {
      key: fs.readFileSync(path.resolve('/config/ssl/private.key')),
      cert: fs.readFileSync(path.resolve('/config/ssl/certificate.crt')),
    },
    host: 'localhost', // ou votre adresse IP locale
    port: 5173, // ou le port que vous utilisez
    strictPort: true,
    proxy: {
      '/api': {
        target: 'https://localhost', // URL de ton backend
        changeOrigin: true,
        secure: false, // Désactiver la vérification SSL auto-signé
      },
    },
  },
});
