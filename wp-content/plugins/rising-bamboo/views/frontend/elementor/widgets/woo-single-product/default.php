<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

global $product;

use RisingBambooCore\Core\View;

defined('ABSPATH') || exit;
?>
<div class="rbb_woo_single_product <?php echo esc_attr($layout); ?>">
<?php
if ( $show_title ) {
	?>
	<div class="mb-9 heading_block">
	<?php if ( $title ) { ?>
			<h2 class="title font-bold"><?php echo esc_html($title); ?></h2>
	<?php } ?>
		<?php if ( $subtitle ) { ?>
			<span class="sub-title block"><?php echo esc_html($subtitle); ?></span>
		<?php } ?>
	</div>
	<?php
}
?>
<?php
foreach ( $products as $product ) {
	/**
	* Hook: woocommerce_before_single_product.
	*
	* @hooked woocommerce_output_all_notices - 10
	*/
    //phpcs:ignore
	do_action('woocommerce_before_single_product');
	?>
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class('w-full', $product); ?>>

		<div class="grid gap-0 grid-cols-12">
			<div class="product-image relative col-span-12 md:col-span-6 xl:col-span-6 lg:pr-[30px]">
				<?php
				View::instance()->load(
					'elementor/widgets/woo-single-product/fragments/image',
					[
						'product'      => $product,
						'image_layout' => $image_layout,
					]
				);
				?>
			</div>
			<div class="product-summary summary entry-summary col-span-12 relative md:col-span-6 xl:col-span-6 lg:pr-[30px] lg:pl-[30px]">
				<div class="single-sticky sticky top-24">
					<?php
					/**
					 * Hook: woocommerce_single_product_summary.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					//phpcs:ignore
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
					//phpcs:ignore
					add_action('woocommerce_single_product_summary', [$widget, 'meta_production'], 80);
					if ( ! $show_excerpt ) {
						remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
					}
					if ( ! $show_guarantee ) {
						remove_action('woocommerce_single_product_summary', [ \RisingBambooTheme\Woocommerce\Woocommerce::instance(), 'guarantee_and_safe_checkout' ], 60);
					}
                    //phpcs:ignore
					do_action('woocommerce_single_product_summary');
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
</div>
