<?php
/**
 * Template part for displaying ”Show Comments” button.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

// Do not show if the post is password protected.
if (post_password_required()) {
	return;
}

$techalgospotlight_comment_count = get_comments_number();
$techalgospotlight_comment_title = esc_html__('Leave a Comment', 'techalgospotlight');

if ($techalgospotlight_comment_count > 0) {
	/* translators: %s is comment count */
	$techalgospotlight_comment_title = esc_html(sprintf(_n('Show %s Comment', 'Show %s Comments', $techalgospotlight_comment_count, 'techalgospotlight'), $techalgospotlight_comment_count));
}

?>
<a href="#" id="techalgospotlight-comments-toggle" class="techalgospotlight-btn btn-large btn-fw btn-left-icon">
	<?php echo techalgospotlight()->icons->get_svg('chat'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<span><?php echo $techalgospotlight_comment_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
</a>