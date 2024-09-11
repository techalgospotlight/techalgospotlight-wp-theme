<?php
/**
 * techalgospotlight Misc section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Misc')):
	/**
	 * techalgospotlight Misc section in Customizer.
	 */
	class techalgospotlight_Customizer_Misc
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
			$options['section']['techalgospotlight_section_misc'] = array(
				'title' => esc_html__('Misc Settings', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_general',
				'priority' => 60,
			);

			// Schema toggle.
			$options['setting']['techalgospotlight_enable_schema'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Schema Markup', 'techalgospotlight'),
					'description' => esc_html__('Add structured data to your content.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_misc',
				),
			);

			// Custom form styles.
			$options['setting']['techalgospotlight_custom_input_style'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Custom Form Styles', 'techalgospotlight'),
					'description' => esc_html__('Custom design for checkboxes and radio buttons.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_misc',
				),
			);

			// Enable/Disable Page Preloader.
			$options['setting']['techalgospotlight_preloader'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Page Preloader', 'techalgospotlight'),
					'description' => esc_html__('Show animation until page is fully loaded.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_misc',
				),
			);

			// Enable/Disable Scroll Top.
			$options['setting']['techalgospotlight_scroll_top'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Scroll Top Button', 'techalgospotlight'),
					'description' => esc_html__('A sticky button that allows users to easily return to the top of a page.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_misc',
				),
			);

			// Enable/Disable Cursor Dot.
			$options['setting']['techalgospotlight_enable_cursor_dot'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Cursor Dot', 'techalgospotlight'),
					'description' => esc_html__('A cursor dot effect show on desktop size mode only with work on mouse.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_misc',
				),
			);

			return $options;
		}
	}
endif;
new techalgospotlight_Customizer_Misc();
