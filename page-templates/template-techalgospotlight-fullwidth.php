<?php
/**
 * Template Name: techalgospotlight Fullwidth
 *
 * 100% wide page template without vertical spacing.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

get_header();
do_action('techalgospotlight_before_singular_container');
if (have_posts()):
	while (have_posts()):
		the_post();

		get_template_part('template-parts/content/content', 'techalgospotlight-fullwidth');
	endwhile;
endif;
do_action('techalgospotlight_after_singular_container');
get_footer();
