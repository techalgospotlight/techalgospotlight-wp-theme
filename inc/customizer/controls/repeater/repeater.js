/*
  jQuery deparam is an extraction of the deparam method from Ben Alman's jQuery BBQ
  http://benalman.com/projects/jquery-bbq-plugin/
*/
function techalgospotlightLinkField($el) {
  this.$el = $el; // Adjust the selector to get the container

  const setupEventListeners = () => {
    this.$el.on("click", 'a[data-name="add"]', onClickEdit);
    this.$el.on("click", 'a[data-name="edit"]', onClickEdit);
    this.$el.on("click", 'a[data-name="remove"]', onClickRemove);
    this.$el.on("change", ".link-node", onChange);

    if (typeof wpLink !== "undefined") {
      wpLink.init(); // Initialize wpLink if it's not already initialized
    }
  };

  const control = () => {
    return this.$el;
  };

  const node = () => {
    return this.$el.find(".link-node");
  };

  const getValue = (e) => {
    var $node = node();

    if (!$node.attr("href")) {
      return false;
    }

    return {
      title: $node.html(),
      url: $node.attr("href"),
      target: $node.attr("target"),
    };
  };

  const setValue = (val, e) => {
    val = val || { title: "", url: "", target: "" };

    var $div = control();
    var $node = node();

    $div.removeClass("-value -external");

    if (val.url) $div.addClass("-value");
    if (val.target === "_blank") $div.addClass("-external");

    this.$el.find(".link-title").html(val.title);
    var url =
      val?.url?.length > 25 ? val.url.substring(0, 22) + "..." : val.url;
    this.$el
      .find(".link-url")
      .attr("href", val.url)
      .html(url)
      .attr("title", val.url);

    $node.html(val.title);
    $node.attr("href", val.url);
    $node.attr("target", val.target);

    this.$el.find(".input-title").val(val.title);
    this.$el.find(".input-target").val(val.target);
    this.$el.find(".input-url").val(val.url).trigger("change");
  };

  const onClickEdit = (e) => {
    e.preventDefault();
    open();
  };

  const onClickRemove = (e) => {
    e.preventDefault();
    setValue(false);
  };

  const onChange = (e) => {
    var val = getValue(e);
    setValue(val, e);
  };

  const getNodeValue = () => {
    var $node = node();
    return {
      title: $node.html(),
      url: $node.attr("href"),
      target: $node.attr("target"),
    };
  };

  const setNodeValue = (val) => {
    var $node = node();
    $node.text(val.title);
    $node.attr("href", val.url);
    $node.attr("target", val.target);
    $node.trigger("change");
  };

  const getInputValue = () => {
    return {
      title: jQuery("#wp-link-text").val(),
      url: jQuery("#wp-link-url").val(),
      target: jQuery("#wp-link-target").prop("checked") ? "_blank" : "",
    };
  };

  const setInputValue = (val) => {
    jQuery("#wp-link-text").val(val.title);
    jQuery("#wp-link-url").val(val.url);
    jQuery("#wp-link-target").prop("checked", val.target === "_blank");
  };

  const open = () => {
    jQuery(document).on("wplink-open", onOpen);
    jQuery(document).on("wplink-close", onClose);

    var $textarea = jQuery(
      '<textarea id="techalgospotlight-link-textarea" style="display:none;"></textarea>'
    );
    if (!jQuery("#techalgospotlight-link-textarea").length)
      jQuery("body").append($textarea);

    var val = getNodeValue();
    wpLink.open("techalgospotlight-link-textarea", val.url, val.title, null);
  };

  const onOpen = () => {
    // always show title (WP will hide title if empty)
    jQuery("#wp-link-wrap").addClass("has-text-field"); // set inputs
    jQuery(document).one("click", "#wp-link-submit", onWPLinkInsert);
    var val = getNodeValue();
    setInputValue(val); // Update button text.

    if (val.url && wpLinkL10n) {
      jQuery("#wp-link-submit").val(wpLinkL10n.update);
    }
  };

  const onClose = () => {
    var $submit = jQuery("#wp-link-submit");
    var isSubmit = $submit.is(":hover") || $submit.is(":focus"); // Set value

    if (isSubmit) {
      var val = getInputValue();
      setNodeValue(val);
    } // Cleanup.

    jQuery(document).off("wplink-open");
    jQuery(document).off("wplink-close");
    jQuery("#techalgospotlight-link-textarea").remove();
  };

  const onWPLinkInsert = (e) => {
    e.stopPropagation();
    var data = getInputValue();
    if (data.url) {
      var val = {
        title: data.title,
        url: data.url,
        target: data.target,
      };

      setNodeValue(val);
    }
  };

  return {
    init: function () {
      setupEventListeners();
    },
  };
}

(function ($) {
  $.deparam = function (params, coerce) {
    var obj = {},
      coerce_types = {
        true: !0,
        false: !1,
        null: null,
      };

    // Iterate over all name=value pairs.
    $.each(params.replace(/\+/g, " ").split("&"), function (j, v) {
      var param = v.split("="),
        key = decodeURIComponent(param[0]),
        val,
        cur = obj,
        i = 0,
        // If key is more complex than 'foo', like 'a[]' or 'a[b][c]', split it
        // into its component parts.
        keys = key.split("]["),
        keys_last = keys.length - 1;

      // If the first keys part contains [ and the last ends with ], then []
      // are correctly balanced.
      if (/\[/.test(keys[0]) && /\]$/.test(keys[keys_last])) {
        // Remove the trailing ] from the last keys part.
        keys[keys_last] = keys[keys_last].replace(/\]$/, "");

        // Split first keys part into two parts on the [ and add them back onto
        // the beginning of the keys array.
        keys = keys.shift().split("[").concat(keys);
        keys_last = keys.length - 1;
      } else {
        // Basic 'foo' style key.
        keys_last = 0;
      }

      // Are we dealing with a name=value pair, or just a name?
      if (2 === param.length) {
        val = decodeURIComponent(param[1]);

        // Coerce values.
        if (coerce) {
          val =
            val && !isNaN(val)
              ? +val // number
              : "undefined" === val
              ? undefined // undefined
              : coerce_types[val] !== undefined
              ? coerce_types[val] // true, false, null
              : val; // string
        }
        if (keys_last) {
          // Complex key, build deep object structure based on a few rules:
          // * The 'cur' pointer starts at the object top-level.
          // * [] = array push (n is set to array length), [n] = array if n is
          //   numeric, otherwise object.
          // * If at the last keys part, set the value.
          // * For each keys part, if the current level is undefined create an
          //   object or array based on the type of the next keys part.
          // * Move the 'cur' pointer to the next level.
          // * Rinse & repeat.
          for (; i <= keys_last; i++) {
            key = "" === keys[i] ? cur.length : keys[i];
            cur = cur[key] =
              i < keys_last
                ? cur[key] || (keys[i + 1] && isNaN(keys[i + 1]) ? {} : [])
                : val;
          }
        } else {
          // Simple key, even simpler rules, since only scalars and shallow
          // arrays are allowed.
          if ($.isArray(obj[key])) {
            // val is already an array, so push on the next value.
            obj[key].push(val);
          } else if (obj[key] !== undefined) {
            // val isn't an array, but since a second value has been specified,
            // convert val into an array.
            obj[key] = [obj[key], val];
          } else {
            // val is a scalar.
            obj[key] = val;
          }
        }
      } else if (key) {
        // No value was defined, so set something meaningful.
        obj[key] = coerce ? undefined : "";
      }
    });
    return obj;
  };
})(jQuery);

// COLOR ALPHA -----------------------------
/**
 * Alpha Color Picker JS
 */
(function ($) {
  /**
   * Override the stock color.js toString() method to add support for
   * outputting RGBa or Hex.
   */
  Color.prototype.toString = function (flag) {
    // If our no-alpha flag has been passed in, output RGBa value with 100% opacity.
    // This is used to set the background color on the opacity slider during color changes.
    if ("no-alpha" == flag) {
      return this.toCSS("rgba", "1").replace(/\s+/g, "");
    }

    // If we have a proper opacity value, output RGBa.
    if (1 > this._alpha) {
      return this.toCSS("rgba", this._alpha).replace(/\s+/g, "");
    }

    // Proceed with stock color.js hex output.
    var hex = parseInt(this._color, 10).toString(16);
    if (this.error) {
      return "";
    }
    if (6 > hex.length) {
      for (var i = 6 - hex.length - 1; 0 <= i; i--) {
        hex = "0" + hex;
      }
    }
    return "#" + hex;
  };

  /**
   * Given an RGBa, RGB, or hex color value, return the alpha channel value.
   */
  function acp_get_alpha_value_from_color(value) {
    var alphaVal;

    // Remove all spaces from the passed in value to help our RGBa regex.
    value = value.replace(/ /g, "");
    if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
      alphaVal =
        parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]).toFixed(
          2
        ) * 100;
      alphaVal = parseInt(alphaVal);
    } else {
      alphaVal = 100;
    }
    return alphaVal;
  }

  /**
   * Force update the alpha value of the color picker object and maybe the alpha slider.
   */
  function acp_update_alpha_value_on_color_input(
    alpha,
    $input,
    $alphaSlider,
    update_slider
  ) {
    var iris, colorPicker, color;
    iris = $input.data("a8cIris");
    colorPicker = $input.data("wpWpColorPicker");

    // Set the alpha value on the Iris object.
    iris._color._alpha = alpha;

    // Store the new color value.
    color = iris._color.toString();

    // Set the value of the input.
    $input.val(color);
    $input.trigger("color_change");

    // Update the background color of the color picker.
    colorPicker.toggler.css({
      "background-color": color,
    });

    // Maybe update the alpha slider itself.
    if (update_slider) {
      acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);
    }

    // Update the color value of the color picker object.
    $input.wpColorPicker("color", color);
  }

  /**
   * Update the slider handle position and label.
   */
  function acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider) {
    $alphaSlider.slider("value", alpha);
    $alphaSlider.find(".ui-slider-handle").text(alpha.toString());
  }
  $.fn.alphaColorPicker = function () {
    return this.each(function () {
      // Scope the vars.
      var $input,
        startingColor,
        paletteInput,
        showOpacity,
        defaultColor,
        palette,
        colorPickerOptions,
        $container,
        $alphaSlider,
        alphaVal,
        sliderOptions;

      // Store the input.
      $input = $(this);

      // We must wrap the input now in order to get our a top level class
      // around the HTML added by wpColorPicker().
      $input.wrap('<div class="alpha-color-picker-wrap"></div>');

      // Get some data off the input.
      paletteInput = $input.attr("data-palette") || "true";
      showOpacity = $input.attr("data-show-opacity") || "true";
      defaultColor = $input.attr("data-default-color") || "";

      // Process the palette.
      if (-1 !== paletteInput.indexOf("|")) {
        palette = paletteInput.split("|");
      } else if ("false" == paletteInput) {
        palette = false;
      } else {
        palette = true;
      }

      // Get a clean starting value for the option.
      startingColor = $input.val().replace(/\s+/g, "");

      //startingColor = $input.val().replace( '#', '' );

      // If we don't yet have a value, use the default color.
      if ("" == startingColor) {
        startingColor = defaultColor;
      }

      // Set up the options that we'll pass to wpColorPicker().
      colorPickerOptions = {
        change: function (event, ui) {
          var key, value, alpha, $transparency;
          key = $input.attr("data-customize-setting-link");
          value = $input.wpColorPicker("color");

          // Set the opacity value on the slider handle when the default color button is clicked.
          if (defaultColor == value) {
            alpha = acp_get_alpha_value_from_color(value);
            $alphaSlider.find(".ui-slider-handle").text(alpha);
          }

          // If we're in the Customizer, send an ajax request to wp.customize
          // to trigger the Save action.
          if ("undefined" != typeof wp.customize) {
            wp.customize(key, function (obj) {
              obj.set(value);
            });
          }
          $transparency = $container.find(".transparency");

          // Always show the background color of the opacity slider at 100% opacity.
          $transparency.css("background-color", ui.color.toString("no-alpha"));
          $input.trigger("color_change");
        },
        clear: function () {
          var key = $input.attr("data-customize-setting-link") || "";
          if (key && "" !== key) {
            if ("undefined" != typeof wp.customize) {
              wp.customize(key, function (obj) {
                obj.set("");
              });
            }
          }
          $input.val("");
          $input.trigger("color_change");
        },
        palettes: palette, // Use the passed in palette.
      };

      // Create the colorpicker.
      $input.wpColorPicker(colorPickerOptions);
      $container = $input.parents(".wp-picker-container:first");

      // Insert our opacity slider.
      $(
        '<div class="alpha-color-picker-container">' +
          '<div class="min-click-zone click-zone"></div>' +
          '<div class="max-click-zone click-zone"></div>' +
          '<div class="alpha-slider"></div>' +
          '<div class="transparency"></div>' +
          "</div>"
      ).appendTo($container.find(".wp-picker-holder"));
      $alphaSlider = $container.find(".alpha-slider");

      // If starting value is in format RGBa, grab the alpha channel.
      alphaVal = acp_get_alpha_value_from_color(startingColor);

      // Set up jQuery UI slider() options.
      sliderOptions = {
        create: function (event, ui) {
          var value = $(this).slider("value");

          // Set up initial values.
          $(this).find(".ui-slider-handle").text(value);
          $(this)
            .siblings(".transparency ")
            .css("background-color", startingColor);
        },
        value: alphaVal,
        range: "max",
        step: 1,
        min: 0,
        max: 100,
        animate: 300,
      };

      // Initialize jQuery UI slider with our options.
      $alphaSlider.slider(sliderOptions);

      // Maybe show the opacity on the handle.
      if ("true" == showOpacity) {
        $alphaSlider.find(".ui-slider-handle").addClass("show-opacity");
      }

      // Bind event handlers for the click zones.
      $container.find(".min-click-zone").on("click", function () {
        acp_update_alpha_value_on_color_input(0, $input, $alphaSlider, true);
      });
      $container.find(".max-click-zone").on("click", function () {
        acp_update_alpha_value_on_color_input(100, $input, $alphaSlider, true);
      });

      // Bind event handler for clicking on a palette color.
      $container.find(".iris-palette").on("click", function () {
        var color, alpha;
        color = $(this).css("background-color");
        alpha = acp_get_alpha_value_from_color(color);
        acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);

        // Sometimes Iris doesn't set a perfect background-color on the palette,
        // for example rgba(20, 80, 100, 0.3) becomes rgba(20, 80, 100, 0.298039).
        // To compensante for this we round the opacity value on RGBa colors here
        // and save it a second time to the color picker object.
        if (100 != alpha) {
          color = color.replace(/[^,]+(?=\))/, (alpha / 100).toFixed(2));
        }
        $input.wpColorPicker("color", color);
      });

      // Bind event handler for clicking on the 'Default' button.
      $container.find(".button.wp-picker-default").on("click", function () {
        var alpha = acp_get_alpha_value_from_color(defaultColor);
        acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);
      });

      // Bind event handler for typing or pasting into the input.
      $input.on("input", function () {
        var value = $(this).val();
        var alpha = acp_get_alpha_value_from_color(value);
        acp_update_alpha_value_on_alpha_slider(alpha, $alphaSlider);
      });

      // Update all the things when the slider is interacted with.
      $alphaSlider.slider().on("slide", function (event, ui) {
        var alpha = parseFloat(ui.value) / 100.0;
        acp_update_alpha_value_on_color_input(
          alpha,
          $input,
          $alphaSlider,
          false
        );

        // Change value shown on slider handle.
        $(this).find(".ui-slider-handle").text(ui.value);
      });
    });
  };
})(jQuery);

// WP COLOR ALPHA customizer -----------------------------
(function (api, $) {
  api.controlConstructor["alpha-color"] = api.Control.extend({
    ready: function () {
      var control = this;
      $(".alpha-color-control", control.container).alphaColorPicker({
        clear: function (event, ui) {},
      });
    },
  });
})(wp.customize, jQuery);

// WP REPEATERABLE Customizer -----------------------------
(function (api, $) {
  api.controlConstructor["techalgospotlight-repeater"] = api.Control.extend({
    ready: function () {
      var control = this;
      setTimeout(function () {
        control._init();
      }, 2500);
    },
    eval: function (valueIs, valueShould, operator) {
      switch (operator) {
        case "not_in":
          valueShould = valueShould.split(",");
          if (0 > $.inArray(valueIs, valueShould)) {
            return true;
          } else {
            return false;
          }
          break;
        case "in":
          valueShould = valueShould.split(",");
          if (-1 < $.inArray(valueIs, valueShould)) {
            return true;
          } else {
            return false;
          }
          break;
        case "!=":
          return valueIs != valueShould;
        case "<=":
          return valueIs <= valueShould;
        case "<":
          return valueIs < valueShould;
        case ">=":
          return valueIs >= valueShould;
        case ">":
          return valueIs > valueShould;
        case "==":
        case "=":
          return valueIs == valueShould;
          break;
      }
    },
    compare: function (value1, cond, value2) {
      var equal = false;
      var _v;
      switch (cond) {
        case "===":
          equal = value1 === value2 ? true : false;
          break;
        case ">":
          equal = value1 > value2 ? true : false;
          break;
        case "<":
          equal = value1 < value2 ? true : false;
          break;
        case "!=":
          equal = value1 != value2 ? true : false;
          break;
        case "empty":
          _v = _.clone(value1);
          if (_.isObject(_v) || _.isArray(_v)) {
            _.each(_v, function (v, i) {
              if (_.isEmpty(v)) {
                delete _v[i];
              }
            });
            equal = _.isEmpty(_v) ? true : false;
          } else {
            equal = _.isNull(_v) || "" == _v ? true : false;
          }
          break;
        case "not_empty":
          _v = _.clone(value1);
          if (_.isObject(_v) || _.isArray(_v)) {
            _.each(_v, function (v, i) {
              if (_.isEmpty(v)) {
                delete _v[i];
              }
            });
          }
          equal = _.isEmpty(_v) ? false : true;
          break;
        default:
          equal = value1 == value2 ? true : false;
      }
      return equal;
    },
    multiple_compare: function (list, values) {
      var control = this;
      var check = true;
      try {
        var test = list[0];
        check = true;
        if (_.isString(test)) {
          check = false;
          var cond = list[1];
          var cond_val = list[2];
          var value;
          if (!_.isUndefined(values[test])) {
            value = values[test];
            check = control.compare(value, cond, cond_val);
          }
        } else if (_.isArray(test)) {
          check = true;
          _.each(list, function (req) {
            var cond_key = req[0];
            var cond_cond = req[1];
            var cond_val = req[2];
            var t_val = values[cond_key];
            if (_.isUndefined(t_val)) {
              t_val = "";
            }
            if (!control.compare(t_val, cond_cond, cond_val)) {
              check = false;
            }
          });
        }
      } catch (e) {
        check = false;
      }
      return check;
    },
    conditionize: function ($context) {
      var control = this;
      if ($context.hasClass("conditionized")) {
        return;
      }
      $context.addClass("conditionized");
      var $fields = $(".field--item", $context);
      $context.on(
        "change condition_check",
        "input, select, textarea",
        function (e) {
          var f = $(".form", $context);
          var data = $("input, textarea, select", f).serialize();
          data = jQuery.deparam(data);
          var fieldData = {};
          if (_.isObject(data)) {
            _.each(data._items, function (value) {
              fieldData = value;
            });
          }
          $fields.each(function () {
            var $field = $(this);
            var check = true;
            var req = $field.attr("data-cond") || false;
            if (!_.isUndefined(req) && req) {
              req = JSON.parse(req);
              check = control.multiple_compare(req, fieldData);
              if (!check) {
                $field.hide().addClass("cond-hide").removeClass("cond-show");
              } else {
                $field
                  .slideDown()
                  .removeClass("cond-hide")
                  .addClass("cond-show");
              }
            }
          });
        }
      );

      /**
       * Current support one level only
       */
      $("input, select, textarea", $context).eq(0).trigger("condition_check");
    },
    remove_editor: function ($context) {},
    editor: function ($textarea) {},
    _init: function () {
      var control = this;
      var default_data = control.params.fields;
      var values;
      try {
        if ("string" == typeof control.params.value) {
          values = JSON.parse(control.params.value);
        } else {
          values = control.params.value;
        }
      } catch (e) {
        values = {};
      }
      var max_item = 0; // unlimited
      var limited_mg = control.params.limited_msg || "";
      if (!isNaN(parseInt(control.params.max_item))) {
        max_item = parseInt(control.params.max_item);
      }
      if ("no" === control.params.changeable) {
        // control.container.addClass( 'no-changeable' );
      }

      /**
       * Toggle show/hide item
       */
      control.container.on(
        "click",
        ".widget .widget-action, .widget .repeat-control-close, .widget-title",
        function (e) {
          e.preventDefault();
          var p = $(this).closest(".widget");
          if (p.hasClass("explained")) {
            $(".widget-inside", p).slideUp(200, "linear", function () {
              $(".widget-inside", p).removeClass("show").addClass("hide");
              p.removeClass("explained");
            });
          } else {
            $(".widget-inside", p).slideDown(200, "linear", function () {
              $(".widget-inside", p).removeClass("hide").addClass("show");
              p.addClass("explained");
            });
          }
        }
      );

      /**
       * Remove repeater item
       */
      control.container.on("click", ".repeat-control-remove", function (e) {
        e.preventDefault();
        var $context = $(this).closest(".repeatable-customize-control");
        $("body").trigger("repeat-control-remove-item", [$context]);
        control.remove_editor($context);
        $context.remove();
        control.rename();
        control.updateValue();
        control._check_max_item();
      });

      /**
       * Get customizer control data
       *
       * @returns {*}
       */
      control.getData = function () {
        var f = $(".form-data", control.container);

        var data = $("input, textarea, select", f).serialize();
        return JSON.stringify(data);
      };

      /**
       * Update repeater value
       */
      control.updateValue = function () {
        var data = control.getData();

        //$("[data-hidden-value]", control.container).val(data);
        //$("[data-hidden-value]", control.container).trigger('change');
        control.setting.set(data);
      };
      control.update_value = function () {
        var self = this,
          value = {},
          option;

        self.container.find("[data-option]").each(function () {
          option = $(this).data("option");

          if (
            "background-size" === option ||
            "background-attachment" === option
          ) {
            value[option] = $(this).is(":checked")
              ? $(this).val()
              : value[option];
          } else {
            value[option] = $(this).val();
          }
        });

        self.setting.set(value);
      };

      /**
       * Rename repeater item
       */
      control.rename = function () {
        $(".list-repeatable li", control.container).each(function (index) {
          var li = $(this);
          $("input, textarea, select", li).each(function () {
            var input = $(this);
            var name = input.attr("data-repeat-name") || undefined;
            if ("undefined" !== typeof name) {
              name = name.replace(/__i__/g, index);
              input.attr("name", name);
            }
          });
        });
      };
      control.autocorrect_range_input_number = function (
        input_number,
        timeout
      ) {
        var range_input = input_number,
          range = range_input.parent().find('input[type="range"]'),
          value = parseFloat(range_input.val()),
          reset = parseFloat(
            range
              .find(".techalgospotlight-reset-range")
              .attr("data-reset_value")
          ),
          step = parseFloat(range_input.attr("step")),
          min = parseFloat(range_input.attr("min")),
          max = parseFloat(range_input.attr("max"));

        clearTimeout(timeout);

        timeout = setTimeout(function () {
          if (isNaN(value)) {
            range_input.val(reset);
            range.val(reset).trigger("change");
            return;
          }

          if (1 <= step && 0 !== value % 1) {
            value = Math.round(value);
            range_input.val(value);
            range.val(value).trigger("change");
          }

          if (value > max) {
            range_input.val(max);
            range.val(max).trigger("change");
          }

          if (value < min) {
            range_input.val(min);
            range.val(min).trigger("change");
          }
        }, timeout);

        range.val(value).trigger("change");
        control.updateValue();
      };
      if (!window._upload_techalgospotlight) {
        // var insertImage = wp.media.controller.Library.extend({
        //     defaults :  _.defaults({
        //             id:        'insert-image',
        //             title:      'Insert Image Url',
        //             allowLocalEdits: true,
        //             displaySettings: true,
        //             displayUserSettings: true,
        //             multiple : false,
        //             type : 'image'//audio, video, application/pdf, ... etc
        //       }, wp.media.controller.Library.prototype.defaults )
        // });

        window._upload_techalgospotlight = wp.media({
          title: wp.media.view.l10n.addMedia,
          multiple: false,

          //frame      : 'post'
          // library: {type: 'all' },
          //button : { text : 'Insert' }
        });
      }
      window._upload_techalgospotlight.on("close", function () {
        // get selections and save to hidden input plus other AJAX stuff etc.
        var selection = window._upload_techalgospotlight
          .state()
          .get("selection");
      });
      window.media_current = {};
      window.media_btn = {};
      window._upload_techalgospotlight.on("select", function () {
        // Grab our attachment selection and construct a JSON representation of the model.
        var media_attachment = window._upload_techalgospotlight
          .state()
          .get("selection")
          .first()
          .toJSON();
        $(".image_id", window.media_current).val(media_attachment.id);
        var preview, img_url;
        img_url = media_attachment.url;
        $(".current", window.media_current)
          .removeClass("hide")
          .addClass("show");
        $(".image_url", window.media_current).val(img_url);
        if ("image" == media_attachment.type) {
          preview = '<img src="' + img_url + '" alt="">';
          $(".thumbnail-image", window.media_current).html(preview);
        }
        $(".remove-button", window.media_current).show();
        $(".image_id", window.media_current).trigger("change");
        try {
          window.media_btn.text(window.media_btn.attr("data-change-txt"));
        } catch (e) {}
      });
      control.handleMedia = function ($context) {
        $(".item-media", $context).each(function () {
          var _item = $(this);
          // when remove item
          $(".remove-button", _item).on("click", function (e) {
            e.preventDefault();
            $(".image_id, .image_url", _item).val("");
            $(".thumbnail-image", _item).html("");
            $(".current", _item).removeClass("show").addClass("hide");
            $(this).hide();
            $(".upload-button", _item).text(
              $(".upload-button", _item).attr("data-add-txt")
            );
            $(".image_id", _item).trigger("change");
          });

          // when upload item
          $(".upload-button, .attachment-media-view", _item).on(
            "click",
            function (e) {
              e.preventDefault();
              window.media_current = _item;
              window.media_btn = $(this);
              window._upload_techalgospotlight.open();
            }
          );
        });
      };

      control.handleGradient = function ($context) {
        var control = this,
          setting = control.setting.get(),
          range,
          range_input,
          value,
          techalgospotlight_range_input_number_timeout,
          popup_content = control.container.find(".popup-content");

        // Range controls.

        $(".item-gradient", $context).each(function () {
          var _item = $(this);

          _item.find(".techalgospotlight-range-wrapper").each(function () {
            var $this = $(this);

            $this.rangeControl({
              id: control.params.id + "-" + $this.data("option-id"),
              option: $this.data("option-id"),
              value: setting[$this.data("options-id")],
              responsive: control.params.responsive,
              change: function () {
                control.update_value();
              },
            });

            // Change the text value
            $this
              .find("input.techalgospotlight-range-input")
              .on("change keyup", function () {
                control.autocorrect_range_input_number(
                  $(this),
                  1000,
                  techalgospotlight_range_input_number_timeout
                );
              })
              .on("focusout", function () {
                control.autocorrect_range_input_number(
                  $(this),
                  0,
                  techalgospotlight_range_input_number_timeout
                );
              });
          });

          // Visibility deps.
          _item.on(
            "change",
            '[data-option="background-type"], [data-option="gradient-type"]',
            function () {
              var field = $(this).attr("data-option");
              _item.find('[data-dep-field="' + field + '"]').hide();
              _item
                .find(
                  '[data-dep-field="' +
                    field +
                    '"][data-dep-value="' +
                    $(this).val() +
                    '"]'
                )
                .show();
            }
          );

          _item
            .find(
              '[data-option="background-type"], [data-option="gradient-type"]'
            )
            .trigger("change");

          _item.on("change", ".techalgospotlight-select-wrapper", function () {
            control.update_value();
          });
        });
      };

      control.handleBackground = function ($context) {
        var control = this,
          setting = control.setting.get(),
          range,
          range_input,
          value,
          techalgospotlight_range_input_number_timeout,
          popup_content = control.container.find(".popup-content");

        // Range controls.

        $(".item-design-options", $context).each(function () {
          var _item = $(this);

          _item.find(".techalgospotlight-range-wrapper").each(function () {
            var $this = $(this);

            $this.rangeControl({
              id: control.params.id + "-" + $this.data("option-id"),
              option: $this.data("option-id"),
              value: setting[$this.data("options-id")],
              responsive: control.params.responsive,
              change: function () {
                control.update_value();
              },
            });

            // Change the text value
            $this
              .find("input.techalgospotlight-range-input")
              .on("change keyup", function () {
                control.autocorrect_range_input_number(
                  $(this),
                  1000,
                  techalgospotlight_range_input_number_timeout
                );
              })
              .on("focusout", function () {
                control.autocorrect_range_input_number(
                  $(this),
                  0,
                  techalgospotlight_range_input_number_timeout
                );
              });
          });

          // Visibility deps.
          _item.on(
            "change",
            '[data-option="background-type"], [data-option="gradient-type"]',
            function () {
              var field = $(this).attr("data-option");
              _item.find('[data-dep-field="' + field + '"]').hide();
              _item
                .find(
                  '[data-dep-field="' +
                    field +
                    '"][data-dep-value="' +
                    $(this).val() +
                    '"]'
                )
                .show();
            }
          );

          _item
            .find(
              '[data-option="background-type"], [data-option="gradient-type"]'
            )
            .trigger("change");

          _item.on("change", ".techalgospotlight-select-wrapper", function () {
            control.update_value();
          });

          // Advanced panel.
          _item.find(".popup-link").on("click", function () {
            popup_content.toggleClass("hidden");
            $(this).toggleClass("active");
            $(this).siblings(".reset-defaults").toggleClass("active");

            // Close the panel on outside click.
            $("body").on("click", outside_click_close);
          });

          var outside_click_close = function (e) {
            if (
              !$(e.target).closest(".customize-save-button-wrapper").length &&
              !_item.has($(e.target).closest(".popup-link")).length &&
              !_item.has($(e.target).closest(".popup-content")).length &&
              !popup_content.hasClass("hidden") &&
              !$(e.target).closest(".reset-defaults").length
            ) {
              popup_content.addClass("hidden");
              _item.find(".popup-link").removeClass("active");
              _item.find(".reset-defaults").removeClass("active");
              $("body").off("click", outside_click_close);
            }
          };

          // Hide unnecessary controls.
          _item.find(".background-image-advanced").hide();

          // Background-Repeat.
          _item.on("change", ".background-repeat select", function () {
            control.update_value();
          });

          // Background-Size.
          _item.on("change click", ".background-size input", function () {
            control.update_value();
          });

          // Background-Attachment.
          _item.on("change click", ".background-attachment input", function () {
            control.update_value();
          });

          // Background-Image.
          _item.on("click", ".background-image-upload-button", function (e) {
            $("body").off("click", outside_click_close);

            var image = wp
              .media({
                multiple: false,
                title: control.params.l10n.image.select_image,
                button: {
                  text: control.params.l10n.image.use_image,
                },
              })
              .open()
              .on("select", function () {
                // This will return the selected image from the Media Uploader, the result is an object.
                var uploadedImage = image.state().get("selection").first(),
                  uploadedImageJSON = uploadedImage.toJSON(),
                  previewImage,
                  imageUrl,
                  imageID,
                  imageWidth,
                  imageHeight,
                  preview,
                  removeButton;

                if (!_.isUndefined(uploadedImageJSON.sizes)) {
                  if (!_.isUndefined(uploadedImageJSON.sizes.medium)) {
                    previewImage = uploadedImageJSON.sizes.medium.url;
                  } else if (
                    !_.isUndefined(uploadedImageJSON.sizes.thumbnail)
                  ) {
                    previewImage = uploadedImageJSON.sizes.thumbnail.url;
                  } else if (!_.isUndefined(uploadedImageJSON.sizes.full)) {
                    previewImage = uploadedImageJSON.sizes.full.url;
                  } else {
                    previewImage = uploadedImageJSON.url;
                  }
                } else {
                  previewImage = uploadedImageJSON.url;
                }

                imageUrl = uploadedImageJSON.url;
                imageID = uploadedImageJSON.id;
                imageWidth = uploadedImageJSON.width;
                imageHeight = uploadedImageJSON.height;

                // Show extra controls if the value has an image.
                if ("" !== imageUrl) {
                  _item.find(".background-image-advanced").show();
                  _item
                    .find(".advanced-settings")
                    .removeClass("hidden")
                    .addClass("up");
                }

                _item.find('[data-option="background-image"]').val(imageUrl);
                _item.find('[data-option="background-image-id"]').val(imageID);

                control.update_value();

                preview = _item.find(".placeholder, .thumbnail");
                removeButton = _item.find(
                  ".background-image-upload-remove-button"
                );

                if (preview.length) {
                  preview
                    .removeClass()
                    .addClass("thumbnail thumbnail-image")
                    .html('<img src="' + previewImage + '" alt="" />');
                }
                if (removeButton.length) {
                  removeButton.show();
                }

                setTimeout(function () {
                  $("body").on("click", outside_click_close);
                }, 100);
              });

            e.preventDefault();
          });

          _item.on(
            "click",
            ".background-image-upload-remove-button",
            function (e) {
              var preview, removeButton;

              e.preventDefault();

              _item.find('[data-option="background-image"]').val("");
              _item.find('[data-option="background-image-id"]').val("");

              control.update_value();

              preview = _item.find(".placeholder, .thumbnail");
              removeButton = _item.find(
                ".background-image-upload-remove-button"
              );

              // Hide unnecessary controls.
              _item.find(".background-image-advanced").hide();
              _item
                .find(".advanced-settings")
                .addClass("hidden")
                .removeClass("up");

              if (preview.length) {
                preview
                  .removeClass()
                  .addClass("placeholder")
                  .html(control.params.l10n.image.placeholder);
              }

              if (removeButton.length) {
                removeButton.hide();
              }
            }
          );

          _item.on("click", ".advanced-settings", function (e) {
            $(this).toggleClass("up");
            _item.find(".background-image-advanced").toggle();
          });

          // Spacing field.

          // Linked button
          _item.on("click", ".techalgospotlight-spacing-linked", function () {
            // Set up variables
            var $this = $(this);

            // Remove linked class
            $this.closest("ul").find(".spacing-input").removeClass("linked");

            // Remove class
            $this.parent(".spacing-link-values").removeClass("unlinked");
          });

          // Unlinked button
          _item.on("click", ".techalgospotlight-spacing-unlinked", function () {
            // Set up variables
            var $this = $(this);

            // Remove linked class
            $this.closest("ul").find(".spacing-input").addClass("linked");

            // Remove class
            $this.parent(".spacing-link-values").addClass("unlinked");
          });

          // Values linked inputs
          _item.on("input", ".linked input", function () {
            var $val = $(this).val();
            $(this)
              .closest(".spacing-input")
              .siblings(".linked")
              .find("input")
              .val($val)
              .change();
          });

          // Store new inputs
          _item.on("change input", ".spacing-input input", function () {
            control.update_value();
          });

          // Reset default.
          _item.find(".reset-defaults").on("click", function () {
            var item, option_id;

            _item.find("[data-option]").each(function () {
              item = $(this);
              option_id = item.data("option");

              if (
                "background-size" === option_id ||
                "background-attachment" === option_id
              ) {
                item.prop("checked", false);

                if (
                  option_id in control.params.default &&
                  control.params.default[option_id] === item.val()
                ) {
                  item.prop("checked", true);
                }
              } else {
                item
                  .val(control.params.default[item.data("option")])
                  .trigger("change");
              }
            });

            _item.find(".background-image-upload-remove-button").click();

            control.update_value();
          });
        });
      };

      control.linkPicker = function ($context) {
        if ((item_link = $context.find(".techalgospotlight-field-link"))) {
          const a = new techalgospotlightLinkField(item_link);
          a.init();
        }
      };

      /**
       * Init color picker
       *
       * @param $context
       */
      control.colorPicker = function ($context) {
        // Add Color Picker to all inputs that have 'color-field' class
        $(".c-color", $context).wpColorPicker({
          change: function (event, ui) {
            control.updateValue();
          },
          clear: function (event, ui) {
            control.updateValue();
          },
        });
        $(".c-coloralpha", $context).each(function () {
          var input = $(this);
          var c = input.val();

          //c = c.replace('#', '');
          input.removeAttr("value");
          input.prop("value", c);
          input.alphaColorPicker({
            change: function (event, ui) {
              control.updateValue();
            },
            clear: function (event, ui) {
              control.updateValue();
            },
          });
        });
      };

      /**
       * Live title events
       *
       * @param $context
       */
      control.actions = function ($context) {
        if (control.params.live_title_id) {
          if (!$context.attr("data-title-format")) {
            $context.attr("data-title-format", control.params.title_format);
          }
          var format = $context.attr("data-title-format") || "";

          // Custom for special ID
          if ("techalgospotlight_section_order_styling" === control.id) {
            if ("click" !== $context.find("input.add_by").val()) {
              format = "[live_title]";
            }
          }

          // Live title
          if (
            control.params.live_title_id &&
            0 <
              $(
                "[data-live-id='" + control.params.live_title_id + "']",
                $context
              ).length
          ) {
            var v = "";
            if (
              $(
                "[data-live-id='" + control.params.live_title_id + "']",
                $context
              ).is(".select-one")
            ) {
              v = $(
                "[data-live-id='" + control.params.live_title_id + "']",
                $context
              )
                .find("option:selected")
                .eq(0)
                .text();
            } else {
              v = $(
                "[data-live-id='" + control.params.live_title_id + "']",
                $context
              )
                .eq(0)
                .val();
            }
            if ("" == v) {
              v = control.params.default_empty_title;
            }
            if ("" !== format) {
              v = format.replace("[live_title]", v);
            }
            $(".widget-title .live-title", $context).text(v);
            $context.on(
              "keyup change",
              "[data-live-id='" + control.params.live_title_id + "']",
              function () {
                var v = "";
                var format = $context.attr("data-title-format") || "";

                // custom for special ID
                if ("techalgospotlight_section_order_styling" === control.id) {
                  if ("click" !== $context.find("input.add_by").val()) {
                    format = "[live_title]";
                  }
                }
                if ($(this).is(".select-one")) {
                  v = $(this).find("option:selected").eq(0).text();
                } else {
                  v = $(this).val();
                }
                if ("" == v) {
                  v = control.params.default_empty_title;
                }
                if ("" !== format) {
                  v = format.replace("[live_title]", v);
                }
                $(".widget-title .live-title", $context).text(v);
              }
            );
          } else {
          }
        } else {
          //$('.widget-title .live-title', $context).text( control.params.title_format );
        }
      };

      /**
       * Check limit number item
       *
       * @private
       */
      control._check_max_item = function () {
        var n = $(
          ".list-repeatable > li.repeatable-customize-control",
          control.container
        ).length;

        if (n >= max_item) {
          $(".repeatable-actions", control.container).hide();
          if (0 >= $(".limited-msg", control.container).length) {
            if ("" !== limited_mg) {
              var msg = $('<p class="limited-msg"/>');
              msg.html(limited_mg);
              msg.insertAfter($(".repeatable-actions", control.container));
              msg.show();
            }
          } else {
            $(".limited-msg", control.container).show();
          }
        } else {
          $(".repeatable-actions", control.container).show();
          $(".limited-msg", control.container).hide();
        }
      };

      /**
       * Function that loads the Mustache template
       */
      control.repeaterTemplate = _.memoize(function () {
        var compiled,
          /*
           * Underscore's default ERB-style templates are incompatible with PHP
           * when asp_tags is enabled, so WordPress uses Mustache-inspired templating syntax.
           *
           * @see trac ticket #22344.
           */
          options = {
            evaluate: /<#([\s\S]+?)#>/g,
            interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
            escape: /\{\{([^\}]+?)\}\}(?!\})/g,
            variable: "data",
          };
        return function (data) {
          if ("undefined" === typeof window.repeater_item_tpl) {
            window.repeater_item_tpl = $("#repeatable-js-item-tpl").html();
          }
          compiled = _.template(window.repeater_item_tpl, null, options);
          return compiled(data);
        };
      });
      control.template = control.repeaterTemplate();

      /**
       * Init item events
       *
       * @param $context
       */
      control.intItem = function ($context) {
        control.rename();
        control.conditionize($context);
        control.colorPicker($context);
        control.linkPicker($context);
        control.handleMedia($context);
        control.handleGradient($context);
        control.handleBackground($context);

        //Special check element
        $('[data-live-id="section_id"]', $context).each(function () {
          $(this)
            .closest(".repeatable-customize-control")
            .addClass("section-" + $(this).val());
          if ("map" === $(this).val()) {
            $context.addClass("show-display-field-only");
          }
        });

        // Custom for special IDs
        if ("techalgospotlight_section_order_styling" === control.id) {
          if ("click" !== $context.find("input.add_by").val()) {
            $context.addClass("no-changeable");

            // Remove because we never use
            $(".item-editor textarea", $context).remove();
          } else {
            $context.find(".item-title").removeClass("item-hidden ");
            $context
              .find('.item-title input[type="hidden"]')
              .attr("type", "text");
            $context.find(".item-section_id").removeClass("item-hidden ");
            $context
              .find('.item-section_id input[type="hidden"]')
              .attr("type", "text");
          }
        }

        // Setup editor
        $(".item-editor textarea", $context).each(function () {
          control.editor($(this));
        });

        // Setup editor
        $("body").trigger("repeater-control-init-item", [$context]);
      };

      /**
       * Drag to sort items
       */
      $(".list-repeatable", control.container).sortable({
        handle: ".widget-title",

        //containment: ".customize-control-repeatable",
        containment: control.container,

        /// placeholder: "sortable-placeholder",
        update: function (event, ui) {
          control.rename();
          control.updateValue();
        },
      });

      /**
       * Create existing items
       */
      _templateData = $.extend(true, {}, control.params.fields);
      var _templateData;
      $.each(values, function (i, _values) {
        _values = values[i];
        if (_values) {
          for (var j in _values) {
            if (_templateData.hasOwnProperty(j) && _values.hasOwnProperty(j)) {
              _templateData[j].value = _values[j];
            }
          }
        }
        var $html = $(control.template(_templateData));
        $(".list-repeatable", control.container).append($html);
        control.intItem($html);
        control.actions($html);
      });

      /**
       * Add new item
       */
      control.container.on("click", ".add-new-repeat-item", function () {
        var $html = $(control.template(default_data));
        $(".list-repeatable", control.container).append($html);

        // add unique ID for section if id_key is set
        if ("" !== control.params.id_key) {
          $html
            .find(".item-" + control.params.id_key)
            .find("input")
            .val("sid" + new Date().getTime());
        }
        $html.find("input.add_by").val("click");
        control.intItem($html);
        control.actions($html);
        control.updateValue();
        control._check_max_item();
      });

      /**
       * Update repeater data when any events fire.
       */
      $(".list-repeatable", control.container).on(
        "keyup change color_change",
        "input, select, textarea",
        function (e) {
          control.updateValue();
        }
      );
      control._check_max_item();
    },
  });
})(wp.customize, jQuery);

/**
 * Icon picker
 */
jQuery(document).ready(function ($) {
  window.editing_icon = false;
  var icon_picker = $(
    '<div class="c-icon-picker"><div class="c-icon-type-wrap"><select class="c-icon-type"></select></div><div class="c-icon-search"><input class="" type="text"></div><div class="c-icon-list"></div></div>'
  );
  var options_font_type = "",
    icon_group = "";
  $.each(techalgospotlight_Icon_Picker.fonts, function (key, font) {
    font = $.extend(
      {},
      {
        url: "",
        name: "",
        prefix: "",
        icons: "",
      },
      font
    );
    $("<link>")
      .appendTo("head")
      .attr({
        type: "text/css",
        rel: "stylesheet",
      })
      .attr("id", "customizer-icon-" + key)
      .attr("href", font.url);
    options_font_type +=
      '<option value="' + key + '">' + font.name + "</option>";
    var icons_array = font.icons.split("|");
    icon_group +=
      '<div class="ic-icons-group" style="display: none;" data-group-name="' +
      key +
      '">';
    $.each(icons_array, function (index, icon) {
      if (font.prefix) {
        icon = font.prefix + " " + icon;
      }
      icon_group +=
        '<span title="' +
        icon +
        '" data-name="' +
        icon +
        '"><i class="' +
        icon +
        '"></i></span>';
    });
    icon_group += "</div>";
  });
  icon_picker
    .find(".c-icon-search input")
    .attr("placeholder", techalgospotlight_Icon_Picker.search);
  icon_picker.find(".c-icon-type").html(options_font_type);
  icon_picker.find(".c-icon-list").append(icon_group);
  $(".wp-full-overlay").append(icon_picker);

  // Change icon type
  $("body").on("change", "select.c-icon-type", function () {
    var t = $(this).val();
    icon_picker.find(".ic-icons-group").hide();
    icon_picker.find('.ic-icons-group[data-group-name="' + t + '"]').show();
  });
  icon_picker.find("select.c-icon-type").trigger("change");

  // When type to search
  $("body").on("keyup", ".c-icon-search input", function () {
    var v = $(this).val();
    if ("" == v) {
      $(".c-icon-list span").show();
    } else {
      $(".c-icon-list span").hide();
      try {
        $('.c-icon-list span[data-name*="' + v + '"]').show();
      } catch (e) {}
    }
  });

  // Edit icon
  $("body").on("click", ".icon-wrapper", function (e) {
    e.preventDefault();
    var icon = $(this);
    window.editing_icon = icon;
    icon_picker.addClass("ic-active");
    $("body").find(".icon-wrapper").removeClass("icon-editing");
    icon.addClass("icon-editing");
  });

  // Remove icon
  $("body").on("click", ".item-icon .remove-icon", function (e) {
    e.preventDefault();
    var item = $(this).closest(".item-icon");
    item.find(".icon-wrapper input").val("");
    item.find(".icon-wrapper input").trigger("change");
    item.find(".icon-wrapper i").attr("class", "");
    $("body").find(".icon-wrapper").removeClass("icon-editing");
  });

  // Selected icon
  $("body").on("click", ".c-icon-list span", function (e) {
    e.preventDefault();
    var icon_name = $(this).attr("data-name") || "";
    if (window.editing_icon) {
      window.editing_icon
        .find("i")
        .attr("class", "")
        .addClass($(this).find("i").attr("class"));
      window.editing_icon.find("input").val(icon_name).trigger("change");
    }
    icon_picker.removeClass("ic-active");
    window.editing_icon = false;
    $("body").find(".icon-wrapper").removeClass("icon-editing");
  });
  $(document).mouseup(function (e) {
    if (window.editing_icon) {
      if (
        !window.editing_icon.is(e.target) && // if the target of the click isn't the container...
        0 === window.editing_icon.has(e.target).length && // ... nor a descendant of the container
        !icon_picker.is(e.target) &&
        0 === icon_picker.has(e.target).length
      ) {
        icon_picker.removeClass("ic-active");

        // window.editing_icon = false;
      }
    }
  });
});
