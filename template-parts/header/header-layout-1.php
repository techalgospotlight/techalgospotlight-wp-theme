<?php
/**
 * The template for displaying header layout 1.
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>

<div class="techalgospotlight-container techalgospotlight-header-container">

	<?php
	techalgospotlight_header_logo_template();
	?>

	<span class="techalgospotlight-header-element techalgospotlight-mobile-nav">
		<?php techalgospotlight_hamburger(techalgospotlight_option('main_nav_mobile_label'), 'techalgospotlight-primary-nav'); ?>
	</span>

	<?php
	techalgospotlight_main_navigation_template();
	do_action('techalgospotlight_header_widget_location', array('left', 'right'));
	?>

</div><!-- END .techalgospotlight-container -->