<?php
/**
 * Template part for displaying video format entry.
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

if (post_password_required()) {
	return;
}

if (has_post_thumbnail()):

	get_template_part('template-parts/entry/format/media');

else:

	$techalgospotlight_media = techalgospotlight_get_post_media('video');

	if ($techalgospotlight_media): ?>

		<div class="post-thumb entry-media thumbnail">
			<div class="techalgospotlight-video-container wp-embed-responsive">
				<figure class="is-type-video wp-embed-aspect-16-9 wp-has-aspect-ratio">
					<div class="wp-block-embed__wrapper">
						<?php echo $techalgospotlight_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</figure>
			</div>
		</div>

		<?php
	endif;

endif;
