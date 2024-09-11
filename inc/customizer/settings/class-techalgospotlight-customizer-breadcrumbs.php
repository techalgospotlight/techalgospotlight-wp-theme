<?php
/**
 * techalgospotlight Breadcrumbs Settings section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Breadcrumbs')):
	/**
	 * techalgospotlight Breadcrumbs Settings section in Customizer.
	 */
	class techalgospotlight_Customizer_Breadcrumbs
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

			// Main Navigation Section.
			$options['section']['techalgospotlight_section_breadcrumbs'] = array(
				'title' => esc_html__('Breadcrumbs', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_header',
				'priority' => 70,
			);

			// Breadcrumbs.
			$options['setting']['techalgospotlight_breadcrumbs_enable'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Enable Breadcrumbs', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_breadcrumbs',
				),
			);

			// Hide breadcrumbs on.
			$options['setting']['techalgospotlight_breadcrumbs_hide_on'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_no_sanitize',
				'control' => array(
					'type' => 'techalgospotlight-checkbox-group',
					'label' => esc_html__('Disable On: ', 'techalgospotlight'),
					'description' => esc_html__('Choose on which pages you want to disable breadcrumbs. ', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_breadcrumbs',
					'choices' => techalgospotlight_get_display_choices(),
					'required' => array(
						array(
							'control' => 'techalgospotlight_breadcrumbs_enable',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Spacing.
			$options['setting']['techalgospotlight_breadcrumbs_spacing'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_responsive',
				'control' => array(
					'type' => 'techalgospotlight-spacing',
					'label' => esc_html__('Spacing', 'techalgospotlight'),
					'description' => esc_html__('Specify top and bottom padding.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_breadcrumbs',
					'choices' => array(
						'top' => esc_html__('Top', 'techalgospotlight'),
						'bottom' => esc_html__('Bottom', 'techalgospotlight'),
					),
					'responsive' => true,
					'unit' => array(
						'px',
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_breadcrumbs_enable',
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
new techalgospotlight_Customizer_Breadcrumbs();
