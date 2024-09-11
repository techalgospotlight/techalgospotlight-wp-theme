// Toggle control
wp.customize.controlConstructor["techalgospotlight-toggle"] =
  wp.customize.Control.extend({
    ready: function () {
      "use strict";

      var control = this;

      // Change the value
      control.container.on(
        "click",
        ".techalgospotlight-toggle-switch",
        function () {
          control.setting.set(!control.setting.get());
        }
      );
    },
  });
