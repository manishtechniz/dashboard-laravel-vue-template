import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Admin
                'resources/admin/css/app.css',
                'resources/admin/js/app.js',

                // Websocket
                // 'resources/websocket/css/app.css',
                // 'resources/websocket/js/app.js',

                // Helper
                'resources/js/realtime-supabase.js'
            ],
            refresh: false,
        }),
        tailwindcss(),
        vue(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
