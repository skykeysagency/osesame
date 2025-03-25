<?php
/**
 * RisingBambooCore Package.
 *
 * @package RisingBamboo
 */

namespace RisingBambooCore\Woocommerce;

use RisingBambooCore\Core\Singleton;
use Automattic\WooCommerce\Utilities\FeaturesUtil;

/**
 * Woocommerce HPOS support.
 */
class HPOS extends Singleton {


	/**
	 * Taxonomy name.
	 */
	public const TAXONOMY_NAME = 'product_brand';

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action(
			'before_woocommerce_init',
			function () {
				if ( class_exists(FeaturesUtil::class) ) {
					FeaturesUtil::declare_compatibility('custom_order_tables', RBB_CORE_PATH_FILE);
				}
			}
		);
	}
}
