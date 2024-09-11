<?php
/**
 * techalgospotlight Main Header Settings section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Main_Header')):
	/**
	 * techalgospotlight Main Header section in Customizer.
	 */
	class techalgospotlight_Customizer_Main_Header
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

			// Main Header Section.
			$options['section']['techalgospotlight_section_main_header'] = array(
				'title' => esc_html__('Main Header', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_header',
				'priority' => 20,
			);

			// Header Layout.
			$options['setting']['techalgospotlight_header_layout'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-radio-image',
					'label' => esc_html__('Header Layout', 'techalgospotlight'),
					'description' => esc_html__('Pre-defined positions of header elements, such as logo and navigation.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_header',
					'priority' => 5,
					'choices' => array(
						'layout-1' => array(
							'image' => techalgospotlight_THEME_URI . '/inc/customizer/assets/images/header-layout-1.svg',
							'title' => esc_html__('Header 1', 'techalgospotlight'),
						),
					),
				),
			);

			// Header widgets heading.
			$options['setting']['techalgospotlight_header_heading_widgets'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Header Widgets', 'techalgospotlight'),
					'description' => esc_html__('Click the "Add Widget" button to add available widgets to your Header. Click the down arrow icon to expand widget options.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_header',
					'space' => true,
				),
			);

			// Header widgets.
			$options['setting']['techalgospotlight_header_widgets'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_widget',
				'control' => array(
					'type' => 'techalgospotlight-widget',
					'label' => esc_html__('Header Widgets', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_header',
					'widgets' => apply_filters(
						'techalgospotlight_main_header_widgets',
						array(
							'search' => array(
								'max_uses' => 1,
							),
							'darkmode' => array(
								'max_uses' => 1,
							),
							'button' => array(
								'max_uses' => 1,
							),
							'socials' => array(
								'max_uses' => 1,
								'styles' => array(
									'rounded-fill' => esc_html__('Rounded Fill', 'techalgospotlight'),
									'rounded-border' => esc_html__('Rounded Border', 'techalgospotlight'),
								),
							),
						)
					),
					'locations' => array(
						'left' => esc_html__('Left', 'techalgospotlight'),
						'right' => esc_html__('Right', 'techalgospotlight'),
					),
					'visibility' => array(
						'all' => esc_html__('Show on All Devices', 'techalgospotlight'),
						'hide-mobile' => esc_html__('Hide on Mobile', 'techalgospotlight'),
						'hide-tablet' => esc_html__('Hide on Tablet', 'techalgospotlight'),
						'hide-mobile-tablet' => esc_html__('Hide on Mobile and Tablet', 'techalgospotlight'),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_header_heading_widgets',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#techalgospotlight-header',
					'render_callback' => 'techalgospotlight_header_content_output',
					'container_inclusive' => false,
					'fallback_refresh' => true,
				),
			);

			return $options;
		}
	}
endif;
new techalgospotlight_Customizer_Main_Header();
