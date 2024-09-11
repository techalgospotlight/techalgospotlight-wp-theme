<?php
/**
 * Template part for displaying quote format entry.
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

$techalgospotlight_quote_content = apply_filters('techalgospotlight_post_format_quote_content', get_the_content());
$techalgospotlight_quote_author = apply_filters('techalgospotlight_post_format_quote_author', get_the_title());
$techalgospotlight_quote_bg = has_post_thumbnail() ? ' style="background-image: url(\'' . esc_url(get_the_post_thumbnail_url()) . '\')"' : '';
?>

<div class="techalgospotlight-blog-entry-content">
	<div class="entry-content techalgospotlight-entry" <?php techalgospotlight_schema_markup('text'); ?>>

		<?php if (!is_single()) { ?>
			<a href="<?php the_permalink(); ?>" class="quote-link"
				aria-label="<?php esc_attr_e('Read more', 'techalgospotlight'); ?>"></a>
		<?php } ?>

		<div class="quote-post-bg" <?php echo $techalgospotlight_quote_bg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>></div>

		<div class="quote-inner">

			<?php echo techalgospotlight()->icons->get_svg('quote', array('class' => 'icon-quote')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<h3><?php echo wp_kses($techalgospotlight_quote_content, techalgospotlight_get_allowed_html_tags()); ?></h3>
			<div class="author">
				<?php echo wp_kses($techalgospotlight_quote_author, techalgospotlight_get_allowed_html_tags()); ?>
			</div>

		</div><!-- END .quote-inner -->

	</div>
</div><!-- END .techalgospotlight-blog-entry-content -->