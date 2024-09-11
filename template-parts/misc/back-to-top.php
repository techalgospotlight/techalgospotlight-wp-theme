<?php
/**
 * The template for displaying scroll to top button.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>

<a href="#" id="techalgospotlight-scroll-top" class="techalgospotlight-smooth-scroll"
	title="<?php esc_attr_e('Scroll to Top', 'techalgospotlight'); ?>" <?php techalgospotlight_scroll_top_classes(); ?>>
	<span class="techalgospotlight-scroll-icon" aria-hidden="true">
		<?php echo techalgospotlight()->icons->get_svg('arrow-up', array('class' => 'top-icon')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo techalgospotlight()->icons->get_svg('arrow-up'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</span>
	<span class="screen-reader-text"><?php esc_html_e('Scroll to Top', 'techalgospotlight'); ?></span>
</a><!-- END #techalgospotlight-scroll-to-top -->