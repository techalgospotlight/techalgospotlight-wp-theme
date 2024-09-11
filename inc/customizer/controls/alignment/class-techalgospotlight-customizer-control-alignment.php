<?php
/**
 * techalgospotlight Customizer custom background control class.
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

if (!class_exists('techalgospotlight_Customizer_Control_Alignment')):
	/**
	 * techalgospotlight Customizer custom background control class.
	 */
	class techalgospotlight_Customizer_Control_Alignment extends techalgospotlight_Customizer_Control
	{

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'techalgospotlight-alignment';

		/**
		 * Set the default typography options.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Default parent's arguments.
		 */
		public function __construct($manager, $id, $args = array())
		{
			parent::__construct($manager, $id, $args);

			$this->strings = wp_parse_args(
				isset($args['strings']) ? $args['strings'] : array(),
				array(
					'top-left' => __('Top Left', 'techalgospotlight'),
					'top' => __('Top', 'techalgospotlight'),
					'top-right' => __('Top Right', 'techalgospotlight'),
					'left' => __('Left', 'techalgospotlight'),
					'center' => __('Center', 'techalgospotlight'),
					'right' => __('Right', 'techalgospotlight'),
					'bottom-left' => __('Bottom Left', 'techalgospotlight'),
					'bottom' => __('Bottom', 'techalgospotlight'),
					'bottom-right' => __('Bottom Right', 'techalgospotlight'),
				)
			);

			$this->icons = wp_parse_args(
				isset($args['icons']) ? $args['icons'] : array(),
				array(
					'top-left' => 'dashicons dashicons-arrow-left-alt',
					'top' => 'dashicons dashicons-arrow-up-alt',
					'top-right' => 'dashicons dashicons-arrow-right-alt',
					'left' => 'dashicons dashicons-arrow-left-alt',
					'center' => 'alignment-position-center-icon',
					'right' => 'dashicons dashicons-arrow-right-alt',
					'bottom-left' => 'dashicons dashicons-arrow-left-alt',
					'bottom' => 'dashicons dashicons-arrow-down-alt',
					'bottom-right' => 'dashicons dashicons-arrow-right-alt',
				)
			);

			// Alignment choices.
			$default_choices = array(
				'top-left' => true,
				'top' => true,
				'top-right' => true,
				'left' => true,
				'center' => true,
				'right' => true,
				'bottom-left' => true,
				'bottom' => true,
				'bottom-right' => true,
			);

			if (isset($args['choices'])) {

				if ('horizontal' === $args['choices']) {

					$this->choices = array(
						'top-left' => false,
						'top' => false,
						'top-right' => false,
						'left' => true,
						'center' => true,
						'right' => true,
						'bottom-left' => false,
						'bottom' => false,
						'bottom-right' => false,
					);
				} elseif ('vertical' === $args['choices']) {

					$this->choices = array(
						'top-left' => false,
						'top' => true,
						'top-right' => false,
						'left' => false,
						'center' => true,
						'right' => false,
						'bottom-left' => false,
						'bottom' => true,
						'bottom-right' => false,
					);
				} elseif (is_array($args['choices'])) {

					$this->choices = array(
						'top-left' => false,
						'top' => false,
						'top-right' => false,
						'left' => false,
						'center' => false,
						'right' => false,
						'bottom-left' => false,
						'bottom' => false,
						'bottom-right' => false,
					);

					foreach ($args['choices'] as $choice) {
						$this->choices[$choice] = true;
					}
				}
			} else {
				$this->choices = $default_choices;
			}

			foreach ($this->choices as $key => $value) {
				$this->choices[$key] = true === $value ? '' : 'disabled';
			}
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();

			$this->json['choices'] = $this->choices;
			$this->json['l10n'] = $this->strings;
			$this->json['icons'] = $this->icons;
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
			<div class="techalgospotlight-alignment-wrapper techalgospotlight-control-wrapper">

				<# if ( data.label ) { #>
					<div class="techalgospotlight-control-heading customize-control-title techalgospotlight-field">
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

						<div class="techalgospotlight-alignment-control">

							<# if ( ! data.choices['top-left'] && ! data.choices['top'] && ! data.choices['top-right'] ) { #>
								<div class="button-group techalgospotlight-top">
									<label class="techalgospotlight-left {{{ data.choices['top-left']}}}">
										<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio" value="top-left"
											{{{ data.link }}}>
										<span class="button display-options position"><span class="{{ data.icons['top-left'] }}"
												aria-hidden="true"></span></span>
										<span class="screen-reader-text">{{{ data.l10n['top-left'] }}}</span>
									</label>

									<label class="techalgospotlight-center {{{ data.choices['top']}}}">
										<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio" value="top" {{{
											data.link }}}>
										<span class="button display-options position"><span class="{{ data.icons['top'] }}"
												aria-hidden="true"></span></span>
										<span class="screen-reader-text">{{{ data.l10n['top'] }}}</span>
									</label>

									<label class="techalgospotlight-right {{{ data.choices['top-right']}}}">
										<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio" value="top-right"
											{{{ data.link }}}>
										<span class="button display-options position"><span class="{{ data.icons['top-right'] }}"
												aria-hidden="true"></span></span>
										<span class="screen-reader-text">{{{ data.l10n['top-right'] }}}</span>
									</label>
								</div>
								<# } #>

									<# if ( ! data.choices['left'] && ! data.choices['center'] && ! data.choices['right'] ) { #>
										<div class="button-group techalgospotlight-middle">
											<label class="techalgospotlight-left {{{ data.choices['left']}}}">
												<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio" value="left"
													{{{ data.link }}}>
												<span class="button display-options position"><span class="{{ data.icons['left'] }}"
														aria-hidden="true"></span></span>
												<span class="screen-reader-text">{{{ data.l10n['left'] }}}</span>
											</label>

											<label class="techalgospotlight-center {{{ data.choices['center']}}}">
												<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio" value="center"
													{{{ data.link }}}>
												<span class="button display-options position"><span class="{{ data.icons['center'] }}"
														aria-hidden="true"></span></span>
												<span class="screen-reader-text">{{{ data.l10n['center'] }}}</span>
											</label>

											<label class="techalgospotlight-right {{{ data.choices['right']}}}">
												<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio" value="right"
													{{{ data.link }}}>
												<span class="button display-options position"><span class="{{ data.icons['right'] }}"
														aria-hidden="true"></span></span>
												<span class="screen-reader-text">{{{ data.l10n['right'] }}}</span>
											</label>
										</div>
										<# } #>

											<# if ( ! data.choices['bottom-left'] && ! data.choices['bottom'] && ! data.choices['bottom-right'] ) {
												#>
												<div class="button-group techalgospotlight-bottom">
													<label class="techalgospotlight-left {{{ data.choices['bottom-left']}}}">
														<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio"
															value="bottom-left" {{{ data.link }}}>
														<span class="button display-options position"><span class="{{ data.icons['bottom-left'] }}"
																aria-hidden="true"></span></span>
														<span class="screen-reader-text">{{{ data.l10n['bottom-left'] }}}</span>
													</label>

													<label class="techalgospotlight-center {{{ data.choices['bottom']}}}">
														<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio"
															value="bottom" {{{ data.link }}}>
														<span class="button display-options position"><span class="{{ data.icons['bottom'] }}"
																aria-hidden="true"></span></span>
														<span class="screen-reader-text">{{{ data.l10n['bottom'] }}}</span>
													</label>

													<label class="techalgospotlight-right {{{ data.choices['bottom-right']}}}">
														<input class="screen-reader-text" name="{{ data.id }}-alignment-position" type="radio"
															value="bottom-right" {{{ data.link }}}>
														<span class="button display-options position"><span class="{{ data.icons['bottom-right'] }}"
																aria-hidden="true"></span></span>
														<span class="screen-reader-text">{{{ data.l10n['bottom-right'] }}}</span>
													</label>
												</div>
												<# } #>

						</div>

			</div><!-- END .techalgospotlight-control-wrapper -->
			<?php
		}
	}
endif;
