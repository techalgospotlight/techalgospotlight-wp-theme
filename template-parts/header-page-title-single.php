<?php
/**
 * Template part for displaying page header for single post.
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>

<div <?php techalgospotlight_page_header_classes(); ?><?php techalgospotlight_page_header_atts(); ?>>

	<?php do_action('techalgospotlight_page_header_start'); ?>

	<?php if ('in-page-header' === techalgospotlight_option('single_title_position')) { ?>

		<div class="techalgospotlight-container">
			<div class="techalgospotlight-page-header-wrapper">

				<?php
				if (techalgospotlight_single_post_displays('category')) {
					get_template_part('template-parts/entry/entry', 'category');
				}

				if (techalgospotlight_page_header_has_title()) {
					echo '<div class="techalgospotlight-page-header-title">';
					techalgospotlight_page_header_title();
					echo '</div>';
				}

				if (techalgospotlight_has_entry_meta_elements()) {
					get_template_part('template-parts/entry/entry', 'meta');
				}
				?>

			</div>
		</div>

	<?php } ?>

	<?php do_action('techalgospotlight_page_header_end'); ?>

</div>