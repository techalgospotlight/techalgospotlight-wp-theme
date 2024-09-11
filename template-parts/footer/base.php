<?php
/**
 * The template for displaying theme footer.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>

<?php do_action('techalgospotlight_before_footer'); ?>
<div id="techalgospotlight-footer" <?php techalgospotlight_footer_classes(); ?>>
	<div class="techalgospotlight-container">
		<div class="techalgospotlight-flex-row" id="techalgospotlight-footer-widgets">

			<?php techalgospotlight_footer_widgets(); ?>

		</div><!-- END .techalgospotlight-flex-row -->
	</div><!-- END .techalgospotlight-container -->
</div><!-- END #techalgospotlight-footer -->
<?php do_action('techalgospotlight_after_footer'); ?>