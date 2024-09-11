<?php
/**
 * WP Forms compatibility class.
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

if (!class_exists('techalgospotlight_WPForms')):
	/**
	 * WPForms compatibility class.
	 */
	class techalgospotlight_WPForms
	{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			add_action('activate_wpforms-lite/wpforms.php', array($this, 'disable_redirect_on_activation'), 20);

			// If WPForms is not activated then return.
			if (!class_exists('WPForms')) {
				return;
			}
		}

		/**
		 * Disable admin page redirect on plugin activation.
		 *
		 * @since 1.0.0
		 */
		public function disable_redirect_on_activation()
		{
			delete_transient('wpforms_activation_redirect');
		}
	}
endif;
new techalgospotlight_WPForms();
