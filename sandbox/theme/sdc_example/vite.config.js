import { defineConfig } from "vite"
import twig from 'vite-plugin-twig-drupal';
import { join } from "node:path"

export default defineConfig({
  plugins: [
    twig({
      namespaces: {
        components: join(__dirname, "components"),
      },
    }),
  ],
})
