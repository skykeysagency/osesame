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
	<div id="<?php echo esc_attr($id); ?>" class="rbb_posts bg-cover <?php echo esc_attr($layout); ?> pb-[67px]">
		<div class="container mx-auto mt-2">
			<div class="block_slide lg:-mx-7">
				<div class="block_slider__content overflow-hidden lg:px-7">
					<div class="text-center">
						<span class="sub_title block text-lg mb-[15px] wow fadeInUp"><?php echo wp_kses_post($sub_title); ?></span>
						<div class="mb-12 text-center md:flex md:justify-center md:items-center">
							<h2 class="title md:mb-0 mb-[30px] block wow fadeInUp"><?php echo wp_kses_post($posts['title']); ?></h2>
							<?php
							if ( isset($posts['type']) && 'category' === $posts['type'] ) {
								?>
								<?php
								$cat_count = isset($posts['categories']) ? count($posts['categories']) : 0;
								if ( 1 < $cat_count || ( $show_filter && 1 === $cat_count ) ) {
									?>
									<span class="title-of hidden md:block text-sm uppercase mx-8"><?php echo esc_html__('Of', 'botanica'); ?></span>
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
						<?php } ?>
					</div>
				</div>
				<div class="rbb-slick-carousel slick-carousel slick-carousel-center flex wow fadeInUp" data-slick='{
					"dots": <?php echo esc_attr(( $show_pagination ) ? 'true' : 'false'); ?>,
					"arrows": <?php echo esc_attr(( $show_arrows ) ? 'true' : 'false'); ?>,
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
							$i++;
						}
						?>
						]
					<?php } ?>
				}'
				>
				<?php
				foreach ( $posts['posts'] as $_post ) {
					View::instance()->load(
						'elementor/widgets/posts/fragments/item-2',
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
