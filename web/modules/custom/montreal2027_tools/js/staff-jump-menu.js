(function (Drupal, once) {
  Drupal.behaviors.staffJumpMenu = {
    attach(context) {
      once('staff-jump-menu', '#staff-jump-menu', context).forEach((select) => {
        select.addEventListener('change', () => {
          if (select.value) {
            // Re-set an identical hash so repeated selections still scroll.
            window.location.hash = '';
            window.location.hash = select.value;
          }
        });
      });
    },
  };
})(Drupal, once);
