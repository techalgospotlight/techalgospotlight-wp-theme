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

if (!class_exists('techalgospotlight_Customizer_Widget_Socials')):

	/**
	 * techalgospotlight Customizer widget class
	 */
	class techalgospotlight_Customizer_Widget_Socials extends techalgospotlight_Customizer_Widget_Nav
	{

		/**
		 * Explicitly declare the sizes and styles properties.
		 *
		 * @var array
		 */
		public $sizes = array();
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
				'size' => '',
				'visibility' => 'all',
			);

			$args['values'] = isset($args['values']) ? wp_parse_args($args['values'], $values) : $values;

			$args['values']['style'] = sanitize_text_field($args['values']['style']);
			$args['values']['size'] = sanitize_text_field($args['values']['size']);

			parent::__construct($args);

			$this->name = __('Social Links', 'techalgospotlight');
			$this->description = __('Links to your social media profiles.', 'techalgospotlight');
			$this->icon = 'dashicons dashicons-twitter';
			$this->type = 'socials';
			$this->styles = isset($args['styles']) ? $args['styles'] : array();
			$this->sizes = isset($args['sizes']) ? $args['sizes'] : array();
		}

		/**
		 * Displays the form for this widget on the Widgets page of the WP Admin area.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function form()
		{

			parent::form();

			if (!empty($this->styles)) { ?>
				<p class="techalgospotlight-widget-socials-style">
					<label for="widget-socials-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-style">
						<?php esc_html_e('Style', 'techalgospotlight'); ?>:
					</label>
					<select id="widget-socials-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-style"
						name="widget-socials[<?php echo esc_attr($this->number); ?>][style]" data-option-name="style">
						<?php foreach ($this->styles as $key => $value) { ?>
							<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $this->values['style'], true); ?>>
								<?php echo esc_html($value); ?>
							</option>
						<?php } ?>
					</select>
				</p>
				<?php
			}

			if (!empty($this->sizes)) {
				?>
				<p class="techalgospotlight-widget-socials-size">
					<label for="widget-socials-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-size">
						<?php esc_html_e('Size', 'techalgospotlight'); ?>:
					</label>
					<select id="widget-socials-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-size"
						name="widget-socials[<?php echo esc_attr($this->number); ?>][size]" data-option-name="size">
						<?php foreach ($this->sizes as $key => $value) { ?>
							<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $this->values['size'], true); ?>>
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
