<?php
/**
 * Image Product.
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\Helper\WoocommerceImage;
use RisingBambooTheme\Core\View;

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists('wc_get_gallery_image_html') ) {
	return;
}

global $product;
$attachment_ids = WoocommerceImage::get_images_id($product);

if ( empty($attachment_ids) ) {
	//phpcs:ignore
	echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_attr__('Placeholder', 'rbb-core')), $product->get_id());
	return;
}

$wrapper_classes = apply_filters(
	//phpcs:ignore
	'rbb_theme_single_product_image_gallery_classes',
	[
		'product-single__photos',
	]
);

$image_layout_path = realpath(__DIR__ . '/image-layouts/' . $image_layout . '.php');
if ( $image_layout_path ) {
	View::instance()->load(
		'elementor/widgets/woo-single-product/fragments/image-layouts/' . $image_layout,
		[
			'product'         => $product,
			'wrapper_classes' => $wrapper_classes,
			'attachment_ids'  => $attachment_ids,
		]
	);
}
