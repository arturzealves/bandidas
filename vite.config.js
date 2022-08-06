import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    server: { 
        https: false, 
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [{
                paths: [
                    ...refreshPaths,
                    'app/Http/Livewire/**',
                ]
            }],
        }),
    ],
});
