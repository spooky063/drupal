import type { Meta, StoryObj } from '@storybook/html'

const meta = {
  title: 'Styleguide',
} satisfies Meta;

export default meta;
type Story = StoryObj<{}>;

export const Colors: Story = {
  parameters: {
    backgrounds: { default: 'dark' },
  },
  render: () => `
  <style>
  .styleguide-color-palette {
    display: flex;
    flex-wrap: wrap;
  }
  .color-palette div {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100px;
    height: 100px;
  }
  </style>
  <h2>Core Palette</h2>
  <span>How website is expressed through colour</span>
  <div>
    <header>
      <h3>Core band</h3>
      <p>The core band is the main colour of the website.</p>
    </header>
    <div class="styleguide-color-palette">
      <div class="color-palette">
        <div style="background-color: #00A6FB"></div>
        <p>Primary</p>
        <span>#00A6FB <br> --clr-primary-400</span>
      </div>
      <div class="color-palette">
        <div style="background-color: #FFA500"></div>
        <p>Secondary</p>
        <span>#FFA500 <br> --clr-secondary-400</span>
      </div>
    </div>
  </div>
`,
};

export const Iconography: Story = {
  render: () => {
    return '<h2>Icons</h2>';
  }
};
