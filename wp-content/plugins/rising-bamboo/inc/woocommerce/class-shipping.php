<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Woocommerce;

use DateInterval;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\App\App;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Setting;

/**
 * Woocommerce Shipping Class.
 */
class Shipping extends Singleton {

	/**
	 * Construction.
	 */
	public function __construct() {
		if ( Setting::get_option('woocommerce_shipping_estimate_time', true) ) {
			add_action('woocommerce_product_options_shipping_product_data', [ $this, 'shipping_estimated' ]);
			add_action('woocommerce_process_product_meta', [ $this, 'save_product_data' ]);
			add_action('woocommerce_single_product_summary', [ $this, 'shipping_delivery_estimate_time' ], 70);
		}
	}

	/**
	 * Add shipping estimated.
	 *
	 * @return void
	 */
	public function shipping_estimated(): void {
		global $product_object;

		$shipping_delivery_early = $product_object->get_meta('_shipping_delivery_early');
		woocommerce_wp_text_input(
			[
				'id'                => '_shipping_delivery_early',
				'value'             => $shipping_delivery_early,
				'label'             => __('Early Delivery Time', App::get_domain()),
				'desc_tip'          => true,
				'description'       => sprintf(
					'%s %s',
					__('An estimated average early delivery time.', App::get_domain()),
					__('Leave blank to use global setting.', App::get_domain())
				),
				'type'              => 'number',
				'custom_attributes' => [
					'step' => '1',
					'min'  => '0',
				],
			]
		);

		$shipping_delivery_lately = $product_object->get_meta('_shipping_delivery_lately');
		woocommerce_wp_text_input(
			[
				'id'                    => '_shipping_delivery_lately',
				'value'                 => $shipping_delivery_lately,
				'label'                 => __('Lately Delivery Time', App::get_domain()),
				'desc_tip'              => true,
				'description'           => sprintf(
					'%s %s',
					__('An estimated average lately delivery time.', App::get_domain()),
					__('Leave blank to use global setting.', App::get_domain())
				),
				'type'                  => 'number',
				'custom_attributes'     => [
					'step' => '1',
					'min'  => '0',
				],
			]
		);

		$shipping_delivery_time_type = $product_object->get_meta('_shipping_delivery_time_type');
		woocommerce_wp_select(
			[
				'id'                    => '_shipping_delivery_time_type',
				'value'                 => $shipping_delivery_time_type,
				'label'                 => __('Delivery Time Type', App::get_domain()),
				'desc_tip'              => true,
				'options'               => [
					''     => esc_html__('Default', App::get_domain()),
					'day'  => esc_html__('Day', App::get_domain()),
					'hour' => esc_html__('Hour', App::get_domain()),
				],
			]
		);
	}

	/**
	 * Save product data.
	 *
	 * @param mixed $post_id Post id.
	 * @return void
	 */
	public function save_product_data( $post_id ): void {
		if ( isset($_POST['woocommerce_meta_nonce']) && wp_verify_nonce(wp_unslash(sanitize_key($_POST['woocommerce_meta_nonce'])), 'woocommerce_save_data') ) {
			$product = wc_get_product($post_id);

			$delivery_time_range_begin = isset($_POST['_shipping_delivery_early']) ? sanitize_text_field(wp_unslash($_POST['_shipping_delivery_early'])) : '';
			$product->update_meta_data('_shipping_delivery_early', $delivery_time_range_begin);

			$delivery_time_range = isset($_POST['_shipping_delivery_lately']) ? sanitize_text_field(wp_unslash($_POST['_shipping_delivery_lately'])) : '';
			$product->update_meta_data('_shipping_delivery_lately', $delivery_time_range);

			$delivery_time_type = isset($_POST['_shipping_delivery_time_type']) ? sanitize_text_field(wp_unslash($_POST['_shipping_delivery_time_type'])) : '';
			$product->update_meta_data('_shipping_delivery_time_type', $delivery_time_type);

			$product->save();
		}
	}

	/**
	 * Get shipping delivery range.
	 *
	 * @return null
	 * @throws \Exception Exception.
	 */
	public static function get_shipping_delivery_range(): ?string {
		global $product;
		$range     = null;
		$early     = (int) $product->get_meta('_shipping_delivery_early');
		$lately    = (int) $product->get_meta('_shipping_delivery_lately');
		$date_type = $product->get_meta('_shipping_delivery_time_type') ?? Setting::get_option('woocommerce_shipping_estimate_time_type');
		$now       = current_datetime();
		if ( 'hour' === $date_type ) {
			$interval = 'PT%sH';
		} else {
			$interval = 'P%sD';
		}
		if ( $early && $lately ) {
			$_early  = min($early, $lately);
			$_lately = max($early, $lately);
			$from    = $now->add(new DateInterval(sprintf($interval, $_early)));
			$to      = $now->add(new DateInterval(sprintf($interval, $_lately)));

			if ( 'hour' === $date_type ) {
				if ( $from->format('d') === $to->format('d') ) {
					$range = Helper::round_up_time($from->format('H:i:s')) . ' - ' . Helper::round_up_time($to->format('H:i:s')) . ' ' . $to->format('d M, Y');
				} else {
					$range = Helper::round_up_time($from->format('H:i:s')) . ' ' . $from->format('d M, Y') . ' - ' . Helper::round_up_time($to->format('H:i:s')) . ' ' . $to->format('d M, Y');
				}
			} elseif ( $from->format('m') === $to->format('m') ) {
					$range = $from->format('d') . ' - ' . $to->format('d M, Y');
			} else {
				$range = $from->format('d M, Y') . ' - ' . $to->format('d M, Y');
			}
		} elseif ( $early && ! $lately ) {
			$only_early = $now->add(new DateInterval(sprintf($interval, $early)));
			if ( 'hour' === $date_type ) {
				$range = Helper::round_up_time($only_early->format('H:i:s')) . ' ' . $only_early->format('d M, Y');
			} else {
				$range = $only_early->format('d M, Y');
			}
		} elseif ( ! $early && $lately ) {
			$only_lately = $now->add(new DateInterval(sprintf($interval, $lately)));
			if ( 'hour' === $date_type ) {
				$range = Helper::round_up_time($only_lately->format('H:i:s')) . ' ' . $only_lately->format('d M, Y');
			} else {
				$range = $only_lately->format('d M, Y');
			}
		}
		return $range;
	}

	/**
	 * Product delivery estimate time.
	 *
	 * @return void
	 */
	public function shipping_delivery_estimate_time(): void {
		wc_get_template_part('single-product/shipping', 'delivery-estimate');
	}
}
