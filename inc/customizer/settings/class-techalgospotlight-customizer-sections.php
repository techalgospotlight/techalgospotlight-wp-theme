<?php
/**
 * techalgospotlight Customizer sections and panels.
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

if (!class_exists('techalgospotlight_Customizer_Sections')):
	/**
	 * techalgospotlight Customizer sections and panels.
	 */
	class techalgospotlight_Customizer_Sections
	{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			/**
			 * Registers our custom panels in Customizer.
			 */
			add_filter('techalgospotlight_customizer_options', array($this, 'register_panel'));
		}

		/**
		 * Registers our custom options in Customizer.
		 *
		 * @since 1.0.0
		 * @param array $options Array of customizer options.
		 */
		public function register_panel($options)
		{

			// Title - General Options
			$options['section']['techalgospotlight_section_general_group'] = array(
				'class' => 'techalgospotlight_Customizer_Control_Section_Group_Title',
				'title' => esc_html__('General Options', 'techalgospotlight'),
				'priority' => 1,
			);

			// General panel.
			$options['panel']['techalgospotlight_panel_general'] = array(
				'title' => esc_html__('General Settings', 'techalgospotlight'),
				'priority' => 2,
			);

			// Header panel.
			$options['panel']['techalgospotlight_panel_header'] = array(
				'title' => esc_html__('Header', 'techalgospotlight'),
				'priority' => 3,
			);

			// Footer panel.
			$options['panel']['techalgospotlight_panel_footer'] = array(
				'title' => esc_html__('Footer', 'techalgospotlight'),
				'priority' => 3,
			);

			// Blog settings.
			$options['panel']['techalgospotlight_panel_blog'] = array(
				'title' => esc_html__('Blog', 'techalgospotlight'),
				'priority' => 3,
			);

			// Title - Extra Options
			$options['section']['techalgospotlight_section_extra_group'] = array(
				'class' => 'techalgospotlight_Customizer_Control_Section_Group_Title',
				'title' => esc_html__('Extra Options', 'techalgospotlight'),
				'priority' => 4,
			);

			// Title - Core
			$options['section']['techalgospotlight_section_core_group'] = array(
				'class' => 'techalgospotlight_Customizer_Control_Section_Group_Title',
				'title' => esc_html__('Core', 'techalgospotlight'),
				'priority' => 7,
			);

			return $options;
		}
	}
endif;
new techalgospotlight_Customizer_Sections();
