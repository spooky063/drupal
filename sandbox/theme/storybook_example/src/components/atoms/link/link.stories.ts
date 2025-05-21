import type { Meta, StoryObj } from '@storybook/html'

import link from './link.twig'
import './link.css'

type LinkArgs = {
  url: string;
  content: string;
};

const meta = {
  component: link,
  title: 'UI/Atoms/Link',
  argTypes: {
    url: {
      description: 'The URL of the link',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    content: {
      description: 'The content of the link',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
  },
} satisfies Meta<typeof link>;

export default meta;
type Story = StoryObj<LinkArgs>;

export const Default: Story = {
  args: {
		content: 'Read more',
		url: '#',
	},
}
