//--------------------------------------------------------------------//
// techalgospotlight WooCommerce compatibility script.
//--------------------------------------------------------------------//
(function ($) {
  "use strict";

  /**
   * Cart dropdown timer.
   * @type {Boolean}
   */
  var cartDropdownTimer = false;

  /**
   * Common element caching.
   */
  var $body = $("body");
  var $wrapper = $("#page");

  /**
   * Holds most important methods that bootstrap the whole theme.
   *
   * @type {Object}
   */
  var techalgospotlightWC = {
    /**
     * Start the engine.
     *
     * @since 1.0.0
     */
    init: function () {
      // Document ready.
      $(document).ready(techalgospotlightWC.ready);

      // Ajax complete event.
      $(document).ajaxComplete(techalgospotlightWC.ajaxComplete);

      // On WooCommerce ajax added to cart event.
      $body.on("added_to_cart", techalgospotlightWC.addedToCart);

      // Bind UI actions.
      techalgospotlightWC.bindUIActions();
    },

    //--------------------------------------------------------------------//
    // Events
    //--------------------------------------------------------------------//

    /**
     * Document ready.
     *
     * @since 1.0.0
     */
    ready: function () {
      techalgospotlightWC.customDropdown();
      techalgospotlightWC.quantButtons();
    },

    /**
     * On ajax request complete.
     *
     * @since 1.0.0
     */
    ajaxComplete: function () {
      techalgospotlightWC.quantButtons();
    },

    /**
     * On WooCommerce added to cart event.
     *
     * @since 1.0.0
     */
    addedToCart: function () {
      techalgospotlightWC.showCartDropdown();
    },

    /**
     * Bind UI actions.
     *
     * @since 1.0.0
     */
    bindUIActions: function () {
      techalgospotlightWC.removeCartItem();
    },

    //--------------------------------------------------------------------//
    // Functions
    //--------------------------------------------------------------------//

    /**
     * Adds plus-munus quantity buttons to WooCommerce.
     *
     * @since 1.0.0
     */
    quantButtons: function () {
      var $newQuantity, $quantity, $input, $this;

      // Append plus and minus buttons to cart quantity.
      var $quantInput = $(
        "div.quantity:not(.appended), td.quantity:not(.appended)"
      ).find(".qty");

      if (
        $quantInput.length &&
        "date" !== $quantInput.prop("type") &&
        "hidden" !== $quantInput.prop("type")
      ) {
        // Add plus and minus icons
        $quantInput.parent().addClass("appended");
        $quantInput.after(
          '<a href="#" class="techalgospotlight-woo-minus">-</a><a href="#" class="techalgospotlight-woo-plus">+</a>'
        );

        $(".techalgospotlight-woo-plus, .techalgospotlight-woo-minus").unbind(
          "click"
        );
        $(".techalgospotlight-woo-plus, .techalgospotlight-woo-minus").on(
          "click",
          function (e) {
            e.preventDefault();

            $this = $(this);
            $input = $this.parent().find("input");
            $quantity = $input.val();
            $newQuantity = 0;

            if ($this.hasClass("techalgospotlight-woo-plus")) {
              $newQuantity = parseInt($quantity) + 1;
            } else {
              if (0 < $quantity) {
                $newQuantity = parseInt($quantity) - 1;
              }
            }

            $input.val($newQuantity);

            // Trigger change.
            $quantInput.trigger("change");
          }
        );
      }
    },

    /**
     * Shows cart dropdown widget for 5 seconds aftern an item has been added to the cart.
     *
     * @since 1.0.0
     */
    showCartDropdown: function () {
      // Exit if header cart dropdown is not available.
      if (!$(".techalgospotlight-header-widget__cart").length) {
        return;
      }

      $(".techalgospotlight-header-widget__cart").addClass("dropdown-visible");

      setTimeout(function () {
        $("#techalgospotlight-header-inner")
          .find(".techalgospotlight-cart")
          .find(".techalgospotlight-cart-count")
          .addClass("animate-pop");
      }, 100);

      if (cartDropdownTimer) {
        clearTimeout(cartDropdownTimer);
        cartDropdownTimer = false;
      }

      cartDropdownTimer = setTimeout(function () {
        $(".techalgospotlight-header-widget__cart")
          .removeClass("dropdown-visible")
          .find(".dropdown-item")
          .removeAttr("style");
      }, 5000);
    },

    /**
     * Adds custom dropdown field for shop orderby.
     *
     * @since 1.0.0
     */
    customDropdown: function () {
      if (!$("form.woocommerce-ordering").length) {
        return;
      }

      var $select = $("form.woocommerce-ordering .orderby");
      var $formWrap = $("form.woocommerce-ordering");
      var $sellOption = $(
        "form.woocommerce-ordering .orderby option:selected"
      ).text();
      var chevronSvg =
        '<svg class="techalgospotlight-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M24.958 10.483a1.29 1.29 0 00-1.868 0l-7.074 7.074-7.074-7.074c-.534-.534-1.335-.534-1.868 0s-.534 1.335 0 1.868l8.008 8.008c.267.267.667.4.934.4s.667-.133.934-.4l8.008-8.008a1.29 1.29 0 000-1.868z"/></svg>';

      $formWrap.append(
        '<span id="techalgospotlight-orderby"><span>' +
          $sellOption +
          "</span>" +
          chevronSvg +
          "</span>"
      );
      $select.addClass("custom-select-loaded");

      var $appended = $("#techalgospotlight-orderby");
      $select.width($appended.width()).css("height", $appended.height() + "px");

      $select.change(function () {
        $appended
          .find("span")
          .html($("form.woocommerce-ordering .orderby option:selected").text());
        $(this).width($appended.width());
      });
    },

    /**
     * Removes an item from cart via ajax.
     *
     * @since 1.0.0
     */
    removeCartItem: function () {
      var $this;

      // Exit if there is no cart item remove button.
      if (!$(".techalgospotlight-remove-cart-item").length) {
        return;
      }

      $wrapper.on("click", ".techalgospotlight-remove-cart-item", function (e) {
        e.preventDefault();
        $this = $(this);

        $this.closest(".techalgospotlight-cart-item").addClass("removing");

        var data = {
          action: "techalgospotlight_remove_wc_cart_item",
          /* eslint-disable camelcase */
          _ajax_nonce: techalgospotlightVars.nonce,
          product_key: $this.data("product_key"),
          /* eslint-enable camelcase */
        };

        $.post(techalgospotlightVars.ajaxurl, data, function (response) {
          if (response.success) {
            $body.trigger("wc_fragment_refresh");
          } else {
            $this
              .closest(".techalgospotlight-cart-item")
              .removeClass("removing");
          }
        });
      });
    },
  }; // END var techalgospotlightWC.

  techalgospotlightWC.init();
  window.techalgospotlight_wc = techalgospotlightWC; // eslint-disable-line camelcase
})(jQuery);
