<?php
/**
 * The template for displaying Hero Horizontal Slider.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */


// Setup Hero posts.
$techalgospotlight_hero_slider_orderby = techalgospotlight_option('hero_slider_orderby');
$techalgospotlight_hero_slider_order = explode('-', $techalgospotlight_hero_slider_orderby);

$techalgospotlight_args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => techalgospotlight_option('hero_slider_post_number'), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
	'order' => $techalgospotlight_hero_slider_order[1],
	'orderby' => $techalgospotlight_hero_slider_order[0],
	'ignore_sticky_posts' => true,
	'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'post_format',
			'field' => 'slug',
			'terms' => array('post-format-quote'),
			'operator' => 'NOT IN',
		),
	),
);

$techalgospotlight_hero_categories = techalgospotlight_option('hero_slider_category');

if (!empty($techalgospotlight_hero_categories)) {
	$techalgospotlight_args['category_name'] = implode(', ', $techalgospotlight_hero_categories);
}

$techalgospotlight_args = apply_filters('techalgospotlight_hero_slider_query_args', $techalgospotlight_args);

$techalgospotlight_posts = new WP_Query($techalgospotlight_args);

// No posts found.
if (!$techalgospotlight_posts->have_posts()) {
	return;
}

$techalgospotlight_hero_items_html = '';

$techalgospotlight_hero_elements = (array) techalgospotlight_option('hero_slider_elements');
$techalgospotlight_hero_readmore = isset($techalgospotlight_hero_elements['read_more']) && $techalgospotlight_hero_elements['read_more'] ? ' techalgospotlight-hero-readmore' : '';
$techalgospotlight_hero_read_more_text = techalgospotlight_option('hero_slider_read_more');

while ($techalgospotlight_posts->have_posts()):
	$techalgospotlight_posts->the_post();

	// Post items HTML markup.
	ob_start();

	?>
	<div class="swiper-slide">
		<article id="post-<?php the_ID(); ?>" <?php post_class('techalgospotlight-article'); ?><?php techalgospotlight_schema_markup('article'); ?>>
			<div class="techalgospotlight-blog-entry-wrapper techalgospotlight-thumb-hero techalgospotlight-thumb-left">
				<div class="post-thumb entry-media thumbnail">
					<a href="<?php echo esc_url(techalgospotlight_entry_get_permalink()); ?>" class="entry-image-link">
						<?php the_post_thumbnail(get_the_ID(), 'full'); ?>
					</a>
				</div>
				<div class="techalgospotlight-entry-content-wrapper">

					<?php if (isset($techalgospotlight_hero_elements['category']) && $techalgospotlight_hero_elements['category']) { ?>
						<div class="post-category">
							<?php techalgospotlight_entry_meta_category(' ', false, apply_filters('techalgospotlight_hero_horizontal_category_limit', 3)); ?>
						</div>
					<?php } ?>

					<?php if (get_the_title()) { ?>
						<header class="entry-header">
							<h4 class="entry-title"><a
									href="<?php echo esc_url(techalgospotlight_entry_get_permalink()); ?>"><?php the_title(); ?></a></h4>
						</header>
					<?php } ?>

					<?php get_template_part('template-parts/entry/entry-summary'); ?>

					<?php if ($techalgospotlight_hero_readmore) { ?>
						<footer class="entry-footer">
							<a href="<?php echo esc_url(techalgospotlight_entry_get_permalink()); ?>"
								class="techalgospotlight-btn btn-text-1"
								role="button"><span><?php echo esc_html($techalgospotlight_hero_read_more_text); ?></span></a>
						</footer>
					<?php } ?>

					<?php if (isset($techalgospotlight_hero_elements['meta']) && $techalgospotlight_hero_elements['meta']) { ?>
						<?php
						get_template_part('template-parts/entry/entry', 'meta', array('techalgospotlight_meta_callback' => 'techalgospotlight_get_hero_entry_meta_elements'));
						?>
						<!-- END .entry-meta -->
					<?php } ?>

				</div><!-- END .slide-inner -->
		</article><!-- END article -->
	</div>
	<?php
	$techalgospotlight_hero_items_html .= ob_get_clean();
endwhile;

// Restore original Post Data.
wp_reset_postdata();

// Hero container. {"delay": 8000, "disableOnInteraction": false}

?>
<div class="techalgospotlight-hero-slider techalgospotlight-blog-horizontal">
	<div class="techalgospotlight-horizontal-slider">

		<div class="techalgospotlight-hero-container techalgospotlight-container">
			<div class="techalgospotlight-flex-row">
				<div class="col-xs-12">
					<div class="techalgospotlight-swiper swiper" data-swiper-options='{
						"spaceBetween": 24,
						"slidesPerView": 1,
						"breakpoints": {
							"0": {
								"spaceBetween": 16
							},
							"768": {
								"spaceBetween": 16
							},
							"1200": {
								"spaceBetween": 24
							}
						},
						"loop": true,
						"autoHeight": true,
						"autoplay": {"delay": 12000, "disableOnInteraction": false},
						"speed": 1000,
						"navigation": {"nextEl": ".hero-next", "prevEl": ".hero-prev"}
					}'>
						<div class="swiper-wrapper">
							<?php echo wp_kses($techalgospotlight_hero_items_html, techalgospotlight_get_allowed_html_tags()); ?>
						</div>
						<div class="swiper-button-next hero-next"></div>
						<div class="swiper-button-prev hero-prev"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="techalgospotlight-spinner visible">
			<div></div>
			<div></div>
		</div>
	</div>
</div><!-- END .techalgospotlight-hero-slider -->