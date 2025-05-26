import type { Meta, StoryObj } from '@storybook/html'

import button from './button.twig'
import './button.css'
import './button.js'

import drupalAttribute from 'drupal-attribute'

type ButtonArgs = {
  value: string;
  variant?: 'primary' | 'secondary';
};

const meta = {
  component: button,
  title: 'UI/Atoms/Button',
  argTypes: {
    attributes: {
      attributes: new drupalAttribute([]),
      table: {
        disable: true,
      },
    },
    value: {
      description: 'The value of the button',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    variant: {
      options: [undefined, 'primary', 'secondary'],
      control: { type: 'radio' },
      table: {
        type: { summary: 'primary | secondary' },
      },
    },
  },
  render: (args) => {
    const attributes = new drupalAttribute([])
      .setAttribute('data-component-id', 'sdc_example:button');
    return button({ ...args, attributes });
  },
} satisfies Meta<typeof button>;

export default meta;
type Story = StoryObj<ButtonArgs>;

export const Default: Story = {
  args: {
    value: 'Click me',
    variant: undefined,
  }
};
