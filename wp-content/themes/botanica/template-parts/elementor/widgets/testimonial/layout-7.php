<?php
/**
 * RisingBambooTheme package
 *
 * @package RisingBambooTheme
 */

if ( $testimonials ) {
	?>
	<div class="rbb-testimonial <?php echo esc_attr($layout); ?>">
		<div class="container mx-auto">
			<div class="">
				<div class="lg:text-left text-center">
					<?php
					if ( isset($image['url']) && ! empty($image['url']) ) {
						?>
							<img class="inline-block" src="<?php echo esc_attr($image['url']); ?>" alt="Testimonials">
						<?php
					}
					?>
				</div>
				<div class="pb-6">
					<?php if ( ! empty($title) || ! empty($sub_title) ) { ?>
					<div class="title_block text-center mb-11">
						<p class="sub_title text-4xl mb-5"><?php echo esc_html($sub_title); ?></p>
						<h4 class="title text-2xl"><?php echo esc_html($title); ?></h4>
					</div>
					<?php } ?>
					<div class="slick-carousel slick-carousel-center load-item" data-slick='{
						"arrows": <?php echo esc_attr($show_arrows); ?>,
						"dots": <?php echo esc_attr($show_pagination); ?>,
						"autoplay": <?php echo esc_attr($autoplay); ?>,
						"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>
					}'>
						<?php
						foreach ( $testimonials as $testimonial ) {
							?>
							<div class="item item-<?php echo esc_attr($testimonial->ID); ?> text-left">
								<div class="testimonial_ratings flex justify-left text-base mb-6 text-[color:var(--rbb-general-primary-color)]">
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
								</div>
								<div class="testimonial_text text-lg font-light mb-6 leading-[30px] relative"><?php echo wp_kses_post($testimonial->post_content); ?></div>
								<div class="testimonial_info flex items-center justify-left font-bold">
									<?php echo esc_html($testimonial->post_title); ?>
									<div class="testimonial_excerpt pl-1"> - <?php echo esc_html($testimonial->post_excerpt); ?></div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
