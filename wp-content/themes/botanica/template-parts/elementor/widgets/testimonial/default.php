<?php
/**
 * RisingBambooTheme package
 *
 * @package RisingBambooTheme
 */

if ( $testimonials ) {
	?>
	<div class="rbb-testimonial pt-11 relative <?php echo esc_attr($layout); ?>">
		<div class="bg hidden lg:block absolute top-0 -left-[7vw] -skew-x-[22deg] w-1/2 h-full rounded-r-[50px] bg-[#bee2b1]"></div>
		<div class="container mx-auto">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative items-center">
				<div class="lg:text-left text-center col-span-1 lg:col-span-1 mb-5 sm:mb-0">
					<?php
					if ( isset($image['url']) && ! empty($image['url']) ) {
						?>
							<img class="inline-block" src="<?php echo esc_attr($image['url']); ?>" alt="Testimonials">
						<?php
					}
					?>
				</div>
				<div class="col-span-1 lg:col-span-1 pb-6">
					<div class="title_block text-center mb-11">
						<p class="sub_title text-4xl mb-5"><?php echo esc_html($sub_title); ?></p>
						<h4 class="title text-2xl"><?php echo esc_html($title); ?></h4>
					</div>
					<div class="slick-carousel slick-carousel-center load-item" data-slick='{
						"arrows": <?php echo esc_attr($show_arrows); ?>,
						"dots": <?php echo esc_attr($show_pagination); ?>,
						"autoplay": <?php echo esc_attr($autoplay); ?>,
						"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>
					}'>
						<?php
						foreach ( $testimonials as $testimonial ) {
							?>
							<div class="item item-<?php echo esc_attr($testimonial->ID); ?> lg:px-16 text-center">
								<div class="testimonial_text text-base font-light mb-7 leading-[30px] relative"><?php echo wp_kses_post($testimonial->post_content); ?></div>
								<div class="testimonial_ratings flex justify-center text-base mb-5 text-[color:var(--rbb-general-primary-color)]">
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
								</div>
								<div class="testimonial_info flex items-center justify-center mb-4 font-bold">
									<?php echo esc_html($testimonial->post_title); ?>
									<div class="testimonial_excerpt pl-1 font-normal"> - <?php echo esc_html($testimonial->post_excerpt); ?></div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
