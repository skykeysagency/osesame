<?php
/**
 * The default value of customizer field.
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\Customizer\Helper as RisingBambooCustomizerHelper;
use RisingBambooTheme\Helper\Helper as RisingBambooThemeHelper;

//phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
$cz_body_font_family = $cz_heading_font_family = 'Nunito';
$cz_body_text        = '#434343';
$cz_primary_color    = '#01693a';
$cz_secondary_color  = '#84d814';
$cz_title_color      = '#222222';
$cz_border_color     = '#0d3619';

$cz_default_footer = 'default';
if ( RisingBambooThemeHelper::elementor_activated() ) {
	$cz_default_footer = array_key_first(RisingBambooCustomizerHelper::get_elementor_footers());
}
return [
	// <editor-fold desc="Page Title">
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_TITLE => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_TITLE_COLOR => [
		'default' => $cz_title_color,
	],
	// </editor-fold>

	// <editor-fold desc="Breadcrumb">
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_COLOR => [
		'default' => $cz_title_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_COLOR => [
		'default' => '#f2f2f2',
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/breadcrumb.jpg',
		],
	],
	// </editor-fold>

	// <editor-fold desc="Header">
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER => [
		'default' => 'default',
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_BACKGROUND_COLOR => [
		'default' => '#00241a',
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_LOGIN_FORM => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_MINI_CART => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_WISH_LIST => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM_MOBILE => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BEHAVIOUR => [
		'default' => 'up',
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_HEIGHT => [
		'default' => 80,
	],
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BACKGROUND_COLOR => [
		'default' => '#00241a',
	],
	// </editor-fold>

	// <editor-fold desc="Footer">
	RISING_BAMBOO_KIRKI_FIELD_LAYOUT_FOOTER => [
		'default' => $cz_default_footer,
	],
	// </editor-fold>

	// <editor-fold desc="Color">
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_HEADING_COLOR => [
		'default' => $cz_title_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_TEXT => [
		'default' => $cz_body_text,
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_BACKGROUND => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_PRIMARY_COLOR => [
		'default' => $cz_primary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_SECONDARY_COLOR => [
		'default' => $cz_secondary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_LINK => [
		'default' => [
			'link'  => $cz_title_color,
			'hover' => $cz_primary_color,
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_LINK => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_LINK_HOVER => [
		'default' => $cz_secondary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_BACKGROUND => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR => [
		'default' => [
			'link'    => '#000000',
			'hover'   => '#ffffff',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND => [
		'default' => [
			'link'    => '#ececec',
			'hover'   => $cz_primary_color,
		],
	],
	// </editor-fold>

	// <editor-fold desc="Typography">
	RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY => [
		'default' => [
			'font-family'    => $cz_body_font_family,
			'variant'        => '400',
			'font-size'      => '14px',
			'line-height'    => '1.5rem',
			'letter-spacing' => '0em',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING => [
		'default' => [
			'font-family'    => $cz_heading_font_family,
			'variant'        => '800',
			'line-height'    => '1.5rem',
			'letter-spacing' => '0em',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_STRONG_WEIGHT => [
		'default' => '700',
	],
	RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON => [
		'default' => [
			'font-family'    => 'inherit',
			'variant'        => '800',
			'font-size'      => '11px',
			'text-transform' => 'uppercase',
			'letter-spacing' => '0em',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM => [
		'default' => [
			'font-family'    => 'inherit',
			'variant'        => 'regular',
			'letter-spacing' => '0em',
			'font-size'      => '12px',
		],
	],
	// </editor-fold>

	// <editor-fold desc="Logo">

	RISING_BAMBOO_KIRKI_FIELD_LOGO_STATUS => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_MODE => [
		'default' => 'light',
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_DARK => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/logo/dark.png',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_LIGHT => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/logo/light.png',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_MAX_WIDTH => [
		'default' => '156',
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_PADDING => [
		'default' => [
			'padding-top'    => '0px',
			'padding-bottom' => '0px',
			'padding-left'   => '0px',
			'padding-right'  => '0px',
		],
	],

	// <editor-fold desc="Logo Sticky">
	RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_DARK => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/logo/dark-sticky.png',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_LIGHT => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/logo/light-sticky.png',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_MAX_WIDTH => [
		'default' => '100',
	],
	RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_PADDING => [
		'default' => [
			'padding-top'    => '0px',
			'padding-bottom' => '0px',
			'padding-left'   => '0px',
			'padding-right'  => '0px',
		],
	],
	// </editor-fold>

	// </editor-fold>

	// <editor-fold desc="Woocommerce">

	// <editor-fold desc="Woocommerce Account Navigation">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DASHBOARD_ICON => [
		'default' => 'rbb-icon-settings-outline3',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_ORDERS_ICON => [
		'default' => 'rbb-icon-shopping-basket-6',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DOWNLOADS_ICON => [
		'default' => 'rbb-icon-download-outline-3',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_ADDRESS_ICON => [
		'default' => 'rbb-icon-home-outline-1',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DETAIL_ICON => [
		'default' => 'rbb-icon-human-user-7',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_LOGOUT_ICON => [
		'default' => 'rbb-icon-account-logout-5',
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce Mini Cart">
	// <editor-fold desc="Woocommerce Mini Cart Layout">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_LAYOUT => [
		'default' => 'canvas',
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce Mini Cart Icon">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON => [
		'default' => 'rbb-icon-shopping-basket-6',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_SIZE => [
		'default' => '20',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER_RADIUS => [
		'default' => '100%',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER_COLOR => [
		'default' => $cz_border_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_BACKGROUND_COLOR => [
		'default' => $cz_primary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_POSITION => [
		'default' => [
			'top'    => '-6px',
			'right'  => '-6px',
			'bottom' => 'auto',
			'left'   => 'auto',
		],
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce Mini Cart Content">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BACKGROUND_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BORDER => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BORDER_COLOR => [
		'default' => '#23c052',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_SIZE => [
		'default' => '20',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_COLOR => [
		'default' => '#000000',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BACKGROUND_COLOR => [
		'default' => 'rgb(0,0,0,0)',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BORDER => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BORDER_COLOR => [
		'default' => '#000000',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_PRODUCT_IMAGE_SIZE => [
		'default' => '100',
	],
	// </editor-fold>
	// </editor-fold>

	// <editor-fold desc="Woocommerce WPC Wishlist">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON => [
		'default' => 'rbb-icon-wishlist-10',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_GENERAL_ICON => [
		'default' => 'rbb-icon-wishlist-8',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_SIZE => [
		'default' => '18',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER_RADIUS => [
		'default' => '100%',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER_COLOR => [
		'default' => $cz_border_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_BACKGROUND => [
		'default' => $cz_primary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_POSITION => [
		'default' => [
			'top'    => '-5px',
			'right'  => '-4px',
			'bottom' => 'auto',
			'left'   => 'auto',
		],
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce WPC Compare">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_COMPARE_GENERAL_ICON => [
		'default' => 'rbb-icon-compare-11',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_COMPARE_GENERAL_ICON_SIZE => [
		'default' => '20',
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce Quick View">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_STATUS => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_ICON => [
		'default' => 'rbb-icon-quickview-popup-1',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_ICON_SIZE => [
		'default' => '20',
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce Product Detail">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_LAYOUT => [
		'default' => 'default',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_THUMBNAIL_POSITION => [
		'default' => 'bottom',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_SCROLL_THUMBNAIL_POSITION => [
		'default' => 'left',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_THUMBNAIL_NUMBER => [
		'default' => 5,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_EXCERPT => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_SKU => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_CATEGORY => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_TAG => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_SHARING => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_GUARANTEE_CHECKOUT => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_GUARANTEE_CHECKOUT => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/guarantee_safe_checkout.png',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_GUARANTEE_CHECKOUT_TEXT => [
		'default' => __('Guarantee safe & secure checkout', 'botanica'),
	],

	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_SHOW => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_TEXT => [
		'default' => __('Ask a Question', 'botanica'),
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_FORM => [
		'default' => '',
	],

	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_DATA_LAYOUT => [
		'default' => 'tab',
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce Related & Up-sell Product">
	// <editor-fold desc="Related & Up-sell Layout">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_UP_CROSS_SELLS_LAYOUT => [
		'default' => 'tabs',
	],
	// </editor-fold>

	// <editor-fold desc="Related Product">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_STATUS => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_COLS => [
		'default' => 4,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_PER_PAGE => [
		'default' => '8',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_ORDER => [
		'default' => 'desc',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_ORDER_BY => [
		'default' => 'rand',
	],
	// </editor-fold>

	// <editor-fold desc="Upsells">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_STATUS => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_COLS => [
		'default' => 4,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_PER_PAGE => [
		'default' => '8',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_ORDER => [
		'default' => 'desc',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_ORDER_BY => [
		'default' => 'rand',
	],
	// </editor-fold>

	// </editor-fold>

	// <editor-fold desc="Woocommerce Cross-sells">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_STATUS => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_COLS => [
		'default' => 4,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_PER_PAGE => [
		'default' => '8',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_ORDER => [
		'default' => 'desc',
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_ORDER_BY => [
		'default' => 'rand',
	],
	// </editor-fold>

	// <editor-fold desc="Woocommerce Catalog">
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_CATEGORIES_PER_ROW => [
		'default' => 4,
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_LAYOUT_TYPE => [
		'default' => 'container',
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PAGINATION => [
		'default' => 'load_more',
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION => [
		'default' => 'left',
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_WISHLIST => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_RATING => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_QUICK_VIEW => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_COMPARE => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_ADD_TO_CART => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_STOCK => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_CUSTOM_FIELDS => [
		'default' => true,
	],
	// </editor-fold>

	// </editor-fold>
	// <editor-fold desc="Woocommerce Images">
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGES_SHOW => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGE_EFFECT => [
		'default' => 'fade_in_image',
	],
	// </editor-fold>

	// </editor-fold>
	
	// <editor-fold desc="Components">

	// <editor-fold desc="Account Component">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON => [
		'default' => 'rbb-icon-human-user-11',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_SIZE => [
		'default' => '18',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_RADIUS => [
		'default' => '100%',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_COLOR => [
		'default' => $cz_border_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_CONTENT_BACKGROUND_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_COLOR => [
		'default' => $cz_border_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_RADIUS => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_EDIT => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON => [
		'default' => 'rbb-icon-edit-human-5',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON_COLOR => [
		'default' => $cz_primary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON => [
		'default' => 'rbb-icon-account-logout-9',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON_COLOR => [
		'default' => $cz_primary_color,
	],
	// </editor-fold>

	// <editor-fold desc="Mobile Navigation Component">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STATUS => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_BACKGROUND_COLOR => [
		'default' => '#fff',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_TEXT_COLOR => [
		'default' => $cz_title_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STICKY_BEHAVIOUR => [
		'default' => 'both',
	],
	// </editor-fold>

	// <editor-fold desc="Search">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_BY_CATEGORY => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_AJAX => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_RESULT_LIMIT => [
		'default' => '4',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_RESULT_COLUMN => [
		'default' => '4',
	],

	// <editor-fold desc="Search Style">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON => [
		'default' => 'rbb-icon-search-10',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_SIZE => [
		'default' => '18',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_COLOR => [
		'default' => $cz_secondary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER_RADIUS => [
		'default' => '100%',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER_COLOR => [
		'default' => 'rgb(0,0,0,0)',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_COLOR => [
		'default' => $cz_primary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER_COLOR => [
		'default' => $cz_primary_color,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER_RADIUS => [
		'default' => '50px',
	],
	// </editor-fold>

	// </editor-fold>

	// <editor-fold desc="Rating">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON => [
		'default' => 'rbb-icon-rating-start-filled-1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON_SIZE => [
		'default' => '0.9',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON_COLOR => [
		'default' => '#f7a92f',
	],
	// </editor-fold>

	// <editor-fold desc="Scroll to top">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON => [
		'default' => 'rbb-icon-direction-51',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_SIZE => [
		'default' => '20',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_COLOR => [
		'default' => '#ffffff',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_BACKGROUND_COLOR => [
		'default' => $cz_primary_color,
	],
	// </editor-fold>

	// <editor-fold desc="Modal">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER_SIZE => [
		'default' => '10',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKGROUND_COLOR => [
		'default' => '#000000',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKGROUND_OPACITY => [
		'default' => '0.5',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_ESC_CLOSE => [
		'default' => '1',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT => [
		'default' => 'none',
	],
	// </editor-fold>

	// <editor-fold desc="Loading">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BLOCK => [
		'default' => 'default',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BUTTON => [
		'default' => 'default',
	],
	// </editor-fold>

	// <editor-fold desc="Post Navigation">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_SINGLE => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_PAGE => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_ATTACHMENT => [
		'default' => '0',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_CUSTOM_POST_TYPE => [
		'default' => '0',
	],
	// </editor-fold>

	// <editor-fold desc="Promotion Popup">
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_ENABLE => [
		'default' => 1,
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_TYPE => [
		'default' => 'promotion',
	],

	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_IMAGE => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/banner-popup.png',
		],
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_NEWSLETTER_IMAGE => [
		'default' => [
			'url' => RBB_THEME_DIST_URI . 'images/newsletter-popup.jpg',
		],
	],

	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_TITLE => [
		'default' => 'GET 10% DISCOUNT',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_SUB_TITLE => [
		'default' => 'Sign up Newsletter',
	],
	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DESC => [
		'default' => 'Sign up for newsletter to receive special offers and exclusive news about products',
	],

	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DONT_SHOW_AGAIN => [
		'default' => 1,
	],

	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DONT_SHOW_AGAIN_EXPIRED => [
		'default' => 1440, // minutes.
	],

	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DELAY => [
		'default' => 1000, // milliseconds.
	],

	RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_REPEAT => [
		'default' => 10, // minutes.
	],
	// </editor-fold>

	// <editor-fold desc="Advanced & Page 404">
	// <editor-fold desc="Advanced">
	RISING_BAMBOO_KIRKI_FIELD_ADVANCED_MEGA_MENU_NORMALIZE_CLASSES => [
		'default' => true,
	],
	// </editor-fold>
	// <editor-fold desc="Page 404">
	RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_TITLE => [
		'default' => 'PAGE NOT FOUND',
	],
	RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_DESC => [
		'default' => 'We’re sorry — something has gone wrong on our end.',
	],

	// </editor-fold>

	// <editor-fold desc="Blog">

	// <editor-fold desc="Blog Category">
	RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT => [
		'default' => 'default',
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT_COLUMNS => [
		'default' => 2,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT_SIDEBAR => [
		'default' => 'left',
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_AUTHOR => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_PUBLISH_DATE => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_EXCERPT => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_COMMENT_COUNT => [
		'default' => false,
	],
	// </editor-fold>

	// <editor-fold desc="Blog Detail">
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT => [
		'default' => 'default',
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_THUMBNAIL_POSITION => [
		'default' => 'before_title',
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_SIDEBAR => [
		'default' => 'left',
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_AUTHOR => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_PUBLISH_DATE => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_CATEGORY => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_TAG => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_SOCIAL_SHARE => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_COMMENT => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_COMMENT_FORM => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW => [
		'default' => true,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW_NAVIGATION => [
		'default' => false,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW_PAGINATION => [
		'default' => false,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_AUTO_PLAY => [
		'default' => false,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_AUTO_PLAY_SPEED => [
		'default' => 3000,
	],
	RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_LIMIT => [
		'default' => 6,
	],
	// </editor-fold>

	// </editor-fold>
];
