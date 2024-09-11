<?php
/**
 * techalgospotlight Customizer custom heading control class.
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

if (!class_exists('techalgospotlight_Customizer_Control_Heading')):
	/**
	 * techalgospotlight Customizer custom heading control class.
	 */
	class techalgospotlight_Customizer_Control_Heading extends techalgospotlight_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'techalgospotlight-heading';

		/**
		 * Top spacer.
		 *
		 * @since  1.0.0
		 * @var    boolean
		 */
		public $space = true;

		/**
		 * Heading style. Possible options are: regular-heading and sub-heading.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public $style = 'regular-heading';

		/**
		 * Toggler.
		 *
		 * @since  1.0.0
		 * @var    boolean
		 */
		public $toggle = true;

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();

			$this->json['space'] = (true === $this->space || 'true' === $this->space) ? ' top-spacer' : '';
			$this->json['toggle'] = (true === $this->toggle || 'true' === $this->toggle) ? ' toggle-heading' : '';
			$this->json['style'] = $this->style;
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
			<# if ( data.space ) { #>
				<div class="techalgospotlight-heading-top-space {{ data.style }}"></div>
				<# } #>
					<div
						class="techalgospotlight-heading-wrapper techalgospotlight-control-wrapper{{ data.space }}{{ data.toggle }} {{ data.style }}">

						<# if ( data.label ) { #>
							<span class="techalgospotlight-control-heading">{{{ data.label }}}</span>
							<# } #>

								<# if ( data.description ) { #>
									<i class="techalgospotlight-info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
											viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
											class="feather feather-help-circle">
											<circle cx="12" cy="12" r="10"></circle>
											<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
											<line x1="12" y1="17" x2="12" y2="17"></line>
										</svg><span class="techalgospotlight-tooltip top-right-tooltip">{{{ data.description }}}</span></i>
									<# } #>

										<# if ( data.toggle ) { #>
											<span class="techalgospotlight-heading-toggle">
												<input type="checkbox" id="{{ data.id }}" name="{{ data.id }}" <# if ( data.value ) { #>
												checked="checked" <# } #>>

													<label for="{{ data.id }}" aria-hidden="true">
													</label>
											</span>
											<# } #>

					</div><!-- END .techalgospotlight-heading-wrapper -->
					<?php
		}

	}
endif;
