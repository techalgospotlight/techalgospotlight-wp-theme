<?php
/**
 * Template part for displaying content of techalgospotlight Canvas [Fullwidth] page template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?><?php techalgospotlight_schema_markup('article'); ?>>
	<div class="entry-content techalgospotlight-entry techalgospotlight-fullwidth-entry">
		<?php
		do_action('techalgospotlight_before_page_content');

		the_content();

		do_action('techalgospotlight_after_page_content');
		?>
	</div><!-- END .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->