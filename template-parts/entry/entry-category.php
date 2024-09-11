<?php
/**
 * Template part for displaying entry category.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

?>

<div class="post-category">

	<?php
	do_action('techalgospotlight_before_post_category');

	if (is_singular()) {
		techalgospotlight_entry_meta_category(' ', false);
	} else {
		if ('blog-horizontal' === techalgospotlight_get_article_feed_layout() || 'blog-layout-2' === techalgospotlight_get_article_feed_layout()) {
			techalgospotlight_entry_meta_category(' ', false, 3);
		} else {
			techalgospotlight_entry_meta_category(', ', false, 3);
		}
	}

	do_action('techalgospotlight_after_post_category');
	?>

</div>