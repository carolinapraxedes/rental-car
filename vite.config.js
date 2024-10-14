import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',  // Permite que o Vite aceite conexões de qualquer IP
        port: 5173,       // Porta padrão do Vite
        hmr: {
            host: 'localhost',  // Define o host HMR para "localhost"
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
