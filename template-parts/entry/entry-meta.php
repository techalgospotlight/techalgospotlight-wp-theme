<?php
/**
 * Template part for displaying entry meta info.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
 * Only show meta tags for posts.
 */
if (!in_array(get_post_type(), (array) apply_filters('techalgospotlight_entry_meta_post_type', array('post')), true)) {
	return;
}

do_action('techalgospotlight_before_entry_meta');

// Get meta items to be displayed.
$techalgospotlight_meta_elements = techalgospotlight_get_entry_meta_elements();

if (isset($args['techalgospotlight_meta_callback'])) {
	$techalgospotlight_meta_elements = call_user_func($args['techalgospotlight_meta_callback']);
}

if (!empty($techalgospotlight_meta_elements)) {

	echo '<div class="entry-meta"><div class="entry-meta-elements">';

	do_action('techalgospotlight_before_entry_meta_elements');

	// Loop through meta items.
	foreach ($techalgospotlight_meta_elements as $techalgospotlight_meta_item) {

		// Call a template tag function.
		if (function_exists('techalgospotlight_entry_meta_' . $techalgospotlight_meta_item)) {
			call_user_func('techalgospotlight_entry_meta_' . $techalgospotlight_meta_item);
		}
	}

	// Add edit post link.
	$techalgospotlight_edit_icon = techalgospotlight()->icons->get_meta_icon('edit', techalgospotlight()->icons->get_svg('edit-3', array('aria-hidden' => 'true')));

	techalgospotlight_edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				$techalgospotlight_edit_icon . __('Edit <span class="screen-reader-text">%s</span>', 'techalgospotlight'),
				techalgospotlight_get_allowed_html_tags()
			),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

	do_action('techalgospotlight_after_entry_meta_elements');

	echo '</div></div>';
}

do_action('techalgospotlight_after_entry_meta');
