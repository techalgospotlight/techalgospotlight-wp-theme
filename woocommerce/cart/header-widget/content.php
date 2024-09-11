<?php
/**
 * Header Cart Widget dropdown content.
 *
 * @package techalgospotlight
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

$techalgospotlight_cart_items = WC()->cart->get_cart();
?>
<div class="wc-cart-widget-content">
	<?php foreach ($techalgospotlight_cart_items as $cart_item_key => $cart_item) { // phpcs:ignore ?>

		<?php
		$techalgospotlight_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
		$techalgospotlight_product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

		if ($techalgospotlight_product && $techalgospotlight_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
			$techalgospotlight_product_permalink = apply_filters('woocommerce_cart_item_permalink', $techalgospotlight_product->is_visible() ? $techalgospotlight_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
			<div class="techalgospotlight-cart-item">
				<div class="techalgospotlight-cart-image">
					<?php
					$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $techalgospotlight_product->get_image(), $cart_item, $cart_item_key);

					if (!$techalgospotlight_product_permalink) {
						echo $thumbnail; // phpcs:ignore
					} else {
						printf('<a href="%s" class="techalgospotlight-woo-thumb">%s</a>', esc_url($techalgospotlight_product_permalink), $thumbnail); // phpcs:ignore
					}
					?>
				</div>

				<div class="techalgospotlight-cart-item-details">
					<p class="techalgospotlight-cart-item-title">
						<?php
						if (!$techalgospotlight_product_permalink) {
							echo wp_kses_post(apply_filters('woocommerce_cart_item_name', esc_html($techalgospotlight_product->get_name()), $cart_item, $cart_item_key) . '&nbsp;');
						} else {
							echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($techalgospotlight_product_permalink), esc_html($techalgospotlight_product->get_name())), $cart_item, $cart_item_key));
						}
						?>
					</p>
					<div class="techalgospotlight-cart-item-meta">

						<?php if ($cart_item['quantity'] > 1) { ?>
							<span class="techalgospotlight-cart-item-quantity"><?php echo esc_html($cart_item['quantity']); ?></span>
						<?php } ?>

						<span
							class="techalgospotlight-cart-item-price"><?php echo $techalgospotlight_product->get_price_html(); // phpcs:ignore ?></span>
					</div>
				</div>

				<?php /* translators: %s is cart item title. */ ?>
				<a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item)); ?>" class="techalgospotlight-remove-cart-item"
					data-product_key="<?php echo esc_attr($cart_item['key']); ?>"
					title="<?php echo esc_html(sprintf(__('Remove %s from cart', 'techalgospotlight'), $techalgospotlight_product->get_title())); ?>">
					<?php echo techalgospotlight()->icons->get_svg('x', array('aria-hidden' => 'true')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php /* translators: %s is cart item title. */ ?>
					<span
						class="screen-reader-text"><?php echo esc_html(sprintf(__('Remove %s from cart', 'techalgospotlight'), $techalgospotlight_product->get_title())); ?></span>
				</a>
			</div>
		<?php } ?>
	<?php } ?>
</div><!-- END .wc-cart-widget-content -->