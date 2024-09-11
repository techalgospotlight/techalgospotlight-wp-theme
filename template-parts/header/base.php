<?php
/**
 * The base template for displaying theme header area.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>
<?php do_action('techalgospotlight_before_header'); ?>
<div id="techalgospotlight-header" <?php techalgospotlight_header_classes(); ?>>
	<?php do_action('techalgospotlight_header_content'); ?>
</div><!-- END #techalgospotlight-header -->
<?php do_action('techalgospotlight_after_header'); ?>