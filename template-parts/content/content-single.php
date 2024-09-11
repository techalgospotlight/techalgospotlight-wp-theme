<?php
/**
 * Template for Single post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>

<?php do_action('techalgospotlight_before_article'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('techalgospotlight-article'); ?><?php techalgospotlight_schema_markup('article'); ?>>

	<?php
	if ('quote' === get_post_format()) {
		get_template_part('template-parts/entry/format/media', 'quote');
	}

	$techalgospotlight_single_post_elements = techalgospotlight_get_single_post_elements();

	if (!empty($techalgospotlight_single_post_elements)) {
		foreach ($techalgospotlight_single_post_elements as $techalgospotlight_element) {

			if ('content' === $techalgospotlight_element) {
				do_action('techalgospotlight_before_single_content', 'before_post_content');
				get_template_part('template-parts/entry/entry', $techalgospotlight_element);
				do_action('techalgospotlight_after_single_content', 'after_post_content');
			} else {
				get_template_part('template-parts/entry/entry', $techalgospotlight_element);
			}
		}
	}
	?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('techalgospotlight_after_article'); ?>