<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\Helper;

use RisingBambooCore\Core\Singleton;
use WC_Product_Query;
use WC_Query;

/**
 * Rising Bamboo Woocommerce Class.
 */
class WoocommerceImage extends Singleton {

	/**
	 * Get all images attachment.
	 *
	 * @param \WC_Product $product Product.
	 * @param boolean     $inc_variation Include Variation.
	 * @return array
	 */
	public static function get_images_id( \WC_Product $product, bool $inc_variation = true ): array {
		$attachment_ids = $product->get_gallery_image_ids();
		$thumbnail_id   = (int) $product->get_image_id();
		if ( $thumbnail_id ) {
			array_unshift($attachment_ids, $thumbnail_id);
		}
		if ( $inc_variation && $product instanceof \WC_Product_Variable ) {
			$variations = $product->get_available_variations();
			foreach ( $variations as $variation ) {
				if ( isset($variation['image_id']) && ! in_array($variation['image_id'], $attachment_ids, true) ) {
					$attachment_ids[] = $variation['image_id'];
				}
			}
		}
		return $attachment_ids;
	}
}
