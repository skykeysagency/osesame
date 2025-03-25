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

<div id="<?php echo esc_attr($id); ?>" class="banner-image relative overflow-hidden rounded-[20px] <?php echo esc_attr($layout); ?>">
	<div class="mx-auto text-center">
		<div class="md:grid relative overflow-hidden rounded-[20px]">
			<div class="absolute md:left-[60px] left-5 z-10 text-left md:top-[83px] top-10 px-5">
				<div>
					<div class="title_block">
						<p class="sub_title text-3xl mb-2"><?php echo wp_kses_post($sub_title); ?></p>
						<h4 class="title text-[40px]"><?php echo wp_kses_post($title); ?></h4>
					</div>
					<div class="desc text-sm text-[#434343] mt-4 leading-[23px]"><?php echo wp_kses_post($description); ?></div>
				</div>
				<?php
				if ( ! empty($countdown) && ( time() < strtotime($countdown) ) ) {
					?>
					<div class="banner-countdown -mx-[5px]">
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
				<div class="flex items-center mt-[34px]">
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
							class="inline-block btn h-12 leading-[48px] pl-6 pr-[7px] rounded-[48px] rbb-button <?php echo esc_attr($button_position); ?> <?php echo esc_attr($button_class); ?>"
							<?php
							foreach ( $button_custom_attribute as $attr => $val ) {
								echo esc_attr($attr) . " = '" . esc_attr($val) . "' ";
							}
							?>
						>
						<?php if ( $button_icon_status && 'before' === $button_icon_position ) { ?>
							<span class="button-icon mr-[16px] ms:ml-3 w-[35px] h-[35px] leading-[44px] rounded-full text-center bg-black/20 inline-block align-middle text-white">
								<?php
								if ( ! empty($button_icon['value']) ) {
									Icons_Manager::render_icon($button_icon, [ 'aria-hidden' => 'true' ]);
								} else {
									?>
									<i class="rbb-icon-direction-711 text-xl" aria-hidden="true"></i>
								<?php } ?>
							</span>
						<?php } ?>

						<span class="button-text inline-block align-middle"><?php echo wp_kses_post($button_name); ?></span>

						<?php if ( $button_icon_status && 'after' === $button_icon_position ) { ?>
							<span class="button-icon ml-[16px] ms:ml-3 w-[35px] h-[35px] leading-[44px] rounded-full text-center bg-black/20 inline-block align-middle text-white">
								<?php
								if ( ! empty($button_icon['value']) ) {
									Icons_Manager::render_icon($button_icon, [ 'aria-hidden' => 'true' ]);
								} else {
									?>
									<i class="rbb-icon-direction-711 text-xl" aria-hidden="true"></i>
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
			</div>
		</div>
		<div class="col-span-12 lg:col-span-12">
			<div class="relative text-center">
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
					<div class="banner-img block">
						<?php
						if ( $image_link_attr_string ) {
							?>
						<a <?php echo wp_kses($image_link_attr_string, 'rbb-kses'); ?> >
						<?php } ?>
						<img alt="Image" src="<?php echo esc_url($image[ $widget->get_name_setting('image') ]['url']); ?>" class="scale-100 hover:scale-105 duration-300 w-full <?php echo esc_attr(( $parallax ) ? 'layer' : ''); ?> inline-block <?php echo esc_attr($css_class); ?> <?php echo esc_attr($position); ?> <?php echo esc_attr($repeater_image_class); ?>"
						<?php if ( $parallax ) { ?>
							data-depth="0.<?php echo esc_attr($z_index); ?>"
						<?php } ?>
						>
						<?php if ( $image_link_attr_string ) { ?>
						</a>
					<?php } ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
