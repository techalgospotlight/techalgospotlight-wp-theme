(function (api, $) {
  // Extends our custom "techalgospotlight-info" section. Make it visible.
  api.sectionConstructor["techalgospotlight-info"] = api.Section.extend({
    // No events for this type of section.
    attachEvents: function () {},

    // Always make the section active.
    isContextuallyActive: function () {
      return true;
    },
  });

  // Custom Customizer Previewer class.
  api.techalgospotlightCustomizerPreviewer = {
    init: function () {
      var self = this,
        control;

      // Listen to the "set-footer-widget" event.
      this.preview.bind("set-footer-widget", function (data) {
        // Focus control.
        control = api.control("sidebars_widgets[" + data + "]");
        control.focus();

        // Open widgets panel.
        api.Widgets.availableWidgetsPanel.open(control);
      });

      // Listen to the "set-footer-widget" event.
      this.preview.bind("set-navigation-widget", function (data) {
        // Focus control.
        control = api.control("nav_menu_locations[" + data + "]");
        control.focus();
      });
    },
  };

  // Store old previewer.
  var techalgospotlightOldPreviewer = api.Previewer;
  api.Previewer = techalgospotlightOldPreviewer.extend({
    initialize: function (params, options) {
      // Store a reference to the Previewer
      api.techalgospotlightCustomizerPreviewer.preview = this;

      // Call the old Previewer's initialize function
      techalgospotlightOldPreviewer.prototype.initialize.call(
        this,
        params,
        options
      );
    },
  });

  api("techalgospotlight_info_style", function (setting) {
    setting.bind(function (value) {
      if (12 == value) {
        var data = {
          desktop: { top: 0, bottom: 0 },
          tablet: { top: 0, bottom: 0 },
          mobile: { top: 6, bottom: 6 },
          unit: "rem",
        };

        api.control("techalgospotlight_info_section_spacing").setting(data);

        // console.log( 'New value', api.control( 'techalgospotlight_info_section_spacing' ).setting() );
      } else {
        var data = {
          desktop: { top: 10, bottom: 10 },
          tablet: { top: 6, bottom: 6 },
          mobile: { top: 6, bottom: 6 },
          unit: "rem",
        };

        api.control("techalgospotlight_info_section_spacing").setting(data);

        // console.log( 'New value', api.control( 'techalgospotlight_info_section_spacing' ).setting() );
      }
      var selector = api.control(
        "techalgospotlight_info_section_spacing"
      ).selector;
      setSpacingFieldValue(selector, data);
    });
  });

  function setSpacingFieldValue(selector, data) {
    for (const [device, values] of Object.entries(data)) {
      if (!["desktop", "tablet", "mobile"].includes(device)) {
        continue;
      }
      for (const [position, value] of Object.entries(values)) {
        var s =
          selector +
          ' input[name="spacing-control-' +
          device +
          "-" +
          position +
          '"]';
        var input = $(s);
        if (input.length) {
          input.val(value);
        }
      }
    }
  }

  // Change preview url for certain sections.
  _.each(
    techalgospotlight_customizer_localized.preview_url_for_section,
    function (url, id) {
      // eslint-disable-line camelcase
      if (url) {
        wp.customize.section(id, function (section) {
          section.expanded.bind(function (isExpanded) {
            if (isExpanded) {
              wp.customize.previewer.previewUrl.set(url);
            }
          });
        });
      }
    }
  );

  $(document).ready(function ($) {
    // Initialize our Previewer
    api.techalgospotlightCustomizerPreviewer.init();

    // Display the first responsive control
    $(".techalgospotlight-control-responsive").each(function () {
      $(this).find(".control-responsive").first().addClass("active");
    });

    // Responsive switchers
    $(".customize-control").on(
      "click",
      ".techalgospotlight-responsive-switchers span",
      function (event) {
        var $this = $(this),
          $switcherContainer = $this.closest(
            ".techalgospotlight-responsive-switchers"
          ),
          $switcherButtons = $switcherContainer.find("li span"),
          $device = $(event.currentTarget).data("device"),
          $control = $(".techalgospotlight-control-responsive"),
          $body = $(".wp-full-overlay"),
          $footerDevices = $(".wp-full-overlay-footer .devices");

        if (!$switcherContainer.hasClass("expanded")) {
          $switcherContainer.addClass("expanded");
          $this.addClass("active");
        } else {
          if ($this.parent().is(":first-child")) {
            if ($this.hasClass("active")) {
              $switcherContainer.removeClass("expanded");
              $this.removeClass("active");
            } else {
              $switcherButtons.removeClass("active");
              $this.addClass("active");
            }
          } else {
            $switcherButtons.removeClass("active");
            $this.addClass("active");
          }
        }

        // Control class
        $control.find(".control-responsive").removeClass("active");
        $control.find(".control-responsive." + $device).addClass("active");
        $control
          .removeClass(
            "control-device-desktop control-device-tablet control-device-mobile"
          )
          .addClass("control-device-" + $device);

        // Wrapper class
        $body
          .removeClass("preview-desktop preview-tablet preview-mobile")
          .addClass("preview-" + $device);

        // Panel footer buttons
        $footerDevices
          .find("button")
          .removeClass("active")
          .attr("aria-pressed", false);
        $footerDevices
          .find("button.preview-" + $device)
          .addClass("active")
          .attr("aria-pressed", true);
      }
    );

    // If panel footer buttons clicked
    $(".wp-full-overlay-footer .devices button").on("click", function (event) {
      // Set up variables
      var $this = $(this),
        $devices = $(
          ".customize-control .techalgospotlight-responsive-switchers"
        ),
        $device = $(event.currentTarget).data("device"),
        $control = $(".techalgospotlight-control-responsive");

      // Button class
      $devices.find("span").removeClass("active");
      $devices.find("span.preview-" + $device).addClass("active");

      // Add expanded class
      if ("desktop" === $device) {
        $devices.removeClass("expanded");
      } else {
        $devices.addClass("expanded");
      }

      // Control class
      $control.find(".control-responsive").removeClass("active");
      $control.find(".control-responsive." + $device).addClass("active");
      $control
        .removeClass(
          "control-device-desktop control-device-tablet control-device-mobile"
        )
        .addClass("control-device-" + $device);
    });

    setTimeout(() => {
      // Tooltip positioning
      if ($(".techalgospotlight-tooltip").length) {
        var $tooltip, $iconPosLeft, $iconPosRight, $titleWidth;

        $(".techalgospotlight-tooltip").each(function () {
          $tooltip = $(this);

          if (
            $tooltip.hasClass("top-right-tooltip") ||
            $tooltip.hasClass("small-tooltip")
          ) {
            return;
          }

          $titleWidth = $tooltip
            .closest(".techalgospotlight-control-wrapper")
            .outerWidth();

          $iconPosLeft = $tooltip
            .closest(".techalgospotlight-info-icon")
            .css("position", "static")
            .position().left;
          $iconPosRight = $titleWidth - $iconPosLeft;

          if ($iconPosLeft < $iconPosRight) {
            $tooltip[0].style.setProperty(
              "--tooltip-left",
              Math.min(104, $iconPosLeft) + "px"
            );
            $tooltip.css("left", Math.max(0, $iconPosLeft - 104));
          } else {
            $tooltip.css(
              "left",
              Math.min($iconPosLeft - 90, $titleWidth - 208)
            );

            if ($iconPosLeft < $titleWidth - 104) {
              $tooltip[0].style.setProperty("--tooltip-left", "90px");
            } else {
              $tooltip[0].style.setProperty(
                "--tooltip-left",
                $iconPosLeft - 178 + "px"
              );
            }
          }
        });
      }
    }, 2000);

    api.hasOwnProperty("previewer") &&
      (api.previewer.bind(
        "techalgospotlight-customize-disable-section",
        function (e) {
          $("input[id=" + e + "]")
            .trigger("click")
            .trigger("change");
          const val = !api.control(e).setting.get();
          api.control(e).setting.set(val);
        }
      ),
      api.previewer.bind(
        "techalgospotlight-customize-focus-control",
        function (e) {
          api.control(e).focus();
        }
      ));

    //Scroll to section
    $("body").on(
      "click",
      "#sub-accordion-panel-techalgospotlight_panel_homepage > .accordion-section.control-subsection",
      function (event) {
        scrollToSection($(this).attr("id").substr(18));
      }
    );

    function scrollToSection(section) {
      var $contents = jQuery("#customize-preview iframe").contents();

      if (0 < $contents.find("." + section).length) {
        $contents.find("html, body").animate(
          {
            scrollTop: $contents.find("." + section).offset().top,
          },
          1000
        );
      }
    }
  });
})(wp.customize, jQuery);
