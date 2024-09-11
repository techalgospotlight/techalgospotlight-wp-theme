<?php
/**
 * Enqueue scripts & styles.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

/**
 * Enqueue and register scripts and styles.
 *
 * @since 1.0.0
 */
class techalgospotlight_Enqueue_Scripts
{

	/**
	 * Check if debug is on
	 *
	 * @var boolean
	 */
	private $is_debug = 'dev';

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->is_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;
		add_action('wp_enqueue_scripts', array($this, 'techalgospotlight_enqueues'));
		add_action('wp_print_footer_scripts', array($this, 'techalgospotlight_skip_link_focus_fix'));
		add_action('enqueue_block_editor_assets', array($this, 'techalgospotlight_block_editor_assets'));
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since 1.0.0
	 */
	public function techalgospotlight_enqueues()
	{
		// Script debug.
		$techalgospotlight_dir = $this->is_debug ? 'dev/' : '';
		$techalgospotlight_suffix = $this->is_debug ? '' : '.min';

		wp_enqueue_style('swiper', techalgospotlight_THEME_URI . '/assets/css/swiper-bundle' . $techalgospotlight_suffix . '.css');

		wp_enqueue_script('swiper', techalgospotlight_THEME_URI . '/assets/js/' . $techalgospotlight_dir . 'vendors/swiper-bundle' . $techalgospotlight_suffix . '.js', array(), false, true);

		// fontawesome enqueue.
		wp_enqueue_style(
			'FontAwesome',
			techalgospotlight_THEME_URI . '/assets/css/all' . $techalgospotlight_suffix . '.css',
			false,
			'5.15.4',
			'all'
		);
		// Enqueue theme stylesheet.
		wp_enqueue_style(
			'techalgospotlight-styles',
			techalgospotlight_THEME_URI . '/assets/css/style' . $techalgospotlight_suffix . '.css',
			false,
			techalgospotlight_THEME_VERSION,
			'all'
		);

		// Enqueue IE specific styles.
		wp_enqueue_style(
			'techalgospotlight-ie',
			techalgospotlight_THEME_URI . '/assets/css/compatibility/ie' . $techalgospotlight_suffix . '.css',
			false,
			techalgospotlight_THEME_VERSION,
			'all'
		);

		wp_style_add_data('techalgospotlight-ie', 'conditional', 'IE');

		// Enqueue HTML5 shiv.
		wp_register_script(
			'html5shiv',
			techalgospotlight_THEME_URI . '/assets/js/' . $techalgospotlight_dir . 'vendors/html5' . $techalgospotlight_suffix . '.js',
			array(),
			'3.7.3',
			true
		);

		// Load only on < IE9.
		wp_script_add_data(
			'html5shiv',
			'conditional',
			'lt IE 9'
		);

		// Flexibility.js for crossbrowser flex support.
		wp_enqueue_script(
			'techalgospotlight-flexibility',
			techalgospotlight_THEME_URI . '/assets/js/' . $techalgospotlight_dir . 'vendors/flexibility' . $techalgospotlight_suffix . '.js',
			array(),
			techalgospotlight_THEME_VERSION,
			false
		);

		wp_add_inline_script(
			'techalgospotlight-flexibility',
			'flexibility(document.documentElement);'
		);

		wp_script_add_data(
			'techalgospotlight-flexibility',
			'conditional',
			'IE'
		);

		// Register techalgospotlight slider.
		wp_register_script(
			'techalgospotlight-slider',
			techalgospotlight_THEME_URI . '/assets/js/' . $techalgospotlight_dir . 'techalgospotlight-slider' . $techalgospotlight_suffix . '.js',
			array('imagesloaded'),
			techalgospotlight_THEME_VERSION,
			true
		);

		wp_register_script(
			'techalgospotlight-marquee',
			techalgospotlight_THEME_URI . '/assets/js/' . $techalgospotlight_dir . 'vendors/vanilla-marquee' . $techalgospotlight_suffix . '.js',
			array('imagesloaded'),
			techalgospotlight_THEME_VERSION,
			true
		);

		if (techalgospotlight()->options->get('techalgospotlight_blog_masonry')) {
			wp_enqueue_script('masonry');
		}

		// Load comment reply script if comments are open.
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		// Enqueue main theme script.
		wp_enqueue_script(
			'techalgospotlight',
			techalgospotlight_THEME_URI . '/assets/js/' . $techalgospotlight_dir . 'techalgospotlight' . $techalgospotlight_suffix . '.js',
			array('jquery', 'imagesloaded'),
			techalgospotlight_THEME_VERSION,
			true
		);

		// Comment count used in localized strings.
		$comment_count = get_comments_number();

		// Localized variables so they can be used for translatable strings.
		$localized = array(
			'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
			'nonce' => wp_create_nonce('techalgospotlight-nonce'),
			'live-search-nonce' => wp_create_nonce('techalgospotlight-live-search-nonce'),
			'post-like-nonce' => wp_create_nonce('techalgospotlight-post-like-nonce'),
			'close' => esc_html__('Close', 'techalgospotlight'),
			'no_results' => esc_html__('No results found', 'techalgospotlight'),
			'more_results' => esc_html__('More results', 'techalgospotlight'),
			'responsive-breakpoint' => intval(techalgospotlight_option('main_nav_mobile_breakpoint')),
			'dark_mode' => (bool) techalgospotlight_option('dark_mode'),
			'sticky-header' => array(
				'enabled' => techalgospotlight_option('sticky_header'),
				'hide_on' => techalgospotlight_option('sticky_header_hide_on'),
			),
			'strings' => array(
				/* translators: %s Comment count */
				'comments_toggle_show' => $comment_count > 0 ? esc_html(sprintf(_n('Show %s Comment', 'Show %s Comments', $comment_count, 'techalgospotlight'), $comment_count)) : esc_html__('Leave a Comment', 'techalgospotlight'),
				'comments_toggle_hide' => esc_html__('Hide Comments', 'techalgospotlight'),
			),
		);

		wp_localize_script(
			'techalgospotlight',
			'techalgospotlight_vars',
			apply_filters('techalgospotlight_localized', $localized)
		);

		// Enqueue google fonts.
		techalgospotlight()->fonts->enqueue_google_fonts();

		// Add additional theme styles.
		do_action('techalgospotlight_enqueue_scripts');
	}

	/**
	 * Skip link focus fix for IE11.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function techalgospotlight_skip_link_focus_fix()
	{
		?>
		<script>
			! function () {
				var e = -1 < navigator.userAgent.toLowerCase().indexOf("webkit"),
					t = -1 < navigator.userAgent.toLowerCase().indexOf("opera"),
					n = -1 < navigator.userAgent.toLowerCase().indexOf("msie");
				(e || t || n) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function () {
					var e, t = location.hash.substring(1);
					/^[A-z0-9_-]+$/.test(t) && (e = document.getElementById(t)) && (/^(?:a|select|input|button|textarea)$/i.test(e.tagName) || (e.tabIndex = -1), e.focus())
				}, !1)
			}();
		</script>
		<?php
	}

	/**
	 * Enqueue assets for the Block Editor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function techalgospotlight_block_editor_assets()
	{

		// RTL version.
		$rtl = is_rtl() ? '-rtl' : '';

		// Minified version.
		$min = $this->is_debug ? '' : '.min';
		// Enqueue block editor styles.
		wp_enqueue_style(
			'techalgospotlight-block-editor-styles',
			techalgospotlight_THEME_URI . '/inc/admin/assets/css/techalgospotlight-block-editor-styles' . $rtl . $min . '.css',
			false,
			techalgospotlight_THEME_VERSION,
			'all'
		);

		// Enqueue google fonts.
		techalgospotlight()->fonts->enqueue_google_fonts();

		// Add dynamic CSS as inline style.
		wp_add_inline_style(
			'techalgospotlight-block-editor-styles',
			apply_filters('techalgospotlight_block_editor_dynamic_css', techalgospotlight_dynamic_styles()->get_block_editor_css())
		);
	}
}
