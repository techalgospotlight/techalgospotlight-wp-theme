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

if (!class_exists('techalgospotlight_Customizer_Widget_Button')):

	/**
	 * techalgospotlight Customizer widget class
	 */
	class techalgospotlight_Customizer_Widget_Button extends techalgospotlight_Customizer_Widget
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
				'text' => '',
				'url' => '',
				'target' => '_self',
				'class' => '',
				'style' => '',
				'visibility' => 'all',
			);

			$args['values'] = isset($args['values']) ? wp_parse_args($args['values'], $values) : $values;

			$args['values']['text'] = wp_kses($args['values']['text'], techalgospotlight_get_allowed_html_tags());
			$args['values']['url'] = esc_url_raw($args['values']['url']);
			$args['values']['target'] = sanitize_text_field($args['values']['target']);
			$args['values']['class'] = sanitize_text_field($args['values']['class']);
			$args['values']['style'] = sanitize_text_field($args['values']['style']);
			$args['values']['visibility'] = isset($args['values']['visibility']) ? sanitize_text_field($args['values']['visibility']) : 'hide-mobile-tablet';

			parent::__construct($args);

			$this->name = __('Button', 'techalgospotlight');
			$this->description = __('A button with custom link.', 'techalgospotlight');
			$this->icon = 'dashicons dashicons-admin-links';
			$this->type = 'button';
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
			?>
			<!-- Text -->
			<p>
				<label
					for="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-text"><?php esc_html_e('Text', 'techalgospotlight'); ?>:</label>
				<input type="text" id="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-text"
					name="widget-button[<?php echo esc_attr($this->number); ?>][text]" data-option-name="text"
					value="<?php echo esc_html($this->values['text']); ?>"
					placeholder="<?php esc_attr_e('Button Text', 'techalgospotlight'); ?>" />
			</p>

			<!-- URL -->
			<p>
				<label
					for="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-url"><?php esc_html_e('URL', 'techalgospotlight'); ?>:</label>
				<input type="text" id="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-url"
					name="widget-button[<?php echo esc_attr($this->number); ?>][url]" data-option-name="url"
					value="<?php echo esc_html($this->values['url']); ?>"
					placeholder="<?php esc_attr_e('Button URL', 'techalgospotlight'); ?>" />
			</p>

			<!-- Target -->
			<p>
				<label
					for="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target"><?php esc_html_e('Open link in', 'techalgospotlight'); ?>:</label>
				<span class="buttonset">
					<input class="switch-input screen-reader-text" type="radio" value="_self"
						name="widget-button[<?php echo esc_attr($this->number); ?>][target]"
						id="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_self" <?php checked('_self', $this->values['target'], true); ?> data-option-name="target">
					<label class="switch-label"
						for="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_self">
						<?php esc_html_e('Same Tab', 'techalgospotlight'); ?>
					</label>
					</input>
					<input class="switch-input screen-reader-text" type="radio" value="_blank"
						name="widget-button[<?php echo esc_attr($this->number); ?>][target]"
						id="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_blank" <?php checked('_blank', $this->values['target'], true); ?> data-option-name="target">
					<label class="switch-label"
						for="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_blank">
						<?php esc_html_e('New Tab', 'techalgospotlight'); ?>
					</label>
					</input>
				</span>
			</p>

			<?php if (!empty($this->styles)) { ?>
				<!-- Styles -->
				<p class="techalgospotlight-widget-button-style">
					<label for="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-style">
						<?php esc_html_e('Style', 'techalgospotlight'); ?>:
					</label>
					<select id="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-style"
						name="widget-button[<?php echo esc_attr($this->number); ?>][style]" data-option-name="style">
						<?php foreach ($this->styles as $key => $value) { ?>
							<option value="<?php echo esc_attr($key); ?>" <?php selected($key, $this->values['style'], true); ?>>
								<?php echo esc_html($value); ?>
							</option>
						<?php } ?>
					</select>
				</p>
			<?php } ?>

			<!-- Class -->
			<p>
				<label
					for="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-class"><?php esc_html_e('Additional class', 'techalgospotlight'); ?>:</label>
				<input type="text" id="widget-button-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-class"
					name="widget-button[<?php echo esc_attr($this->number); ?>][class]" data-option-name="class"
					value="<?php echo esc_html($this->values['class']); ?>" />
			</p>
			<?php
		}
	}
endif;
