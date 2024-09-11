<?php
/**
 * techalgospotlight Customizer helper functions.
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
 * Returns array of available widgets.
 *
 * @since 1.0.0
 * @return array, $widgets array of available widgets.
 */
function techalgospotlight_get_customizer_widgets()
{

	$widgets = array(
		'text' => 'techalgospotlight_Customizer_Widget_Text',
		'advertisements' => 'techalgospotlight_Customizer_Widget_Advertisements',
		'nav' => 'techalgospotlight_Customizer_Widget_Nav',
		'socials' => 'techalgospotlight_Customizer_Widget_Socials',
		'search' => 'techalgospotlight_Customizer_Widget_Search',
		'darkmode' => 'techalgospotlight_Customizer_Widget_Darkmode',
		'button' => 'techalgospotlight_Customizer_Widget_Button',
	);

	return apply_filters('techalgospotlight_customizer_widgets', $widgets);
}

/**
 * Get choices for "Hide on" customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function techalgospotlight_get_display_choices()
{

	// Default options.
	$return = array(
		'home' => array(
			'title' => esc_html__('Home Page', 'techalgospotlight'),
		),
		'posts_page' => array(
			'title' => esc_html__('Blog / Posts Page', 'techalgospotlight'),
		),
		'search' => array(
			'title' => esc_html__('Search', 'techalgospotlight'),
		),
		'archive' => array(
			'title' => esc_html__('Archive', 'techalgospotlight'),
			'desc' => esc_html__('Dynamic pages such as categories, tags, custom taxonomies...', 'techalgospotlight'),
		),
		'post' => array(
			'title' => esc_html__('Single Post', 'techalgospotlight'),
		),
		'page' => array(
			'title' => esc_html__('Single Page', 'techalgospotlight'),
		),
	);

	// Get additionally registered post types.
	$post_types = get_post_types(
		array(
			'public' => true,
			'_builtin' => false,
		),
		'objects'
	);

	if (is_array($post_types) && !empty($post_types)) {
		foreach ($post_types as $slug => $post_type) {
			$return[$slug] = array(
				'title' => $post_type->label,
			);
		}
	}

	return apply_filters('techalgospotlight_display_choices', $return);
}

/**
 * Get device choices for "Display on" customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function techalgospotlight_get_device_choices()
{

	// Default options.
	$return = array(
		'desktop' => array(
			'title' => esc_html__('Hide On Desktop', 'techalgospotlight'),
		),
		'tablet' => array(
			'title' => esc_html__('Hide On Tablet', 'techalgospotlight'),
		),
		'mobile' => array(
			'title' => esc_html__('Hide On Mobile', 'techalgospotlight'),
		),
	);

	return apply_filters('techalgospotlight_device_choices', $return);
}
