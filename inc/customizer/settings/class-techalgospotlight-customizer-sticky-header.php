<?php
/**
 * techalgospotlight Sticky Header Settings section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Sticky_Header')):
	/**
	 * techalgospotlight Sticky Header section in Customizer.
	 */
	class techalgospotlight_Customizer_Sticky_Header
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

			// Sticky Header Section.
			$options['section']['techalgospotlight_section_sticky_header'] = array(
				'title' => esc_html__('Sticky Header', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_header',
				'priority' => 80,
			);

			// Enable Transparent Header.
			$options['setting']['techalgospotlight_sticky_header'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Sticky Header', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_sticky_header',
				),
			);

			return $options;
		}
	}
endif;
new techalgospotlight_Customizer_Sticky_Header();
