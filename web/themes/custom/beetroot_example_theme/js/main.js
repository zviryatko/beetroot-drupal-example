import {MyComponent} from './Components/MyComponent'

(function ($, Drupal) {
  Drupal.behaviors.customElement = {
    attach: function (context, settings) {
      $('#block-exampleblock, .custom-react-list', context).each(function () {
        const root = ReactDOM.createRoot($(this)[0]);
        root.render(<MyComponent/>);
      });
    }
  }
})(jQuery, Drupal)
