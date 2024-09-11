<?php
/**
 * techalgospotlight Base Typography section in Customizer.
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('techalgospotlight_Customizer_Typography')):
	/**
	 * techalgospotlight Typography section in Customizer.
	 */
	class techalgospotlight_Customizer_Typography
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
			$options['section']['techalgospotlight_section_typography'] = array(
				'title' => esc_html__('Base Typography', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_general',
				'priority' => 30,
			);

			// Body Font.
			$options['setting']['techalgospotlight_body_font'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_typography',
				'control' => array(
					'type' => 'techalgospotlight-typography',
					'label' => esc_html__('Body Typography', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_typography',
					'display' => array(
						'font-family' => array(),
						'font-subsets' => array(),
						'font-weight' => array(),
						'font-style' => array(),
						'text-transform' => array(),
						'text-decoration' => array(),
						'letter-spacing' => array(),
						'font-size' => array(),
						'line-height' => array(),
					),
				),
			);

			return $options;
		}

	}
endif;
new techalgospotlight_Customizer_Typography();
