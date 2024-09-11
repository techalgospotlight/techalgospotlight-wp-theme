<?php
/**
 * The template for displaying all pages, single posts, and attachments.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

get_header();

$class_no_media = has_post_thumbnail() ? '' : 'no-entry-media';

do_action('techalgospotlight_before_singular_container');
do_action('techalgospotlight_before_container');

?>

<div class="techalgospotlight-container">

	<?php do_action('techalgospotlight_before_content_area', 'before_post_archive'); ?>

	<div id="primary" class="content-area">

		<?php do_action('techalgospotlight_before_content'); ?>

		<main id="content" class="site-content <?php echo esc_attr($class_no_media); ?>" role="main" <?php techalgospotlight_schema_markup('main'); ?>>

			<?php
			do_action('techalgospotlight_before_singular');
			do_action('techalgospotlight_content_singular');
			do_action('techalgospotlight_after_singular');
			?>

		</main><!-- #content .site-content -->

		<?php do_action('techalgospotlight_after_content'); ?>

	</div><!-- #primary .content-area -->

	<?php
	do_action('techalgospotlight_sidebar');
	do_action('techalgospotlight_after_content_area');
	?>

</div><!-- END .techalgospotlight-container -->

<?php
do_action('techalgospotlight_after_container');
do_action('techalgospotlight_after_singular_container');
get_footer();