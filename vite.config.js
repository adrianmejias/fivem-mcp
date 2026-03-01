import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss({
            theme: {
                extend: {
                    colors: {
                        gta: {
                            dark: '#1a1a1a',
                            light: '#f8fafc',
                            orange: '#F57C00',
                            cyan: '#1de9b6',
                            gray: '#334155',
                            indigo: '#3b82f6',
                        },
                    },
                },
            },
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
