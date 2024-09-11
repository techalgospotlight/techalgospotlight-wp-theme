<?php
/**
 * Template part for displaying more posts button in author box.
 *
 * @package techalgospotlight
 * @author TechAlgoSpotlight Themes
 * @since   1.0.0
 */

?>

<div class="more-posts-button">
	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
		class="techalgospotlight-btn btn-text-1"
		role="button"><span><?php echo wp_kses(__('View All Posts', 'techalgospotlight'), techalgospotlight_get_allowed_html_tags('button')); ?></span></i></a>
</div>