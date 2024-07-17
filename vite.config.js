import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin'; // Assuming this is the correct import path

export default defineConfig({
    // publicDir: 'public',
    base: '/public/', // Adjust this based on your asset path
    // outDir: 'public',
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/merchandiserTimeSheetDataTableAndChart.js'
            ],
            refresh: true,
        }),
    ],
});