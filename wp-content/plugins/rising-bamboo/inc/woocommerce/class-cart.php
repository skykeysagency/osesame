<?php
/**
 * RisingBambooCore Package.
 *
 * @package RisingBamboo
 */

namespace RisingBambooCore\Woocommerce;

use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Utilities;
use WC_AJAX;

/**
 * RisingBamboo Cart
 */
class Cart extends Singleton {


	/**
	 * Construction.
	 */
	public function __construct() {
		if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
			remove_action('wc_ajax_add_to_cart', [ WC_AJAX::class, 'add_to_cart' ]);
			add_action('wc_ajax_add_to_cart', [ $this, 'ajax_add_to_cart' ]);
		}
	}

	/**
	 * Ajax Cart fix Variation.
	 *
	 * @return void
	 * @throws mixed \Exception Exception.
	 */
	public static function ajax_add_to_cart(): void {
		ob_start();

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! isset($_POST['product_id']) ) {
			return;
		}

		$product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
		$product    = wc_get_product($product_id);
		//phpcs:ignore
		$quantity          = empty($_POST['quantity']) ? 1 : wc_stock_amount(wp_unslash($_POST['quantity']));
		$passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
		$product_status    = get_post_status($product_id);
		$variation_id      = 0;
		$variation         = [];

		if ( $product && 'variation' === $product->get_type() ) {
			$variation_id = $product_id;
			$product_id   = $product->get_parent_id();
			$variation    = $product->get_variation_attributes();
			$_attr        = self::get_attr_from_request();
			foreach ( $variation as $attr_name => $attr_val ) {
				if ( ( '' === $attr_val || null === $attr_val ) && isset($_attr[ $attr_name ]) ) {
					$variation[ $attr_name ] = $_attr[ $attr_name ];
				}
			}
		}

		if ( $passed_validation && 'publish' === $product_status && false !== WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation) ) {

			do_action('woocommerce_ajax_added_to_cart', $product_id);

			if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
				wc_add_to_cart_message([ $product_id => $quantity ], true);
			}

			WC_AJAX::get_refreshed_fragments();

		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = [
				'error'       => true,
				'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
			];

			wp_send_json($data);
		}
		// phpcs:enable
	}

	/**
	 * Get Attributes from
	 *
	 * @return array
	 */
	public static function get_attr_from_request(): array {
		$result = [];
		//phpcs:ignore
		foreach ($_POST as $key => $val) {
			if ( strpos($key, 'attribute_pa') === 0 ) {
				$result[ Utilities::camel_2_dashed($key) ] = $val;
			}
		}
		return $result;
	}
}
