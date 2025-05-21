import type { Meta, StoryObj } from '@storybook/html'

import button from './button.twig'
import './button.css'
import './button.js'

import { within, userEvent } from '@storybook/test';

type ButtonArgs = {
  content: string;
  variant?: 'default' | 'primary' | 'secondary';
};

const meta = {
  component: button,
  title: 'UI/Atoms/Button',
  argTypes: {
    content: {
      description: 'The content of the button',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    variant: {
      options: ['default', 'primary', 'secondary'],
      control: { type: 'radio' },
      table: {
        type: { summary: 'default | primary | secondary' },
      },
    },
  },
} satisfies Meta<typeof button>;

export default meta;
type Story = StoryObj<ButtonArgs>;

export const Default: Story = {
  play: async ({ canvasElement }) => {
    const canvas = within(canvasElement);
    const button = await canvas.getByRole("button");
    await userEvent.click(button);
  },
  args: {
		content: 'Click me',
    variant: 'default',
	},
};
