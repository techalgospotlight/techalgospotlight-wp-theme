<?php
/**
 * Template part for displaying post format image entry.
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

$techalgospotlight_media = techalgospotlight_get_post_media('image');

if (!$techalgospotlight_media || post_password_required()) {
	return;
}

?>

<div class="post-thumb entry-media thumbnail">

	<?php
	if (!is_single(get_the_ID())) {
		$techalgospotlight_media = sprintf(
			'<a href="%1$s" class="entry-image-link">%2$s</a>',
			esc_url(techalgospotlight_entry_get_permalink()),
			$techalgospotlight_media
		);
	}

	echo $techalgospotlight_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</div>