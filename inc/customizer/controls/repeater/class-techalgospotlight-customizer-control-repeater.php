<?php
/**
 * Customizer Repeatable control class.
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

if (!class_exists('techalgospotlight_Customizer_Control_Repeater')):

	/**
	 * Customizer Repeatable control class.
	 */
	class techalgospotlight_Customizer_Control_Repeater extends techalgospotlight_Customizer_Control
	{
		/**
		 * The type of customize control being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'techalgospotlight-repeater';

		/**
		 * Repreater fields
		 *
		 * @var array
		 */
		public $fields = array();

		/**
		 * Live title id to update repeater heading
		 *
		 * @var string
		 */
		public $live_title_id = null;

		/**
		 * Title format
		 *
		 * @var string
		 */
		public $title_format = null;
		/**
		 * Defined values
		 *
		 * @var array
		 */
		public $defined_values = null;
		/**
		 * Key Id
		 *
		 * @var string
		 */
		public $id_key = null;
		/**
		 * Limited message
		 *
		 * @var string
		 */
		public $limited_msg = null;
		/**
		 * Add new button text
		 *
		 * @var string
		 */
		public $add_text = null;

		/**
		 * Set the default options.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Default parent's arguments.
		 */
		public function __construct($manager, $id, $args = array())
		{
			parent::__construct($manager, $id, $args);
			if (empty($args['fields']) || !is_array($args['fields'])) {
				$args['fields'] = array();
			}
			foreach ($args['fields'] as $key => $op) {
				$args['fields'][$key]['id'] = $key;
				if (!isset($op['value'])) {
					if (isset($op['default'])) {
						$args['fields'][$key]['value'] = $op['default'];
					} else {
						$args['fields'][$key]['value'] = '';
					}
				}
			}

			$this->fields = $args['fields'];
			$this->live_title_id = isset($args['live_title_id']) ? $args['live_title_id'] : false;
			$this->defined_values = isset($args['defined_values']) ? $args['defined_values'] : false;
			$this->id_key = isset($args['id_key']) ? $args['id_key'] : false;
			if (isset($args['title_format']) && '' !== $args['title_format']) {
				$this->title_format = $args['title_format'];
			} else {
				$this->title_format = '';
			}
			if (isset($args['limited_msg']) && '' !== $args['limited_msg']) {
				$this->limited_msg = $args['limited_msg'];
			} else {
				$this->limited_msg = '';
			}
			if (!isset($args['max_item'])) {
				$args['max_item'] = 0;
			}
			if (!isset($args['allow_unlimited']) || false !== $args['allow_unlimited']) {
				$this->max_item = apply_filters('techalgospotlight_reepeatable_max_item', absint($args['max_item']));
			} else {
				$this->max_item = absint($args['max_item']);
			}
			$this->changeable = isset($args['changeable']) && 'no' === $args['changeable'] ? 'no' : 'yes';
			$this->default_empty_title = isset($args['default_empty_title']) && '' !== $args['default_empty_title'] ? $args['default_empty_title'] : esc_html__('Item', 'techalgospotlight');

			add_action('customize_controls_print_footer_scripts', array(__CLASS__, 'item_tpl'), 66);
			add_action('customize_controls_enqueue_scripts', array($this, 'techalgospotlight_customize_controls_enqueue_scripts'));
		}

		/**
		 * Merge fields data
		 *
		 * @param array $array_value field values.
		 * @param array $array_default default field values.
		 * @return array
		 */
		public function merge_data($array_value, $array_default)
		{
			if (!$this->id_key) {
				return $array_value;
			}
			if (!is_array($array_value)) {
				$array_value = array();
			}
			if (!is_array($array_default)) {
				$array_default = array();
			}
			$new_array = array();
			foreach ($array_value as $k => $a) {
				if (is_array($a)) {
					if (isset($a[$this->id_key]) && '' !== $a[$this->id_key]) {
						$new_array[$a[$this->id_key]] = $a;
					} else {
						$new_array[$k] = $a;
					}
				}
			}
			foreach ($array_default as $k => $a) {
				if (is_array($a) && isset($a[$this->id_key])) {
					if (!isset($new_array[$a[$this->id_key]])) {
						$new_array[$a[$this->id_key]] = $a;
					}
				}
			}
			return array_values($new_array);
		}

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json()
		{
			parent::to_json();
			$value = $this->value();
			if (is_string($value)) {
				$value = json_decode($value, true);
			}
			if (empty($value)) {
				$value = $this->defined_values;
			} elseif (is_array($this->defined_values) && !empty($this->defined_values)) {
				$value = $this->merge_data($value, $this->defined_values);
			}

			$this->json['live_title_id'] = $this->live_title_id;
			$this->json['title_format'] = $this->title_format;
			$this->json['max_item'] = $this->max_item;
			$this->json['limited_msg'] = $this->limited_msg;
			$this->json['changeable'] = $this->changeable;
			$this->json['default_empty_title'] = $this->default_empty_title;
			$this->json['add_text'] = $this->add_text ?? __('Add new item', 'techalgospotlight');
			$this->json['value'] = $value;
			$this->json['id_key'] = $this->id_key;
			$this->json['fields'] = $this->fields;

			$this->json['l10n'] = array(
				'image' => array(
					'placeholder' => __('No image selected', 'techalgospotlight'),
					'less' => __('Less Settings', 'techalgospotlight'),
					'more' => __('Advanced', 'techalgospotlight'),
					'select_image' => __('Select Image', 'techalgospotlight'),
					'use_image' => __('Use This Image', 'techalgospotlight'),
				),
			);

		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue()
		{

			parent::enqueue();

			// Script debug.
			$techalgospotlight_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

			// Enqueue background image stylesheet.
			if (in_array('button_set', array_values(array_column($this->fields, 'type')), true)) {
				wp_enqueue_style(
					'techalgospotlight-background-alignment-style',
					techalgospotlight_THEME_URI . '/inc/customizer/controls/alignment/alignment' . $techalgospotlight_suffix . '.css',
					false,
					techalgospotlight_THEME_VERSION,
					'all'
				);
			}

			// Enqueue background image stylesheet.
			if (in_array('link', array_values(array_column($this->fields, 'type')), true)) {
				wp_enqueue_script('wplink');
				wp_enqueue_style('editor-buttons');
			}

			// Enqueue background image stylesheet.
			if (in_array('background', array_values(array_column($this->fields, 'type')), true)) {
				wp_enqueue_style(
					'techalgospotlight-background-control-style',
					techalgospotlight_THEME_URI . '/inc/customizer/controls/background/background' . $techalgospotlight_suffix . '.css',
					false,
					techalgospotlight_THEME_VERSION,
					'all'
				);
			}
		}

		/**
		 * Item template to for repeatable
		 *
		 * @return void
		 */
		public static function item_tpl()
		{
			?>
			<script type="text/html" id="repeatable-js-item-tpl">
															<?php self::js_item(); ?>
														</script>
			<?php
		}

		/**
		 * Render the control to be displayed in the Customizer.
		 */
		public function content_template()
		{
			?>

			<# if ( data.label ) { #>
				<div
					class="techalgospotlight-control-heading customize-control-title techalgospotlight-field techalgospotlight-control-wrapper">
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
				</div>
				<# } #>
					<input data-hidden-value type="hidden" value="" {{{ data.link }}} />
					<div class="form-data">
						<ul class="list-repeatable"></ul>
					</div>
					<div class="repeatable-actions">
						<span class="button-secondary add-new-repeat-item">
							{{data.add_text}}
						</span>
					</div>

					<?php
		}

		/**
		 * Repeatable field items.
		 *
		 * @return void
		 */
		public static function js_item()
		{
			?>

					<li class="repeatable-customize-control">
						<div class="widget">
							<div class="widget-top">
								<div class="widget-title-action">
									<a class="widget-action" href="#"></a>
								</div>
								<div class="widget-title">
									<h4 class="live-title"><?php esc_html_e('Item', 'techalgospotlight'); ?></h4>
								</div>
							</div>
							<div class="widget-inside">
								<div class="form">
									<div class="widget-content">
										<# var cond_v; #>
											<# for ( i in data ) { #>
												<# if ( ! data.hasOwnProperty( i ) ) continue; #>
													<# field=data[i]; #>
														<# if ( ! field.type ) continue; #>
															<# if ( field.type ){ #>
																<# if ( ! _.isEmpty( field.required ) ) { #>
																	<div data-field-id="{{ field.id }}"
																		class="field--item conditionize item item-{{ field.type }} item-{{ field.id }}"
																		data-cond="{{ JSON.stringify( field.required ) }}">
																		<# } else { #>
																			<div data-field-id="{{ field.id }}"
																				class="field--item item item-{{ field.type }} item-{{ field.id }}">
																				<# } #>
																					<# if ( field.type !=='checkbox' ) { #>
																						<# if ( field.title && field.type !='design-options' ) { #>
																							<div
																								class="techalgospotlight-control-heading techalgospotlight-control-wrapper">
																								<div class="customize-control-title">
																									<span>{{{ field.title }}}</span>

																									<# if ( field.desc ) { #>
																										<i class="techalgospotlight-info-icon">
																											<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
																												viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"
																												stroke-linejoin="round" class="feather feather-help-circle">
																												<circle cx="12" cy="12" r="10"></circle>
																												<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
																												<line x1="12" y1="17" x2="12" y2="17"></line>
																											</svg>
																											<span class="techalgospotlight-tooltip">{{{ field.desc }}}</span>
																										</i>
																										<# } #>
																								</div>
																							</div>
																							<# } #>
																								<# } #>
																									<# if ( field.type==='hidden' ) { #>
																										<input data-live-id="{{ field.id }}" type="hidden"
																											value="{{ field.value }}"
																											data-repeat-name="_items[__i__][{{ field.id }}]" class="">
																										<# } else if ( field.type==='add_by' ) { #>
																											<input data-live-id="{{ field.id }}" type="hidden"
																												value="{{ field.value }}"
																												data-repeat-name="_items[__i__][{{ field.id }}]" class="add_by">
																											<# } else if ( field.type==='text' ) { #>
																												<input data-live-id="{{ field.id }}" type="text"
																													value="{{ field.value }}"
																													data-repeat-name="_items[__i__][{{ field.id }}]" class="">
																												<# } else if ( field.type==='url' ) { #>
																													<input data-live-id="{{ field.id }}" type="url"
																														value="{{ field.value }}"
																														data-repeat-name="_items[__i__][{{ field.id }}]" class="">
																													<# } else if ( field.type==='checkbox' ) { #>
																														<# if ( field.title ) { #>
																															<label class="checkbox-label">
																																<input data-live-id="{{ field.id }}" type="checkbox" <# if (
																																	field.value ) { #> checked="checked" <# } #> value="1"
																																	data-repeat-name="_items[__i__][{{ field.id }}]" class="">
																																	{{ field.title }}</label>
																															<# } #>
																																<# if ( field.desc ) { #>
																																	<p class="field-desc description">{{ field.desc }}</p>
																																	<# } #>
																																		<# } else if ( field.type==='select' ) { #>
																																			<# if ( field.multiple ) { #>
																																				<select data-live-id="{{ field.id }}"
																																					class="select-multiple" multiple="multiple"
																																					data-repeat-name="_items[__i__][{{ field.id }}][]">
																																					<# } else { #>
																																						<select data-live-id="{{ field.id }}"
																																							class="select-one"
																																							data-repeat-name="_items[__i__][{{ field.id }}]">
																																							<# } #>
																																								<# for ( k in field.options ) { #>
																																									<# if ( _.isArray( field.value ) ) { #>
																																										<option <# if ( _.contains( field.value ,
																																											k ) ) { #> selected="selected" <# } #>
																																												value="{{ k }}">{{ field.options[k] }}
																																										</option>
																																										<# } else { #>
																																											<option <# if ( field.value==k ) { #>
																																												selected="selected" <# } #> value="{{
																																													k }}">{{ field.options[k] }}
																																											</option>
																																											<# } #>
																																												<# } #>
																																						</select>
																																						<# } else if ( field.type==='radio' ) { #>
																																							<# for ( k in field.options ) { #>
																																								<# if ( field.options.hasOwnProperty( k ) ) {
																																									#>
																																									<label>
																																										<input data-live-id="{{ field.id }}"
																																											type="radio" <# if ( field.value==k ) {
																																											#> checked="checked" <# } #> value="{{ k
																																											}}" data-repeat-name="_items[__i__][{{
																																											field.id }}]" class="widefat">
																																											{{ field.options[k] }}
																																									</label>
																																									<# } #>
																																										<# } #>
																																											<# } else if(field.type==='link' ) { #>

																																												<div
																																													class="techalgospotlight-field techalgospotlight-field-link<# if ( field.value.url ) { #> -value<# } #> <# if ( field.value.target ) { #> -external<# } #>"
																																													data-name="link" data-type="link">
																																													<div
																																														class="techalgospotlight-input">
																																														<div class="techalgospotlight">

																																															<div
																																																class="techalgospotlight-hidden">
																																																<a class="link-node"
																																																	href="{{ field.value.url }}"
																																																	target="{{ field.value.target }}">{{
																																																	field.value.title }}</a>
																																																<input type="hidden"
																																																	class="input-title widefat"
																																																	data-repeat-name="_items[__i__][{{ field.id }}][title]"
																																																	value="{{ field.value.title }}">
																																																<input type="hidden"
																																																	class="input-url widefat"
																																																	data-repeat-name="_items[__i__][{{ field.id }}][url]"
																																																	value="{{ field.value.url }}"
																																																	data-live-id="{{ field.id }}">
																																																<input type="hidden"
																																																	class="input-target widefat"
																																																	data-repeat-name="_items[__i__][{{ field.id }}][target]"
																																																	value="{{ field.value.target }}">
																																															</div>

																																															<a href="#" class="button"
																																																data-name="add"
																																																target=""><?php esc_html_e('Select Link', 'techalgospotlight'); ?></a>

																																															<div class="link-wrap">
																																																<span class="link-title">{{
																																																	field.value.title }}</span>
																																																<# var
																																																	url=field.value.url?.length>
																																																	25 ?
																																																	field.value.url.substring(0,
																																																	22) + '...' :
																																																	field.value.url #>
																																																	<a class="link-url"
																																																		href="{{ field.value.url }}"
																																																		target="{{ field.value.target }}"
																																																		title="{{ field.value.url }}">{{
																																																		url }}</a>
																																																	<i class="techalgospotlight-icon -link-ext"
																																																		title="Opens in a new window/tab"></i>
																																																	<a class="techalgospotlight-icon -pencil -clear"
																																																		data-name="edit" href="#"
																																																		title="Edit"></a>
																																																	<a class="techalgospotlight-icon -cancel -clear"
																																																		data-name="remove"
																																																		href="#"
																																																		title="Remove"></a>
																																															</div>
																																														</div>
																																													</div>
																																												</div>

																																												<# } else if (
																																													field.type==='button_set' ) { #>
																																													<div
																																														class="techalgospotlight-alignment-control button_set">
																																														<div
																																															class="button-group techalgospotlight-middle">
																																															<# for ( k in field.options ) {
																																																#>
																																																<# if (
																																																	field.options.hasOwnProperty(
																																																	k ) ) { #>
																																																	<label
																																																		class="techalgospotlight-{{ k }}">
																																																		<input
																																																			data-live-id="{{ field.id }}"
																																																			class="screen-reader-text"
																																																			data-repeat-name="_items[__i__][{{ field.id }}]"
																																																			type="radio" <# if (
																																																			field.value==k ) { #>
																																																		checked="checked" <# } #>
																																																			value="{{ k }}" >
																																																			<span
																																																				class="button display-options position">
																																																				<span
																																																					class="{{ field.options[k] }}"
																																																					aria-hidden="true"></span>
																																																			</span>
																																																			<span
																																																				class="screen-reader-text">{{{
																																																				k }}}</span>
																																																	</label>
																																																	<# } #>
																																																		<# } #>
																																														</div>
																																													</div>
																																													<# } else if ( field.type=='color'
																																														|| field.type=='coloralpha' ) { #>
																																														<# if ( field.value !='' ) {
																																															field.value='#' +field.value ; }
																																															#>
																																															<input
																																																data-live-id="{{ field.id }}"
																																																data-show-opacity="true"
																																																type="text"
																																																value="{{ field.value }}"
																																																data-repeat-name="_items[__i__][{{ field.id }}]"
																																																class="color-field c-{{ field.type }} alpha-color-control">
																																															<# } else if (
																																																field.type=='media' ) { #>
																																																<# if ( !field.media ||
																																																	field.media=='' ||
																																																	field.media=='image' ) { #>
																																																	<input type="hidden"
																																																		value="{{ field.value.url }}"
																																																		data-repeat-name="_items[__i__][{{ field.id }}][url]"
																																																		class="image_url widefat">
																																																	<# } else { #>
																																																		<input type="text"
																																																			value="{{ field.value.url }}"
																																																			data-repeat-name="_items[__i__][{{ field.id }}][url]"
																																																			class="image_url widefat">
																																																		<# } #>
																																																			<input type="hidden"
																																																				data-live-id="{{ field.id }}"
																																																				value="{{ field.value.id }}"
																																																				data-repeat-name="_items[__i__][{{ field.id }}][id]"
																																																				class="image_id widefat">
																																																			<# if ( !field.media ||
																																																				field.media=='' ||
																																																				field.media=='image' )
																																																				{ #>
																																																				<div
																																																					class="current <# if ( field.value.url !== '' ){ #> show <# } #>">
																																																					<div
																																																						class="container">
																																																						<div
																																																							class="attachment-media-view attachment-media-view-image landscape">
																																																							<div
																																																								class="thumbnail thumbnail-image">
																																																								<# if (
																																																									field.value.url
																																																									!=='' ){ #>
																																																									<img
																																																										src="{{ field.value.url }}"
																																																										alt="">
																																																									<# } #>
																																																							</div>
																																																						</div>
																																																					</div>
																																																				</div>
																																																				<# } #>
																																																					<div
																																																						class="actions">
																																																						<button
																																																							class="button remove-button "
																																																							<# if ( !
																																																							field.value.url
																																																							){ #>
																																																							style="display:none";
																																																							<# } #>
																																																								type="button"><?php esc_html_e('Remove', 'techalgospotlight'); ?>
																																																						</button>
																																																						<button
																																																							class="button upload-button"
																																																							data-media="{{field.media}}"
																																																							data-add-txt="<?php esc_attr_e('Add', 'techalgospotlight'); ?>"
																																																							data-change-txt="<?php esc_attr_e('Change', 'techalgospotlight'); ?>"
																																																							type="button">
																																																							<# if ( !
																																																								field.value.url
																																																								){ #>
																																																								<?php esc_html_e('Add', 'techalgospotlight'); ?>
																																																								<# } else { #>
																																																									<?php esc_html_e('Change', 'techalgospotlight'); ?>
																																																									<# } #>
																																																						</button>
																																																						<div
																																																							style="clear:both">
																																																						</div>
																																																					</div>
																																																					<# } else if (
																																																						field.type=='textarea'
																																																						||
																																																						field.type=='editor'
																																																						) { #>
																																																						<textarea rows="5"
																																																							data-live-id="{{{ field.id }}}"
																																																							data-repeat-name="_items[__i__][{{ field.id }}]">{{ field.value }}</textarea>
																																																						<# } else if (
																																																							field.type=='icon'
																																																							) { #>
																																																							<# var
																																																								icon_class=field.value;
																																																								if (
																																																								icon_class.indexOf( 'fa-'
																																																								) !=0 ) {
																																																								icon_class='fa-'
																																																								+ field.value;
																																																								} else {
																																																								icon_class=icon_class.replace( 'fa '
																																																								, '' ); }
																																																								icon_class=icon_class.replace( 'fa-fa'
																																																								, '' ); #>
																																																								<div
																																																									class="icon-wrapper">
																																																									<i
																																																										class="fa {{ icon_class }}"></i>
																																																									<input
																																																										data-live-id="{{ field.id }}"
																																																										type="hidden"
																																																										value="{{ field.value }}"
																																																										data-repeat-name="_items[__i__][{{ field.id }}]"
																																																										class="">
																																																								</div>
																																																								<a href="#"
																																																									class="remove-icon"><?php esc_html_e('Remove', 'techalgospotlight'); ?></a>
																																																								<# } else
																																																									if(field.type=='gradient'
																																																									){ #>

																																																									<div
																																																										data-dep-field="background-type"
																																																										data-dep-value="gradient">

																																																										<!-- Color 1 -->
																																																										<div
																																																											class="popup-element color-element style-1">
																																																											<label
																																																												for="gradient-color-1-{{ field.id }}"><?php esc_html_e('Color 1', 'techalgospotlight'); ?></label>
																																																											<input
																																																												data-live-id="{{ field.id }}"
																																																												type="text"
																																																												value="{{ field.value['gradient-color-1'] }}"
																																																												data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-1]"
																																																												class="color-field c-coloralpha alpha-color-control">
																																																										</div>

																																																										<!-- Color 1 Location -->
																																																										<div
																																																											class="techalgospotlight-range-wrapper popup-element color-element style-2"
																																																											data-option-id="gradient-color-1-location">
																																																											<label
																																																												for="gradient-color-1-location-{{ field.id }}"><?php esc_html_e('Color location', 'techalgospotlight'); ?></label>

																																																											<div
																																																												class="techalgospotlight-control-wrap">
																																																												<input
																																																													type="range"
																																																													value="{{field.value['gradient-color-1-location']}}"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-1-location]"
																																																													min="0"
																																																													max="100"
																																																													step="1" />

																																																												<input
																																																													type="number"
																																																													class="techalgospotlight-range-input"
																																																													value="{{field.value['gradient-color-1-location']}}"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-1-location]"
																																																													data-option="gradient-color-1-location" />
																																																											</div>
																																																										</div>

																																																										<!-- Color 2 -->
																																																										<div
																																																											class="popup-element color-element style-1">
																																																											<label
																																																												for="gradient-color-2-{{ field.id }}"><?php esc_html_e('Color 2', 'techalgospotlight'); ?></label>

																																																											<input
																																																												class="color-field c-coloralpha alpha-color-control"
																																																												data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-2]"
																																																												type="text"
																																																												value="{{field.value['gradient-color-2']}}"
																																																												data-show-opacity="true" />
																																																										</div>

																																																										<!-- Color 2 Location -->
																																																										<div
																																																											class="techalgospotlight-range-wrapper popup-element color-element style-2"
																																																											data-option-id="gradient-color-2-location">
																																																											<label
																																																												for="gradient-color-2-location-{{ field.id }}"><?php esc_html_e('Color location', 'techalgospotlight'); ?></label>

																																																											<div
																																																												class="techalgospotlight-control-wrap">
																																																												<input
																																																													type="range"
																																																													value="{{field.value['gradient-color-2-location']}}"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-2-location]"
																																																													min="0"
																																																													max="100"
																																																													step="1" />

																																																												<input
																																																													type="number"
																																																													class="techalgospotlight-range-input"
																																																													value="{{field.value['gradient-color-2-location']}}"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-2-location]"
																																																													data-option="gradient-color-2-location" />
																																																											</div>
																																																										</div>

																																																										<!-- Type -->
																																																										<div
																																																											class="techalgospotlight-select-wrapper popup-element style-1">
																																																											<label
																																																												for="gradient-type-{{ field.id }}"><?php esc_html_e('Gradient type', 'techalgospotlight'); ?></label>
																																																											<div
																																																												class="popup-input-wrapper">
																																																												<select
																																																													data-option="gradient-type"
																																																													id="gradient-type-{{ field.id }}"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-type]">
																																																													<option
																																																														value="linear"
																																																														<#
																																																														if
																																																														( 'linear'===field.value['gradient-type']
																																																														)
																																																														{
																																																														#>
																																																														selected="selected"
																																																														<# }
																																																															#>
																																																															><?php esc_html_e('Linear', 'techalgospotlight'); ?>
																																																													</option>
																																																													<option
																																																														value="radial"
																																																														<#
																																																														if
																																																														( 'radial'===field.value['gradient-type']
																																																														)
																																																														{
																																																														#>
																																																														selected="selected"
																																																														<# }
																																																															#>
																																																															><?php esc_html_e('Radial', 'techalgospotlight'); ?>
																																																													</option>
																																																												</select>
																																																											</div>
																																																										</div>

																																																										<!-- Linear Angle -->
																																																										<div
																																																											data-dep-field="gradient-type"
																																																											data-dep-value="linear"
																																																											class="techalgospotlight-range-wrapper popup-element color-element style-2"
																																																											data-option-id="gradient-linear-angle">
																																																											<label
																																																												for="gradient-angle-{{ field.id }}"><?php esc_html_e('Angle', 'techalgospotlight'); ?></label>

																																																											<div
																																																												class="techalgospotlight-control-wrap">
																																																												<input
																																																													type="range"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-angle]"
																																																													value="{{field.value['gradient-linear-angle']}}"
																																																													min="0"
																																																													max="360"
																																																													step="1" />

																																																												<input
																																																													type="number"
																																																													class="techalgospotlight-range-input"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-angle]"
																																																													value="{{field.value['gradient-linear-angle']}}"
																																																													data-option="gradient-linear-angle" />
																																																											</div>
																																																										</div>

																																																										<!-- Radial Position -->
																																																										<div
																																																											data-dep-field="gradient-type"
																																																											data-dep-value="radial"
																																																											class="techalgospotlight-select-wrapper popup-element style-1">
																																																											<label
																																																												for="gradient-position-{{ field.id }}"><?php esc_html_e('Position', 'techalgospotlight'); ?></label>
																																																											<div
																																																												class="popup-input-wrapper">
																																																												<!-- <# var choices = 
																{'center center':'Center Center', 
																'center left':'Center Left',
																'center right': 'Center Right' ,
																'top center':'Top Center',
																'top left':'Top Left',
																'top right' : 'Top Right',
																'bottom center' : 'Bottom Center',
																'bottom left' : 'Bottom Left',
																'bottom right' : 'Bottom Right'}
																; #>
															<select data-option="gradient-position" id="gradient-position-{{ field.id }}">
															<# _.each( choices, function( value, key ){#>
																<option value="{{ key }}"<# if ( key === field.value['gradient-position'] ) { #> selected="selected"<# } #>>{{{ value }}}</option>
															<# }); #>
															</select> -->

																																																												<?php
																																																												$choices = array(
																																																													'center center' => esc_html__('Center Center', 'techalgospotlight'),
																																																													'center left' => esc_html__('Center Left', 'techalgospotlight'),
																																																													'center right' => esc_html__('Center Right', 'techalgospotlight'),
																																																													'top center' => esc_html__('Top Center', 'techalgospotlight'),
																																																													'top left' => esc_html__('Top Left', 'techalgospotlight'),
																																																													'top right' => esc_html__('Top Right', 'techalgospotlight'),
																																																													'bottom center' => esc_html__('Bottom Center', 'techalgospotlight'),
																																																													'bottom left' => esc_html__('Bottom Left', 'techalgospotlight'),
																																																													'bottom right' => esc_html__('Bottom Right', 'techalgospotlight'),
																																																												);
																																																												?>

																																																												<select
																																																													data-option="gradient-position"
																																																													id="gradient-position-{{ field.id }}"
																																																													data-repeat-name="_items[__i__][{{ field.id }}][gradient-position]">
																																																													<?php foreach ($choices as $key => $value) { ?>
																																																														<option
																																																															value="<?php echo esc_attr($key); ?>"
																																																															<#
																																																															if
																																																															( '<?php echo esc_attr($key); ?>'===field.value['gradient-position']
																																																															)
																																																															{
																																																															#>
																																																															selected="selected"
																																																															<# }
																																																																#>
																																																																><?php echo esc_html($value); ?>
																																																														</option>
																																																													<?php } ?>
																																																												</select>

																																																											</div>
																																																										</div>
																																																									</div>
																																																									<# } else
																																																										if(field.type=='design-options'
																																																										) { #>
																																																										<div
																																																											class="techalgospotlight-design-options-wrapper techalgospotlight-popup-options techalgospotlight-control-wrapper">
																																																											<div
																																																												class="techalgospotlight-design-options-heading">
																																																												<# if
																																																													(
																																																													field.title
																																																													) {
																																																													#>
																																																													<span
																																																														class="customize-control-title">{{{
																																																														field.title
																																																														}}}</span>
																																																													<# }
																																																														#>

																																																														<# if
																																																															(
																																																															field.desc
																																																															)
																																																															{
																																																															#>
																																																															<span
																																																																class="description customize-control-description">{{{
																																																																field.desc
																																																																}}}</span>
																																																															<# }
																																																																#>
																																																											</div>

																																																											<!-- <a href="#" class="reset-defaults">
														<span class="dashicons dashicons-image-rotate"></span>
													</a> -->

																																																											<a href="#"
																																																												class="popup-link">
																																																												<span
																																																													class="dashicons dashicons-edit"></span>
																																																											</a>

																																																											<div
																																																												class="hidden popup-content">

																																																												<# if
																																																													( 'background'
																																																													in
																																																													field.display
																																																													) {
																																																													#>

																																																													<!-- Background Type -->
																																																													<div
																																																														class="techalgospotlight-select-wrapper popup-element style-1">
																																																														<label
																																																															for="background-type-{{ field.id }}"><?php esc_html_e('Background type', 'techalgospotlight'); ?></label>
																																																														<div
																																																															class="popup-input-wrapper">
																																																															<select
																																																																data-live-id="{{{ field.id }}}"
																																																																data-repeat-name="_items[__i__][{{ field.id }}][background-type]"
																																																																data-option="background-type"
																																																																id="background-type-{{ field.id }}">
																																																																<# _.each(
																																																																	field.display['background'],
																																																																	function(
																																																																	value,
																																																																	key
																																																																	){
																																																																	#>
																																																																	<option
																																																																		value="{{ key }}"
																																																																		<#
																																																																		if
																																																																		(
																																																																		key===field.value['background-type']
																																																																		)
																																																																		{
																																																																		#>
																																																																		selected="selected"
																																																																		<# }
																																																																			#>
																																																																			>{{{
																																																																			value
																																																																			}}}
																																																																	</option>
																																																																	<# });
																																																																		#>
																																																															</select>
																																																														</div>
																																																													</div>

																																																													<# if
																																																														( 'color'
																																																														in
																																																														field.display['background']
																																																														)
																																																														{
																																																														#>

																																																														<div
																																																															data-dep-field="background-type"
																																																															data-dep-value="color">
																																																															<!-- Background Color -->
																																																															<div
																																																																class="popup-element color-element style-1">
																																																																<label
																																																																	for="background-color-{{ field.id }}"><?php esc_html_e('Background color', 'techalgospotlight'); ?></label>

																																																																<input
																																																																	class="color-field c-coloralpha alpha-color-control"
																																																																	data-live-id="{{ field.id }}"
																																																																	data-repeat-name="_items[__i__][{{ field.id }}][background-color]"
																																																																	data-option="background-color"
																																																																	type="text"
																																																																	value="{{field.value['background-color']}}"
																																																																	data-show-opacity="true"
																																																																	data-default-color="{{field.value['background-color']}}" />
																																																															</div>
																																																														</div>
																																																														<# }
																																																															#>

																																																															<# if
																																																																( 'gradient'
																																																																in
																																																																field.display['background']
																																																																)
																																																																{
																																																																#>

																																																																<div
																																																																	data-dep-field="background-type"
																																																																	data-dep-value="gradient">

																																																																	<!-- Color 1 -->
																																																																	<div
																																																																		class="popup-element color-element style-1">
																																																																		<label
																																																																			for="gradient-color-1-{{ field.id }}"><?php esc_html_e('Color 1', 'techalgospotlight'); ?></label>

																																																																		<input
																																																																			class="color-field c-coloralpha alpha-color-control"
																																																																			data-live-id="{{ field.id }}"
																																																																			data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-1]"
																																																																			data-option="gradient-color-1"
																																																																			type="text"
																																																																			value="{{field.value['gradient-color-1']}}"
																																																																			data-show-opacity="true"
																																																																			data-default-color="{{field.value['gradient-color-1']}}" />
																																																																	</div>

																																																																	<!-- Color 1 Location -->
																																																																	<div
																																																																		class="techalgospotlight-range-wrapper popup-element color-element style-2"
																																																																		data-option-id="gradient-color-1-location">
																																																																		<label
																																																																			for="gradient-color-1-location-{{ field.id }}"><?php esc_html_e('Color location', 'techalgospotlight'); ?></label>

																																																																		<div
																																																																			class="techalgospotlight-control-wrap">
																																																																			<input
																																																																				type="range"
																																																																				value="{{field.value['gradient-color-1-location']}}"
																																																																				min="0"
																																																																				max="100"
																																																																				step="1" />

																																																																			<input
																																																																				type="number"
																																																																				class="techalgospotlight-range-input"
																																																																				value="{{field.value['gradient-color-1-location']}}"
																																																																				data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-1-location]"
																																																																				data-option="gradient-color-1-location" />
																																																																		</div>
																																																																	</div>

																																																																	<!-- Color 2 -->
																																																																	<div
																																																																		class="popup-element color-element style-1">
																																																																		<label
																																																																			for="gradient-color-2-{{ field.id }}"><?php esc_html_e('Color 2', 'techalgospotlight'); ?></label>

																																																																		<input
																																																																			class="color-field c-coloralpha alpha-color-control"
																																																																			data-live-id="{{ field.id }}"
																																																																			data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-2]"
																																																																			data-option="gradient-color-2"
																																																																			type="text"
																																																																			value="{{field.value['gradient-color-2']}}"
																																																																			data-show-opacity="true"
																																																																			data-default-color="{{field.value['gradient-color-2']}}" />
																																																																	</div>

																																																																	<!-- Color 2 Location -->
																																																																	<div
																																																																		class="techalgospotlight-range-wrapper popup-element color-element style-2"
																																																																		data-option-id="gradient-color-2-location">
																																																																		<label
																																																																			for="gradient-color-2-location-{{ field.id }}"><?php esc_html_e('Color location', 'techalgospotlight'); ?></label>

																																																																		<div
																																																																			class="techalgospotlight-control-wrap">
																																																																			<input
																																																																				type="range"
																																																																				value="{{field.value['gradient-color-2-location']}}"
																																																																				min="0"
																																																																				max="100"
																																																																				step="1" />

																																																																			<input
																																																																				type="number"
																																																																				class="techalgospotlight-range-input"
																																																																				value="{{field.value['gradient-color-2-location']}}"
																																																																				data-repeat-name="_items[__i__][{{ field.id }}][gradient-color-2-location]"
																																																																				data-option="gradient-color-2-location" />
																																																																		</div>
																																																																	</div>

																																																																	<!-- Type -->
																																																																	<div
																																																																		class="techalgospotlight-select-wrapper popup-element style-1">
																																																																		<label
																																																																			for="gradient-type-{{ field.id }}"><?php esc_html_e('Gradient type', 'techalgospotlight'); ?></label>
																																																																		<div
																																																																			class="popup-input-wrapper">
																																																																			<select
																																																																				data-repeat-name="_items[__i__][{{ field.id }}][gradient-type]"
																																																																				data-option="gradient-type"
																																																																				id="gradient-type-{{ field.id }}"
																																																																				data-live-id="{{{ field.id }}}">
																																																																				<option
																																																																					value="linear"
																																																																					<#
																																																																					if
																																																																					( 'linear'===field.value['gradient-type']
																																																																					)
																																																																					{
																																																																					#>
																																																																					selected="selected"
																																																																					<# }
																																																																						#>
																																																																						><?php esc_html_e('Linear', 'techalgospotlight'); ?>
																																																																				</option>
																																																																				<option
																																																																					value="radial"
																																																																					<#
																																																																					if
																																																																					( 'radial'===field.value['gradient-type']
																																																																					)
																																																																					{
																																																																					#>
																																																																					selected="selected"
																																																																					<# }
																																																																						#>
																																																																						><?php esc_html_e('Radial', 'techalgospotlight'); ?>
																																																																				</option>
																																																																			</select>
																																																																		</div>
																																																																	</div>

																																																																	<!-- Linear Angle -->
																																																																	<div
																																																																		data-dep-field="gradient-type"
																																																																		data-dep-value="linear"
																																																																		class="techalgospotlight-range-wrapper popup-element color-element style-2"
																																																																		data-option-id="gradient-linear-angle">
																																																																		<label
																																																																			for="gradient-angle-{{ field.id }}"><?php esc_html_e('Angle', 'techalgospotlight'); ?></label>

																																																																		<div
																																																																			class="techalgospotlight-control-wrap">
																																																																			<input
																																																																				type="range"
																																																																				value="{{field.value['gradient-linear-angle']}}"
																																																																				min="0"
																																																																				max="360"
																																																																				step="1" />

																																																																			<input
																																																																				type="number"
																																																																				class="techalgospotlight-range-input"
																																																																				value="{{field.value['gradient-linear-angle']}}"
																																																																				data-repeat-name="_items[__i__][{{ field.id }}][gradient-linear-angle]"
																																																																				data-option="gradient-linear-angle" />
																																																																		</div>
																																																																	</div>

																																																																	<!-- Radial Position -->
																																																																	<div
																																																																		data-dep-field="gradient-type"
																																																																		data-dep-value="radial"
																																																																		class="techalgospotlight-select-wrapper popup-element style-1">
																																																																		<label
																																																																			for="gradient-position-{{ field.id }}"><?php esc_html_e('Gradient position', 'techalgospotlight'); ?></label>
																																																																		<div
																																																																			class="popup-input-wrapper">
																																																																			<?php
																																																																			$choices = array(
																																																																				'center center' => esc_html__('Center Center', 'techalgospotlight'),
																																																																				'center left' => esc_html__('Center Left', 'techalgospotlight'),
																																																																				'center right' => esc_html__('Center Right', 'techalgospotlight'),
																																																																				'top center' => esc_html__('Top Center', 'techalgospotlight'),
																																																																				'top left' => esc_html__('Top Left', 'techalgospotlight'),
																																																																				'top right' => esc_html__('Top Right', 'techalgospotlight'),
																																																																				'bottom center' => esc_html__('Bottom Center', 'techalgospotlight'),
																																																																				'bottom left' => esc_html__('Bottom Left', 'techalgospotlight'),
																																																																				'bottom right' => esc_html__('Bottom Right', 'techalgospotlight'),
																																																																			);
																																																																			?>

																																																																			<select
																																																																				data-option="gradient-position"
																																																																				id="gradient-position-{{ field.id }}"
																																																																				data-repeat-name="_items[__i__][{{ field.id }}][gradient-position]">
																																																																				<?php foreach ($choices as $key => $value) { ?>
																																																																					<option
																																																																						value="<?php echo esc_attr($key); ?>"
																																																																						<#
																																																																						if
																																																																						( '<?php echo esc_attr($key); ?>'===field.value['gradient-position']
																																																																						)
																																																																						{
																																																																						#>
																																																																						selected="selected"
																																																																						<# }
																																																																							#>
																																																																							><?php echo esc_html($value); ?>
																																																																					</option>
																																																																				<?php } ?>
																																																																			</select>
																																																																		</div>
																																																																	</div>
																																																																</div>
																																																																<# }
																																																																	#>

																																																																	<# if
																																																																		( 'image'
																																																																		in
																																																																		field.display['background']
																																																																		)
																																																																		{
																																																																		#>

																																																																		<div
																																																																			class="techalgospotlight-background-wrapper"
																																																																			data-dep-field="background-type"
																																																																			data-dep-value="image">

																																																																			<!-- Background Image -->
																																																																			<div
																																																																				class="background-image">

																																																																				<div
																																																																					class="attachment-media-view background-image-upload">

																																																																					<# if
																																																																						(
																																																																						field.value['background-image']
																																																																						)
																																																																						{
																																																																						#>
																																																																						<div
																																																																							class="thumbnail thumbnail-image">
																																																																							<img
																																																																								src="{{ field.value['background-image'] }}"
																																																																								alt="" />
																																																																						</div>
																																																																						<# }
																																																																							else
																																																																							{
																																																																							#>
																																																																							<div
																																																																								class="placeholder">
																																																																								<?php esc_html_e('No image selected', 'techalgospotlight'); ?>
																																																																							</div>
																																																																							<# }
																																																																								#>

																																																																								<input
																																																																									type="hidden"
																																																																									data-live-id="{{{ field.id }}}"
																																																																									data-repeat-name="_items[__i__][{{ field.id }}][background-image]"
																																																																									data-option="background-image"
																																																																									value="{{ field.value['background-image'] }}" />

																																																																								<div
																																																																									class="actions">

																																																																									<button
																																																																										class="button background-image-upload-remove-button<# if ( ! field.value['background-image'] ) { #> hidden<# } #>"><?php esc_html_e('Remove', 'techalgospotlight'); ?></button>

																																																																									<button
																																																																										type="button"
																																																																										class="button background-image-upload-button"><?php esc_html_e('Select image', 'techalgospotlight'); ?></button>

																																																																									<a href="#"
																																																																										class="advanced-settings<# if ( ! field.value['background-image'] ) { #> hidden<# } #>">
																																																																										<span
																																																																											class="message"><?php esc_html_e('Advanced', 'techalgospotlight'); ?></span>
																																																																										<span
																																																																											class="dashicons dashicons-arrow-down"></span>
																																																																									</a>

																																																																								</div>
																																																																				</div>
																																																																			</div>

																																																																			<!-- Background Advanced -->
																																																																			<div
																																																																				class="background-image-advanced">

																																																																				<!-- Background Repeat -->
																																																																				<div
																																																																					class="background-repeat">
																																																																					<select
																																																																						{{{
																																																																						data.inputAttrs
																																																																						}}}
																																																																						data-live-id="{{{ field.id }}}"
																																																																						data-option="background-repeat"
																																																																						data-repeat-name="_items[__i__][{{ field.id }}][background-repeat]">
																																																																						<option
																																																																							value="no-repeat"
																																																																							<#
																																																																							if
																																																																							( 'no-repeat'===field.value['background-repeat']
																																																																							)
																																																																							{
																																																																							#>
																																																																							selected
																																																																							<# }
																																																																								#>
																																																																								><?php esc_html_e('No Repeat', 'techalgospotlight'); ?>
																																																																						</option>
																																																																						<option
																																																																							value="repeat"
																																																																							<#
																																																																							if
																																																																							( 'repeat'===field.value['background-repeat']
																																																																							)
																																																																							{
																																																																							#>
																																																																							selected
																																																																							<# }
																																																																								#>
																																																																								><?php esc_html_e('Repeat All', 'techalgospotlight'); ?>
																																																																						</option>
																																																																						<option
																																																																							value="repeat-x"
																																																																							<#
																																																																							if
																																																																							( 'repeat-x'===field.value['background-repeat']
																																																																							)
																																																																							{
																																																																							#>
																																																																							selected
																																																																							<# }
																																																																								#>
																																																																								><?php esc_html_e('Repeat Horizontally', 'techalgospotlight'); ?>
																																																																						</option>
																																																																						<option
																																																																							value="repeat-y"
																																																																							<#
																																																																							if
																																																																							( 'repeat-y'===field.value['background-repeat']
																																																																							)
																																																																							{
																																																																							#>
																																																																							selected
																																																																							<# }
																																																																								#>
																																																																								><?php esc_html_e('Repeat Vertically', 'techalgospotlight'); ?>
																																																																						</option>
																																																																					</select>
																																																																				</div>

																																																																				<!-- Background Position -->
																																																																				<div
																																																																					class="background-position">

																																																																					<h4>
																																																																						<?php esc_html_e('Background Position', 'techalgospotlight'); ?>
																																																																					</h4>

																																																																					<div
																																																																						class="techalgospotlight-range-wrapper"
																																																																						data-option-id="background-position-x">
																																																																						<span><?php esc_html_e('Horizontal', 'techalgospotlight'); ?></span>
																																																																						<div
																																																																							class="techalgospotlight-control-wrap">
																																																																							<input
																																																																								type="range"
																																																																								data-key="background-position-x"
																																																																								value="{{ field.value['background-position-x'] }}"
																																																																								min="0"
																																																																								max="100"
																																																																								step="1" />
																																																																							<input
																																																																								type="number"
																																																																								class="techalgospotlight-range-input"
																																																																								data-option="background-position-x"
																																																																								data-repeat-name="_items[__i__][{{ field.id }}][background-position-x]"
																																																																								value="{{ field.value['background-position-x'] }}" />
																																																																							<span
																																																																								class="techalgospotlight-range-suffix">%</span>
																																																																						</div>
																																																																					</div>

																																																																					<div
																																																																						class="techalgospotlight-range-wrapper"
																																																																						data-option-id="background-position-y">
																																																																						<span><?php esc_html_e('Vertical', 'techalgospotlight'); ?></span>
																																																																						<div
																																																																							class="techalgospotlight-control-wrap">
																																																																							<input
																																																																								type="range"
																																																																								data-key="background-position-y"
																																																																								value="{{ field.value['background-position-y'] }}"
																																																																								min="0"
																																																																								max="100"
																																																																								step="1" />
																																																																							<input
																																																																								type="number"
																																																																								class="techalgospotlight-range-input"
																																																																								data-option="background-position-y"
																																																																								data-repeat-name="_items[__i__][{{ field.id }}][background-position-y]"
																																																																								value="{{ field.value['background-position-y'] }}" />
																																																																							<span
																																																																								class="techalgospotlight-range-suffix">%</span>
																																																																						</div>
																																																																					</div>

																																																																				</div>

																																																																				<!-- Background Size -->
																																																																				<div
																																																																					class="background-size">
																																																																					<h4>
																																																																						<?php esc_html_e('Background Size', 'techalgospotlight'); ?>
																																																																					</h4>
																																																																					<div
																																																																						class="buttonset">
																																																																						<input
																																																																							{{{
																																																																							data.inputAttrs
																																																																							}}}
																																																																							data-live-id="{{{ field.id }}}"
																																																																							data-repeat-name="_items[__i__][{{ field.id }}][background-size]"
																																																																							data-option="background-size"
																																																																							class="switch-input screen-reader-text"
																																																																							type="radio"
																																																																							value="cover"
																																																																							id="{{ field.id }}cover"
																																																																							<#
																																																																							if
																																																																							( 'cover'===field.value['background-size']
																																																																							)
																																																																							{
																																																																							#>
																																																																						checked="checked"
																																																																						<# }
																																																																							#>
																																																																							>
																																																																							<label
																																																																								class="switch-label"
																																																																								for="{{ field.id }}cover"><?php esc_html_e('Cover', 'techalgospotlight'); ?></label>
																																																																							</input>
																																																																							<input
																																																																								{{{
																																																																								data.inputAttrs
																																																																								}}}
																																																																								data-live-id="{{{ field.id }}}"
																																																																								data-repeat-name="_items[__i__][{{ field.id }}][background-size]"
																																																																								data-option="background-size"
																																																																								class="switch-input screen-reader-text"
																																																																								type="radio"
																																																																								value="contain"
																																																																								id="{{ field.id }}contain"
																																																																								<#
																																																																								if
																																																																								( 'contain'===field.value['background-size']
																																																																								)
																																																																								{
																																																																								#>
																																																																							checked="checked"
																																																																							<# }
																																																																								#>
																																																																								>
																																																																								<label
																																																																									class="switch-label"
																																																																									for="{{ field.id }}contain"><?php esc_html_e('Contain', 'techalgospotlight'); ?></label>
																																																																								</input>
																																																																								<input
																																																																									{{{
																																																																									data.inputAttrs
																																																																									}}}
																																																																									data-live-id="{{{ field.id }}}"
																																																																									data-repeat-name="_items[__i__][{{ field.id }}][background-size]"
																																																																									data-option="background-size"
																																																																									class="switch-input screen-reader-text"
																																																																									type="radio"
																																																																									value="auto"
																																																																									id="{{ field.id }}auto"
																																																																									<#
																																																																									if
																																																																									( 'auto'===field.value['background-size']
																																																																									)
																																																																									{
																																																																									#>
																																																																								checked="checked"
																																																																								<# }
																																																																									#>
																																																																									>
																																																																									<label
																																																																										class="switch-label"
																																																																										for="{{ field.id }}auto"><?php esc_html_e('Auto', 'techalgospotlight'); ?></label>
																																																																									</input>
																																																																					</div>
																																																																				</div>

																																																																				<!-- Background Attachment -->
																																																																				<div
																																																																					class="background-attachment">
																																																																					<h4>
																																																																						<?php esc_html_e('Background Attachment', 'techalgospotlight'); ?>
																																																																					</h4>
																																																																					<div
																																																																						class="buttonset">
																																																																						<input
																																																																							{{{
																																																																							data.inputAttrs
																																																																							}}}
																																																																							data-live-id="{{{ field.id }}}"
																																																																							data-repeat-name="_items[__i__][{{ field.id }}][background-attachment]"
																																																																							data-option="background-attachment"
																																																																							lass="switch-input screen-reader-text"
																																																																							type="radio"
																																																																							value="inherit"
																																																																							id="{{ field.id }}inherit"
																																																																							<#
																																																																							if
																																																																							( 'inherit'===field.value['background-attachment']
																																																																							)
																																																																							{
																																																																							#>
																																																																						checked="checked"
																																																																						<# }
																																																																							#>
																																																																							>
																																																																							<label
																																																																								class="switch-label"
																																																																								for="{{ field.id }}inherit"><?php esc_html_e('Inherit', 'techalgospotlight'); ?></label>
																																																																							</input>
																																																																							<input
																																																																								{{{
																																																																								data.inputAttrs
																																																																								}}}
																																																																								data-live-id="{{{ field.id }}}"
																																																																								data-repeat-name="_items[__i__][{{ field.id }}][background-attachment]"
																																																																								data-option="background-attachment"
																																																																								class="switch-input screen-reader-text"
																																																																								type="radio"
																																																																								value="scroll"
																																																																								id="{{ field.id }}scroll"
																																																																								<#
																																																																								if
																																																																								( 'scroll'===field.value['background-attachment']
																																																																								)
																																																																								{
																																																																								#>
																																																																							checked="checked"
																																																																							<# }
																																																																								#>
																																																																								>
																																																																								<label
																																																																									class="switch-label"
																																																																									for="{{ field.id }}scroll"><?php esc_html_e('Scroll', 'techalgospotlight'); ?></label>
																																																																								</input>
																																																																								<input
																																																																									{{{
																																																																									data.inputAttrs
																																																																									}}}
																																																																									data-live-id="{{{ field.id }}}"
																																																																									data-repeat-name="_items[__i__][{{ field.id }}][background-attachment]"
																																																																									data-option="background-attachment"
																																																																									class="switch-input screen-reader-text"
																																																																									type="radio"
																																																																									value="fixed"
																																																																									id="{{ field.id }}fixed"
																																																																									<#
																																																																									if
																																																																									( 'fixed'===field.value['background-attachment']
																																																																									)
																																																																									{
																																																																									#>
																																																																								checked="checked"
																																																																								<# }
																																																																									#>
																																																																									>
																																																																									<label
																																																																										class="switch-label"
																																																																										for="{{ field.id }}fixed"><?php esc_html_e('Fixed', 'techalgospotlight'); ?></label>
																																																																									</input>
																																																																					</div>
																																																																				</div>

																																																																				<!-- Background Color Overlay -->
																																																																				<div
																																																																					class="background-color-overlay popup-element color-element style-1">

																																																																					<label
																																																																						for="background-color-overlay-{{ field.id }}">
																																																																						<h4>
																																																																							<?php esc_html_e('Overlay Color', 'techalgospotlight'); ?>
																																																																						</h4>
																																																																					</label>

																																																																					<input
																																																																						class="color-field c-coloralpha alpha-color-control"
																																																																						data-live-id="{{ field.id }}"
																																																																						data-repeat-name="_items[__i__][{{ field.id }}][background-color-overlay]"
																																																																						data-option="background-color-overlay"
																																																																						type="text"
																																																																						value="{{field.value['background-color-overlay']}}"
																																																																						data-show-opacity="true"
																																																																						data-default-color="{{field.value['background-color-overlay']}}" />
																																																																				</div>

																																																																				<!-- Background Image ID -->
																																																																				<input
																																																																					type="hidden"
																																																																					data-option="background-image-id"
																																																																					data-repeat-name="_items[__i__][{{ field.id }}][background-image-id]"
																																																																					value="{{ field.value['background-image-id'] }}"
																																																																					class="background-image-id" />
																																																																			</div>

																																																																		</div>
																																																																		<# }
																																																																			#>
																																																																			<# }
																																																																				#>

																																																																				<# if
																																																																					( 'color'
																																																																					in
																																																																					field.display
																																																																					)
																																																																					{
																																																																					#>

																																																																					<# _.each(
																																																																						field.display['color'],
																																																																						function(
																																																																						title,
																																																																						id
																																																																						){
																																																																						#>

																																																																						<div
																																																																							class="popup-element color-element style-1">
																																																																							<label
																																																																								for="{{ id }}-{{ field.id }}">{{{
																																																																								title
																																																																								}}}</label>
																																																																							<input
																																																																								class="color-field c-coloralpha alpha-color-control"
																																																																								data-live-id="{{ field.id }}"
																																																																								data-repeat-name="_items[__i__][{{ field.id }}][{{ id }}]"
																																																																								data-option="{{ id }}"
																																																																								type="text"
																																																																								value="{{field.value[ id ]}}"
																																																																								data-show-opacity="true"
																																																																								data-default-color="{{field.value[ id ]}}" />
																																																																						</div>
																																																																						<# });
																																																																							#>

																																																																							<# }
																																																																								#>

																																																																								<# if
																																																																									( 'border'
																																																																									in
																																																																									field.display
																																																																									)
																																																																									{
																																																																									#>

																																																																									<# if
																																																																										( 'width'
																																																																										in
																																																																										field.display['border']
																																																																										&& 'positions'
																																																																										in
																																																																										field.display['border']
																																																																										)
																																																																										{
																																																																										#>

																																																																										<div
																																																																											class="customize-control-techalgospotlight-spacing popup-element style-2">

																																																																											<label>{{{
																																																																												field.display['border']['width']
																																																																												}}}</label>

																																																																											<div
																																																																												class="techalgospotlight-control-wrap">

																																																																												<ul
																																																																													class="active">

																																																																													<# _.each(
																																																																														field.display['border']['positions'],
																																																																														function(
																																																																														title,
																																																																														id
																																																																														){
																																																																														#>
																																																																														<li
																																																																															class="spacing-control-wrap spacing-input">
																																																																															<input
																																																																																{{{
																																																																																data.inputAttrs
																																																																																}}}
																																																																																data-live-id="{{{ field.id }}}"
																																																																																data-repeat-name="_items[__i__][{{ field.id }}][border-{{ id }}-width]"
																																																																																type="number"
																																																																																data-option="border-{{ id }}-width"
																																																																																value="{{{ field.value[ 'border-' + id + '-width' ] }}}" />
																																																																															<span
																																																																																class="techalgospotlight-spacing-label">{{{
																																																																																title
																																																																																}}}</span>
																																																																														</li>
																																																																														<# });
																																																																															#>

																																																																															<li
																																																																																class="spacing-control-wrap">
																																																																																<div
																																																																																	class="spacing-link-values">
																																																																																	<span
																																																																																		class="dashicons dashicons-admin-links techalgospotlight-spacing-linked"
																																																																																		data-element="{{ field.id }}"
																																																																																		title="{{ data.title }}"></span>
																																																																																	<span
																																																																																		class="dashicons dashicons-editor-unlink techalgospotlight-spacing-unlinked"
																																																																																		data-element="{{ field.id }}"
																																																																																		title="{{ data.title }}"></span>
																																																																																</div>
																																																																															</li>

																																																																												</ul>
																																																																											</div>
																																																																										</div>

																																																																										<# }
																																																																											#>

																																																																											<# if
																																																																												( 'style'
																																																																												in
																																																																												field.display['border']
																																																																												)
																																																																												{
																																																																												#>
																																																																												<!-- Border Style -->
																																																																												<div
																																																																													class="techalgospotlight-select-wrapper popup-element style-1">
																																																																													<label
																																																																														for="border-style-{{ field.id }}">{{{
																																																																														field.display['border']['style']
																																																																														}}}</label>
																																																																													<div
																																																																														class="popup-input-wrapper">
																																																																														<select
																																																																															data-option="border-style"
																																																																															data-live-id="{{{ field.id }}}"
																																																																															id="border-style-{{ field.id }}"
																																																																															data-repeat-name="_items[__i__][{{ field.id }}][border-style]">
																																																																															<?php
																																																																															$choices = array(
																																																																																'solid' => esc_html__('Solid', 'techalgospotlight'),
																																																																																'dotted' => esc_html__('Dotted', 'techalgospotlight'),
																																																																																'dashed' => esc_html__('Dashed', 'techalgospotlight'),
																																																																															);
																																																																															?>
																																																																															<?php foreach ($choices as $key => $value) { ?>
																																																																																<option
																																																																																	value="<?php echo esc_attr($key); ?>"
																																																																																	<#
																																																																																	if
																																																																																	( '<?php echo esc_attr($key); ?>'===field.value['border-style']
																																																																																	)
																																																																																	{
																																																																																	#>
																																																																																	selected="selected"
																																																																																	<# }
																																																																																		#>
																																																																																		><?php echo esc_html($value); ?>
																																																																																</option>
																																																																															<?php } ?>
																																																																														</select>
																																																																													</div>
																																																																												</div>
																																																																												<# }
																																																																													#>

																																																																													<# if
																																																																														( 'color'
																																																																														in
																																																																														field.display['border']
																																																																														)
																																																																														{
																																																																														#>
																																																																														<!-- Border Color -->
																																																																														<div
																																																																															class="popup-element color-element style-1">
																																																																															<label
																																																																																for="border-color-{{ field.id }}">{{{
																																																																																field.display['border']['color']
																																																																																}}}</label>

																																																																															<input
																																																																																class="color-field c-coloralpha alpha-color-control"
																																																																																data-live-id="{{ field.id }}"
																																																																																data-repeat-name="_items[__i__][{{ field.id }}][border-color]"
																																																																																data-option="border-color"
																																																																																type="text"
																																																																																value="{{field.value['border-color']}}"
																																																																																data-show-opacity="true"
																																																																																data-default-color="{{field.value['border-color']}}" />
																																																																														</div>
																																																																														<# }
																																																																															#>

																																																																															<# if
																																																																																( 'separator'
																																																																																in
																																																																																field.display['border']
																																																																																)
																																																																																{
																																																																																#>
																																																																																<!-- Separator Color -->
																																																																																<div
																																																																																	class="popup-element color-element style-1">
																																																																																	<label
																																																																																		for="separator-color-{{ field.id }}">{{{
																																																																																		field.display['border']['separator']
																																																																																		}}}</label>
																																																																																	<input
																																																																																		class="color-field c-coloralpha alpha-color-control"
																																																																																		data-live-id="{{ field.id }}"
																																																																																		data-repeat-name="_items[__i__][{{ field.id }}][seperator-style]"
																																																																																		data-option="separator-color"
																																																																																		type="text"
																																																																																		value="{{field.value['separator-color']}}"
																																																																																		data-show-opacity="true"
																																																																																		data-default-color="{{field.value['separator-color']}}" />
																																																																																</div>
																																																																																<# }
																																																																																	#>

																																																																																	<# }
																																																																																		#>

																																																											</div>
																																																											<!-- .popup-content -->
																																																										</div>
																																																										<# } #>
																																																											<!-- background end -->
																			</div>
																			<# } #>
																				<# } #>
																					<div class="widget-control-actions">
																						<div class="alignleft">
																							<span class="remove-btn-wrapper">
																								<a href="#" class="repeat-control-remove"
																									title=""><?php esc_html_e('Remove', 'techalgospotlight'); ?></a> |
																							</span>
																							<a href="#"
																								class="repeat-control-close"><?php esc_html_e('Close', 'techalgospotlight'); ?></a>
																						</div>
																						<br class="clear">
																					</div>
																	</div>
									</div><!-- .form -->
								</div>
							</div>
					</li>
					<?php
		}

		/**
		 * Customizer Icon picker
		 */
		public function techalgospotlight_customize_controls_enqueue_scripts()
		{
			wp_localize_script(
				'customize-controls',
				'techalgospotlight_Icon_Picker',
				apply_filters(
					'techalgospotlight_icon_picker_js_setup',
					array(
						'search' => esc_html__('Search', 'techalgospotlight'),
						'fonts' => array(
							'font-awesome' => array(
								// Name of icon.
								'name' => esc_html__('Font Awesome', 'techalgospotlight'),
								// prefix class example for font-awesome fa-fa-{name}.
								'prefix' => '',
								// font url.
								'url' => esc_url(add_query_arg(array('ver' => '5.15.4'), get_template_directory_uri() . '/assets/css/all.css')),
								// Icon class name, separated by |.
								'icons' => 'fab fa-500px|fab fa-accessible-icon|fab fa-accusoft|fab fa-acquisitions-incorporated|fas fa-ad|fas fa-address-book|far fa-address-book|fas fa-address-card|far fa-address-card|fas fa-adjust|fab fa-adn|fab fa-adversal|fab fa-affiliatetheme|fas fa-air-freshener|fab fa-airbnb|fab fa-algolia|fas fa-align-center|fas fa-align-justify|fas fa-align-left|fas fa-align-right|fab fa-alipay|fas fa-allergies|fab fa-amazon|fab fa-amazon-pay|fas fa-ambulance|fas fa-american-sign-language-interpreting|fab fa-amilia|fas fa-anchor|fab fa-android|fab fa-angellist|fas fa-angle-double-down|fas fa-angle-double-left|fas fa-angle-double-right|fas fa-angle-double-up|fas fa-angle-down|fas fa-angle-left|fas fa-angle-right|fas fa-angle-up|fas fa-angry|far fa-angry|fab fa-angrycreative|fab fa-angular|fas fa-ankh|fab fa-app-store|fab fa-app-store-ios|fab fa-apper|fab fa-apple|fas fa-apple-alt|fab fa-apple-pay|fas fa-archive|fas fa-archway|fas fa-arrow-alt-circle-down|far fa-arrow-alt-circle-down|fas fa-arrow-alt-circle-left|far fa-arrow-alt-circle-left|fas fa-arrow-alt-circle-right|far fa-arrow-alt-circle-right|fas fa-arrow-alt-circle-up|far fa-arrow-alt-circle-up|fas fa-arrow-circle-down|fas fa-arrow-circle-left|fas fa-arrow-circle-right|fas fa-arrow-circle-up|fas fa-arrow-down|fas fa-arrow-left|fas fa-arrow-right|fas fa-arrow-up|fas fa-arrows-alt|fas fa-arrows-alt-h|fas fa-arrows-alt-v|fab fa-artstation|fas fa-assistive-listening-systems|fas fa-asterisk|fab fa-asymmetrik|fas fa-at|fas fa-atlas|fab fa-atlassian|fas fa-atom|fab fa-audible|fas fa-audio-description|fab fa-autoprefixer|fab fa-avianex|fab fa-aviato|fas fa-award|fab fa-aws|fas fa-baby|fas fa-baby-carriage|fas fa-backspace|fas fa-backward|fas fa-bacon|fas fa-bacteria|fas fa-bacterium|fas fa-bahai|fas fa-balance-scale|fas fa-balance-scale-left|fas fa-balance-scale-right|fas fa-ban|fas fa-band-aid|fab fa-bandcamp|fas fa-barcode|fas fa-bars|fas fa-baseball-ball|fas fa-basketball-ball|fas fa-bath|fas fa-battery-empty|fas fa-battery-full|fas fa-battery-half|fas fa-battery-quarter|fas fa-battery-three-quarters|fab fa-battle-net|fas fa-bed|fas fa-beer|fab fa-behance|fab fa-behance-square|fas fa-bell|far fa-bell|fas fa-bell-slash|far fa-bell-slash|fas fa-bezier-curve|fas fa-bible|fas fa-bicycle|fas fa-biking|fab fa-bimobject|fas fa-binoculars|fas fa-biohazard|fas fa-birthday-cake|fab fa-bitbucket|fab fa-bitcoin|fab fa-bity|fab fa-black-tie|fab fa-blackberry|fas fa-blender|fas fa-blender-phone|fas fa-blind|fas fa-blog|fab fa-blogger|fab fa-blogger-b|fab fa-bluetooth|fab fa-bluetooth-b|fas fa-bold|fas fa-bolt|fas fa-bomb|fas fa-bone|fas fa-bong|fas fa-book|fas fa-book-dead|fas fa-book-medical|fas fa-book-open|fas fa-book-reader|fas fa-bookmark|far fa-bookmark|fab fa-bootstrap|fas fa-border-all|fas fa-border-none|fas fa-border-style|fas fa-bowling-ball|fas fa-box|fas fa-box-open|fas fa-box-tissue|fas fa-boxes|fas fa-braille|fas fa-brain|fas fa-bread-slice|fas fa-briefcase|fas fa-briefcase-medical|fas fa-broadcast-tower|fas fa-broom|fas fa-brush|fab fa-btc|fab fa-buffer|fas fa-bug|fas fa-building|far fa-building|fas fa-bullhorn|fas fa-bullseye|fas fa-burn|fab fa-buromobelexperte|fas fa-bus|fas fa-bus-alt|fas fa-business-time|fab fa-buy-n-large|fab fa-buysellads|fas fa-calculator|fas fa-calendar|far fa-calendar|fas fa-calendar-alt|far fa-calendar-alt|fas fa-calendar-check|far fa-calendar-check|fas fa-calendar-day|fas fa-calendar-minus|far fa-calendar-minus|fas fa-calendar-plus|far fa-calendar-plus|fas fa-calendar-times|far fa-calendar-times|fas fa-calendar-week|fas fa-camera|fas fa-camera-retro|fas fa-campground|fab fa-canadian-maple-leaf|fas fa-candy-cane|fas fa-cannabis|fas fa-capsules|fas fa-car|fas fa-car-alt|fas fa-car-battery|fas fa-car-crash|fas fa-car-side|fas fa-caravan|fas fa-caret-down|fas fa-caret-left|fas fa-caret-right|fas fa-caret-square-down|far fa-caret-square-down|fas fa-caret-square-left|far fa-caret-square-left|fas fa-caret-square-right|far fa-caret-square-right|fas fa-caret-square-up|far fa-caret-square-up|fas fa-caret-up|fas fa-carrot|fas fa-cart-arrow-down|fas fa-cart-plus|fas fa-cash-register|fas fa-cat|fab fa-cc-amazon-pay|fab fa-cc-amex|fab fa-cc-apple-pay|fab fa-cc-diners-club|fab fa-cc-discover|fab fa-cc-jcb|fab fa-cc-mastercard|fab fa-cc-paypal|fab fa-cc-stripe|fab fa-cc-visa|fab fa-centercode|fab fa-centos|fas fa-certificate|fas fa-chair|fas fa-chalkboard|fas fa-chalkboard-teacher|fas fa-charging-station|fas fa-chart-area|fas fa-chart-bar|far fa-chart-bar|fas fa-chart-line|fas fa-chart-pie|fas fa-check|fas fa-check-circle|far fa-check-circle|fas fa-check-double|fas fa-check-square|far fa-check-square|fas fa-cheese|fas fa-chess|fas fa-chess-bishop|fas fa-chess-board|fas fa-chess-king|fas fa-chess-knight|fas fa-chess-pawn|fas fa-chess-queen|fas fa-chess-rook|fas fa-chevron-circle-down|fas fa-chevron-circle-left|fas fa-chevron-circle-right|fas fa-chevron-circle-up|fas fa-chevron-down|fas fa-chevron-left|fas fa-chevron-right|fas fa-chevron-up|fas fa-child|fab fa-chrome|fab fa-chromecast|fas fa-church|fas fa-circle|far fa-circle|fas fa-circle-notch|fas fa-city|fas fa-clinic-medical|fas fa-clipboard|far fa-clipboard|fas fa-clipboard-check|fas fa-clipboard-list|fas fa-clock|far fa-clock|fas fa-clone|far fa-clone|fas fa-closed-captioning|far fa-closed-captioning|fas fa-cloud|fas fa-cloud-download-alt|fas fa-cloud-meatball|fas fa-cloud-moon|fas fa-cloud-moon-rain|fas fa-cloud-rain|fas fa-cloud-showers-heavy|fas fa-cloud-sun|fas fa-cloud-sun-rain|fas fa-cloud-upload-alt|fab fa-cloudflare|fab fa-cloudscale|fab fa-cloudsmith|fab fa-cloudversify|fas fa-cocktail|fas fa-code|fas fa-code-branch|fab fa-codepen|fab fa-codiepie|fas fa-coffee|fas fa-cog|fas fa-cogs|fas fa-coins|fas fa-columns|fas fa-comment|far fa-comment|fas fa-comment-alt|far fa-comment-alt|fas fa-comment-dollar|fas fa-comment-dots|far fa-comment-dots|fas fa-comment-medical|fas fa-comment-slash|fas fa-comments|far fa-comments|fas fa-comments-dollar|fas fa-compact-disc|fas fa-compass|far fa-compass|fas fa-compress|fas fa-compress-alt|fas fa-compress-arrows-alt|fas fa-concierge-bell|fab fa-confluence|fab fa-connectdevelop|fab fa-contao|fas fa-cookie|fas fa-cookie-bite|fas fa-copy|far fa-copy|fas fa-copyright|far fa-copyright|fab fa-cotton-bureau|fas fa-couch|fab fa-cpanel|fab fa-creative-commons|fab fa-creative-commons-by|fab fa-creative-commons-nc|fab fa-creative-commons-nc-eu|fab fa-creative-commons-nc-jp|fab fa-creative-commons-nd|fab fa-creative-commons-pd|fab fa-creative-commons-pd-alt|fab fa-creative-commons-remix|fab fa-creative-commons-sa|fab fa-creative-commons-sampling|fab fa-creative-commons-sampling-plus|fab fa-creative-commons-share|fab fa-creative-commons-zero|fas fa-credit-card|far fa-credit-card|fab fa-critical-role|fas fa-crop|fas fa-crop-alt|fas fa-cross|fas fa-crosshairs|fas fa-crow|fas fa-crown|fas fa-crutch|fab fa-css3|fab fa-css3-alt|fas fa-cube|fas fa-cubes|fas fa-cut|fab fa-cuttlefish|fab fa-d-and-d|fab fa-d-and-d-beyond|fab fa-dailymotion|fab fa-dashcube|fas fa-database|fas fa-deaf|fab fa-deezer|fab fa-delicious|fas fa-democrat|fab fa-deploydog|fab fa-deskpro|fas fa-desktop|fab fa-dev|fab fa-deviantart|fas fa-dharmachakra|fab fa-dhl|fas fa-diagnoses|fab fa-diaspora|fas fa-dice|fas fa-dice-d20|fas fa-dice-d6|fas fa-dice-five|fas fa-dice-four|fas fa-dice-one|fas fa-dice-six|fas fa-dice-three|fas fa-dice-two|fab fa-digg|fab fa-digital-ocean|fas fa-digital-tachograph|fas fa-directions|fab fa-discord|fab fa-discourse|fas fa-disease|fas fa-divide|fas fa-dizzy|far fa-dizzy|fas fa-dna|fab fa-dochub|fab fa-docker|fas fa-dog|fas fa-dollar-sign|fas fa-dolly|fas fa-dolly-flatbed|fas fa-donate|fas fa-door-closed|fas fa-door-open|fas fa-dot-circle|far fa-dot-circle|fas fa-dove|fas fa-download|fab fa-draft2digital|fas fa-drafting-compass|fas fa-dragon|fas fa-draw-polygon|fab fa-dribbble|fab fa-dribbble-square|fab fa-dropbox|fas fa-drum|fas fa-drum-steelpan|fas fa-drumstick-bite|fab fa-drupal|fas fa-dumbbell|fas fa-dumpster|fas fa-dumpster-fire|fas fa-dungeon|fab fa-dyalog|fab fa-earlybirds|fab fa-ebay|fab fa-edge|fab fa-edge-legacy|fas fa-edit|far fa-edit|fas fa-egg|fas fa-eject|fab fa-elementor|fas fa-ellipsis-h|fas fa-ellipsis-v|fab fa-ello|fab fa-ember|fab fa-empire|fas fa-envelope|far fa-envelope|fas fa-envelope-open|far fa-envelope-open|fas fa-envelope-open-text|fas fa-envelope-square|fab fa-envira|fas fa-equals|fas fa-eraser|fab fa-erlang|fab fa-ethereum|fas fa-ethernet|fab fa-etsy|fas fa-euro-sign|fab fa-evernote|fas fa-exchange-alt|fas fa-exclamation|fas fa-exclamation-circle|fas fa-exclamation-triangle|fas fa-expand|fas fa-expand-alt|fas fa-expand-arrows-alt|fab fa-expeditedssl|fas fa-external-link-alt|fas fa-external-link-square-alt|fas fa-eye|far fa-eye|fas fa-eye-dropper|fas fa-eye-slash|far fa-eye-slash|fab fa-facebook|fab fa-facebook-f|fab fa-facebook-messenger|fab fa-facebook-square|fas fa-fan|fab fa-fantasy-flight-games|fas fa-fast-backward|fas fa-fast-forward|fas fa-faucet|fas fa-fax|fas fa-feather|fas fa-feather-alt|fab fa-fedex|fab fa-fedora|fas fa-female|fas fa-fighter-jet|fab fa-figma|fas fa-file|far fa-file|fas fa-file-alt|far fa-file-alt|fas fa-file-archive|far fa-file-archive|fas fa-file-audio|far fa-file-audio|fas fa-file-code|far fa-file-code|fas fa-file-contract|fas fa-file-csv|fas fa-file-download|fas fa-file-excel|far fa-file-excel|fas fa-file-export|fas fa-file-image|far fa-file-image|fas fa-file-import|fas fa-file-invoice|fas fa-file-invoice-dollar|fas fa-file-medical|fas fa-file-medical-alt|fas fa-file-pdf|far fa-file-pdf|fas fa-file-powerpoint|far fa-file-powerpoint|fas fa-file-prescription|fas fa-file-signature|fas fa-file-upload|fas fa-file-video|far fa-file-video|fas fa-file-word|far fa-file-word|fas fa-fill|fas fa-fill-drip|fas fa-film|fas fa-filter|fas fa-fingerprint|fas fa-fire|fas fa-fire-alt|fas fa-fire-extinguisher|fab fa-firefox|fab fa-firefox-browser|fas fa-first-aid|fab fa-first-order|fab fa-first-order-alt|fab fa-firstdraft|fas fa-fish|fas fa-fist-raised|fas fa-flag|far fa-flag|fas fa-flag-checkered|fas fa-flag-usa|fas fa-flask|fab fa-flickr|fab fa-flipboard|fas fa-flushed|far fa-flushed|fab fa-fly|fas fa-folder|far fa-folder|fas fa-folder-minus|fas fa-folder-open|far fa-folder-open|fas fa-folder-plus|fas fa-font|fab fa-font-awesome|fab fa-font-awesome-alt|fab fa-font-awesome-flag|fab fa-fonticons|fab fa-fonticons-fi|fas fa-football-ball|fab fa-fort-awesome|fab fa-fort-awesome-alt|fab fa-forumbee|fas fa-forward|fab fa-foursquare|fab fa-free-code-camp|fab fa-freebsd|fas fa-frog|fas fa-frown|far fa-frown|fas fa-frown-open|far fa-frown-open|fab fa-fulcrum|fas fa-funnel-dollar|fas fa-futbol|far fa-futbol|fab fa-galactic-republic|fab fa-galactic-senate|fas fa-gamepad|fas fa-gas-pump|fas fa-gavel|fas fa-gem|far fa-gem|fas fa-genderless|fab fa-get-pocket|fab fa-gg|fab fa-gg-circle|fas fa-ghost|fas fa-gift|fas fa-gifts|fab fa-git|fab fa-git-alt|fab fa-git-square|fab fa-github|fab fa-github-alt|fab fa-github-square|fab fa-gitkraken|fab fa-gitlab|fab fa-gitter|fas fa-glass-cheers|fas fa-glass-martini|fas fa-glass-martini-alt|fas fa-glass-whiskey|fas fa-glasses|fab fa-glide|fab fa-glide-g|fas fa-globe|fas fa-globe-africa|fas fa-globe-americas|fas fa-globe-asia|fas fa-globe-europe|fab fa-gofore|fas fa-golf-ball|fab fa-goodreads|fab fa-goodreads-g|fab fa-google|fab fa-google-drive|fab fa-google-pay|fab fa-google-play|fab fa-google-plus|fab fa-google-plus-g|fab fa-google-plus-square|fab fa-google-wallet|fas fa-gopuram|fas fa-graduation-cap|fab fa-gratipay|fab fa-grav|fas fa-greater-than|fas fa-greater-than-equal|fas fa-grimace|far fa-grimace|fas fa-grin|far fa-grin|fas fa-grin-alt|far fa-grin-alt|fas fa-grin-beam|far fa-grin-beam|fas fa-grin-beam-sweat|far fa-grin-beam-sweat|fas fa-grin-hearts|far fa-grin-hearts|fas fa-grin-squint|far fa-grin-squint|fas fa-grin-squint-tears|far fa-grin-squint-tears|fas fa-grin-stars|far fa-grin-stars|fas fa-grin-tears|far fa-grin-tears|fas fa-grin-tongue|far fa-grin-tongue|fas fa-grin-tongue-squint|far fa-grin-tongue-squint|fas fa-grin-tongue-wink|far fa-grin-tongue-wink|fas fa-grin-wink|far fa-grin-wink|fas fa-grip-horizontal|fas fa-grip-lines|fas fa-grip-lines-vertical|fas fa-grip-vertical|fab fa-gripfire|fab fa-grunt|fab fa-guilded|fas fa-guitar|fab fa-gulp|fas fa-h-square|fab fa-hacker-news|fab fa-hacker-news-square|fab fa-hackerrank|fas fa-hamburger|fas fa-hammer|fas fa-hamsa|fas fa-hand-holding|fas fa-hand-holding-heart|fas fa-hand-holding-medical|fas fa-hand-holding-usd|fas fa-hand-holding-water|fas fa-hand-lizard|far fa-hand-lizard|fas fa-hand-middle-finger|fas fa-hand-paper|far fa-hand-paper|fas fa-hand-peace|far fa-hand-peace|fas fa-hand-point-down|far fa-hand-point-down|fas fa-hand-point-left|far fa-hand-point-left|fas fa-hand-point-right|far fa-hand-point-right|fas fa-hand-point-up|far fa-hand-point-up|fas fa-hand-pointer|far fa-hand-pointer|fas fa-hand-rock|far fa-hand-rock|fas fa-hand-scissors|far fa-hand-scissors|fas fa-hand-sparkles|fas fa-hand-spock|far fa-hand-spock|fas fa-hands|fas fa-hands-helping|fas fa-hands-wash|fas fa-handshake|far fa-handshake|fas fa-handshake-alt-slash|fas fa-handshake-slash|fas fa-hanukiah|fas fa-hard-hat|fas fa-hashtag|fas fa-hat-cowboy|fas fa-hat-cowboy-side|fas fa-hat-wizard|fas fa-hdd|far fa-hdd|fas fa-head-side-cough|fas fa-head-side-cough-slash|fas fa-head-side-mask|fas fa-head-side-virus|fas fa-heading|fas fa-headphones|fas fa-headphones-alt|fas fa-headset|fas fa-heart|far fa-heart|fas fa-heart-broken|fas fa-heartbeat|fas fa-helicopter|fas fa-highlighter|fas fa-hiking|fas fa-hippo|fab fa-hips|fab fa-hire-a-helper|fas fa-history|fab fa-hive|fas fa-hockey-puck|fas fa-holly-berry|fas fa-home|fab fa-hooli|fab fa-hornbill|fas fa-horse|fas fa-horse-head|fas fa-hospital|far fa-hospital|fas fa-hospital-alt|fas fa-hospital-symbol|fas fa-hospital-user|fas fa-hot-tub|fas fa-hotdog|fas fa-hotel|fab fa-hotjar|fas fa-hourglass|far fa-hourglass|fas fa-hourglass-end|fas fa-hourglass-half|fas fa-hourglass-start|fas fa-house-damage|fas fa-house-user|fab fa-houzz|fas fa-hryvnia|fab fa-html5|fab fa-hubspot|fas fa-i-cursor|fas fa-ice-cream|fas fa-icicles|fas fa-icons|fas fa-id-badge|far fa-id-badge|fas fa-id-card|far fa-id-card|fas fa-id-card-alt|fab fa-ideal|fas fa-igloo|fas fa-image|far fa-image|fas fa-images|far fa-images|fab fa-imdb|fas fa-inbox|fas fa-indent|fas fa-industry|fas fa-infinity|fas fa-info|fas fa-info-circle|fab fa-innosoft|fab fa-instagram|fab fa-instagram-square|fab fa-instalod|fab fa-intercom|fab fa-internet-explorer|fab fa-invision|fab fa-ioxhost|fas fa-italic|fab fa-itch-io|fab fa-itunes|fab fa-itunes-note|fab fa-java|fas fa-jedi|fab fa-jedi-order|fab fa-jenkins|fab fa-jira|fab fa-joget|fas fa-joint|fab fa-joomla|fas fa-journal-whills|fab fa-js|fab fa-js-square|fab fa-jsfiddle|fas fa-kaaba|fab fa-kaggle|fas fa-key|fab fa-keybase|fas fa-keyboard|far fa-keyboard|fab fa-keycdn|fas fa-khanda|fab fa-kickstarter|fab fa-kickstarter-k|fas fa-kiss|far fa-kiss|fas fa-kiss-beam|far fa-kiss-beam|fas fa-kiss-wink-heart|far fa-kiss-wink-heart|fas fa-kiwi-bird|fab fa-korvue|fas fa-landmark|fas fa-language|fas fa-laptop|fas fa-laptop-code|fas fa-laptop-house|fas fa-laptop-medical|fab fa-laravel|fab fa-lastfm|fab fa-lastfm-square|fas fa-laugh|far fa-laugh|fas fa-laugh-beam|far fa-laugh-beam|fas fa-laugh-squint|far fa-laugh-squint|fas fa-laugh-wink|far fa-laugh-wink|fas fa-layer-group|fas fa-leaf|fab fa-leanpub|fas fa-lemon|far fa-lemon|fab fa-less|fas fa-less-than|fas fa-less-than-equal|fas fa-level-down-alt|fas fa-level-up-alt|fas fa-life-ring|far fa-life-ring|fas fa-lightbulb|far fa-lightbulb|fab fa-line|fas fa-link|fab fa-linkedin|fab fa-linkedin-in|fab fa-linode|fab fa-linux|fas fa-lira-sign|fas fa-list|fas fa-list-alt|far fa-list-alt|fas fa-list-ol|fas fa-list-ul|fas fa-location-arrow|fas fa-lock|fas fa-lock-open|fas fa-long-arrow-alt-down|fas fa-long-arrow-alt-left|fas fa-long-arrow-alt-right|fas fa-long-arrow-alt-up|fas fa-low-vision|fas fa-luggage-cart|fas fa-lungs|fas fa-lungs-virus|fab fa-lyft|fab fa-magento|fas fa-magic|fas fa-magnet|fas fa-mail-bulk|fab fa-mailchimp|fas fa-male|fab fa-mandalorian|fas fa-map|far fa-map|fas fa-map-marked|fas fa-map-marked-alt|fas fa-map-marker|fas fa-map-marker-alt|fas fa-map-pin|fas fa-map-signs|fab fa-markdown|fas fa-marker|fas fa-mars|fas fa-mars-double|fas fa-mars-stroke|fas fa-mars-stroke-h|fas fa-mars-stroke-v|fas fa-mask|fab fa-mastodon|fab fa-maxcdn|fab fa-mdb|fas fa-medal|fab fa-medapps|fab fa-medium|fab fa-medium-m|fas fa-medkit|fab fa-medrt|fab fa-meetup|fab fa-megaport|fas fa-meh|far fa-meh|fas fa-meh-blank|far fa-meh-blank|fas fa-meh-rolling-eyes|far fa-meh-rolling-eyes|fas fa-memory|fab fa-mendeley|fas fa-menorah|fas fa-mercury|fas fa-meteor|fab fa-microblog|fas fa-microchip|fas fa-microphone|fas fa-microphone-alt|fas fa-microphone-alt-slash|fas fa-microphone-slash|fas fa-microscope|fab fa-microsoft|fas fa-minus|fas fa-minus-circle|fas fa-minus-square|far fa-minus-square|fas fa-mitten|fab fa-mix|fab fa-mixcloud|fab fa-mixer|fab fa-mizuni|fas fa-mobile|fas fa-mobile-alt|fab fa-modx|fab fa-monero|fas fa-money-bill|fas fa-money-bill-alt|far fa-money-bill-alt|fas fa-money-bill-wave|fas fa-money-bill-wave-alt|fas fa-money-check|fas fa-money-check-alt|fas fa-monument|fas fa-moon|far fa-moon|fas fa-mortar-pestle|fas fa-mosque|fas fa-motorcycle|fas fa-mountain|fas fa-mouse|fas fa-mouse-pointer|fas fa-mug-hot|fas fa-music|fab fa-napster|fab fa-neos|fas fa-network-wired|fas fa-neuter|fas fa-newspaper|far fa-newspaper|fab fa-nimblr|fab fa-node|fab fa-node-js|fas fa-not-equal|fas fa-notes-medical|fab fa-npm|fab fa-ns8|fab fa-nutritionix|fas fa-object-group|far fa-object-group|fas fa-object-ungroup|far fa-object-ungroup|fab fa-octopus-deploy|fab fa-odnoklassniki|fab fa-odnoklassniki-square|fas fa-oil-can|fab fa-old-republic|fas fa-om|fab fa-opencart|fab fa-openid|fab fa-opera|fab fa-optin-monster|fab fa-orcid|fab fa-osi|fas fa-otter|fas fa-outdent|fab fa-page4|fab fa-pagelines|fas fa-pager|fas fa-paint-brush|fas fa-paint-roller|fas fa-palette|fab fa-palfed|fas fa-pallet|fas fa-paper-plane|far fa-paper-plane|fas fa-paperclip|fas fa-parachute-box|fas fa-paragraph|fas fa-parking|fas fa-passport|fas fa-pastafarianism|fas fa-paste|fab fa-patreon|fas fa-pause|fas fa-pause-circle|far fa-pause-circle|fas fa-paw|fab fa-paypal|fas fa-peace|fas fa-pen|fas fa-pen-alt|fas fa-pen-fancy|fas fa-pen-nib|fas fa-pen-square|fas fa-pencil-alt|fas fa-pencil-ruler|fab fa-penny-arcade|fas fa-people-arrows|fas fa-people-carry|fas fa-pepper-hot|fab fa-perbyte|fas fa-percent|fas fa-percentage|fab fa-periscope|fas fa-person-booth|fab fa-phabricator|fab fa-phoenix-framework|fab fa-phoenix-squadron|fas fa-phone|fas fa-phone-alt|fas fa-phone-slash|fas fa-phone-square|fas fa-phone-square-alt|fas fa-phone-volume|fas fa-photo-video|fab fa-php|fab fa-pied-piper|fab fa-pied-piper-alt|fab fa-pied-piper-hat|fab fa-pied-piper-pp|fab fa-pied-piper-square|fas fa-piggy-bank|fas fa-pills|fab fa-pinterest|fab fa-pinterest-p|fab fa-pinterest-square|fas fa-pizza-slice|fas fa-place-of-worship|fas fa-plane|fas fa-plane-arrival|fas fa-plane-departure|fas fa-plane-slash|fas fa-play|fas fa-play-circle|far fa-play-circle|fab fa-playstation|fas fa-plug|fas fa-plus|fas fa-plus-circle|fas fa-plus-square|far fa-plus-square|fas fa-podcast|fas fa-poll|fas fa-poll-h|fas fa-poo|fas fa-poo-storm|fas fa-poop|fas fa-portrait|fas fa-pound-sign|fas fa-power-off|fas fa-pray|fas fa-praying-hands|fas fa-prescription|fas fa-prescription-bottle|fas fa-prescription-bottle-alt|fas fa-print|fas fa-procedures|fab fa-product-hunt|fas fa-project-diagram|fas fa-pump-medical|fas fa-pump-soap|fab fa-pushed|fas fa-puzzle-piece|fab fa-python|fab fa-qq|fas fa-qrcode|fas fa-question|fas fa-question-circle|far fa-question-circle|fas fa-quidditch|fab fa-quinscape|fab fa-quora|fas fa-quote-left|fas fa-quote-right|fas fa-quran|fab fa-r-project|fas fa-radiation|fas fa-radiation-alt|fas fa-rainbow|fas fa-random|fab fa-raspberry-pi|fab fa-ravelry|fab fa-react|fab fa-reacteurope|fab fa-readme|fab fa-rebel|fas fa-receipt|fas fa-record-vinyl|fas fa-recycle|fab fa-red-river|fab fa-reddit|fab fa-reddit-alien|fab fa-reddit-square|fab fa-redhat|fas fa-redo|fas fa-redo-alt|fas fa-registered|far fa-registered|fas fa-remove-format|fab fa-renren|fas fa-reply|fas fa-reply-all|fab fa-replyd|fas fa-republican|fab fa-researchgate|fab fa-resolving|fas fa-restroom|fas fa-retweet|fab fa-rev|fas fa-ribbon|fas fa-ring|fas fa-road|fas fa-robot|fas fa-rocket|fab fa-rocketchat|fab fa-rockrms|fas fa-route|fas fa-rss|fas fa-rss-square|fas fa-ruble-sign|fas fa-ruler|fas fa-ruler-combined|fas fa-ruler-horizontal|fas fa-ruler-vertical|fas fa-running|fas fa-rupee-sign|fab fa-rust|fas fa-sad-cry|far fa-sad-cry|fas fa-sad-tear|far fa-sad-tear|fab fa-safari|fab fa-salesforce|fab fa-sass|fas fa-satellite|fas fa-satellite-dish|fas fa-save|far fa-save|fab fa-schlix|fas fa-school|fas fa-screwdriver|fab fa-scribd|fas fa-scroll|fas fa-sd-card|fas fa-search|fas fa-search-dollar|fas fa-search-location|fas fa-search-minus|fas fa-search-plus|fab fa-searchengin|fas fa-seedling|fab fa-sellcast|fab fa-sellsy|fas fa-server|fab fa-servicestack|fas fa-shapes|fas fa-share|fas fa-share-alt|fas fa-share-alt-square|fas fa-share-square|far fa-share-square|fas fa-shekel-sign|fas fa-shield-alt|fas fa-shield-virus|fas fa-ship|fas fa-shipping-fast|fab fa-shirtsinbulk|fas fa-shoe-prints|fab fa-shopify|fas fa-shopping-bag|fas fa-shopping-basket|fas fa-shopping-cart|fab fa-shopware|fas fa-shower|fas fa-shuttle-van|fas fa-sign|fas fa-sign-in-alt|fas fa-sign-language|fas fa-sign-out-alt|fas fa-signal|fas fa-signature|fas fa-sim-card|fab fa-simplybuilt|fas fa-sink|fab fa-sistrix|fas fa-sitemap|fab fa-sith|fas fa-skating|fab fa-sketch|fas fa-skiing|fas fa-skiing-nordic|fas fa-skull|fas fa-skull-crossbones|fab fa-skyatlas|fab fa-skype|fab fa-slack|fab fa-slack-hash|fas fa-slash|fas fa-sleigh|fas fa-sliders-h|fab fa-slideshare|fas fa-smile|far fa-smile|fas fa-smile-beam|far fa-smile-beam|fas fa-smile-wink|far fa-smile-wink|fas fa-smog|fas fa-smoking|fas fa-smoking-ban|fas fa-sms|fab fa-snapchat|fab fa-snapchat-ghost|fab fa-snapchat-square|fas fa-snowboarding|fas fa-snowflake|far fa-snowflake|fas fa-snowman|fas fa-snowplow|fas fa-soap|fas fa-socks|fas fa-solar-panel|fas fa-sort|fas fa-sort-alpha-down|fas fa-sort-alpha-down-alt|fas fa-sort-alpha-up|fas fa-sort-alpha-up-alt|fas fa-sort-amount-down|fas fa-sort-amount-down-alt|fas fa-sort-amount-up|fas fa-sort-amount-up-alt|fas fa-sort-down|fas fa-sort-numeric-down|fas fa-sort-numeric-down-alt|fas fa-sort-numeric-up|fas fa-sort-numeric-up-alt|fas fa-sort-up|fab fa-soundcloud|fab fa-sourcetree|fas fa-spa|fas fa-space-shuttle|fab fa-speakap|fab fa-speaker-deck|fas fa-spell-check|fas fa-spider|fas fa-spinner|fas fa-splotch|fab fa-spotify|fas fa-spray-can|fas fa-square|far fa-square|fas fa-square-full|fas fa-square-root-alt|fab fa-squarespace|fab fa-stack-exchange|fab fa-stack-overflow|fab fa-stackpath|fas fa-stamp|fas fa-star|far fa-star|fas fa-star-and-crescent|fas fa-star-half|far fa-star-half|fas fa-star-half-alt|fas fa-star-of-david|fas fa-star-of-life|fab fa-staylinked|fab fa-steam|fab fa-steam-square|fab fa-steam-symbol|fas fa-step-backward|fas fa-step-forward|fas fa-stethoscope|fab fa-sticker-mule|fas fa-sticky-note|far fa-sticky-note|fas fa-stop|fas fa-stop-circle|far fa-stop-circle|fas fa-stopwatch|fas fa-stopwatch-20|fas fa-store|fas fa-store-alt|fas fa-store-alt-slash|fas fa-store-slash|fab fa-strava|fas fa-stream|fas fa-street-view|fas fa-strikethrough|fab fa-stripe|fab fa-stripe-s|fas fa-stroopwafel|fab fa-studiovinari|fab fa-stumbleupon|fab fa-stumbleupon-circle|fas fa-subscript|fas fa-subway|fas fa-suitcase|fas fa-suitcase-rolling|fas fa-sun|far fa-sun|fab fa-superpowers|fas fa-superscript|fab fa-supple|fas fa-surprise|far fa-surprise|fab fa-suse|fas fa-swatchbook|fab fa-swift|fas fa-swimmer|fas fa-swimming-pool|fab fa-symfony|fas fa-synagogue|fas fa-sync|fas fa-sync-alt|fas fa-syringe|fas fa-table|fas fa-table-tennis|fas fa-tablet|fas fa-tablet-alt|fas fa-tablets|fas fa-tachometer-alt|fas fa-tag|fas fa-tags|fas fa-tape|fas fa-tasks|fas fa-taxi|fab fa-teamspeak|fas fa-teeth|fas fa-teeth-open|fab fa-telegram|fab fa-telegram-plane|fas fa-temperature-high|fas fa-temperature-low|fab fa-tencent-weibo|fas fa-tenge|fas fa-terminal|fas fa-text-height|fas fa-text-width|fas fa-th|fas fa-th-large|fas fa-th-list|fab fa-the-red-yeti|fas fa-theater-masks|fab fa-themeco|fab fa-themeisle|fas fa-thermometer|fas fa-thermometer-empty|fas fa-thermometer-full|fas fa-thermometer-half|fas fa-thermometer-quarter|fas fa-thermometer-three-quarters|fab fa-think-peaks|fas fa-thumbs-down|far fa-thumbs-down|fas fa-thumbs-up|far fa-thumbs-up|fas fa-thumbtack|fas fa-ticket-alt|fab fa-tiktok|fas fa-times|fas fa-times-circle|far fa-times-circle|fas fa-tint|fas fa-tint-slash|fas fa-tired|far fa-tired|fas fa-toggle-off|fas fa-toggle-on|fas fa-toilet|fas fa-toilet-paper|fas fa-toilet-paper-slash|fas fa-toolbox|fas fa-tools|fas fa-tooth|fas fa-torah|fas fa-torii-gate|fas fa-tractor|fab fa-trade-federation|fas fa-trademark|fas fa-traffic-light|fas fa-trailer|fas fa-train|fas fa-tram|fas fa-transgender|fas fa-transgender-alt|fas fa-trash|fas fa-trash-alt|far fa-trash-alt|fas fa-trash-restore|fas fa-trash-restore-alt|fas fa-tree|fab fa-trello|fas fa-trophy|fas fa-truck|fas fa-truck-loading|fas fa-truck-monster|fas fa-truck-moving|fas fa-truck-pickup|fas fa-tshirt|fas fa-tty|fab fa-tumblr|fab fa-tumblr-square|fas fa-tv|fab fa-twitch|fab fa-twitter|fab fa-twitter-square|fab fa-typo3|fab fa-uber|fab fa-ubuntu|fab fa-uikit|fab fa-umbraco|fas fa-umbrella|fas fa-umbrella-beach|fab fa-uncharted|fas fa-underline|fas fa-undo|fas fa-undo-alt|fab fa-uniregistry|fab fa-unity|fas fa-universal-access|fas fa-university|fas fa-unlink|fas fa-unlock|fas fa-unlock-alt|fab fa-unsplash|fab fa-untappd|fas fa-upload|fab fa-ups|fab fa-usb|fas fa-user|far fa-user|fas fa-user-alt|fas fa-user-alt-slash|fas fa-user-astronaut|fas fa-user-check|fas fa-user-circle|far fa-user-circle|fas fa-user-clock|fas fa-user-cog|fas fa-user-edit|fas fa-user-friends|fas fa-user-graduate|fas fa-user-injured|fas fa-user-lock|fas fa-user-md|fas fa-user-minus|fas fa-user-ninja|fas fa-user-nurse|fas fa-user-plus|fas fa-user-secret|fas fa-user-shield|fas fa-user-slash|fas fa-user-tag|fas fa-user-tie|fas fa-user-times|fas fa-users|fas fa-users-cog|fas fa-users-slash|fab fa-usps|fab fa-ussunnah|fas fa-utensil-spoon|fas fa-utensils|fab fa-vaadin|fas fa-vector-square|fas fa-venus|fas fa-venus-double|fas fa-venus-mars|fas fa-vest|fas fa-vest-patches|fab fa-viacoin|fab fa-viadeo|fab fa-viadeo-square|fas fa-vial|fas fa-vials|fab fa-viber|fas fa-video|fas fa-video-slash|fas fa-vihara|fab fa-vimeo|fab fa-vimeo-square|fab fa-vimeo-v|fab fa-vine|fas fa-virus|fas fa-virus-slash|fas fa-viruses|fab fa-vk|fab fa-vnv|fas fa-voicemail|fas fa-volleyball-ball|fas fa-volume-down|fas fa-volume-mute|fas fa-volume-off|fas fa-volume-up|fas fa-vote-yea|fas fa-vr-cardboard|fab fa-vuejs|fas fa-walking|fas fa-wallet|fas fa-warehouse|fab fa-watchman-monitoring|fas fa-water|fas fa-wave-square|fab fa-waze|fab fa-weebly|fab fa-weibo|fas fa-weight|fas fa-weight-hanging|fab fa-weixin|fab fa-whatsapp|fab fa-whatsapp-square|fas fa-wheelchair|fab fa-whmcs|fas fa-wifi|fab fa-wikipedia-w|fas fa-wind|fas fa-window-close|far fa-window-close|fas fa-window-maximize|far fa-window-maximize|fas fa-window-minimize|far fa-window-minimize|fas fa-window-restore|far fa-window-restore|fab fa-windows|fas fa-wine-bottle|fas fa-wine-glass|fas fa-wine-glass-alt|fab fa-wix|fab fa-wizards-of-the-coast|fab fa-wodu|fab fa-wolf-pack-battalion|fas fa-won-sign|fab fa-wordpress|fab fa-wordpress-simple|fab fa-wpbeginner|fab fa-wpexplorer|fab fa-wpforms|fab fa-wpressr|fas fa-wrench|fas fa-x-ray|fab fa-xbox|fab fa-xing|fab fa-xing-square|fab fa-y-combinator|fab fa-yahoo|fab fa-yammer|fab fa-yandex|fab fa-yandex-international|fab fa-yarn|fab fa-yelp|fas fa-yen-sign|fas fa-yin-yang|fab fa-yoast|fab fa-youtube|fab fa-youtube-square|fab fa-zhihu',
							),
						),
					)
				)
			);
		}
	}
endif;
