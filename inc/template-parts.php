<?php

/**
 * Template parts.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Adds the meta tag to the site header.
 *
 * @since 1.0.0
 */
function techalgospotlight_meta_viewport()
{
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}
add_action('wp_head', 'techalgospotlight_meta_viewport', 1);

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 *
 * @since 1.0.0
 */
function techalgospotlight_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'techalgospotlight_pingback_header');

/**
 * Adds the meta tag for website accent color.
 *
 * @since 1.0.0
 */
function techalgospotlight_meta_theme_color()
{

	$color = techalgospotlight_option('accent_color');

	if ($color) {
		printf('<meta name="theme-color" content="%s">', esc_attr($color));
	}
}
add_action('wp_head', 'techalgospotlight_meta_theme_color');

/**
 * Outputs the theme top bar area.
 *
 * @since 1.0.0
 */
function techalgospotlight_topbar_output()
{

	if (!techalgospotlight_is_top_bar_displayed()) {
		return;
	}

	get_template_part('template-parts/topbar/topbar');
}
add_action('techalgospotlight_header', 'techalgospotlight_topbar_output', 10);

/**
 * Outputs the top bar widgets.
 *
 * @since 1.0.0
 * @param string $location Widget location in top bar.
 */
function techalgospotlight_topbar_widgets_output($location)
{

	do_action('techalgospotlight_top_bar_widgets_before_' . $location);

	$techalgospotlight_top_bar_widgets = techalgospotlight_option('top_bar_widgets');

	if (is_array($techalgospotlight_top_bar_widgets) && !empty($techalgospotlight_top_bar_widgets)) {
		foreach ($techalgospotlight_top_bar_widgets as $widget) {

			if (!isset($widget['values'])) {
				continue;
			}

			if ($location !== $widget['values']['location']) {
				continue;
			}

			if (function_exists('techalgospotlight_top_bar_widget_' . $widget['type'])) {

				$classes = array();
				$classes[] = 'techalgospotlight-topbar-widget__' . esc_attr($widget['type']);
				$classes[] = 'techalgospotlight-topbar-widget';

				if (isset($widget['values']['visibility']) && $widget['values']['visibility']) {
					$classes[] = 'techalgospotlight-' . esc_attr($widget['values']['visibility']);
				}

				$classes = apply_filters('techalgospotlight_topbar_widget_classes', $classes, $widget);
				$classes = trim(implode(' ', $classes));

				printf('<div class="%s">', esc_attr($classes));
				call_user_func('techalgospotlight_top_bar_widget_' . $widget['type'], $widget['values']);
				printf('</div><!-- END .techalgospotlight-topbar-widget -->');
			}
		}
	}

	do_action('techalgospotlight_top_bar_widgets_after_' . $location);
}
add_action('techalgospotlight_topbar_widgets', 'techalgospotlight_topbar_widgets_output');

/**
 * Outputs the theme header area.
 *
 * @since 1.0.0
 */
function techalgospotlight_header_output()
{

	if (!techalgospotlight_is_header_displayed()) {
		return;
	}

	get_template_part('template-parts/header/base');
}
add_action('techalgospotlight_header', 'techalgospotlight_header_output', 20);

/**
 * Outputs the header widgets in Header Widget Locations.
 *
 * @since 1.0.0
 * @param string $locations Widget location.
 */
function techalgospotlight_header_widgets($locations)
{

	$locations = (array) $locations;
	$all_widgets = (array) techalgospotlight_option('header_widgets');

	techalgospotlight_header_widget_output($locations, $all_widgets);
}
add_action('techalgospotlight_header_widget_location', 'techalgospotlight_header_widgets', 1);

/**
 * Outputs the header widgets in Header Navigation Widget Locations.
 *
 * @since 1.0.0
 * @param string $locations Widget location.
 */
function techalgospotlight_header_navigation_widgets($locations)
{

	$locations = (array) $locations;
	$all_widgets = (array) techalgospotlight_option('header_navigation_widgets');

	techalgospotlight_header_widget_output($locations, $all_widgets);
}
add_action('techalgospotlight_header_navigation_widget_location', 'techalgospotlight_header_navigation_widgets', 1);

/**
 * Outputs the content of theme header.
 *
 * @since 1.0.0
 */
function techalgospotlight_header_content_output()
{

	// Get the selected header layout from Customizer.
	$header_layout = techalgospotlight_option('header_layout');

	?>
	<div id="techalgospotlight-header-inner">
		<?php

		// Load header layout template.
		get_template_part('template-parts/header/header', $header_layout);

		?>
	</div><!-- END #techalgospotlight-header-inner -->
	<?php
}
add_action('techalgospotlight_header_content', 'techalgospotlight_header_content_output');

/**
 * Outputs the main footer area.
 *
 * @since 1.0.0
 */
function techalgospotlight_footer_output()
{

	if (!techalgospotlight_is_footer_displayed()) {
		return;
	}

	get_template_part('template-parts/footer/base');
}
add_action('techalgospotlight_footer', 'techalgospotlight_footer_output', 20);

/**
 * Outputs the copyright area.
 *
 * @since 1.0.0
 */
function techalgospotlight_copyright_bar_output()
{

	if (!techalgospotlight_is_copyright_bar_displayed()) {
		return;
	}

	get_template_part('template-parts/footer/copyright/copyright');
}
add_action('techalgospotlight_footer', 'techalgospotlight_copyright_bar_output', 30);

/**
 * Outputs the copyright widgets.
 *
 * @since 1.0.0
 * @param string $location Widget location in copyright.
 */
function techalgospotlight_copyright_widgets_output($location)
{

	do_action('techalgospotlight_copyright_widgets_before_' . $location);

	$techalgospotlight_widgets = techalgospotlight_option('copyright_widgets');

	if (is_array($techalgospotlight_widgets) && !empty($techalgospotlight_widgets)) {
		foreach ($techalgospotlight_widgets as $widget) {

			if (!isset($widget['values'])) {
				continue;
			}

			if (isset($widget['values'], $widget['values']['location']) && $location !== $widget['values']['location']) {
				continue;
			}

			if (function_exists('techalgospotlight_copyright_widget_' . $widget['type'])) {

				$classes = array();
				$classes[] = 'techalgospotlight-copyright-widget__' . esc_attr($widget['type']);
				$classes[] = 'techalgospotlight-copyright-widget';

				if (isset($widget['values']['visibility']) && $widget['values']['visibility']) {
					$classes[] = 'techalgospotlight-' . esc_attr($widget['values']['visibility']);
				}

				$classes = apply_filters('techalgospotlight_copyright_widget_classes', $classes, $widget);
				$classes = trim(implode(' ', $classes));

				printf('<div class="%s">', esc_attr($classes));
				call_user_func('techalgospotlight_copyright_widget_' . $widget['type'], $widget['values']);
				printf('</div><!-- END .techalgospotlight-copyright-widget -->');
			}
		}
	}

	do_action('techalgospotlight_copyright_widgets_after_' . $location);
}
add_action('techalgospotlight_copyright_widgets', 'techalgospotlight_copyright_widgets_output');

/**
 * Outputs the theme sidebar area.
 *
 * @since 1.0.0
 */
function techalgospotlight_sidebar_output()
{

	if (techalgospotlight_is_sidebar_displayed()) {
		get_sidebar();
	}
}
add_action('techalgospotlight_sidebar', 'techalgospotlight_sidebar_output');

/**
 * Outputs the back to top button.
 *
 * @since 1.0.0
 */
function techalgospotlight_back_to_top_output()
{

	if (!techalgospotlight_option('scroll_top')) {
		return;
	}

	get_template_part('template-parts/misc/back-to-top');
}
add_action('techalgospotlight_after_page_wrapper', 'techalgospotlight_back_to_top_output');

/**
 * Outputs the cursor dot.
 *
 * @since 1.0.0
 */
function techalgospotlight_cursor_dot_output()
{

	if (!techalgospotlight_option('enable_cursor_dot')) {
		return;
	}

	get_template_part('template-parts/misc/cursor-dot');
}
add_action('techalgospotlight_after_page_wrapper', 'techalgospotlight_cursor_dot_output');

/**
 * Outputs the theme page content.
 *
 * @since 1.0.0
 */
function techalgospotlight_page_header_template()
{

	do_action('techalgospotlight_before_page_header');

	if (techalgospotlight_is_page_header_displayed()) {
		if (is_singular('post')) {
			get_template_part('template-parts/header-page-title-single');
		} else {
			get_template_part('template-parts/header-page-title');
		}
	}

	do_action('techalgospotlight_after_page_header');
}
add_action('techalgospotlight_page_header', 'techalgospotlight_page_header_template');



/**
 * Outputs the theme blog hero content.
 *
 * @since 1.0.0
 */
function techalgospotlight_blog_hero()
{

	if (!techalgospotlight_is_hero_displayed()) {
		return;
	}

	// Hero type.
	$hero_type = techalgospotlight_option('hero_type');

	do_action('techalgospotlight_before_hero');

	// Enqueue techalgospotlight Slider script.
	wp_enqueue_script('techalgospotlight-slider');

	?>
	<div id="hero">
		<?php
		get_template_part('template-parts/hero/hero', $hero_type);
		?>
	</div><!-- END #hero -->
	<?php

	do_action('techalgospotlight_after_hero');
}
add_action('techalgospotlight_after_masthead', 'techalgospotlight_blog_hero', 30);


/**
 * Outputs the theme Blog Featured Links content.
 *
 * @since 1.0.0
 */
function techalgospotlight_blog_featured_links()
{

	if (!techalgospotlight_is_featured_links_displayed()) {
		return;
	}

	// Featured links type.
	$techalgospotlight_featured_links_type = techalgospotlight_option('featured_links_type');

	$techalgospotlight_featured_links = techalgospotlight_option('featured_links');

	// No items found.
	if (!$techalgospotlight_featured_links) {
		return;
	}

	$features = array();

	foreach ($techalgospotlight_featured_links as $techalgospotlight_featured_link) {
		$features[] = array(
			'link' => $techalgospotlight_featured_link['link'],
			'image' => $techalgospotlight_featured_link['image'],
		);
	}

	do_action('techalgospotlight_before_featured_links');

	?>
	<div id="featured_links">
		<?php get_template_part('template-parts/featured-links/featured-links', $techalgospotlight_featured_links_type, array('features' => $features)); ?>
	</div><!-- END #featured_links -->
	<?php

	do_action('techalgospotlight_after_featured_links');
}
add_action('techalgospotlight_after_masthead', 'techalgospotlight_blog_featured_links', 31);


/**
 * Outputs the theme Blog PYML content.
 *
 * @since 1.0.0
 */
function techalgospotlight_blog_pyml()
{

	if (!techalgospotlight_is_pyml_displayed()) {
		return;
	}

	$pyml_type = techalgospotlight_option('pyml_type');

	do_action('techalgospotlight_before_pyml');

	?>
	<div id="pyml">
		<?php get_template_part('template-parts/pyml/pyml', $pyml_type); ?>
	</div><!-- END #pyml -->
	<?php

	do_action('techalgospotlight_after_pyml');
}
add_action('techalgospotlight_after_container', 'techalgospotlight_blog_pyml', 32);


/**
 * Outputs the theme Body Animation.
 *
 * @since 1.0.0
 */
function techalgospotlight_body_animation()
{

	$body_animation_option = techalgospotlight_option('body_animation');

	if ('0' === $body_animation_option) {
		return;
	}

	do_action('techalgospotlight_before_body_animation');
	?>
	<?php if ('1' === $body_animation_option): ?>
		<div class="techalgospotlight-glassmorphism">
			<span class="block one"></span>
			<span class="block two"></span>
		</div>
		<?php
	endif;
	do_action('techalgospotlight_after_body_animation');
}
add_action('techalgospotlight_main_end', 'techalgospotlight_body_animation', 33);

function techalgospotlight_blog_heading_content()
{

	if ($blog_heading = techalgospotlight_option('blog_heading')) {
		echo '<div id="techalgospotlight-blog-heading">';
		echo wp_kses($blog_heading, techalgospotlight_get_allowed_html_tags());
		echo '</div>';
	}
}
add_action('techalgospotlight_blog_heading', 'techalgospotlight_blog_heading_content');

/**
 * Outputs the queried articles.
 *
 * @since 1.0.0
 */
function techalgospotlight_content()
{
	global $wp_query;
	$techalgospotlight_blog_layout = techalgospotlight_option('blog_masonry') ? 'masonries' : '';
	$techalgospotlight_blog_layout_column = 12;

	if (techalgospotlight_option('blog_layout') != 'blog-horizontal'):
		$techalgospotlight_blog_layout_column = techalgospotlight_option('blog_layout_column');
	endif;

	if (have_posts()):

		if (is_home()) {
			do_action('techalgospotlight_blog_heading');
		}
		echo '<div class="techalgospotlight-flex-row g-4 ' . $techalgospotlight_blog_layout . '">';

		$ads_info = techalgospotlight_algorithm_to_push_ads_in_archive();
		$count = 0;
		while (have_posts()):
			the_post();

			if (is_array($ads_info) && !is_null($ads_info['ads_to_render'])):
				if (in_array($wp_query->current_post, $ads_info['random_numbers'])):
					echo '<div class="col-md-' . $techalgospotlight_blog_layout_column . ' col-sm-' . $techalgospotlight_blog_layout_column . ' col-xs-12">';
					techalgospotlight_random_post_archive_advertisement_part(is_array($ads_info['ads_to_render']) ? $ads_info['ads_to_render'][$count] : $ads_info['ads_to_render']);
					echo '</div>';
					$count++;
				endif;
			endif;

			echo '<div class="col-md-' . $techalgospotlight_blog_layout_column . ' col-sm-' . $techalgospotlight_blog_layout_column . ' col-xs-12">';
			get_template_part('template-parts/content/content', techalgospotlight_get_article_feed_layout());
			echo '</div>';
		endwhile;
		echo '</div>';
		techalgospotlight_pagination();

	else:
		get_template_part('template-parts/content/content', 'none');
	endif;
}
add_action('techalgospotlight_content', 'techalgospotlight_content');
add_action('techalgospotlight_content_archive', 'techalgospotlight_content');
add_action('techalgospotlight_content_search', 'techalgospotlight_content');

/**
 * Outputs the theme single content.
 *
 * @since 1.0.0
 */
function techalgospotlight_content_singular()
{

	if (have_posts()):
		while (have_posts()):
			the_post();

			if (is_singular('post')) {
				do_action('techalgospotlight_content_single');
			} else {
				do_action('techalgospotlight_content_page');
			}

		endwhile;
	else:
		get_template_part('template-parts/content/content', 'none');
	endif;
}
add_action('techalgospotlight_content_singular', 'techalgospotlight_content_singular');


/**
 * Outputs the theme 404 page content.
 *
 * @since 1.0.0
 */
function techalgospotlight_404_page_content()
{

	get_template_part('template-parts/content/content', '404');
}
add_action('techalgospotlight_content_404', 'techalgospotlight_404_page_content');

/**
 * Outputs the theme page content.
 *
 * @since 1.0.0
 */
function techalgospotlight_content_page()
{

	get_template_part('template-parts/content/content', 'page');
}
add_action('techalgospotlight_content_page', 'techalgospotlight_content_page');

/**
 * Outputs the theme single post content.
 *
 * @since 1.0.0
 */
function techalgospotlight_content_single()
{

	get_template_part('template-parts/content/content', 'single');
}
add_action('techalgospotlight_content_single', 'techalgospotlight_content_single');

/**
 * Outputs the comments template.
 *
 * @since 1.0.0
 */
function techalgospotlight_output_related_posts()
{

	if ('post' == get_post_type()) {
		get_template_part('template-parts/related-posts/related', 'posts');
	}
}
add_action('techalgospotlight_after_singular', 'techalgospotlight_output_related_posts');

/**
 * Outputs the comments template.
 *
 * @since 1.0.0
 */
function techalgospotlight_output_comments()
{
	comments_template();
}
add_action('techalgospotlight_after_singular', 'techalgospotlight_output_comments');

/**
 * Outputs the theme archive page info.
 *
 * @since 1.0.0
 */
function techalgospotlight_archive_info()
{

	// Author info.
	if (is_author()) {
		get_template_part('template-parts/entry/entry', 'about-author');
	}
}
add_action('techalgospotlight_before_content', 'techalgospotlight_archive_info');

/**
 * Outputs more posts button to author description box.
 *
 * @since 1.0.0
 */
function techalgospotlight_add_author_posts_button()
{
	if (!is_author()) {
		get_template_part('template-parts/entry/entry', 'author-posts-button');
	}
}
add_action('techalgospotlight_entry_after_author_description', 'techalgospotlight_add_author_posts_button');

/**
 * Outputs Comments Toggle button.
 *
 * @since 1.0.0
 */
function techalgospotlight_comments_toggle()
{

	if (techalgospotlight_comments_toggle_displayed()) {
		get_template_part('template-parts/entry/entry-show-comments');
	}
}
add_action('techalgospotlight_before_comments', 'techalgospotlight_comments_toggle');

/**
 * Outputs Page Preloader.
 *
 * @since 1.0.0
 */
function techalgospotlight_preloader()
{

	if (!techalgospotlight_is_preloader_displayed()) {
		return;
	}

	get_template_part('template-parts/preloader/base');
}
add_action('techalgospotlight_before_page_wrapper', 'techalgospotlight_preloader');

/**
 * Outputs breadcrumbs after header.
 *
 * @since  1.0.0
 * @return void
 */
function techalgospotlight_breadcrumb_after_header_output()
{

	if ('below-header' === techalgospotlight_option('breadcrumbs_position') && techalgospotlight_has_breadcrumbs()) {

		$alignment = 'techalgospotlight-text-align-' . techalgospotlight_option('breadcrumbs_alignment');

		$args = array(
			'container_before' => '<div class="techalgospotlight-breadcrumbs"><div class="techalgospotlight-container ' . $alignment . '">',
			'container_after' => '</div></div>',
		);

		techalgospotlight_breadcrumb($args);
	}
}
add_action('techalgospotlight_main_start', 'techalgospotlight_breadcrumb_after_header_output');

/**
 * Outputs breadcumbs in page header.
 *
 * @since  1.0.0
 * @return void
 */
function techalgospotlight_breadcrumb_page_header_output()
{

	if (techalgospotlight_page_header_has_breadcrumbs()) {

		if (is_singular('post')) {
			$args = array(
				'container_before' => '<div class="techalgospotlight-container techalgospotlight-breadcrumbs">',
				'container_after' => '</div>',
			);
		} else {
			$args = array(
				'container_before' => '<div class="techalgospotlight-breadcrumbs">',
				'container_after' => '</div>',
			);
		}

		techalgospotlight_breadcrumb($args);
	}
}
add_action('techalgospotlight_page_header_end', 'techalgospotlight_breadcrumb_page_header_output');

/**
 * Output the main navigation template.
 */
function techalgospotlight_main_navigation_template()
{
	get_template_part('template-parts/header/navigation');
}

/**
 * Output the Header logo template.
 */
function techalgospotlight_header_logo_template()
{
	get_template_part('template-parts/header/logo');
}

function techalgospotlight_about_button()
{
	$button_widgets = techalgospotlight_option('about_widgets');

	if (empty($button_widgets)) {
		return;
	}
	foreach ($button_widgets as $widget) {
		call_user_func('techalgospotlight_about_widget_' . $widget['type'], $widget['values']);
	}
}

function techalgospotlight_cta_widgets()
{
	$widgets = techalgospotlight_option('cta_widgets');

	if (empty($widgets)) {
		return;
	}
	foreach ($widgets as $widget) {
		call_user_func('techalgospotlight_cta_widget_' . $widget['type'], $widget['values']);
	}
}
