<?php
/**
 * Template part for displaying audio format entry.
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

$techalgospotlight_media = techalgospotlight_get_post_media('audio');

if ($techalgospotlight_media): ?>

	<div class="post-thumb entry-media thumbnail">
		<div class="techalgospotlight-audio-wrapper">
			<?php echo $techalgospotlight_media; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>

<?php else: ?>

	<?php get_template_part('template-parts/entry/format/media'); ?>

	<?php
endif;
