import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/izy/app.tsx',
            refresh: ['resources/izy/app.tsx'],
        }),
        react(),
    ],
    resolve: {
        alias: {
          '@': path.resolve(__dirname, 'resources/izy'), // Imposta il percorso base della tua cartella 'resources/izy'
          '@types': path.resolve(__dirname, './resources/types')
        },
    },
});
