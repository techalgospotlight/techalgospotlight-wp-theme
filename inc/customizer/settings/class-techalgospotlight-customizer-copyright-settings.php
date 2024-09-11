<?php
/**
 * techalgospotlight Copyright Bar section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Copyright_Settings')):
	/**
	 * techalgospotlight Copyright Bar section in Customizer.
	 */
	class techalgospotlight_Customizer_Copyright_Settings
	{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			// Registers our custom options in Customizer.
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

			// Section.
			$options['section']['techalgospotlight_section_copyright_bar'] = array(
				'title' => esc_html__('Copyright Bar', 'techalgospotlight'),
				'priority' => 30,
				'panel' => 'techalgospotlight_panel_footer',
			);

			// Enable Copyright Bar.
			$options['setting']['techalgospotlight_enable_copyright'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Copyright Bar', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_copyright_bar',
				),
			);

			// Copyright Layout.
			$options['setting']['techalgospotlight_copyright_layout'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-radio-image',
					'section' => 'techalgospotlight_section_copyright_bar',
					'label' => esc_html__('Copyright Layout', 'techalgospotlight'),
					'description' => esc_html__('Choose your site&rsquo;s copyright widgets layout.', 'techalgospotlight'),
					'choices' => array(
						'layout-1' => array(
							'image' => techalgospotlight_THEME_URI . '/inc/customizer/assets/images/copyright-layout-1.svg',
							'title' => esc_html__('Centered', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_copyright',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Copyright widgets heading.
			$options['setting']['techalgospotlight_copyright_heading_widgets'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'section' => 'techalgospotlight_section_copyright_bar',
					'label' => esc_html__('Copyright Bar Widgets', 'techalgospotlight'),
					'description' => esc_html__('Click the Add Widget button to add available widgets to your Copyright Bar.', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_copyright',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Copyright widgets.
			$options['setting']['techalgospotlight_copyright_widgets'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_widget',
				'control' => array(
					'type' => 'techalgospotlight-widget',
					'section' => 'techalgospotlight_section_copyright_bar',
					'label' => esc_html__('Copyright Bar Widgets', 'techalgospotlight'),
					'widgets' => array(
						'text' => array(
							'max_uses' => 1,
						),
						'nav' => array(
							'menu_location' => apply_filters('techalgospotlight_footer_menu_location', 'techalgospotlight-footer'),
							'max_uses' => 1,
						),
						'socials' => array(
							'max_uses' => 1,
							'styles' => array(
								'minimal' => esc_html__('Minimal', 'techalgospotlight'),
								'rounded' => esc_html__('Rounded', 'techalgospotlight'),
							),
						),
					),
					'locations' => array(
						'start' => esc_html__('Start', 'techalgospotlight'),
						'end' => esc_html__('End', 'techalgospotlight'),
					),
					'visibility' => array(
						'all' => esc_html__('Show on All Devices', 'techalgospotlight'),
						'hide-mobile' => esc_html__('Hide on Mobile', 'techalgospotlight'),
						'hide-tablet' => esc_html__('Hide on Tablet', 'techalgospotlight'),
						'hide-mobile-tablet' => esc_html__('Hide on Mobile and Tablet', 'techalgospotlight'),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_copyright_heading_widgets',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_enable_copyright',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#techalgospotlight-copyright',
					'render_callback' => 'techalgospotlight_copyright_bar_output',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			return $options;
		}

	}
endif;
new techalgospotlight_Customizer_Copyright_Settings();
