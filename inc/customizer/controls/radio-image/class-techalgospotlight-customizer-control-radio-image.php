<?php
/**
 * techalgospotlight Customizer radio image control class.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @see         https://github.com/aristath/kirki
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('techalgospotlight_Customizer_Control_Radio_Image')):
	/**
	 * techalgospotlight Customizer custom heading control class.
	 */
	class techalgospotlight_Customizer_Control_Radio_Image extends techalgospotlight_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'techalgospotlight-radio-image';

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();

			$this->json['choices'] = $this->choices;
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
			<div class="techalgospotlight-control-wrapper">

				<# if ( data.label ) { #>
					<div class="customize-control-title techalgospotlight-field">
						<span>{{{ data.label }}}</span>

						<# if ( data.description ) { #>
							<i class="techalgospotlight-info-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2"
									stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle">
									<circle cx="12" cy="12" r="10"></circle>
									<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
									<line x1="12" y1="17" x2="12" y2="17"></line>
								</svg>
								<span class="techalgospotlight-tooltip">{{{ data.description }}}</span>
							</i>
							<# } #>
					</div>
					<# } #>

						<div class="techalgospotlight-radio-image-choices">
							<# for ( key in data.choices ) { #>
								<input {{{ data.inputAttrs }}} class="techalgospotlight-radio-image" type="radio" value="{{ key }}"
									name="techalgospotlight_radio_group-{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}}<# if (
									data.value===key ) { #> checked="checked"<# } #>>
									<label for="{{ data.id }}-{{ key }}">
										<img src="{{ data.choices[ key ].image }}" />
										<span class="techalgospotlight-image-title techalgospotlight-tooltip small-tooltip">{{ data.choices[ key
											].title }}</span>
									</label>
									</input>
									<# } #>
						</div><!-- END .techalgospotlight-radio-image-choices -->

			</div><!-- END .techalgospotlight-control-wrapper -->
			<?php
		}
	}
endif;
