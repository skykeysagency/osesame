<?php
/**
 * FreeShippingCalculator Class.
 *
 * @package RisingBambooCore
 * @since 1.0.0
 * @version 1.0.0
 */

namespace RisingBambooCore\Woocommerce;

use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Setting;
use RisingBambooCore\Helper\Woocommerce as RbbCoreWoocommerceHelper;

/**
 * Free shipping calculator class.
 */
class FreeShippingCalculator extends Singleton {

	/**
	 * Construction.
	 */
	public function __construct() {
		if ( Setting::get_option('woocommerce_free_shipping_calculator', true) ) {
			add_action('woocommerce_single_product_summary', [ $this, 'free_shipping_in_product_detail' ], 80);
			$position = Setting::get_option('woocommerce_free_shipping_calculator_position', 'woocommerce_before_cart_table');
			add_action($position, [ $this, 'free_shipping_calculator_output' ]);
			if ( Setting::get_option('woocommerce_free_shipping_calculator_mini_cart', true) ) {
				add_action('woocommerce_widget_shopping_cart_before_buttons', [ $this, 'free_shipping_calculator_output' ]);
			}
		}
	}

	/**
	 * Get template.
	 *
	 * @return void
	 */
	public function free_shipping_in_product_detail(): void {
		wc_get_template_part('single-product/shipping', 'free-calculator');
	}

	/**
	 * Get Min Amount.
	 *
	 * @return array|false
	 */
	public function get_min_amount() {
		global $woocommerce;
		if ( Helper::woocommerce_activated() ) {
			$version          = RbbCoreWoocommerceHelper::get_version();
			$min_amount       = false;
			$ignore_discounts = true;
			if ( version_compare($version, '2.6.0', '<') ) {
				$free_shipping = new \WC_Shipping_Free_Shipping();
				if ( in_array($free_shipping->requires, [ 'min_amount', 'either', 'both' ], true) ) {
					$min_amount = $free_shipping->min_amount;
				}
			} else {
				$shipping = $woocommerce->shipping;
				if ( $shipping->enabled ) {
					$cart = $woocommerce->cart;
					if ( $cart ) {
						$packages = $cart->get_shipping_packages();
						if ( $packages ) {
							$shipping_methods = $shipping->load_shipping_methods($packages[0]);
							foreach ( $shipping_methods as $shipping_method ) {
								if ( $shipping_method instanceof \WC_Shipping_Free_Shipping && $shipping_method->is_enabled() && $shipping_method->get_instance_id() && in_array($shipping_method->requires, [ 'min_amount', 'either', 'both' ], true) ) {
									$min_amount = $shipping_method->min_amount;
									if ( version_compare($version, '4.1', '>') ) {
										$ignore_discounts = $shipping_method->ignore_discounts;
									}
								}
							}
						}
					}
				}
			}

			return [
				'min_amount'       => $min_amount,
				'ignore_discounts' => $ignore_discounts,
			];
		}
		return false;
	}

	/**
	 * Get Cart Total.
	 *
	 * @param bool $ignore_discounts Ignore Discount.
	 * @return float
	 */
	public function get_cart_total( bool $ignore_discounts = true ): float {
		global $woocommerce;
		$total = $woocommerce->cart->get_displayed_subtotal();
		$cart  = $woocommerce->cart;
		if ( ! $ignore_discounts ) {
			if ( $cart->display_prices_including_tax() ) {
				$total = round($total - ( $cart->get_discount_total() + $cart->get_discount_tax() ), wc_get_price_decimals());
			} else {
				$total = round($total - $cart->get_discount_total(), wc_get_price_decimals());
			}
		}

		return $total;
	}

	/**
	 * General html.
	 *
	 * @return void
	 */
	public function free_shipping_calculator_output(): void {
		$min_amount_data  = $this->get_min_amount();
		$min_amount       = (float) $min_amount_data['min_amount'];
		$ignore_discounts = $min_amount_data['ignore_discounts'];
		if ( $min_amount ) {
			$amount      = $this->get_cart_total('yes' === $ignore_discounts);
			$percent     = 100;
			$amount_left = 0;
			if ( $amount <= $min_amount ) {
				$percent     = ceil(( $amount / $min_amount ) * 100);
				$amount_left = $min_amount - $amount;
			}
			$position      = Setting::get_option('woocommerce_free_shipping_calculator_position', 'woocommerce_before_cart_table');
			$percent_class = 'percent-low';
			if ( $percent < 100 && $percent >= 80 ) {
				$percent_class = 'percent-high';
			} elseif ( $percent < 80 && $percent >= 30 ) {
				$percent_class = 'percent-middle';
			}
			wc_get_template(
				'cart/shipping-free-goal.php',
				[
					'min_amount'    => $min_amount,
					'amount'        => $amount,
					'amount_left'   => $amount_left,
					'percent'       => (int) $percent,
					'position'      => $position,
					'percent_class' => $percent_class,
				]
			);
		}
	}
}
