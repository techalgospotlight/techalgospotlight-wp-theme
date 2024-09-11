<?php
/**
 * techalgospotlight Featured Links Section Settings section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Featured_Links')):
	/**
	 * techalgospotlight Page Title Settings section in Customizer.
	 */
	class techalgospotlight_Customizer_Featured_Links
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

			// Featured links Section.
			$options['section']['techalgospotlight_section_featured_links'] = array(
				'title' => esc_html__('Featured Items', 'techalgospotlight'),
				'priority' => 4,
			);

			// Featured links enable.
			$options['setting']['techalgospotlight_enable_featured_links'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'section' => 'techalgospotlight_section_featured_links',
					'label' => esc_html__('Enable featured items section', 'techalgospotlight'),
				),
			);

			// Title.
			$options['setting']['techalgospotlight_featured_links_title'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'control' => array(
					'type' => 'techalgospotlight-text',
					'section' => 'techalgospotlight_section_featured_links',
					'label' => esc_html__('Title', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_featured_links',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			$options['setting']['techalgospotlight_featured_links'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_repeater_sanitize',
				'control' => array(
					'type' => 'techalgospotlight-repeater',
					'label' => esc_html__('Featured Items', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_featured_links',
					'item_name' => esc_html__('Featured Link', 'techalgospotlight'),
					'title_format' => esc_html__('[live_title]', 'techalgospotlight'), // [live_title]
					'add_text' => esc_html__('Add new Feature', 'techalgospotlight'),
					'max_item' => 3, // 3 Maximum item can add,
					'limited_msg' => wp_kses_post(__('Upgrade to <a target="_blank" href="https://peregrine-themes.com/techalgospotlight/">techalgospotlight Pro</a> to be able to add more items and unlock other premium features!', 'techalgospotlight')),
					'fields' => array(
						'link' => array(
							'title' => esc_html__('Select feature link', 'techalgospotlight'),
							'type' => 'link',
						),

						'image' => array(
							'title' => esc_html__('Image', 'techalgospotlight'),
							'type' => 'media',
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_featured_links',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#featured_links',
					'render_callback' => 'techalgospotlight_blog_featured_links',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			// Featured links display on.
			$options['setting']['techalgospotlight_featured_links_enable_on'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_no_sanitize',
				'control' => array(
					'type' => 'techalgospotlight-checkbox-group',
					'label' => esc_html__('Enable On: ', 'techalgospotlight'),
					'description' => esc_html__('Choose on which pages you want to enable Featured links. ', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_featured_links',
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
							'control' => 'techalgospotlight_enable_featured_links',
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
new techalgospotlight_Customizer_Featured_Links();
