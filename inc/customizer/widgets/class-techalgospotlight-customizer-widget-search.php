<?php
/**
 * techalgospotlight Customizer widgets class.
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

if (!class_exists('techalgospotlight_Customizer_Widget_Search')):

	/**
	 * techalgospotlight Customizer widget class
	 */
	class techalgospotlight_Customizer_Widget_Search extends techalgospotlight_Customizer_Widget
	{

		/**
		 * Menu Location for this widget
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $styles = array();

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct($args = array())
		{

			$values = array(
				'style' => '',
				'visibility' => 'all',
			);

			$args['values'] = isset($args['values']) ? wp_parse_args($args['values'], $values) : $values;

			$args['values']['style'] = sanitize_text_field($args['values']['style']);

			parent::__construct($args);

			$this->name = __('Search', 'techalgospotlight');
			$this->description = __('A search form for your site.', 'techalgospotlight');
			$this->icon = 'dashicons dashicons-search';
			$this->type = 'search';

			$this->styles = isset($args['styles']) ? $args['styles'] : array();
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function form()
		{

			if (!empty($this->styles)) { ?>
				<p class="techalgospotlight-widget-search-style">
					<label for="widget-search-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-style">
						<?php esc_html_e('Style', 'techalgospotlight'); ?>:
					</label>
					<select id="widget-search-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-style"
						name="widget-search[<?php echo esc_attr($this->number); ?>][style]" data-option-name="style">
						<?php foreach ($this->styles as $key => $value) { ?>
							<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $this->values['style'], true); ?>>
								<?php echo esc_html($value); ?>
							</option>
						<?php } ?>
					</select>
				</p>
				<?php
			}
		}
	}
endif;
