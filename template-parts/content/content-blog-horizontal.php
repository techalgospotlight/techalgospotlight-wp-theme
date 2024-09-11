<?php
/**
 * Template part for displaying blog post - horizontal.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */
$class_no_media = !has_post_thumbnail() ? 'no-entry-media' : '';
?>

<?php do_action('techalgospotlight_before_article'); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(array('techalgospotlight-article', esc_attr($class_no_media))); ?><?php techalgospotlight_schema_markup('article'); ?>>

	<?php
	$techalgospotlight_blog_entry_format = get_post_format();

	if ('quote' === $techalgospotlight_blog_entry_format) {
		get_template_part('template-parts/entry/format/media', $techalgospotlight_blog_entry_format);
	} else {

		$techalgospotlight_classes = array();
		$techalgospotlight_classes[] = 'techalgospotlight-blog-entry-wrapper';
		$techalgospotlight_thumb_align = techalgospotlight_option('blog_image_position');
		$techalgospotlight_thumb_align = apply_filters('techalgospotlight_horizontal_blog_image_position', $techalgospotlight_thumb_align);
		$techalgospotlight_classes[] = 'techalgospotlight-thumb-' . $techalgospotlight_thumb_align;
		$techalgospotlight_classes = implode(' ', $techalgospotlight_classes);
		?>

		<div class="<?php echo esc_attr($techalgospotlight_classes); ?>">
			<?php get_template_part('template-parts/entry/entry-thumbnail'); ?>

			<div class="techalgospotlight-entry-content-wrapper">

				<?php
				if (techalgospotlight_option('blog_horizontal_post_categories')) {
					get_template_part('template-parts/entry/entry-category');
				}

				get_template_part('template-parts/entry/entry-header');
				get_template_part('template-parts/entry/entry-summary');


				if (techalgospotlight_option('blog_horizontal_read_more')) {
					get_template_part('template-parts/entry/entry-summary-footer');
				}

				get_template_part('template-parts/entry/entry-meta');
				?>
			</div>
		</div>

	<?php } ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php do_action('techalgospotlight_after_article'); ?>