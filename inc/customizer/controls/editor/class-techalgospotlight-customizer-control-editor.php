<?php
/**
 * Customizer editor control class.
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
/**
 * techalgospotlight Customizer editor control class.
 */
class techalgospotlight_Customizer_Control_Editor extends techalgospotlight_Customizer_Control
{
	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'techalgospotlight-editor';

	/**
	 * Add support for palettes to be passed in.
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 *
	 * @var string
	 */
	public $mod = '';
	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue()
	{
		parent::enqueue();
		if (!class_exists('_WP_Editors')) {
			require ABSPATH . WPINC . '/class-wp-editor.php';
		}
		add_action('customize_controls_print_footer_scripts', array(__CLASS__, 'enqueue_editor'), 2);
		add_action('customize_controls_print_footer_scripts', array('_WP_Editors', 'editor_js'), 50);
		add_action('customize_controls_print_footer_scripts', array('_WP_Editors', 'enqueue_scripts'), 1);
	}
	/**
	 * Enqueue/initialize edior scripts.
	 *
	 * @return void
	 */
	public static function enqueue_editor()
	{
		if (!isset($GLOBALS['__wp_mce_editor__']) || !$GLOBALS['__wp_mce_editor__']) {
			$GLOBALS['__wp_mce_editor__'] = true; ?>
			<script id="_wp-mce-editor-tpl" type="text/html">
																<?php wp_editor('', '__wp_mce_editor__'); ?>
															</script>
			<?php
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
		$this->json['mod'] = strtolower($this->mod);
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
		<div class="techalgospotlight-control-wrapper wp-js-editor techalgospotlight-textarea-wrapper">
			<label>
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
						<# var mod=data.mod; if(mod !='html' ){ mod='tmce' ; } #>
							<textarea class="wp-js-editor-textarea large-text" data-editor-mod="{{ mod }}" {{{ data.link
								}}}>{{ data.value }}</textarea>
			</label>
		</div><!-- END .techalgospotlight-control-wrapper -->
		<?php
	}
}
