<?php
/**
 * techalgospotlight Base Colors section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Colors')):
	/**
	 * techalgospotlight Colors section in Customizer.
	 */
	class techalgospotlight_Customizer_Colors
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
			$options['section']['techalgospotlight_section_colors'] = array(
				'title' => esc_html__('Base Colors', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_general',
				'priority' => 20,
			);

			// Accent color.
			$options['setting']['techalgospotlight_accent_color'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_color',
				'control' => array(
					'type' => 'techalgospotlight-color',
					'label' => esc_html__('Accent Color', 'techalgospotlight'),
					'description' => esc_html__('The accent color is used subtly throughout your site, to call attention to key elements.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_colors',
					'priority' => 10,
					'opacity' => false,
				),
			);

			// Dark mode
			$options['setting']['techalgospotlight_dark_mode'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Dark mode', 'techalgospotlight'),
					'description' => esc_html__('Enable dark mode.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_colors',
					'priority' => 11,
				),
			);

			// Body Animation
			$options['setting']['techalgospotlight_body_animation'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'label' => esc_html__('Body Animation', 'techalgospotlight'),
					'description' => esc_html__('Choose Body Animation.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_colors',
					'priority' => 12,
					'choices' => array(
						'0' => esc_html__('None', 'techalgospotlight'),
						'1' => esc_html__('Glassmorphism', 'techalgospotlight'),
					),
				),
			);

			// Body background heading.
			$options['setting']['techalgospotlight_body_background_heading'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'priority' => 40,
					'label' => esc_html__('Body Background', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_colors',
					'toggle' => false,
				),
			);

			return $options;
		}

	}
endif;
new techalgospotlight_Customizer_Colors();
