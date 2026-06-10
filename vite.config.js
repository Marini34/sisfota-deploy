import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/images/Logo.png',
                'resources/images/semhas.svg',
                'resources/images/sempro.svg',
            ],
            refresh: true,
        }),
    ],
});
