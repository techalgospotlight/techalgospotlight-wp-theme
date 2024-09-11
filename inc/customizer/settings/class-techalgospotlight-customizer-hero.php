<?php
/**
 * techalgospotlight Hero Section Settings section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Hero')):
	/**
	 * techalgospotlight Page Title Settings section in Customizer.
	 */
	class techalgospotlight_Customizer_Hero
	{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			/**
			 * Registers our custom options in Customizer.
			 */
			add_filter('techalgospotlight_customizer_options', array($this, 'register_options'));
		}

		/**
		 * Registers our custom options in Customizer.
		 *
		 * @since 1.0.0
		 * @param array $options Array of customizer options.
		 */
		public function register_options($options)
		{

			// Hero Section.
			$options['section']['techalgospotlight_section_hero'] = array(
				'title' => esc_html__('Hero', 'techalgospotlight'),
				'priority' => 4,
			);

			// Hero enable.
			$options['setting']['techalgospotlight_enable_hero'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Enable Hero Section', 'techalgospotlight'),
				),
			);

			// Hero display on.
			$options['setting']['techalgospotlight_hero_enable_on'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_no_sanitize',
				'control' => array(
					'type' => 'techalgospotlight-checkbox-group',
					'label' => esc_html__('Enable On: ', 'techalgospotlight'),
					'description' => esc_html__('Choose on which pages you want to enable Hero. ', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_hero',
					'choices' => array(
						'home' => array(
							'title' => esc_html__('Home Page', 'techalgospotlight'),
						),
						'posts_page' => array(
							'title' => esc_html__('Blog / Posts Page', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Hero Type.
			$options['setting']['techalgospotlight_hero_type'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Type', 'techalgospotlight'),
					'description' => esc_html__('Choose hero style type.', 'techalgospotlight'),
					'choices' => array(
						'horizontal-slider' => esc_html__('Slider Horizontal', 'techalgospotlight'),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Post Settings heading.
			$options['setting']['techalgospotlight_hero_slider_posts'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Post Settings', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Post count.
			$options['setting']['techalgospotlight_hero_slider_post_number'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_range',
				'control' => array(
					'type' => 'techalgospotlight-range',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Post Number', 'techalgospotlight'),
					'description' => esc_html__('Set the number of visible posts.', 'techalgospotlight'),
					'min' => 1,
					'max' => 50,
					'step' => 1,
					'unit' => '',
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_hero_slider_posts',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#hero',
					'render_callback' => 'techalgospotlight_blog_hero',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			// Post category.
			$options['setting']['techalgospotlight_hero_slider_category'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Category', 'techalgospotlight'),
					'description' => esc_html__('Display posts from selected category only. Leave empty to include all.', 'techalgospotlight'),
					'is_select2' => true,
					'data_source' => 'category',
					'multiple' => true,
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_hero_slider_posts',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Hero Slider heading.
			$options['setting']['techalgospotlight_hero_slider'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Style', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Hero Slider Elements.
			$options['setting']['techalgospotlight_hero_slider_elements'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_sortable',
				'control' => array(
					'type' => 'techalgospotlight-sortable',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Post Elements', 'techalgospotlight'),
					'description' => esc_html__('Set order and visibility for post elements.', 'techalgospotlight'),
					'sortable' => false,
					'choices' => array(
						'category' => esc_html__('Categories', 'techalgospotlight'),
						'meta' => esc_html__('Post Details', 'techalgospotlight'),
						'read_more' => esc_html__('Continue Reading', 'techalgospotlight'),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_hero_slider',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#hero',
					'render_callback' => 'techalgospotlight_blog_hero',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			// Hero Slider Meta/Post Details.
			$options['setting']['techalgospotlight_hero_entry_meta_elements'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_sortable',
				'control' => array(
					'type' => 'techalgospotlight-sortable',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Post Meta', 'techalgospotlight'),
					'description' => esc_html__('Set order and visibility for post meta details.', 'techalgospotlight'),
					'choices' => array(
						'author' => esc_html__('Author', 'techalgospotlight'),
						'date' => esc_html__('Publish Date', 'techalgospotlight'),
						'comments' => esc_html__('Comments', 'techalgospotlight'),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_hero_slider',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#hero',
					'render_callback' => 'techalgospotlight_blog_hero',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			// Continue Reading.
			$options['setting']['techalgospotlight_hero_slider_read_more'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
				'control' => array(
					'type' => 'techalgospotlight-text',
					'section' => 'techalgospotlight_section_hero',
					'label' => esc_html__('Continue Reading', 'techalgospotlight'),
					'description' => esc_html__('Change Continue Reading Text.', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_hero',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_hero_slider',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			return $options;
		}
	}
endif;
new techalgospotlight_Customizer_Hero();
