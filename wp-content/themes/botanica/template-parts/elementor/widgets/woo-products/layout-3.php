<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\Elementor as RbbElementorHelper;
use RisingBambooTheme\App\App;

/**
 * Use for woocommerce_template_loop_add_to_cart func.
 */
global $product;
if ( count($data['products']) ) {
	?>
	<div id="<?php echo esc_attr($id); ?>" class="rbb_woo_products overflow-hidden <?php echo esc_attr($layout); ?> layout-2">
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
		<div class="container mx-auto relative mb-20">
			<div class="sm:flex items-center justify-center items-center mb-7">
				<div class="title_left md:flex md:items-center">
					<?php if ( $show_title ) { ?>
					<div class="text-center">
						<h2 class="title"><?php echo wp_kses_post($data['title']); ?></h2>
						<span class="sub-title block"><?php echo wp_kses_post($subtitle); ?></span>
					</div>
				<?php } ?>
					<?php
					if ( 'category' === $data['type'] ) {
						?>
						<?php
						$cat_count = count($data['categories']);
						if ( '1' < $cat_count || ( $show_filter && '1' === $cat_count ) ) {
							?>
							<span class="hidden sm:block text-sm uppercase font-bold mx-8"><?php echo esc_html__('Of', 'botanica'); ?></span>
							<select class="appearance-none"
							data-class="relative inset-0 px-4 font-extrabold text-base uppercase cursor-pointer transition-all duration-200 ease-in border-2 rounded-[53px]"
							data-ajax='{
								"action": "rbb_get_products_by_category",
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
		<div class="block_slide swiper-right overflow-hidden">
			<div class="rbb-swiper swiper-container md:pb-[68px] pb-10"
			data-swiper='{
				<?php if ( $show_pagination ) { ?>
				"scrollbar" : {
					"el": "#<?php echo esc_attr($id); ?> .swiper-scrollbar",
					"draggable": true
				},
				<?php } ?>
				"grid": {
					"rows": <?php echo esc_attr($row); ?>,
					"fill": <?php echo esc_attr($row); ?>
				},
				"slidesPerView" : <?php echo esc_attr($slides_per_row); ?>,
				"spaceBetween": 30,
				"navigation": {
					"nextEl": "#<?php echo esc_attr($id); ?> .next_custom",
					"prevEl": "#<?php echo esc_attr($id); ?> .prev_custom"
				},
				<?php
				$i                   = 1;
				$active_break_points = RbbElementorHelper::get_breakpoint_mobile_first($active_break_points);
				$count               = count($active_break_points);
				if ( $count ) {
					?>
					"breakpoints": {
						<?php
						foreach ( $active_break_points as $name => $break_point ) {
							$sliders_per_row_bp = ( $widget->get_value_setting('general_layout_slides_per_row_' . $name) ) ? ceil(abs($widget->get_value_setting('general_layout_slides_per_row_' . $name)['size'])) : $slides_per_row;
							?>
							"<?php echo esc_attr($break_point); ?>" : {
								"spaceBetween": 0,
								"slidesPerView" : <?php echo esc_attr($sliders_per_row_bp); ?>
							}
							<?php
							if ( $i < $count ) {
								echo ',';
							}
							$i++;
						}
						?>
					}
				<?php } ?>
			}'
			>
			<div class="swiper-wrapper">

				<?php
				foreach ( $data['products'] as $product ) {
					?>
					<div class="swiper-slide">
						<?php
						View::instance()->load(
							'elementor/widgets/woo-products/fragments/item',
							[
								'product' => $product,
							]
						);
						?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if ( $show_pagination ) { ?>
		<div class="swiper-scrollbar !bg-white z-[2]"></div>
	<?php } ?>
	<?php if ( $show_arrows ) { ?>
		<div class="arrow-custom block_btn flex z-10 absolute bottom-0 translate-y-[24%] right-[15px]">
			<span class="prev_custom mr-[5px] md:w-[53px] md:h-[53px] md:leading-[53px] w-10 h-10 leading-10 text-lg text-center rounded-full cursor-pointer"></span>
				<span class="next_custom md:w-[53px] md:h-[53px] md:leading-[53px] w-10 h-10 leading-10 text-lg text-center rounded-full cursor-pointer"></span>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php
}
?>
