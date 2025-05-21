import type { Meta, StoryObj } from '@storybook/html'

import card from './card.twig'
import './card.css'

import drupalAttribute from 'drupal-attribute'

type CardArgs = {
  image: {
    source: string;
    alt: string;
  };
  label: string;
  content: string;
  link: {
    url: string;
    content: string;
  };
};

const meta = {
  component: card,
  title: 'UI/Organisms/Card',
  argTypes: {
    attributes: {
      attributes: new drupalAttribute([]),
      table: {
        disable: true,
      },
    },
    title_attributes: {
      attributes: new drupalAttribute([]),
      table: {
        disable: true,
      },
    },
    plugin_id: {
      description: 'The plugin ID of the card',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    configuration: {
      description: 'The configuration of the card',
      table: {
        type: { summary: 'object' },
      },
    },
    title_prefix: {
      description: 'The prefix of the title',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    title_suffix: {
      description: 'The suffix of the title',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    image: {
      description: 'The image of the card',
      table: {
        type: { summary: 'object' },
      },
    },
    label: {
      description: 'The label of the card',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    content: {
      description: 'The content of the card',
      control: 'text',
      table: {
        type: { summary: 'string' },
      },
    },
    link: {
      description: 'The link of the card',
      table: {
        type: { summary: 'object' },
      },
    },
  }
} satisfies Meta<typeof card>;

export default meta;
type Story = StoryObj<CardArgs>;

export const Default: Story = {
  args: {
    image: {
      source: '/images/377-400x300.jpg',
      alt: ''
    },
    label: 'Design Systems',
    content: '<p>This is an example of a card created with storybook and integrate in Drupal.</p>',
    link: {
      url: '#',
      content: 'Read more'
    }
  }
}
