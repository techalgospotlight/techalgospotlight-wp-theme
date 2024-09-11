<?php
/**
 * The template for displaying theme top bar.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>

<?php do_action('techalgospotlight_before_topbar'); ?>
<div id="techalgospotlight-topbar" <?php techalgospotlight_top_bar_classes(); ?>>
	<div class="techalgospotlight-container">
		<div class="techalgospotlight-flex-row">
			<div class="col-md flex-basis-auto start-sm"><?php do_action('techalgospotlight_topbar_widgets', 'left'); ?>
			</div>
			<div class="col-md flex-basis-auto end-sm"><?php do_action('techalgospotlight_topbar_widgets', 'right'); ?>
			</div>
		</div>
	</div>
</div><!-- END #techalgospotlight-topbar -->
<?php do_action('techalgospotlight_after_topbar'); ?>