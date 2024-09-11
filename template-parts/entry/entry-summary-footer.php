<?php
/**
 * Template part for displaying entry footer.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if (!defined('ABSPATH')) {
	exit;
}

?>

<?php do_action('techalgospotlight_before_entry_footer'); ?>
<footer class="entry-footer">
	<?php

	// Allow text to be filtered.
	$techalgospotlight_read_more_text = techalgospotlight_option('blog_read_more');

	?>
	<a href="<?php echo esc_url(techalgospotlight_entry_get_permalink()); ?>"
		class="techalgospotlight-btn btn-text-1"><span><?php echo esc_html($techalgospotlight_read_more_text); ?></span></a>
</footer>
<?php do_action('techalgospotlight_after_entry_footer'); ?>