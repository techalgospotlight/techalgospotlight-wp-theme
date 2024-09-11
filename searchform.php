<?php
/**
 * The template for displaying search form.
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

$techalgospotlight_aria_label = !empty($args['aria_label']) ? 'aria-label="' . esc_attr($args['aria_label']) . '"' : 'aria-label="' . esc_attr__('Search for:', 'techalgospotlight') . '"';

// Support for custom search post type.
$techalgospotlight_post_type = apply_filters('techalgospotlight_search_post_type', 'all');
$techalgospotlight_post_type = 'all' !== $techalgospotlight_post_type ? '<input type="hidden" name="post_type" value="' . esc_attr($techalgospotlight_post_type) . '" />' : '';
?>

<form role="search" <?php echo $techalgospotlight_aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get"
	class="techalgospotlight-search-form search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<div>
		<input type="search" class="techalgospotlight-input-search search-field"
			aria-label="<?php esc_attr_e('Enter search keywords', 'techalgospotlight'); ?>"
			placeholder="<?php esc_attr_e('Search', 'techalgospotlight'); ?>" value="<?php echo get_search_query(); ?>"
			name="s" />
		<?php echo $techalgospotlight_post_type; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<?php if (!isset($args['icon'])): ?>
			<button role="button" type="submit" class="search-submit"
				aria-label="<?php esc_attr_e('Search', 'techalgospotlight'); ?>">
				<?php echo techalgospotlight()->icons->get_svg('search', array('aria-hidden' => 'true')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		<?php else:
			techalgospotlight_animated_arrow('right', 'submit', true);
			?>
			<button type="button" class="techalgospotlight-search-close" aria-hidden="true" role="button">
				<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
					<path
						d="M6.852 7.649L.399 1.195 1.445.149l6.454 6.453L14.352.149l1.047 1.046-6.454 6.454 6.454 6.453-1.047 1.047-6.453-6.454-6.454 6.454-1.046-1.047z"
						fill="currentColor" fill-rule="evenodd"></path>
				</svg>
			</button>
		<?php endif; ?>
	</div>
</form>