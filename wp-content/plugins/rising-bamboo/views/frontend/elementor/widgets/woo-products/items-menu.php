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
		<div class="max-w-[190px] xl:mx-auto relative">
			<div class="sm:flex items-center justify-between items-center mb-5">
				<div class="title_left flex items-center">
					<div class="title font-extrabold text-base text-[#222]"><?php echo wp_kses_post($data['title']); ?></div>
				</div>
				<?php if ( $show_arrows ) { ?>
				<div class="arrow-custom block_btn flex mt-4">
					<span class="prev_custom w-10 h-10 leading-[42px] text-center rounded-full absolute top-[38%] z-10 -left-[20px] cursor-pointer"><i
								class="rbb-icon-direction-36"></i></span>
					<span class="next_custom w-10 h-10 leading-[42px] text-center rounded-full absolute top-[38%] z-10 -right-[20px] cursor-pointer"><i
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
							$sliders_per_row_bp = ceil(abs($widget->get_value_setting('general_layout_slides_per_row_' . $name)['size'])) ?? $slides_per_row;
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
