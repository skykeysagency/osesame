<?php
/**
 * Elementor widget : woo-product.
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;
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
<div class="item">
	<div class="item-product relative border rounded-[18px] bg-white md:flex justify-center items-center">
		<div class="bg-product absolute w-full h-full -z-10 rounded-[18px] duration-300 shadow-[10px_11px_20px_0px_rgba(0,0,0,0.15)]"></div>
		<?php if ( $show_wishlist ) { ?>
			<div class="absolute md:top-5 z-10 top-3 md:right-5 right-3">
				<?php echo Tag::wish_list_button($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php } ?>
		<div class="md:w-1/2 thumbnail-container md:static relative
			<?php 
			echo wp_kses_post($effect_images);
			echo ( true === $product_images ) && \RisingBambooCore\Helper\Woocommerce::get_gallery_image($product, [ 600, 600 ]) ? ' hover-images ' : ' none-hover-images ';
			?>
			">
			<a class="relative block rounded-[18px] overflow-hidden" href="<?php echo esc_url($product->get_permalink()); ?>">
				<?php
					echo wp_kses(
						$product->get_image(
							[ 600, 600 ],
							[
								'class' => 'max-w-full w-full',
								'alt'   => esc_attr($product->get_name()),
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
				<div class="product-flags absolute md:left-4 left-3 md:top-5 top-3 z-10 font-semibold text-[11px]">
					<?php
					if ( $show_percentage_discount && $product->get_sale_price() ) {
						$regular_price   = $product->get_regular_price();
						$sale_price      = $product->get_sale_price();
						$sale_percentage = 100 - round(( $sale_price / $regular_price ) * 100);
						?>
						<label class="bg-[#d4ff6e] text-[#222] py-1 px-[13px] rounded-[26px] flex leading-[18px] items-center mb-2.5 h-[26px] text-center min-w-[66px]"><i class="rbb-icon-flash-1 mr-[1px] text-xs"></i>
							<?php echo '-' . esc_html($sale_percentage) . '%'; ?>
						</label>
					<?php } ?>
					<?php if ( $product->is_featured() && 'instock' === $product->get_stock_status() ) { ?>
						<label class="bg-[#66ad53] text-white font-bold py-[5px] leading-[18px] px-[13px] text-[10px] h-[26px] rounded-[26px] block mb-2.5 uppercase text-center min-w-[66px]"><?php echo esc_html__('New', 'botanica'); ?>
					</label>
				<?php } ?>
				<?php if ( 'instock' !== $product->get_stock_status() ) { ?>
					<label class="bg-[#66ad53] text-white font-bold py-[5px] px-[13px] leading-[18px] text-[10px] h-[26px] rounded-[26px] block mb-2.5 uppercase text-center min-w-[66px]"><?php echo esc_html__('Sold out', 'botanica'); ?>
				</label>
			<?php } ?>
		</div>
		<?php
		$countdown_date_to = $product->get_date_on_sale_to();
		if ( $show_countdown && $countdown_date_to ) {
			$current_date         = new \WC_DateTime();
			$countdown_date_start = $product->get_date_on_sale_from() ?? $product->get_date_modified();
			if ( ( $current_date >= $countdown_date_start ) && ( $current_date <= $countdown_date_to ) ) {
				?>
				<div class="item-countdown absolute md:inset-x-[30px] inset-x-1 md:top-[5px] md:bottom-auto bottom-[5px] duration-300 z-50">
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
	<div class="md:w-1/2 md:py-4 pt-4 pb-6 md:px-[15px] product_info relative">
		<div class="product_info-bottom bg-white pt-3 md:px-0 px-3">
			<?php if ( $show_rating ) { ?>
				<div class="product_ratting text-amber-400 flex items-center mb-3.5">
					<?php echo wc_get_rating_html($product->get_average_rating()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="ratting-count text-[#5e5e5e] font-medium ml-1 text-[10px]">(<?php echo esc_html($product->get_rating_count()); ?>)</span>
				</div>
			<?php } ?>
			<a href="<?php echo esc_url($product->get_permalink()); ?>" class="product_name block md:text-base text-[0.8125rem] font-bold md:mb-5 mb-2"><?php echo wp_kses_post($product->get_name()); ?></a>
			<div class="product_price text-sm font-extrabold overflow-hidden">
				<?php echo wp_kses($product->get_price_html(), 'rbb-kses'); ?>
			</div>
			<?php if ( $show_stock ) { ?>
				<?php if ( $product->get_stock_status() === 'instock' ) { ?>
					<?php if ( $product->get_stock_quantity() ) { ?>
						<div class="product_stock pt-4 pb-2 md:block hidden">
					<span class="stock in-stock text-xs font-normal leading-6 text-[#34ad5e]">
						<?php
						// translators: 1: Product stock.
						echo sprintf(esc_html__(' %s Products in stock', 'botanica'), esc_html($product->get_stock_quantity()));
						?>
					</span>
				</div>
				<?php } else { ?>
					<div class="product_stock py-3 md:block hidden">
						<span class="stock in-stock text-xs font-normal leading-6 text-[#34ad5e]">
							<?php echo sprintf(esc_html__('In Stock', 'botanica')); ?>
						</span>
					</div>
					<?php } ?>	
					<?php
				} else {
					?>
					<div class="product_stock pt-4 pb-2 md:block hidden">
					<span class="stock out-of-stock text-xs font-normal leading-6">
					<?php echo esc_html__('Out of stock', 'botanica'); ?>
				</span>
			</div>
				<?php } ?>

				<?php } ?>
			<?php
			if ( $show_custom_field ) {
				?>
					<?php
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
		<div class="product-groups z-10 bottom-0 opacity-0 absolute duration-300 left-[15px] right-0">
			<div class="flex relative bg-white rounded-br-[20px]">
			<?php
			if ( $show_add_to_cart ) {
				$args       = [];
				$click_cart = '';
				$icon       = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON);
				if ( $icon ) {
					$args['cart-icon'] = '<div class="add-to-cart-icon relative text-center mb-1 leading-10">
					<i class="rbb-icon ' . $icon . '"></i>
					</div>';
				}
				woocommerce_template_loop_add_to_cart($args);
			}
			?>
			<?php
			if ( $show_compare ) {
				echo Tag::compare_button($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<?php
			if ( $show_quickview ) {
				echo Tag::quick_view_button($product); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
				</div>
			</div>
		</div>
	</div>
</div>
