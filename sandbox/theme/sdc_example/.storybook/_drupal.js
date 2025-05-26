// Simple Drupal.behaviors usage for Storybook.
// Borrowed from Emulsify <https://github.com/emulsify-ds>
// Borrowed from SB-drupal <https://github.com/mel-miller/sb-drupal/blob/main/.storybook/_drupal.js>

window.Drupal = { behaviors: {} };
window.drupalSettings = window.drupalSettings || {};

window.once = function (id, selector, context) {
  context = context || document;
  const elements = Array.from(context.querySelectorAll(selector));

  return elements.filter(el => {
    const marker = `__once__${id}`;
    if (el[marker]) {
      return false;
    }
    el[marker] = true;
    return true;
  });
};

(function (Drupal, drupalSettings) {
  Drupal.throwError = function (error) {
    setTimeout(function () {
      throw error;
    }, 0);
  };

  Drupal.attachBehaviors = function (context, settings) {
    context = context || document;
    settings = settings || drupalSettings;
    const behaviors = Drupal.behaviors;

    Object.keys(behaviors).forEach(function (i) {
      if (typeof behaviors[i].attach === 'function') {
        try {
          behaviors[i].attach(context, settings);
        } catch (e) {
          Drupal.throwError(e);
        }
      }
    });
  };
})(Drupal, window.drupalSettings);
