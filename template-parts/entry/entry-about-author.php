<?php
/**
 * Template part for displaying about author box.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

// Do not show the about author box if post is password protected.
if (post_password_required()) {
	return;
}
?>

<?php do_action('techalgospotlight_entry_before_author'); ?>
<section class="author-box" <?php techalgospotlight_schema_markup('author'); ?>>

	<div class="author-box-avatar">
		<?php echo get_avatar(get_the_author_meta('email'), 75); ?>
	</div>

	<div class="author-box-meta">
		<div class="h4 author-box-title">
			<?php
			if (is_single()) {
				?>
				<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="url fn n" rel="author"
					<?php techalgospotlight_schema_markup('url'); ?>>
					<?php echo esc_html(get_the_author()); ?>
				</a>
				<?php
			} else {
				esc_html_e('About', 'techalgospotlight');
				?>
				<?php echo esc_html(get_the_author()); ?>
			<?php } ?>
		</div>

		<?php do_action('techalgospotlight_entry_after_author_name'); ?>

		<?php
		$techalgospotlight_author_description = get_the_author_meta('description');
		$techalgospotlight_author_id = get_the_author_meta('ID');
		$techalgospotlight_current_user_id = is_user_logged_in() ? wp_get_current_user()->ID : false;
		?>

		<div class="author-box-content" <?php techalgospotlight_schema_markup('description'); ?>>
			<?php
			if ('' === $techalgospotlight_author_description) {
				if ($techalgospotlight_current_user_id && $techalgospotlight_author_id === $techalgospotlight_current_user_id) {

					// Translators: %1$s: <a> tag. %2$s: </a>.
					printf(wp_kses_post(__('You haven&rsquo;t entered your Biographical Information yet. %1$sEdit your Profile%2$s now.', 'techalgospotlight')), '<a href="' . esc_url(get_edit_user_link($techalgospotlight_current_user_id)) . '">', '</a>');
				}
			} else {
				echo wp_kses_post($techalgospotlight_author_description);
			}
			?>
		</div>

		<?php do_action('techalgospotlight_entry_after_author_description'); ?>
	</div><!-- END .author-box-meta -->

</section>
<?php do_action('techalgospotlight_entry_after_author'); ?>