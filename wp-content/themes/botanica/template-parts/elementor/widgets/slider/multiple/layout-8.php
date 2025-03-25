<?php
/**
 * Default template of Multiple Layout
 *
 * @package RisingBambooCore
 */

use Elementor\Icons_Manager;
use RisingBambooCore\Helper\Helper;
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
		<?php if ( ! empty($surrounding_animation_image_01['url']) ) { ?>
			<div class="bg-left hidden wow fadeInLeft absolute top-[140px] left-[50px] z-10" data-wow-delay="0.4s">
				<img src="<?php echo esc_url($surrounding_animation_image_01['url']); ?>" alt="bg left" >
			</div>
		<?php } ?>
		<?php if ( ! empty($surrounding_animation_image_02['url']) ) { ?>
			<div class="bg-right hidden wow fadeInRight absolute top-[10px] right-[60px] z-10" data-wow-delay="0.4s">
				<img src="<?php echo esc_url($surrounding_animation_image_02['url']); ?>" alt="bg right" >
			</div>
		<?php } ?>
		<div class="max-w-[1320px] xl:px-5 mx-auto ">
			<div class="block_title text-center flex justify-center items-center">
				<?php if ( $widget_title || $widget_sub_title ) { ?>
					<div class="title_block inline-block">
						<?php
						if ( $widget_sub_title ) {
							?>
							<p class="sub-title text-2xl pb-3 mb-0"><?php echo esc_html($widget_sub_title); ?></p> <?php } ?>
							<?php
							if ( $widget_title ) {
								?>
								<h2 class="main-title"><?php echo esc_html($widget_title); ?></h2> <?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="block_slide">
						<div class="rbb-slick-carousel slick-carousel slick-carousel-center load-item-<?php echo esc_attr($sliders_to_show_default); ?>" data-slick='{
							"arrows": <?php echo esc_attr($show_arrows); ?>,
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
									$i++;
								}
								?>
								]
							<?php } ?>
						}'>
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
							<div class="item elementor-repeater-item-<?php echo esc_attr($slider['_id']); ?> p-2.5">
								<div class="block__content flex items-center relative duration-300 bg-white text-center rounded-[5px] md:p-5 px-5 py-3 md:min-h-[90px] min-h-[65px] shadow-[6px_6px_6px_1px_rgba(0,0,0,0.08)]">
									<?php if ( $slider[ $slick->get_name_setting('multiple_content_image') ]['url'] ) { ?>
										<img class="!max-w-10 w-10 !inline-block" src="<?php echo esc_url($slider[ $slick->get_name_setting('multiple_content_image') ]['url']); ?>" alt="<?php echo esc_attr($slider[ $slick->get_name_setting('multiple_content_title') ]); ?>">
										<?php
									}
									?>
									<div class="pl-4 text-left flex justify-between items-center w-full">
									<div class="inline-grid items-center">
										<h4 class="text-sm font-semibold">
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
										<?php if ( isset($slider[ $slick->get_name_setting('multiple_content_description') ]) && ! empty($slider[ $slick->get_name_setting('multiple_content_description') ]) ) : ?>
											<p class="pt-2.5 mb-0"><?php echo esc_html($slider[ $slick->get_name_setting('multiple_content_description') ]); ?></p>
										<?php endif; ?>
									</div>
									<?php
									if ( $button ) {
										$button_url_target   = isset($slider[ $slick->get_name_setting('multiple_content_button_url') ]['is_external']) && 'on' === $slider[ $slick->get_name_setting('multiple_content_button_url') ]['is_external'] ? 'target="_blank"' : '';
										$button_url_nofollow = isset($slider[ $slick->get_name_setting('multiple_content_button_url') ]['nofollow']) && 'on' === $slider[ $slick->get_name_setting('multiple_content_button_url') ]['nofollow'] ? 'rel="nofollow"' : '';
										?>
										<a <?php echo ( 'url' !== $button_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-multiple-button\', event)"' : ''; ?>
										href="<?php echo esc_url($button_url); ?>" <?php echo esc_attr($button_url_target); ?> <?php echo esc_attr($button_url_nofollow); ?>
										class="rbb-slick-button ml-4 w-10 h-10 btn_view-all opacity-0 duration-300 rounded-full inline-flex justify-center items-center">
										<span class="rounded-full w-10 h-10 text-lg text-white inline-flex justify-center items-center bg-[color:var(--rbb-general-secondary-color)]">
											<?php
											if ( $button_icon ) {
												Icons_Manager::render_icon($button_icon, [ 'aria-hidden' => 'true' ]);
												?>
											<?php } else { ?>
												<i class="rbb-icon-direction-711"></i>
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
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
