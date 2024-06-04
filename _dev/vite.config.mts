import eslint from 'vite-plugin-eslint'
import { resolve } from 'path'
import { defineConfig } from 'vite'

const psRootDir = resolve(__dirname, '../../../')

export default defineConfig({
  build: {
    manifest: true,
    rollupOptions: {
      external: ['prestashop', '$', 'jquery'],
      input: {
        'recaptcha-v3': resolve(__dirname, 'src/js/front/recaptcha-v3.ts'),
      },
      output: {
        assetFileNames: (assetInfo) => {
          const info = assetInfo.name.split('.')
          let extType = info[info.length - 1].toLowerCase()

          if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(extType)) {
            extType = 'img'
          } else if (/woff|woff2/.test(extType)) {
            extType = 'css'
          }

          return `${extType}/[name]-[hash][extname]`
        },
        chunkFileNames: 'js/[name]-[hash].js',
        entryFileNames: 'js/[name]-[hash].js',
        globals: {
          prestashop: 'prestashop',
          $: '$',
          jquery: 'jQuery',
        },
      },
    },
    outDir: '../views',
  },
  plugins: [eslint()],
  resolve: {
    alias: {
      '@app': resolve(psRootDir, './admin-dev/themes/new-theme/js/app'),
      '@components': resolve(
        psRootDir,
        './admin-dev/themes/new-theme/js/components',
      ),
      '@src': resolve(__dirname, './src'),
    },
  },
})
