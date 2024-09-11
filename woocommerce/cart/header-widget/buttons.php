<?php
/**
 * Header Cart Widget cart & checkout buttons.
 *
 * @package techalgospotlight
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

?>
<div class="techalgospotlight-cart-buttons">
	<a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="techalgospotlight-btn btn-text-1" role="button">
		<span><?php esc_html_e('View Cart', 'techalgospotlight'); ?></span>
	</a>

	<a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="techalgospotlight-btn btn-fw" role="button">
		<span><?php esc_html_e('Checkout', 'techalgospotlight'); ?></span>
	</a>
</div>