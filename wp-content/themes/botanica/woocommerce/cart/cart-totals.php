<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

use RisingBambooTheme\App\App;

defined('ABSPATH') || exit;

?>
<div class="rbb-cart__totals rounded-2xl cart_totals border overflow-hidden <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action('woocommerce_before_cart_totals'); ?>

	<h2 class="rbb-cart__totals-header text-lg uppercase px-[30px] py-4"><?php esc_html_e('Cart totals', 'botanica'); ?></h2>

	<div class="rbb-cart__totals-content rounded-2xl p-[30px] shop_table shop_table_responsive">

		<div class="rbb-cart__totals-sub flex items-center justify-between border-b pb-6 mb-6 cart-subtotal">
			<div class="rbb-cart__header font-bold"><?php esc_html_e('Subtotal', 'botanica'); ?></div>
			<div class="total__price font-medium" data-title="<?php esc_attr_e('Subtotal', 'botanica'); ?>"><?php wc_cart_totals_subtotal_html(); ?></div>
		</div>

		<?php do_action('rbb_woocommerce_after_cart_subtotal'); ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
				<div><?php wc_cart_totals_coupon_label($coupon); ?></div>
				<div data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></div>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action('woocommerce_cart_totals_before_shipping'); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action('woocommerce_cart_totals_after_shipping'); ?>

		<?php elseif ( 'yes' === get_option('woocommerce_enable_shipping_calc') && WC()->cart->needs_shipping() ) : ?>

			<div class="shipping">
				<div><?php esc_html_e('Shipping', 'botanica'); ?></div>
				<div data-title="<?php esc_attr_e('Shipping', 'botanica'); ?>"><?php woocommerce_shipping_calculator(); ?></div>
			</div>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee">
				<div><?php echo esc_html($fee->name); ?></div>
				<div data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></div>
			</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf(' <small>' . esc_html__('(estimated for %s)', 'botanica') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[ $taxable_address[0] ]);
			}

			if ( 'itemized' === get_option('woocommerce_tax_total_display') ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<div class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
						<div><?php echo esc_html($tax->label) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="tax-total">
					<div><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
				<?php
			}
		}
		?>

		<?php do_action('woocommerce_cart_totals_before_order_total'); ?>

		<div class="order-total flex items-center justify-between mb-10">
			<div class="font-bold">
				<?php esc_html_e('Total', 'botanica'); ?>
			</div>
			<div class="font-extrabold text-xl" data-title="<?php esc_attr_e('Total', 'botanica'); ?>"><?php wc_cart_totals_order_total_html(); ?></div>
		</div>

		<?php do_action('woocommerce_cart_totals_after_order_total'); ?>
		<div class="wc-proceed-to-checkout">
			<?php do_action('woocommerce_proceed_to_checkout'); ?>
		</div>
	</div>

	<?php do_action('woocommerce_after_cart_totals'); ?>

</div>
