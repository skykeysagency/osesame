<?php
/**
 * RisingBambooTheme package
 *
 * @package RisingBambooTheme
 */

if ( $testimonials ) {
	?>
	<div class="rbb-elementor-slider rbb-testimonial lg:pt-0 md:pt-7 relative <?php echo esc_attr($layout); ?>">
		<div class="container mx-auto md:px-[15px] px-0">
			<div class="grid grid-cols-1 md:grid-cols-5 lg:grid-cols-2 gap-8 items-center wow fadeInUp">
				<div class="col-span-1 md:col-span-3 lg:col-span-1 md:px-0 px-[15px]">
					<div class="title_block text-left md:mb-[75px] mb-12">
						<p class="sub_title text-3xl mb-7"><?php echo esc_html($sub_title); ?></p>
						<h2 class="title"><?php echo esc_html($title); ?></h2>
					</div>
					<div class="rbb-slick-carousel slick-carousel slick-carousel-center dots-style2 load-item" data-slick='{
						"arrows": <?php echo esc_attr($show_arrows); ?>,
						"dots": <?php echo esc_attr($show_pagination); ?>,
						"autoplay": <?php echo esc_attr($autoplay); ?>,
						"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>
					}'>
					<?php
					foreach ( $testimonials as $testimonial ) {
						?>
						<div class="item item-<?php echo esc_attr($testimonial->ID); ?> text-left">
							<div class="flex items-center mb-5">
								<div class="border border-[#d3e7e6] rounded-full w-[78px] h-[78px] p-[7px] inline-block overflow-hidden bg-white">
									<div class="rounded-full overflow-hidden">
										<?php
										if ( has_post_thumbnail($testimonial) ) {
											echo get_the_post_thumbnail(
												$testimonial,
												'post-thumbnail',
												[
													'class' => 'w-full object-cover !w-[62px] !h-[62px]',
													'alt' => get_the_title($testimonial),
												]
											);
										} else {
											?>
											<img class="object-cover !w-[62px] !h-[62px]" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/default-thumbnail.png'); ?>" alt="<?php echo esc_attr__('Default post thumbnail', 'botanica'); ?>" >
										<?php } ?>
									</div>
								</div>
								<div class="testimonial_ratings flex justify-center text-base ml-[30px] text-[#fdad09]">
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
									<i class="rbb-icon-rating-start-filled-1"></i>
								</div>
							</div>
							<div class="testimonial_text text-base mb-6 leading-[var(--typography-body-line-height)] line-clamp-3 relative">
								<?php echo esc_html($testimonial->post_content); ?>
							</div>
							<div class="testimonial_info text-lg mb-2 font-bold text-[color:var(--rbb-general-link-color)]"><?php echo esc_html($testimonial->post_title); ?></div>
							<div class="testimonial_excerpt"><?php echo esc_html($testimonial->post_excerpt); ?></div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="banner-img col-span-1 md:col-span-2 lg:col-span-1 relative md:px-0 px-[15px] md:text-right text-center md:order-1 -order-1">
				<?php
				if ( isset($image['url']) && ! empty($image['url']) ) {
					?>
					<div class="rounded-full overflow-hidden inline-block">
						<img class="inline-block" src="<?php echo esc_attr($image['url']); ?>" alt="Testimonials">
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
