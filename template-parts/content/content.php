<?php
/**
 * Template part for displaying post in post listing.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>

<?php do_action('techalgospotlight_before_article'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('techalgospotlight-article'); ?><?php techalgospotlight_schema_markup('article'); ?>>

	<?php
	$techalgospotlight_blog_entry_format = get_post_format();

	if ('quote' === $techalgospotlight_blog_entry_format) {
		get_template_part('template-parts/entry/format/media', $techalgospotlight_blog_entry_format);
	} else {

		$techalgospotlight_blog_entry_elements = techalgospotlight_get_blog_entry_elements();

		if (!empty($techalgospotlight_blog_entry_elements)) {
			foreach ($techalgospotlight_blog_entry_elements as $techalgospotlight_element) {
				get_template_part('template-parts/entry/entry', $techalgospotlight_element);
			}
		}
	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('techalgospotlight_after_article'); ?>