import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // Tailwind v4 Vite Plugin

export default defineConfig({
    plugins: [
        laravel({
            // files Vite must track & compile
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // Automatic browser reload on Blade template updates
            refresh: true,
        }),
        // Registers Tailwind v4 engine to process Tailwind directives
        tailwindcss(),
    ],
});