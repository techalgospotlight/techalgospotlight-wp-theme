<?php
/**
 * Template part for displaying media of the entry.
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

$techalgospotlight_post_format = get_post_format();

if (is_single()) {
	$techalgospotlight_post_format = '';
}

do_action('techalgospotlight_before_entry_thumbnail');

get_template_part('template-parts/entry/format/media', $techalgospotlight_post_format);

do_action('techalgospotlight_after_entry_thumbnail');
