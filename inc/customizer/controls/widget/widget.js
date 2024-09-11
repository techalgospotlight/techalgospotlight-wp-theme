(function ($) {
  "use strict";

  wp.customize.controlConstructor["techalgospotlight-widget"] =
    wp.customize.Control.extend({
      ready: function () {
        "use strict";

        var control = this;

        control.widget_count = control.container.find(".widget").length;
        control.setupSortable();

        // Expand widget content on header click
        control.container.on(
          "click",
          ".techalgospotlight-widget-container .widget-top",
          function () {
            $(this)
              .closest(".widget")
              .toggleClass("techalgospotlight-expanded")
              .find(".widget-inside")
              .slideToggle(200);
          }
        );

        // Minimize widget content when clicked on Done
        control.container.on(
          "click",
          ".techalgospotlight-widget-container .widget-control-close",
          function () {
            $(this)
              .closest(".widget")
              .toggleClass("techalgospotlight-expanded")
              .find(".widget-inside")
              .slideToggle(200);
          }
        );

        // Show available widgets
        control.container.on(
          "click",
          ".techalgospotlight-add-widget",
          function (e) {
            e.preventDefault();
            control.updateList();
          }
        );

        control.container.on(
          "change paste keyup",
          "input, textarea, select",
          function (e) {
            control.update();
          }
        );

        control.container.on("click", ".widget-control-remove", function () {
          $(this).closest(".widget").remove();
          control.update();
          control.updateList();
        });

        control.container.on(
          "click",
          ".techalgospotlight-widget-edit-nav",
          function () {
            wp.customize
              .control(
                "nav_menu_locations[" +
                  $(this)
                    .closest(".techalgospotlight-widget-nav-container")
                    .data("menu-location") +
                  "]"
              )
              .focus();
            control.close();
          }
        );

        // Close the panel if the URL in the preview changes
        wp.customize.previewer.bind("url", this.close);

        $(control.container)
          .find(".techalgospotlight-widget-nav-container")
          .each(function () {
            var $this = $(this);
            control.bindMenuLocation($this);
          });

        // Image upload functionality
        control.container.on("click", ".widget-media-upload", function (e) {
          e.preventDefault();
          const $parentEl = $(this).closest(".widget");
          control.mediaUploader = wp.media({
            title: "Choose Image",
            button: {
              text: "Select",
            },
            multiple: false,
          });

          control.mediaUploader.on("select", function () {
            var attachment = control.mediaUploader
              .state()
              .get("selection")
              .first()
              .toJSON();
            $parentEl
              .find(".banner-preview")
              .html(
                '<img src="' +
                  attachment.url +
                  '" alt="Preview" style="max-width: 100%; height: auto;">'
              );

            // Update value attribute with image object
            $parentEl
              .find(".widget-media-upload")
              .prev()
              .val(JSON.stringify(attachment.id))
              .trigger("change"); // Trigger change event

            $parentEl.find(".widget-media-upload").addClass("hide");
            $parentEl.find(".remove-image").removeClass("hide");
          });

          control.mediaUploader.open();
        });

        // Remove image functionality
        control.container.on("click", ".remove-image", function (e) {
          e.preventDefault();
          const $parentEl = $(this).closest(".widget");
          $parentEl.find(".banner-preview").html("");

          // Clear data-array attribute and value attribute
          $parentEl
            .find(".widget-media-upload")
            .prev()
            .val("")
            .trigger("change"); // Trigger change event

          $parentEl.find(".widget-media-upload").removeClass("hide");
          $parentEl.find(".remove-image").addClass("hide");
        });

        // Trigger change event for display area checkboxes
        control.container.on(
          "change",
          '[name^="widget-ad"] [data-option-name="display_area"]',
          function () {
            $(this).trigger("change"); // Trigger change event
          }
        );
      },

      bindMenuLocation: function ($container) {
        var menu_location = $container.data("menu-location");

        // Bind menu location setting
        wp.customize(
          "nav_menu_locations[" + menu_location + "]",
          function (value) {
            value.bind(function (newval) {
              if (newval) {
                var menu_name = wp.customize
                  .control("nav_menu_locations[" + menu_location + "]")
                  .container.find("option:selected")
                  .html();

                $container
                  .addClass("techalgospotlight-widget-nav-has-menu")
                  .find(".techalgospotlight-widget-nav-name")
                  .html(menu_name);
              } else {
                $container.removeClass("techalgospotlight-widget-nav-has-menu");
              }
            });
          }
        );
      },

      // Changes visibility of available widgets
      updateList: function () {
        var widget,
          self = this,
          widgets = self.params.widgets;

        // Filter which widgets are available.
        if (widgets) {
          // Hide all widgets.
          $(
            "#techalgospotlight-available-widgets-list .techalgospotlight-widget"
          )
            .hide()
            .removeClass("disabled");

          // Display allowed widgets.
          $.each(widgets, function (index, el) {
            widget = $(
              "#techalgospotlight-available-widgets-list #techalgospotlight-widget-tpl-techalgospotlight_customizer_widget_" +
                index
            );

            widget.show();

            if (
              el.hasOwnProperty("max_uses") &&
              el.max_uses > 0 &&
              el.max_uses <=
                $(self.container).find(
                  '.techalgospotlight-widget-container [data-widget-type="' +
                    index +
                    '"]'
                ).length
            ) {
              widget.addClass("disabled");
            }
          });
        } else {
          // Show all widgets
          $(
            "#techalgospotlight-available-widgets-list .techalgospotlight-widget"
          ).show();
        }
      },

      addWidget: function (widget_id_base) {
        var widget_html, widget_uuid;

        widget_uuid = this.setting.id + "-" + this.widget_count;

        // Get widget form
        widget_html = $.trim(
          $(this.container)
            .find(".techalgospotlight-widget-tpl-" + widget_id_base)
            .html()
        );
        widget_html = widget_html.replace(/<[^<>]+>/g, function (m) {
          return m.replace(/__i__|%i%/g, widget_uuid);
        });

        // Append new widget.
        var $widget = $(widget_html).appendTo(
          this.container.find(".techalgospotlight-widget-container")
        );

        // Increase widget count.
        this.widget_count++;

        // Expand the widget and focus first setting.
        $widget.find(".widget-top").trigger("click");

        this.update();

        if ($widget.find(".techalgospotlight-widget-nav-container").length) {
          this.bindMenuLocation(
            $widget.find(".techalgospotlight-widget-nav-container")
          );
        }
      },

      close: function () {
        $("body").removeClass("techalgospotlight-adding-widget");
      },

      update: function () {
        // Get all widgets in the area
        var widgets = this.container.find(
          ".techalgospotlight-widget-container .widget"
        );
        var inputs,
          widgetobj,
          new_value = [],
          option,
          checked,
          $widget;

        if (widgets.length) {
          // Get from each widfget
          _.each(widgets, function (widget) {
            $widget = $(widget);
            widgetobj = {};
            widgetobj.classname = $widget.data("widget-base");
            widgetobj.type = $widget.data("widget-type");
            widgetobj.values = {};

            inputs = $widget.find("input, textarea, select");

            _.each(inputs, function (input) {
              //don't save value which has multiple choice options
              if ($(input).data("input-type") == "multiple") return;

              option = $(input).attr("data-option-name");

              // Save values.
              if (typeof option !== typeof undefined && option !== false) {
                widgetobj.values[option] = $(input).val();
              }
            });

            _.each($widget.find(".buttonset"), function (buttonset) {
              // Save location if exist.
              checked = $(buttonset).find('input[type="radio"]:checked');

              // Save values.
              if (typeof checked !== typeof undefined && checked !== false) {
                widgetobj.values[checked.data("option-name")] = checked.val();
              }
            });

            _.each($widget.find('input[type="checkbox"]'), function (checkbox) {
              var $checkboxEl = $(checkbox);
              var option = $checkboxEl.data("option-name");

              if (typeof checkbox !== typeof undefined && checkbox !== false) {
                if ($checkboxEl.data("input-type") == "multiple") {
                  if ($checkboxEl.is(":checked")) {
                    if (!widgetobj.values[option]) {
                      widgetobj.values[option] = [];
                    }

                    widgetobj.values[option].push($checkboxEl.val());
                  } else {
                    // Remove previously saved values that are not checked/selected
                    const index = widgetobj.values[option]?.indexOf(
                      $checkboxEl.val()
                    );
                    if (index > -1) widgetobj.values[option]?.splice(index, 1);
                  }
                } else if ($checkboxEl.is(":checked")) {
                  widgetobj.values[option] = $checkboxEl.val();
                }
              }
            });

            new_value.push(widgetobj);
          });

          this.setting.set(new_value);
        } else {
          this.setting.set(false);
        }
      },

      setupSortable: function () {
        var self = this;

        $(this.container)
          .find(".techalgospotlight-widget-container")
          .sortable({
            items: "> .widget",
            handle: ".widget-top",
            intersect: "pointer",
            axis: "y",
            update: function () {
              self.update();
            },
          });
      },
    });

  $(document).ready(function () {
    var control;

    $(".wp-full-overlay").on(
      "click",
      ".techalgospotlight-add-widget, .techalgospotlight-close-widgets-panel",
      function (e) {
        e.preventDefault();

        $("body").toggleClass("techalgospotlight-adding-widget");

        if ($(this).data("location-title")) {
          control = wp.customize.control($(this).data("control"));
          $("#techalgospotlight-available-widgets")
            .attr("data-control", control.params.id)
            .find(".techalgospotlight-widget-caption")
            .find("h3")
            .html($(this).data("location-title"));
        }
      }
    );

    $(".wp-full-overlay").on("click", ".customize-section-back", function (e) {
      $("body").removeClass("techalgospotlight-adding-widget");
      $("#techalgospotlight-available-widgets").removeAttr("data-control");
    });

    // Add widget to widget control.
    $("#techalgospotlight-available-widgets").on(
      "click",
      ".techalgospotlight-widget",
      function (e) {
        // Get active control.
        control = wp.customize.control(
          $("#techalgospotlight-available-widgets").attr("data-control")
        );

        var widget_id = $(this).data("widget-id");
        var widget_form = control.addWidget(widget_id);

        control.close();
      }
    );
  });
})(jQuery);
