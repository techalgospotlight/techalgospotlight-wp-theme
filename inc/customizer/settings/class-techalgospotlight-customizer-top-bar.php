<?php
/**
 * techalgospotlight Top Bar Settings section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Top_Bar')):
	/**
	 * techalgospotlight Top Bar Settings section in Customizer.
	 */
	class techalgospotlight_Customizer_Top_Bar
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
			$options['section']['techalgospotlight_section_top_bar'] = array(
				'title' => esc_html__('Top Bar', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_header',
				'priority' => 10,
			);

			// Enable Top Bar.
			$options['setting']['techalgospotlight_top_bar_enable'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Top Bar', 'techalgospotlight'),
					'description' => esc_html__('Top Bar is a section with widgets located above Main Header area.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_top_bar',
				),
			);

			// Top Bar widgets heading.
			$options['setting']['techalgospotlight_top_bar_heading_widgets'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Top Bar Widgets', 'techalgospotlight'),
					'description' => esc_html__('Click the Add Widget button to add available widgets to your Top Bar.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_top_bar',
					'required' => array(
						array(
							'control' => 'techalgospotlight_top_bar_enable',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar widgets.
			$options['setting']['techalgospotlight_top_bar_widgets'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_widget',
				'control' => array(
					'type' => 'techalgospotlight-widget',
					'label' => esc_html__('Top Bar Widgets', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_top_bar',
					'widgets' => array(
						'text' => array(
							'max_uses' => 2,
						),
						'nav' => array(
							'max_uses' => 1,
						),
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
							'control' => 'techalgospotlight_top_bar_heading_widgets',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_top_bar_enable',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#techalgospotlight-topbar',
					'render_callback' => 'techalgospotlight_topbar_output',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			// Top Bar design options heading.
			$options['setting']['techalgospotlight_top_bar_heading_design_options'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Design Options', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_top_bar',
					'required' => array(
						array(
							'control' => 'techalgospotlight_top_bar_enable',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar Background.
			$options['setting']['techalgospotlight_top_bar_background'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_design_options',
				'control' => array(
					'type' => 'techalgospotlight-design-options',
					'label' => esc_html__('Background', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_top_bar',
					'display' => array(
						'background' => array(
							'color' => esc_html__('Solid Color', 'techalgospotlight'),
							'gradient' => esc_html__('Gradient', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_top_bar_enable',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_top_bar_heading_design_options',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Top Bar Text Color.
			$options['setting']['techalgospotlight_top_bar_text_color'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_design_options',
				'control' => array(
					'type' => 'techalgospotlight-design-options',
					'label' => esc_html__('Font Color', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_top_bar',
					'display' => array(
						'color' => array(
							'text-color' => esc_html__('Text Color', 'techalgospotlight'),
							'link-color' => esc_html__('Link Color', 'techalgospotlight'),
							'link-hover-color' => esc_html__('Link Hover Color', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_top_bar_enable',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_top_bar_heading_design_options',
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
new techalgospotlight_Customizer_Top_Bar();
