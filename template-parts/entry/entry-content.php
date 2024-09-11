<?php
/**
 * Template part for displaying entry content.
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

<?php do_action('techalgospotlight_before_entry_content'); ?>
<div class="entry-content techalgospotlight-entry" <?php techalgospotlight_schema_markup('text'); ?>>
	<?php the_content(); ?>
</div>

<?php techalgospotlight_link_pages(); ?>

<?php do_action('techalgospotlight_after_entry_content'); ?>