<?php
/**
 * Template part for displaying page header.
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>

<div <?php techalgospotlight_page_header_classes(); ?><?php techalgospotlight_page_header_atts(); ?>>
	<div class="techalgospotlight-container">

		<?php do_action('techalgospotlight_page_header_start'); ?>

		<?php if (techalgospotlight_page_header_has_title()) { ?>

			<div class="techalgospotlight-page-header-wrapper">

				<div class="techalgospotlight-page-header-title">
					<?php techalgospotlight_page_header_title(); ?>
				</div>

				<?php $techalgospotlight_description = apply_filters('techalgospotlight_page_header_description', techalgospotlight_get_the_description()); ?>

				<?php if ($techalgospotlight_description) { ?>

					<div class="techalgospotlight-page-header-description">
						<?php echo wp_kses($techalgospotlight_description, techalgospotlight_get_allowed_html_tags()); ?>
					</div>

				<?php } ?>
			</div>

		<?php } ?>

		<?php do_action('techalgospotlight_page_header_end'); ?>

	</div>
</div>