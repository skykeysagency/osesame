<?php
/**
 * Default template of Multiple Layout
 *
 * @package RisingBambooCore
 */

use Elementor\Icons_Manager;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Elementor as RbbElementorHelper;
use RisingBambooTheme\Helper\Setting;
$modal_effect    = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT);
$outside         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE);
$backdrop_filter = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER);
$classes         = [ 'rbb-modal' ];
$classes[]       = ( true === $backdrop_filter ) ? 'backdrop' : 'backdrop-none';
$classes[]       = ( false === $outside ) ? 'outside-modal' : '';
$class_string    = implode(' ', array_filter($classes));
if ( $sliders ) {
	?>
	<div id="<?php echo esc_attr($id); ?>" class="rbb-elementor-slider overflow-hidden <?php echo esc_attr($layout_type); ?> <?php echo esc_attr($layout); ?>">
		<div class="bg-left hidden wow fadeInLeft absolute top-[140px] left-[50px] z-10" data-wow-delay="0.4s">
			<?php
			if ( ! empty($surrounding_animation_image_01['url']) ) {
				$surrounding_animation_image_01_link = $surrounding_animation_image_01['url'];
			} else {
				$surrounding_animation_image_01_link = get_stylesheet_directory_uri() . '/dist/images/elementor/widgets/slick/icon-carrot.png';
			}
			?>
			<img src="<?php echo esc_url($surrounding_animation_image_01_link); ?>" alt="bg left" >
		</div>
		<div class="bg-right hidden wow fadeInRight absolute top-[10px] right-[60px] z-10" data-wow-delay="0.4s">
			<?php
			if ( ! empty($surrounding_animation_image_02['url']) ) {
				$surrounding_animation_image_02_link = $surrounding_animation_image_02['url'];
			} else {
				$surrounding_animation_image_02_link = get_stylesheet_directory_uri() . '/dist/images/elementor/widgets/slick/icon-pumpkin2.png';
			}
			?>
			<img src="<?php echo esc_url($surrounding_animation_image_02_link); ?>" alt="bg right" >
		</div>
		<div class="container mx-auto overflow-hidden">
			<div class="block_title text-center flex justify-center items-center mb-9">
				<?php if ( $widget_title || $widget_sub_title ) { ?>
					<div class="title_block inline-block">
						<?php
						if ( $widget_sub_title ) {
							?>
							<p class="sub-title text-white font-bold text-lg pb-2 mb-0"><?php echo esc_html($widget_sub_title); ?></p> <?php } ?>
							<?php
							if ( $widget_title ) {
								?>
								<h4 class="main-title text-white text-[2.5rem]"><?php echo esc_html($widget_title); ?></h4> <?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="block_slide swiper-right -mx-[15px] overflow-hidden">
						<div class="rbb-slider-swiper swiper-container mb-14" data-swiper='{
							"scrollbar" : {
								"el": "#<?php echo esc_attr($id); ?> .swiper-scrollbar",
								"draggable": true
							},
							"slidesPerView" : <?php echo esc_attr($sliders_to_show_default); ?>,
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
									$sliders_per_row_bp_val = $slick->get_value_setting('general_layout_multiple_sliders_to_show_' . $name);
									$sliders_per_row_bp     = $sliders_per_row_bp_val ? ceil(abs($sliders_per_row_bp_val['size'])) : $sliders_to_show_default;
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
						}'>
						<div class="swiper-wrapper">
						<?php
						foreach ( $sliders as $slider ) {
							$button                   = $slider[ $slick->get_name_setting('multiple_content_button') ] ?? null;
							$button_icon              = ! empty($slider[ $slick->get_name_setting('multiple_content_button_icon') ]['value']) ? $slider[ $slick->get_name_setting('multiple_content_button_icon') ] : null;
							$button_type              = $slider[ $slick->get_name_setting('multiple_content_button_type') ] ?? null;
							$button_url               = isset($slider[ $slick->get_name_setting('multiple_content_button_url') ]) ? $slider[ $slick->get_name_setting('multiple_content_button_url') ]['url'] : '#';
							$content_link             = $slider[ $slick->get_name_setting('multiple_content_link') ];
							$content_link_attr_string = false;
							if ( ! empty($content_link['url']) ) {
								$content_link_key = 'multiple_content_link_for_' . $slider['_id'];
								$slick->add_link_attributes($content_link_key, $content_link);
								$content_link_attr_string = $slick->get_render_attribute_string($content_link_key);
							}
							?>
							<div class="swiper-slide">
								<div class="item elementor-repeater-item-<?php echo esc_attr($slider['_id']); ?> md:py-[60px] sm:py-10">
									<div class="block__content relative duration-300 text-center rounded-[26px] px-2 md:px-3 lg:px-4 pt-4 pb-5">
										<?php if ( $slider[ $slick->get_name_setting('multiple_content_image') ]['url'] ) { ?>
											<div class="item-img rounded-t-[30px] overflow-hidden relative mb-7 ">
												<img src="<?php echo esc_url($slider[ $slick->get_name_setting('multiple_content_image') ]['url']); ?>" alt="<?php echo esc_attr($slider[ $slick->get_name_setting('multiple_content_title') ]); ?>">
											<?php
											if ( $button ) {

												$button_url_target   = isset($slider[ $slick->get_name_setting('multiple_content_button_url') ]['is_external']) && 'on' === $slider[ $slick->get_name_setting('multiple_content_button_url') ]['is_external'] ? 'target="_blank"' : '';
												$button_url_nofollow = isset($slider[ $slick->get_name_setting('multiple_content_button_url') ]['nofollow']) && 'on' === $slider[ $slick->get_name_setting('multiple_content_button_url') ]['nofollow'] ? 'rel="nofollow"' : '';
												?>
											<a <?php echo ( 'url' !== $button_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-multiple-button\', event)"' : ''; ?>
											href="<?php echo esc_url($button_url); ?>" <?php echo esc_attr($button_url_target); ?> <?php echo esc_attr($button_url_nofollow); ?>
											class="rbb-slick-button w-[62px] h-[62px] absolute left-1/2 bottom-0 translate-x-[-50%]  btn_view-all rounded-full inline-flex justify-center items-center">
											<span class="rounded-full w-[50px] h-[50px] text-lg text-white inline-flex justify-center items-center">
												<?php
												if ( $button_icon ) {
													Icons_Manager::render_icon($button_icon, [ 'aria-hidden' => 'true' ]);
													?>
												<?php } else { ?>
													<i class="rbb-icon-store-16"></i>
												<?php } ?>
											</span>
										</a>
												<?php
												if ( 'url' !== $button_type ) {
													?>
											<div id="rbb-modal-multiple-button" class="<?php echo esc_attr($class_string); ?>" data-modal-animation="<?php echo esc_attr($modal_effect); ?>">
												<div class="rbb-modal-dialog">
													<header class="rbb-modal-header">
														<button class="rbb-close-modal"
														aria-label="close modal">✕
													</button>
												</header>
												<div class="rbb-modal-body">
													<?php
													if ( 'video' === $button_type ) {
														$button_video_url = $slider[ $slick->get_name_setting('multiple_content_button_video') ]['url'];
														?>
														<video width="640" height="360" controls>
															<source src="<?php echo esc_attr($button_video_url); ?>">
																Your browser does not support the video tag.
															</video>

															<?php
													} elseif ( 'youtube' === $button_type ) {
														$button_video_url = Helper::get_youtube_embed($slider[ $slick->get_name_setting('multiple_content_button_youtube') ]);
														?>
															<iframe width="640" height="360"
															src="<?php echo esc_attr($button_video_url); ?>"
															title="YouTube video player"
															allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
															allowfullscreen></iframe>
														<?php
													} elseif ( 'vimeo' === $button_type ) {
														$button_video_url = Helper::get_vimeo_embed($slider[ $slick->get_name_setting('multiple_content_button_vimeo') ]);
														?>
															<iframe width="640" height="360"
															src="<?php echo esc_attr($button_video_url); ?>"
															allow="autoplay; fullscreen; picture-in-picture"
															allowfullscreen></iframe>
														<?php
													} elseif ( 'dailymotion' === $button_type ) {
														$button_video_url = Helper::get_dailymotion_embed($slider[ $slick->get_name_setting('multiple_content_button_dailymotion') ]);
														?>
															<iframe width="640" height="360"
															type="text/html"
															src="<?php echo esc_attr($button_video_url); ?>"
															allowfullscreen
															allow="autoplay"></iframe>
											<?php } ?>
													</div>
												</div>
											</div>
												<?php } ?>
										<?php } ?>
											</div>
											<?php
										}
										?>
										<p class="text-center leading-[22px] max-w-[260px] mb-0 inline-block font-bold text-[color:var(--rbb-general-primary-color)]"><?php echo esc_html($slider[ $slick->get_name_setting('multiple_content_description') ]); ?></p>
										<h4 class="text-base font-bold pt-3">
											<?php
											if ( 'url' === $button_type || $content_link_attr_string ) {
												?>
												<a class="duration-300 text-[color:var(--rbb-general-link-color)] hover:text-[color:var(--rbb-general-link-hover-color)]" <?php echo ! empty($content_link_attr_string) ? $content_link_attr_string : 'href="' . esc_url($button_url) . '"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
													<?php echo esc_attr($slider[ $slick->get_name_setting('multiple_content_title') ]); ?>
												</a>
												<?php
											} else {
												echo esc_attr($slider[ $slick->get_name_setting('multiple_content_title') ]);
											}
											?>
										</h4>
									</div>
								</div>
							</div>
					<?php } ?>
				</div>
				</div>
			</div>
				<div class="swiper-scrollbar"></div>
		</div>
	</div>
<?php } ?>
