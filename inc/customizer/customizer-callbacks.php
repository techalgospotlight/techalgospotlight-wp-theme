<?php
/**
 * techalgospotlight Customizer sanitization callback functions.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Number sanitization callback
 *
 * @since 1.0.0
 * @param int $val Number.
 */
function techalgospotlight_sanitize_number($val)
{
	return is_numeric($val) ? $val : 0;
}

/**
 * Toggle field sanitization.
 *
 * @since 1.0.0
 * @param mixed  $input    Value of the toggle field.
 * @param object $setting  Setting object.
 */
function techalgospotlight_sanitize_toggle($input, $setting)
{

	if ('techalgospotlight_enable_front_page' === $setting) {
		sanitize_key($input);
	}
	// Ensure input is a slug.
	$input = sanitize_key($input);

	return true === (bool) $input;
}

/**
 * Sanitize checkbox
 *
 * @param mixed $input Value of checkbox.
 * @return boolean
 */
function techalgospotlight_sanitize_checkbox($input)
{

	// returns true if checkbox is checked.
	return (isset($input) ? true : false);
}

/**
 * Select field sanitization callback.
 *
 * @since 1.0.0
 * @param mixed  $input    Value of the select field.
 * @param object $setting  Setting object.
 */
function techalgospotlight_sanitize_select($input, $setting)
{

	$multiple = isset($setting->manager->get_control($setting->id)->multiple) ? $setting->manager->get_control($setting->id)->multiple : false;

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control($setting->id)->choices;

	if ($multiple) {

		// Check if input is array.
		if (is_array($input) && !empty($input)) {

			$return = array();

			// Get only valid values.
			foreach ($input as $selected) {

				$selected = sanitize_key($selected);

				if (array_key_exists($selected, $choices)) {
					$return[] = $selected;
				}
			}

			// Return valid only.
			return $return;

		} else {

			// Return default if input valid.
			return array();
		}
	} else {

		// Ensure input is a slug.
		$input = sanitize_key($input);

		// If the input is a valid key, return it; otherwise, return the default.
		return (array_key_exists($input, $choices) ? $input : $setting->default);
	}

}

/**
 * Textarea field sanitization callback.
 *
 * @since 1.0.0
 * @param mixed  $input    Value of the select field.
 * @param object $setting  Setting object.
 */
function techalgospotlight_sanitize_textarea($input, $setting)
{

	return wp_kses_post($input);
}

/**
 * Color field sanitization callback
 *
 * @since 1.0.0
 * @param string $color Color code.
 */
function techalgospotlight_sanitize_color($color)
{

	if (empty($color) || is_array($color)) {
		return '';
	}

	if (false === strpos($color, 'rgba')) {
		return techalgospotlight_sanitize_hex_color($color);
	}

	return techalgospotlight_sanitize_alpha_color($color);
}

/**
 * Sanitize HEX color.
 *
 * @since 1.0.0
 * @param string $color Color code in HEX.
 */
function techalgospotlight_sanitize_hex_color($color)
{

	if ('' === $color) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
		return $color;
	}

	return '';
}

/**
 * Sanitize Alpha color.
 *
 * @since 1.0.0
 * @param string $color Color code in RGBA.
 */
function techalgospotlight_sanitize_alpha_color($color)
{

	if ('' === $color) {
		return '';
	}

	if (false === strpos($color, 'rgba')) {
		/* Hex sanitize */
		return techalgospotlight_sanitize_hex_color($color);
	}

	/* rgba sanitize */
	$color = str_replace(' ', '', $color);
	sscanf($color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha);
	return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Sanitize image.
 *
 * @since 1.0.0
 * @param mixed  $image    Selected image..
 * @param object $setting  Setting object.
 */
function techalgospotlight_sanitize_image($image, $setting)
{
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'bmp' => 'image/bmp',
		'tif|tiff' => 'image/tiff',
		'ico' => 'image/x-icon',
		'svg' => 'image/svg+xml',
	);

	// Return an array with file extension and mime_type.
	$file = wp_check_filetype($image, $mimes);

	// If $image has a valid mime_type, return it; otherwise, return the default.
	return ($file['ext'] ? $image : $setting->default);
}

/**
 * Sanitize widget control value.
 *
 * @since 1.0.0
 * @param array  $widgets Array of saved widgets.
 * @param object $setting WP Customize manager object.
 */
function techalgospotlight_sanitize_widget($widgets, $setting)
{

	// Widgets has to be an array.
	if (!is_array($widgets) || empty($widgets)) {
		return array();
	}

	$control = $setting->manager->get_control($setting->id);

	// Control widgets.
	$control_widgets = $control->widgets;

	// Control locations.
	$control_locations = $control->locations;

	// Control visibility options.
	$control_visibility = $control->visibility;

	// Used to control max uses for widgets.
	$widget_uses = array();

	foreach ($widgets as $index => $widget) {

		// Check if this widget is allowed.
		if (!array_key_exists($widget['type'], $control_widgets)) {
			unset($widgets[$index]);
			continue;
		}

		// Set max usage for widget.
		if (!isset($widget_uses[$widget['type']])) {
			$widget_uses[$widget['type']] = isset($control_widgets[$widget['type']]['max_uses']) ? intval($control_widgets[$widget['type']]['max_uses']) : -1;
		}

		// Remove if max widgets exceeded count.
		if (-1 !== $widget_uses[$widget['type']]) {

			if ($widget_uses[$widget['type']] > 0) {

				// Widget count still good.
				$widget_uses[$widget['type']]--;
			} else {

				// Exceeded widget max usage.
				unset($widgets[$index]);
				continue;
			}
		}

		// Ensure widget has values.
		if (!isset($widget['values']) || !is_array($widget['values'])) {
			$widget['values'] = array();
		}

		// Validate the location parameter.
		if (isset($widget['values']['location'])) {

			if (empty($control_locations)) {

				// No locations available.
				unset($widget['values']['location']);
			} elseif (!array_key_exists($widget['values']['location'], $control_locations)) {

				// This locations is not available, take one from array of available locations.
				$widget['values']['location'] = key($control_locations);
			}
		} elseif (!empty($control_locations)) {

			// Widget has to have a location option.
			$widget['values']['location'] = key($control_locations);
		}

		// Validate the visibility parameter.
		if (isset($widget['values']['visibility'])) {

			if (empty($control_visibility)) {

				// No visibility available.
				$widget['values']['visibility'] = '';
			} elseif (!array_key_exists($widget['values']['visibility'], $control_visibility)) {

				// This locations is not available, take one from array of available locations.
				$widget['values']['visibility'] = key($control_visibility);
			}
		} elseif (!empty($control_visibility)) {

			// Widget has to have a location option.
			$widget['values']['visibility'] = key($control_visibility);
		}
		// Validated Image field
		if (isset($widget['values']['image_id']) && $widget['values']['image_id'] !== '') {
			$widget['values']['image_id'] = absint($widget['values']['image_id']);
		}

		// Sanitize display_area checkbox array.
		if (isset($widget['values']['display_area']) && is_array($widget['values']['display_area'])) {
			$widget['values']['display_area'] = array_map('sanitize_text_field', $widget['values']['display_area']);
		}
		// Validate widget values.
		if (!empty($widget['values'])) {
			$classname = $widget['classname'];
			$instance = new $classname($widget['values']);
			$widget['values'] = $instance->values;
		}
	}

	return $widgets;
}

/**
 * No sanitization. Used for controls that only output HTML.
 *
 * @since 1.0.0
 * @param mixed $val Value.
 */
function techalgospotlight_no_sanitize($val)
{
	return $val;
}

/**
 * Function to sanitize sections order control
 *
 * @param string $input Sections order in json format.
 *
 * @return string
 */
function techalgospotlight_sanitize_order($input)
{

	$json = json_decode($input, true);
	foreach ($json as $section => $priority) {
		if (!is_string($section) || !is_int($priority)) {
			return false;
		}
	}
	$filter_empty = array_filter($json, 'techalgospotlight_not_empty');

	return wp_json_encode($filter_empty);
}

/**
 * Function to filter json empty elements.
 *
 * @param int $val Element of json decoded.
 *
 * @return bool
 */
function techalgospotlight_not_empty($val)
{
	return !empty($val);
}

/**
 * Sanitize Background control value.
 *
 * @since 1.0.0
 * @param mixed $background Value.
 */
function techalgospotlight_sanitize_background($background)
{

	$bg_params = array(
		'background-image' => '',
		'background-image-id' => '',
		'background-repeat' => 'repeat',
		'background-position-x' => '50',
		'background-position-y' => '50',
		'background-size' => 'auto',
		'background-attachment' => 'scroll',
	);

	foreach ($bg_params as $key => $value) {

		if (isset($background[$key])) {

			if ('background-image' === $key) {
				$bg_params[$key] = esc_url_raw($background[$key]);
			} else {
				$bg_params[$key] = esc_attr($background[$key]);
			}
		}
	}

	if (empty($bg_params['background-image'])) {
		$bg_params['background-image-id'] = '';
	}

	return $bg_params;
}

/**
 * Sanitize Spacing control value.
 *
 * @since 1.0.0
 * @param mixed  $values  Values.
 * @param object $setting WP Customize Setting instance.
 * @param mixed  $default Default Value.
 */
function techalgospotlight_sanitize_spacing($values, $setting, $default = array())
{

	$control = $setting->manager->get_control($setting->id);
	$control_choices = $control->choices;
	$control_units = $control->unit;

	foreach ($control_choices as $key => $value) {
		if (!isset($values[$key])) {
			$values[$key] = isset($default[$key]) ? $default[$key] : 0;
		}
	}

	foreach ($values as $key => $value) {

		if ('unit' === $key) {
			continue;
		}

		if (!isset($control_choices[$key])) {
			unset($values[$key]);
			continue;
		}

		$values[$key] = is_numeric($value) ? $value : '';
	}

	if (isset($values['unit']) && !in_array($values['unit'], $control_units, true)) {
		if (isset($default['unit'])) {
			$values['unit'] = $default['unit'];
		} elseif (!empty($control_units)) {
			$values['unit'] = $control_units[0];
		} else {
			$values['unit'] = '';
		}
	}

	return $values;
}

/**
 * Sanitize Range control value.
 *
 * @since 1.0.0
 * @param mixed  $value   Values.
 * @param object $setting WP Customize Setting instance.
 * @param mixed  $default Default Value.
 */
function techalgospotlight_sanitize_range($value, $setting, $default = '')
{

	if (is_array($value)) {
		if (isset($value['value'], $value['unit'])) {
			return array(
				'value' => floatval($value['value']),
				'unit' => sanitize_text_field($value['unit']),
			);
		} elseif (isset($value['value'])) {
			return floatval($value['value']);
		}
	}

	return is_numeric($value) ? floatval($value) : $default;
}

/**
 * Sanitize Responsive control value.
 * Iterate through all responsive breakpoints and sanitize each value.
 *
 * @since 1.0.0
 * @param mixed  $values  Values.
 * @param object $setting WP Customize Setting instance.
 */
function techalgospotlight_sanitize_responsive($values, $setting)
{

	$control = $setting->manager->get_control($setting->id);
	$control_type = str_replace('techalgospotlight-', '', $control->type);
	$control_units = isset($control->unit) ? $control->unit : false;

	if (is_array($control->responsive) && !empty($control->responsive)) {

		// Ensure all responsive devices are in value.
		foreach ($control->responsive as $device => $settings) {

			if (!isset($values[$device])) {
				$values[$device] = isset($setting->default[$device]) ? $setting->default[$device] : '';
			}
		}

		// Ensure all devices in value are allowed and sanitize value.
		foreach ($values as $device => $value) {

			if ('unit' === $device) {
				continue;
			}

			if (!isset($control->responsive[$device])) {
				unset($values[$device]);
				continue;
			}

			// Sanitize value.
			$values[$device] = call_user_func_array(
				'techalgospotlight_sanitize_' . $control_type,
				array(
					$values[$device],
					$setting,
					isset($setting->default[$device]) ? $setting->default[$device] : '',
				)
			);
		}
	}

	return $values;
}

/**
 * Typography field sanitization.
 *
 * @since 1.0.0
 * @param mixed  $value    Value of the toggle field.
 * @param object $setting  Setting object.
 */
function techalgospotlight_sanitize_typography($value, $setting)
{

	$defaults = array(
		'font-family' => '',
		'font-subsets' => array(),
		'font-weight' => '',
		'font-style' => '',
		'text-transform' => '',
		'font-size-desktop' => '',
		'font-size-tablet' => '',
		'font-size-mobile' => '',
		'font-size-unit' => '',
		'letter-spacing' => '',
		'letter-spacing-unit' => '',
		'line-height-desktop' => '',
		'line-height-tablet' => '',
		'line-height-mobile' => '',
		'line-height-unit' => '',
	);

	$defaults = wp_parse_args($setting->default, $defaults);

	$value = wp_parse_args($value, $defaults);

	return $value;
}

/**
 * Design Options field sanitization.
 *
 * @since 1.0.0
 * @param mixed  $value    Value of the toggle field.
 * @param object $setting  Setting object.
 */
function techalgospotlight_sanitize_design_options($value, $setting)
{

	$control = $setting->manager->get_control($setting->id);

	$sanitized = (array) $setting->default;

	if (isset($control->display)) {

		if (isset($control->display['background'])) {

			if (!array_key_exists($value['background-type'], $control->display['background'])) {
				$sanitized['background-type'] = 'color';
			} else {
				$sanitized['background-type'] = $value['background-type'];
			}

			// Color.
			if (array_key_exists('color', $control->display['background'])) {

				if (isset($value['background-color'])) {
					$sanitized['background-color'] = techalgospotlight_sanitize_color($value['background-color']);
				}
			}

			// Gradient.
			if (array_key_exists('gradient', $control->display['background'])) {

				if (isset($value['gradient-color-1'])) {
					$sanitized['gradient-color-1'] = techalgospotlight_sanitize_color($value['gradient-color-1']);
				}

				if (isset($value['gradient-color-2'])) {
					$sanitized['gradient-color-2'] = techalgospotlight_sanitize_color($value['gradient-color-2']);
				}

				if (isset($value['gradient-color-1-location'])) {
					$sanitized['gradient-color-1-location'] = techalgospotlight_sanitize_range($value['gradient-color-1-location'], $setting);
				}

				if (isset($value['gradient-color-2-location'])) {
					$sanitized['gradient-color-2-location'] = techalgospotlight_sanitize_range($value['gradient-color-2-location'], $setting);
				}

				if (isset($value['gradient-type']) && in_array($value['gradient-type'], array('linear', 'radial'), true)) {
					$sanitized['gradient-type'] = $value['gradient-type'];
				}

				if (isset($value['gradient-linear-angle'])) {
					$sanitized['gradient-linear-angle'] = techalgospotlight_sanitize_range($value['gradient-linear-angle'], $setting);
				}

				if (isset($value['gradient-position'])) {
					$sanitized['gradient-position'] = sanitize_text_field($value['gradient-position']);
				}
			}

			// Image.
			if (array_key_exists('image', $control->display['background'])) {

				if (isset($value['background-image'])) {
					$sanitized['background-image'] = sanitize_text_field($value['background-image']);
				}

				if (isset($value['background-image-id'])) {
					$sanitized['background-image-id'] = sanitize_text_field($value['background-image-id']);
				}

				if (isset($value['background-repeat'])) {
					$sanitized['background-repeat'] = sanitize_text_field($value['background-repeat']);
				}

				if (isset($value['background-position-x'])) {
					$sanitized['background-position-x'] = intval($value['background-position-x']);
				}

				if (isset($value['background-position-y'])) {
					$sanitized['background-position-y'] = intval($value['background-position-y']);
				}

				if (isset($value['background-size'])) {
					$sanitized['background-size'] = sanitize_text_field($value['background-size']);
				}

				if (isset($value['background-attachment'])) {
					$sanitized['background-attachment'] = sanitize_text_field($value['background-attachment']);
				}

				if (isset($value['background-color-overlay'])) {
					$sanitized['background-color-overlay'] = sanitize_text_field($value['background-color-overlay']);
				}
			}
		}

		if (isset($control->display['color'])) {
			foreach ($control->display['color'] as $id => $title) {
				if (isset($value[$id])) {
					$sanitized[$id] = techalgospotlight_sanitize_color($value[$id]);
				}
			}
		}

		if (isset($control->display['border'])) {

			// Border Color.
			if (array_key_exists('color', $control->display['border']) && isset($value['border-color'])) {
				$sanitized['border-color'] = techalgospotlight_sanitize_color($value['border-color']);
			}

			// Border Style.
			if (isset($value['border-style'])) {
				$sanitized['border-style'] = sanitize_key($value['border-style']);
			}

			// Separator Border Style.
			if (isset($value['separator-color'])) {
				$sanitized['separator-color'] = techalgospotlight_sanitize_color($value['separator-color']);
			}

			// Border Width.
			$border_width = array('left', 'top', 'right', 'bottom');

			foreach ($border_width as $position) {
				if (isset($value['border-' . $position . '-width'])) {
					$sanitized['border-' . $position . '-width'] = intval($value['border-' . $position . '-width']);
				}
			}
		}
	}

	return $sanitized;
}

/**
 * Sortable field sanitization.
 *
 * @since 1.0.0
 * @param mixed  $value    Value of the toggle field.
 * @param object $setting  Setting object.
 */
function techalgospotlight_sanitize_sortable($value, $setting)
{

	$control = $setting->manager->get_control($setting->id);
	$choices = $control->choices;
	$default = $setting->default;

	if (empty($value)) {
		return $default;
	}

	foreach ($value as $item => $config) {
		if (!isset($choices[$item])) {
			unset($value[$item]);
		}
	}

	foreach ($choices as $item => $config) {
		if (!isset($value[$item])) {
			$value[$item] = isset($default[$item]) ? $default[$item] : false;
		}
	}

	return $value;
}

/**
 * Sanitize repeatable data
 *
 * @param JSON   $input     Repeater input.
 * @param Object $setting $wp_customize.
 * @return bool|mixed|string|void
 */
function techalgospotlight_repeater_sanitize($input, $setting)
{
	$control = $setting->manager->get_control($setting->id);
	$fields = $control->fields;
	if (is_string($input)) {
		$input = json_decode(wp_unslash($input), true);
	}
	$data = wp_parse_args($input, array());
	if (!is_array($data)) {
		return false;
	}
	if (!isset($data['_items'])) {
		return false;
	}
	$data = $data['_items'];
	foreach ($data as $i => $item_data) {
		foreach ($item_data as $id => $value) {
			if (isset($fields[$id])) {
				switch (strtolower($fields[$id]['type'])) {
					case 'text':
						$data[$i][$id] = sanitize_text_field($value);
						break;
					case 'url':
						$data[$i][$id] = esc_url_raw($value);
						break;
					case 'textarea':
					case 'editor':
						$data[$i][$id] = wp_kses_post($value);
						break;
					case 'color':
						$data[$i][$id] = techalgospotlight_sanitize_hex_color($value);
						break;
					case 'coloralpha':
						$data[$i][$id] = techalgospotlight_sanitize_alpha_color($value);
						break;
					case 'checkbox':
						$data[$i][$id] = techalgospotlight_sanitize_checkbox($value);
						break;
					case 'select':
						$data[$i][$id] = '';
						if (is_array($fields[$id]['options']) && !empty($fields[$id]['options'])) {
							// if is multiple choices.
							if (is_array($value)) {
								foreach ($value as $k => $v) {
									if (isset($fields[$id]['options'][$v])) {
										$value[$k] = $v;
									}
								}
								$data[$i][$id] = $value;
							} else {
								// is single choice.
								if (isset($fields[$id]['options'][$value])) {
									$data[$i][$id] = $value;
								}
							}
						}
						break;
					case 'radio':
						$data[$i][$id] = sanitize_text_field($value);
						break;
					case 'media':
						$value = wp_parse_args(
							$value,
							array(
								'url' => '',
								'id' => false,
							)
						);
						$value['id'] = absint($value['id']);
						$data[$i][$id]['url'] = sanitize_text_field($value['url']);
						if ($url = wp_get_attachment_url($value['id'])) {
							$data[$i][$id]['id'] = $value['id'];
							$data[$i][$id]['url'] = $url;
						} else {
							$data[$i][$id]['id'] = '';
						}
						break;
					case 'link':
						$value = wp_parse_args(
							$value,
							array(
								'url' => '',
								'title' => '',
								'target' => '',
							)
						);
						$data[$i][$id]['url'] = esc_url($value['url']);
						$data[$i][$id]['title'] = sanitize_text_field($value['title']);
						$data[$i][$id]['target'] = sanitize_text_field($value['target']);
						break;
					case 'gradient':
						if (isset($value['gradient-color-1'])) {
							$sanitized['gradient-color-1'] = techalgospotlight_sanitize_color($value['gradient-color-1']);
						}

						if (isset($value['gradient-color-2'])) {
							$sanitized['gradient-color-2'] = techalgospotlight_sanitize_color($value['gradient-color-2']);
						}

						if (isset($value['gradient-color-1-location'])) {
							$sanitized['gradient-color-1-location'] = techalgospotlight_sanitize_range($value['gradient-color-1-location'], $setting);
						}

						if (isset($value['gradient-color-2-location'])) {
							$sanitized['gradient-color-2-location'] = techalgospotlight_sanitize_range($value['gradient-color-2-location'], $setting);
						}

						if (isset($value['gradient-type']) && in_array($value['gradient-type'], array('linear', 'radial'), true)) {
							$sanitized['gradient-type'] = $value['gradient-type'];
						}

						if (isset($value['gradient-linear-angle'])) {
							$sanitized['gradient-linear-angle'] = techalgospotlight_sanitize_range($value['gradient-linear-angle'], $setting);
						}

						if (isset($value['gradient-position'])) {
							$sanitized['gradient-position'] = sanitize_text_field($value['gradient-position']);
						}
						break;
					case 'design-options':
						techalgospotlight_sanitize_design_options($value, $setting);
						break;
					default:
						$data[$i][$id] = wp_kses_post($value);
				}
			} else {
				$data[$i][$id] = wp_kses_post($value);
			}
			if (count($data[$i]) != count($fields)) {
				foreach ($fields as $k => $f) {
					if (!isset($data[$i][$k])) {
						$data[$i][$k] = '';
					}
				}
			}
		}
	}
	return $data;
}
