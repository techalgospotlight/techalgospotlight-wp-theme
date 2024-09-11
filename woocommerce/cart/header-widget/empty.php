<?php
/**
 * Header Cart Widget empty cart.
 *
 * @package techalgospotlight
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

?>
<div class="techalgospotlight-empty-cart">
	<?php echo techalgospotlight()->icons->get_svg('shopping-empty', array('aria-hidden' => 'true')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<p><?php esc_html_e('No products in the cart.', 'techalgospotlight'); ?></p>
</div>