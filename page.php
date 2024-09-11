<?php
/**
 * The main template file.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

get_header();

do_action('techalgospotlight_before_singular_container');

if ('' !== get_the_content()) {
	do_action('techalgospotlight_before_container');
	?>
	<div class="techalgospotlight-container">
		<?php do_action('techalgospotlight_before_content_area', 'before_post_archive'); ?>
		<div id="primary" class="content-area">
			<?php do_action('techalgospotlight_before_content'); ?>
			<main id="content" class="site-content" role="main" <?php techalgospotlight_schema_markup('main'); ?>>
				<?php
				do_action('techalgospotlight_before_singular');
				do_action('techalgospotlight_content_singular');
				do_action('techalgospotlight_after_singular');
				?>
			</main>
			<?php do_action('techalgospotlight_after_content'); ?>
		</div>
		<?php do_action('techalgospotlight_sidebar'); ?>
		<?php do_action('techalgospotlight_after_content_area'); ?>
	</div>
	<?php
	do_action('techalgospotlight_after_container');
}

do_action('techalgospotlight_after_singular_container');
get_footer();