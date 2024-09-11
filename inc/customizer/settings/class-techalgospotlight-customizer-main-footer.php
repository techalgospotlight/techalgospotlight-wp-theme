<?php
/**
 * techalgospotlight Main Footer section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Main_Footer')):
	/**
	 * techalgospotlight Main Footer section in Customizer.
	 */
	class techalgospotlight_Customizer_Main_Footer
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

			// Section.
			$options['section']['techalgospotlight_section_main_footer'] = array(
				'title' => esc_html__('Main Footer', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_footer',
				'priority' => 20,
			);

			// Enable Footer.
			$options['setting']['techalgospotlight_enable_footer'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Main Footer', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_footer',
				),
			);

			// Footer Layout.
			$options['setting']['techalgospotlight_footer_layout'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-radio-image',
					'label' => esc_html__('Column Layout', 'techalgospotlight'),
					'description' => esc_html__('Choose your site&rsquo;s footer column layout.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_footer',
					'choices' => array(
						'layout-2' => array(
							'image' => techalgospotlight_THEME_URI . '/inc/customizer/assets/images/footer-layout-2.svg',
							'title' => esc_html__('1/3 + 1/3 + 1/3', 'techalgospotlight'),
						),
						'layout-8' => array(
							'image' => techalgospotlight_THEME_URI . '/inc/customizer/assets/images/footer-layout-8.svg',
							'title' => esc_html__('1', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_footer',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#techalgospotlight-footer-widgets',
					'render_callback' => 'techalgospotlight_footer_widgets',
					'container_inclusive' => false,
					'fallback_refresh' => true,
				),
			);

			// Center footer widgets..
			$options['setting']['techalgospotlight_footer_widgets_align_center'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Center Widget Content', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_footer',
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_footer',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#techalgospotlight-footer-widgets',
					'render_callback' => 'techalgospotlight_footer_widgets',
					'container_inclusive' => false,
					'fallback_refresh' => true,
				),
			);

			// Footer Design Options heading.
			$options['setting']['techalgospotlight_footer_heading_design_options'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Design Options', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_footer',
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_footer',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Background.
			$options['setting']['techalgospotlight_footer_background'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_design_options',
				'control' => array(
					'type' => 'techalgospotlight-design-options',
					'label' => esc_html__('Background', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_footer',
					'display' => array(
						'background' => array(
							'color' => esc_html__('Solid Color', 'techalgospotlight'),
							'gradient' => esc_html__('Gradient', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_footer',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_footer_heading_design_options',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Text Color.
			$options['setting']['techalgospotlight_footer_text_color'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_design_options',
				'control' => array(
					'type' => 'techalgospotlight-design-options',
					'label' => esc_html__('Font Color', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_footer',
					'display' => array(
						'color' => array(
							'text-color' => esc_html__('Text Color', 'techalgospotlight'),
							'link-color' => esc_html__('Link Color', 'techalgospotlight'),
							'link-hover-color' => esc_html__('Link Hover Color', 'techalgospotlight'),
							'widget-title-color' => esc_html__('Widget Title Color', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_footer',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_footer_heading_design_options',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Footer Border.
			$options['setting']['techalgospotlight_footer_border'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_design_options',
				'control' => array(
					'type' => 'techalgospotlight-design-options',
					'label' => esc_html__('Border', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_main_footer',
					'display' => array(
						'border' => array(
							'style' => esc_html__('Style', 'techalgospotlight'),
							'color' => esc_html__('Color', 'techalgospotlight'),
							'width' => esc_html__('Width (px)', 'techalgospotlight'),
							'positions' => array(
								'top' => esc_html__('Top', 'techalgospotlight'),
								'bottom' => esc_html__('Bottom', 'techalgospotlight'),
							),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_footer',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_footer_heading_design_options',
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
new techalgospotlight_Customizer_Main_Footer();
