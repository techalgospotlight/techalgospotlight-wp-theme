<?php
/**
 * techalgospotlight Page Title Settings section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Page_Header')):
	/**
	 * techalgospotlight Page Title Settings section in Customizer.
	 */
	class techalgospotlight_Customizer_Page_Header
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

			// Page Title Section.
			$options['section']['techalgospotlight_section_page_header'] = array(
				'title' => esc_html__('Page Header', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_header',
				'priority' => 60,
			);

			// Page Header enable.
			$options['setting']['techalgospotlight_page_header_enable'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Page Header', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_page_header',
				),
			);

			// Spacing.
			$options['setting']['techalgospotlight_page_header_spacing'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_responsive',
				'control' => array(
					'type' => 'techalgospotlight-spacing',
					'label' => esc_html__('Page Title Spacing', 'techalgospotlight'),
					'description' => esc_html__('Specify Page Title top and bottom padding.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_page_header',
					'choices' => array(
						'top' => esc_html__('Top', 'techalgospotlight'),
						'bottom' => esc_html__('Bottom', 'techalgospotlight'),
					),
					'responsive' => true,
					'unit' => array(
						'px',
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_page_header_enable',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header design options heading.
			$options['setting']['techalgospotlight_page_header_heading_design'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Design Options', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_page_header',
					'required' => array(
						array(
							'control' => 'techalgospotlight_page_header_enable',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header background design.
			$options['setting']['techalgospotlight_page_header_background'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_design_options',
				'control' => array(
					'type' => 'techalgospotlight-design-options',
					'label' => esc_html__('Background', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_page_header',
					'display' => array(
						'background' => array(
							'color' => esc_html__('Solid Color', 'techalgospotlight'),
							'gradient' => esc_html__('Gradient', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_page_header_enable',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_page_header_heading_design',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Page Header Text Color.
			$options['setting']['techalgospotlight_page_header_text_color'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_design_options',
				'control' => array(
					'type' => 'techalgospotlight-design-options',
					'label' => esc_html__('Font Color', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_page_header',
					'display' => array(
						'color' => array(
							'text-color' => esc_html__('Text Color', 'techalgospotlight'),
							'link-color' => esc_html__('Link Color', 'techalgospotlight'),
							'link-hover-color' => esc_html__('Link Hover Color', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_page_header_enable',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_page_header_heading_design',
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
new techalgospotlight_Customizer_Page_Header();
