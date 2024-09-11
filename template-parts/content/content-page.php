<?php
/**
 * Template part for displaying page layout in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?><?php techalgospotlight_schema_markup('article'); ?>>

	<?php
	if (techalgospotlight_show_post_thumbnail()) {
		get_template_part('template-parts/entry/format/media', 'page');
	}
	?>

	<div class="entry-content techalgospotlight-entry">
		<?php
		do_action('techalgospotlight_before_page_content');

		the_content();

		do_action('techalgospotlight_after_page_content');
		?>
	</div><!-- END .entry-content -->

	<?php techalgospotlight_link_pages(); ?>

</article><!-- #post-<?php the_ID(); ?> -->