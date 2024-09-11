<?php
/**
 * techalgospotlight Blog » Blog Page / Archive section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Blog_Page')):
	/**
	 * techalgospotlight Blog » Blog Page / Archive section in Customizer.
	 */
	class techalgospotlight_Customizer_Blog_Page
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
			$options['section']['techalgospotlight_section_blog_page'] = array(
				'title' => esc_html__('Blog Page / Archive', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_blog',
			);

			// Layout.
			$options['setting']['techalgospotlight_blog_layout'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'label' => esc_html__('Layout', 'techalgospotlight'),
					'description' => esc_html__('Choose blog layout.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_blog_page',
					'choices' => array(
						'blog-horizontal' => esc_html__('Horizontal', 'techalgospotlight'),
					),
				),
			);

			$_image_sizes = techalgospotlight_get_image_sizes();
			$size_choices = array();

			if (!empty($_image_sizes)) {
				foreach ($_image_sizes as $key => $value) {
					$name = ucwords(str_replace(array('-', '_'), ' ', $key));

					$size_choices[$key] = $name;

					if ($value['width'] || $value['height']) {
						$size_choices[$key] .= ' (' . $value['width'] . 'x' . $value['height'] . ')';
					}
				}
			}

			// Featured Image Size.
			$options['setting']['techalgospotlight_blog_image_size'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_select',
				'control' => array(
					'type' => 'techalgospotlight-select',
					'label' => esc_html__('Featured Image Size', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_blog_page',
					'choices' => $size_choices,
				),
			);

			// Read more.
			$options['setting']['techalgospotlight_blog_read_more'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
				'control' => array(
					'type' => 'techalgospotlight-text',
					'section' => 'techalgospotlight_section_blog_page',
					'label' => esc_html__('Read More', 'techalgospotlight'),
					'description' => esc_html__('Change Read More Text.', 'techalgospotlight'),
				),
			);

			// Meta/Post Details Layout.
			$options['setting']['techalgospotlight_blog_entry_meta_elements'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_sortable',
				'control' => array(
					'type' => 'techalgospotlight-sortable',
					'section' => 'techalgospotlight_section_blog_page',
					'label' => esc_html__('Post Meta', 'techalgospotlight'),
					'description' => esc_html__('Set order and visibility for post meta details.', 'techalgospotlight'),
					'choices' => array(
						'author' => esc_html__('Author', 'techalgospotlight'),
						'date' => esc_html__('Publish Date', 'techalgospotlight'),
						'comments' => esc_html__('Comments', 'techalgospotlight'),
						'tag' => esc_html__('Tags', 'techalgospotlight'),
					),
				),
			);

			// Post Categories.
			$options['setting']['techalgospotlight_blog_horizontal_post_categories'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Show Post Categories', 'techalgospotlight'),
					'description' => esc_html__('A list of categories the post belongs to. Displayed above post title.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_blog_page',
					'required' => array(
						array(
							'control' => 'techalgospotlight_blog_layout',
							'value' => 'blog-horizontal',
							'operator' => '==',
						),
					),
				),
			);

			// Read More Button.
			$options['setting']['techalgospotlight_blog_horizontal_read_more'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Show Read More Button', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_blog_page',
					'required' => array(
						array(
							'control' => 'techalgospotlight_blog_layout',
							'value' => 'blog-horizontal',
							'operator' => '==',
						),
					),
				),
			);

			// Meta Author image.
			$options['setting']['techalgospotlight_entry_meta_icons'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'section' => 'techalgospotlight_section_blog_page',
					'label' => esc_html__('Show avatar and icons in post meta', 'techalgospotlight'),
				),
			);

			// Excerpt Length.
			$options['setting']['techalgospotlight_excerpt_length'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_range',
				'control' => array(
					'type' => 'techalgospotlight-range',
					'section' => 'techalgospotlight_section_blog_page',
					'label' => esc_html__('Excerpt Length', 'techalgospotlight'),
					'description' => esc_html__('Number of words displayed in the excerpt.', 'techalgospotlight'),
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'unit' => '',
					'responsive' => false,
				),
			);

			// Excerpt more.
			$options['setting']['techalgospotlight_excerpt_more'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
				'control' => array(
					'type' => 'techalgospotlight-text',
					'section' => 'techalgospotlight_section_blog_page',
					'label' => esc_html__('Excerpt More', 'techalgospotlight'),
					'description' => esc_html__('What to append to excerpt if the text is cut.', 'techalgospotlight'),
				),
			);

			return $options;
		}
	}
endif;

new techalgospotlight_Customizer_Blog_Page();
