<?php
/**
 * techalgospotlight Customizer custom toggle control class.
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

if (!class_exists('techalgospotlight_Customizer_Control_Toggle')):
	/**
	 * techalgospotlight Customizer custom toggle control class.
	 */
	class techalgospotlight_Customizer_Control_Toggle extends techalgospotlight_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'techalgospotlight-toggle';

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
			<div class="techalgospotlight-toggle-wrapper techalgospotlight-control-wrapper">

				<# if ( data.label ) { #>
					<div class="customize-control-title">
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

						<div id="input_{{ data.id }}" class="techalgospotlight-toggle">

							<input type="checkbox" id="{{ data.id }}" name="{{ data.id }}" <# if ( data.value ) { #> checked="checked" <# }
								#>>

								<label for="{{ data.id }}">
									<span class="techalgospotlight-toggle-switch"></span>
								</label>
						</div><!-- END .techalgospotlight-toggle -->

			</div><!-- END .techalgospotlight-toggle-wrapper -->
			<?php
		}

	}
endif;
