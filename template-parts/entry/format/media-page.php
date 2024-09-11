<?php
/**
 * Template part for displaying page featured image.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

// Get default post media.
$techalgospotlight_media = techalgospotlight_get_post_media('');

if (!$techalgospotlight_media || post_password_required()) {
	return;
}

$techalgospotlight_media = apply_filters('techalgospotlight_post_thumbnail', $techalgospotlight_media, get_the_ID());

$techalgospotlight_classes = array('post-thumb', 'entry-media', 'thumbnail');

$techalgospotlight_classes = apply_filters('techalgospotlight_post_thumbnail_wrapper_classes', $techalgospotlight_classes, get_the_ID());
$techalgospotlight_classes = trim(implode(' ', array_unique($techalgospotlight_classes)));

// Print the post thumbnail.
echo wp_kses_post(
	sprintf(
		'<div class="%2$s">%1$s</div>',
		$techalgospotlight_media,
		esc_attr($techalgospotlight_classes)
	)
);
