<?php
/**
 * Template part for displaying entry thumbnail (featured image).
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

// Get default post media.
$techalgospotlight_media = techalgospotlight_get_post_media('');

if (!$techalgospotlight_media || post_password_required()) {
	return;
}

$techalgospotlight_post_format = get_post_format();

// Wrap with link for non-singular pages.
if ('link' === $techalgospotlight_post_format || !is_single(get_the_ID())) {

	$techalgospotlight_icon = '';

	if (is_sticky()) {
		$techalgospotlight_icon = sprintf(
			'<span class="entry-media-icon is_sticky" title="%1$s" aria-hidden="true"><span class="entry-media-icon-wrapper">%2$s%3$s</span></span>',
			esc_attr__('Featured', 'techalgospotlight'),
			techalgospotlight()->icons->get_svg(
				'pin',
				array(
					'class' => 'top-icon',
					'aria-hidden' => 'true',
				)
			),
			techalgospotlight()->icons->get_svg('pin', array('aria-hidden' => 'true'))
		);
	} elseif ('video' === $techalgospotlight_post_format) {

		$techalgospotlight_icon = sprintf(
			'<span class="entry-media-icon" aria-hidden="true"><span class="entry-media-icon-wrapper">%1$s%2$s</span></span>',
			techalgospotlight()->icons->get_svg(
				'play-2',
				array(
					'class' => 'top-icon',
					'aria-hidden' => 'true',
				)
			),
			techalgospotlight()->icons->get_svg('play-2', array('aria-hidden' => 'true'))
		);
	} elseif ('link' === $techalgospotlight_post_format) {
		$techalgospotlight_icon = sprintf(
			'<span class="entry-media-icon" title="%1$s" aria-hidden="true"><span class="entry-media-icon-wrapper">%2$s%3$s</span></span>',
			esc_url(techalgospotlight_entry_get_permalink()),
			techalgospotlight()->icons->get_svg(
				'external-link',
				array(
					'class' => 'top-icon',
					'aria-hidden' => 'true',
				)
			),
			techalgospotlight()->icons->get_svg('external-link', array('aria-hidden' => 'true'))
		);
	}

	$techalgospotlight_icon = apply_filters('techalgospotlight_post_format_media_icon', $techalgospotlight_icon, $techalgospotlight_post_format);

	$techalgospotlight_media = sprintf(
		'<a href="%1$s" class="entry-image-link">%2$s%3$s</a>',
		esc_url(techalgospotlight_entry_get_permalink()),
		$techalgospotlight_media,
		$techalgospotlight_icon
	);
}

$techalgospotlight_media = apply_filters('techalgospotlight_post_thumbnail', $techalgospotlight_media);

// Print the post thumbnail.
echo wp_kses(
	sprintf(
		'<div class="post-thumb entry-media thumbnail">%1$s</div>',
		$techalgospotlight_media
	),
	techalgospotlight_get_allowed_html_tags()
);
