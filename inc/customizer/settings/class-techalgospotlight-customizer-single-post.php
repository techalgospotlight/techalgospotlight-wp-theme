<?php
/**
 * techalgospotlight Blog - Single Post section in Customizer.
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

if (!class_exists('techalgospotlight_Customizer_Single_Post')):
	/**
	 * techalgospotlight Blog - Single Post section in Customizer.
	 */
	class techalgospotlight_Customizer_Single_Post
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
			$options['section']['techalgospotlight_section_blog_single_post'] = array(
				'title' => esc_html__('Single Post', 'techalgospotlight'),
				'panel' => 'techalgospotlight_panel_blog',
				'priority' => 20,
			);

			$options['setting']['techalgospotlight_single_post_elements'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_sortable',
				'control' => array(
					'type' => 'techalgospotlight-sortable',
					'section' => 'techalgospotlight_section_blog_single_post',
					'label' => esc_html__('Post Elements', 'techalgospotlight'),
					'description' => esc_html__('Set visibility of post elements.', 'techalgospotlight'),
					'sortable' => false,
					'choices' => array(
						'thumb' => esc_html__('Featured Image', 'techalgospotlight'),
						'category' => esc_html__('Post Categories', 'techalgospotlight'),
						'tags' => esc_html__('Post Tags', 'techalgospotlight'),
						'last-updated' => esc_html__('Last Updated Date', 'techalgospotlight'),
						'about-author' => esc_html__('About Author Box', 'techalgospotlight'),
						'prev-next-post' => esc_html__('Next/Prev Post Links', 'techalgospotlight'),
					),
				),
			);

			// Meta/Post Details Layout.
			$options['setting']['techalgospotlight_single_post_meta_elements'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_sortable',
				'control' => array(
					'type' => 'techalgospotlight-sortable',
					'label' => esc_html__('Post Meta', 'techalgospotlight'),
					'description' => esc_html__('Set order and visibility for post meta details.', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_blog_single_post',
					'choices' => array(
						'author' => esc_html__('Author', 'techalgospotlight'),
						'date' => esc_html__('Publish Date', 'techalgospotlight'),
						'comments' => esc_html__('Comments', 'techalgospotlight'),
						'category' => esc_html__('Categories', 'techalgospotlight'),
					),
				),
			);

			// Meta icons.
			$options['setting']['techalgospotlight_single_entry_meta_icons'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'section' => 'techalgospotlight_section_blog_single_post',
					'label' => esc_html__('Show avatar and icons in post meta', 'techalgospotlight'),
				),
			);

			// Toggle Comments.
			$options['setting']['techalgospotlight_single_toggle_comments'] = array(
				'transport' => 'refresh',
				'sanitize_callback' => 'techalgospotlight_sanitize_toggle',
				'control' => array(
					'type' => 'techalgospotlight-toggle',
					'label' => esc_html__('Show Toggle Comments', 'techalgospotlight'),
					'description' => esc_html__('Hide comments and comment form behind a toggle button. ', 'techalgospotlight'),
					'section' => 'techalgospotlight_section_blog_single_post',
				),
			);

			return $options;
		}
	}
endif;
new techalgospotlight_Customizer_Single_Post();
