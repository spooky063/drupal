import type { StorybookConfig } from '@storybook/html-vite';

const config: StorybookConfig = {
  "stories": [
    "../components/**/*.mdx",
    "../components/**/*.stories.@(js|jsx|mjs|ts|tsx)",
  ],
  "addons": [
    "@storybook/addon-essentials",
    "@storybook/addon-a11y",
    "@storybook/addon-interactions"
  ],
  "framework": {
    "name": "@storybook/html-vite",
    "options": {}
  }
};
export default config;
