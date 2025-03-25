<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

use Elementor\Icons_Manager;
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
		<div class="absolute top-0 right-[3%] md:block hidden z-10 animate-[iconbanner_3s_linear_infinite]">
			<?php
			if ( ! empty($surrounding_animation_image_02['url']) ) {
				$surrounding_animation_image_02_link = $surrounding_animation_image_02['url'];
			} else {
				$surrounding_animation_image_02_link = get_stylesheet_directory_uri() . '/dist/images/elementor/widgets/slick/icon-honey.png';
			}
			?>
			<img class="xl:w-full w-1/2" src="<?php echo esc_url($surrounding_animation_image_02_link); ?>" alt="bg right" >
		</div>
		<div class="max-w-[1330px] lg:px-[25px] px-[15px] overflow-hidden mx-auto md:mb-20 mt-12 mb-10 wow fadeInUp">
			<div class="flex items-center justify-center items-center mb-6">
				<div class="title_left flex items-center">
					<?php if ( $show_title ) { ?>
					<div class="md:mr-0 mr-4">
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
							<span class="title hidden sm:block text-sm uppercase mx-8"><?php echo esc_html__('Of', 'botanica'); ?></span>
							<select class="appearance-none"
									data-class="relative inset-0 px-[30px] font-extrabold text-base uppercase cursor-pointer transition-all duration-200 ease-in border-2 rounded-[53px]"
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

			<div class="rbb-slick-carousel slick-carousel flex flex-wrap slick-carousel-center load-item-<?php echo esc_attr($slides_per_row); ?>" data-slick='{
				"arrows": <?php echo esc_attr(( $show_arrows ) ? 'true' : 'false'); ?>,
				"dots": <?php echo esc_attr(( $show_pagination ) ? 'true' : 'false'); ?>,
				"autoplay": <?php echo esc_attr(( $autoplay ) ? 'true' : 'false'); ?>,
				"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
				"pauseOnFocus": <?php echo esc_attr(( $autoplay_pause ) ? 'true' : 'false'); ?>,
				"pauseOnHover": <?php echo esc_attr(( $autoplay_pause ) ? 'true' : 'false'); ?>,
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
						$i++;
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
			<div class="all-products button text-center pt-[14px] font-bold"><a class="h-14 px-12 leading-[56px] inline-block rounded-[56px] duration-300 bg-[color:var(--rbb-general-secondary-color)] hover:bg-[color:var(--rbb-general-primary-color)] hover:text-white" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"><?php echo esc_html__('All Products', 'botanica'); ?></a></div>
		</div>
	</div>
	<?php
}
?>
