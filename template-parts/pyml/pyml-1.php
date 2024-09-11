<?php
/**
 * The template for displaying PYML Slider.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */


// Setup PYML posts.
$techalgospotlight_pyml_orderby = techalgospotlight_option('pyml_orderby');
$techalgospotlight_pyml_order = explode('-', $techalgospotlight_pyml_orderby);

$techalgospotlight_args = array(
	'post_type' => 'post',
	'post_status' => 'publish',
	'posts_per_page' => techalgospotlight_option('pyml_post_number'), // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
	'order' => $techalgospotlight_pyml_order[1],
	'orderby' => $techalgospotlight_pyml_order[0],
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

$techalgospotlight_pyml_categories = techalgospotlight_option('pyml_category');

if (!empty($techalgospotlight_pyml_categories)) {
	$techalgospotlight_args['category_name'] = implode(', ', $techalgospotlight_pyml_categories);
}

$techalgospotlight_args = apply_filters('techalgospotlight_pyml_query_args', $techalgospotlight_args);

$techalgospotlight_posts = new WP_Query($techalgospotlight_args);

// No posts found.
if (!$techalgospotlight_posts->have_posts()) {
	return;
}

// $techalgospotlight_pyml_bgs_html   = '';
$techalgospotlight_pyml_items_html = '';

$techalgospotlight_pyml_elements = (array) techalgospotlight_option('pyml_elements');

$techalgospotlight_posts_per_page = 'col-md-' . ceil(esc_attr(12 / $techalgospotlight_args['posts_per_page'])) . ' col-sm-6 col-xs-12';

while ($techalgospotlight_posts->have_posts()):
	$techalgospotlight_posts->the_post();

	// Post items HTML markup.
	ob_start();
	?>
	<div class="<?php echo esc_attr($techalgospotlight_posts_per_page); ?>">
		<div class="techalgospotlight-post-item style-1 end rounded">
			<div class="techalgospotlight-post-thumb">
				<a href="<?php echo esc_url(techalgospotlight_entry_get_permalink()); ?>" tabindex="0"></a>
				<div class="inner"><?php the_post_thumbnail(get_the_ID(), 'full'); ?></div>
			</div><!-- END .techalgospotlight-post-thumb -->
			<div class="techalgospotlight-post-content">

				<?php if (isset($techalgospotlight_pyml_elements['category']) && $techalgospotlight_pyml_elements['category']) { ?>
					<div class="post-category">
						<?php techalgospotlight_entry_meta_category(' ', false, apply_filters('techalgospotlight_pyml_category_limit', 3)); ?>
					</div>
				<?php } ?>

				<?php get_template_part('template-parts/entry/entry-header'); ?>

				<?php if (isset($techalgospotlight_pyml_elements['meta']) && $techalgospotlight_pyml_elements['meta']) { ?>
					<div class="entry-meta">
						<div class="entry-meta-elements">
							<?php
							techalgospotlight_entry_meta_author();

							techalgospotlight_entry_meta_date(
								array(
									'show_modified' => false,
									'published_label' => '',
								)
							);
							?>
						</div>
					</div><!-- END .entry-meta -->
				<?php } ?>

			</div><!-- END .techalgospotlight-post-content -->
		</div><!-- END .techalgospotlight-post-item -->
	</div>
	<?php
	$techalgospotlight_pyml_items_html .= ob_get_clean();
endwhile;

// Restore original Post Data.
wp_reset_postdata();

// Container.
$techalgospotlight_pyml_container = techalgospotlight_option('pyml_container');
$techalgospotlight_pyml_container = 'full-width' === $techalgospotlight_pyml_container ? 'techalgospotlight-container techalgospotlight-container__wide' : 'techalgospotlight-container';

// Title.
$techalgospotlight_pyml_title = techalgospotlight_option('pyml_title');

// Classes.
$techalgospotlight_classes = '';
$techalgospotlight_classes .= techalgospotlight_option('pyml_card_border') ? ' techalgospotlight-card__boxed' : '';
$techalgospotlight_classes .= techalgospotlight_option('pyml_card_shadow') ? ' techalgospotlight-card-shadow' : '';

?>

<div class="techalgospotlight-pyml slider-overlay-1 <?php echo esc_attr($techalgospotlight_classes); ?>">
	<div class="techalgospotlight-pyml-container <?php echo esc_attr($techalgospotlight_pyml_container); ?>">
		<div class="techalgospotlight-flex-row">
			<div class="col-xs-12">
				<div class="techalgospotlight-card-items">
					<div class="h4 widget-title">
						<?php if ($techalgospotlight_pyml_title): ?>
							<span><?php echo esc_html($techalgospotlight_pyml_title); ?></span>
						<?php endif; ?>
					</div>
					<div class="techalgospotlight-flex-row gy-4">
						<?php echo wp_kses_post($techalgospotlight_pyml_items_html); ?>
					</div>
				</div>
			</div>
		</div><!-- END .techalgospotlight-card-items -->
	</div>
</div><!-- END .techalgospotlight-pyml -->