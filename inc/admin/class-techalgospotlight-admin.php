<?php
/**
 * Admin class.
 *
 * This class ties together all admin classes.
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

if (!class_exists('techalgospotlight_Admin')):

	/**
	 * Admin Class
	 */
	class techalgospotlight_Admin
	{

		/**
		 * Primary class constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct()
		{

			/**
			 * Include admin files.
			 */
			$this->includes();

			/**
			 * Load admin assets.
			 */
			add_action('admin_enqueue_scripts', array($this, 'load_assets'));

			/**
			 * Add filters for WordPress header and footer text.
			 */
			add_filter('update_footer', array($this, 'filter_update_footer'), 50);
			add_filter('admin_footer_text', array($this, 'filter_admin_footer_text'), 50);

			/**
			 * Admin page header.
			 */
			add_action('in_admin_header', array($this, 'admin_header'), 100);

			/**
			 * Admin page footer.
			 */
			add_action('in_admin_footer', array($this, 'admin_footer'), 100);

			/**
			 * Add notices.
			 */
			add_action('admin_notices', array($this, 'admin_notices'));

			/**
			 * After admin loaded
			 */
			do_action('techalgospotlight_admin_loaded');
		}

		/**
		 * Includes files.
		 *
		 * @since 1.0.0
		 */
		private function includes()
		{

			/**
			 * Include helper functions.
			 */
			require_once techalgospotlight_THEME_PATH . '/inc/admin/helpers.php'; // phpcs:ignore

			/**
			 * Include techalgospotlight welcome page.
			 */
			require_once techalgospotlight_THEME_PATH . '/inc/admin/class-techalgospotlight-dashboard.php'; // phpcs:ignore

			/**
			 * Guten block
			 */
			// require_once techalgospotlight_THEME_PATH . '/inc/admin/guten-block.php'; // phpcs:ignore

			/**
			 * Include techalgospotlight meta boxes.
			 */
			require_once techalgospotlight_THEME_PATH . '/inc/admin/metabox/class-techalgospotlight-meta-boxes.php'; // phpcs:ignore

		}

		/**
		 * Load our required assets on admin pages.
		 *
		 * @since 1.0.0
		 * @param string $hook it holds the information about the current page.
		 */
		public function load_assets($hook)
		{
			/**
			 * Do not enqueue if we are not on one of our pages.
			 */
			if (!techalgospotlight_is_admin_page($hook)) {
				return;
			}

			// Script debug.
			$prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? 'dev/' : '';
			$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

			/**
			 * Enqueue admin pages stylesheet.
			 */
			wp_enqueue_style(
				'techalgospotlight-admin-styles',
				techalgospotlight_THEME_URI . '/inc/admin/assets/css/techalgospotlight-admin' . $suffix . '.css',
				false,
				techalgospotlight_THEME_VERSION
			);

			/**
			 * Enqueue admin pages script.
			 */
			wp_enqueue_script(
				'techalgospotlight-admin-script',
				techalgospotlight_THEME_URI . '/inc/admin/assets/js/' . $prefix . 'techalgospotlight-admin' . $suffix . '.js',
				array('jquery', 'wp-util', 'updates'),
				techalgospotlight_THEME_VERSION,
				true
			);

			/**
			 * Localize admin strings.
			 */
			$texts = array(
				'install' => esc_html__('Install', 'techalgospotlight'),
				'install-inprogress' => esc_html__('Installing...', 'techalgospotlight'),
				'activate-inprogress' => esc_html__('Activating...', 'techalgospotlight'),
				'deactivate-inprogress' => esc_html__('Deactivating...', 'techalgospotlight'),
				'active' => esc_html__('Active', 'techalgospotlight'),
				'retry' => esc_html__('Retry', 'techalgospotlight'),
				'please_wait' => esc_html__('Please Wait...', 'techalgospotlight'),
				'importing' => esc_html__('Importing... Please Wait...', 'techalgospotlight'),
				'currently_processing' => esc_html__('Currently processing: ', 'techalgospotlight'),
				'import' => esc_html__('Import', 'techalgospotlight'),
				'import_demo' => esc_html__('Import Demo', 'techalgospotlight'),
				'importing_notice' => esc_html__('The demo importer is still working. Closing this window may result in failed import.', 'techalgospotlight'),
				'import_complete' => esc_html__('Import Complete!', 'techalgospotlight'),
				'import_complete_desc' => esc_html__('The demo has been imported.', 'techalgospotlight') . ' <a href="' . esc_url(get_home_url()) . '">' . esc_html__('Visit site.', 'techalgospotlight') . '</a>',
			);

			$strings = array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'wpnonce' => wp_create_nonce('techalgospotlight_nonce'),
				'texts' => $texts,
				'color_pallete' => array('#F43676', '#06cca6', '#2c2e3a', '#e4e7ec', '#f0b849', '#ffffff', '#000000'),
			);

			$strings = apply_filters('techalgospotlight_admin_strings', $strings);

			wp_localize_script('techalgospotlight-admin-script', 'hester_strings', $strings);
		}

		/**
		 * Filters WordPress footer right text to hide all text.
		 *
		 * @since 1.0.0
		 * @param string $text Text that we're going to replace.
		 */
		public function filter_update_footer($text)
		{

			$base = get_current_screen()->base;

			/**
			 * Only do this if we are on one of our plugin pages.
			 */
			if (techalgospotlight_is_admin_page($base)) {
				return apply_filters('techalgospotlight_footer_version', esc_html__('Techalgospotlight Theme', 'techalgospotlight') . ' ' . techalgospotlight_THEME_VERSION . '<br/><a href="' . esc_url('https://twitter.com/peregrine-themes') . '" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a><a href="' . esc_url('https://facebook.com/peregrine-themes') . '" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a>');
			} else {
				return $text;
			}
		}

		/**
		 * Filter WordPress footer left text to display our text.
		 *
		 * @since 1.0.0
		 * @param string $text Text that we're going to replace.
		 */
		public function filter_admin_footer_text($text)
		{

			if (techalgospotlight_is_admin_page()) {
				return;
			}

			return $text;
		}

		/**
		 * Outputs the page admin header.
		 *
		 * @since 1.0.0
		 */
		public function admin_header()
		{

			$base = get_current_screen()->base;

			if (!techalgospotlight_is_admin_page($base)) {
				return;
			}
			?>

			<div id="hester-header">
				<div class="hester-container">

					<a href="<?php echo esc_url(admin_url('admin.php?page=techalgospotlight-dashboard')); ?>" class="hester-logo">
						<img src="<?php echo esc_url(techalgospotlight_THEME_URI . '/assets/images/techalgospotlight-logo.svg'); ?>"
							alt="<?php echo esc_html('techalgospotlight'); ?>" />
					</a>

					<span class="hester-header-action">
						<a
							href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php esc_html_e('Customize', 'techalgospotlight'); ?></a>
						<a href="<?php echo esc_url('http://docs.peregrine-themes.com/'); ?>" target="_blank"
							rel="noopener noreferrer"><?php esc_html_e('Help Articles', 'techalgospotlight'); ?></a>
					</span>

				</div>
			</div><!-- END #hester-header -->
			<?php
		}

		/**
		 * Outputs the page admin footer.
		 *
		 * @since 1.0.0
		 */
		public function admin_footer()
		{

			$base = get_current_screen()->base;

			if (!techalgospotlight_is_admin_page($base) || techalgospotlight_is_admin_page($base, 'hester_wizard')) {
				return;
			}
			?>
			<div id="hester-footer">
				<ul>
					<li><a href="<?php echo esc_url('http://docs.peregrine-themes.com/'); ?>" target="_blank"
							rel="noopener noreferrer"><span><?php esc_html_e('Help Articles', 'techalgospotlight'); ?></span></span></a>
					</li>
					<li><a href="<?php echo esc_url('https://www.facebook.com/groups/peregrine-themes/'); ?>" target="_blank"
							rel="noopener noreferrer"><span><?php esc_html_e('Join Facebook Group', 'techalgospotlight'); ?></span></span></a>
					</li>
					<li><a href="<?php echo esc_url('https://wordpress.org/support/theme/techalgospotlight/reviews/#new-post'); ?>"
							target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-heart"
								aria-hidden="true"></span><span><?php esc_html_e('Leave a Review', 'techalgospotlight'); ?></span></a></li>
				</ul>
			</div><!-- END #hester-footer -->

			<?php
		}

		/**
		 * Admin Notices
		 *
		 * @since 1.0.0
		 */
		public function admin_notices()
		{

			$screen = get_current_screen();

			// Display on Dashboard, Themes and techalgospotlight admin pages.
			if (!in_array($screen->base, array('dashboard', 'themes'), true) && !techalgospotlight_is_admin_page()) {
				return;
			}

			// Display if not dismissed and not on techalgospotlight plugins page.
			if (!techalgospotlight_is_notice_dismissed('techalgospotlight_notice_recommended-plugins') && !techalgospotlight_is_admin_page(false, 'techalgospotlight-plugins')) {

				$plugins = techalgospotlight_plugin_utilities()->get_recommended_plugins();
				$plugins = techalgospotlight_plugin_utilities()->get_deactivated_plugins($plugins);

				$plugin_list = '';

				if (is_array($plugins) && !empty($plugins)) {

					foreach ($plugins as $slug => $plugin) {

						$url = admin_url('plugin-install.php?tab=plugin-information&plugin=' . esc_attr($slug) . '&TB_iframe=true&width=990&height=500');

						$plugin_list .= '<a href="' . esc_url($url) . '" class="thickbox">' . esc_html($plugin['name']) . '</a>, ';
					}

					wp_enqueue_script('plugin-install');
					add_thickbox();

					$plugin_list = trim($plugin_list, ', ');

					/* translators: %1$s <strong> tag, %2$s </strong> tag */
					// $message = sprintf(wp_kses(__('techalgospotlight theme recommends the following plugins: %1$s.', 'techalgospotlight'), techalgospotlight_get_allowed_html_tags()), $plugin_list);

					$navigation_items = techalgospotlight_dashboard()->get_navigation_items();

					// techalgospotlight_print_notice(
					// 	array(
					// 		'type' => 'info',
					// 		'message' => $message,
					// 		'message_id' => 'recommended-plugins',
					// 		'expires' => 7 * 24 * 60 * 60,
					// 		'action_link' => $navigation_items['plugins']['url'],
					// 		'action_text' => esc_html__('Install Now', 'techalgospotlight'),
					// 	)
					// );
				}
			}

		}
	}
endif;
