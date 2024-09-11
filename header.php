<?php
/**
 * The header for our theme.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php techalgospotlight_schema_markup('html'); ?> <?php echo techalgospotlight_option('dark_mode') ? 'data-darkmode="dark"' : ''; ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<?php do_action('techalgospotlight_before_page_wrapper'); ?>
	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#main">
			<?php esc_html_e('Skip to content', 'techalgospotlight'); ?>
		</a>

		<?php if (get_header_image()):
			the_custom_header_markup();
		endif; ?>

		<?php do_action('techalgospotlight_before_masthead', 'before_header'); ?>
		<header id="masthead" class="site-header" role="banner" <?php techalgospotlight_masthead_atts(); ?> <?php techalgospotlight_schema_markup('header'); ?>>
			<?php do_action('techalgospotlight_header'); ?>
			<?php do_action('techalgospotlight_page_header'); ?>
		</header><!-- #masthead .site-header -->

		<?php do_action('techalgospotlight_after_masthead', 'after_header'); ?>
		<?php do_action('techalgospotlight_before_main'); ?>
		<div id="main" class="site-main">
			<?php do_action('techalgospotlight_main_start'); ?>