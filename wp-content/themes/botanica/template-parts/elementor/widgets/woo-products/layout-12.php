<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

use RisingBambooCore\Core\View;
use RisingBambooTheme\App\App;
/**
 * Use for woocommerce_template_loop_add_to_cart func.
 */
global $product;
if ( count($data['products']) ) {
	?>
	<div id="<?php echo esc_attr($id); ?>" class="rbb_woo_products overflow-hidden <?php echo esc_attr($layout); ?>">
		<?php if ( ! empty($surrounding_animation_image_01['url']) ) { ?>
			<div class="absolute top-0 left-[3%] md:block hidden z-10 animate-[iconbanner_3s_linear_infinite]">
				<img class="xl:w-full w-1/2" src="<?php echo esc_url($surrounding_animation_image_01['url']); ?>" alt="bg right" >
			</div>
		<?php } ?>
		<?php if ( ! empty($surrounding_animation_image_02['url']) ) { ?>
			<div class="absolute top-0 right-[3%] md:block hidden z-10 animate-[iconbanner_3s_linear_infinite]">
				<img class="xl:w-full w-1/2" src="<?php echo esc_url($surrounding_animation_image_02); ?>" alt="bg right" >
			</div>
		<?php } ?>
		<div class="max-w-[1340px] xl:px-[15px] pb-3 mx-auto overflow-hidden">
			<div class="text-center">
				<div class="title_left">
					<div class="mb-8">
						<?php if ( $show_title ) { ?>
						<span class="title block lg:text-4xl text-3xl pb-1.5"><?php echo wp_kses_post($data['title']); ?></span>
						<span class="sub-title block"><?php echo wp_kses_post($subtitle); ?></span>
					<?php } ?>
					<?php
					if ( 'category' === $data['type'] ) {
						?>
						<?php
						$cat_count = count($data['categories']);
						if ( '1' < $cat_count || ( $show_filter && '1' === $cat_count ) ) {
							?>
							<select class="appearance-none"
									data-class="relative inset-0 px-[30px] font-extrabold text-base uppercase cursor-pointer transition-all duration-200 ease-in border-2 rounded-[53px]"
									data-ajax='{
										"action": "rbb_get_products_by_category",
										"fragment": "item-3",
										"order_by" : "<?php echo esc_attr($order_by); ?>",
										"order" : "<?php echo esc_attr($order); ?>",
										"limit" : "<?php echo esc_attr($limit); ?>",
										"show_wishlist" : <?php echo esc_attr(( $show_wishlist ) ? '1' : '0'); ?>,
										"show_rating" : <?php echo esc_attr(( $show_rating ) ? '1' : '0'); ?>,
										"show_quickview" : <?php echo esc_attr(( $show_quickview ) ? '1' : '0'); ?>,
										"show_compare" : <?php echo esc_attr(( $show_compare ) ? '1' : '0'); ?>,
										"show_add_to_cart" : <?php echo esc_attr(( $show_add_to_cart ) ? '1' : '0'); ?>,
										"show_countdown" : <?php echo esc_attr(( $show_countdown ) ? '1' : '0'); ?>,
										"show_percentage_discount" : <?php echo esc_attr(( $show_percentage_discount ) ? '1' : '0'); ?>,
										"show_stock" : <?php echo esc_attr(( $show_stock ) ? '1' : '0'); ?>,
										"show_custom_field" : <?php echo esc_attr(( $show_custom_field ) ? '1' : '0'); ?>,
										"custom_fields" : <?php echo wp_json_encode($custom_fields); ?>,
										"custom_field_ignore" : <?php echo wp_json_encode($custom_field_ignore); ?>
									}'
							>
								<?php foreach ( $data['categories'] as $category_id => $category ) { ?>
									<option value="<?php echo esc_attr($category_id); ?>"><?php echo wp_kses_post($category); ?></option>
								<?php } ?>
							</select>
						<?php } ?>
						<?php
					}
					?>
					</div>
				</div>
			</div>
			<div class="rbb-product-list grid gap-0 xl:grid-cols-3 grid-cols-2">
			<?php
			foreach ( $data['products'] as $product_id => $product ) { 
				if ( $product_id <= 6 ) {
					if ( 0 === $product_id || 3 === $product_id || 4 === $product_id ) { 
						?>
						<div class="
						<?php
						if ( 3 === $product_id ) {
							?>
							block_center xl:col-span-1 col-span-2 xl:order-1 -order-1 <?php } ?>
							<?php
							if ( 0 === $product_id ) {
								?>
								col-span-1 block_left xl:order-[0]<?php } ?>
							<?php
							if ( 4 === $product_id ) {
								?>
								col-span-1 block_right xl:order-3<?php } ?>">
						<?php } ?>
							<?php if ( 3 === $product_id ) { ?>
								<?php
								View::instance()->load(
									'elementor/widgets/woo-products/fragments/item',
									[
										'product' => $product,
									]
								);
								?>
							<?php } else { ?>
								<?php
								View::instance()->load(
									'elementor/widgets/woo-products/fragments/item-3',
									[
										'product' => $product,
									]
								);
								?>
							<?php } ?>
						<?php if ( 2 === $product_id || 3 === $product_id || 6 === $product_id ) { ?>
						</div>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
		</div>
	</div>
	<?php
}
?>
