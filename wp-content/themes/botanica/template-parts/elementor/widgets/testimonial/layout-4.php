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
			<div class="mb-[22px] text-center wow fadeInUp">
				<?php
				if ( isset($image['url']) && ! empty($image['url']) ) { 
					?>
					<img class="inline-block" src="<?php echo esc_attr($image['url']); ?>" alt="Testimonials">
				<?php } ?>
			</div>
			<div class="title_block text-center mb-11 wow fadeInUp">
				<p class="sub_title text-lg mb-[6px]"><?php echo esc_html($sub_title); ?></p>
				<h2 class="title"><?php echo esc_html($title); ?></h2>
			</div>
			<div class="slick-carousel pb-[50px] slick-carousel-center wow fadeInUp load-item-2" data-slick='{
				"arrows": <?php echo esc_attr($show_arrows); ?>,
				"dots": <?php echo esc_attr($show_pagination); ?>,
				"autoplay": <?php echo esc_attr($autoplay); ?>,
				"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
				"slidesToShow": 2,
				"responsive": [
					{
						"breakpoint": 1024,
						"settings": {
							"slidesToShow": 2
						}
					},
					{
						"breakpoint": 991,
						"settings": {
							"slidesToShow": 2
						}
					},
					{
						"breakpoint": 768,
						"settings": {
							"slidesToShow": 1
						}
					},
					{
						"breakpoint": 480,
						"settings": {
							"slidesToShow": 1
						}
					}
				]
			}'>
			<?php
			foreach ( $testimonials as $testimonial ) {
				?>
				<div class="item last:lg:pl-[95px] last:pl-[25px] first:lg:pr-[95px] first:pr-[25px] relative item-<?php echo esc_attr($testimonial->ID); ?> text-left">
					<div class="item-content relative lg:pl-[70px] pl-16 lg:my-[55px] after:content-[''] after:absolute after:top-[5px] after:left-0 after:w-[49px] after:h-[38px] after:bg-[url('../images/elementor/widgets/testimonials/quote2.png')]">
						<div class="testimonial_text text-base font-light mb-8 leading-6 relative"><?php echo esc_html($testimonial->post_content); ?></div>
						<div class="flex items-center">
							<div class="rounded-full w-[70px] h-[70px] inline-block overflow-hidden">
								<div class="rounded-full overflow-hidden">
									<?php
									if ( has_post_thumbnail($testimonial) ) {
										echo get_the_post_thumbnail(
											$testimonial,
											'post-thumbnail',
											[
												'class' => 'w-full object-cover !w-[70px] !h-[70px]', 
												'alt'   => get_the_title($testimonial),
											]
										);
									} else {
										?>
										<img class="object-cover !w-[70px] !h-[70px]" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/default-thumbnail.png'); ?>" alt="<?php echo esc_attr__('Default post thumbnail', 'botanica'); ?>" >
									<?php } ?>
								</div>
							</div>
							<div class="pl-7 text-base font-bold">
								<div class="testimonial_info mb-[1px] uppercase text-[color:var(--rbb-general-link-color)]"><?php echo esc_html($testimonial->post_title); ?></div>
								<div class="testimonial_excerpt text-[color:var(--rbb-general-primary-color)]"><?php echo esc_html($testimonial->post_excerpt); ?></div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>
