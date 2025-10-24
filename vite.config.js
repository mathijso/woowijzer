import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({ command }) => {
    // Determine base path based on environment
    const isProduction = command === 'build';
    const basePath = isProduction ? '/woowijzer/' : '/';

    return {
        base: basePath,
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            cors: true,
        },
    };
});