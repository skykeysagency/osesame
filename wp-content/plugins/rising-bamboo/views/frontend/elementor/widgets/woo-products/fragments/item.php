<?php
/**
 * Elementor widget : woo-product.
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Helper\Tag;

?>
<div class="item !flex justify-center h-auto">
	<div class="item-product relative w-full rounded-[18px] xl:mt-6 md:mt-[14px] mt-[5px]">
		<div class="bg-product absolute top-0 left-0 w-full h-full -z-10 rounded-[18px] duration-300"></div>
		<div class="relative h-full flex items-start flex-col overflow-hidden border bg-white rounded-[18px]  z-10">
			<div class="thumbnail-container relative center ">
				<a class="relative block overflow-hidden" href="<?php echo esc_url($product->get_permalink()); ?>">
					<?php
					echo $product->get_image(// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						[ 410, 410 ],
						[
							'class' => 'max-w-full w-full',
							'alt'   => $product->name, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						]
					);
					if ( \RisingBambooCore\Helper\Woocommerce::get_gallery_image($product, [ 410, 410 ]) ) {
						$second_image = \RisingBambooCore\Helper\Woocommerce::get_gallery_image($product, [ 410, 410 ])[0];
						?>
						<img class="max-w-full image-cover absolute left-0 top-0 opacity-0"
							src="<?php echo esc_attr($second_image->src); ?>"
							alt="<?php echo esc_html__('Second image of ', App::get_domain()) . esc_attr($product->name); ?>"/>
						<?php
					}
					?>
				</a>
				<?php if ( $show_wishlist ) { ?>
					<div class="absolute md:top-5 top-3 md:right-5 right-3">
						<?php echo Tag::wish_list_button($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php } ?>
				<div class="product-flags absolute md:left-4 left-3 md:top-5 top-3 z-10 font-semibold text-[11px]">
					<?php
					if ( $show_percentage_discount && $product->sale_price ) {
						$regular_price   = $product->get_regular_price();
						$sale_price      = $product->get_sale_price();
						$sale_percentage = 100 - round(( $sale_price / $regular_price ) * 100);
						?>
						<label class="bg-[#d4ff6e] text-[#222] py-1 px-[13px] rounded-[26px] flex leading-[18px] items-center mb-[10px] h-[26px] text-center min-w-[66px]"><i
									class="rbb-icon-flash-1 mr-[1px] text-xs"></i>
							<?php echo '-' . esc_html($sale_percentage) . '%'; ?>
						</label>
					<?php } ?>
					<?php if ( 'instock' === $product->stock_status && $product->is_featured() ) { ?>
						<label class="bg-[#66ad53] text-white font-bold py-[5px] px-[13px] leading-[18px] text-[10px] h-[26px] rounded-[26px] block mb-[10px] uppercase text-center min-w-[66px]"><?php echo esc_html__('New', App::get_domain()); ?></label>
					<?php } ?>
					<?php if ( 'instock' !== $product->stock_status ) { ?>
						<label class="bg-[#66ad53] text-white font-bold py-[5px] px-[13px] leading-[18px] text-[10px] h-[26px] rounded-[26px] block mb-[10px] uppercase text-center min-w-[66px]"><?php echo esc_html__('Sold out', App::get_domain()); ?></label>
					<?php } ?>
				</div>
				<?php
				$countdown_date_to = $product->get_date_on_sale_to();
				if ( $show_countdown && $countdown_date_to ) {
					$current_date         = new \WC_DateTime();
					$countdown_date_start = $product->get_date_on_sale_from() ?? $product->get_date_modified();
					if ( ( $current_date >= $countdown_date_start ) && ( $current_date <= $countdown_date_to ) ) {
						?>
						<div class="item-countdown absolute md:inset-x-[30px] inset-x-1 bottom-3.5 duration-300">
							<div class="rbb-countdown flex justify-center relative" data-countdown-date="<?php echo esc_attr($countdown_date_to->format('Y/m/d')); ?>">
								<div class="item-time"><span class="data-number">%D</span><span class="name-time"><?php echo esc_html__('Day%!H', 'rbb-core'); ?></span></div>
								<div class="item-time"><span class="data-number">%H</span><span class="name-time"><?php echo esc_html__('Hour%!H', 'rbb-core'); ?></span></div>
								<div class="item-time"><span class="data-number">%M</span><span class="name-time"><?php echo esc_html__('Min%!H', 'rbb-core'); ?></span></div>
								<div class="item-time"><span class="data-number">%S</span><span class="name-time"><?php echo esc_html__('Secs', 'rbb-core'); ?></span></div>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<div class="product_title w-full md:pl-7 xl:pr-16 md:pr-3 mt-3 bg-white">
				<div class="pt-3 md:px-0 px-3">
					<?php if ( $show_rating ) { ?>
						<div class="product_ratting text-amber-400 flex items-center mb-3.5">
							<?php echo wc_get_rating_html($product->get_average_rating()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="ratting-count font-medium ml-1 text-[10px]">(<?php echo $product->get_rating_count();  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>)</span>
						</div>
					<?php } ?>
					<a href="<?php echo esc_url($product->get_permalink()); ?>"
						class="product_name line-clamp-2 block xl:text-base text-[0.8125rem] font-bold md:mb-6 mb-2"><?php echo wp_kses_post($product->name); ?></a>
				</div>
			</div>
			<div class="product_info w-full mt-auto md:pl-7 xl:pr-16 md:pr-3 xl:pb-9 pb-6 relative">
				<div class="product_info-bottom bg-white md:px-0 px-3">
					<div class="product_price xl:text-lg text-sm font-extrabold">
						<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<div class="product_button absolute md:right-5 md:left-auto left-3 xl:bottom-7 md:bottom-4 bottom-3">
					<?php
					if ( $show_quickview ) {
						echo Tag::quick_view_button($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
					<?php
					if ( $show_compare ) {
						echo Tag::compare_button($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
					<?php
					if ( $show_add_to_cart ) {
						$args      = [];
						$text_cart = '';
						$icon      = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON);
						if ( 'instock' !== $product->stock_status ) {
							$text_cart = esc_html__('Out of stock', App::get_domain());
						} elseif ( $product instanceof \WC_Product_Variable && $product->get_available_variations() ) {
								$text_cart = esc_html__('Select options', App::get_domain());
						} else {
							$text_cart = esc_html__('Add to Cart', App::get_domain());
						}
						if ( $icon ) {
							$args['cart-icon'] = '<div class="add-to-cart-icon relative text-center leading-10">
						<span class="title-tooltips absolute whitespace-nowrap opacity-0 text-white">' . $text_cart . '</span>
						<i class="rbb-icon ' . $icon . '"></i>
						</div>';
						}
						woocommerce_template_loop_add_to_cart($args);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
