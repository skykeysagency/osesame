<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme
 * @version 1.0.0
 * @since 1.0.0
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;
$promotion_popup = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_TYPE);
$outside         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE);
$modal_effect    = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT);
$backdrop_filter = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER);
$classes         = [ 'rbb-promotion-popup rbb-modal', 'hidden' ];
$classes[]       = ( true === $backdrop_filter ) ? 'backdrop' : 'backdrop-none';
$classes[]       = ( false === $outside ) ? 'outside-modal' : '';
$classes[]       = esc_attr($promotion_popup);
$class_string    = implode(' ', array_filter($classes));
?>
<div id="rbb-promotion-popup" class="<?php echo esc_attr($class_string); ?>" data-modal-animation="<?php echo esc_attr($modal_effect); ?>" data-modal = '{
	"delay" : <?php echo esc_attr($args['delay']); ?>,
	"repeat" : <?php echo esc_attr($args['repeat']); ?>,
	"dont_show_again_expired" : <?php echo esc_attr($args['dont_show_again_expired']); ?>
}'>
<div class="rbb-promotion-popup-inner flex rounded-[18px] overflow-hidden">
	<?php if ( 'promotion' === $promotion_popup ) { ?>
		<div class="image w-full"><a href="<?php echo esc_url($args['link']); ?>"><img class="rounded-l-[18px]" src="<?php echo esc_url(( $args['image'] ) ? $args['image']['url'] : ''); ?>" alt="Image Newsletter"></a>
		</div>
	<?php } else { ?>
	<div class="image w-[47.8%] hidden md:block">
		<img class="rounded-l-[18px]" src="<?php echo esc_url(( $args['image'] ) ? $args['image']['url'] : ''); ?>" alt="Image Newsletter">
	</div>
	<div class="popup-content md:w-[52.2%] w-full lg:px-[65px] lg:pt-20 lg:pb-5 md:px-10 md:pt-10 p-6 text-center relative bg-white z-10 rounded-r-[18px]">
		<?php
		if ( $args['sub_title'] ) {
			?>
			<h4 class="sub-title text-lg font-normal pb-[13px] text-[color:var(--rbb-general-secondary-color)]"><?php echo esc_html($args['sub_title']); ?></h4>
		<?php } ?>
		<?php if ( $args['title'] ) { ?>
			<h2 class="title text-[24px] font-extrabold uppercase pb-4"><?php echo esc_html($args['title']); ?></h2>
		<?php } ?>
		<?php if ( $args['desc'] ) { ?>
			<div class="desc"><?php echo wp_kses_post($args['desc']); ?></div>
		<?php } ?>
		<?php if ( $args['form'] ) { ?>
			<div class="newsletter-form pt-8 relative">
				<?php echo do_shortcode('[contact-form-7 id="' . $args['form'] . '"]'); ?>
			</div>
		<?php } ?>
		<?php if ( $args['dont_show_again'] ) { ?>
			<div class="md:absolute md:bottom-5 md:right-1/2 md:translate-x-2/4 md:pt-0 pt-8 text-[11px]">
				<input class="cursor-pointer" id="rbb_dont_show_again" type="checkbox" name="rbb_dont_show_again">
				<label for="rbb_dont_show_again"><?php echo esc_html__('Don\'t show this popup again', 'botanica'); ?></label>
			</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>
</div>
