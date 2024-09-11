<?php
/**
 * The template for displaying Featured Links.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */



$techalgospotlight_featured_links_title_type = techalgospotlight_option('featured_links_title_type');
$techalgospotlight_featured_links_items_html = '';


$techalgospotlight_featured_column = 'col-md-4 col-sm-6 col-xs-12';
foreach ($args['features'] as $key => $feature):

	// Post items HTML markup.
	ob_start();

	?>

	<div id="techalgospotlight-featured-item-<?php echo esc_attr($key); ?>"
		class="<?php echo esc_attr($techalgospotlight_featured_column); ?>">
		<div class="techalgospotlight-post-item style-1 center">
			<div class="techalgospotlight-post-thumb">
				<div class="inner bloghsah-featured-item-image">
					<?php
					if (!empty($feature['image']['id'])):
						echo wp_get_attachment_image($feature['image']['id'], 'large');
					endif;
					?>
				</div>
			</div><!-- END .techalgospotlight-post-thumb-->
			<div class="techalgospotlight-post-content">

				<?php
				if (!empty($feature['link'])):
					if ('1' == $techalgospotlight_featured_links_title_type):
						printf('<a href="%1$s" class="techalgospotlight-btn btn-small btn-white" title="%2$s" target="%3$s">%4$s</a>', esc_url_raw($feature['link']['url']), esc_attr($feature['link']['title']), esc_attr($feature['link']['target']), esc_html($feature['link']['title']));
						?>
						<?php
					endif;
				endif;
				?>
			</div><!-- END .techalgospotlight-post-content -->
		</div><!-- END .techalgospotlight-post-item -->
	</div>
	<?php
	$techalgospotlight_featured_links_items_html .= ob_get_clean();
endforeach;

// Restore original Post Data.
wp_reset_postdata();

// Title.
$techalgospotlight_featured_links_title = techalgospotlight_option('featured_links_title');

// Classes.
$techalgospotlight_classes = '';
$techalgospotlight_classes .= techalgospotlight_option('featured_links_card_border') ? ' techalgospotlight-card__boxed' : '';
$techalgospotlight_classes .= techalgospotlight_option('featured_links_card_shadow') ? ' techalgospotlight-card-shadow' : '';

?>

<div
	class="techalgospotlight-featured featured-one slider-overlay-1 <?php echo esc_attr($techalgospotlight_classes); ?>">
	<div class="techalgospotlight-featured-container techalgospotlight-container">
		<div class="techalgospotlight-flex-row g-0">
			<div class="col-xs-12">
				<div class="techalgospotlight-card-items">
					<?php if ($techalgospotlight_featured_links_title): ?>
						<div class="h4 widget-title">
							<span><?php echo esc_html($techalgospotlight_featured_links_title); ?></span>
						</div>
					<?php endif; ?>
					<div class="techalgospotlight-flex-row gy-4">
						<?php echo wp_kses_post($techalgospotlight_featured_links_items_html); ?>
					</div>
				</div>
			</div>
		</div><!-- END .techalgospotlight-card-items -->
	</div>
</div><!-- END .techalgospotlight-featured -->