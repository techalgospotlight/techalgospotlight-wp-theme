<?php
/**
 * The template for displaying Related posts on post details page.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */


// Setup Related posts.

if (!techalgospotlight_option('related_posts_enable')) {
	return;
}
$numbre_of_posts = techalgospotlight_option('related_post_number');
$numbre_of_posts = $numbre_of_posts ? $numbre_of_posts : 3;
$techalgospotlight_args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => $numbre_of_posts, // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
	'orderby' => 'date',
	'ignore_sticky_posts' => true,
	'category__in' => wp_get_post_categories(get_the_ID()),
	'post__not_in' => array(get_the_ID()),
	'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'post_format',
			'field' => 'slug',
			'terms' => array('post-format-quote'),
			'operator' => 'NOT IN',
		),
	),
);

$techalgospotlight_args = apply_filters('techalgospotlight_related_posts_query_args', $techalgospotlight_args);

$techalgospotlight_posts = new WP_Query($techalgospotlight_args);

// No posts found.
if (!$techalgospotlight_posts->have_posts()) {
	return;
}

$techalgospotlight_related_posts_items_html = '';
$col = techalgospotlight_option('related_posts_column');
while ($techalgospotlight_posts->have_posts()):
	$techalgospotlight_posts->the_post();

	// Post items HTML markup.
	ob_start();
	?>

	<div class="col-md-<?php echo esc_attr($col); ?> col-sm-6 col-xs-12">
		<div class="techalgospotlight-post-item style-1 end rounded">
			<div class="techalgospotlight-post-thumb">
				<a href="<?php echo esc_url(techalgospotlight_entry_get_permalink()); ?>" tabindex="0"></a>
				<div class="inner"><?php the_post_thumbnail(get_the_ID(), 'full'); ?></div>
			</div><!-- END .techalgospotlight-post-thumb -->
			<div class="techalgospotlight-post-content">

				<div class="post-category">
					<?php techalgospotlight_entry_meta_category(' ', false, apply_filters('techalgospotlight_pyml_category_limit', 3)); ?>
				</div>

				<?php get_template_part('template-parts/entry/entry-header'); ?>

				<div class="entry-meta">
					<div class="entry-meta-elements">
						<?php
						techalgospotlight_entry_meta_author();
						?>
					</div>
				</div><!-- END .entry-meta -->

			</div><!-- END .techalgospotlight-post-content -->
		</div><!-- END .techalgospotlight-post-item -->
	</div>
	<?php
	$techalgospotlight_related_posts_items_html .= ob_get_clean();
endwhile;

// Restore original Post Data.
wp_reset_postdata();

// Title.
$techalgospotlight_related_posts_title = techalgospotlight_option('related_posts_heading');

?>
<div id="related_posts" class="mt-5">
	<div class="techalgospotlight-rp slider-overlay-1 <?php echo esc_attr($techalgospotlight_classes); ?>">
		<div class="techalgospotlight-rp-container">
			<div class="techalgospotlight-flex-row">
				<div class="col-xs-12">
					<div class="techalgospotlight-card-items">
						<div class="h4 widget-title">
							<?php if ($techalgospotlight_related_posts_title): ?>
								<?php echo esc_html($techalgospotlight_related_posts_title); ?>
							<?php endif; ?>
						</div>
						<div class="techalgospotlight-flex-row gy-4">
							<?php echo $techalgospotlight_related_posts_items_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</div>
			</div><!-- END .techalgospotlight-card-items -->
		</div>
	</div><!-- END .techalgospotlight-rp -->
</div><!-- END #related_posts -->