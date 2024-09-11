<?php
/**
 * The template for displaying theme copyright bar.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>

<?php do_action('techalgospotlight_before_copyright'); ?>
<div id="techalgospotlight-copyright" <?php techalgospotlight_copyright_classes(); ?>>
	<div class="techalgospotlight-container">
		<div class="techalgospotlight-flex-row">

			<div class="col-xs-12 center-xs col-md flex-basis-auto start-md">
				<?php do_action('techalgospotlight_copyright_widgets', 'start'); ?>
			</div>
			<div class="col-xs-12 center-xs col-md flex-basis-auto end-md">
				<?php do_action('techalgospotlight_copyright_widgets', 'end'); ?>
			</div>

		</div><!-- END .techalgospotlight-flex-row -->
	</div>
</div><!-- END #techalgospotlight-copyright -->
<?php do_action('techalgospotlight_after_copyright'); ?>