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

if (!class_exists('techalgospotlight_Customizer_Widget_Advertisements')):

	/**
	 * techalgospotlight Customizer widget class
	 */
	class techalgospotlight_Customizer_Widget_Advertisements extends techalgospotlight_Customizer_Widget
	{

		public $display_areas = array();
		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @param array $args An array of the values for this widget.
		 */
		public function __construct($args = array())
		{
			$args['name'] = __('Advertisement', 'techalgospotlight');
			$args['icon'] = 'dashicons dashicons-format-image';
			$args['type'] = 'advertisements';
			$values = array(
				'image_id' => '',
				'url' => '',
				'target' => '_self',
				'display_area' => array(),
				'visibility' => 'all',
			);

			$args['values'] = isset($args['values']) ? wp_parse_args($args['values'], $values) : $values;

			$args['values']['image_id'] = absint($args['values']['image_id']);

			$args['values']['url'] = esc_url_raw($args['values']['url']);
			$args['values']['target'] = sanitize_text_field($args['values']['target']);

			$args['values']['display_area'] = array_map('sanitize_text_field', $args['values']['display_area']);
			$args['values']['visibility'] = isset($args['values']['visibility']) ? sanitize_text_field($args['values']['visibility']) : 'hide-mobile-tablet';

			parent::__construct($args);

			$this->display_areas = isset($args['display_areas']) ? $args['display_areas'] : array();
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


			<div class="banner-controls" id="bh-banner-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>">

				<div class="banner-wrapper">
					<p>
						<input type="hidden"
							id="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-image_id"
							name="widget-ad[<?php echo esc_attr($this->number); ?>][image_id]" data-option-name="image_id"
							value="<?php echo esc_attr(json_encode($this->values['image_id'])); ?>" />

						<button
							class="button button-primary widget-media-upload <?php echo $this->values['image_id'] == 0 ? 'show' : 'hide'; ?>"
							data-widget-id="<?php echo esc_attr($this->id); ?>"
							data-widget-number="<?php echo esc_attr($this->number); ?>"><?php esc_html_e('Upload Banner', 'techalgospotlight'); ?></button>

						<span class="banner-preview">
							<?php
							if ($this->values['image_id'] !== 0) {
								echo wp_get_attachment_image($this->values['image_id'], 'large');
							}
							?>
						</span>
						<button
							class="button button-secondary remove-image <?php echo $this->values['image_id'] !== 0 ? 'show' : 'hide'; ?>">&times;</button>
					</p>
				</div>

				<!-- URL -->
				<p>
					<label
						for="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-url"><?php esc_html_e('URL', 'techalgospotlight'); ?>:</label>
					<input type="text" id="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-url"
						name="widget-ad[<?php echo esc_attr($this->number); ?>][url]" data-option-name="url"
						value="<?php echo esc_attr($this->values['url']); ?>"
						placeholder="<?php esc_attr_e('Banner URL', 'techalgospotlight'); ?>" />
				</p>

				<!-- Target -->
				<p>
					<label
						for="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target"><?php esc_html_e('Open link in', 'techalgospotlight'); ?>:</label>
					<span class="buttonset">
						<input class="switch-input screen-reader-text" type="radio" value="_self"
							name="widget-ad[<?php echo esc_attr($this->number); ?>][target]"
							id="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_self" <?php checked('_self', $this->values['target'], true); ?> data-option-name="target">
						<label class="switch-label"
							for="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_self">
							<?php esc_html_e('Same Tab', 'techalgospotlight'); ?>
						</label>
						</input>
						<input class="switch-input screen-reader-text" type="radio" value="_blank"
							name="widget-ad[<?php echo esc_attr($this->number); ?>][target]"
							id="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_blank" <?php checked('_blank', $this->values['target'], true); ?> data-option-name="target">
						<label class="switch-label"
							for="widget-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>-target-_blank">
							<?php esc_html_e('New Tab', 'techalgospotlight'); ?>
						</label>
						</input>
					</span>
				</p>
			</div>

			<div id="bh-ad-<?php echo esc_attr($this->id); ?>-<?php echo esc_attr($this->number); ?>"
				class="techalgospotlight-checkbox-group-control techalgospotlight-ad-display-area">
				<div class="techalgospotlight-control-heading customize-control-title techalgospotlight-field">
					<span><?php esc_html_e('Show on:', 'techalgospotlight'); ?> </span>
				</div>
				<?php
				foreach ($this->display_areas as $key => $value) {
					$is_match = in_array($key, $this->values['display_area']);
					?>
					<p>
						<label class="techalgospotlight-checkbox">
							<input <?php echo $is_match ? 'checked' : ''; ?> type="checkbox" data-input-type="multiple"
								data-option-name="display_area" name="widget-ad[<?php echo esc_attr($this->number); ?>][display_area]"
								value="<?php echo esc_attr($key); ?>">
							<span class="techalgospotlight-label"><?php echo esc_html($value); ?></span>
						</label>
					</p>
				<?php } ?>

			</div>
			<?php
		}
	}
endif;
