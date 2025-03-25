<?php
/**
 * RisingBambooTheme package
 *
 * @package RisingBambooTheme
 */

use Elementor\Icons_Manager;
use RisingBambooCore\Helper\Helper as CoreHelper;
use RisingBambooCore\Helper\Elementor as RisingBambooElementorHelper;
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
	<div class="rbb-elementor-slider md:justify-start <?php echo esc_attr($layout_type); ?> <?php echo esc_attr($layout); ?>">
		<div class="flex rbb-slick-carousel slick-carousel slick-carousel-center load-item" data-slick='{
			"arrows": <?php echo esc_attr($show_arrows); ?>,
			"dots": <?php echo esc_attr($show_pagination); ?>,
			"autoplay": <?php echo esc_attr($autoplay); ?>,
			"fade": true,
			"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>
		}'>
			<?php
			foreach ( $sliders as $slider ) {
				$slider_title     = $slider[ $slick->get_name_setting('title') ] ?? null;
				$slider_sub_title = $slider[ $slick->get_name_setting('subtitle') ] ?? null;
				$description      = $slider[ $slick->get_name_setting('description') ] ?? null;
				$button_1         = $slider[ $slick->get_name_setting('button_1') ] ?? null;
				$button_2         = $slider[ $slick->get_name_setting('button_2') ] ?? null;
				?>
				<div class="w-full item elementor-repeater-item-<?php echo esc_attr($slider['_id']); ?>">
					<div class="item-content relative">
						<div class="md:absolute md:py-0 pt-9 pb-6 top-0 w-full h-full">
							<div class="container <?php echo esc_attr(( $parallax ) ? 'parallax' : ''); ?> m-auto relative h-full">
								<?php
								if ( $slider_title || $slider_sub_title || $description || $button_1 || $button_2 ) {
									$horizontal_align = $slider[ $slick->get_name_setting('horizontal_align') ] ?? 'start';
									switch ( $horizontal_align ) {
										case 'end':
											$horizontal_align_class = 'right-0 md:text-right text-center';
											break;
										case 'center':
											$horizontal_align_class = 'left-1/2 md:-translate-x-1/2 text-center';
											break;
										default:
											$horizontal_align_class = 'left-0 md:text-left text-center';
									}
									
									$vertical_align = $slider[ $slick->get_name_setting('vertical_align') ] ?? 'top';
									switch ( $vertical_align ) {
										case 'bottom':
											$vertical_align_class = 'md:bottom-1/4';
											break;
										case 'middle':
											$vertical_align_class = 'md:top-1/2 transform md:-translate-y-1/2';
											break;
										default:
											$vertical_align_class = 'md:top-1/4';
									}
									?>
									<div class="md:absolute info-wap lg:w-[42%] md:w-1/2 z-50 <?php echo esc_attr($horizontal_align_class); ?> <?php echo esc_attr($vertical_align_class); ?>">
										<div class="info-inner md:px-[15px]">
											<?php
											$countdown          = $slider[ $slick->get_name_setting('countdown_due_date') ];
											$countdown_position = $slider[ $slick->get_name_setting('countdown_position') ] ?? 'absolute';
											if ( ! empty($countdown) && ( time() < strtotime($countdown) ) ) {
												?>
												<div class="rbb-countdown wow fadeInLeft flex justify-self-end mb-14 <?php echo esc_attr($countdown_position); ?>" data-visibility-start="invisible" data-countdown-date="<?php echo esc_attr($countdown); ?>">
													<div class="item-time days"><span class="data-number">%D</span><span class="name-time"><?php echo esc_html__('Day%!H', 'botanica'); ?></span>
													</div>
													<div class="item-time"><span class="data-number">%H</span><span class="name-time"><?php echo esc_html__('Hour%!H', 'botanica'); ?></span>
													</div>
													<div class="item-time"><span class="data-number">%M</span><span class="name-time"><?php echo esc_html__('Min%!H', 'botanica'); ?></span>
													</div>
													<div class="item-time"><span class="data-number">%S</span><span class="name-time"><?php echo esc_html__('Secs', 'botanica'); ?></span>
													</div>
												</div>
												<?php
											}
											?>
											<?php
											if ( $slider_sub_title ) {
												$subtitle_animation       = ! empty($slider[ $slick->get_name_setting('subtitle_animation') ]) ? $slider[ $slick->get_name_setting('subtitle_animation') ] . ' ' . ( $slider[ $slick->get_name_setting('subtitle_animation_duration') ] ?? '' ) : '';
												$subtitle_animation_delay = ! empty($slider[ $slick->get_name_setting('subtitle_animation_delay') ]) ? $slider[ $slick->get_name_setting('subtitle_animation_delay') ] . 'ms"' : '';
												$subtitle_visibility      = ! empty($subtitle_animation) ? 'invisible' : 'visible';
												?>
												<h3 data-visibility-start="<?php echo esc_attr($subtitle_visibility); ?>"
													<?php echo ! empty($subtitle_animation) ? 'data-animation="' . esc_attr($subtitle_animation) . '"' : ''; ?>
													<?php echo ! empty($subtitle_animation_delay) ? 'data-animation-delay="' . esc_attr($subtitle_animation_delay) . '"' : ''; ?>
													class="sub-title sm:text-base md:text-xl md:mb-2 lg:text-4xl lg:mb-6 <?php echo esc_attr($subtitle_visibility); ?>"
												>
													<?php echo wp_kses_post($slider_sub_title); ?>
												</h3>
											<?php } ?>
											<?php
											if ( $slider_title ) {
												$title_animation       = ! empty($slider[ $slick->get_name_setting('title_animation') ]) ? $slider[ $slick->get_name_setting('title_animation') ] . ' ' . ( $slider[ $slick->get_name_setting('title_animation_duration') ] ?? '' ) : '';
												$title_animation_delay = ! empty($slider[ $slick->get_name_setting('title_animation_delay') ]) ? $slider[ $slick->get_name_setting('title_animation_delay') ] . 'ms"' : '';
												$title_visibility      = ! empty($title_animation) ? 'invisible' : 'visible';
												?>
												<h2 data-visibility-start="<?php echo esc_attr($title_visibility); ?>"
													<?php echo ! empty($title_animation) ? 'data-animation="' . esc_attr($title_animation) . '"' : ''; ?>
													<?php echo ! empty($title_animation_delay) ? 'data-animation-delay="' . esc_attr($title_animation_delay) . '"' : ''; ?>
													class="title sm:text-base md:text-xl md:mb-1 lg:text-4xl lg:mb-4 <?php echo esc_attr($title_visibility); ?>"
												>
													<?php echo wp_kses_post($slider_title); ?>
												</h2>
											<?php } ?>
											<?php
											if ( $description ) {
												$description_animation       = ! empty($slider[ $slick->get_name_setting('description_animation') ]) ? $slider[ $slick->get_name_setting('description_animation') ] . ' ' . ( $slider[ $slick->get_name_setting('description_animation_duration') ] ?? '' ) : '';
												$description_animation_delay = ! empty($slider[ $slick->get_name_setting('description_animation_delay') ]) ? $slider[ $slick->get_name_setting('description_animation_delay') ] . 'ms"' : '';
												$description_visibility      = ! empty($description_animation) ? 'invisible' : 'visible';
												?>
												<div data-visibility-start="<?php echo esc_attr($description_visibility); ?>"
													<?php echo ! empty($description_animation) ? 'data-animation="' . esc_attr($description_animation) . '"' : ''; ?>
													<?php echo ! empty($description_animation_delay) ? 'data-animation-delay="' . esc_attr($description_animation_delay) . '"' : ''; ?>
													class="description md:truncate md:mb-4 lg:whitespace-normal lg:mb-6 <?php echo esc_attr($description_visibility); ?>"
												>
													<?php echo wp_kses_post($description); ?>
												</div>
											<?php } ?>
											<?php
											$horizontal_align_center = $slider[ $slick->get_name_setting('horizontal_align') ] ?? 'center';
											?>
											<div class="button-group flex justify-center items-center md:text-left text-center md:justify-<?php echo wp_kses_post($horizontal_align_center); ?>">
												<?php
												if ( $button_1 ) {
													$button_1_type             = $slider[ $slick->get_name_setting('button_1_type') ] ?? null;
													$button_1_url              = isset($slider[ $slick->get_name_setting('button_1_url') ]) ? $slider[ $slick->get_name_setting('button_1_url') ]['url'] : '#';
													$button_1_url_target       = isset($slider[ $slick->get_name_setting('button_1_url') ]['is_external']) && 'on' === $slider[ $slick->get_name_setting('button_1_url') ]['is_external'] ? '_blank' : '';
													$button_1_url_nofollow     = isset($slider[ $slick->get_name_setting('button_1_url') ]['nofollow']) && 'on' === $slider[ $slick->get_name_setting('button_1_url') ]['nofollow'] ? 'nofollow' : '';
													$button_1_icon             = ! empty($slider[ $slick->get_name_setting('button_1_icon') ]['value']) ? $slider[ $slick->get_name_setting('button_1_icon') ] : null;
													$button_1_animation        = ! empty($slider[ $slick->get_name_setting('button_1_animation') ]) ? $slider[ $slick->get_name_setting('button_1_animation') ] . ' ' . ( $slider[ $slick->get_name_setting('button_1_animation_duration') ] ?? '' ) : '';
													$button_1_animation_delay  = ! empty($slider[ $slick->get_name_setting('button_1_animation_delay') ]) ? $slider[ $slick->get_name_setting('button_1_animation_delay') ] . 'ms"' : '';
													$button_1_visibility       = ! empty($button_1_animation) ? 'invisible' : 'visible';
													$button_1_custom_attribute = isset($slider[ $slick->get_name_setting('button_1_url') ]['custom_attributes']) ? RisingBambooElementorHelper::get_custom_attributes($slider[ $slick->get_name_setting('button_1_url') ]['custom_attributes']) : [];
													?>
													<a <?php echo ( 'url' !== $button_1_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-button-01-' . esc_attr($slider['_id']) . '\', event)"' : ''; ?>
															href="<?php echo esc_url($button_1_url); ?>"
															data-visibility-start="<?php echo esc_attr($button_1_visibility); ?>"
															<?php echo ! empty($button_1_animation) ? 'data-animation="' . esc_attr($button_1_animation) . '"' : ''; ?>
															<?php echo ! empty($button_1_animation_delay) ? 'data-animation-delay="' . esc_attr($button_1_animation_delay) . '"' : ''; ?>
															<?php echo ! empty($button_1_url_target) ? 'target="' . esc_attr($button_1_url_target) . '"' : ''; ?>
															<?php echo ! empty($button_1_url_nofollow) ? 'rel="' . esc_attr($button_1_url_nofollow) . '"' : ''; ?>
															class="rbb-slick-button duration-300 button-1 md:pl-9 pl-6 pr-2 py-2 rounded-full inline-block <?php echo esc_attr($button_1_visibility); ?>"
															<?php
															foreach ( $button_1_custom_attribute as $attr => $val ) {
																echo esc_attr($attr) . " = '" . esc_attr($val) . "' ";
															}
															?>
													>
														<span class="button-text inline-block align-middle"><?php echo wp_kses_post($button_1); ?></span>
														<span class="button-icon inline-block align-middle ml-4 w-10 h-10 leading-10 rounded-full">
														<?php
														if ( $button_1_icon ) {
															Icons_Manager::render_icon($button_1_icon, [ 'aria-hidden' => 'true' ]);
															?>
														<?php } else { ?>
															<i class="rbb-icon-direction-711 text-xl leading-10" aria-hidden="true"></i>
														<?php } ?>
														</span>
													</a>
													<?php
													if ( 'url' !== $button_1_type ) {
														?>
														<div id="rbb-modal-button-01-<?php echo esc_attr($slider['_id']); ?>" class="<?php echo esc_attr($class_string); ?>" data-modal-animation="<?php echo esc_attr($modal_effect); ?>">
															<div class="rbb-modal-dialog">
																<header class="rbb-modal-header">
																	<button class="rbb-close-modal"
																			aria-label="close modal">✕
																	</button>
																</header>
																<div class="rbb-modal-body">
																	<?php
																	if ( 'video' === $button_1_type ) {
																		$button_1_video_url = $slider[ $slick->get_name_setting('button_1_video') ]['url'];
																		?>
																		<video width="640" height="360" controls>
																			<source src="<?php echo esc_url($button_1_video_url); ?>">
																			<?php echo esc_html__('Your browser does not support the video tag.', 'botanica'); ?>
																		</video>
																		<?php
																	} elseif ( 'youtube' === $button_1_type ) {
																		$button_1_video_url = CoreHelper::get_youtube_embed($slider[ $slick->get_name_setting('button_1_youtube') ]);
																		?>
																		<iframe width="640" height="360"
																				src="<?php echo esc_url($button_1_video_url); ?>"
																				title="YouTube video player"
																				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
																				allowfullscreen></iframe>
																		<?php
																	} elseif ( 'vimeo' === $button_1_type ) {
																		$button_1_video_url = CoreHelper::get_vimeo_embed($slider[ $slick->get_name_setting('button_1_vimeo') ]);
																		?>
																		<iframe width="640" height="360"
																				src="<?php echo esc_url($button_1_video_url); ?>"
																				allow="autoplay; fullscreen; picture-in-picture"
																				allowfullscreen></iframe>
																		<?php
																	} elseif ( 'dailymotion' === $button_1_type ) {
																		$button_1_video_url = CoreHelper::get_dailymotion_embed($slider[ $slick->get_name_setting('button_1_dailymotion') ]);
																		?>
																		<iframe width="640" height="360"
																				type="text/html"
																				src="<?php echo esc_url($button_1_video_url); ?>"
																				allowfullscreen
																				allow="autoplay"></iframe>
																	<?php } ?>
																</div>
															</div>
														</div>
													<?php } ?>
												<?php } ?>
												<?php
												if ( $button_2 ) {
													$button_2_type             = $slider[ $slick->get_name_setting('button_2_type') ] ?? null;
													$button_2_url              = isset($slider[ $slick->get_name_setting('button_2_url') ]) ? $slider[ $slick->get_name_setting('button_2_url') ]['url'] : '#';
													$button_2_url_target       = isset($slider[ $slick->get_name_setting('button_2_url') ]['is_external']) && 'on' === $slider[ $slick->get_name_setting('button_2_url') ]['is_external'] ? '_blank' : '';
													$button_2_url_nofollow     = isset($slider[ $slick->get_name_setting('button_2_url') ]['nofollow']) && 'on' === $slider[ $slick->get_name_setting('button_2_url') ]['nofollow'] ? 'nofollow' : '';
													$button_2_icon             = ! empty($slider[ $slick->get_name_setting('button_2_icon') ]['value']) ? $slider[ $slick->get_name_setting('button_2_icon') ] : null;
													$button_2_animation        = ! empty($slider[ $slick->get_name_setting('button_2_animation') ]) ? $slider[ $slick->get_name_setting('button_2_animation') ] . ' ' . ( $slider[ $slick->get_name_setting('button_2_animation_duration') ] ?? '' ) : '';
													$button_2_animation_delay  = ! empty($slider[ $slick->get_name_setting('button_2_animation_delay') ]) ? $slider[ $slick->get_name_setting('button_2_animation_delay') ] . 'ms"' : '';
													$button_2_visibility       = ! empty($button_2_animation) ? 'invisible' : 'visible';
													$button_2_custom_attribute = isset($slider[ $slick->get_name_setting('button_2_url') ]['custom_attributes']) ? RisingBambooElementorHelper::get_custom_attributes($slider[ $slick->get_name_setting('button_2_url') ]['custom_attributes']) : [];
													?>
													<a <?php echo ( 'url' !== $button_2_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-button-02-' . esc_attr($slider['_id']) . '\', event)"' : ''; ?>
															href="<?php echo esc_url($button_2_url); ?>"
															data-visibility-start="<?php echo esc_attr($button_2_visibility); ?>"
															<?php echo ! empty($button_2_animation) ? 'data-animation="' . esc_attr($button_2_animation) . '"' : ''; ?>
															<?php echo ! empty($button_2_animation_delay) ? 'data-animation-delay="' . esc_attr($button_2_animation_delay) . '"' : ''; ?>
															<?php echo ! empty($button_2_url_target) ? 'target="' . esc_attr($button_2_url_target) . '"' : ''; ?>
															<?php echo ! empty($button_2_url_nofollow) ? 'rel="' . esc_attr($button_2_url_nofollow) . '"' : ''; ?>
															class="rbb-slick-button duration-300 button-2 md:ml-2 lg:ml-5 inline-block <?php echo esc_attr($button_2_visibility); ?>"
															<?php
															foreach ( $button_2_custom_attribute as $attr => $val ) {
																echo esc_attr($attr) . " = '" . esc_attr($val) . "' ";
															}
															?>
													>
													<span class="button-icon xl:w-14 xl:h-14 w-12 h-12 rounded-full text-center inline-block align-middle">
														<span class="w-full h-full bg-white xl:p-2 p-1 rounded-full inline-block">
															<?php
															if ( $button_2_icon ) {
																Icons_Manager::render_icon($button_2_icon, [ 'aria-hidden' => 'true' ]);
																?>
															<?php } else { ?>
																<i class="rbb-icon-direction-74 leading-6 text-[18px] inline-block"></i>
															<?php } ?>
														</span>
													</span>
														<span class="button-text inline-block md:ml-1 lg:ml-3 align-middle"><?php echo wp_kses_post($button_2); ?></span>
													</a>
													<?php
													if ( 'url' !== $button_2_type ) {
														?>
														<div id="rbb-modal-button-02-<?php echo esc_attr($slider['_id']); ?>" class="<?php echo esc_attr($class_string); ?>" data-modal-animation="<?php echo esc_attr($modal_effect); ?>">
															<div class="rbb-modal-dialog">
																<header class="rbb-modal-header">
																	<button class="rbb-close-modal" aria-label="close modal">✕</button>
																</header>
																<div class="rbb-modal-body">
																	<?php
																	if ( 'video' === $button_2_type ) {
																		$button_2_video_url = $slider[ $slick->get_name_setting('button_2_video') ]['url'];
																		?>
																		<video width="640" height="360" controls>
																			<source src="<?php echo esc_url($button_2_video_url); ?>">
																			<?php echo esc_html__('Your browser does not support the video tag.', 'botanica'); ?>
																		</video>
																		<?php
																	} elseif ( 'youtube' === $button_2_type ) {
																		$button_2_video_url = RisingBambooCore\Helper\Helper::get_youtube_embed($slider[ $slick->get_name_setting('button_2_youtube') ]);
																		?>
																		<iframe width="640" height="360"
																				src="<?php echo esc_url($button_2_video_url); ?>"
																				title="YouTube video player"
																				allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
																				allowfullscreen></iframe>
																		<?php
																	} elseif ( 'vimeo' === $button_2_type ) {
																		$button_2_video_url = RisingBambooCore\Helper\Helper::get_vimeo_embed($slider[ $slick->get_name_setting('button_2_vimeo') ]);
																		?>
																		<iframe width="640" height="360"
																				src="<?php echo esc_url($button_2_video_url); ?>"
																				allow="autoplay; fullscreen; picture-in-picture"
																				allowfullscreen></iframe>
																		<?php
																	} elseif ( 'dailymotion' === $button_2_type ) {
																		$button_2_video_url = RisingBambooCore\Helper\Helper::get_dailymotion_embed($slider[ $slick->get_name_setting('button_2_dailymotion') ]);
																		?>
																		<iframe width="640" height="360"
																				type="text/html"
																				src="<?php echo esc_url($button_2_video_url); ?>"
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
									</div>
								<?php } ?>
								<?php
								for ( $i = 1; $i <= $slick->images_per_slider; $i++ ) {
									$position = $slider[ $slick->get_name_setting('image_' . $i . '_position') ] ?? 'absolute';
									$index    = $slider[ $slick->get_name_setting('image_' . $i . '_z_index') ] ?? null;
									$z_index  = '';
									if ( null !== $index ) {
										$z_index = 'z-' . $index . '0';
									}
									$animation       = ! empty($slider[ $slick->get_name_setting('image_' . $i . '_animation') ]) ? $slider[ $slick->get_name_setting('image_' . $i . '_animation') ] . ' ' . ( $slider[ $slick->get_name_setting('image_' . $i . '_animation_duration') ] ?? '' ) : '';
									$animation_delay = ! empty($slider[ $slick->get_name_setting('image_' . $i . '_animation_delay') ]) ? $slider[ $slick->get_name_setting('image_' . $i . '_animation_delay') ] . 'ms' : '';
									$visibility      = ! empty($animation) ? 'invisible' : 'visible';
									if ( $slider[ $slick->get_name_setting('image_' . $i) ] && $slider[ $slick->get_name_setting('image_' . $i) ]['url'] ) {
										?>
										<img
												data-depth="0.<?php echo esc_attr($index); ?>"
												data-visibility-start="<?php echo esc_attr($visibility); ?>"
												<?php echo ! empty($animation) ? 'data-animation="' . esc_attr($animation) . '"' : ''; ?>
												<?php echo ! empty($animation_delay) ? 'data-animation-delay="' . esc_attr($animation_delay) . '"' : ''; ?>
												class="invisible <?php echo esc_attr(( 'absolute' === $position ) ? '!absolute' : '!relative'); ?>
												<?php
												if ( $parallax && 1 !== $i ) {
													echo 'layer'; }
												?>
												image_<?php echo esc_attr($i); ?> <?php echo esc_attr($z_index); ?> 
												"
												src="<?php echo esc_url($slider[ $slick->get_name_setting('image_' . $i) ]['url']); ?>"
												alt="<?php echo esc_attr__('Image Slider ', 'botanica') . esc_attr($i); ?>"
										>
										<?php
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
