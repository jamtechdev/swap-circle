import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/landing.css',
                'resources/js/landing.js',
                'resources/css/auth.css',
                'resources/js/auth.js',
                'resources/css/portal.css',
                'resources/js/portal.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
                'resources/css/legal.css',
                'resources/js/legal.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
    },
});
