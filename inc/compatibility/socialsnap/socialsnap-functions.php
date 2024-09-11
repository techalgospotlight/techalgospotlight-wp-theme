<?php
/**
 * techalgospotlight Social Snap compatibility functions.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

if (!function_exists('techalgospotlight_entry_meta_shares')):
	/**
	 * Add share count information to entry meta.
	 *
	 * @since 1.0.0
	 */
	function techalgospotlight_entry_meta_shares()
	{

		$share_count = socialsnap_get_total_share_count();

		// Icon.
		$icon = techalgospotlight()->icons->get_meta_icon('share', techalgospotlight()->icons->get_svg('share-2', array('aria-hidden' => 'true')));

		$output = sprintf(
			'<span class="share-count">%3$s%1$s %2$s</span>',
			socialsnap_format_number($share_count),
			esc_html(_n('Share', 'Shares', $share_count, 'techalgospotlight')),
			$icon
		);

		echo wp_kses(apply_filters('techalgospotlight_entry_share_count', $output), techalgospotlight_get_allowed_html_tags());
	}
endif;
