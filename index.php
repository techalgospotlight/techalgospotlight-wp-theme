<?php
/**
 * The main template file.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

get_header();

do_action('techalgospotlight_before_container');

?>

<div class="techalgospotlight-container">

	<?php
	do_action('techalgospotlight_before_content_area', 'before_post_archive');

	// Primary content area
	?>
	<div id="primary" class="content-area">
		<?php do_action('techalgospotlight_before_content'); ?>
		<main id="content" class="site-content" role="main" <?php techalgospotlight_schema_markup('main'); ?>>
			<?php do_action('techalgospotlight_content'); ?>
		</main><!-- #content .site-content -->
		<?php do_action('techalgospotlight_after_content'); ?>
	</div><!-- #primary .content-area -->

	<?php
	// Sidebar
	do_action('techalgospotlight_sidebar');

	// After content area
	do_action('techalgospotlight_after_content_area');
	?>
</div><!-- END .techalgospotlight-container -->

<?php
do_action('techalgospotlight_after_container');
get_footer();