/**
 * Update Customizer settings live.
 *
 * @since 1.0.0
 */
(function ($) {
  "use strict";

  // Declare variables
  var api = wp.customize,
    $body = $("body"),
    $head = $("head"),
    $style_tag,
    $link_tag,
    techalgospotlight_visibility_classes =
      "techalgospotlight-hide-mobile techalgospotlight-hide-tablet techalgospotlight-hide-mobile-tablet",
    techalgospotlight_style_tag_collection = [],
    techalgospotlight_link_tag_collection = [];

  /**
   * Helper function to get style tag with id.
   */
  function techalgospotlight_get_style_tag(id) {
    if (techalgospotlight_style_tag_collection[id]) {
      return techalgospotlight_style_tag_collection[id];
    }

    $style_tag = $("head").find("#techalgospotlight-dynamic-" + id);

    if (!$style_tag.length) {
      $("head").append(
        '<style id="techalgospotlight-dynamic-' +
          id +
          '" type="text/css" href="#"></style>'
      );
      $style_tag = $("head").find("#techalgospotlight-dynamic-" + id);
    }

    techalgospotlight_style_tag_collection[id] = $style_tag;

    return $style_tag;
  }

  /**
   * Helper function to get link tag with id.
   */
  function techalgospotlight_get_link_tag(id, url) {
    if (techalgospotlight_link_tag_collection[id]) {
      return techalgospotlight_link_tag_collection[id];
    }

    $link_tag = $("head").find("#techalgospotlight-dynamic-link-" + id);

    if (!$link_tag.length) {
      $("head").append(
        '<link id="techalgospotlight-dynamic-' +
          id +
          '" type="text/css" rel="stylesheet" href="' +
          url +
          '"/>'
      );
      $link_tag = $("head").find("#techalgospotlight-dynamic-link-" + id);
    } else {
      $link_tag.attr("href", url);
    }

    techalgospotlight_link_tag_collection[id] = $link_tag;

    return $link_tag;
  }

  /*
   * Helper function to print visibility classes.
   */
  function techalgospotlight_print_visibility_classes($element, newval) {
    if (!$element.length) {
      return;
    }

    $element.removeClass(techalgospotlight_visibility_classes);

    if ("all" !== newval) {
      $element.addClass("techalgospotlight-" + newval);
    }
  }

  /*
   * Helper function to convert hex to rgba.
   */
  function techalgospotlight_hex2rgba(hex, opacity) {
    if ("rgba" === hex.substring(0, 4)) {
      return hex;
    }

    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

    hex = hex.replace(shorthandRegex, function (m, r, g, b) {
      return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

    if (opacity) {
      if (1 < opacity) {
        opacity = 1;
      }

      opacity = "," + opacity;
    }

    if (result) {
      return (
        "rgba(" +
        parseInt(result[1], 16) +
        "," +
        parseInt(result[2], 16) +
        "," +
        parseInt(result[3], 16) +
        opacity +
        ")"
      );
    }

    return false;
  }

  /**
   * Helper function to lighten or darken the provided hex color.
   */
  function techalgospotlight_luminance(hex, percent) {
    // Convert RGB color to HEX.
    if (hex.includes("rgb")) {
      hex = techalgospotlight_rgba2hex(hex);
    }

    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;

    hex = hex.replace(shorthandRegex, function (m, r, g, b) {
      return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

    var isColor = /^#[0-9A-F]{6}$/i.test(hex);

    if (!isColor) {
      return hex;
    }

    var from, to;

    for (var i = 1; 3 >= i; i++) {
      result[i] = parseInt(result[i], 16);
      from = 0 > percent ? 0 : result[i];
      to = 0 > percent ? result[i] : 255;
      result[i] = result[i] + Math.ceil((to - from) * percent);
    }

    result =
      "#" +
      techalgospotlight_dec2hex(result[1]) +
      techalgospotlight_dec2hex(result[2]) +
      techalgospotlight_dec2hex(result[3]);

    return result;
  }

  /**
   * Convert dec to hex.
   */
  function techalgospotlight_dec2hex(c) {
    var hex = c.toString(16);
    return 1 == hex.length ? "0" + hex : hex;
  }

  /**
   * Convert rgb to hex.
   */
  function techalgospotlight_rgba2hex(c) {
    var a, x;

    a = c.split("(")[1].split(")")[0].trim();
    a = a.split(",");

    var result = "";

    for (var i = 0; 3 > i; i++) {
      x = parseInt(a[i]).toString(16);
      result += 1 === x.length ? "0" + x : x;
    }

    if (result) {
      return "#" + result;
    }

    return false;
  }

  /**
   * Check if is light color.
   */
  function techalgospotlight_is_light_color(color = "") {
    var r, g, b, brightness;

    if (color.match(/^rgb/)) {
      color = color.match(
        /^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/
      );
      r = color[1];
      g = color[2];
      b = color[3];
    } else {
      color = +(
        "0x" + color.slice(1).replace(5 > color.length && /./g, "$&$&")
      );
      r = color >> 16;
      g = (color >> 8) & 255;
      b = color & 255;
    }

    brightness = (r * 299 + g * 587 + b * 114) / 1000;

    return 137 < brightness;
  }

  /**
   * Detect if we should use a light or dark color on a background color.
   */
  function techalgospotlight_light_or_dark(
    color,
    dark = "#000000",
    light = "#FFFFFF"
  ) {
    return techalgospotlight_is_light_color(color) ? dark : light;
  }

  /**
   * Spacing field CSS.
   */
  function techalgospotlight_spacing_field_css(
    selector,
    property,
    setting,
    responsive
  ) {
    if (!Array.isArray(setting) && "object" !== typeof setting) {
      return;
    }

    // Set up unit.
    var unit = "px",
      css = "";

    if ("unit" in setting) {
      unit = setting.unit;
    }

    var before = "",
      after = "";

    Object.keys(setting).forEach(function (index, el) {
      if ("unit" === index) {
        return;
      }

      if (responsive) {
        if ("tablet" === index) {
          before = "@media only screen and (max-width: 768px) {";
          after = "}";
        } else if ("mobile" === index) {
          before = "@media only screen and (max-width: 480px) {";
          after = "}";
        } else {
          before = "";
          after = "";
        }

        css += before + selector + "{";

        Object.keys(setting[index]).forEach(function (position) {
          if ("border" === property) {
            position += "-width";
          }

          if (setting[index][position]) {
            css +=
              property +
              "-" +
              position +
              ": " +
              setting[index][position] +
              unit +
              ";";
          }
        });

        css += "}" + after;
      } else {
        if ("border" === property) {
          index += "-width";
        }

        css += property + "-" + index + ": " + setting[index] + unit + ";";
      }
    });

    if (!responsive) {
      css = selector + "{" + css + "}";
    }

    return css;
  }

  /**
   * Range field CSS.
   */
  function techalgospotlight_range_field_css(
    selector,
    property,
    setting,
    responsive,
    unit
  ) {
    var css = "",
      before = "",
      after = "";

    if (responsive && (Array.isArray(setting) || "object" === typeof setting)) {
      Object.keys(setting).forEach(function (index, el) {
        if (setting[index]) {
          if ("tablet" === index) {
            before = "@media only screen and (max-width: 768px) {";
            after = "}";
          } else if ("mobile" === index) {
            before = "@media only screen and (max-width: 480px) {";
            after = "}";
          } else if ("desktop" === index) {
            before = "";
            after = "";
          } else {
            return;
          }

          css +=
            before +
            selector +
            "{" +
            property +
            ": " +
            setting[index] +
            unit +
            "; }" +
            after;
        }
      });
    }

    if (!responsive) {
      if (setting.value) {
        setting = setting.value;
      } else {
        setting = 0;
      }

      css = selector + "{" + property + ": " + setting + unit + "; }";
    }

    return css;
  }

  /**
   * Typography field CSS.
   */
  function techalgospotlight_typography_field_css(selector, setting) {
    var css = "";

    css += selector + "{";

    if ("default" === setting["font-family"]) {
      css +=
        "font-family: " +
        techalgospotlight_customizer_preview.default_system_font +
        ";";
    } else if (
      setting["font-family"] in
      techalgospotlight_customizer_preview.fonts.standard_fonts.fonts
    ) {
      css +=
        "font-family: " +
        techalgospotlight_customizer_preview.fonts.standard_fonts.fonts[
          setting["font-family"]
        ].fallback +
        ";";
    } else if ("inherit" !== setting["font-family"]) {
      css += 'font-family: "' + setting["font-family"] + '";';
    }

    css += "font-weight:" + setting["font-weight"] + ";";
    css += "font-style:" + setting["font-style"] + ";";
    css += "text-transform:" + setting["text-transform"] + ";";

    if ("text-decoration" in setting) {
      css += "text-decoration:" + setting["text-decoration"] + ";";
    }

    if ("letter-spacing" in setting) {
      css +=
        "letter-spacing:" +
        setting["letter-spacing"] +
        setting["letter-spacing-unit"] +
        ";";
    }

    if ("line-height-desktop" in setting) {
      css += "line-height:" + setting["line-height-desktop"] + ";";
    }

    if ("font-size-desktop" in setting && "font-size-unit" in setting) {
      css +=
        "font-size:" +
        setting["font-size-desktop"] +
        setting["font-size-unit"] +
        ";";
    }

    css += "}";

    if ("font-size-tablet" in setting && setting["font-size-tablet"]) {
      css +=
        "@media only screen and (max-width: 768px) {" +
        selector +
        "{" +
        "font-size: " +
        setting["font-size-tablet"] +
        setting["font-size-unit"] +
        ";" +
        "}" +
        "}";
    }

    if ("line-height-tablet" in setting && setting["line-height-tablet"]) {
      css +=
        "@media only screen and (max-width: 768px) {" +
        selector +
        "{" +
        "line-height:" +
        setting["line-height-tablet"] +
        ";" +
        "}" +
        "}";
    }

    if ("font-size-mobile" in setting && setting["font-size-mobile"]) {
      css +=
        "@media only screen and (max-width: 480px) {" +
        selector +
        "{" +
        "font-size: " +
        setting["font-size-mobile"] +
        setting["font-size-unit"] +
        ";" +
        "}" +
        "}";
    }

    if ("line-height-mobile" in setting && setting["line-height-mobile"]) {
      css +=
        "@media only screen and (max-width: 480px) {" +
        selector +
        "{" +
        "line-height:" +
        setting["line-height-mobile"] +
        ";" +
        "}" +
        "}";
    }

    return css;
  }

  /**
   * Load google font.
   */
  function techalgospotlight_enqueue_google_font(font) {
    if (techalgospotlight_customizer_preview.fonts.google_fonts.fonts[font]) {
      var id = "google-font-" + font.trim().toLowerCase().replace(" ", "-");
      var url =
        techalgospotlight_customizer_preview.google_fonts_url +
        "/css?family=" +
        font +
        ":" +
        techalgospotlight_customizer_preview.google_font_weights;

      var tag = techalgospotlight_get_link_tag(id, url);
    }
  }

  /**
   * Design Options field CSS.
   */
  function techalgospotlight_design_options_css(selector, setting, type) {
    var css = "",
      before = "",
      after = "";

    if ("background" === type) {
      var bg_type = setting["background-type"];

      css += selector + "{";

      if ("color" === bg_type) {
        setting["background-color"] = setting["background-color"]
          ? setting["background-color"]
          : "inherit";
        css += "background: " + setting["background-color"] + ";";
      } else if ("gradient" === bg_type) {
        css += "background: " + setting["gradient-color-1"] + ";";

        if ("linear" === setting["gradient-type"]) {
          css +=
            "background: -webkit-linear-gradient(" +
            setting["gradient-linear-angle"] +
            "deg, " +
            setting["gradient-color-1"] +
            " " +
            setting["gradient-color-1-location"] +
            "%, " +
            setting["gradient-color-2"] +
            " " +
            setting["gradient-color-2-location"] +
            "%);" +
            "background: -o-linear-gradient(" +
            setting["gradient-linear-angle"] +
            "deg, " +
            setting["gradient-color-1"] +
            " " +
            setting["gradient-color-1-location"] +
            "%, " +
            setting["gradient-color-2"] +
            " " +
            setting["gradient-color-2-location"] +
            "%);" +
            "background: linear-gradient(" +
            setting["gradient-linear-angle"] +
            "deg, " +
            setting["gradient-color-1"] +
            " " +
            setting["gradient-color-1-location"] +
            "%, " +
            setting["gradient-color-2"] +
            " " +
            setting["gradient-color-2-location"] +
            "%);";
        } else if ("radial" === setting["gradient-type"]) {
          css +=
            "background: -webkit-radial-gradient(" +
            setting["gradient-position"] +
            ", circle, " +
            setting["gradient-color-1"] +
            " " +
            setting["gradient-color-1-location"] +
            "%, " +
            setting["gradient-color-2"] +
            " " +
            setting["gradient-color-2-location"] +
            "%);" +
            "background: -o-radial-gradient(" +
            setting["gradient-position"] +
            ", circle, " +
            setting["gradient-color-1"] +
            " " +
            setting["gradient-color-1-location"] +
            "%, " +
            setting["gradient-color-2"] +
            " " +
            setting["gradient-color-2-location"] +
            "%);" +
            "background: radial-gradient(circle at " +
            setting["gradient-position"] +
            ", " +
            setting["gradient-color-1"] +
            " " +
            setting["gradient-color-1-location"] +
            "%, " +
            setting["gradient-color-2"] +
            " " +
            setting["gradient-color-2-location"] +
            "%);";
        }
      } else if ("image" === bg_type) {
        css +=
          "" +
          "background-image: url(" +
          setting["background-image"] +
          ");" +
          "background-size: " +
          setting["background-size"] +
          ";" +
          "background-attachment: " +
          setting["background-attachment"] +
          ";" +
          "background-position: " +
          setting["background-position-x"] +
          "% " +
          setting["background-position-y"] +
          "%;" +
          "background-repeat: " +
          setting["background-repeat"] +
          ";";
      }

      css += "}";

      // Background image color overlay.
      if (
        "image" === bg_type &&
        setting["background-color-overlay"] &&
        setting["background-image"]
      ) {
        css +=
          selector +
          "::after { background-color: " +
          setting["background-color-overlay"] +
          "; }";
      } else {
        css += selector + "::after { background-color: initial; }";
      }
    } else if ("color" === type) {
      setting["text-color"] = setting["text-color"]
        ? setting["text-color"]
        : "inherit";
      setting["link-color"] = setting["link-color"]
        ? setting["link-color"]
        : "inherit";
      setting["link-hover-color"] = setting["link-hover-color"]
        ? setting["link-hover-color"]
        : "inherit";

      css += selector + " { color: " + setting["text-color"] + "; }";
      css += selector + " a { color: " + setting["link-color"] + "; }";
      css +=
        selector +
        " a:hover { color: " +
        setting["link-hover-color"] +
        " !important; }";
    } else if ("border" === type) {
      setting["border-color"] = setting["border-color"]
        ? setting["border-color"]
        : "inherit";
      setting["border-style"] = setting["border-style"]
        ? setting["border-style"]
        : "solid";
      setting["border-left-width"] = setting["border-left-width"]
        ? setting["border-left-width"]
        : 0;
      setting["border-top-width"] = setting["border-top-width"]
        ? setting["border-top-width"]
        : 0;
      setting["border-right-width"] = setting["border-right-width"]
        ? setting["border-right-width"]
        : 0;
      setting["border-bottom-width"] = setting["border-bottom-width"]
        ? setting["border-bottom-width"]
        : 0;

      css += selector + "{";
      css += "border-color: " + setting["border-color"] + ";";
      css += "border-style: " + setting["border-style"] + ";";
      css += "border-left-width: " + setting["border-left-width"] + "px;";
      css += "border-top-width: " + setting["border-top-width"] + "px;";
      css += "border-right-width: " + setting["border-right-width"] + "px;";
      css += "border-bottom-width: " + setting["border-bottom-width"] + "px;";
      css += "}";
    } else if ("separator_color" === type) {
      css +=
        selector +
        ":after{ background-color: " +
        setting["separator-color"] +
        "; }";
    }

    return css;
  }

  /**
   * Logo max height.
   */
  api("techalgospotlight_logo_max_height", function (value) {
    value.bind(function (newval) {
      var $logo = $(".techalgospotlight-logo");

      if (!$logo.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_logo_max_height"
      );
      var style_css = "";

      style_css += techalgospotlight_range_field_css(
        ".techalgospotlight-logo img",
        "max-height",
        newval,
        true,
        "px"
      );
      style_css += techalgospotlight_range_field_css(
        ".techalgospotlight-logo img.techalgospotlight-svg-logo",
        "height",
        newval,
        true,
        "px"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Logo text font size.
   */
  api("techalgospotlight_logo_text_font_size", function (value) {
    value.bind(function (newval) {
      var $logo = $(
        "#techalgospotlight-header .techalgospotlight-logo .site-title"
      );

      if (!$logo.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_logo_text_font_size"
      );
      var style_css = "";

      style_css += techalgospotlight_range_field_css(
        "#techalgospotlight-header .techalgospotlight-logo .site-title",
        "font-size",
        newval,
        true,
        newval.unit
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Logo margin.
   */
  api("techalgospotlight_logo_margin", function (value) {
    value.bind(function (newval) {
      var $logo = $(".techalgospotlight-logo");

      if (!$logo.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_logo_margin"
      );

      var style_css = techalgospotlight_spacing_field_css(
        ".techalgospotlight-logo .logo-inner",
        "margin",
        newval,
        true
      );
      $style_tag.html(style_css);
    });
  });

  /**
   * Tagline.
   */
  api("blogdescription", function (value) {
    value.bind(function (newval) {
      if ($(".techalgospotlight-logo").find(".site-description").length) {
        $(".techalgospotlight-logo").find(".site-description").html(newval);
      }
    });
  });

  /**
   * Site Title.
   */
  api("blogname", function (value) {
    value.bind(function (newval) {
      if ($(".techalgospotlight-logo").find(".site-title").length) {
        $(".techalgospotlight-logo").find(".site-title").find("a").html(newval);
      }
    });
  });

  /**
   * Site Layout.
   */
  api("techalgospotlight_site_layout", function (value) {
    value.bind(function (newval) {
      $body.removeClass(function (index, className) {
        return (
          className.match(
            /(^|\s)techalgospotlight-layout__(?!boxed-separated)\S+/g
          ) || []
        ).join(" ");
      });

      $body.addClass("techalgospotlight-layout__" + newval);
    });
  });

  /**
   * Sticky Sidebar.
   */
  api("techalgospotlight_sidebar_sticky", function (value) {
    value.bind(function (newval) {
      $body.removeClass(function (index, className) {
        return (
          className.match(/(^|\s)techalgospotlight-sticky-\S+/g) || []
        ).join(" ");
      });

      if (newval) {
        $body.addClass("techalgospotlight-sticky-" + newval);
      }
    });
  });

  /**
   * Sidebar width.
   */
  api("techalgospotlight_sidebar_width", function (value) {
    value.bind(function (newval) {
      var $sidebar = $("#secondary");

      if (!$sidebar.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_sidebar_width"
      );
      var style_css = "#secondary { width: " + newval.value + "%; }";
      style_css +=
        "body:not(.techalgospotlight-no-sidebar) #primary { " +
        "max-width: " +
        (100 - parseInt(newval.value)) +
        "%;" +
        "};";

      $style_tag.html(style_css);
    });
  });

  /**
   * Single Page title spacing.
   */
  api("techalgospotlight_single_title_spacing", function (value) {
    value.bind(function (newval) {
      var $page_header = $(".page-header");

      if (!$page_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_single_title_spacing"
      );

      var style_css = techalgospotlight_spacing_field_css(
        ".techalgospotlight-single-title-in-page-header #page .page-header .techalgospotlight-page-header-wrapper",
        "padding",
        newval,
        true
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Single post narrow container width.
   */
  api("techalgospotlight_single_narrow_container_width", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_single_narrow_container_width"
      );
      var style_css = "";

      style_css +=
        '.single-post.narrow-content .entry-content > :not([class*="align"]):not([class*="gallery"]):not(.wp-block-image):not(.quote-inner):not(.quote-post-bg), ' +
        '.single-post.narrow-content .mce-content-body:not([class*="page-template-full-width"]) > :not([class*="align"]):not([data-wpview-type*="gallery"]):not(blockquote):not(.mceTemp), ' +
        ".single-post.narrow-content .entry-footer, " +
        ".single-post.narrow-content .post-nav, " +
        ".single-post.narrow-content .entry-content > .alignwide, " +
        ".single-post.narrow-content p.has-background:not(.alignfull):not(.alignwide)" +
        ".single-post.narrow-content #techalgospotlight-comments-toggle, " +
        ".single-post.narrow-content #comments, " +
        ".single-post.narrow-content .entry-content .aligncenter, " +
        ".single-post.narrow-content .techalgospotlight-narrow-element, " +
        ".single-post.narrow-content.techalgospotlight-single-title-in-content .entry-header, " +
        ".single-post.narrow-content.techalgospotlight-single-title-in-content .entry-meta, " +
        ".single-post.narrow-content.techalgospotlight-single-title-in-content .post-category, " +
        ".single-post.narrow-content.techalgospotlight-no-sidebar .techalgospotlight-page-header-wrapper, " +
        ".single-post.narrow-content.techalgospotlight-no-sidebar .techalgospotlight-breadcrumbs > .techalgospotlight-container > nav {" +
        "max-width: " +
        parseInt(newval.value) +
        "px; margin-left: auto; margin-right: auto; " +
        "}";

      style_css +=
        ".single-post.narrow-content .author-box, " +
        ".single-post.narrow-content .entry-content > .alignwide { " +
        "max-width: " +
        (parseInt(newval.value) + 70) +
        "px;" +
        "}";

      $style_tag.html(style_css);
    });
  });

  /**
   * Single post content font size.
   */
  api("techalgospotlight_single_content_font_size", function (value) {
    value.bind(function (newval) {
      var $content = $(".single-post");

      if (!$content.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_single_content_font_size"
      );
      var style_css = "";

      style_css += techalgospotlight_range_field_css(
        ".single-post .entry-content",
        "font-size",
        newval,
        true,
        newval.unit
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Header container width.
   */
  api("techalgospotlight_header_container_width", function (value) {
    value.bind(function (newval) {
      var $header = $("#techalgospotlight-header");

      if (!$header.length) {
        return;
      }

      if ("full-width" === newval) {
        $header.addClass("techalgospotlight-container__wide");
      } else {
        $header.removeClass("techalgospotlight-container__wide");
      }
    });
  });

  /**
   * Main navigation disply breakpoint.
   */
  api("techalgospotlight_main_nav_mobile_breakpoint", function (value) {
    value.bind(function (newval) {
      var $nav = $("#techalgospotlight-header-inner .techalgospotlight-nav");

      if (!$nav.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_main_nav_mobile_breakpoint"
      );
      var style_css = "";

      style_css +=
        "@media screen and (min-width: " +
        parseInt(newval) +
        "px) {#techalgospotlight-header-inner .techalgospotlight-nav {display:flex} .techalgospotlight-mobile-nav,.techalgospotlight-mobile-toggen,#techalgospotlight-header-inner .techalgospotlight-nav .menu-item-has-children>a > .techalgospotlight-icon,#techalgospotlight-header-inner .techalgospotlight-nav .page_item_has_children>a > .techalgospotlight-icon {display:none;} }";
      style_css +=
        "@media screen and (max-width: " +
        parseInt(newval) +
        "px) {#techalgospotlight-header-inner .techalgospotlight-nav {display:none} .techalgospotlight-mobile-nav,.techalgospotlight-mobile-toggen {display:inline-flex;} }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Mobile Menu Button Label.
   */
  api("techalgospotlight_main_nav_mobile_label", function (value) {
    value.bind(function (newval) {
      if (
        $(".techalgospotlight-hamburger-techalgospotlight-primary-nav").find(
          ".hamburger-label"
        ).length
      ) {
        $(".techalgospotlight-hamburger-techalgospotlight-primary-nav")
          .find(".hamburger-label")
          .html(newval);
      }
    });
  });

  /**
   * Main Nav Font color.
   */
  api("techalgospotlight_main_nav_font_color", function (value) {
    value.bind(function (newval) {
      var $navigation = $(
        "#techalgospotlight-header-inner .techalgospotlight-nav"
      );

      if (!$navigation.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_main_nav_font_color"
      );
      var style_css = "";

      // Link color.
      newval["link-color"] = newval["link-color"]
        ? newval["link-color"]
        : "inherit";
      style_css +=
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li > a { color: " +
        newval["link-color"] +
        "; }";

      // Link hover color.
      newval["link-hover-color"] = newval["link-hover-color"]
        ? newval["link-hover-color"]
        : api.value("techalgospotlight_accent_color")();
      style_css +=
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li > a:hover, " +
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.menu-item-has-children:hover > a, " +
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current-menu-item > a, " +
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current-menu-ancestor > a " +
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.page_item_has_children:hover > a, " +
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current_page_item > a, " +
        "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current_page_ancestor > a " +
        "{ color: " +
        newval["link-hover-color"] +
        "; }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Main Nav Background.
   */
  api("techalgospotlight_main_nav_background", function (value) {
    value.bind(function (newval) {
      var $navigation = $(
        ".techalgospotlight-header-layout-6 .techalgospotlight-nav-container, .techalgospotlight-header-layout-4 .techalgospotlight-nav-container, .techalgospotlight-header-layout-3 .techalgospotlight-nav-container"
      );

      if (!$navigation.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_main_nav_background"
      );
      var style_css = techalgospotlight_design_options_css(
        ".techalgospotlight-header-layout-3 .techalgospotlight-nav-container",
        newval,
        "background"
      );
      style_css += techalgospotlight_design_options_css(
        ".techalgospotlight-header-layout-4 .techalgospotlight-nav-container",
        newval,
        "background"
      );
      style_css += techalgospotlight_design_options_css(
        ".techalgospotlight-header-layout-6 .techalgospotlight-nav-container",
        newval,
        "background"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Main Nav Border.
   */
  api("techalgospotlight_main_nav_border", function (value) {
    value.bind(function (newval) {
      var $navigation = $(
        ".techalgospotlight-header-layout-6 .techalgospotlight-nav-container, .techalgospotlight-header-layout-4 .techalgospotlight-nav-container, .techalgospotlight-header-layout-3 .techalgospotlight-nav-container"
      );

      if (!$navigation.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_main_nav_border"
      );
      var style_css = techalgospotlight_design_options_css(
        ".techalgospotlight-header-layout-3 .techalgospotlight-nav-container",
        newval,
        "border"
      );
      style_css += techalgospotlight_design_options_css(
        ".techalgospotlight-header-layout-4 .techalgospotlight-nav-container",
        newval,
        "border"
      );
      style_css += techalgospotlight_design_options_css(
        ".techalgospotlight-header-layout-6 .techalgospotlight-nav-container",
        newval,
        "border"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Main Nav font size.
   */
  api("techalgospotlight_main_nav_font", function (value) {
    value.bind(function (newval) {
      var $nav = $("#techalgospotlight-header-inner");

      if (!$nav.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_main_nav_font"
      );
      var style_css = techalgospotlight_typography_field_css(
        ".techalgospotlight-nav.techalgospotlight-header-element, .techalgospotlight-header-layout-1 .techalgospotlight-header-widgets, .techalgospotlight-header-layout-2 .techalgospotlight-header-widgets",
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Top Bar container width.
   */
  api("techalgospotlight_top_bar_container_width", function (value) {
    value.bind(function (newval) {
      var $topbar = $("#techalgospotlight-topbar");

      if (!$topbar.length) {
        return;
      }

      if ("full-width" === newval) {
        $topbar.addClass("techalgospotlight-container__wide");
      } else {
        $topbar.removeClass("techalgospotlight-container__wide");
      }
    });
  });

  /**
   * Top Bar visibility.
   */
  api("techalgospotlight_top_bar_visibility", function (value) {
    value.bind(function (newval) {
      var $topbar = $("#techalgospotlight-topbar");

      techalgospotlight_print_visibility_classes($topbar, newval);
    });
  });

  /**
   * Top Bar widgets separator.
   */
  api("techalgospotlight_top_bar_widgets_separator", function (value) {
    value.bind(function (newval) {
      $body.removeClass(function (index, className) {
        return (
          className.match(/(^|\s)techalgospotlight-topbar__separators-\S+/g) ||
          []
        ).join(" ");
      });

      $body.addClass("techalgospotlight-topbar__separators-" + newval);
    });
  });

  /**
   * Top Bar background.
   */
  api("techalgospotlight_top_bar_background", function (value) {
    value.bind(function (newval) {
      var $topbar = $("#techalgospotlight-topbar");

      if (!$topbar.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_top_bar_background"
      );
      var style_css = techalgospotlight_design_options_css(
        "#techalgospotlight-topbar",
        newval,
        "background"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Top Bar color.
   */
  api("techalgospotlight_top_bar_text_color", function (value) {
    value.bind(function (newval) {
      var $topbar = $("#techalgospotlight-topbar");

      if (!$topbar.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_top_bar_text_color"
      );
      var style_css = "";

      newval["text-color"] = newval["text-color"]
        ? newval["text-color"]
        : "inherit";
      newval["link-color"] = newval["link-color"]
        ? newval["link-color"]
        : "inherit";
      newval["link-hover-color"] = newval["link-hover-color"]
        ? newval["link-hover-color"]
        : "inherit";

      // Text color.
      style_css +=
        "#techalgospotlight-topbar { color: " + newval["text-color"] + "; }";

      // Link color.
      style_css +=
        ".techalgospotlight-topbar-widget__text a, " +
        ".techalgospotlight-topbar-widget .techalgospotlight-nav > ul > li > a, " +
        ".techalgospotlight-topbar-widget__socials .techalgospotlight-social-nav > ul > li > a, " +
        "#techalgospotlight-topbar .techalgospotlight-topbar-widget__text .techalgospotlight-icon { color: " +
        newval["link-color"] +
        "; }";

      // Link hover color.
      style_css +=
        "#techalgospotlight-topbar .techalgospotlight-nav > ul > li > a:hover, " +
        ".using-keyboard #techalgospotlight-topbar .techalgospotlight-nav > ul > li > a:focus, " +
        "#techalgospotlight-topbar .techalgospotlight-nav > ul > li.menu-item-has-children:hover > a,  " +
        "#techalgospotlight-topbar .techalgospotlight-nav > ul > li.current-menu-item > a, " +
        "#techalgospotlight-topbar .techalgospotlight-nav > ul > li.current-menu-ancestor > a, " +
        "#techalgospotlight-topbar .techalgospotlight-topbar-widget__text a:hover, " +
        "#techalgospotlight-topbar .techalgospotlight-social-nav > ul > li > a .techalgospotlight-icon.bottom-icon { color: " +
        newval["link-hover-color"] +
        "; }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Top Bar border.
   */
  api("techalgospotlight_top_bar_border", function (value) {
    value.bind(function (newval) {
      var $topbar = $("#techalgospotlight-topbar");

      if (!$topbar.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_top_bar_border"
      );
      var style_css = techalgospotlight_design_options_css(
        "#techalgospotlight-topbar",
        newval,
        "border"
      );

      style_css += techalgospotlight_design_options_css(
        "#techalgospotlight-topbar .techalgospotlight-topbar-widget",
        newval,
        "separator_color"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Header menu item hover animation.
   */
  api("techalgospotlight_main_nav_hover_animation", function (value) {
    value.bind(function (newval) {
      $body.removeClass(function (index, className) {
        return (
          className.match(/(^|\s)techalgospotlight-menu-animation-\S+/g) || []
        ).join(" ");
      });

      $body.addClass("techalgospotlight-menu-animation-" + newval);
    });
  });

  /**
   * Header widgets separator.
   */
  api("techalgospotlight_header_widgets_separator", function (value) {
    value.bind(function (newval) {
      $body.removeClass(function (index, className) {
        return (
          className.match(/(^|\s)techalgospotlight-header__separators-\S+/g) ||
          []
        ).join(" ");
      });

      $body.addClass("techalgospotlight-header__separators-" + newval);
    });
  });

  /**
   * Header background.
   */
  api("techalgospotlight_header_background", function (value) {
    value.bind(function (newval) {
      var $header = $("#techalgospotlight-header-inner");

      if (!$header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_header_background"
      );
      var style_css = techalgospotlight_design_options_css(
        "#techalgospotlight-header-inner",
        newval,
        "background"
      );

      if ("color" === newval["background-type"] && newval["background-color"]) {
        style_css +=
          ".techalgospotlight-header-widget__cart .techalgospotlight-cart .techalgospotlight-cart-count { border: 2px solid " +
          newval["background-color"] +
          "; }";
      } else {
        style_css +=
          ".techalgospotlight-header-widget__cart .techalgospotlight-cart .techalgospotlight-cart-count { border: none; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Header font color.
   */
  api("techalgospotlight_header_text_color", function (value) {
    value.bind(function (newval) {
      var $header = $("#techalgospotlight-header");

      if (!$header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_header_text_color"
      );
      var style_css = "";

      // Text color.
      style_css +=
        ".techalgospotlight-logo .site-description { color: " +
        newval["text-color"] +
        "; }";

      // Link color.
      if (newval["link-color"]) {
        style_css +=
          "#techalgospotlight-header, " +
          ".techalgospotlight-header-widgets a:not(.techalgospotlight-btn), " +
          ".techalgospotlight-logo a," +
          ".techalgospotlight-hamburger { color: " +
          newval["link-color"] +
          "; }";
        style_css +=
          ".hamburger-inner," +
          ".hamburger-inner::before," +
          ".hamburger-inner::after { background-color: " +
          newval["link-color"] +
          "; }";
      }

      // Link hover color.
      if (newval["link-hover-color"]) {
        style_css +=
          ".techalgospotlight-header-widgets a:not(.techalgospotlight-btn):hover, " +
          "#techalgospotlight-header-inner .techalgospotlight-header-widgets .techalgospotlight-active," +
          ".techalgospotlight-logo .site-title a:hover, " +
          ".techalgospotlight-hamburger:hover .hamburger-label, " +
          ".is-mobile-menu-active .techalgospotlight-hamburger .hamburger-label," +
          "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li > a:hover," +
          "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.menu-item-has-children:hover > a," +
          "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current-menu-item > a," +
          "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current-menu-ancestor > a," +
          "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.page_item_has_children:hover > a," +
          "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current_page_item > a," +
          "#techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current_page_ancestor > a { color: " +
          newval["link-hover-color"] +
          "; }";

        style_css +=
          ".techalgospotlight-hamburger:hover .hamburger-inner," +
          ".techalgospotlight-hamburger:hover .hamburger-inner::before," +
          ".techalgospotlight-hamburger:hover .hamburger-inner::after," +
          ".is-mobile-menu-active .techalgospotlight-hamburger .hamburger-inner," +
          ".is-mobile-menu-active .techalgospotlight-hamburger .hamburger-inner::before," +
          ".is-mobile-menu-active .techalgospotlight-hamburger .hamburger-inner::after { background-color: " +
          newval["link-hover-color"] +
          "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Header border.
   */
  api("techalgospotlight_header_border", function (value) {
    value.bind(function (newval) {
      var $header = $("#techalgospotlight-header-inner");

      if (!$header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_header_border"
      );
      var style_css = techalgospotlight_design_options_css(
        "#techalgospotlight-header-inner",
        newval,
        "border"
      );

      // Separator color.
      newval["separator-color"] = newval["separator-color"]
        ? newval["separator-color"]
        : "inherit";
      style_css +=
        ".techalgospotlight-header-widget:after { background-color: " +
        newval["separator-color"] +
        "; }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Featured Links title.
   */
  api("techalgospotlight_featured_links_title", function (value) {
    value.bind(function (newval) {
      $("#featured_links .widget-title").text(newval);
    });
  });

  /**
   * PYML title.
   */
  api("techalgospotlight_pyml_title", function (value) {
    value.bind(function (newval) {
      $("#pyml .widget-title").text(newval);
    });
  });

  /**
   * Related posts title.
   */
  api("techalgospotlight_related_posts_heading", function (value) {
    value.bind(function (newval) {
      $("#related_posts .widget-title").text(newval);
    });
  });

  /**
   * Ticker News title.
   */
  api("techalgospotlight_ticker_title", function (value) {
    value.bind(function (newval) {
      $("#ticker .ticker-title .title").text(newval);
    });
  });

  /**
   * Custom input style.
   */
  api("techalgospotlight_custom_input_style", function (value) {
    value.bind(function (newval) {
      if (newval) {
        $body.addClass("techalgospotlight-input-supported");
      } else {
        $body.removeClass("techalgospotlight-input-supported");
      }
    });
  });

  /**
   * WooCommerce sale badge text.
   */
  api("techalgospotlight_product_sale_badge_text", function (value) {
    value.bind(function (newval) {
      var $badge = $(
        ".woocommerce ul.products li.product .onsale, .woocommerce span.onsale"
      ).not(".sold-out");

      if (!$badge.length) {
        return;
      }

      $badge.html(newval);
    });
  });

  /**
   * Accent color.
   */
  api("techalgospotlight_accent_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_accent_color"
      );
      var style_css;

      // Colors.
      style_css =
        ":root { " +
        "--techalgospotlight-primary: " +
        newval +
        ";" +
        "--techalgospotlight-primary_80: " +
        techalgospotlight_luminance(newval, 0.8) +
        ";" +
        "--techalgospotlight-primary_15: " +
        techalgospotlight_luminance(newval, 0.15) +
        ";" +
        "--techalgospotlight-primary_27: " +
        techalgospotlight_hex2rgba(newval, 0.27) +
        ";" +
        "--techalgospotlight-primary_10: " +
        techalgospotlight_hex2rgba(newval, 0.1) +
        ";" +
        "}";

      $style_tag.html(style_css);
    });
  });

  api("techalgospotlight_dark_mode", function (value) {
    value.bind(function (newval) {
      if (newval) {
        document.documentElement.setAttribute("data-darkmode", "dark");
        localStorage.setItem("darkmode", "dark");
      } else {
        document.documentElement.setAttribute("data-darkmode", "light");
        localStorage.setItem("darkmode", "light");
      }
    });
  });

  /**
   * Content background color.
   */
  api("techalgospotlight_boxed_content_background_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_boxed_content_background_color"
      );
      var style_css = "";

      if (newval) {
        style_css =
          ".techalgospotlight-layout__boxed .techalgospotlight-card-items .techalgospotlight-swiper-buttons, " +
          ".techalgospotlight-card__boxed .techalgospotlight-card-items, " +
          ".techalgospotlight-layout__boxed-separated.author .author-box, " +
          ".techalgospotlight-layout__boxed-separated #comments, " +
          ".techalgospotlight-layout__boxed-separated #content > article, " +
          ".techalgospotlight-layout__boxed-separated.techalgospotlight-sidebar-style-2 #secondary .techalgospotlight-widget, " +
          ".techalgospotlight-layout__boxed-separated.techalgospotlight-sidebar-style-2 .elementor-widget-sidebar .techalgospotlight-widget, " +
          ".techalgospotlight-layout__boxed-separated.page .techalgospotlight-article," +
          ".techalgospotlight-layout__boxed-separated.archive .techalgospotlight-article," +
          ".techalgospotlight-layout__boxed-separated.blog .techalgospotlight-article, " +
          ".techalgospotlight-layout__boxed-separated.search-results .techalgospotlight-article, " +
          ".techalgospotlight-layout__boxed-separated.category .techalgospotlight-article { background-color: " +
          newval +
          "; }";

        // style_css += '@media screen and (max-width: 960px) { ' + '.techalgospotlight-layout__boxed-separated #page { background-color: ' + newval + '; }' + '}';
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Content text color.
   */
  api("techalgospotlight_content_text_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_content_text_color"
      );
      var style_css = "";

      if (newval) {
        style_css =
          "body { " +
          "color: " +
          newval +
          ";" +
          "}" +
          ".comment-form .comment-notes, " +
          "#comments .no-comments, " +
          "#page .wp-caption .wp-caption-text," +
          "#comments .comment-meta," +
          ".comments-closed," +
          ".techalgospotlight-entry cite," +
          "legend," +
          ".techalgospotlight-page-header-description," +
          ".page-links em," +
          ".site-content .page-links em," +
          ".single .entry-footer .last-updated," +
          ".single .post-nav .post-nav-title," +
          "#main .widget_recent_comments span," +
          "#main .widget_recent_entries span," +
          "#main .widget_calendar table > caption," +
          ".post-thumb-caption, " +
          ".wp-block-image figcaption, " +
          ".techalgospotlight-cart-item .techalgospotlight-x," +
          ".woocommerce form.login .lost_password a," +
          ".woocommerce form.register .lost_password a," +
          ".woocommerce a.remove," +
          "#add_payment_method .cart-collaterals .cart_totals .woocommerce-shipping-destination, " +
          ".woocommerce-cart .cart-collaterals .cart_totals .woocommerce-shipping-destination, " +
          ".woocommerce-checkout .cart-collaterals .cart_totals .woocommerce-shipping-destination," +
          ".woocommerce ul.products li.product .techalgospotlight-loop-product__category-wrap a," +
          ".woocommerce ul.products li.product .techalgospotlight-loop-product__category-wrap," +
          ".woocommerce .woocommerce-checkout-review-order table.shop_table thead th," +
          "#add_payment_method #payment div.payment_box, " +
          ".woocommerce-cart #payment div.payment_box, " +
          ".woocommerce-checkout #payment div.payment_box," +
          "#add_payment_method #payment ul.payment_methods .about_paypal, " +
          ".woocommerce-cart #payment ul.payment_methods .about_paypal, " +
          ".woocommerce-checkout #payment ul.payment_methods .about_paypal," +
          ".woocommerce table dl," +
          ".woocommerce table .wc-item-meta," +
          ".widget.woocommerce .reviewer," +
          ".woocommerce.widget_shopping_cart .cart_list li a.remove:before," +
          ".woocommerce .widget_shopping_cart .cart_list li a.remove:before," +
          ".woocommerce .widget_shopping_cart .cart_list li .quantity, " +
          ".woocommerce.widget_shopping_cart .cart_list li .quantity," +
          ".woocommerce div.product .woocommerce-product-rating .woocommerce-review-link," +
          ".woocommerce div.product .woocommerce-tabs table.shop_attributes td," +
          ".woocommerce div.product .product_meta > span span:not(.techalgospotlight-woo-meta-title), " +
          ".woocommerce div.product .product_meta > span a," +
          ".woocommerce .star-rating::before," +
          ".woocommerce div.product #reviews #comments ol.commentlist li .comment-text p.meta," +
          ".ywar_review_count," +
          ".woocommerce .add_to_cart_inline del, " +
          ".woocommerce div.product p.price del, " +
          ".woocommerce div.product span.price del { color: " +
          techalgospotlight_hex2rgba(newval, 0.75) +
          "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Content link hover color.
   */
  api("techalgospotlight_content_link_hover_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_content_link_hover_color"
      );
      var style_css = "";

      if (newval) {
        // Content link hover.
        style_css +=
          '.content-area a:not(.techalgospotlight-btn, .wp-block-button__link, [class^="cat-"], [rel="tag"]):hover, ' +
          ".techalgospotlight-woo-before-shop select.custom-select-loaded:hover ~ #techalgospotlight-orderby, " +
          "#add_payment_method #payment ul.payment_methods .about_paypal:hover, " +
          ".woocommerce-cart #payment ul.payment_methods .about_paypal:hover, " +
          ".woocommerce-checkout #payment ul.payment_methods .about_paypal:hover, " +
          ".techalgospotlight-breadcrumbs a:hover, " +
          ".woocommerce div.product .woocommerce-product-rating .woocommerce-review-link:hover, " +
          ".woocommerce ul.products li.product .meta-wrap .woocommerce-loop-product__link:hover, " +
          ".woocommerce ul.products li.product .techalgospotlight-loop-product__category-wrap a:hover { " +
          "color: " +
          newval +
          ";" +
          "}";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Content text color.
   */
  api("techalgospotlight_headings_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_headings_color"
      );
      var style_css = "";

      if (newval) {
        style_css =
          "h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, a, .entry-meta, .techalgospotlight-logo .site-title, .wp-block-heading, .wp-block-search__label, .error-404 .page-header h1 { " +
          "color: " +
          newval +
          ";" +
          "} :root { " +
          "--techalgospotlight-secondary: " +
          newval +
          ";" +
          "}";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Scroll Top visibility.
   */
  api("techalgospotlight_scroll_top_visibility", function (value) {
    value.bind(function (newval) {
      techalgospotlight_print_visibility_classes(
        $("#techalgospotlight-scroll-top"),
        newval
      );
    });
  });

  /**
   * Page Preloader visibility.
   */
  api("techalgospotlight_preloader_visibility", function (value) {
    value.bind(function (newval) {
      techalgospotlight_print_visibility_classes(
        $("#techalgospotlight-preloader"),
        newval
      );
    });
  });

  /**
   * Footer visibility.
   */
  api("techalgospotlight_footer_visibility", function (value) {
    value.bind(function (newval) {
      techalgospotlight_print_visibility_classes(
        $("#techalgospotlight-footer"),
        newval
      );
    });
  });

  /**
   * Footer Widget Heading Style Enable.
   */
  api("techalgospotlight_footer_widget_heading_style", function (value) {
    value.bind(function (newval) {
      $body
        .removeClass(function (index, className) {
          return (
            className.match(/(^|\s)is-footer-heading-init-s\S+/g) || []
          ).join(" ");
        })
        .addClass(
          "is-footer-heading-init-s" +
            api.value("techalgospotlight_footer_widget_heading_style")()
        );
    });
  });

  /**
   * Footer background.
   */
  api("techalgospotlight_footer_background", function (value) {
    value.bind(function (newval) {
      var $footer = $("#colophon");

      if (!$footer.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_footer_background"
      );
      var style_css = techalgospotlight_design_options_css(
        "#colophon",
        newval,
        "background"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Footer font color.
   */
  api("techalgospotlight_footer_text_color", function (value) {
    var $footer = $("#techalgospotlight-footer"),
      copyright_separator_color,
      style_css;

    value.bind(function (newval) {
      if (!$footer.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_footer_text_color"
      );

      style_css = "";

      newval["text-color"] = newval["text-color"]
        ? newval["text-color"]
        : "inherit";
      newval["link-color"] = newval["link-color"]
        ? newval["link-color"]
        : "inherit";
      newval["link-hover-color"] = newval["link-hover-color"]
        ? newval["link-hover-color"]
        : "inherit";
      newval["widget-title-color"] = newval["widget-title-color"]
        ? newval["widget-title-color"]
        : "inherit";

      // Text color.
      style_css += "#colophon { color: " + newval["text-color"] + "; }";

      // Link color.
      style_css += "#colophon a { color: " + newval["link-color"] + "; }";

      // Link hover color.
      style_css +=
        "#colophon a:hover, #colophon li.current_page_item > a, #colophon .techalgospotlight-social-nav > ul > li > a .techalgospotlight-icon.bottom-icon " +
        "{ color: " +
        newval["link-hover-color"] +
        "; }";

      // Widget title color.
      style_css +=
        "#colophon .widget-title, #colophon .wp-block-heading, #colophon .wp-block-search__label { color: " +
        newval["widget-title-color"] +
        "; }";

      // Copyright separator color.
      copyright_separator_color = techalgospotlight_light_or_dark(
        newval["text-color"],
        "rgba(255,255,255,0.1)",
        "rgba(0,0,0,0.1)"
      );

      // copyright_separator_color = techalgospotlight_luminance( newval['text-color'], 0.8 );

      style_css +=
        "#techalgospotlight-copyright.contained-separator > .techalgospotlight-container:before { background-color: " +
        copyright_separator_color +
        "; }";
      style_css +=
        "#techalgospotlight-copyright.fw-separator { border-top-color: " +
        copyright_separator_color +
        "; }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Footer border.
   */
  api("techalgospotlight_footer_border", function (value) {
    value.bind(function (newval) {
      var $footer = $("#techalgospotlight-footer");

      if (!$footer.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_footer_border"
      );
      var style_css = "";

      if (newval["border-top-width"]) {
        style_css +=
          "#colophon { " +
          "border-top-width: " +
          newval["border-top-width"] +
          "px;" +
          "border-top-style: " +
          newval["border-style"] +
          ";" +
          "border-top-color: " +
          newval["border-color"] +
          ";" +
          "}";
      }

      if (newval["border-bottom-width"]) {
        style_css +=
          "#colophon { " +
          "border-bottom-width: " +
          newval["border-bottom-width"] +
          "px;" +
          "border-bottom-style: " +
          newval["border-style"] +
          ";" +
          "border-bottom-color: " +
          newval["border-color"] +
          ";" +
          "}";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Copyright layout.
   */
  api("techalgospotlight_copyright_layout", function (value) {
    value.bind(function (newval) {
      $body.removeClass(function (index, className) {
        return (
          className.match(/(^|\s)techalgospotlight-copyright-layout-\S+/g) || []
        ).join(" ");
      });

      $body.addClass("techalgospotlight-copyright-" + newval);
    });
  });

  /**
   * Copyright separator.
   */
  api("techalgospotlight_copyright_separator", function (value) {
    value.bind(function (newval) {
      var $copyright = $("#techalgospotlight-copyright");

      if (!$copyright.length) {
        return;
      }

      $copyright.removeClass("fw-separator contained-separator");

      if ("none" !== newval) {
        $copyright.addClass(newval);
      }
    });
  });

  /**
   * Copyright visibility.
   */
  api("techalgospotlight_copyright_visibility", function (value) {
    value.bind(function (newval) {
      techalgospotlight_print_visibility_classes(
        $("#techalgospotlight-copyright"),
        newval
      );
    });
  });

  /**
   * Copyright background.
   */
  api("techalgospotlight_copyright_background", function (value) {
    value.bind(function (newval) {
      var $copyright = $("#techalgospotlight-copyright");

      if (!$copyright.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_copyright_background"
      );
      var style_css = techalgospotlight_design_options_css(
        "#techalgospotlight-copyright",
        newval,
        "background"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Copyright text color.
   */
  api("techalgospotlight_copyright_text_color", function (value) {
    value.bind(function (newval) {
      var $copyright = $("#techalgospotlight-copyright");

      if (!$copyright.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_copyright_text_color"
      );
      var style_css = "";

      newval["text-color"] = newval["text-color"]
        ? newval["text-color"]
        : "inherit";
      newval["link-color"] = newval["link-color"]
        ? newval["link-color"]
        : "inherit";
      newval["link-hover-color"] = newval["link-hover-color"]
        ? newval["link-hover-color"]
        : "inherit";

      // Text color.
      style_css +=
        "#techalgospotlight-copyright { color: " + newval["text-color"] + "; }";

      // Link color.
      style_css +=
        "#techalgospotlight-copyright a { color: " +
        newval["link-color"] +
        "; }";

      // Link hover color.
      style_css +=
        "#techalgospotlight-copyright a:hover, #techalgospotlight-copyright .techalgospotlight-social-nav > ul > li > a .techalgospotlight-icon.bottom-icon, #techalgospotlight-copyright li.current_page_item > a, #techalgospotlight-copyright .techalgospotlight-nav > ul > li.current-menu-item > a, #techalgospotlight-copyright .techalgospotlight-nav > ul > li.current-menu-ancestor > a #techalgospotlight-copyright .techalgospotlight-nav > ul > li:hover > a, #techalgospotlight-copyright .techalgospotlight-social-nav > ul > li > a .techalgospotlight-icon.bottom-icon { color: " +
        newval["link-hover-color"] +
        "; }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Container width.
   */
  api("techalgospotlight_container_width", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_container_width"
      );
      var style_css;

      style_css =
        ".techalgospotlight-container, .alignfull > div { max-width: " +
        newval.value +
        "px; } " +
        ".techalgospotlight-header-layout-5:not(.techalgospotlight-sticky-header) #techalgospotlight-header #techalgospotlight-header-inner, .techalgospotlight-header-layout-5 #masthead+#main .techalgospotlight-breadcrumbs { max-width: calc(" +
        newval.value +
        "px - 8rem); }";

      style_css +=
        ".techalgospotlight-layout__boxed #page, .techalgospotlight-layout__boxed.techalgospotlight-sticky-header.techalgospotlight-is-mobile #techalgospotlight-header-inner, " +
        ".techalgospotlight-layout__boxed.techalgospotlight-sticky-header:not(.techalgospotlight-header-layout-3,.techalgospotlight-header-layout-4,.techalgospotlight-header-layout-6) #techalgospotlight-header-inner, " +
        ".techalgospotlight-layout__boxed.techalgospotlight-sticky-header:not(.techalgospotlight-is-mobile).techalgospotlight-header-layout-6 #techalgospotlight-header-inner .techalgospotlight-nav-container > .techalgospotlight-container " +
        ".techalgospotlight-layout__boxed.techalgospotlight-sticky-header:not(.techalgospotlight-is-mobile).techalgospotlight-header-layout-4 #techalgospotlight-header-inner .techalgospotlight-nav-container > .techalgospotlight-container " +
        ".techalgospotlight-layout__boxed.techalgospotlight-sticky-header:not(.techalgospotlight-is-mobile).techalgospotlight-header-layout-3 #techalgospotlight-header-inner .techalgospotlight-nav-container > .techalgospotlight-container { max-width: " +
        (parseInt(newval.value) + 100) +
        "px; }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Transparent Header Logo max height.
   */
  api("techalgospotlight_tsp_logo_max_height", function (value) {
    value.bind(function (newval) {
      var $logo = $(".techalgospotlight-tsp-header .techalgospotlight-logo");

      if (!$logo.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_tsp_logo_max_height"
      );
      var style_css = "";

      style_css += techalgospotlight_range_field_css(
        ".techalgospotlight-tsp-header .techalgospotlight-logo img",
        "max-height",
        newval,
        true,
        "px"
      );
      style_css += techalgospotlight_range_field_css(
        ".techalgospotlight-tsp-header .techalgospotlight-logo img.techalgospotlight-svg-logo",
        "height",
        newval,
        true,
        "px"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Transparent Header Logo margin.
   */
  api("techalgospotlight_tsp_logo_margin", function (value) {
    value.bind(function (newval) {
      var $logo = $(".techalgospotlight-tsp-header .techalgospotlight-logo");

      if (!$logo.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_tsp_logo_margin"
      );

      var style_css = techalgospotlight_spacing_field_css(
        ".techalgospotlight-tsp-header .techalgospotlight-logo .logo-inner",
        "margin",
        newval,
        true
      );
      $style_tag.html(style_css);
    });
  });

  /**
   * Transparent header - Main Header & Topbar background.
   */
  api("techalgospotlight_tsp_header_background", function (value) {
    value.bind(function (newval) {
      var $tsp_header = $(".techalgospotlight-tsp-header");

      if (!$tsp_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_tsp_header_background"
      );

      var style_css = "";
      style_css += techalgospotlight_design_options_css(
        ".techalgospotlight-tsp-header #techalgospotlight-header-inner",
        newval,
        "background"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Transparent header - Main Header & Topbar font color.
   */
  api("techalgospotlight_tsp_header_font_color", function (value) {
    value.bind(function (newval) {
      var $tsp_header = $(".techalgospotlight-tsp-header");

      if (!$tsp_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_tsp_header_font_color"
      );

      var style_css = "";

      newval["text-color"] = newval["text-color"]
        ? newval["text-color"]
        : "inherit";
      newval["link-color"] = newval["link-color"]
        ? newval["link-color"]
        : "inherit";
      newval["link-hover-color"] = newval["link-hover-color"]
        ? newval["link-hover-color"]
        : "inherit";

      /** Header **/

      // Text color.
      style_css +=
        ".techalgospotlight-tsp-header .techalgospotlight-logo .site-description { color: " +
        newval["text-color"] +
        "; }";

      // Link color.
      if (newval["link-color"]) {
        style_css +=
          ".techalgospotlight-tsp-header #techalgospotlight-header, " +
          ".techalgospotlight-tsp-header .techalgospotlight-header-widgets a:not(.techalgospotlight-btn), " +
          ".techalgospotlight-tsp-header .techalgospotlight-logo a," +
          ".techalgospotlight-tsp-header .techalgospotlight-hamburger, " +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li > a { color: " +
          newval["link-color"] +
          "; }";
        style_css +=
          ".techalgospotlight-tsp-header .hamburger-inner," +
          ".techalgospotlight-tsp-header .hamburger-inner::before," +
          ".techalgospotlight-tsp-header .hamburger-inner::after { background-color: " +
          newval["link-color"] +
          "; }";
      }

      // Link hover color.
      if (newval["link-hover-color"]) {
        style_css +=
          ".techalgospotlight-tsp-header .techalgospotlight-header-widgets a:not(.techalgospotlight-btn):hover, " +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-header-widgets .techalgospotlight-active," +
          ".techalgospotlight-tsp-header .techalgospotlight-logo .site-title a:hover, " +
          ".techalgospotlight-tsp-header .techalgospotlight-hamburger:hover .hamburger-label, " +
          ".is-mobile-menu-active .techalgospotlight-tsp-header .techalgospotlight-hamburger .hamburger-label," +
          ".techalgospotlight-tsp-header.using-keyboard .site-title a:focus," +
          ".techalgospotlight-tsp-header.using-keyboard .techalgospotlight-header-widgets a:not(.techalgospotlight-btn):focus," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li.hovered > a," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li > a:hover," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li.menu-item-has-children:hover > a," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current-menu-item > a," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current-menu-ancestor > a," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li.page_item_has_children:hover > a," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current_page_item > a," +
          ".techalgospotlight-tsp-header #techalgospotlight-header-inner .techalgospotlight-nav > ul > li.current_page_ancestor > a { color: " +
          newval["link-hover-color"] +
          "; }";

        style_css +=
          ".techalgospotlight-tsp-header .techalgospotlight-hamburger:hover .hamburger-inner," +
          ".techalgospotlight-tsp-header .techalgospotlight-hamburger:hover .hamburger-inner::before," +
          ".techalgospotlight-tsp-header .techalgospotlight-hamburger:hover .hamburger-inner::after," +
          ".is-mobile-menu-active .techalgospotlight-tsp-header .techalgospotlight-hamburger .hamburger-inner," +
          ".is-mobile-menu-active .techalgospotlight-tsp-header .techalgospotlight-hamburger .hamburger-inner::before," +
          ".is-mobile-menu-active .techalgospotlight-tsp-header .techalgospotlight-hamburger .hamburger-inner::after { background-color: " +
          newval["link-hover-color"] +
          "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Transparent header - Main Header & Topbar border.
   */
  api("techalgospotlight_tsp_header_border", function (value) {
    value.bind(function (newval) {
      var $tsp_header = $(".techalgospotlight-tsp-header");

      if (!$tsp_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_tsp_header_border"
      );

      var style_css = "";

      style_css += techalgospotlight_design_options_css(
        ".techalgospotlight-tsp-header #techalgospotlight-header-inner",
        newval,
        "border"
      );

      // Separator color.
      newval["separator-color"] = newval["separator-color"]
        ? newval["separator-color"]
        : "inherit";
      style_css +=
        ".techalgospotlight-tsp-header .techalgospotlight-header-widget:after { background-color: " +
        newval["separator-color"] +
        "; }";

      $style_tag.html(style_css);
    });
  });

  /**
   * Page Header layout.
   */
  api("techalgospotlight_page_header_alignment", function (value) {
    value.bind(function (newval) {
      if ($body.hasClass("single-post")) {
        return;
      }

      $body.removeClass(function (index, className) {
        return (
          className.match(/(^|\s)techalgospotlight-page-title-align-\S+/g) || []
        ).join(" ");
      });

      $body.addClass("techalgospotlight-page-title-align-" + newval);
    });
  });

  /**
   * Page Header spacing.
   */
  api("techalgospotlight_page_header_spacing", function (value) {
    value.bind(function (newval) {
      var $page_header = $(".page-header");

      if (!$page_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_page_header_spacing"
      );

      var style_css = techalgospotlight_spacing_field_css(
        ".techalgospotlight-page-title-align-left .page-header.techalgospotlight-has-page-title, .techalgospotlight-page-title-align-right .page-header.techalgospotlight-has-page-title, .techalgospotlight-page-title-align-center .page-header .techalgospotlight-page-header-wrapper",
        "padding",
        newval,
        true
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Page Header background.
   */
  api("techalgospotlight_page_header_background", function (value) {
    value.bind(function (newval) {
      var $page_header = $(".page-header");

      if (!$page_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_page_header_background"
      );

      var style_css = "";
      style_css += techalgospotlight_design_options_css(
        ".page-header",
        newval,
        "background"
      );
      style_css += techalgospotlight_design_options_css(
        ".techalgospotlight-tsp-header:not(.techalgospotlight-tsp-absolute) #masthead",
        newval,
        "background"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Header Text color.
   */
  api("techalgospotlight_page_header_text_color", function (value) {
    value.bind(function (newval) {
      var $page_header = $(".page-header");

      if (!$page_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_page_header_text_color"
      );
      var style_css = "";

      newval["text-color"] = newval["text-color"]
        ? newval["text-color"]
        : "inherit";
      newval["link-color"] = newval["link-color"]
        ? newval["link-color"]
        : "inherit";
      newval["link-hover-color"] = newval["link-hover-color"]
        ? newval["link-hover-color"]
        : "inherit";

      // Text color.
      style_css +=
        ".page-header .page-title { color: " + newval["text-color"] + "; }";
      style_css +=
        ".page-header .techalgospotlight-page-header-description" +
        "{ color: " +
        techalgospotlight_hex2rgba(newval["text-color"], 0.75) +
        "}";

      // Link color.
      style_css +=
        ".page-header .techalgospotlight-breadcrumbs a" +
        "{ color: " +
        newval["link-color"] +
        "; }";

      style_css +=
        ".page-header .techalgospotlight-breadcrumbs span," +
        ".page-header .breadcrumb-trail .trail-items li::after, .page-header .techalgospotlight-breadcrumbs .separator" +
        "{ color: " +
        techalgospotlight_hex2rgba(newval["link-color"], 0.75) +
        "}";

      $style_tag.html(style_css);
    });
  });

  /**
   * Page Header border.
   */
  api("techalgospotlight_page_header_border", function (value) {
    value.bind(function (newval) {
      var $page_header = $(".page-header");

      if (!$page_header.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_page_header_border"
      );
      var style_css = techalgospotlight_design_options_css(
        ".page-header",
        newval,
        "border"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Breadcrumbs alignment.
   */
  api("techalgospotlight_breadcrumbs_alignment", function (value) {
    value.bind(function (newval) {
      var $breadcrumbs = $(
        "#main > .techalgospotlight-breadcrumbs > .techalgospotlight-container"
      );

      if (!$breadcrumbs.length) {
        return;
      }

      $breadcrumbs.removeClass(function (index, className) {
        return (
          className.match(/(^|\s)techalgospotlight-text-align\S+/g) || []
        ).join(" ");
      });

      $breadcrumbs.addClass("techalgospotlight-text-align-" + newval);
    });
  });

  /**
   * Breadcrumbs spacing.
   */
  api("techalgospotlight_breadcrumbs_spacing", function (value) {
    value.bind(function (newval) {
      var $breadcrumbs = $(".techalgospotlight-breadcrumbs");

      if (!$breadcrumbs.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_breadcrumbs_spacing"
      );

      var style_css = techalgospotlight_spacing_field_css(
        ".techalgospotlight-breadcrumbs",
        "padding",
        newval,
        true
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Breadcrumbs Background.
   */
  api("techalgospotlight_breadcrumbs_background", function (value) {
    value.bind(function (newval) {
      var $breadcrumbs = $(".techalgospotlight-breadcrumbs");

      if (!$breadcrumbs.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_breadcrumbs_background"
      );
      var style_css = techalgospotlight_design_options_css(
        ".techalgospotlight-breadcrumbs",
        newval,
        "background"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Breadcrumbs Text Color.
   */
  api("techalgospotlight_breadcrumbs_text_color", function (value) {
    value.bind(function (newval) {
      var $breadcrumbs = $(".techalgospotlight-breadcrumbs");

      if (!$breadcrumbs.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_breadcrumbs_text_color"
      );
      var style_css = techalgospotlight_design_options_css(
        ".techalgospotlight-breadcrumbs",
        newval,
        "color"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Breadcrumbs Border.
   */
  api("techalgospotlight_breadcrumbs_border", function (value) {
    value.bind(function (newval) {
      var $breadcrumbs = $(".techalgospotlight-breadcrumbs");

      if (!$breadcrumbs.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_breadcrumbs_border"
      );
      var style_css = techalgospotlight_design_options_css(
        ".techalgospotlight-breadcrumbs",
        newval,
        "border"
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Base HTML font size.
   */
  api("techalgospotlight_html_base_font_size", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_html_base_font_size"
      );
      var style_css = techalgospotlight_range_field_css(
        "html",
        "font-size",
        newval,
        true,
        "%"
      );
      $style_tag.html(style_css);
    });
  });

  /**
   * Font smoothing.
   */
  api("techalgospotlight_font_smoothing", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_font_smoothing"
      );

      if (newval) {
        $style_tag.html(
          "*," +
            "*::before," +
            "*::after {" +
            "-moz-osx-font-smoothing: grayscale;" +
            "-webkit-font-smoothing: antialiased;" +
            "}"
        );
      } else {
        $style_tag.html(
          "*," +
            "*::before," +
            "*::after {" +
            "-moz-osx-font-smoothing: auto;" +
            "-webkit-font-smoothing: auto;" +
            "}"
        );
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_html_base_font_size"
      );
      var style_css = techalgospotlight_range_field_css(
        "html",
        "font-size",
        newval,
        true,
        "%"
      );
      $style_tag.html(style_css);
    });
  });

  /**
   * Body font.
   */
  api("techalgospotlight_body_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_body_font"
      );
      var style_css = techalgospotlight_typography_field_css("body", newval);

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Headings font.
   */
  api("techalgospotlight_headings_font", function (value) {
    var style_css, selector;
    value.bind(function (newval) {
      selector =
        "h1, .h1, .techalgospotlight-logo .site-title, .page-header h1.page-title";
      selector += ", h2, .h2, .woocommerce div.product h1.product_title";
      selector += ", h3, .h3, .woocommerce #reviews #comments h2";
      selector +=
        ", h4, .h4, .woocommerce .cart_totals h2, .woocommerce .cross-sells > h4, .woocommerce #reviews #respond .comment-reply-title";
      selector += ", h5, h6, .h5, .h6";

      style_css = techalgospotlight_typography_field_css(selector, newval);

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_headings_font"
      );
      $style_tag.html(style_css);
    });
  });

  /**
   * Heading 1 font.
   */
  api("techalgospotlight_h1_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag("techalgospotlight_h1_font");

      var style_css = techalgospotlight_typography_field_css(
        "h1, .h1, .techalgospotlight-logo .site-title, .page-header h1.page-title",
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Heading 2 font.
   */
  api("techalgospotlight_h2_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag("techalgospotlight_h2_font");

      var style_css = techalgospotlight_typography_field_css(
        "h2, .h2, .woocommerce div.product h1.product_title",
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Heading 3 font.
   */
  api("techalgospotlight_h3_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag("techalgospotlight_h3_font");

      var style_css = techalgospotlight_typography_field_css(
        "h3, .h3, .woocommerce #reviews #comments h2",
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Heading 4 font.
   */
  api("techalgospotlight_h4_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag("techalgospotlight_h4_font");

      var style_css = techalgospotlight_typography_field_css(
        "h4, .h4, .woocommerce .cart_totals h2, .woocommerce .cross-sells > h4, .woocommerce #reviews #respond .comment-reply-title",
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Heading 5 font.
   */
  api("techalgospotlight_h5_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag("techalgospotlight_h5_font");
      var style_css = techalgospotlight_typography_field_css("h5, .h5", newval);

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Heading 6 font.
   */
  api("techalgospotlight_h6_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag("techalgospotlight_h6_font");
      var style_css = techalgospotlight_typography_field_css("h6, .h6", newval);

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Heading emphasized font.
   */
  api("techalgospotlight_heading_em_font", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_heading_em_font"
      );
      var style_css = techalgospotlight_typography_field_css(
        "h1 em, h2 em, h3 em, h4 em, h5 em, h6 em, .h1 em, .h2 em, .h3 em, .h4 em, .h5 em, .h6 em, .techalgospotlight-logo .site-title em, .error-404 .page-header h1 em",
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Footer widget title font size.
   */
  api("techalgospotlight_footer_widget_title_font_size", function (value) {
    value.bind(function (newval) {
      var $widget_title = $(
        "#colophon .widget-title, #colophon .wp-block-heading"
      );

      if (!$widget_title.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_footer_widget_title_font_size"
      );
      var style_css = "";

      style_css += techalgospotlight_range_field_css(
        "#colophon .widget-title, #colophon .wp-block-heading",
        "font-size",
        newval,
        true,
        newval.unit
      );

      $style_tag.html(style_css);
    });
  });

  /**
   * Page title font size.
   */
  api("techalgospotlight_page_header_font_size", function (value) {
    value.bind(function (newval) {
      var $page_title = $(".page-header .page-title");

      if (!$page_title.length) {
        return;
      }

      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_page_header_font_size"
      );
      var style_css = "";

      style_css += techalgospotlight_range_field_css(
        "#page .page-header .page-title",
        "font-size",
        newval,
        true,
        newval.unit
      );

      $style_tag.html(style_css);
    });
  });

  var $btn_selectors =
    ".techalgospotlight-btn, " +
    "body:not(.wp-customizer) input[type=submit], " +
    ".site-main .woocommerce #respond input#submit, " +
    ".site-main .woocommerce a.button, " +
    ".site-main .woocommerce button.button, " +
    ".site-main .woocommerce input.button, " +
    ".woocommerce ul.products li.product .added_to_cart, " +
    ".woocommerce ul.products li.product .button, " +
    ".woocommerce div.product form.cart .button, " +
    ".woocommerce #review_form #respond .form-submit input, " +
    "#infinite-handle span";

  var $btn_hover_selectors =
    ".techalgospotlight-btn:hover, " +
    ".techalgospotlight-btn:focus, " +
    "body:not(.wp-customizer) input[type=submit]:hover, " +
    "body:not(.wp-customizer) input[type=submit]:focus, " +
    ".site-main .woocommerce #respond input#submit:hover, " +
    ".site-main .woocommerce #respond input#submit:focus, " +
    ".site-main .woocommerce a.button:hover, " +
    ".site-main .woocommerce a.button:focus, " +
    ".site-main .woocommerce button.button:hover, " +
    ".site-main .woocommerce button.button:focus, " +
    ".site-main .woocommerce input.button:hover, " +
    ".site-main .woocommerce input.button:focus, " +
    ".woocommerce ul.products li.product .added_to_cart:hover, " +
    ".woocommerce ul.products li.product .added_to_cart:focus, " +
    ".woocommerce ul.products li.product .button:hover, " +
    ".woocommerce ul.products li.product .button:focus, " +
    ".woocommerce div.product form.cart .button:hover, " +
    ".woocommerce div.product form.cart .button:focus, " +
    ".woocommerce #review_form #respond .form-submit input:hover, " +
    ".woocommerce #review_form #respond .form-submit input:focus, " +
    "#infinite-handle span:hover";

  /**
   * Primary button background color.
   */
  api("techalgospotlight_primary_button_bg_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_bg_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_selectors + "{ background-color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button hover background color.
   */
  api("techalgospotlight_primary_button_hover_bg_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_hover_bg_color"
      );
      var style_css = "";

      if (newval) {
        style_css =
          $btn_hover_selectors + " { background-color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button text color.
   */
  api("techalgospotlight_primary_button_text_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_text_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_selectors + " { color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button hover text color.
   */
  api("techalgospotlight_primary_button_hover_text_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_hover_text_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_hover_selectors + " { color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button border width.
   */
  api("techalgospotlight_primary_button_border_width", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_border_width"
      );
      var style_css = "";

      if (newval) {
        style_css =
          $btn_selectors + " { border-width: " + newval.value + "rem; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button border radius.
   */
  api("techalgospotlight_primary_button_border_radius", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_border_radius"
      );
      var style_css = "";

      if (newval) {
        style_css =
          $btn_selectors +
          " { " +
          "border-top-left-radius: " +
          newval["top-left"] +
          "rem;" +
          "border-top-right-radius: " +
          newval["top-right"] +
          "rem;" +
          "border-bottom-left-radius: " +
          newval["bottom-left"] +
          "rem;" +
          "border-bottom-right-radius: " +
          newval["bottom-right"] +
          "rem; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button border color.
   */
  api("techalgospotlight_primary_button_border_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_border_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_selectors + " { border-color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button hover border color.
   */
  api("techalgospotlight_primary_button_hover_border_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_hover_border_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_hover_selectors + " { border-color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Primary button typography.
   */
  api("techalgospotlight_primary_button_typography", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_primary_button_typography"
      );
      var style_css = techalgospotlight_typography_field_css(
        $btn_selectors,
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  // Secondary button.
  var $btn_sec_selectors =
    ".btn-secondary, .techalgospotlight-btn.btn-secondary";

  var $btn_sec_hover_selectors =
    ".btn-secondary:hover, " +
    ".btn-secondary:focus, " +
    ".techalgospotlight-btn.btn-secondary:hover, " +
    ".techalgospotlight-btn.btn-secondary:focus";

  /**
   * Secondary button background color.
   */
  api("techalgospotlight_secondary_button_bg_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_bg_color"
      );
      var style_css = "";

      if (newval) {
        style_css =
          $btn_sec_selectors + "{ background-color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Secondary button hover background color.
   */
  api("techalgospotlight_secondary_button_hover_bg_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_hover_bg_color"
      );
      var style_css = "";

      if (newval) {
        style_css =
          $btn_sec_hover_selectors + "{ background-color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Secondary button text color.
   */
  api("techalgospotlight_secondary_button_text_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_text_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_sec_selectors + "{ color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Secondary button hover text color.
   */
  api("techalgospotlight_secondary_button_hover_text_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_hover_text_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_sec_hover_selectors + "{ color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Secondary button border width.
   */
  api("techalgospotlight_secondary_button_border_width", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_border_width"
      );
      var style_css = "";

      if (newval) {
        style_css =
          $btn_sec_selectors + " { border-width: " + newval.value + "rem; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Secondary button border radius.
   */
  api("techalgospotlight_secondary_button_border_radius", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_border_radius"
      );
      var style_css = "";

      if (newval) {
        style_css =
          $btn_sec_selectors +
          " { " +
          "border-top-left-radius: " +
          newval["top-left"] +
          "rem;" +
          "border-top-right-radius: " +
          newval["top-right"] +
          "rem;" +
          "border-bottom-left-radius: " +
          newval["bottom-left"] +
          "rem;" +
          "border-bottom-right-radius: " +
          newval["bottom-right"] +
          "rem; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Secondary button border color.
   */
  api("techalgospotlight_secondary_button_border_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_border_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_sec_selectors + " { border-color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Secondary button hover border color.
   */
  api(
    "techalgospotlight_secondary_button_hover_border_color",
    function (value) {
      value.bind(function (newval) {
        $style_tag = techalgospotlight_get_style_tag(
          "techalgospotlight_secondary_button_hover_border_color"
        );
        var style_css = "";

        if (newval) {
          style_css =
            $btn_sec_hover_selectors + " { border-color: " + newval + "; }";
        }

        $style_tag.html(style_css);
      });
    }
  );

  /**
   * Secondary button typography.
   */
  api("techalgospotlight_secondary_button_typography", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_secondary_button_typography"
      );
      var style_css = techalgospotlight_typography_field_css(
        $btn_sec_selectors,
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  // Text button.
  var $btn_text_selectors = ".techalgospotlight-btn.btn-text-1, .btn-text-1";

  var $btn_text_hover_selectors =
    ".techalgospotlight-btn.btn-text-1:hover, .techalgospotlight-btn.btn-text-1:focus, .btn-text-1:hover, .btn-text-1:focus";

  /**
   * Text button text color.
   */
  api("techalgospotlight_text_button_text_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_text_button_text_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_text_selectors + "{ color: " + newval + "; }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Text button hover text color.
   */
  api("techalgospotlight_text_button_hover_text_color", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_text_button_hover_text_color"
      );
      var style_css = "";

      if (newval) {
        style_css = $btn_text_hover_selectors + "{ color: " + newval + "; }";
        style_css +=
          ".techalgospotlight-btn.btn-text-1 > span::before { background-color: " +
          newval +
          " }";
      }

      $style_tag.html(style_css);
    });
  });

  /**
   * Text button typography.
   */
  api("techalgospotlight_text_button_typography", function (value) {
    value.bind(function (newval) {
      $style_tag = techalgospotlight_get_style_tag(
        "techalgospotlight_text_button_typography"
      );
      var style_css = techalgospotlight_typography_field_css(
        $btn_text_selectors,
        newval
      );

      techalgospotlight_enqueue_google_font(newval["font-family"]);

      $style_tag.html(style_css);
    });
  });

  /**
   * Section Heading Style Enable.
   */
  api("techalgospotlight_section_heading_style", function (value) {
    value.bind(function (newval) {
      $body
        .removeClass(function (index, className) {
          return (
            className.match(/(^|\s)is-section-heading-init-s\S+/g) || []
          ).join(" ");
        })
        .addClass(
          "is-section-heading-init-s" +
            api.value("techalgospotlight_section_heading_style")()
        );
    });
  });

  // Selective refresh.
  if (api.selectiveRefresh) {
    // Bind partial content rendered event.
    api.selectiveRefresh.bind("partial-content-rendered", function (placement) {
      // Hero Slider.
      if (
        "techalgospotlight_hero_slider_post_number" === placement.partial.id ||
        "techalgospotlight_hero_slider_elements" === placement.partial.id
      ) {
        document
          .querySelectorAll(placement.partial.params.selector)
          .forEach((item) => {
            techalgospotlightHeroSlider(item);
          });
      }

      // Preloader style.
      if ("techalgospotlight_preloader_style" === placement.partial.id) {
        $body.removeClass("techalgospotlight-loaded");

        setTimeout(function () {
          window.techalgospotlight.preloader();
        }, 300);
      }
    });
  }

  // Custom Customizer Preview class (attached to the Customize API)
  api.techalgospotlightCustomizerPreview = {
    // Init
    init: function () {
      var self = this; // Store a reference to "this"
      var previewBody = self.preview.body;

      previewBody.on("click", ".techalgospotlight-set-widget", function () {
        self.preview.send("set-footer-widget", $(this).data("sidebar-id"));
      });
    },
  };

  /**
   * Capture the instance of the Preview since it is private (this has changed in WordPress 4.0)
   *
   * @see https://github.com/WordPress/WordPress/blob/5cab03ab29e6172a8473eb601203c9d3d8802f17/wp-admin/js/customize-controls.js#L1013
   */
  var techalgospotlightOldPreview = api.Preview;
  api.Preview = techalgospotlightOldPreview.extend({
    initialize: function (params, options) {
      // Store a reference to the Preview
      api.techalgospotlightCustomizerPreview.preview = this;

      // Call the old Preview's initialize function
      techalgospotlightOldPreview.prototype.initialize.call(
        this,
        params,
        options
      );
    },
  });

  // Document ready
  $(function () {
    // Initialize our Preview
    api.techalgospotlightCustomizerPreview.init();
  });
})(jQuery);
