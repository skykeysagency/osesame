<?php
/**
 * RisingBambooTheme Package
 *
 * @package @RisingBambooCore
 */

use RisingBambooTheme\App\App;
use Elementor\Icons_Manager;
use RisingBambooCore\Helper\Elementor as RisingBambooElementorHelper;
use RisingBambooTheme\Helper\Setting;
$modal_effect    = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT);
$outside         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE);
$backdrop_filter = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER);
$classes         = [ 'rbb-modal' ];
$classes[]       = ( true === $backdrop_filter ) ? 'backdrop' : 'backdrop-none';
$classes[]       = ( false === $outside ) ? 'outside-modal' : '';
$class_string    = implode(' ', array_filter($classes));
?>

<div id="<?php echo esc_attr($id); ?>" class="special-offer py-[59px] xl:min-h-[640px] min-h-[540px] relative overflow-hidden <?php echo esc_attr($layout); ?>">
	<div class="bg absolute top-0 -right-[7vw] skew-x-[22deg] w-1/2 h-full hidden lg:block bg-[#ffd074] rounded-l-[50px] after:content-[''] after:absolute after:top-0 after:right-[calc(-7vw + 3px)] after:w-[7vw] after:h-full after:w-full after:bg-[#ffd074]"></div>
	<div class="container mx-auto">
		<div class="grid grid-cols-12 gap-4 py-[15px]">
			<div class="col-span-12 lg:col-span-5 xl:mt-28 xl:mb-16 lg:mb-0 relative">
				<div class="lg:text-left text-center">
					<div class="title_block">
						<p class="sub_title"><?php echo wp_kses_post($sub_title); ?></p>
						<?php
						if ( ! empty($countdown) && ( time() < strtotime($countdown) ) ) {
							?>
							<div class="pb-5 banner-countdown -mx-[5px]">
								<div class="rbb-countdown flex justify-self-start <?php echo esc_attr($countdown_position); ?>" data-countdown-date="<?php echo esc_attr($countdown); ?>">
									<div class="item-time days"><span class="data-number">%D</span><span class="name-time"><?php echo esc_html__('Day%!H', 'botanica'); ?></span></div>
									<div class="item-time"><span class="data-number">%H</span><span class="name-time"><?php echo esc_html__('Hour%!H', 'botanica'); ?></span></div>
									<div class="item-time"><span class="data-number">%M</span><span class="name-time"><?php echo esc_html__('Min%!H', 'botanica'); ?></span></div>
									<div class="item-time"><span class="data-number">%S</span><span class="name-time"><?php echo esc_html__('Sec', 'botanica'); ?></span></div>
								</div>
							</div>
							<?php
						}
						?>
						<h4 class="title"><?php echo wp_kses_post($title); ?></h4>
					</div>
					<div class="desc mt-4 mb-0"><?php echo wp_kses_post($description); ?></div>
				</div>
				<div class="flex items-center lg:justify-start justify-center xl:mt-20 mt-10 ">
				<?php
				foreach ( $buttons as $button ) {
					$button_class            = 'elementor-repeater-item-' . $button['_id'];
					$button_position         = $button[ $widget->get_name_setting('button_position') ] ?? 'relative';
					$button_icon_status      = $button[ $widget->get_name_setting('button_icon_status') ];
					$button_icon             = $button[ $widget->get_name_setting('button_icon') ];
					$button_icon_position    = $button[ $widget->get_name_setting('button_icon_position') ] ?? 'after';
					$button_name             = $button[ $widget->get_name_setting('button') ];
					$button_type             = $button[ $widget->get_name_setting('button_type') ] ?? null;
					$button_url              = isset($button[ $widget->get_name_setting('button_url') ]) ? $button[ $widget->get_name_setting('button_url') ]['url'] : '#';
					$button_url_target       = isset($button[ $widget->get_name_setting('button_url') ]['is_external']) && 'on' === $button[ $widget->get_name_setting('button_url') ]['is_external'] ? '_blank' : '';
					$button_url_nofollow     = isset($button[ $widget->get_name_setting('button_url') ]['nofollow']) && 'on' === $button[ $widget->get_name_setting('button_url') ]['nofollow'] ? 'nofollow' : '';
					$button_custom_attribute = isset($button[ $widget->get_name_setting('button_url') ]['custom_attributes']) ? RisingBambooElementorHelper::get_custom_attributes($button[ $widget->get_name_setting('button_url') ]['custom_attributes']) : [];
					?>
					<a <?php echo ( 'url' !== $button_type ) ? 'onclick="RisingBambooModal.modal(\'#rbb-modal-button-' . esc_attr($button['_id']) . '\', event)"' : ''; ?>
							href="<?php echo esc_url($button_url); ?>"
						<?php echo ! empty($button_url_target) ? 'target="' . esc_attr($button_url_target) . '"' : ''; ?>
						<?php echo ! empty($button_url_nofollow) ? 'rel="' . esc_attr($button_url_nofollow) . '"' : ''; ?>
							class="inline-block btn h-14 leading-[56px] pl-8 pr-[7px] rounded-[56px] rbb-button <?php echo esc_attr($button_position); ?> <?php echo esc_attr($button_class); ?>"
						<?php
						foreach ( $button_custom_attribute as $attr => $val ) {
							echo esc_attr($attr) . " = '" . esc_attr($val) . "' ";
						}
						?>
					>
						<?php if ( $button_icon_status && 'before' === $button_icon_position ) { ?>
						<span class="button-icon md:mr-6 mr-3 w-[42px] h-[42px] leading-[42px] rounded-full text-center bg-black/20 inline-block align-middle">
							<?php
							if ( ! empty($button_icon['value']) ) {
								Icons_Manager::render_icon($button_icon, [ 'aria-hidden' => 'true' ]);
							} else {
								?>
								<i class="rbb-icon-direction-711 text-xl leading-10" aria-hidden="true"></i>
							<?php } ?>
						</span>
						<?php } ?>

						<span class="button-text inline-block align-middle"><?php echo wp_kses_post($button_name); ?></span>

						<?php if ( $button_icon_status && 'after' === $button_icon_position ) { ?>
						<span class="button-icon md:ml-6 ml-3 w-[42px] h-[42px] leading-[42px] rounded-full text-center bg-black/20 inline-block align-middle">
							<?php
							if ( ! empty($button_icon['value']) ) {
								Icons_Manager::render_icon($button_icon, [ 'aria-hidden' => 'true' ]);
							} else {
								?>
								<i class="rbb-icon-direction-711 text-xl leading-10" aria-hidden="true"></i>
							<?php } ?>
						</span>
						<?php } ?>
					</a>
					<?php
					if ( 'url' !== $button_type ) {
						?>
						<div id="rbb-modal-button-<?php echo esc_attr($button['_id']); ?>" class="<?php echo esc_attr($class_string); ?>" data-modal-animation="<?php echo esc_attr($modal_effect); ?>">
							<div class="rbb-modal-dialog">
								<header class="rbb-modal-header">
									<button class="rbb-close-modal"
											aria-label="close modal">✕
									</button>
								</header>
								<div class="rbb-modal-body">
									<?php
									if ( 'video' === $button_type ) {
										$button_video_url = $button[ $widget->get_name_setting('button_video') ]['url'];
										?>
										<video width="640" height="360" controls>
											<source src="<?php echo esc_url($button_video_url); ?>">
											<?php echo esc_html__('Your browser does not support the video tag.', 'botanica'); ?>
										</video>
										<?php
									} elseif ( 'youtube' === $button_type ) {
										$button_video_url = RisingBambooCore\Helper\Helper::get_youtube_embed($button[ $widget->get_name_setting('button_youtube') ]);
										?>
										<iframe width="640" height="360"
												src="<?php echo esc_url($button_video_url); ?>"
												title="YouTube video player"
												allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
												allowfullscreen></iframe>
										<?php
									} elseif ( 'vimeo' === $button_type ) {
										$button_video_url = RisingBambooCore\Helper\Helper::get_vimeo_embed($button[ $widget->get_name_setting('button_vimeo') ]);
										?>
										<iframe width="640" height="360"
												src="<?php echo esc_url($button_video_url); ?>"
												allow="autoplay; fullscreen; picture-in-picture"
												allowfullscreen></iframe>
										<?php
									} elseif ( 'dailymotion' === $button_type ) {
										$button_video_url = RisingBambooCore\Helper\Helper::get_dailymotion_embed($button[ $widget->get_name_setting('button_dailymotion') ]);
										?>
										<iframe width="640" height="360"
												type="text/html"
												src="<?php echo esc_url($button_video_url); ?>"
												allowfullscreen
												allow="autoplay"></iframe>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
				<?php
				foreach ( $custom_html as $html ) {
					$repeater_custom_html_class = 'elementor-repeater-item-' . $html['_id'];
					$position                   = $html[ $widget->get_name_setting('custom_html_position') ];
					echo "<div class='custom-html " . esc_attr($repeater_custom_html_class) . ' ' . esc_attr($position) . "'>" . wp_kses_post($html[ $widget->get_name_setting('custom_html') ]) . '</div>';
				}
				?>
			</div>
			</div>
			<div class="col-span-12 lg:col-span-7 lg:mt-0 mt-5">
				<div class="<?php echo esc_attr(( $parallax ) ? 'parallax' : ''); ?> relative text-center">
					<?php
					foreach ( $images as $image ) {
						$position               = $image[ $widget->get_name_setting('image_position') ];
						$css_class              = $image[ $widget->get_name_setting('image_css_class') ];
						$z_index                = $image[ $widget->get_name_setting('image_index') ];
						$repeater_image_class   = 'elementor-repeater-item-' . $image['_id'];
						$image_link             = $image[ $widget->get_name_setting('image_link') ];
						$image_link_attr_string = false;
						if ( ! empty($image_link['url']) ) {
							$image_link_key = 'image_link_for_' . $image['_id'];
							$widget->add_link_attributes($image_link_key, $image_link);
							$image_link_attr_string = $widget->get_render_attribute_string($image_link_key);
						}
						?>
						<?php
						if ( $image_link_attr_string ) {
							?>
							<a <?php echo wp_kses($image_link_attr_string, 'rbb-kses'); ?>>
							<?php } ?>
							<img alt="Image" src="<?php echo esc_url($image[ $widget->get_name_setting('image') ]['url']); ?>" class="<?php echo esc_attr(( $parallax ) ? 'layer' : ''); ?> inline-block <?php echo esc_attr($css_class); ?> <?php echo esc_attr($position); ?> <?php echo esc_attr($repeater_image_class); ?>"
							<?php if ( $parallax ) { ?>
								data-depth="0.<?php echo esc_attr($z_index); ?>"
							<?php } ?>
							>
						<?php if ( $image_link_attr_string ) { ?>
							</a>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
