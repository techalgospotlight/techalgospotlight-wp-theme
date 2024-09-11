<?php
/**
 * techalgospotlight Layout section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Layout')):
	/**
	 * techalgospotlight Layout section in Customizer.
	 */
	class techalgospotlight_Customizer_Layout
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
			$options['section']['techalgospotlight_layout_section'] = array(
				'title' => esc_html__('Layout', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_general',
				'priority' => 10,
			);

			// Site layout.
			$options['setting']['techalgospotlight_site_layout'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'section' => 'techalgospotlight_layout_section',
					'label' => esc_html__('Site Layout', 'techalgospotlight'),
					'description' => esc_html__('Choose your site&rsquo;s main layout.', 'techalgospotlight'),
					'choices' => array(
						'fw-contained' => esc_html__('Full Width: Contained', 'techalgospotlight'),
						'fw-stretched' => esc_html__('Full Width: Stretched', 'techalgospotlight'),
					),
				),
			);

			return $options;
		}
	}
endif;
new techalgospotlight_Customizer_Layout();
