<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined('ABSPATH') ) {
	exit;
}
?>
<div class="rbb-payment__method py-[19px] border-b flex wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?>">
	<label class="rbb__input-radio flex items-center grow-0" for="payment_method_<?php echo esc_attr($gateway->id); ?>">
		<input id="payment_method_<?php echo esc_attr($gateway->id); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" />
		<span class="presentation"></span>
	</label>
	<div class="flex-grow">
		<div class="font-semibold flex">
			<span class="rbb-payment__gateway-title font-bold"><?php echo wp_kses($gateway->get_title(), 'rbb-kses'); ?></span>
			<span class="rbb-payment__gateway-icon flex-grow ml-2"><?php echo wp_kses($gateway->get_icon(), 'rbb-kses'); ?></span>
		</div>
		<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="pt-3 mb-[6px] payment_box payment_method_<?php echo esc_attr($gateway->id); ?> <?php echo ! $gateway->chosen ? esc_attr('hidden') : ''; ?>">
			<?php $gateway->payment_fields(); ?>
		</div>
	</div>
	<?php endif; ?>
</div>
