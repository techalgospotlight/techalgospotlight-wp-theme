<?php
/**
 * techalgospotlight PYML section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_PYML')):
	/**
	 * techalgospotlight PYML section in Customizer.
	 */
	class techalgospotlight_Customizer_PYML
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
			// Posts You Might Like Section.
			$options['section']['techalgospotlight_section_pyml'] = array(
				'title' => esc_html__('Posts You Might Like', 'techalgospotlight'),
				'priority' => 4,
			);

			// Posts You Might Like enable.
			$options['setting']['techalgospotlight_enable_pyml'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'section' => 'techalgospotlight_section_pyml',
					'label' => esc_html__('Enable Posts You Might Like Section', 'techalgospotlight'),
				),
			);

			// Title.
			$options['setting']['techalgospotlight_pyml_title'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
				'control' => array(
					'type' => 'techalgospotlight-text',
					'section' => 'techalgospotlight_section_pyml',
					'label' => esc_html__('Title', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_pyml',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Posts You Might Like display on.
			$options['setting']['techalgospotlight_pyml_enable_on'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_no_sanitize',
				'control' => array(
					'type' => 'techalgospotlight-checkbox-group',
					'label' => esc_html__('Enable On: ', 'techalgospotlight'),
					'description' => esc_html__('Choose on which pages you want to enable Posts You Might Like. ', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_pyml',
					'choices' => array(
						'home' => array(
							'title' => esc_html__('Home Page', 'techalgospotlight'),
						),
						'posts_page' => array(
							'title' => esc_html__('Blog / Posts Page', 'techalgospotlight'),
						),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_pyml',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// PYML heading.
			$options['setting']['techalgospotlight_pyml_style'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'section' => 'techalgospotlight_section_pyml',
					'label' => esc_html__('Style', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_pyml',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// PYML Elements.
			$options['setting']['techalgospotlight_pyml_elements'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_sortable',
				'control' => array(
					'type' => 'techalgospotlight-sortable',
					'section' => 'techalgospotlight_section_pyml',
					'label' => esc_html__('Post Elements', 'techalgospotlight'),
					'description' => esc_html__('Set order and visibility for post elements.', 'techalgospotlight'),
					'sortable' => false,
					'choices' => array(
						'category' => esc_html__('Categories', 'techalgospotlight'),
						'meta' => esc_html__('Post Details', 'techalgospotlight'),
					),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_pyml',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_pyml_style',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#pyml',
					'render_callback' => 'techalgospotlight_blog_pyml',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			// Post Settings heading.
			$options['setting']['techalgospotlight_pyml_posts'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-heading',
					'section' => 'techalgospotlight_section_pyml',
					'label' => esc_html__('Post Settings', 'techalgospotlight'),
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_pyml',
							'value' => true,
							'operator' => '==',
						),
					),
				),
			);

			// Post count.
			$options['setting']['techalgospotlight_pyml_post_number'] = array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'techalgospotlight_sanitize_range',
				'control' => array(
					'type' => 'techalgospotlight-range',
					'section' => 'techalgospotlight_section_pyml',
					'label' => esc_html__('Post Number', 'techalgospotlight'),
					'description' => esc_html__('Set the number of visible posts.', 'techalgospotlight'),
					'min' => 1,
					'max' => 4,
					'step' => 1,
					'unit' => '',
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_pyml',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_pyml_posts',
							'value' => true,
							'operator' => '==',
						),
					),
				),
				'partial' => array(
					'selector' => '#pyml',
					'render_callback' => 'techalgospotlight_blog_pyml',
					'container_inclusive' => true,
					'fallback_refresh' => true,
				),
			);

			// Post category.
			$options['setting']['techalgospotlight_pyml_category'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'section' => 'techalgospotlight_section_pyml',
					'label' => esc_html__('Category', 'techalgospotlight'),
					'description' => esc_html__('Display posts from selected category only. Leave empty to include all.', 'techalgospotlight'),
					'is_select2' => true,
					'data_source' => 'category',
					'multiple' => true,
					'required' => array(
						array(
							'control' => 'techalgospotlight_enable_pyml',
							'value' => true,
							'operator' => '==',
						),
						array(
							'control' => 'techalgospotlight_pyml_posts',
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
new techalgospotlight_Customizer_PYML();
