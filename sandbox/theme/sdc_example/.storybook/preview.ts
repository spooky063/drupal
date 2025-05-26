import type { Preview } from '@storybook/html';
import { DecoratorFunction } from 'storybook/internal/types';

import './_drupal.js'
import './preview.css'

type viewportTypes = {
  name: string,
  type?: string,
  styles: styleTypes,
};
type styleTypes = {
  width: string,
  height: string,
};

const viewports: Record<string, viewportTypes> = {
  small: {
    name: 'XS - 320px',
    type: 'mobile',
    styles: {
      width: '320px',
      height: '640px',
    },
  },
  desktop: {
    name: 'L - 1200px',
    type: 'desktop',
    styles: {
      width: '1200px',
      height: '1200px',
    },
  },
};

const preview: Preview = {
  parameters: {
    actions: { argTypesRegex: "^on[A-Z].*" },
    backgrounds: {
      default: 'light',
      values: [
        {
          name: 'light',
          value: '#FFF',
        },
        {
          name: 'dark',
          value: '#000',
        },
      ],
    },
    controls: {
      matchers: {
       color: /(background|color)$/i,
       date: /Date$/i,
      },
    },
    options: {
      storySort: {
        order: [
          'Styleguide',
          'UI',
        ],
      },
    },
    viewport: {
      viewports,
    },
  },
};

interface DrupalType {
  attachBehaviors: () => void;
}
declare const Drupal: DrupalType;

export const decorators: DecoratorFunction[] = [
  (storyFn: () => any, context: any) => {
    const story = storyFn();
    setTimeout(() => Drupal.attachBehaviors(), 0);


    let currentTheme = context?.globals?.backgrounds?.value ?? context?.parameters?.backgrounds?.default;
    if (currentTheme.startsWith('#')) {
      const backgrounds = context?.parameters?.backgrounds?.values;
      const match = backgrounds.find(bg => bg.value.toLowerCase() === currentTheme.toLowerCase());
      currentTheme = match.name;
    }
    const body = document.body;
    [...body.classList].filter(c => c.startsWith('theme-')).forEach(c => body.classList.remove(c));
    body.classList.add(`theme-${currentTheme}`);

	  return story;
  },
];

export default preview;
