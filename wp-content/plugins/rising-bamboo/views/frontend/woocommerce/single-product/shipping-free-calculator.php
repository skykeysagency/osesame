<?php
/**
 * Free shipping calculator template.
 *
 * @package RisingBambooTheme
 */

use RisingBambooCore\App\App;
use RisingBambooCore\Woocommerce\FreeShippingCalculator;

$min_amount = FreeShippingCalculator::instance()->get_min_amount()['min_amount'];
?>

<div class="rbb-free-shipping-calculator mt-2">
	<i class="rbb-icon-delivery-1"></i>
	<label class="font-semibold"><?php echo esc_html__('Free Shipping & Returns', App::get_domain()); ?> : </label>
	<?php if ( $min_amount ) { ?>
	<span><?php printf( __('On all order over %s', App::get_domain()), wc_price($min_amount) ); //phpcs:ignore ?></span>
	<?php } else { ?>
	<span><?php echo esc_html__('On all order', App::get_domain()); ?></span>
	<?php } ?>
</div>
