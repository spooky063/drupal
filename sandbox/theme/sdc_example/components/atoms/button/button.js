(function(Drupal, once) {

  function console () {
    window.console.log('click');
  }

  Drupal.behaviors.button = {
    attach(context) {
      once('button', '.btn', context)
        .forEach(
          button => button.addEventListener('click', console)
        );
    },
    detach(context) {
      once('button', '.btn', context)
        .forEach(
          button => button.removeEventListener('click', console)
        );
    },
  };

})(Drupal, once);
