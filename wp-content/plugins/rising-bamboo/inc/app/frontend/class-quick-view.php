<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\App\Frontend;

use RisingBambooCore\Core\Singleton;
use RisingBambooCore\App\App;
use RisingBambooCore\Helper\Helper;

/**
 * Quick View.
 */
class QuickView extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('wp_ajax_rbb_product_quick_view', [ $this, 'quick_view' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_product_quick_view', [ $this, 'quick_view' ]); // nonce - ok.
		add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
	}

	/**
	 * Scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		wp_enqueue_script('wc-add-to-cart-variation');
		Helper::register_asset('rbb-quick-view-product', 'js/frontend/components/quick-view.js', [ 'jquery' ], App::get_version());
		wp_enqueue_script('rbb-quick-view-product');
	}

	/**
	 * Quick-view template.
	 *
	 * @return void
	 */
	public function quick_view(): void {
		global $product;
		if ( check_ajax_referer(App::get_nonce(), 'nonce') ) {
			$product_id = ( isset($_REQUEST['product_id']) && $_REQUEST['product_id'] > 0 ) ? sanitize_key(wp_unslash($_REQUEST['product_id'])) : 0;
			$product    = wc_get_product($product_id);
			$result     = '';
			if ( $product->exists() ) {
				ob_start();
				wc_get_template_part('content', 'quick-view-product');
				$result = ob_get_clean();
			}
			wp_send_json(
				[
					'result' => $result,
				]
			);
		}
	}
}
