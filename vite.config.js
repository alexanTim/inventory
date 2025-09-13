import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        host: '0.0.0.0',
    },
    build: {
        target: 'es2015',
        cssTarget: 'chrome87',
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'flowbite'],
                    charts: ['apexcharts'],
                    qr: ['html5-qrcode'],
                }
            }
        },
        chunkSizeWarningLimit: 1000,
    },
    css: {
        postcss: {
            plugins: [
                {
                    postcssPlugin: 'internal:charset-removal',
                    AtRule: {
                        charset: (atRule) => {
                            if (atRule.name === 'charset') {
                                atRule.remove();
                            }
                        }
                    }
                }
            ]
        }
    }
});