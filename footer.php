<?php
/**
 * The template for displaying the footer in our theme.
 *
 * @package     techalgospotlight
 * @author      TechAlgoSpotlight Themes
 * @since       1.0.0
 */

// Hook: After Main Content
do_action('techalgospotlight_main_end');
?>

</div><!-- #main .site-main -->

<?php
// Hook: After Main Content Wrapper
do_action('techalgospotlight_after_main');

// Hook: Before Footer
do_action('techalgospotlight_before_colophon', 'before_footer');

// Display Footer if Applicable
if (techalgospotlight_is_colophon_displayed()): ?>
	<footer id="colophon" class="site-footer" role="contentinfo" <?php techalgospotlight_schema_markup('footer'); ?>>
		<?php
		// Hook: Footer Content
		do_action('techalgospotlight_footer');
		?>
	</footer><!-- #colophon .site-footer -->
<?php endif; ?>

<?php
// Hook: After Footer
do_action('techalgospotlight_after_colophon', 'after_footer');
?>

</div><!-- END #page -->

<?php
// Hook: After Page Wrapper
do_action('techalgospotlight_after_page_wrapper');

// WordPress Footer
wp_footer();
?>

</body>

</html>