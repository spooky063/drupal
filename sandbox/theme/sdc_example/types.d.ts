declare module '*.twig' {
  const template: (context: Record<string, any>) => string;
  export default template;
}
declare module '*.css' {
  const style: string;
  export default style;
}
