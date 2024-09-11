<?php
/**
 * Header Cart Widget dropdown header.
 *
 * @package techalgospotlight
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

$techalgospotlight_cart_count = WC()->cart->get_cart_contents_count();
$techalgospotlight_cart_subtotal = WC()->cart->get_cart_subtotal();

?>
<div class="wc-cart-widget-header">
	<span class="techalgospotlight-cart-count">
		<?php
		/* translators: %s: the number of cart items; */
		echo wp_kses_post(sprintf(_n('%s item', '%s items', $techalgospotlight_cart_count, 'techalgospotlight'), $techalgospotlight_cart_count));
		?>
	</span>

	<span class="techalgospotlight-cart-subtotal">
		<?php
		/* translators: %s is the cart subtotal. */
		echo wp_kses_post(sprintf(__('Subtotal: %s', 'techalgospotlight'), '<span>' . $techalgospotlight_cart_subtotal . '</span>'));
		?>
	</span>
</div>