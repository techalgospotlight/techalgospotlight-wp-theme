<?php
/**
 * techalgospotlight compatibility class for Header Footer Elementor plugin.
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

// Return if Elementor not active.
if (!class_exists('\Elementor\Plugin')) {
	return;
}

// Return if HFE not active.
if (!class_exists('Header_Footer_Elementor')) {
	return false;
}

if (!class_exists('techalgospotlight_HFE')):

	/**
	 * HFE compatibility.
	 */
	class techalgospotlight_HFE
	{

		/**
		 * Singleton instance of the class.
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Instance.
		 *
		 * @since 1.0.0
		 * @return techalgospotlight_HFE
		 */
		public static function instance()
		{
			if (!isset(self::$instance) && !(self::$instance instanceof techalgospotlight_HFE)) {
				self::$instance = new techalgospotlight_HFE();
			}
			return self::$instance;
		}

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct()
		{
			add_action('after_setup_theme', array($this, 'add_theme_support'));
			add_action('techalgospotlight_header', array($this, 'do_header'), 0);
			add_action('techalgospotlight_footer', array($this, 'do_footer'), 0);
		}

		/**
		 * Add theme support
		 *
		 * @since 1.0.0
		 */
		public function add_theme_support()
		{
			add_theme_support('header-footer-elementor');
		}

		/**
		 * Override Header
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function do_header()
		{
			if (!hfe_header_enabled()) {
				return;
			}

			hfe_render_header();

			remove_action('techalgospotlight_header', 'techalgospotlight_topbar_output', 10);
			remove_action('techalgospotlight_header', 'techalgospotlight_header_output', 20);
		}

		/**
		 * Override Footer
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function do_footer()
		{
			if (!hfe_footer_enabled()) {
				return;
			}

			hfe_render_footer();

			remove_action('techalgospotlight_footer', 'techalgospotlight_footer_output', 20);
			remove_action('techalgospotlight_footer', 'techalgospotlight_copyright_bar_output', 30);
		}

	}

endif;

/**
 * Returns the one techalgospotlight_HFE instance.
 */
function techalgospotlight_hfe()
{
	return techalgospotlight_HFE::instance();
}

techalgospotlight_hfe();
