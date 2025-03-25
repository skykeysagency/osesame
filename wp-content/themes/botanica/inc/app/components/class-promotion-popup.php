<?php
/**
 * RisingBambooTheme Component.
 *
 * @package RisingBambooTheme.
 * @version 1.0.0
 * @since 1.0.0
 */

namespace RisingBambooTheme\App\Components;

use RisingBambooTheme\Core\Singleton;
use RisingBambooTheme\Helper\Setting;

/**
 * Promotion Popup Class.
 */
class PromotionPopup extends Singleton {

	/**
	 * Construction.
	 */
	public function __construct() {
		if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_ENABLE) ) {
			add_action('wp_footer', [ $this, 'html_output' ]);
		}
	}

	/**
	 * Render HTML.
	 *
	 * @return void
	 */
	public function html_output(): void {
		$type                    = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_TYPE);
		$form                    = ( 'newsletter' === $type ) ? Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_NEWSLETTER_FORM) : null;
		$title                   = ( 'newsletter' === $type ) ? Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_TITLE) : '';
		$sub_title               = ( 'newsletter' === $type ) ? Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_SUB_TITLE) : '';
		$desc                    = ( 'newsletter' === $type ) ? Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DESC) : '';
		$link                    = ( 'promotion' === $type ) ? Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_LINK) : null;
		$image                   = ( 'promotion' === $type ) ? Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_IMAGE) : Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_NEWSLETTER_IMAGE);
		$dont_show_again         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DONT_SHOW_AGAIN);
		$dont_show_again_expired = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DONT_SHOW_AGAIN_EXPIRED);
		$delay                   = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DELAY);
		$repeat                  = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_REPEAT);
		get_template_part('template-parts/components/promotion-popup', null, compact('type', 'form', 'image', 'title', 'sub_title', 'desc', 'dont_show_again', 'dont_show_again_expired', 'delay', 'repeat', 'link'));
	}
}
