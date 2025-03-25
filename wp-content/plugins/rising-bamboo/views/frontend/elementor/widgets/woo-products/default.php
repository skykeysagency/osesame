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
	<div id="<?php echo esc_attr($id); ?>" class="rbb_woo_products <?php echo esc_attr($layout); ?>">
		<div class="container mx-auto mb-20">
			<div class="sm:flex items-center justify-between items-center mb-6">
				<div class="title_left flex items-center">
					<?php
					if ( $show_title ) {
						?>
					<div class="title uppercase font-extrabold md:text-4xl text-xl md:mr-0 mr-4"><?php echo wp_kses_post($data['title']); ?></div>
						<?php
					}
					?>
					<?php
					if ( 'category' === $data['type'] ) {
						?>
						<?php
						$cat_count = count($data['categories']);
						if ( $show_filter || $cat_count > 1 ) {
							?>
							<span class="hidden sm:block text-sm uppercase font-bold mx-8"><?php echo esc_html__('Of', App::get_domain()); ?></span>
							<select class="appearance-none"
									data-class="relative inset-0 px-[30px] font-extrabold text-base uppercase cursor-pointer transition-all duration-200 ease-in border-2 rounded-3xl"
									data-ajax='{
										"action": "rbb_get_products_by_category",
										"order_by" : "<?php echo esc_attr($order_by); ?>",
										"order" : "<?php echo esc_attr($order); ?>",
										"limit" : "<?php echo esc_attr($limit); ?>",
										"show_wishlist" : <?php echo ( $show_wishlist ) ? 'true' : 'false'; ?>,
										"show_rating" : <?php echo ( $show_rating ) ? 'true' : 'false'; ?>,
										"show_quickview" : <?php echo ( $show_quickview ) ? 'true' : 'false'; ?>,
										"show_compare" : <?php echo ( $show_compare ) ? 'true' : 'false'; ?>,
										"show_add_to_cart" : <?php echo ( $show_add_to_cart ) ? 'true' : 'false'; ?>,
										"show_countdown" : <?php echo ( $show_countdown ) ? 'true' : 'false'; ?>
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
				<?php if ( $show_arrows ) { ?>
					<div class="arrow-custom block_btn flex mt-1">
						<span class="prev_custom mr-4 md:w-[53px] md:h-[53px] md:leading-[53px] w-10 h-10 leading-10 text-lg text-center rounded-full cursor-pointer"><i
									class="rbb-icon-direction-36"></i></span>
						<span class="next_custom md:w-[53px] md:h-[53px] md:leading-[53px] w-10 h-10 leading-10 text-lg text-center rounded-full cursor-pointer"><i
									class="rbb-icon-direction-39"></i></span>
					</div>
				<?php } ?>
			</div>

			<div class="rbb-slick-carousel slick-carousel slick-carousel-center" data-slick='{
				"arrows": <?php echo ( $show_arrows ) ? 'true' : 'false'; ?>,
				"appendArrows" : "#<?php echo esc_attr($id); ?> .arrow-custom",
				"prevArrow": "#<?php echo esc_attr($id); ?> .arrow-custom .prev_custom",
				"nextArrow": "#<?php echo esc_attr($id); ?> .arrow-custom .next_custom",
				"dots": <?php echo ( $show_pagination ) ? 'true' : 'false'; ?>,
				"autoplay": <?php echo ( $autoplay ) ? 'true' : 'false'; ?>,
				"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
				"rows": <?php echo esc_attr($row); ?>,
				"slidesPerRow" : <?php echo esc_attr($slides_per_row); ?>,
				<?php
				$i     = 1;
				$count = count($active_break_points);
				if ( $count ) {
					?>
				"responsive": [
					<?php
					foreach ( $active_break_points as $name => $break_point ) {
						$sliders_per_row_bp = ( $widget->get_value_setting('general_layout_slides_per_row_' . $name) ) ? ceil(abs($widget->get_value_setting('general_layout_slides_per_row_' . $name)['size'])) : $slides_per_row;
						?>
					{
						"breakpoint": <?php echo esc_attr($break_point->get_value()); ?>,
						"settings": {
							"slidesPerRow": <?php echo esc_attr($sliders_per_row_bp); ?>
						}
					}
						<?php
						if ( $i < $count ) {
							echo ',';
						}
						++$i;
					}
					?>
					]
					<?php } ?>
				}'
			>
				<?php
				foreach ( $data['products'] as $product ) {
					View::instance()->load(
						'elementor/widgets/woo-products/fragments/item',
						[
							'product' => $product,
						]
					);
				}
				?>
			</div>
		</div>
	</div>
	<?php
}
?>
