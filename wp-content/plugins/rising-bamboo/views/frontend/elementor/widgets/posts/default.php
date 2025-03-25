<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

use RisingBambooCore\Core\View;
use RisingBambooTheme\App\App;

if ( count($posts['posts']) ) {
	?>
	<div id="<?php echo esc_attr($id); ?>" class="rbb_posts <?php echo esc_attr($layout); ?> pt-24 pb-[67px]">
		<div class="container mx-auto mt-2">
			<div class="block_slide -ml-7">
				<div class="block_slider__content pl-7 overflow-hidden">
					<div class="title uppercase font-extrabold text-4xl mb-14  text-white">
						<?php echo wp_kses_post($posts['title']); ?>
						<span class="sub_title text-sm">
							<?php echo wp_kses_post($sub_title); ?>
						</span>

						<?php
						if ( isset($posts['type']) && 'category' === $posts['type'] ) {
							?>
							<?php
							$cat_count = count($posts['categories']);
							if ( $show_filter && $cat_count > 1 ) {
								?>
								<span class="hidden sm:block text-sm uppercase font-bold mx-8"><?php echo esc_html__('Of', App::get_domain()); ?></span>
								<select class="appearance-none"
										data-class="relative inset-0 px-[30px] font-extrabold text-base uppercase cursor-pointer transition-all duration-200 ease-in border-2 rounded-3xl"
										data-ajax='{
										"action": "rbb_get_posts_by_category",
										"order_by" : "<?php echo esc_attr($order_by); ?>",
										"order" : "<?php echo esc_attr($order); ?>",
										"limit" : "<?php echo esc_attr($limit); ?>",
										"show_author" : "<?php echo esc_attr($show_author); ?>",
										"show_date" : "<?php echo esc_attr($show_date); ?>",
										"show_read_more" : "<?php echo esc_attr($show_read_more); ?>"
									}'
								>
									<?php foreach ( $posts['categories'] as $category_id => $category ) { ?>
										<option value="<?php echo esc_attr($category_id); ?>"><?php echo wp_kses_post($category); ?></option>
									<?php } ?>
								</select>
							<?php } ?>
							<?php
						}
						?>

					</div>
					<div class="rbb-slick-carousel slick-carousel slick-carousel-center" data-slick='{
						"dots": <?php echo ( $show_pagination ) ? 'true' : 'false'; ?>,
						"arrows": <?php echo ( $show_arrows ) ? 'true' : 'false'; ?>,
						"autoplay": <?php echo ( $autoplay ) ? 'true' : 'false'; ?>,
						"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
						"pauseOnFocus": <?php echo ( $autoplay_pause ) ? 'true' : 'false'; ?>,
						"pauseOnHover": <?php echo ( $autoplay_pause ) ? 'true' : 'false'; ?>,
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
								$sliders_per_row_bp_val = $widget->get_value_setting('general_layout_slides_per_row_' . $name);
								$sliders_per_row_bp     = $sliders_per_row_bp_val ? ceil(abs($sliders_per_row_bp_val['size'])) : $slides_per_row;
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
						foreach ( $posts['posts'] as $_post ) {
							View::instance()->load(
								'elementor/widgets/posts/fragments/item',
								[
									'_post' => $_post,
								]
							);
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
