<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @package     techalgospotlight
 * @since       1.0.0
 */

// Return if comments are not meant to be displayed.
if (!techalgospotlight_comments_displayed()) {
	return;
}

do_action('techalgospotlight_before_comments');
?>

<section id="comments" class="comments-area">

	<div class="comments-title-wrapper center-text">
		<h3 class="comments-title">
			<?php
			// Get the number of comments.
			$techalgospotlight_comments_count = get_comments_number();

			// Determine the comments title based on the count.
			if (0 === intval($techalgospotlight_comments_count)) {
				$techalgospotlight_comments_title = esc_html__('Comments', 'techalgospotlight');
			} else {
				/* translators: %s Comment number */
				$techalgospotlight_comments_title = sprintf(
					_n('%s Comment', '%s Comments', $techalgospotlight_comments_count, 'techalgospotlight'),
					number_format_i18n($techalgospotlight_comments_count)
				);
			}

			// Apply filters to the comments title.
			$techalgospotlight_comments_title = apply_filters('techalgospotlight_comments_count', $techalgospotlight_comments_title);

			// Display the comments title with allowed HTML tags.
			echo wp_kses($techalgospotlight_comments_title, techalgospotlight_get_allowed_html_tags());
			?>
		</h3><!-- .comments-title -->

		<?php if (!have_comments()): ?>
			<p class="no-comments">
				<?php
				echo esc_html(
					apply_filters('techalgospotlight_no_comments_text', esc_html__('No comments yet. Why don&rsquo;t you start the discussion?', 'techalgospotlight'))
				);
				?>
			</p>
		<?php endif; ?>
	</div>

	<ol class="comment-list">
		<?php
		// List comments.
		wp_list_comments(
			array(
				'callback' => 'techalgospotlight_comment',
				'avatar_size' => apply_filters('techalgospotlight_comment_avatar_size', 50),
				'reply_text' => __('Reply', 'techalgospotlight'),
			)
		);
		?>
	</ol>

	<?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')): ?>
		<p class="comments-closed center-text"><?php esc_html_e('Comments are closed', 'techalgospotlight'); ?></p>
	<?php endif; ?>

	<?php
	// Pagination for comments.
	the_comments_pagination(
		array(
			'prev_text' => '<span class="screen-reader-text">' . __('Previous', 'techalgospotlight') . '</span>',
			'next_text' => '<span class="screen-reader-text">' . __('Next', 'techalgospotlight') . '</span>',
		)
	);

	// Comment form.
	comment_form(
		array(
			/* translators: %1$s opening anchor tag, %2$s closing anchor tag */
			'must_log_in' => '<p class="must-log-in">' . sprintf(
				esc_html__('You must be %1$slogged in%2$s to post a comment.', 'techalgospotlight'),
				'<a href="' . wp_login_url(apply_filters('the_permalink', get_permalink())) . '">',
				'</a>'
			) . '</p>',
			'logged_in_as' => '<p class="logged-in-as">' . esc_html__('Logged in as', 'techalgospotlight') . ' <a href="' . esc_url(admin_url('profile.php')) . '">' . esc_html($user_identity) . '</a> <a href="' . wp_logout_url(get_permalink()) . '" title="' . esc_html__('Log out of this account', 'techalgospotlight') . '">' . esc_html__('Log out?', 'techalgospotlight') . '</a></p>',
			'class_submit' => 'techalgospotlight-btn primary-button',
			'comment_field' => '<p class="comment-textarea"><textarea name="comment" id="comment" cols="44" rows="8" class="textarea-comment" placeholder="' . esc_html__('Write a comment&hellip;', 'techalgospotlight') . '" required="required"></textarea></p>',
			'id_submit' => 'comment-submit',
		)
	);
	?>

</section><!-- #comments -->

<?php do_action('techalgospotlight_after_comments'); ?>