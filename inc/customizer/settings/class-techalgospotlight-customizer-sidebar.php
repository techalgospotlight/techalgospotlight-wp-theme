<?php
/**
 * techalgospotlight Sidebar section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Sidebar')):

	/**
	 * techalgospotlight Sidebar section in Customizer.
	 */
	class techalgospotlight_Customizer_Sidebar
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
			$options['section']['techalgospotlight_section_sidebar'] = array(
				'title' => esc_html__('Sidebar', 'techalgospotlight'),
				'priority' => 3,
			);

			// Default sidebar position.
			$options['setting']['techalgospotlight_sidebar_position'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'section' => 'techalgospotlight_section_sidebar',
					'label' => esc_html__('Default Position', 'techalgospotlight'),
					'description' => esc_html__('Choose default sidebar position layout. You can change this setting per page via metabox settings.', 'techalgospotlight'),
					'choices' => array(
						'no-sidebar' => esc_html__('No Sidebar', 'techalgospotlight'),
						'left-sidebar' => esc_html__('Left Sidebar', 'techalgospotlight'),
						'right-sidebar' => esc_html__('Right Sidebar', 'techalgospotlight'),
					),
				),
			);

			// Single post sidebar position.
			$options['setting']['techalgospotlight_single_post_sidebar_position'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'label' => esc_html__('Single Post', 'techalgospotlight'),
					'description' => esc_html__('Choose default sidebar position layout for single posts. You can change this setting per post via metabox settings.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_sidebar',
					'choices' => array(
						'default' => esc_html__('Default', 'techalgospotlight'),
						'no-sidebar' => esc_html__('No Sidebar', 'techalgospotlight'),
						'left-sidebar' => esc_html__('Left Sidebar', 'techalgospotlight'),
						'right-sidebar' => esc_html__('Right Sidebar', 'techalgospotlight'),
					),
				),
			);

			// Single page sidebar position.
			$options['setting']['techalgospotlight_single_page_sidebar_position'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'label' => esc_html__('Page', 'techalgospotlight'),
					'description' => esc_html__('Choose default sidebar position layout for pages. You can change this setting per page via metabox settings.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_sidebar',
					'choices' => array(
						'default' => esc_html__('Default', 'techalgospotlight'),
						'no-sidebar' => esc_html__('No Sidebar', 'techalgospotlight'),
						'left-sidebar' => esc_html__('Left Sidebar', 'techalgospotlight'),
						'right-sidebar' => esc_html__('Right Sidebar', 'techalgospotlight'),
					),
				),
			);

			// Archive sidebar position.
			$options['setting']['techalgospotlight_archive_sidebar_position'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'label' => esc_html__('Archives & Search', 'techalgospotlight'),
					'description' => esc_html__('Choose default sidebar position layout for archives and search results.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_sidebar',
					'choices' => array(
						'default' => esc_html__('Default', 'techalgospotlight'),
						'no-sidebar' => esc_html__('No Sidebar', 'techalgospotlight'),
						'left-sidebar' => esc_html__('Left Sidebar', 'techalgospotlight'),
						'right-sidebar' => esc_html__('Right Sidebar', 'techalgospotlight'),
					),
				),
			);

			// Sidebar options heading.
			$options['setting']['techalgospotlight_sidebar_options_heading'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'label' => esc_html__('Options', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_sidebar',
				),
			);

			// Sidebar width.
			$options['setting']['techalgospotlight_sidebar_width'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_range',
				'control' => array(
					'type' => 'techalgospotlight-range',
					'section' => 'techalgospotlight_section_sidebar',
					'label' => esc_html__('Sidebar Width', 'techalgospotlight'),
					'description' => esc_html__('Change your sidebar width.', 'techalgospotlight'),
					'min' => 15,
					'max' => 50,
					'step' => 1,
					'unit' => '%',
					'required' => array(
						array(
							'control' => 'techalgospotlight_sidebar_options_heading',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Sticky sidebar.
			$options['setting']['techalgospotlight_sidebar_sticky'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'section' => 'techalgospotlight_section_sidebar',
					'label' => esc_html__('Sticky Sidebar', 'techalgospotlight'),
					'description' => esc_html__('Stick sidebar when scrolling.', 'techalgospotlight'),
					'choices' => array(
						'' => esc_html__('Disable', 'techalgospotlight'),
						'sidebar' => esc_html__('Stick first widget', 'techalgospotlight'),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_sidebar_options_heading',
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

new techalgospotlight_Customizer_Sidebar();
