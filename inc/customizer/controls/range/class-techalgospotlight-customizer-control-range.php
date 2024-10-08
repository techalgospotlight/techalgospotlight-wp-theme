<?php
/**
 * techalgospotlight Customizer custom heading control class.
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('techalgospotlight_Customizer_Control_Range')):
	/**
	 * techalgospotlight Customizer custom heading control class.
	 */
	class techalgospotlight_Customizer_Control_Range extends techalgospotlight_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'techalgospotlight-range';

		/**
		 * Minimum range value.
		 *
		 * @since 1.0.0
		 * @var integer
		 */
		public $min = 0;

		/**
		 * Maximum range value.
		 *
		 * @since 1.0.0
		 * @var integer
		 */
		public $max = 1000;

		/**
		 * Range step value.
		 *
		 * @since 1.0.0
		 * @var integer
		 */
		public $step = 1;

		/**
		 * Range unit.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $unit = array();

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();

			$this->json['min'] = $this->min;
			$this->json['max'] = $this->max;
			$this->json['step'] = $this->step;
			$this->json['unit'] = $this->unit;
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue()
		{
			parent::enqueue();
			$base_font_size = techalgospotlight()->options->get('techalgospotlight_html_base_font_size');
			$bfs = ($base_font_size['desktop'] * 16) / 100;
			wp_localize_script(
				'techalgospotlight-range-js',
				'range_obj',
				array(
					'base_font' => $bfs,
				)
			);
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
			<div class="techalgospotlight-range-wrapper techalgospotlight-control-wrapper">

				<# if ( data.label ) { #>
					<!-- Control label -->
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

						<div class="techalgospotlight-control-wrap" data-unit="{{ data.value['unit'] }}">

							<# if ( ! _.isEmpty( data.responsive ) ) { #>

								<# _.each( data.responsive, function( settings, device ){ #>

									<div class="{{ device }} control-responsive">

										<input type="range" {{{ data.inputAttrs }}} value="{{ data.value[ device ] }}" min="{{ data.min }}"
											max="{{ data.max }}" step="{{ data.step }}" data-device="{{ device }}" />

										<span class="techalgospotlight-reset-range" data-reset_value="{{ data.default[ device ] }}"
											data-reset_unit="{{ data.default[ 'unit'] }}">
											<span class="dashicons dashicons-image-rotate"></span>
										</span>

										<input type="number" {{{ data.inputAttrs }}} class="techalgospotlight-range-input"
											value="{{ data.value[ device ] }}" />
									</div>

									<# } ); #>

										<# } else { #>

											<# if ( _.isObject( data.value ) ) { #>
												<input type="range" {{{ data.inputAttrs }}} value="{{ data.value.value }}" min="{{ data.min }}"
													max="{{ data.max }}" step="{{ data.step }}" />
												<# } else { #>
													<input type="range" {{{ data.inputAttrs }}} value="{{ data.value }}" min="{{ data.min }}"
														max="{{ data.max }}" step="{{ data.step }}" />
													<# } #>

														<span class="techalgospotlight-reset-range"
															data-reset_value="{{ data.default['value'] ? data.default['value'] : data.default }}"
															data-reset_unit="{{ data.default[ 'unit'] }}">
															<span class="dashicons dashicons-image-rotate"></span>
														</span>

														<# if ( _.isObject( data.value ) ) { #>
															<input type="number" {{{ data.inputAttrs }}} class="techalgospotlight-range-input"
																value="{{ data.value.value }}" />
															<# } else { #>
																<input type="number" {{{ data.inputAttrs }}} class="techalgospotlight-range-input"
																	value="{{ data.value }}" />
																<# } #>

																	<# } #>
						</div>
			</div>
			<?php
		}
	}
endif;
