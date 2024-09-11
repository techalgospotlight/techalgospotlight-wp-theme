<?php
/**
 * Template part for displaying entry header.
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

<?php do_action('techalgospotlight_before_entry_header'); ?>
<header class="entry-header">

	<?php
	$techalgospotlight_tag = is_single(get_the_ID()) && !techalgospotlight_page_header_has_title() ? 'h1' : 'h4';
	$techalgospotlight_tag = apply_filters('techalgospotlight_entry_header_tag', $techalgospotlight_tag);

	$techalgospotlight_title_string = '%2$s%1$s';

	if ('link' === get_post_format()) {
		$techalgospotlight_title_string = '<a href="%3$s" title="%3$s" rel="bookmark">%2$s%1$s</a>';
	} elseif (!is_single(get_the_ID())) {
		$techalgospotlight_title_string = '<a href="%3$s" title="%4$s" rel="bookmark">%2$s%1$s</a>';
	}

	$techalgospotlight_title_icon = apply_filters('techalgospotlight_post_title_icon', '');
	$techalgospotlight_title_icon = techalgospotlight()->icons->get_svg($techalgospotlight_title_icon);
	?>

	<<?php echo tag_escape($techalgospotlight_tag); ?>
		class="entry-title"<?php techalgospotlight_schema_markup('headline'); ?>>
		<?php
		echo sprintf(
			wp_kses_post($techalgospotlight_title_string),
			wp_kses_post(get_the_title()),
			wp_kses_post((string) $techalgospotlight_title_icon),
			esc_url(techalgospotlight_entry_get_permalink()),
			the_title_attribute(array('echo' => false))
		);
		?>
	</<?php echo tag_escape($techalgospotlight_tag); ?>>

</header>
<?php do_action('techalgospotlight_after_entry_header'); ?>