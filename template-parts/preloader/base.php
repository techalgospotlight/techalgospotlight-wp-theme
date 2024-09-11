<?php
/**
 * The template for displaying page preloader.
 *
 * @see https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>

<div id="techalgospotlight-preloader" <?php techalgospotlight_preloader_classes(); ?>>
	<?php get_template_part('template-parts/preloader/preloader', techalgospotlight_option('preloader_style')); ?>
</div><!-- END #techalgospotlight-preloader -->