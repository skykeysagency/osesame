<?php
/**
 * Default template of Multiple Layout
 *
 * @package RisingBambooCore
 */

use Elementor\Icons_Manager;
use RisingBambooCore\Helper\Helper;
use RisingBambooTheme\Helper\Setting;

if ( $sliders ) {
	?>
	<div id="<?php echo esc_attr($id); ?>" class="rbb-elementor-slider overflow-hidden <?php echo esc_attr($layout_type); ?> <?php echo esc_attr($layout); ?>">
		<div class="container mx-auto px-4 wow fadeInUp" data-wow-delay="0.1s">
			<div class="grid md:grid-cols-4 md:pt-0 pt-12 lg:grid-cols-5 xl:grid-cols-6 md:gap-4 gap-0 lg:gap-12 items-center relative">
				<div class="block_title col-span-1 flex justify-between md:block">
					<?php if ( $widget_title || $widget_sub_title ) { ?>
						<div class="title_block">
							<?php
							if ( $widget_sub_title ) {
								?>
								<p class="sub-title text-white text-2xl pb-3"><?php echo esc_html($widget_sub_title); ?></p> <?php } ?>
							<?php
							if ( $widget_title ) {
								?>
								<h4 class="main-title text-white text-xl"><?php echo esc_html($widget_title); ?></h4> <?php } ?>
						</div>
					<?php } ?>
					<?php if ( 'true' === $show_arrows ) { ?>
						<div class="slick-arrow-custom flex md:mt-12 text-lg text-white">
							<span class="prev_custom mr-4 w-[40px] h-[40px] rounded-full bg-white text-black flex items-center justify-center cursor-pointer"><i
										class="rbb-icon-direction-36"></i></span>
							<span class="next_custom w-[40px] h-[40px] rounded-full bg-white text-black flex items-center justify-center cursor-pointer"><i
										class="rbb-icon-direction-39"></i></span>
						</div>
					<?php } ?>
				</div>
				<div class="block_slide md:col-span-3 lg:col-span-4 xl:col-span-5">
					<div class="rbb-slick-carousel slick-carousel slick-carousel-center" data-slick='{
						"arrows": <?php echo esc_attr($show_arrows); ?>,
						"appendArrows": "#<?php echo esc_attr($id); ?> .slick-arrow-custom",
						"prevArrow": "#<?php echo esc_attr($id); ?> .slick-arrow-custom .prev_custom",
						"nextArrow": "#<?php echo esc_attr($id); ?> .slick-arrow-custom .next_custom",
						"dots": <?php echo esc_attr($show_pagination); ?>,
						"autoplay": <?php echo esc_attr($autoplay); ?>,
						"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
						"slidesToShow": <?php echo esc_attr($sliders_to_show_default); ?>,
						"slidesToScroll": <?php echo esc_attr($sliders_to_show_default); ?>,
						<?php
						$i     = 1;
						$count = count($active_break_points);
						if ( $count ) {
							?>
						"responsive": [
							<?php
							foreach ( $active_break_points as $name => $break_point ) {
								$sliders_per_row_bp_val = $slick->get_value_setting('general_layout_multiple_sliders_to_show_' . $name);
								$sliders_per_row_bp     = $sliders_per_row_bp_val ? ceil(abs($sliders_per_row_bp_val['size'])) : $sliders_to_show_default;
								?>
							{
							"breakpoint": <?php echo esc_attr($break_point->get_value()); ?>,
							"settings": {
								"slidesToShow": <?php echo esc_attr($sliders_per_row_bp); ?>,
								"slidesToScroll": <?php echo esc_attr($sliders_per_row_bp); ?>
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
						}'>
						<?php
						foreach ( $sliders as $slider ) {
							$button                   = $slider[ $slick->get_name_setting('multiple_content_button') ] ?? null;
							$button_icon              = $slider[ $slick->get_name_setting('multiple_content_button_icon') ]['value'] ?? null;
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
							<div class="item elementor-repeater-item-<?php echo esc_attr($slider['_id']); ?> md:py-[60px] sm:py-10">
								<div class="block__content duration-300 relative bg-white text-center rounded-[95px] px-3 lg:px-4 pt-4 pb-14 shadow-[5px_5px_10px_rgba(0,0,0,0.15)]">
									<?php if ( $slider[ $slick->get_name_setting('multiple_content_image') ]['url'] ) { ?>
										<img class="pb-5" src="<?php echo esc_url($slider[ $slick->get_name_setting('multiple_content_image') ]['url']); ?>" alt="<?php echo esc_attr($slider[ $slick->get_name_setting('multiple_content_title') ]); ?>">
										<?php
									}
									?>
									<h4 class="text-base font-bold pb-2.5">
										<?php
										if ( 'url' === $button_type || $content_link_attr_string ) {
											?>
											<a <?php echo ! empty($content_link_attr_string) ? $content_link_attr_string : 'href="' . esc_url($button_url) . '"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
												<?php echo esc_attr($slider[ $slick->get_name_setting('multiple_content_title') ]); ?>
											</a>
											<?php
										} else {
											echo esc_attr($slider[ $slick->get_name_setting('multiple_content_title') ]);
										}
										?>
									</h4>
									<p class="text-center"><?php echo esc_attr($slider[ $slick->get_name_setting('multiple_content_description') ]); ?></p>

									<?php
									if ( $button ) {
										$button_url_target   = isset($slider[ $slick->get_name_setting('multiple_content_button_url') ]['is_external']) && 'on' === $slider[ $slick->get_name_setting('multiple_content_button_url') ]['is_external'] ? 'target="_blank"' : '';
										$button_url_nofollow = isset($slider[ $slick->get_name_setting('multiple_content_button_url') ]['nofollow']) && 'on' === $slider[ $slick->get_name_setting('multiple_content_button_url') ]['nofollow'] ? 'rel="nofollow"' : '';
										?>
										<a <?php echo ( 'url' !== $button_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-multiple-button\', event)"' : ''; ?>
												href="<?php echo esc_url($button_url); ?>" <?php echo esc_attr($button_url_target); ?> <?php echo esc_attr($button_url_nofollow); ?>
												class="rbb-slick-button w-[62px] h-[62px] absolute bottom-0 opacity-0 left-1/2 translate-x-[-50%] translate-y-[50%] btn_view-all rounded-full inline-flex justify-center items-center">
												<span class="rounded-full w-[50px] h-[50px] text-[15px] text-white inline-flex justify-center items-center">
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
											<div id="rbb-modal-multiple-button" class="rbb-modal" data-modal-animation="<?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT)); ?>">
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
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
