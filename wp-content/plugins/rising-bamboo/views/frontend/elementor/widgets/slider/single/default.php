<?php
/**
 * RisingBambooTheme package
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;
use Elementor\Icons_Manager;
use RisingBambooCore\Helper\Helper as CoreHelper;
use RisingBambooCore\Helper\Elementor as RisingBambooElementorHelper;
use RisingBambooTheme\Helper\Setting;

if ( $sliders ) {
	?>
	<div class="rbb-elementor-slider <?php echo esc_attr($layout_type); ?> <?php echo esc_attr($layout); ?>">
		<div class="rbb-slick-carousel slick-carousel slick-carousel-center" data-slick='{
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
				<div class="item elementor-repeater-item-<?php echo esc_attr($slider['_id']); ?>">
					<div class="item-content relative">
						<?php
						$countdown          = $slider[ $slick->get_name_setting('countdown_due_date') ];
						$countdown_position = $slider[ $slick->get_name_setting('countdown_position') ] ?? 'absolute';
						if ( ! empty($countdown) && ( time() < strtotime($countdown) ) ) {
							?>
							<div class="max-w-[270px] absolute left-1/2 -translate-x-1/2 -top-[30px] z-10">
								<div class="rbb-countdown countdown-banner flex justify-center <?php echo esc_attr($countdown_position); ?>" data-countdown-date="<?php echo esc_attr($countdown); ?>">
									<div class="item-time days"><span class="data-number">%D</span><span class="name-time"><?php echo esc_html__('Day%!H', App::get_domain()); ?></span></div>
									<div class="item-time"><span class="data-number">%H</span><span class="name-time"><?php echo esc_html__('Hour%!H', App::get_domain()); ?></span></div>
									<div class="item-time"><span class="data-number">%M</span><span class="name-time"><?php echo esc_html__('Min%!H', App::get_domain()); ?></span></div>
									<div class="item-time"><span class="data-number">%S</span><span class="name-time"><?php echo esc_html__('Sec', App::get_domain()); ?></span></div>
								</div>
							</div>
							<?php
						}
						?>
						<div class="md:absolute md:my-0 my-7 top-0 w-full h-full">
							<div class="container <?php echo ( $parallax ) ? 'parallax' : ''; ?> m-auto relative h-full">
								<?php
								if ( $slider_title || $slider_sub_title || $description || $button_1 || $button_2 ) {
									$horizontal_align = $slider[ $slick->get_name_setting('horizontal_align') ] ?? 'start';
									switch ( $horizontal_align ) {
										case 'end':
											$horizontal_align_class = 'right-0';
											break;
										case 'center':
											$horizontal_align_class = 'left-1/2 -translate-x-1/2';
											break;
										default:
											$horizontal_align_class = 'left-0';
									}

									$vertical_align = $slider[ $slick->get_name_setting('vertical_align') ] ?? 'top';
									switch ( $vertical_align ) {
										case 'bottom':
											$vertical_align_class = 'bottom-0';
											break;
										case 'middle':
											$vertical_align_class = 'md:top-1/2 transform md:-translate-y-1/2';
											break;
										default:
											$vertical_align_class = 'top-0';
									}
									?>
									<div class="md:absolute info-wap lg:w-[42%] md:w-1/2 z-50 <?php echo esc_attr($horizontal_align_class); ?> <?php echo esc_attr($vertical_align_class); ?>">
										<div class="info-inner md:pl-[15px]">
											<?php
											if ( $slider_sub_title ) {
												$subtitle_animation       = isset($slider[ $slick->get_name_setting('subtitle_animation') ]) && ! empty($slider[ $slick->get_name_setting('subtitle_animation') ]) ? 'data-animation="' . $slider[ $slick->get_name_setting('subtitle_animation') ] . ' ' . $slider[ $slick->get_name_setting('subtitle_animation_duration') ] . '"' : '';
												$subtitle_animation_delay = isset($slider[ $slick->get_name_setting('subtitle_animation_delay') ]) && ! empty($slider[ $slick->get_name_setting('subtitle_animation_delay') ]) ? 'data-animation-delay="' . $slider[ $slick->get_name_setting('subtitle_animation_delay') ] . 'ms"' : '';
												$subtitle_visibility      = ! empty($subtitle_animation) ? 'invisible' : 'visible';
												?>
												<h3 data-visibility-start="<?php echo esc_attr($subtitle_visibility); ?>" <?php echo $subtitle_animation; // phpcs:ignore ?> <?php echo $subtitle_animation_delay; // phpcs:ignore ?>
													class="sub-title sm:text-base md:text-xl md:mb-2 lg:text-4xl lg:mb-6 <?php echo esc_attr($subtitle_visibility); ?>"><?php echo wp_kses_post($slider_sub_title); ?></h3>
											<?php } ?>
											<?php
											if ( $slider_title ) {
												$title_animation       = isset($slider[ $slick->get_name_setting('title_animation') ]) && ! empty($slider[ $slick->get_name_setting('title_animation') ]) ? 'data-animation="' . $slider[ $slick->get_name_setting('title_animation') ] . ' ' . $slider[ $slick->get_name_setting('title_animation_duration') ] . '"' : '';
												$title_animation_delay = isset($slider[ $slick->get_name_setting('title_animation_delay') ]) && ! empty($slider[ $slick->get_name_setting('title_animation_delay') ]) ? 'data-animation-delay="' . $slider[ $slick->get_name_setting('title_animation_delay') ] . 'ms"' : '';
												$title_visibility      = ! empty($title_animation) ? 'invisible' : 'visible';
												?>
												<h2 data-visibility-start="<?php echo esc_attr($title_visibility); ?>" <?php echo $title_animation; // phpcs:ignore ?> <?php echo $title_animation_delay; // phpcs:ignore ?>
													class="title sm:text-base md:text-xl md:mb-1 lg:text-4xl lg:mb-4 <?php echo esc_attr($title_visibility); ?>"><?php echo wp_kses_post($slider_title); ?></h2>
											<?php } ?>
											<?php
											if ( $description ) {
												$description_animation       = isset($slider[ $slick->get_name_setting('description_animation') ]) && ! empty($slider[ $slick->get_name_setting('description_animation') ]) ? 'data-animation="' . $slider[ $slick->get_name_setting('description_animation') ] . ' ' . $slider[ $slick->get_name_setting('description_animation_duration') ] . '"' : '';
												$description_animation_delay = isset($slider[ $slick->get_name_setting('description_animation_delay') ]) && ! empty($slider[ $slick->get_name_setting('description_animation_delay') ]) ? 'data-animation-delay="' . $slider[ $slick->get_name_setting('description_animation_delay') ] . 'ms"' : '';
												$description_visibility      = ! empty($description_animation) ? 'invisible' : 'visible';
												?>
												<div data-visibility-start="<?php echo esc_attr($description_visibility); ?>" <?php echo $description_animation; // phpcs:ignore ?> <?php echo $description_animation_delay; // phpcs:ignore ?>class="description truncate md:mb-4 lg:whitespace-normal lg:mb-6 <?php echo esc_attr($description_visibility); ?>"><?php echo wp_kses_post($description); ?></div>
											<?php } ?>
											<div class="button-group flex items-center">
												<?php
												if ( $button_1 ) {
													$button_1_type             = $slider[ $slick->get_name_setting('button_1_type') ] ?? null;
													$button_1_url              = isset($slider[ $slick->get_name_setting('button_1_url') ]) ? $slider[ $slick->get_name_setting('button_1_url') ]['url'] : '#';
													$button_1_url_target       = isset($slider[ $slick->get_name_setting('button_1_url') ]['is_external']) && 'on' === $slider[ $slick->get_name_setting('button_1_url') ]['is_external'] ? 'target="_blank"' : '';
													$button_1_url_nofollow     = isset($slider[ $slick->get_name_setting('button_1_url') ]['nofollow']) && 'on' === $slider[ $slick->get_name_setting('button_1_url') ]['nofollow'] ? 'rel="nofollow"' : '';
													$button_1_icon             = ! empty($slider[ $slick->get_name_setting('button_1_icon') ]['value']) ? $slider[ $slick->get_name_setting('button_1_icon') ] : null;
													$button_1_animation        = isset($slider[ $slick->get_name_setting('button_1_animation') ]) && ! empty($slider[ $slick->get_name_setting('button_1_animation') ]) ? 'data-animation="' . $slider[ $slick->get_name_setting('button_1_animation') ] . ' ' . $slider[ $slick->get_name_setting('button_1_animation_duration') ] . '"' : '';
													$button_1_animation_delay  = isset($slider[ $slick->get_name_setting('button_1_animation_delay') ]) && ! empty($slider[ $slick->get_name_setting('button_1_animation_delay') ]) ? 'data-animation-delay="' . $slider[ $slick->get_name_setting('button_1_animation_delay') ] . 'ms"' : '';
													$button_1_visibility       = ! empty($button_1_animation) ? 'invisible' : 'visible';
													$button_1_custom_attribute = isset($slider[ $slick->get_name_setting('button_1_url') ]['custom_attributes']) ? RisingBambooElementorHelper::get_custom_attributes($slider[ $slick->get_name_setting('button_1_url') ]['custom_attributes']) : [];
													?>
													<a <?php echo ( 'url' !== $button_1_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-button-01-' . esc_attr($slider['_id']) . '\', event)"' : ''; ?>
                                                        data-visibility-start="<?php echo esc_attr($button_1_visibility); ?>" <?php echo $button_1_animation; // phpcs:ignore ?> <?php echo $button_1_animation_delay; // phpcs:ignore ?>
                                                        href="<?php echo esc_url($button_1_url); ?>" <?php echo $button_1_url_target; // phpcs:ignore ?> <?php echo $button_1_url_nofollow; // phpcs:ignore ?>
														class="rbb-slick-button duration-300 button-1 pl-9 pr-2 md:py-2 rounded-full inline-block <?php echo esc_attr($button_1_visibility); ?>"
														<?php
														foreach ( $button_1_custom_attribute as $attr => $val ) {
															// phpcs:ignore
                                                            echo $attr . " = '" . $val . "' ";
														}
														?>
													>
														<span class="button-text inline-block align-middle font-semibold md:text-sm lg:text-base"><?php echo wp_kses_post($button_1); ?></span>
														<span class="button-icon inline-block align-middle xl:ml-4 ml-2 xl:w-10 xl:h-10 xl:leading-10 md:w-8 md:h-8 md:leading-8 w-6 h-6 leading-6 rounded-full">
														<?php
														if ( $button_1_icon ) {
															Icons_Manager::render_icon($button_1_icon, [ 'aria-hidden' => 'true' ]);
															?>
														<?php } else { ?>
															<i class="rbb-icon-direction-711 text-xl xl:leading-10 md:leading-8 leading-[22px]" aria-hidden="true"></i>
														<?php } ?>
														</span>
													</a>
													<?php
													if ( 'url' !== $button_1_type ) {
														?>
														<div id="rbb-modal-button-01-<?php echo esc_attr($slider['_id']); ?>" class="rbb-modal" data-modal-animation="<?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT)); ?>">
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
																			<?php echo esc_html__('Your browser does not support the video tag.', App::get_domain()); ?>
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
													$button_2_url_target       = isset($slider[ $slick->get_name_setting('button_2_url') ]['is_external']) && 'on' === $slider[ $slick->get_name_setting('button_2_url') ]['is_external'] ? 'target="_blank"' : '';
													$button_2_url_nofollow     = isset($slider[ $slick->get_name_setting('button_2_url') ]['nofollow']) && 'on' === $slider[ $slick->get_name_setting('button_2_url') ]['nofollow'] ? 'rel="nofollow"' : '';
													$button_2_icon             = ! empty($slider[ $slick->get_name_setting('button_2_icon') ]['value']) ? $slider[ $slick->get_name_setting('button_2_icon') ] : null;
													$button_2_animation        = isset($slider[ $slick->get_name_setting('button_2_animation') ]) && ! empty($slider[ $slick->get_name_setting('button_2_animation') ]) ? 'data-animation="' . $slider[ $slick->get_name_setting('button_2_animation') ] . ' ' . $slider[ $slick->get_name_setting('button_2_animation_duration') ] . '"' : '';
													$button_2_animation_delay  = isset($slider[ $slick->get_name_setting('button_2_animation_delay') ]) && ! empty($slider[ $slick->get_name_setting('button_2_animation_delay') ]) ? 'data-animation-delay="' . $slider[ $slick->get_name_setting('button_2_animation_delay') ] . 'ms"' : '';
													$button_2_visibility       = ! empty($button_2_animation) ? 'invisible' : 'visible';
													$button_2_custom_attribute = isset($slider[ $slick->get_name_setting('button_2_url') ]['custom_attributes']) ? RisingBambooElementorHelper::get_custom_attributes($slider[ $slick->get_name_setting('button_2_url') ]['custom_attributes']) : [];
													?>
													<a <?php echo ( 'url' !== $button_2_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-button-02-' . esc_attr($slider['_id']) . '\', event)"' : ''; ?>
															data-visibility-start="<?php echo esc_attr($button_2_visibility); ?>" <?php echo $button_2_animation; // phpcs:ignore ?> <?php echo $button_2_animation_delay; // phpcs:ignore ?>
															href="<?php echo esc_url($button_2_url); ?>" <?php echo $button_2_url_target; // phpcs:ignore ?> <?php echo $button_2_url_nofollow; // phpcs:ignore ?>
															class="rbb-slick-button duration-300 button-2 md:ml-2 lg:ml-5 inline-block <?php echo esc_attr($button_2_visibility); ?>"
														<?php
														foreach ( $button_2_custom_attribute as $attr => $val ) {
															// phpcs:ignore
                                                            echo $attr . " = '" . $val . "' ";
														}
														?>
													>
													<span class="button-icon manh xl:w-14 xl:h-14 w-10 h-10 rounded-full text-center inline-block align-middle">
														<span class="w-full h-full bg-white xl:p-2 rounded-full inline-block">
															<?php
															if ( $button_2_icon ) {
																Icons_Manager::render_icon($button_2_icon, [ 'aria-hidden' => 'true' ]);
																?>
															<?php } else { ?>
																<i class="rbb-icon-direction-74 leading-6 text-[18px] inline-block"></i>
															<?php } ?>
														</span>
													</span>
														<span class="button-text inline-block md:ml-1 lg:ml-3 font-bold align-middle"><?php echo wp_kses_post($button_2); ?></span>
													</a>
													<?php
													if ( 'url' !== $button_2_type ) {
														?>
														<div id="rbb-modal-button-02-<?php echo esc_attr($slider['_id']); ?>" class="rbb-modal" data-modal-animation="<?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT)); ?>">
															<div class="rbb-modal-dialog">
																<header class="rbb-modal-header">
																	<button class="rbb-close-modal"
																			aria-label="close modal">✕
																	</button>
																</header>
																<div class="rbb-modal-body">
																	<?php
																	if ( 'video' === $button_2_type ) {
																		$button_2_video_url = $slider[ $slick->get_name_setting('button_2_video') ]['url'];
																		?>
																		<video width="640" height="360" controls>
																			<source src="<?php echo esc_url($button_2_video_url); ?>">
																			<?php echo esc_html__('Your browser does not support the video tag.', App::get_domain()); ?>
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
									$animation       = isset($slider[ $slick->get_name_setting('image_' . $i . '_animation') ]) && ! empty($slider[ $slick->get_name_setting('image_' . $i . '_animation') ]) ? 'data-animation="' . $slider[ $slick->get_name_setting('image_' . $i . '_animation') ] . ' ' . $slider[ $slick->get_name_setting('image_' . $i . '_animation_duration') ] . '"' : '';
									$animation_delay = isset($slider[ $slick->get_name_setting('image_' . $i . '_animation_delay') ]) && ! empty($slider[ $slick->get_name_setting('image_' . $i . '_animation_delay') ]) ? 'data-animation-delay="' . $slider[ $slick->get_name_setting('image_' . $i . '_animation_delay') ] . 'ms"' : '';
									$visibility      = ! empty($animation) ? 'invisible' : 'visible';
									if ( $slider[ $slick->get_name_setting('image_' . $i) ] && $slider[ $slick->get_name_setting('image_' . $i) ]['url'] ) {
										?>
										<img data-depth="0.<?= esc_attr($index) ?>" data-visibility-start="<?php echo esc_attr($visibility); ?>" <?php echo $animation; // phpcs:ignore ?> <?php echo $animation_delay; // phpcs:ignore ?>class=" img1 <?php echo ($position === 'absolute') ? '!absolute':'!relative'; ?> <?php if($parallax && 1 === $i) { echo 'img-banner1'; }?> <?php if($parallax && 1 !== $i ) { echo 'layer'; } ?> image_<?php echo esc_attr($i); ?> <?php echo esc_attr($z_index); ?> <?php echo esc_attr($visibility); ?>" src="<?php echo esc_url($slider[ $slick->get_name_setting('image_' . $i) ]['url']); ?>" alt="<?php echo esc_attr__('Image Slider ', App::get_domain()). $i; ?>">
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
