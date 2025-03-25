<?php
/**
 * Elementor widget : woo-product.
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Helper\Tag;
$product_images      = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGES_SHOW);
$effect_images       = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGE_EFFECT);
$custom_field_exists = false;
if ( $show_custom_field ) {
	if ( empty($custom_fields) ) {
		$custom_fields = get_post_custom_keys($product->get_id());
	}
	foreach ( $custom_fields as $custom_field ) {
		if ( in_array($custom_field, $custom_field_ignore, true) || strpos($custom_field, '_') === 0 ) {
			continue;
		}
		if ( $product->get_meta($custom_field) ) {
			$custom_field_exists = true;
			break;
		}
	}
}
$custom_field_class = $custom_field_exists ? ' custom-field pb-6' : 'pb-2';
if ( $product->get_stock_status() !== 'instock' ) {
	$custom_field_class .= ' custom-field';
} elseif ( $product->get_stock_status() === 'instock' && $product->get_stock_quantity() > 0 ) {
	$custom_field_class .= ' custom-field'; 
}
?>
<div class="item !flex justify-center h-auto">
	<div class="item-product relative w-full rounded-[18px] xl:mt-6 md:mt-[14px] mt-[5px]">
		<div class="bg-product absolute top-0 left-0 w-full h-full -z-10 rounded-[18px] duration-300 shadow-[10px_11px_20px_0px_rgba(0,0,0,0.15)]"></div>
		<div class="relative h-full flex items-start flex-col overflow-hidden border bg-white rounded-[18px]  z-10">
			<div class="thumbnail-container relative
				<?php
				echo wp_kses_post($effect_images);
				echo ( true === $product_images ) && \RisingBambooCore\Helper\Woocommerce::get_gallery_image($product, [ 600, 600 ]) ? ' hover-images ' : ' none-hover-images ';
				?>
				">
				<a class="relative block overflow-hidden" href="<?php echo esc_url($product->get_permalink()); ?>">
					<?php
					echo wp_kses(
						$product->get_image(
							[ 600, 600 ],
							[
								'class'   => 'max-w-full w-full',
								'alt'     => esc_attr($product->get_name()),
							]
						),
						'rbb-kses'
					);
					if ( \RisingBambooCore\Helper\Woocommerce::get_gallery_image($product, [ 600, 600 ]) ) {
						$second_image = \RisingBambooCore\Helper\Woocommerce::get_gallery_image($product, [ 600, 600 ])[0];
						?>
						<img class="max-w-full image-cover absolute left-0 top-0 opacity-0" src="<?php echo esc_url($second_image->src); ?>" alt="<?php echo esc_attr__('Second image of ', 'botanica') . esc_attr($product->get_name()); ?>"/>
						<?php
					}
					?>
				</a>
				<?php if ( $show_wishlist ) { ?>
					<div class="wishlist-icon absolute md:top-5 top-3 md:right-5 right-3 tracking-normal">
						<?php echo Tag::wish_list_button($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php } ?>
				<div class="product-flags absolute md:left-4 left-3 md:top-5 top-3 z-10 font-semibold text-[11px]">
					<?php
					if ( $show_percentage_discount && $product->get_sale_price() ) {
						$regular_price   = $product->get_regular_price();
						$sale_price      = $product->get_sale_price();
						$sale_percentage = 100 - round(( $sale_price / $regular_price ) * 100);
						?>
						<label class="bg-[#d4ff6e] text-[#222] py-1 px-[13px] rounded-[26px] flex leading-[18px] items-center mb-2.5 h-[26px] text-center min-w-[66px]"><i
									class="rbb-icon-flash-1 mr-[1px] text-xs"></i>
							<?php echo '-' . esc_html($sale_percentage) . '%'; ?>
						</label>
					<?php } ?>
					<?php if ( 'instock' === $product->get_stock_status() && $product->is_featured() ) { ?>
						<label class="bg-[#66ad53] text-white font-bold py-[5px] px-[13px] leading-[18px] text-[10px] h-[26px] rounded-[26px] block mb-2.5 uppercase text-center min-w-[66px]"><?php echo esc_html__('New', 'botanica'); ?></label>
					<?php } ?>
					<?php if ( 'instock' !== $product->get_stock_status() ) { ?>
						<label class="bg-[#66ad53] text-white font-bold py-[5px] px-[13px] leading-[18px] text-[10px] h-[26px] rounded-[26px] block mb-2.5 uppercase text-center min-w-[66px]"><?php echo esc_html__('Sold out', 'botanica'); ?></label>
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
							<div class="item-time"><span class="data-number">%D</span><span class="name-time"><?php echo esc_html__('Day%!H', 'botanica'); ?></span></div>
							<div class="item-time"><span class="data-number">%H</span><span class="name-time"><?php echo esc_html__('Hour%!H', 'botanica'); ?></span></div>
							<div class="item-time"><span class="data-number">%M</span><span class="name-time"><?php echo esc_html__('Min%!H', 'botanica'); ?></span></div>
							<div class="item-time"><span class="data-number">%S</span><span class="name-time"><?php echo esc_html__('Secs', 'botanica'); ?></span></div>
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
							<span class="ratting-count text-[#5e5e5e] font-medium ml-1 text-[10px]">(<?php echo esc_html($product->get_rating_count()); ?>)</span>
						</div>
					<?php } ?>
					<a href="<?php echo esc_url($product->get_permalink()); ?>" class="product_name line-clamp-2 block xl:text-base text-[0.8125rem] font-bold md:mb-6 mb-2"><?php echo wp_kses_post($product->get_name()); ?></a>
				</div>
			</div>
			<div class="product_info w-full mt-auto md:pl-7 xl:pr-16 md:pr-3 xl:pb-9 pb-6 relative">
				<div class="product_info-bottom bg-white md:px-0 px-3">
					<div class="product_price text-sm font-extrabold">
						<?php echo wp_kses($product->get_price_html(), 'rbb-kses'); ?>
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
						if ( 'instock' !== $product->get_stock_status() ) {
							$text_cart = esc_html__('Out of stock', 'botanica');
						} elseif ( $product instanceof WC_Product_Variable && $product->get_available_variations() ) {
							$text_cart = esc_html__('Select options', 'botanica');
						} else {
							$text_cart = esc_html__('Add to Cart', 'botanica');
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

			<?php if ( $show_stock || $show_custom_field ) { ?>
			<div class="product-popup bg-white absolute bottom-0 w-full pt-5 <?php echo esc_attr($custom_field_class); ?>">
				<div class="relative pt-3 md:px-0 px-3 md:pl-7 xl:pr-16 md:pr-3 xl:pb-7 pb-6">
					<?php if ( $show_rating ) { ?>
						<div class="product_ratting text-amber-400 flex items-center mb-3.5">
							<?php echo wc_get_rating_html($product->get_average_rating()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="ratting-count text-[#5e5e5e] font-medium ml-1 text-[10px]">(<?php echo esc_html($product->get_rating_count()); ?>)</span>
						</div>
					<?php } ?>
					<a href="<?php echo esc_url($product->get_permalink()); ?>" class="product_name line-clamp-2 block xl:text-base text-[0.8125rem] font-bold md:mb-6 mb-2"><?php echo wp_kses_post($product->get_name()); ?></a>
					<div class="product_info-bottom bg-white md:px-0 px-3">
						<div class="product_price text-lg font-extrabold">
							<?php echo wp_kses($product->get_price_html(), 'rbb-kses'); ?>
						</div>
					</div>
					<div class="product_button absolute md:right-5 md:left-auto left-3 xl:bottom-5 md:bottom-4 bottom-3">
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
						if ( 'instock' !== $product->get_stock_status() ) {
							$text_cart = esc_html__('Out of stock', 'botanica');
						} elseif ( $product instanceof WC_Product_Variable && $product->get_available_variations() ) {
							$text_cart = esc_html__('Select options', 'botanica');
						} else {
							$text_cart = esc_html__('Add to Cart', 'botanica');
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
				<div class="product_custom_field pt-6 border-t border-[#e4e4e4] md:px-7">
					<?php if ( $show_stock ) { ?>
						<div class="product_stock <?php echo $custom_field_exists ? 'mb-3' : 'mb-4'; ?>">
							<?php
							if ( $product->get_stock_status() === 'instock' ) {
								if ( $product->get_stock_quantity() ) {
									?>
									<span class="stock in-stock text-xs font-normal leading-6 text-[#34ad5e]">
										<?php
										// translators: 1: Product stock.
										echo sprintf(esc_html__(' %s Products in stock', 'botanica'), esc_html($product->get_stock_quantity()));
										?>
									</span>
								<?php } else { ?>
									<span class="stock in-stock text-[#34ad5e]">
										<?php
										echo sprintf(esc_html__('In Stock', 'botanica'));
										?>
									</span>
								<?php } ?>
							<?php } else { ?>
								<span class="stock out-of-stock text-xs font-normal leading-6"><?php echo esc_html__('Out of stock', 'botanica'); ?></span>
							<?php } ?>
						</div>
					<?php } ?>
					<?php
					if ( $show_custom_field ) {
						if ( empty($custom_fields) ) {
							$custom_fields = get_post_custom_keys($product->get_id());
						}
						foreach ( $custom_fields as $custom_field ) {
							if ( in_array($custom_field, $custom_field_ignore, true) || strpos($custom_field, '_') === 0 ) {
								continue;
							}
							if ( $product->get_meta($custom_field) ) {
								?>
						<div class="product_info_custom_field">
							<div class="data-item flex items-center">
								<i class="rbb-icon-check-9 pl-[3px]"></i>
								<span class="block ml-4 text-xs font-normal leading-6 text-[color:var(--rbb-general-body-text-color)]">
									<?php echo esc_html($product->get_meta($custom_field)); ?>
								</span>
							</div>
						</div>
						<?php } ?>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
