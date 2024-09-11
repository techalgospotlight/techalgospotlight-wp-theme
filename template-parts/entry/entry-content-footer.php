<?php
/**
 * Template part for displaying entry tags.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

$techalgospotlight_entry_elements = techalgospotlight_option('single_post_elements');
$techalgospotlight_entry_footer_tags = isset($techalgospotlight_entry_elements['tags']) && $techalgospotlight_entry_elements['tags'] && has_tag();
$techalgospotlight_entry_footer_date = isset($techalgospotlight_entry_elements['last-updated']) && $techalgospotlight_entry_elements['last-updated'] && get_the_time('U') !== get_the_modified_time('U');

$techalgospotlight_entry_footer_tags = apply_filters('techalgospotlight_display_entry_footer_tags', $techalgospotlight_entry_footer_tags);
$techalgospotlight_entry_footer_date = apply_filters('techalgospotlight_display_entry_footer_date', $techalgospotlight_entry_footer_date);

// Nothing is enabled, don't display the div.
if (!$techalgospotlight_entry_footer_tags && !$techalgospotlight_entry_footer_date) {
	return;
}
?>

<?php do_action('techalgospotlight_before_entry_footer'); ?>

<div class="entry-footer">

	<?php
	// Post Tags.
	if ($techalgospotlight_entry_footer_tags) {
		techalgospotlight_entry_meta_tag(
			'<div class="post-tags"><span class="cat-links">',
			'',
			'</span></div>',
			0,
			false
		);
	}

	// Last Updated Date.
	if ($techalgospotlight_entry_footer_date) {

		$techalgospotlight_before = '<span class="last-updated techalgospotlight-iflex-center">';

		if (true === techalgospotlight_option('single_entry_meta_icons')) {
			$techalgospotlight_before .= techalgospotlight()->icons->get_svg('edit-3');
		}

		techalgospotlight_entry_meta_date(
			array(
				'show_published' => false,
				'show_modified' => true,
				'before' => $techalgospotlight_before,
				'after' => '</span>',
			)
		);
	}
	?>

</div>

<?php do_action('techalgospotlight_after_entry_footer'); ?>