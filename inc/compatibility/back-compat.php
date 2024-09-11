<?php
/**
 * Theme back compatibility functionality
 *
 * Prevents techalgospotlight from running on WordPress versions prior to 5.4,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 5.4.
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
 * Prevent switching to Highend X on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since 1.0.0
 */
function techalgospotlight_switch_theme()
{
	switch_theme(WP_DEFAULT_THEME);
	unset($_GET['activated']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	add_action('admin_notices', 'techalgospotlight_upgrade_notice');
}
add_action('after_switch_theme', 'techalgospotlight_switch_theme');

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * techalgospotlight on WordPress versions prior to 5.4.
 *
 * @since 1.0.0
 * @global string $wp_version WordPress version.
 */
function techalgospotlight_upgrade_notice()
{
	/* translators: %s WordPress version */
	$message = sprintf(esc_html__('techalgospotlight theme requires at least WordPress version 5.4. You are running version %s. Please upgrade and try again.', 'techalgospotlight'), $GLOBALS['wp_version']);
	printf('<div class="error"><p>%s</p></div>', esc_html($message));
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 5.4.
 *
 * @since 1.0.0
 * @global string $wp_version WordPress version.
 */
function techalgospotlight_customize_prevent()
{

	/* translators: %s WordPress version */
	$message = sprintf(esc_html__('techalgospotlight theme requires at least WordPress version 5.4. You are running version %s. Please upgrade your WordPress and try again.', 'techalgospotlight'), $GLOBALS['wp_version']);

	wp_die(esc_html($message), '', array('back_link' => true));
}
add_action('load-customize.php', 'techalgospotlight_customize_prevent');

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 5.4.
 *
 * @since 1.0.0
 * @global string $wp_version WordPress version.
 */
function techalgospotlight_preview_prevent()
{
	if (isset($_GET['preview'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s WordPress version */
		$message = sprintf(esc_html__('techalgospotlight theme requires at least WordPress version 5.4. You are running version %s. Please upgrade and try again.', 'techalgospotlight'), $GLOBALS['wp_version']);
		wp_die(esc_html($message));
	}
}
add_action('template_redirect', 'techalgospotlight_preview_prevent');

if (!function_exists('wp_body_open')) {
	/**
	 * Backward compatibility for wp_body_open hook.
	 *
	 * @since 1.0.0
	 */
	function wp_body_open()
	{ // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		do_action('wp_body_open'); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}
