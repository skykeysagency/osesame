<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\Customizer;

use Automattic\Jetpack\Search\Package;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooTheme\Core\Singleton;

/**
 * Customizer style
 *
 * @package Rising_Bamboo
 */
class Style extends Singleton {

	/**
	 * Construction.
	 */
	public function __construct() {
		if ( class_exists(RisingBambooKirki::class) ) {
			add_action('customize_controls_print_styles', [ $this, 'customize_style' ]);
			add_action('customize_controls_print_styles', [ $this, 'kirki_style' ]);
		}
	}

	/**
	 * For customizer.
	 */
	public function customize_style(): void {
		?>
		<style>
			.accordion-section.control-section h3 {
				display: flex;
				align-items: center;
			}
			.assigned-to-menu-location h3.accordion-section-title {
				padding: 5px 10px 22px 14px;
			}
			.assigned-to-menu-location h3 .menu-in-location {
				position: absolute;
				left: 55px;
				bottom: 10px;
			}
			.assigned-to-menu-location h3.accordion-section-title:before {
				-moz-transform: translateY(9px);
				-webkit-transform: translateY(9px);
				-o-transform: translateY(9px);
				-ms-transform: translateY(9px);
				transform: translateY(9px);
			}
			#customize-theme-controls h3.accordion-section-title:before {
				width: 2em;
				height: 2em;
				margin-right: 10px;
				display: flex;
				align-items: center;
				justify-content: center;
				text-align: center;
				background-color: #08944d;
				color: #fff;
				border-radius: 10%;
				font-family: 'Dashicons', serif;
				font-size: 16px;
				-webkit-font-smoothing: antialiased;
			}
			#accordion-section-title_tagline h3:before {
				content: "\f102";
			}
			#accordion-section-header_image h3:before {
				content: "\f128";
			}
			#accordion-section-background_image h3:before {
				content: "\f161";
			}
			#accordion-section-static_front_page h3:before {
				content: "\f319";
			}
			#accordion-panel-nav_menus h3:before {
				content: "\f333";
			}
			#accordion-panel-widgets h3:before {
				content: "\f109";
			}
			#accordion-panel-woocommerce h3:before {
				content: "\f230";
			}
			#accordion-section-custom_css h3:before {
				content: "\f475";
			}
			#accordion-section-wpseo_breadcrumbs_customizer_section h3:before {
				content: "\f238";
			}
		</style>
		<?php
	}
	/**
	 * For Kirki Plugin.
	 */
	public function kirki_style(): void {
		?>
		<style>
			/* Panel */
			#accordion-panel-<?php echo RISING_BAMBOO_KIRKI_PANEL_GENERAL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before{
				content: "\f226";
			}
			#accordion-panel-<?php echo RISING_BAMBOO_KIRKI_PANEL_LAYOUT; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before{
				content: "\f15c";
			}
			#accordion-panel-<?php echo RISING_BAMBOO_KIRKI_PANEL_COMPONENT; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before{
				content: "\f108";
			}
			#accordion-panel-<?php echo RISING_BAMBOO_KIRKI_PANEL_ADVANCED; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before{
				content: "\f111";
			}
			#accordion-panel-<?php echo RISING_BAMBOO_KIRKI_PANEL_BLOG; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before{
				content: "\f119";
			}

			/* Section */
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before{
				content: "\f210";
			}
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_LOGO; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before{
				content: "\f309";
			}
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_LAYOUT_TITLE; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_LAYOUT_FOOTER; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COLOR_MENU; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_LOADING; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SEARCH; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_RATING; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MODAL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_PROMOTION_POPUP; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_ADVANCED_MEGA_MENU; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_ACCOUNT; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_CART; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_WISHLIST; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_COMPARE; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_DETAIL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_QUICK_VIEW; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_BLOG_CATEGORY; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before,
			#accordion-section-<?php echo RISING_BAMBOO_KIRKI_SECTION_BLOG_DETAIL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> h3:before
			{
				content: "\e900";
				font-family: "rbb-font", serif !important;
			}
			li[id ^=accordion-section-nav_menu] h3:before,
			#accordion-section-menu_locations h3:before,
			#accordion-section-woocommerce_store_notice h3:before,
			#accordion-section-woocommerce_product_catalog h3:before,
			#accordion-section-woocommerce_product_images h3:before,
			#accordion-section-woocommerce_checkout h3:before
			{
				content: "\ea9c";
				font-family: "rbb-font", serif !important;
			}
			.customize-control-kirki-radio-image input:checked + label img,
			.customize-control-kirki-radio-image img {
				margin-top: 10px;
				border-radius: 6px;
				-webkit-box-shadow: none;
				box-shadow: none;
				border: 2px solid transparent;
				opacity: 0.8;
			}
			.customize-control-kirki-radio-image input:checked + label img {
				border-color: #3498DB;
				opacity: 1;
			}
			.rising-bamboo-kirki-separator {
				margin: 10px -12px;
				padding: 12px 12px;
				color: #fff;
				text-transform: uppercase;
				letter-spacing: 1px;
				background-color: #08944d;
				cursor: default;
			}
			.rising-bamboo-kirki-separator > small {
				margin-left:8px;
			}
		</style>
		<?php
	}
}
