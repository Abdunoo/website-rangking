import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import viteCompression from "vite-plugin-compression";
import { VitePWA } from "vite-plugin-pwa";
import sitemapPlugin from "vite-plugin-sitemap";
import VitePluginVueDevTools from "vite-plugin-vue-devtools";

export default defineConfig({
  plugins: [
    vue(),
    VitePluginVueDevTools(),
    sitemapPlugin({
      hostname: 'https://sandbox2.panemu.com/rank/',
      routes: [
        '/', '/:domain', '/profile', '/change-password', '/register', '/login',
        '/buy'
      ],
    }),  
    viteCompression({
      verbose: true,
      disable: false,
      threshold: 1024,
      algorithm: "gzip",
      ext: ".gz",
    }),
    VitePWA({
      registerType: "autoUpdate",
      includeAssets: ['favicon.ico', 'robots.txt', 'apple-touch-icon.png'],
      manifest: {
        name: "Ticketku",
        short_name: "Ticketku",
        description: "A web application for managing tickets efficiently.",
        theme_color: "#0d7cf2",
        background_color: "#ffffff",
        display: "standalone",
        start_url: "/rank/",
        id: "/rank/",
        icons: [
          {
            src: "/rank/assets/images/logo_192x192.webp",
            sizes: "192x192",
            type: "image/webp",
          },
          {
            src: "/rank/assets/images/logo_512x512.webp",
            sizes: "512x512",
            type: "image/webp",
          },
          {
            src: "/rank/assets/images/logo_512x512_maskable.webp",
            sizes: "512x512",
            type: "image/webp",
            purpose: "any maskable",
          },
        ],
      },
      workbox: {
        runtimeCaching: [
          {
            urlPattern: /.*\.(?:js|css|html|json|woff2?|ttf|eot|svg|png|jpe?g|gif|webp)/,
            handler: 'CacheFirst',
            options: {
              cacheName: 'static-assets-cache',
              expiration: { 
                maxEntries: 100, // Increased for more flexibility
                maxAgeSeconds: 30 * 24 * 60 * 60 // 30 days
              },
              cacheableResponse: {
                statuses: [0, 200]
              },
            },
          },
          {
            urlPattern: /\/api\//, // Assuming you have API calls
            handler: 'NetworkFirst',
            options: {
              cacheName: 'api-cache',
              networkTimeoutSeconds: 10,
              expiration: {
                maxEntries: 50,
                maxAgeSeconds: 24 * 60 * 60 // 24 hours
              },
              cacheableResponse: {
                statuses: [0, 200]
              },
            },
          },
          {
            urlPattern: /\.(?:json)/,
            handler: 'StaleWhileRevalidate',
            options: {
              cacheName: 'dynamic-content-cache',
              expiration: {
                maxEntries: 50,
                maxAgeSeconds: 12 * 60 * 60 // 12 hours
              },
            },
          },
          {
            urlPattern: /\/robots\.txt/,
            handler: 'NetworkOnly',
            options: {
              cacheName: 'robots-txt-cache',
            },
          },
        ],
      },
    }),
  ],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  base: "/rank/",
  build: {
    target: 'esnext',
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true,
        drop_debugger: true
      }
    },
    rollupOptions: {
      output: {
        manualChunks: {
          'vue-vendor': ['vue', 'vue-router', 'pinia'],
        },
        chunkFileNames: 'assets/js/[name]-[hash].js',
        entryFileNames: 'assets/js/[name]-[hash].js',
        assetFileNames: ({name}) => {
          if (/\.(gif|jpe?g|png|svg|webp|avif)$/.test(name ?? '')) {
            return 'assets/images/[name]-[hash][extname]'
          }
          if (/\.css$/.test(name ?? '')) {
            return 'assets/css/[name]-[hash][extname]'
          }
          return 'assets/[name]-[hash][extname]'
        }
      }
    },
    cssCodeSplit: true,
    sourcemap: false,
    cssMinify: true,
    reportCompressedSize: false
  },
  server: {
    open: true,
    cors: true,
    strictPort: true,
    historyApiFallback: true,
    warmup: {
      clientFiles: [
        './src/views/*.vue',
        './src/components/*.vue'
      ]
    }
  },
  optimizeDeps: {
    include: ["vue", "axios"],
  },
});

