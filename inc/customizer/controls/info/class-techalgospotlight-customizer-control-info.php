<?php
/**
 * techalgospotlight Customizer info control class.
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

if (!class_exists('techalgospotlight_Customizer_Control_Info')):
	/**
	 * techalgospotlight Customizer info control class.
	 */
	class techalgospotlight_Customizer_Control_Info extends techalgospotlight_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'techalgospotlight-info';

		/**
		 * Custom URL.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public $url = '';

		/**
		 * Link target.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public $target = '_blank';

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue()
		{

			// Script debug.
			$techalgospotlight_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

			// Control type.
			$techalgospotlight_type = str_replace('techalgospotlight-', '', $this->type);

			/**
			 * Enqueue control stylesheet
			 */
			wp_enqueue_style(
				'techalgospotlight-' . $techalgospotlight_type . '-control-style',
				techalgospotlight_THEME_URI . '/inc/customizer/controls/' . $techalgospotlight_type . '/' . $techalgospotlight_type . $techalgospotlight_suffix . '.css',
				false,
				techalgospotlight_THEME_VERSION,
				'all'
			);
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();

			$this->json['url'] = $this->url;
			$this->json['target'] = $this->target;
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 */
		protected function content_template()
		{
			?>
			<div class="techalgospotlight-info-wrapper techalgospotlight-control-wrapper">

				<# if ( data.label ) { #>
					<span class="techalgospotlight-control-heading customize-control-title techalgospotlight-field">{{{ data.label
						}}}</span>
					<# } #>

						<# if ( data.description ) { #>
							<div
								class="description customize-control-description techalgospotlight-field techalgospotlight-info-description">
								{{{ data.description }}}</div>
							<# } #>

								<a href="{{ data.url }}" class="button button-primary" target="{{ data.target }}"
									rel="noopener noreferrer"><?php esc_html_e('Learn More', 'techalgospotlight'); ?></a>

			</div><!-- END .techalgospotlight-control-wrapper -->
			<?php
		}

	}
endif;
