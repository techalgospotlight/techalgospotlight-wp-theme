<?php //phpcs:ignore
/**
 * Theme functions and definitions.
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

/**
 * Main techalgospotlight class.
 *
 * @since 1.0.0
 */
final class techalgospotlight
{

	/**
	 * Theme options
	 *
	 * @since 1.0.0
	 * @var object
	 */
	public $options;

	/**
	 * Theme fonts
	 *
	 * @since 1.0.0
	 * @var object
	 */
	public $fonts;

	/**
	 * Theme icons
	 *
	 * @since 1.0.0
	 * @var object
	 */
	public $icons;

	/**
	 * Theme customizer
	 *
	 * @since 1.0.0
	 * @var object
	 */
	public $customizer;

	/**
	 * Theme admin
	 *
	 * @since 1.0.0
	 * @var object
	 */
	public $admin;

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;
	/**
	 * Theme version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version = '1.0.16';
	/**
	 * Main techalgospotlight Instance.
	 *
	 * Insures that only one instance of techalgospotlight exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0.0
	 * @return techalgospotlight
	 */
	public static function instance()
	{
		if (!isset(self::$instance) && !(self::$instance instanceof techalgospotlight)) {
			self::$instance = new techalgospotlight();
			self::$instance->constants();
			self::$instance->includes();
			self::$instance->objects();
			// Hook now that all of the techalgospotlight stuff is loaded.
			do_action('techalgospotlight_loaded');
		}
		return self::$instance;
	}



	/**
	 * Setup constants.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function constants()
	{
		if (!defined('techalgospotlight_THEME_VERSION')) {
			define('techalgospotlight_THEME_VERSION', $this->version);
		}
		if (!defined('techalgospotlight_THEME_URI')) {
			define('techalgospotlight_THEME_URI', get_parent_theme_file_uri());
		}
		if (!defined('techalgospotlight_THEME_PATH')) {
			define('techalgospotlight_THEME_PATH', get_parent_theme_file_path());
		}
	}


	/**
	 * Include files.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function includes()
	{
		require_once techalgospotlight_THEME_PATH . '/inc/common.php';
		require_once techalgospotlight_THEME_PATH . '/inc/helpers.php';
		require_once techalgospotlight_THEME_PATH . '/inc/widgets.php';
		require_once techalgospotlight_THEME_PATH . '/inc/template-tags.php';
		require_once techalgospotlight_THEME_PATH . '/inc/template-parts.php';
		require_once techalgospotlight_THEME_PATH . '/inc/icon-functions.php';
		require_once techalgospotlight_THEME_PATH . '/inc/breadcrumbs.php';
		require_once techalgospotlight_THEME_PATH . '/inc/class-techalgospotlight-dynamic-styles.php';
		// Core.
		require_once techalgospotlight_THEME_PATH . '/inc/core/class-techalgospotlight-options.php';
		require_once techalgospotlight_THEME_PATH . '/inc/core/class-techalgospotlight-enqueue-scripts.php';
		require_once techalgospotlight_THEME_PATH . '/inc/core/class-techalgospotlight-fonts.php';
		require_once techalgospotlight_THEME_PATH . '/inc/core/class-techalgospotlight-theme-setup.php';
		// Compatibility.
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/woocommerce/class-techalgospotlight-woocommerce.php';
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/socialsnap/class-techalgospotlight-socialsnap.php';
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/class-techalgospotlight-wpforms.php';
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/class-techalgospotlight-jetpack.php';
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/class-techalgospotlight-beaver-themer.php';
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/class-techalgospotlight-elementor.php';
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/class-techalgospotlight-elementor-pro.php';
		require_once techalgospotlight_THEME_PATH . '/inc/compatibility/class-techalgospotlight-hfe.php';


		new techalgospotlight_Enqueue_Scripts();
		// Customizer.
		require_once techalgospotlight_THEME_PATH . '/inc/customizer/class-techalgospotlight-customizer.php';
		require_once techalgospotlight_THEME_PATH . '/inc/customizer/customizer-callbacks.php';
		require_once techalgospotlight_THEME_PATH . '/inc/customizer/class-techalgospotlight-section-ordering.php';
	}
	/**
	 * Setup objects to be used throughout the theme.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function objects()
	{

		techalgospotlight()->options = new techalgospotlight_Options();
		techalgospotlight()->fonts = new techalgospotlight_Fonts();
		techalgospotlight()->icons = new techalgospotlight_Icons();
		techalgospotlight()->customizer = new techalgospotlight_Customizer();
	}
}

/**
 * The function which returns the one techalgospotlight instance.
 *
 * @since 1.0.0
 * @return object
 */
function techalgospotlight()
{
	return techalgospotlight::instance();
}

techalgospotlight();

