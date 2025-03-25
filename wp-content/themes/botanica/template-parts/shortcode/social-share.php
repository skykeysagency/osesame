<?php
/**
 * Social share shortcode template.
 *
 * @package RisingBambooCore
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;
$outside         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE);
$modal_effect    = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT);
$backdrop_filter = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER);
$classes         = [ 'rbb-social-share', 'hidden' ];
$classes[]       = ( true === $backdrop_filter ) ? 'backdrop' : 'backdrop-none';
$classes[]       = ( false === $outside ) ? 'outside-modal' : '';
$class_string    = implode(' ', array_filter($classes));
if ( $facebook || $twitter || $linkedin || $pinterest || $tumblr || $email ) {
	$html_link = '';
	if ( $facebook ) {
		$html_link .= '<a href="http://www.facebook.com/sharer.php?u=' . esc_url($permalink) . '&i=' . esc_url($image) . '" title="' . esc_attr__('Facebook', 'botanica') . '" class="share-facebook pl-5 " target="_blank"><i class="rbb-icon-social-facebook-5"></i></a>';
	}
	if ( $twitter ) {
		$html_link .= '<a href="https://twitter.com/intent/tweet?url=' . esc_url($permalink) . '"  title="' . esc_attr__('Twitter', 'botanica') . '" class="share-twitter pl-5"><i class="rbb-icon-social-twitter-5"></i></a>';
	}
	if ( $linkedin ) {
		$html_link .= '<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . esc_url($permalink) . '"  title="' . esc_attr__('LinkedIn', 'botanica') . '" class="share-linkedin pl-5"><i class="rbb-icon-linkedin-01"></i></a>';
	}
	if ( $pinterest ) {
		$html_link .= '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url($permalink) . '&amp;media=' . esc_url($image) . '"  title="' . esc_attr__('Pinterest', 'botanica') . '" class="share-pinterest pl-5"><i class="rbb-icon-social-pinterest-2"></i></a>';
	}
	if ( $tumblr ) {
		$html_link .= '<a href="https://tumblr.com/share/link?url=' . esc_url($permalink) . '"  title="' . esc_attr__('Tumblr', 'botanica') . '" class="share-tumblr pl-5"><i class="rbb-icon-social-tumblr-1"></i></a>';
	}
	if ( $email ) {
		$html_link .= '<a href="mailto:?subject=' . $title . '&body=' . esc_url($permalink) . '"  title="' . esc_attr__('Email', 'botanica') . '" class="share-email pl-5"><i class="rbb-icon-email-4"></i></a>';
	}
	if ( 'enable' === $config['popup'] ) {
		?>
		<div class="rbb-social text-xs"><a class="flex items-center" onclick="RisingBambooModal.modal('.rbb-social-share', event)" href="#"><i
						class="rbb-icon-social-share-3 pr-[14px] text-base"></i><span><?php echo esc_html__('Social Share', 'botanica'); ?></span></a>
		</div>
		<div class="<?php echo esc_attr($class_string); ?>" data-modal-animation="<?php echo esc_attr($modal_effect); ?>">
			<div class="rbb-social-share-inner p-[25px] md:min-w-[500px] bg-white rounded-lg shadow-[0px_0px_10px_0_rgba(0,0,0,0.2)]">
				<div class="copy-link">
					<div class="social-title text-[color:var(--rbb-general-heading-color)] font-bold mb-3"><?php echo esc_html__('Copy link', 'botanica'); ?></div>
				</div>
				<div class="rbb-copy flex pb-[15px]">
					<input class="w-full rounded" type="text" value="<?php echo esc_url($permalink); ?>"/>
					<div class="copy-btn button bg-[color:var(--rbb-general-primary-color)] hover:bg-[color:var(--rbb-general-secondary-color)] text-white duration-300 h-[46px] leading-[46px] ml-[5px] rounded-[5px] min-w-[85px] text-center cursor-pointer" data-copy="Copy" data-copied="Copied"><?php echo esc_html__('Copy', 'botanica'); ?></div>
				</div>
				<div class="mb-2.5 font-bold share-title text-[color:var(--rbb-general-heading-color)]"><?php echo esc_html__('Share', 'botanica'); ?></div>
				<div class="share-list text-xs"><?php echo wp_kses_post($html_link); ?></div>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="rbb-social-share-wrapper flex">
			<label class="uppercase font-bold text-[color:var(--rbb-general-heading-color)]"><i class="rbb-icon-social-share-3 pr-3"></i>
				<?php echo esc_html__('Social Share :', 'botanica'); ?>
			</label>
			<div class="rbb-social-share_2">
				<?php echo wp_kses_post($html_link); ?>
			</div>
		</div>
		<?php
	}
}
?>
