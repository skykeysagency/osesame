<?php
/**
 * Shipping delivery estimate template.
 *
 * @package RisingBambooTheme.
 */

use RisingBambooCore\Woocommerce\Shipping;
use RisingBambooCore\App\App;

$shipping_delivery_range = Shipping::get_shipping_delivery_range();
if ( $shipping_delivery_range ) {
	?>

<div class="shipping-delivery-estimate-time mt-2">
	<div class="item product-meta-shipping-delivery-time">
		<i class="rbb-icon-delivery-13"></i>
		<label class="font-semibold"><?php echo esc_html__('Estimated Delivery: ', App::get_domain()); ?></label>
		<span class="estimate-time"><?php echo Shipping::get_shipping_delivery_range(); //phpcs:ignore ?></span>
	</div>
</div>
<?php } ?>
