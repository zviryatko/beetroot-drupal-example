(function (Drupal, $) {
    Drupal.behaviors.redColorInput = {
        attach: function (context, settings) {
            console.log(settings.foo)
        }
    }
})(Drupal, jQuery)
