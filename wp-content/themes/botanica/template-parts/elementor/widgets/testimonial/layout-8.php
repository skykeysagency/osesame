<?php
/**
 * RisingBambooTheme package
 *
 * @package RisingBambooTheme
 */

if ( $testimonials ) { ?>
	<div class="container mx-auto">
		<div class="rbb-testimonial overflow-hidden <?php echo esc_attr($layout); ?>">
			<div class="max-w-[900px] mx-auto px-[15px] overflow-hidden">
				<div class="relative text-center">
					<div class="title_block text-center md:mb-[42px] mb-12">
						<h2 class="title text-[#222] pb-2"><?php echo esc_html($title); ?></h2>
						<p class="sub_title text-xs font-bold mb-2"><?php echo esc_html($sub_title); ?></p>
						<?php
						if ( isset($image['url']) && ! empty($image['url']) ) {
							?>
							<div class="inline-block">
								<img class="inline-block !max-w-[54px]" src="<?php echo esc_attr($image['url']); ?>" alt="Testimonials">
							</div>
							<?php
						}
						?>
					</div>
					<div id="testimonial_quote" class="slick-carousel slick-carousel-center dots-style2 load-item" data-slick='{
						"arrows": false,
						"dots": <?php echo esc_attr($show_pagination); ?>,
						"autoplay": <?php echo esc_attr($autoplay); ?>,
						"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
						"slidesToShow": 1,
						"slidesToScroll": 1,
						"asNavFor": "#testimonial_thumb"
					}'>
					<?php
					foreach ( $testimonials as $testimonial ) { 
						?>
						<div class="item item-<?php echo esc_attr($testimonial->ID); ?> text-left">
							<div class="testimonial_text text-center font-light text-xl mb-10 leading-[38px] relative">
								<?php echo esc_html($testimonial->post_content); ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div id="testimonial_thumb" class="slick-carousel slick-carousel-center load-item mx-auto md:max-w-[500px] max-w-[300px]" data-slick='{
					"arrows": <?php echo esc_attr($show_arrows); ?>,
					"dots": false,
					"autoplay": <?php echo esc_attr($autoplay); ?>,
					"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
					"slidesToShow": 1,
					"slidesToScroll": 1,
					"centerMode": true,
					"variableWidth": true,
					"asNavFor": "#testimonial_quote"
				}'>
				<?php
				foreach ( $testimonials as $testimonial ) { 
					?>
					<div class="item min-h-[148px] pt-[10px] relative item-<?php echo esc_attr($testimonial->ID); ?> px-[15px]">
						<div class="item-img duration-300 rounded-full w-[70px] h-[70px] inline-block overflow-hidden">
							<?php
							if ( has_post_thumbnail($testimonial) ) {
								echo get_the_post_thumbnail(
									$testimonial,
									'post-thumbnail',
									[
										'class' => 'w-full object-cover !w-[90px] !h-[90px]', 
										'alt'   => get_the_title($testimonial),
									]
								);
							} else {
								?>
								<img class="object-cover !w-[90px] !h-[90px]" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/default-thumbnail.png'); ?>" alt="<?php echo esc_attr__('Default post thumbnail', 'botanica'); ?>" >
							<?php } ?>
						</div> 
						<div class="info_other absolute bottom-0 w-[300%] opacity-0 duration-300 left-1/2 -translate-x-1/2">
							<div class="flex justify-center items-center font-bold">
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
