<?php
/**
 * RisingBambooTheme package
 *
 * @package RisingBambooTheme
 */

if ( $testimonials ) {
	?>
	<div class="rbb-testimonial pt-11 relative <?php echo esc_attr($layout); ?>">
		<div class="container mx-auto">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative items-center">
				<div class="banner-testimonials lg:text-left text-center col-span-1 lg:col-span-1 xl:mb-5 lg:order-[0] order-1">
					<?php
					if ( isset($image['url']) && ! empty($image['url']) ) {
						?>
							<img class="inline-block" src="<?php echo esc_attr($image['url']); ?>" alt="Testimonials">
						<?php
					}
					?>
				</div>
				<div class="col-span-1 lg:col-span-1 pb-6">
					<div class="title_block text-center text-white mb-24 mb-2">
						<div class="inline-block translate-x-1/2">
							<div class="trapezium inline-block relative pl-[30px] pr-5 py-5 min-h-[150px] items-center -ml-[30px]">
								<span class="absolute rounded-lg w-4 h-[120px] left-0 top-[15px] z-[3333] bg-[color:var(--rbb-general-primary-color)]"></span>
								<div class="flex items-center min-h-[110px]">
									<div class="block">
										<p class="sub_title block"><?php echo esc_html($sub_title); ?></p>
										<h4 class="title md:text-[40px] text-2xl block"><?php echo esc_html($title); ?></h4>
									</div>
								</div>
							</div>
						</div>
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
							<div class="item item-<?php echo esc_attr($testimonial->ID); ?> lg:px-10 text-center text-white">
								<div class="testimonial_text text-xl font-extralight pt-14 mb-7 leading-[30px] relative before:absolute before:top-0 before:left-1/2 before:-translate-x-1/2 before:w-[35px] before:h-[25px] before:bg-[url('../images/elementor/widgets/testimonials/quote3.png')]">
									<?php echo esc_html(wp_trim_words($testimonial->post_content, 38, '...')); ?>
								</div>
								<div class="testimonial_ratings flex justify-center text-base mb-6 !text-[color:var(--rbb-general-primary-color)]">
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
								</div>
								<div class="info_other md:mb-10 mb-5">
									<div class="flex justify-center items-center text-[0.8125rem] font-bold">
										<div class="testimonial_info pr-1">
											<?php echo esc_html($testimonial->post_title); ?>
										</div>
										<div class="testimonial_excerpt"> - <?php echo esc_html($testimonial->post_excerpt); ?></div>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
