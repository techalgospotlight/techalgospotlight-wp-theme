<?php
/**
 * The template for displaying header navigation.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>

<nav
	class="site-navigation main-navigation techalgospotlight-primary-nav techalgospotlight-nav techalgospotlight-header-element"
	role="navigation" <?php techalgospotlight_schema_markup('site_navigation'); ?>
	aria-label="<?php esc_attr_e('Site Navigation', 'techalgospotlight'); ?>">

	<?php

	if (has_nav_menu('techalgospotlight-primary')) {
		wp_nav_menu(
			array(
				'theme_location' => 'techalgospotlight-primary',
				'menu_id' => 'techalgospotlight-primary-nav',
				'container' => '',
				'link_before' => '<span>',
				'link_after' => '</span>',
			)
		);
	} else {
		wp_page_menu(
			array(
				'menu_class' => 'techalgospotlight-primary-nav',
				'show_home' => true,
				'container' => 'ul',
				'before' => '',
				'after' => '',
				'link_before' => '<span>',
				'link_after' => '</span>',
			)
		);
	}

	?>
</nav><!-- END .techalgospotlight-nav -->