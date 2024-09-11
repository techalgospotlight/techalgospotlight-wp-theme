<?php
/**
 * techalgospotlight Logo section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Logo')):
	/**
	 * techalgospotlight Logo section in Customizer.
	 */
	class techalgospotlight_Customizer_Logo
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

			// Logo Max Height.
			$options['setting']['techalgospotlight_logo_max_height'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_responsive',
				'control' => array(
					'type' => 'techalgospotlight-range',
					'label' => esc_html__('Logo Height', 'techalgospotlight'),
					'description' => esc_html__('Maximum logo image height.', 'techalgospotlight'),
					'section' => 'title_tagline',
					'priority' => 30,
					'min' => 0,
					'max' => 1000,
					'step' => 10,
					'unit' => 'px',
					'responsive' => true,
					'required' => array(
						array(
							'control' => 'custom_logo',
							'value' => false,
							'operator' => '!=',
						),
					),
				),
			);

			// Logo margin.
			$options['setting']['techalgospotlight_logo_margin'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_responsive',
				'control' => array(
					'type' => 'techalgospotlight-spacing',
					'label' => esc_html__('Logo Margin', 'techalgospotlight'),
					'description' => esc_html__('Specify spacing around logo. Negative values are allowed.', 'techalgospotlight'),
					'section' => 'title_tagline',
					'settings' => 'techalgospotlight_logo_margin',
					'priority' => 40,
					'choices' => array(
						'top' => esc_html__('Top', 'techalgospotlight'),
						'right' => esc_html__('Right', 'techalgospotlight'),
						'bottom' => esc_html__('Bottom', 'techalgospotlight'),
						'left' => esc_html__('Left', 'techalgospotlight'),
					),
					'responsive' => true,
					'unit' => array(
						'px',
					),
				),
			);

			// Show tagline.
			$options['setting']['techalgospotlight_display_tagline'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Display Tagline', 'techalgospotlight'),
					'section' => 'title_tagline',
					'settings' => 'techalgospotlight_display_tagline',
					'priority' => 80,
				),
				'partial' => array(
					'selector' => '.techalgospotlight-logo',
					'render_callback' => 'techalgospotlight_logo',
					'container_inclusive' => false,
					'fallback_refresh' => true,
				),
			);

			// Site Identity heading.
			$options['setting']['techalgospotlight_logo_heading_site_identity'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Site Identity', 'techalgospotlight'),
					'section' => 'title_tagline',
					'settings' => 'techalgospotlight_logo_heading_site_identity',
					'priority' => 50,
					'toggle' => false,
				),
			);

			// Logo typography heading.
			$options['setting']['techalgospotlight_typography_logo_heading'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Typography', 'techalgospotlight'),
					'section' => 'title_tagline',
					'priority' => 100,
					'required' => array(
						array(
							'control' => 'custom_logo',
							'value' => false,
							'operator' => '==',
						),
					),
				),
			);

			// Site title font size.
			$options['setting']['techalgospotlight_logo_text_font_size'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_responsive',
				'control' => array(
					'type' => 'techalgospotlight-range',
					'label' => esc_html__('Site Title Font Size', 'techalgospotlight'),
					'section' => 'title_tagline',
					'priority' => 100,
					'min' => 8,
					'max' => 30,
					'step' => 1,
					'responsive' => true,
					'unit' => array(
						array(
							'id' => 'px',
							'name' => 'px',
							'min' => 8,
							'max' => 90,
							'step' => 1,
						),
						array(
							'id' => 'em',
							'name' => 'em',
							'min' => 0.5,
							'max' => 5,
							'step' => 0.01,
						),
						array(
							'id' => 'rem',
							'name' => 'rem',
							'min' => 0.5,
							'max' => 5,
							'step' => 0.01,
						),
					),
					'required' => array(
						array(
							'control' => 'custom_logo',
							'value' => false,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_typography_logo_heading',
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
new techalgospotlight_Customizer_Logo();
