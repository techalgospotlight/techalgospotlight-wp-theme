<?php

/**
 * techalgospotlight Options Class.
 *
 * @package  techalgospotlight
 * @author   TechAlgoSpotlight Themes
 * @since    1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('techalgospotlight_Options')):

	/**
	 * techalgospotlight Options Class.
	 */
	class techalgospotlight_Options
	{

		/**
		 * Singleton instance of the class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance;

		/**
		 * Options variable.
		 *
		 * @since 1.0.0
		 * @var mixed $options
		 */
		private static $options;

		/**
		 * Main techalgospotlight_Options Instance.
		 *
		 * @since 1.0.0
		 * @return techalgospotlight_Options
		 */
		public static function instance()
		{

			if (!isset(self::$instance) && !(self::$instance instanceof techalgospotlight_Options)) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			// Refresh options.
			add_action('after_setup_theme', array($this, 'refresh'));
		}

		/**
		 * Set default option values.
		 *
		 * @since  1.0.0
		 * @return array Default values.
		 */
		public function get_defaults()
		{

			$categories = get_categories(array('hide_empty' => 1));
			$techalgospotlight_categories_color_options = array();
			foreach ($categories as $category) {
				$techalgospotlight_categories_color_options['techalgospotlight_category_color_' . $category->term_id] = '#525ceb';
			}

			$defaults = array(

				/**
				 * General Settings.
				 */

				// Layout.
				'techalgospotlight_site_layout' => 'fw-contained',
				'techalgospotlight_container_width' => 1480,

				// Base Colors.
				'techalgospotlight_accent_color' => '#525ceb',
				'techalgospotlight_dark_mode' => false,
				'techalgospotlight_body_animation' => '1',
				'techalgospotlight_content_text_color' => '#002050',
				'techalgospotlight_headings_color' => '#302D55',
				'techalgospotlight_content_link_hover_color' => '#302D55',
				'techalgospotlight_body_background_heading' => true,
				'techalgospotlight_content_background_heading' => true,
				'techalgospotlight_boxed_content_background_color' => '#FFFFFF',
				'techalgospotlight_scroll_top_visibility' => 'all',

				// Base Typography.
				'techalgospotlight_html_base_font_size' => array(
					'desktop' => 62.5,
					'tablet' => 53,
					'mobile' => 50,
				),
				'techalgospotlight_font_smoothing' => true,
				'techalgospotlight_typography_body_heading' => false,
				'techalgospotlight_typography_headings_heading' => false,
				'techalgospotlight_body_font' => techalgospotlight_typography_defaults(
					array(
						'font-family' => 'Be Vietnam Pro',
						'font-weight' => 400,
						'font-size-desktop' => '1.7',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.75',
					)
				),
				'techalgospotlight_headings_font' => techalgospotlight_typography_defaults(
					array(
						'font-family' => 'Be Vietnam Pro',
						'font-weight' => 700,
						'font-style' => 'normal',
						'text-transform' => 'none',
						'text-decoration' => 'none',
					)
				),
				'techalgospotlight_h1_font' => techalgospotlight_typography_defaults(
					array(
						'font-weight' => 700,
						'font-size-desktop' => '4',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.4',
					)
				),
				'techalgospotlight_h2_font' => techalgospotlight_typography_defaults(
					array(
						'font-weight' => 700,
						'font-size-desktop' => '3.6',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.4',
					)
				),
				'techalgospotlight_h3_font' => techalgospotlight_typography_defaults(
					array(
						'font-weight' => 700,
						'font-size-desktop' => '2.8',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.4',
					)
				),
				'techalgospotlight_h4_font' => techalgospotlight_typography_defaults(
					array(
						'font-weight' => 700,
						'font-size-desktop' => '2.4',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.4',
					)
				),
				'techalgospotlight_h5_font' => techalgospotlight_typography_defaults(
					array(
						'font-weight' => 700,
						'font-size-desktop' => '2',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.4',
					)
				),
				'techalgospotlight_h6_font' => techalgospotlight_typography_defaults(
					array(
						'font-weight' => 600,
						'font-size-desktop' => '1.8',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.72',
					)
				),
				'techalgospotlight_heading_em_font' => techalgospotlight_typography_defaults(
					array(
						'font-family' => 'Playfair Display',
						'font-weight' => 'inherit',
						'font-style' => 'italic',
					)
				),
				'techalgospotlight_section_heading_style' => '1',
				'techalgospotlight_footer_widget_title_font_size' => array(
					'desktop' => 2,
					'unit' => 'rem',
				),

				// Primary Button.
				'techalgospotlight_primary_button_heading' => false,
				'techalgospotlight_primary_button_bg_color' => '',
				'techalgospotlight_primary_button_hover_bg_color' => '',
				'techalgospotlight_primary_button_text_color' => '#fff',
				'techalgospotlight_primary_button_hover_text_color' => '#fff',
				'techalgospotlight_primary_button_border_radius' => array(
					'top-left' => '0.8',
					'top-right' => '0.8',
					'bottom-right' => '0.8',
					'bottom-left' => '0.8',
					'unit' => 'rem',
				),
				'techalgospotlight_primary_button_border_width' => 0.1,
				'techalgospotlight_primary_button_border_color' => 'rgba(0, 0, 0, 0.12)',
				'techalgospotlight_primary_button_hover_border_color' => 'rgba(0, 0, 0, 0.12)',
				'techalgospotlight_primary_button_typography' => techalgospotlight_typography_defaults(
					array(
						'font-family' => 'Be Vietnam Pro',
						'font-weight' => 500,
						'font-size-desktop' => '1.8',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '',
					)
				),

				// Secondary Button.
				'techalgospotlight_secondary_button_heading' => false,
				'techalgospotlight_secondary_button_bg_color' => '#302D55',
				'techalgospotlight_secondary_button_hover_bg_color' => '#002050',
				'techalgospotlight_secondary_button_text_color' => '#FFFFFF',
				'techalgospotlight_secondary_button_hover_text_color' => '#FFFFFF',
				'techalgospotlight_secondary_button_border_radius' => array(
					'top-left' => '',
					'top-right' => '',
					'bottom-right' => '',
					'bottom-left' => '',
					'unit' => 'rem',
				),
				'techalgospotlight_secondary_button_border_width' => .1,
				'techalgospotlight_secondary_button_border_color' => 'rgba(0, 0, 0, 0.12)',
				'techalgospotlight_secondary_button_hover_border_color' => 'rgba(0, 0, 0, 0.12)',
				'techalgospotlight_secondary_button_typography' => techalgospotlight_typography_defaults(
					array(
						'font-family' => 'Be Vietnam Pro',
						'font-weight' => 500,
						'font-size-desktop' => '1.8',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.6',
					)
				),

				// Text button.
				'techalgospotlight_text_button_heading' => false,
				'techalgospotlight_text_button_text_color' => '#302D55',
				'techalgospotlight_text_button_hover_text_color' => '',
				'techalgospotlight_text_button_typography' => techalgospotlight_typography_defaults(
					array(
						'font-family' => 'Be Vietnam Pro',
						'font-weight' => 500,
						'font-size-desktop' => '1.6',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.5',
					)
				),

				// Misc Settings.
				'techalgospotlight_enable_schema' => true,
				'techalgospotlight_custom_input_style' => true,
				'techalgospotlight_preloader_heading' => false,
				'techalgospotlight_preloader' => false,
				'techalgospotlight_preloader_style' => '1',
				'techalgospotlight_preloader_visibility' => 'all',
				'techalgospotlight_scroll_top_heading' => false,
				'techalgospotlight_scroll_top' => true,
				'techalgospotlight_scroll_top_visibility' => 'all',
				'techalgospotlight_cursor_dot_heading' => false,
				'techalgospotlight_cursor_dot' => false,

				/**
				 * Logos & Site Title.
				 */
				'techalgospotlight_logo_default_retina' => '',
				'techalgospotlight_logo_max_height' => array(
					'desktop' => 45,
				),
				'techalgospotlight_logo_margin' => array(
					'desktop' => array(
						'top' => 27,
						'right' => 10,
						'bottom' => 27,
						'left' => 10,
					),
					'tablet' => array(
						'top' => 25,
						'right' => 1,
						'bottom' => 25,
						'left' => 0,
					),
					'mobile' => array(
						'top' => '',
						'right' => '',
						'bottom' => '',
						'left' => '',
					),
					'unit' => 'px',
				),
				'techalgospotlight_display_tagline' => false,
				'techalgospotlight_logo_heading_site_identity' => true,
				'techalgospotlight_typography_logo_heading' => false,
				'techalgospotlight_logo_text_font_size' => array(
					'desktop' => 3,
					'unit' => 'rem',
				),

				/**
				 * Header.
				 */

				// Top Bar.
				'techalgospotlight_top_bar_enable' => false,
				'techalgospotlight_top_bar_container_width' => 'content-width',
				'techalgospotlight_top_bar_visibility' => 'all',
				'techalgospotlight_top_bar_heading_widgets' => true,
				'techalgospotlight_top_bar_widgets' => array(
					array(
						'classname' => 'techalgospotlight_customizer_widget_text',
						'type' => 'text',
						'values' => array(
							'content' => wp_kses('<i class="far fa-calendar-alt fa-lg techalgospotlight-icon"></i><strong><span id="techalgospotlight-date"></span> - <span id="techalgospotlight-time"></span></strong>', techalgospotlight_get_allowed_html_tags()),
							'location' => 'left',
							'visibility' => 'all',
						),
					),
					array(
						'classname' => 'techalgospotlight_customizer_widget_text',
						'type' => 'text',
						'values' => array(
							'content' => wp_kses('<i class="far fa-location-arrow fa-lg techalgospotlight-icon"></i> Subscribe to our techalgospotlightter & never miss our best posts. <a href="#"><strong>Subscribe Now!</strong></a>', techalgospotlight_get_allowed_html_tags()),
							'location' => 'right',
							'visibility' => 'all',
						),
					),
				),
				'techalgospotlight_top_bar_widgets_separator' => 'regular',
				'techalgospotlight_top_bar_heading_design_options' => false,
				'techalgospotlight_top_bar_background' => techalgospotlight_design_options_defaults(
					array(
						'background' => array(
							'color' => array(
								'background-color' => 'rgba(247,229,183,0.35)',
							),
							'gradient' => array(
								'gradient-color-1' => 'rgba(247,229,183,0.35)',
								'gradient-color-2' => 'rgba(226,181,181,0.39)',
							),
						),
					)
				),
				'techalgospotlight_top_bar_text_color' => techalgospotlight_design_options_defaults(
					array(
						'color' => array(
							'text-color' => '#002050',
							'link-color' => '#302D55',
							'link-hover-color' => '#F43676',
						),
					)
				),
				'techalgospotlight_top_bar_border' => techalgospotlight_design_options_defaults(
					array(
						'border' => array(
							'border-top-width' => '',
							'border-style' => 'solid',
							'border-color' => '',
							'separator-color' => '#cccccc',
						),
					)
				),

				// Main Header.
				'techalgospotlight_header_layout' => 'layout-1',

				'techalgospotlight_header_container_width' => 'content-width',
				'techalgospotlight_header_heading_widgets' => true,
				'techalgospotlight_header_widgets' => array(
					array(
						'classname' => 'techalgospotlight_customizer_widget_socials',
						'type' => 'socials',
						'values' => array(
							'style' => 'rounded-border',
							'size' => 'standard',
							'location' => 'left',
							'visibility' => 'hide-mobile-tablet',
						),
					),
					array(
						'classname' => 'techalgospotlight_customizer_widget_darkmode',
						'type' => 'darkmode',
						'values' => array(
							'style' => 'rounded-border',
							'location' => 'right',
							'visibility' => 'hide-mobile-tablet',
						),
					),
					array(
						'classname' => 'techalgospotlight_customizer_widget_search',
						'type' => 'search',
						'values' => array(
							'style' => 'rounded-fill',
							'location' => 'right',
							'visibility' => 'hide-mobile-tablet',
						),
					),
					array(
						'classname' => 'techalgospotlight_customizer_widget_button',
						'type' => 'button',
						'values' => array(
							'text' => '<i class="far fa-bell mr-1 techalgospotlight-icon"></i> Subscribe',
							'url' => '#',
							'class' => 'btn-small',
							'target' => '_self',
							'location' => 'right',
							'visibility' => 'hide-mobile-tablet',
						),
					),
				),

				// Ad Widget
				'techalgospotlight_ad_widgets' => array(
					array(
						'classname' => 'techalgospotlight_customizer_widget_advertisements',
						'type' => 'advertisements',
					),
				),

				'techalgospotlight_header_widgets_separator' => 'none',
				'techalgospotlight_header_heading_design_options' => false,
				'techalgospotlight_header_background' => techalgospotlight_design_options_defaults(
					array(
						'background' => array(
							'color' => array(
								'background-color' => '#FFFFFF',
							),
							'gradient' => array(),
							'image' => array(),
						),
					)
				),
				'techalgospotlight_header_border' => techalgospotlight_design_options_defaults(
					array(
						'border' => array(
							'border-bottom-width' => 1,
							'border-color' => 'rgba(185, 185, 185, 0.4)',
							'separator-color' => '#cccccc',
						),
					)
				),
				'techalgospotlight_header_text_color' => techalgospotlight_design_options_defaults(
					array(
						'color' => array(
							'text-color' => '#66717f',
							'link-color' => '#131315',
						),
					)
				),

				// Header navigation widgets
				'techalgospotlight_header_navigation_heading_widgets' => true,
				'techalgospotlight_header_navigation_widgets' => array(),

				// Transparent Header.
				'techalgospotlight_tsp_header' => false,
				'techalgospotlight_tsp_header_disable_on' => array(
					'404',
					'posts_page',
					'archive',
					'search',
				),

				// Sticky Header.
				'techalgospotlight_sticky_header' => false,
				'techalgospotlight_sticky_header_hide_on' => array(''),

				// Main Navigation.
				'techalgospotlight_main_nav_heading_animation' => false,
				'techalgospotlight_main_nav_hover_animation' => 'underline',
				'techalgospotlight_main_nav_heading_sub_menus' => true,
				'techalgospotlight_main_nav_sub_indicators' => true,
				'techalgospotlight_main_nav_heading_mobile_menu' => false,
				'techalgospotlight_main_nav_mobile_breakpoint' => 960,
				'techalgospotlight_main_nav_mobile_label' => '',
				'techalgospotlight_nav_design_options' => false,
				'techalgospotlight_main_nav_background' => techalgospotlight_design_options_defaults(
					array(
						'background' => array(
							'color' => array(
								'background-color' => '#FFFFFF',
							),
							'gradient' => array(),
						),
					)
				),
				'techalgospotlight_main_nav_border' => techalgospotlight_design_options_defaults(
					array(
						'border' => array(
							'border-top-width' => 1,
							'border-bottom-width' => 0,
							'border-style' => 'solid',
							'border-color' => 'rgba(185, 185, 185, 0.4)',
						),
					)
				),
				'techalgospotlight_main_nav_font_color' => techalgospotlight_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'techalgospotlight_typography_main_nav_heading' => false,
				'techalgospotlight_main_nav_font' => techalgospotlight_typography_defaults(
					array(
						'font-family' => 'Inter Tight',
						'font-weight' => 600,
						'font-size-desktop' => '1.7',
						'font-size-unit' => 'rem',
						'line-height-desktop' => '1.5',
					)
				),

				// Page Header.
				'techalgospotlight_page_header_enable' => true,
				'techalgospotlight_page_header_alignment' => 'right',
				'techalgospotlight_page_header_spacing' => array(
					'desktop' => array(
						'top' => 30,
						'bottom' => 30,
					),
					'tablet' => array(
						'top' => '',
						'bottom' => '',
					),
					'mobile' => array(
						'top' => '',
						'bottom' => '',
					),
					'unit' => 'px',
				),
				'techalgospotlight_page_header_background' => techalgospotlight_design_options_defaults(
					array(
						'background' => array(
							'color' => array('background-color' => '#fff9f3'),
							'gradient' => array(),
							'image' => array(),
						),
					)
				),
				'techalgospotlight_page_header_text_color' => techalgospotlight_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'techalgospotlight_page_header_border' => techalgospotlight_design_options_defaults(
					array(
						'border' => array(
							'border-bottom-width' => 1,
							'border-style' => 'solid',
							'border-color' => '#fff9f3',
						),
					)
				),
				'techalgospotlight_typography_page_header' => false,
				'techalgospotlight_page_header_font_size' => array(
					'desktop' => 2.6,
					'unit' => 'rem',
				),

				// Breadcrumbs.
				'techalgospotlight_breadcrumbs_enable' => true,
				'techalgospotlight_breadcrumbs_hide_on' => array('home'),
				'techalgospotlight_breadcrumbs_position' => 'in-page-header',
				'techalgospotlight_breadcrumbs_alignment' => 'left',
				'techalgospotlight_breadcrumbs_spacing' => array(
					'desktop' => array(
						'top' => 15,
						'bottom' => 15,
					),
					'tablet' => array(
						'top' => '',
						'bottom' => '',
					),
					'mobile' => array(
						'top' => '',
						'bottom' => '',
					),
					'unit' => 'px',
				),
				'techalgospotlight_breadcrumbs_heading_design' => false,
				'techalgospotlight_breadcrumbs_background' => techalgospotlight_design_options_defaults(
					array(
						'background' => array(
							'color' => array(),
							'gradient' => array(),
							'image' => array(),
						),
					)
				),
				'techalgospotlight_breadcrumbs_text_color' => techalgospotlight_design_options_defaults(
					array(
						'color' => array(),
					)
				),
				'techalgospotlight_breadcrumbs_border' => techalgospotlight_design_options_defaults(
					array(
						'border' => array(
							'border-top-width' => 0,
							'border-bottom-width' => 0,
							'border-color' => '',
							'border-style' => 'solid',
						),
					)
				),

				/**
				 * Hero.
				 */
				'techalgospotlight_enable_hero' => true,
				'techalgospotlight_hero_type' => 'horizontal-slider',
				'techalgospotlight_hero_slider_align' => 'center',
				'techalgospotlight_hero_enable_on' => array('home'),
				'techalgospotlight_hero_slider' => false,
				'techalgospotlight_hero_slider_orderby' => 'date-desc',
				'techalgospotlight_hero_slider_title_font_size' => array(
					'desktop' => 2.4,
					'unit' => 'rem',
				),
				'techalgospotlight_hero_slider_elements' => array(
					'category' => true,
					'meta' => true,
					'read_more' => true,
				),
				'techalgospotlight_hero_entry_meta_elements' => array(
					'author' => true,
					'date' => true,
					'comments' => false,
				),
				'techalgospotlight_hero_slider_posts' => false,
				'techalgospotlight_hero_slider_post_number' => 6,
				'techalgospotlight_hero_slider_category' => array(),
				'techalgospotlight_hero_slider_read_more' => esc_html__('Continue Reading', 'techalgospotlight'),

				/**
				 * Featured Links
				 */
				'techalgospotlight_enable_featured_links' => false,
				'techalgospotlight_featured_links_title' => esc_html__('Today Best Trending Topics', 'techalgospotlight'),
				'techalgospotlight_featured_links_enable_on' => array('home'),
				'techalgospotlight_featured_links_style' => false,
				'techalgospotlight_featured_links_type' => 'one',
				'techalgospotlight_featured_links_title_type' => '1',
				'techalgospotlight_featured_links_card_border' => true,
				'techalgospotlight_featured_links_card_shadow' => true,
				'techalgospotlight_featured_links' => apply_filters(
					'techalgospotlight_featured_links_default',
					array(
						array(
							'link' => '',
							'image' => array(),
						),
						array(
							'link' => '',
							'image' => array(),
						),
						array(
							'link' => '',
							'image' => array(),
						),
					),
				),

				/**
				 * PYML
				 */
				'techalgospotlight_enable_pyml' => true,
				'techalgospotlight_pyml_title' => esc_html__('You May Have Missed', 'techalgospotlight'),
				'techalgospotlight_pyml_enable_on' => array('home'),
				'techalgospotlight_pyml_style' => false,
				'techalgospotlight_pyml_type' => '1',
				'techalgospotlight_pyml_orderby' => 'date-desc',
				'techalgospotlight_pyml_card_border' => true,
				'techalgospotlight_pyml_card_shadow' => true,
				'techalgospotlight_pyml_elements' => array(
					'category' => true,
					'meta' => true,
				),
				'techalgospotlight_pyml_posts' => true,
				'techalgospotlight_pyml_post_number' => 4,
				'techalgospotlight_pyml_post_title_font_size' => array(
					'desktop' => 2,
					'unit' => 'rem',
				),
				'techalgospotlight_pyml_category' => array(),

				/**
				 * Blog.
				 */

				// Blog Page / Archive.
				'techalgospotlight_blog_entry_elements' => array(
					'thumbnail' => true,
					'header' => true,
					'meta' => true,
					'summary' => true,
					'summary-footer' => true,
				),
				'techalgospotlight_blog_entry_meta_elements' => array(
					'author' => true,
					'date' => true,
					'category' => false,
					'tag' => false,
					'comments' => false,
				),
				'techalgospotlight_related_posts' => false,
				'techalgospotlight_related_posts_enable' => false,
				'techalgospotlight_related_posts_heading' => esc_html__('Related posts', 'techalgospotlight'),
				'techalgospotlight_related_post_number' => 3,
				'techalgospotlight_related_posts_column' => 4,
				'techalgospotlight_entry_meta_icons' => true,
				'techalgospotlight_excerpt_length' => 30,
				'techalgospotlight_excerpt_more' => '&hellip;',
				'techalgospotlight_blog_layout' => 'blog-horizontal',
				'techalgospotlight_blog_image_wrap' => true,
				'techalgospotlight_blog_zig_zag' => false,
				'techalgospotlight_blog_masonry' => false,
				'techalgospotlight_blog_layout_column' => 6,
				'techalgospotlight_blog_image_position' => 'left',
				'techalgospotlight_blog_image_size' => 'large',
				'techalgospotlight_blog_card_border' => true,
				'techalgospotlight_blog_card_shadow' => true,
				'techalgospotlight_blog_heading' => '',
				'techalgospotlight_blog_read_more' => esc_html__('Read More', 'techalgospotlight'),
				'techalgospotlight_blog_horizontal_post_categories' => true,
				'techalgospotlight_blog_horizontal_read_more' => false,

				// Single Post.
				'techalgospotlight_single_post_layout_heading' => false,
				'techalgospotlight_single_title_position' => 'in-content',
				'techalgospotlight_single_title_alignment' => 'left',
				'techalgospotlight_single_title_spacing' => array(
					'desktop' => array(
						'top' => 152,
						'bottom' => 100,
					),
					'tablet' => array(
						'top' => 90,
						'bottom' => 55,
					),
					'mobile' => array(
						'top' => '',
						'bottom' => '',
					),
					'unit' => 'px',
				),
				'techalgospotlight_single_content_width' => 'wide',
				'techalgospotlight_single_narrow_container_width' => 700,
				'techalgospotlight_single_post_elements_heading' => false,
				'techalgospotlight_single_post_meta_elements' => array(
					'author' => true,
					'date' => true,
					'comments' => true,
					'category' => false,
				),
				'techalgospotlight_single_post_thumb' => true,
				'techalgospotlight_single_post_categories' => true,
				'techalgospotlight_single_post_tags' => true,
				'techalgospotlight_single_last_updated' => true,
				'techalgospotlight_single_about_author' => true,
				'techalgospotlight_single_post_next_prev' => true,
				'techalgospotlight_single_post_elements' => array(
					'thumb' => true,
					'category' => true,
					'tags' => true,
					'last-updated' => true,
					'about-author' => true,
					'prev-next-post' => true,
				),
				'techalgospotlight_single_toggle_comments' => false,
				'techalgospotlight_single_entry_meta_icons' => true,
				'techalgospotlight_typography_single_post_heading' => false,
				'techalgospotlight_single_content_font_size' => array(
					'desktop' => '1.6',
					'unit' => 'rem',
				),

				/**
				 * Sidebar.
				 */

				'techalgospotlight_sidebar_position' => 'right-sidebar',
				'techalgospotlight_single_post_sidebar_position' => 'default',
				'techalgospotlight_single_page_sidebar_position' => 'default',
				'techalgospotlight_archive_sidebar_position' => 'default',
				'techalgospotlight_sidebar_options_heading' => false,
				'techalgospotlight_sidebar_style' => '2',
				'techalgospotlight_sidebar_width' => 30,
				'techalgospotlight_sidebar_sticky' => 'sidebar',
				'techalgospotlight_typography_sidebar_heading' => false,
				'techalgospotlight_sidebar_widget_title_font_size' => array(
					'desktop' => 2.4,
					'unit' => 'rem',
				),

				/**
				 * Footer.
				 */

				// Copyright.
				'techalgospotlight_enable_copyright' => true,
				'techalgospotlight_copyright_layout' => 'layout-1',
				'techalgospotlight_copyright_separator' => 'contained-separator',
				'techalgospotlight_copyright_visibility' => 'all',
				'techalgospotlight_copyright_heading_widgets' => true,
				'techalgospotlight_copyright_widgets' => array(
					array(
						'classname' => 'techalgospotlight_customizer_widget_text',
						'type' => 'text',
						'values' => array(
							'content' => wp_kses('Copyright {{the_year}} &mdash; <b>{{site_title}}</b>. All rights reserved. <b>{{theme_link}}</b>', techalgospotlight_get_allowed_html_tags()),
							// 'content'    => esc_html__( '', 'techalgospotlight' ),
							'location' => 'start',
							'visibility' => 'all',
						),
					),
				),
				'techalgospotlight_copyright_heading_design_options' => false,
				'techalgospotlight_copyright_background' => techalgospotlight_design_options_defaults(
					array(
						'background' => array(
							'color' => array(
								'background-color' => '',
							),
							'gradient' => array(),
						),
					)
				),
				'techalgospotlight_copyright_text_color' => techalgospotlight_design_options_defaults(
					array(
						'color' => array(
							'text-color' => '#d9d9d9',
							'link-color' => '#ffffff',
							'link-hover-color' => '#ffffff',
						),
					)
				),

				// Main Footer.
				'techalgospotlight_enable_footer' => true,
				'techalgospotlight_footer_layout' => 'layout-2',
				'techalgospotlight_footer_widgets_align_center' => false,
				'techalgospotlight_footer_visibility' => 'all',
				'techalgospotlight_footer_widget_heading_style' => '0',
				'techalgospotlight_footer_heading_design_options' => false,
				'techalgospotlight_footer_background' => techalgospotlight_design_options_defaults(
					array(
						'background' => array(
							'color' => array(
								'background-color' => '#525ceb',
							),
							'gradient' => array(),
							'image' => array(),
						),
					)
				),
				'techalgospotlight_footer_text_color' => techalgospotlight_design_options_defaults(
					array(
						'color' => array(
							'text-color' => '#d9d9d9',
							'link-color' => '#d9d9d9',
							'link-hover-color' => '#fffffff',
							'widget-title-color' => '#ffffff',
						),
					)
				),
				'techalgospotlight_footer_border' => techalgospotlight_design_options_defaults(
					array(
						'border' => array(
							'border-top-width' => 1,
							'border-bottom-width' => 0,
							'border-color' => 'rgba(255,255,255,0.1)',
							'border-style' => 'solid',
						),
					)
				),
				'techalgospotlight_typography_main_footer_heading' => false,
			);

			$defaults = array_merge($defaults, $techalgospotlight_categories_color_options);

			$defaults = apply_filters('techalgospotlight_default_option_values', $defaults);
			return $defaults;
		}

		/**
		 * Get the options from static array()
		 *
		 * @since  1.0.0
		 * @return array    Return array of theme options.
		 */
		public function get_options()
		{
			return self::$options;
		}

		/**
		 * Get the options from static array().
		 *
		 * @since  1.0.0
		 * @param string $id Options jet to get.
		 * @return array Return array of theme options.
		 */
		public function get($id)
		{
			$value = isset(self::$options[$id]) ? self::$options[$id] : self::get_default($id);
			$value = apply_filters("theme_mod_{$id}", $value); // phpcs:ignore
			return $value;
		}

		/**
		 * Set option.
		 *
		 * @since  1.0.0
		 * @param string $id Option key.
		 * @param any    $value Option value.
		 * @return void
		 */
		public function set($id, $value)
		{
			set_theme_mod($id, $value);
			self::$options[$id] = $value;
		}

		/**
		 * Refresh options.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function refresh()
		{
			self::$options = wp_parse_args(
				get_theme_mods(),
				self::get_defaults()
			);
		}

		/**
		 * Returns the default value for option.
		 *
		 * @since  1.0.0
		 * @param  string $id Option ID.
		 * @return mixed      Default option value.
		 */
		public function get_default($id)
		{
			$defaults = self::get_defaults();
			return isset($defaults[$id]) ? $defaults[$id] : false;
		}
	}

endif;
