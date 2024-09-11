<?php
/**
 * The template for displaying theme sidebar.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

if (!techalgospotlight_is_sidebar_displayed()) {
	return;
}

$sidebar_id = techalgospotlight_get_sidebar();
$sidebar_name = techalgospotlight_get_sidebar_name_by_id($sidebar_id);
?>

<aside id="secondary" class="widget-area techalgospotlight-sidebar-container" <?php techalgospotlight_schema_markup('sidebar'); ?> role="complementary">

	<div class="techalgospotlight-sidebar-inner">
		<?php do_action('techalgospotlight_before_sidebar'); ?>

		<?php if (is_active_sidebar($sidebar_id)): ?>
			<?php dynamic_sidebar($sidebar_id); ?>
		<?php elseif (current_user_can('edit_theme_options')): ?>
			<div class="techalgospotlight-sidebar-widget techalgospotlight-widget techalgospotlight-no-widget">
				<h4 class="widget-title"><?php echo esc_html($sidebar_name); ?></h4>
				<p class="no-widget-text">
					<?php if (is_customize_preview()): ?>
						<a href="#" class="techalgospotlight-set-widget" data-sidebar-id="<?php echo esc_attr($sidebar_id); ?>">
						<?php else: ?>
							<a href="<?php echo esc_url(admin_url('widgets.php')); ?>">
							<?php endif; ?>
							<?php esc_html_e('Click here to assign a widget.', 'techalgospotlight'); ?>
						</a>
				</p>
			</div>
		<?php endif; ?>

		<?php do_action('techalgospotlight_after_sidebar'); ?>
	</div>

</aside><!-- #secondary .widget-area -->