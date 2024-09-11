<?php
/**
 * The template for displaying theme header search widget.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

$techalgospotlight_header_widgets = techalgospotlight_option('header_widgets');
$style_for_search = '';
foreach ($techalgospotlight_header_widgets as $widget) {
	// Check if the widget type is 'search'
	if ($widget['type'] === 'search') {
		// Access the 'style' from the 'values' array
		$style_for_search = $widget['values']['style'] ?? 'rounded-fill';
		break; // Stop the loop if the search widget is found
	}
}

?>

<div aria-haspopup="true">
	<a href="#" class="techalgospotlight-search <?php echo esc_attr($style_for_search); ?>">
		<?php echo techalgospotlight()->icons->get_svg('search', array('aria-label' => esc_html__('Search', 'techalgospotlight'))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a><!-- END .techalgospotlight-search -->

	<div class="techalgospotlight-search-simple techalgospotlight-search-container dropdown-item">
		<?php
		get_search_form(
			array(
				'aria_label' => __('Search for:', 'techalgospotlight'),
				'icon' => 'arrow'
			)
		);
		?>
	</div><!-- END .techalgospotlight-search-simple -->
</div>