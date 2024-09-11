/**
 * Remove activate button and replace with activation in progress button.
 *
 * @package techalgospotlight
 */

/* global techalgospotlight_plugin_helper */
/* global console */

jQuery(document).ready(function ($) {
  $.pluginInstall = {
    init: function () {
      this.handleInstall();
      this.handleActivate();
    },

    handleInstall: function () {
      var self = this;
      $("body").on("click", ".techalgospotlight-install-plugin", function (e) {
        e.preventDefault();
        var button = $(this);
        var slug = button.attr("data-slug");
        var url = button.attr("href");
        var redirect = $(button).attr("data-redirect");
        button.text(wp.updates.l10n.installing);
        button.addClass("updating-message");
        wp.updates.installPlugin({
          slug: slug,
          success: function () {
            button.text(techalgospotlight_plugin_helper.activating + "...");
            self.activatePlugin(url, redirect);
          },
        });
      });
    },

    activatePlugin: function (url, redirect) {
      if ("undefined" === typeof url || !url) {
        return;
      }
      jQuery.ajax({
        async: true,
        type: "GET",
        url: url,
        success: function () {
          // Reload the page.
          if ("undefined" !== typeof redirect && "" !== redirect) {
            window.location.replace(redirect);
          } else {
            location.reload();
          }
        },
        error: function (jqXHR, exception) {
          var msg = "";
          if (0 === jqXHR.status) {
            msg = "Not connect.\n Verify Network.";
          } else if (404 === jqXHR.status) {
            msg = "Requested page not found. [404]";
          } else if (500 === jqXHR.status) {
            msg = "Internal Server Error [500].";
          } else if ("parsererror" === exception) {
            msg = "Requested JSON parse failed.";
          } else if ("timeout" === exception) {
            msg = "Time out error.";
          } else if ("abort" === exception) {
            msg = "Ajax request aborted.";
          } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
          }
          console.log(msg);
        },
      });
    },

    handleActivate: function () {
      var self = this;
      $("body").on("click", ".activate-now", function (e) {
        e.preventDefault();
        var button = $(this);
        var url = button.attr("href");
        var redirect = button.attr("data-redirect");
        button.addClass("updating-message");
        button.text(techalgospotlight_plugin_helper.activating + "...");
        self.activatePlugin(url, redirect);
      });
    },
  };
  $.pluginInstall.init();
});
