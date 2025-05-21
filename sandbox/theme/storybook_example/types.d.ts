declare module '*.twig' {
  const template: (context: Record<string, any>) => string;
  export default template;
}
