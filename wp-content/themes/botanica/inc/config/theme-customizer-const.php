<?php
/**
 * Theme customizer constance.
 *
 * @package RisingBambooTheme
 */

/**
 * Config ID.
 */

const RISING_BAMBOO_PREFIX = 'rbb_';

const RISING_BAMBOO_KIRKI_CONFIG = 'rising_bamboo_config';

// <editor-fold desc="General Panel">
const RISING_BAMBOO_KIRKI_PANEL_GENERAL = RISING_BAMBOO_PREFIX . 'panel_general';
// </editor-fold>

// <editor-fold desc="Layout">
const RISING_BAMBOO_KIRKI_PANEL_LAYOUT = RISING_BAMBOO_PREFIX . 'panel_layout';

// <editor-fold desc="Page Title">
const RISING_BAMBOO_KIRKI_SECTION_LAYOUT_TITLE           = RISING_BAMBOO_PREFIX . 'section_layout_title';
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_TITLE_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'layout_title_group_title';
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_TITLE', defined('RBB_CORE_META_FIELD_LAYOUT_TITLE') ? RBB_CORE_META_FIELD_LAYOUT_TITLE : RISING_BAMBOO_PREFIX . 'layout_title_show');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_TITLE_COLOR', defined('RBB_CORE_META_FIELD_LAYOUT_TITLE_COLOR') ? RBB_CORE_META_FIELD_LAYOUT_TITLE_COLOR : RISING_BAMBOO_PREFIX . 'layout_title_color');
// </editor-fold>

// <editor-fold desc="Breadcrumb">
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB', defined('RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB') ? RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB : RISING_BAMBOO_PREFIX . 'layout_breadcrumb_show');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_COLOR', defined('RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_COLOR') ? RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_COLOR : RISING_BAMBOO_PREFIX . 'layout_breadcrumb_color');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_COLOR', defined('RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_COLOR') ? RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_COLOR : RISING_BAMBOO_PREFIX . 'layout_breadcrumb_background_color');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE', defined('RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE') ? RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE : RISING_BAMBOO_PREFIX . 'layout_breadcrumb_background_image');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE_ENABLE', defined('RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE_ENABLE') ? RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE_ENABLE : RISING_BAMBOO_PREFIX . 'layout_breadcrumb_background_image_enable');
// </editor-fold>

// <editor-fold desc="Header">
const RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER           = RISING_BAMBOO_PREFIX . 'layout_header';
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'layout_header_group_title';
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER', defined('RBB_CORE_META_FIELD_LAYOUT_HEADER') ? RBB_CORE_META_FIELD_LAYOUT_HEADER : RISING_BAMBOO_PREFIX . 'layout_header');
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_BACKGROUND_COLOR = RISING_BAMBOO_PREFIX . 'layout_header_navigation_background_color';
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_LOGIN_FORM', defined('RBB_CORE_META_FIELD_LAYOUT_HEADER_LOGIN_FORM') ? RBB_CORE_META_FIELD_LAYOUT_HEADER_LOGIN_FORM : RISING_BAMBOO_PREFIX . 'layout_header_login_form');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM', defined('RBB_CORE_META_FIELD_LAYOUT_HEADER_SEARCH_FORM') ? RBB_CORE_META_FIELD_LAYOUT_HEADER_SEARCH_FORM : RISING_BAMBOO_PREFIX . 'layout_header_search_form');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM_MOBILE', defined('RBB_CORE_META_FIELD_LAYOUT_HEADER_SEARCH_FORM_MOBILE') ? RBB_CORE_META_FIELD_LAYOUT_HEADER_SEARCH_FORM_MOBILE : RISING_BAMBOO_PREFIX . 'layout_header_search_form_mobile');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_MINI_CART', defined('RBB_CORE_META_FIELD_LAYOUT_HEADER_MINI_CART') ? RBB_CORE_META_FIELD_LAYOUT_HEADER_MINI_CART : RISING_BAMBOO_PREFIX . 'layout_header_mini_cart');
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_WISH_LIST', defined('RBB_CORE_META_FIELD_LAYOUT_HEADER_WISH_LIST') ? RBB_CORE_META_FIELD_LAYOUT_HEADER_WISH_LIST : RISING_BAMBOO_PREFIX . 'layout_header_wish_list');
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY                  = RISING_BAMBOO_PREFIX . 'layout_header_sticky';
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BEHAVIOUR        = RISING_BAMBOO_PREFIX . 'layout_header_sticky_behaviour';
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_HEIGHT           = RISING_BAMBOO_PREFIX . 'layout_header_sticky_height';
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BACKGROUND_COLOR = RISING_BAMBOO_PREFIX . 'layout_header_sticky_background_color';
// </editor-fold>

// <editor-fold desc="Footer">
const RISING_BAMBOO_KIRKI_SECTION_LAYOUT_FOOTER = RISING_BAMBOO_PREFIX . 'section_layout_footer';
define('RISING_BAMBOO_KIRKI_FIELD_LAYOUT_FOOTER', defined('RBB_CORE_META_FIELD_LAYOUT_FOOTER') ? RBB_CORE_META_FIELD_LAYOUT_FOOTER : RISING_BAMBOO_PREFIX . 'layout_footer');
const RISING_BAMBOO_KIRKI_FIELD_LAYOUT_FOOTER_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'layout_footer_group_title';
// </editor-fold>
// </editor-fold>

// <editor-fold desc="Color">
const RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL                 = RISING_BAMBOO_PREFIX . 'section_color_general';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_GROUP_TITLE       = RISING_BAMBOO_PREFIX . 'color_general_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_HEADING_COLOR     = RISING_BAMBOO_PREFIX . 'color_general_heading_color';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_TEXT         = RISING_BAMBOO_PREFIX . 'color_general_body_text';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_BACKGROUND   = RISING_BAMBOO_PREFIX . 'color_general_body_background';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_PRIMARY_COLOR     = RISING_BAMBOO_PREFIX . 'color_general_primary_color';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_SECONDARY_COLOR   = RISING_BAMBOO_PREFIX . 'color_general_secondary_color';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_LINK              = RISING_BAMBOO_PREFIX . 'color_general_link';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR = RISING_BAMBOO_PREFIX . 'color_general_button_text';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND = RISING_BAMBOO_PREFIX . 'color_general_button_background';
// </editor-fold>

// <editor-fold desc="Menu">
const RISING_BAMBOO_KIRKI_SECTION_COLOR_MENU           = RISING_BAMBOO_PREFIX . 'section_color_menu';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'color_menu_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_LINK        = RISING_BAMBOO_PREFIX . 'color_menu_link';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_LINK_HOVER  = RISING_BAMBOO_PREFIX . 'color_menu_link_hover';
const RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_BACKGROUND  = RISING_BAMBOO_PREFIX . 'color_menu_background';
// </editor-fold>

// <editor-fold desc="Typography">
const RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY             = RISING_BAMBOO_PREFIX . 'section_typography';
const RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_GROUP_TITLE   = RISING_BAMBOO_PREFIX . 'typography_group_title';
const RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY          = RISING_BAMBOO_PREFIX . 'typography_body';
const RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING       = RISING_BAMBOO_PREFIX . 'typography_heading';
const RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_STRONG_WEIGHT = RISING_BAMBOO_PREFIX . 'typography_strong_weight';
const RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON        = RISING_BAMBOO_PREFIX . 'typography_button';
const RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM          = RISING_BAMBOO_PREFIX . 'typography_form';
// </editor-fold>

// <editor-fold desc="Logo">
// <editor-fold desc="Logo">
const RISING_BAMBOO_KIRKI_SECTION_LOGO           = RISING_BAMBOO_PREFIX . 'section_logo';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'logo_group_title';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_MODE        = RISING_BAMBOO_PREFIX . 'logo_mode';
// </editor-fold>

// <editor-fold desc="Logo General">
define('RISING_BAMBOO_KIRKI_FIELD_LOGO_STATUS', defined('RBB_CORE_META_FIELD_LOGO_STATUS') ? RBB_CORE_META_FIELD_LOGO_STATUS : RISING_BAMBOO_PREFIX . 'logo_status');
const RISING_BAMBOO_KIRKI_FIELD_LOGO_LIGHT     = RISING_BAMBOO_PREFIX . 'logo_light';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_DARK      = RISING_BAMBOO_PREFIX . 'logo_dark';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_MAX_WIDTH = RISING_BAMBOO_PREFIX . 'logo_max_width';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_PADDING   = RISING_BAMBOO_PREFIX . 'logo_padding';
// </editor-fold>

// <editor-fold desc="Logo Sticky">
const RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_LIGHT     = RISING_BAMBOO_PREFIX . 'logo_sticky_light';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_DARK      = RISING_BAMBOO_PREFIX . 'logo_sticky_dark';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_MAX_WIDTH = RISING_BAMBOO_PREFIX . 'logo_sticky_max_width';
const RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_PADDING   = RISING_BAMBOO_PREFIX . 'logo_sticky_padding';
// </editor-fold>
// </editor-fold>

// <editor-fold desc="Advance">
const RISING_BAMBOO_KIRKI_PANEL_ADVANCED = RISING_BAMBOO_PREFIX . 'panel_advanced';
// <editor-fold desc="Advance Mega Menu">
const RISING_BAMBOO_KIRKI_SECTION_ADVANCED_MEGA_MENU                 = RISING_BAMBOO_PREFIX . 'section_advanced_mega_menu';
const RISING_BAMBOO_KIRKI_FIELD_ADVANCED_MEGA_MENU_NORMALIZE_CLASSES = RISING_BAMBOO_PREFIX . 'section_advanced_mega_menu';
// </editor-fold>
// <editor-fold desc="Advance Page 404">
const RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404             = RISING_BAMBOO_PREFIX . 'section_advanced_page_404';
const RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'section_advanced_page_404_group_title';
const RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_IMAGE       = RISING_BAMBOO_PREFIX . 'section_advanced_page_404_image';
const RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_TITLE       = RISING_BAMBOO_PREFIX . 'section_advanced_page_404_title';
const RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_DESC        = RISING_BAMBOO_PREFIX . 'section_advanced_page_404_desc';
// </editor-fold>
// </editor-fold>

// <editor-fold desc="Woocommerce">

// <editor-fold desc="Account">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_ACCOUNT              = RISING_BAMBOO_PREFIX . 'section_woocommerce_account';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_GROUP_TITLE    = RISING_BAMBOO_PREFIX . 'woocommerce_account_group_title';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DASHBOARD_ICON = RISING_BAMBOO_PREFIX . 'woocommerce_account_dashboard_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_ORDERS_ICON    = RISING_BAMBOO_PREFIX . 'woocommerce_account_orders_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DOWNLOADS_ICON = RISING_BAMBOO_PREFIX . 'woocommerce_account_downloads_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_ADDRESS_ICON   = RISING_BAMBOO_PREFIX . 'woocommerce_account_address_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DETAIL_ICON    = RISING_BAMBOO_PREFIX . 'woocommerce_account_detail_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_LOGOUT_ICON    = RISING_BAMBOO_PREFIX . 'woocommerce_account_logout_icon';
// </editor-fold>

// <editor-fold desc="Cart">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_CART           = RISING_BAMBOO_PREFIX . 'section_woocommerce_cart';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_CART_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'woocommerce_cart_group_title';

// <editor-fold desc="Mini Cart">
// <editor-fold desc="Mini Cart Layout">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_LAYOUT = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_layout';
// </editor-fold>

// <editor-fold desc="Mini Cart Icon">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON                   = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_SIZE              = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_icon_size';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_COLOR             = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_icon_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER            = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_icon_border';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER_RADIUS     = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_icon_border_radius';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER_COLOR      = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_icon_border_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_COLOR            = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_count_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_BACKGROUND_COLOR = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_count_background_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_POSITION         = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_count_position';
// </editor-fold>

// <editor-fold desc="Mini Cart Content">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BACKGROUND_COLOR       = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_content_background_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BORDER                 = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_content_border';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BORDER_COLOR           = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_content_border_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_PRODUCT_IMAGE_SIZE             = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_product_image_size';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_SIZE             = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_remove_button_size';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_COLOR            = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_remove_button_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BACKGROUND_COLOR = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_remove_button_background_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BORDER           = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_remove_button_border';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BORDER_COLOR     = RISING_BAMBOO_PREFIX . 'woocommerce_mini_cart_remove_button_border_color';
// </editor-fold>
// </editor-fold>

// <editor-fold desc="Cross-sells">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_STATUS   = RISING_BAMBOO_PREFIX . 'woocommerce_product_cross_sells_status';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_PER_PAGE = RISING_BAMBOO_PREFIX . 'woocommerce_product_cross_sells_per_page';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_COLS     = RISING_BAMBOO_PREFIX . 'woocommerce_product_cross_sells_cols';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_ORDER    = RISING_BAMBOO_PREFIX . 'woocommerce_product_cross_sells_order';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_ORDER_BY = RISING_BAMBOO_PREFIX . 'woocommerce_product_cross_sells_order_by';
// </editor-fold>

// </editor-fold>

// <editor-fold desc="Wish List">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_WISHLIST                  = RISING_BAMBOO_PREFIX . 'section_woocommerce_wishlist';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_GROUP_TITLE        = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_group_title';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON               = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_GENERAL_ICON       = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_general_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_SIZE          = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_icon_size';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_COLOR         = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_icon_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER        = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_icon_border';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER_RADIUS = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_icon_border_radius';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER_COLOR  = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_icon_borer_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_COLOR        = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_count_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_BACKGROUND   = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_count_background_color';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_POSITION     = RISING_BAMBOO_PREFIX . 'woocommerce_wishlist_count_position';
// </editor-fold>

// <editor-fold desc="Compare">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_COMPARE                 = RISING_BAMBOO_PREFIX . 'section_woocommerce_compare';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_COMPARE_GROUP_TITLE       = RISING_BAMBOO_PREFIX . 'woocommerce_compare_group_title';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_COMPARE_GENERAL_ICON      = RISING_BAMBOO_PREFIX . 'woocommerce_compare_general_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_COMPARE_GENERAL_ICON_SIZE = RISING_BAMBOO_PREFIX . 'woocommerce_compare_general_icon_size';
// </editor-fold>

// <editor-fold desc="Quick View">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_QUICK_VIEW           = RISING_BAMBOO_PREFIX . 'section_woocommerce_quick_view';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'woocommerce_quick_view_group_title';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_STATUS      = RISING_BAMBOO_PREFIX . 'woocommerce_quick_view_status';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_ICON        = RISING_BAMBOO_PREFIX . 'woocommerce_quick_view_icon';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_ICON_SIZE   = RISING_BAMBOO_PREFIX . 'woocommerce_quick_view_icon_size';
// </editor-fold>

// <editor-fold desc="Related Product - Up-sells">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_UP_CROSS_SELLS_LAYOUT = RISING_BAMBOO_PREFIX . 'woocommerce_product_related_up_cross_layout';
// <editor-fold desc="Related Product">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_STATUS   = RISING_BAMBOO_PREFIX . 'woocommerce_product_related_status';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_PER_PAGE = RISING_BAMBOO_PREFIX . 'woocommerce_product_related_per_page';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_COLS     = RISING_BAMBOO_PREFIX . 'woocommerce_product_related_cols';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_ORDER    = RISING_BAMBOO_PREFIX . 'woocommerce_product_related_order';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_ORDER_BY = RISING_BAMBOO_PREFIX . 'woocommerce_product_related_order_by';
// </editor-fold>

// <editor-fold desc="Upsells">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_STATUS   = RISING_BAMBOO_PREFIX . 'woocommerce_product_up_sells_status';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_PER_PAGE = RISING_BAMBOO_PREFIX . 'woocommerce_product_up_sells_per_page';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_COLS     = RISING_BAMBOO_PREFIX . 'woocommerce_product_up_sells_cols';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_ORDER    = RISING_BAMBOO_PREFIX . 'woocommerce_product_up_sells_order';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_ORDER_BY = RISING_BAMBOO_PREFIX . 'woocommerce_product_up_sells_order_by';
// </editor-fold>
// </editor-fold>

// <editor-fold desc="Product Detail">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_DETAIL           = RISING_BAMBOO_PREFIX . 'section_woocommerce_product_detail';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_group_title';
// <editor-fold desc="Product Detail Image">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_LAYOUT                    = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_image_layout';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_THUMBNAIL_POSITION        = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_image_thumbnail_position';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_THUMBNAIL_NUMBER          = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_image_thumbnail_number';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_SCROLL_THUMBNAIL_POSITION = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_image_scroll_thumbnail_position';
// </editor-fold>
// <editor-fold desc="Product Detail Summary">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_EXCERPT            = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_show_excerpt';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_SKU                = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_show_sku';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_CATEGORY           = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_show_category';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_TAG                = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_show_tag';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_SHARING            = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_show_sharing';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_GUARANTEE_CHECKOUT = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_show_guarantee_checkout';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_GUARANTEE_CHECKOUT      = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_guarantee_checkout';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_GUARANTEE_CHECKOUT_TEXT = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_guarantee_checkout_text';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_SHOW            = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_contact_show';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_TEXT            = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_contact_text';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_FORM            = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_summary_contact_from';
// </editor-fold>
// <editor-fold desc="Product Data">
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_DATA_LAYOUT = RISING_BAMBOO_PREFIX . 'woocommerce_product_detail_data_layout';
// </editor-fold>

// </editor-fold>

// <editor-fold desc="Product Catalog">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG                                 = 'woocommerce_product_catalog';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_GROUP_TITLE                     = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_group_title';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_CATEGORIES_PER_ROW              = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_categories_per_row';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_LAYOUT_TYPE                     = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_layout_type';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PAGINATION                      = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_pagination';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION                 = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_filter_position';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_WISHLIST      = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_wishlist';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_RATING        = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_rating';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_QUICK_VIEW    = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_quick_view';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_COMPARE       = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_compare';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_ADD_TO_CART   = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_add_to_cart';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_STOCK         = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_stock';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_CUSTOM_FIELDS = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_custom_fields';
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_CUSTOM_FIELDS_KEYWORD = RISING_BAMBOO_PREFIX . 'woocommerce_product_catalog_product_item_show_custom_fields_keyword';

// </editor-fold>

// </editor-fold>
// <editor-fold desc="Product Images">
const RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_IMAGES              = 'woocommerce_product_images';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGES_TITLE          = RISING_BAMBOO_PREFIX . 'woocommerce_product_images_title';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGES_SHOW           = RISING_BAMBOO_PREFIX . 'woocommerce_product_images_show';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGE_EFFECT          = RISING_BAMBOO_PREFIX . 'woocommerce_product_images_efect';
const RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_IMAGES_CROPPING_TITLE = RISING_BAMBOO_PREFIX . 'woocommerce_product_images_cropping_title';

// <editor-fold desc="Component">
const RISING_BAMBOO_KIRKI_PANEL_COMPONENT = RISING_BAMBOO_PREFIX . 'panel_component';

// <editor-fold desc="Account">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT                        = RISING_BAMBOO_PREFIX . 'section_component_account';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_GROUP_TITLE              = RISING_BAMBOO_PREFIX . 'component_account_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP                    = RISING_BAMBOO_PREFIX . 'component_account_popup';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON                     = RISING_BAMBOO_PREFIX . 'component_account_icon';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_SIZE                = RISING_BAMBOO_PREFIX . 'component_account_icon_size';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_COLOR               = RISING_BAMBOO_PREFIX . 'component_account_icon_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER              = RISING_BAMBOO_PREFIX . 'component_account_icon_border';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_COLOR        = RISING_BAMBOO_PREFIX . 'component_account_icon_border_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_RADIUS       = RISING_BAMBOO_PREFIX . 'component_account_icon_border_radius';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_CONTENT_BACKGROUND_COLOR = RISING_BAMBOO_PREFIX . 'component_account_content_background_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER             = RISING_BAMBOO_PREFIX . 'component_account_input_border';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_COLOR       = RISING_BAMBOO_PREFIX . 'component_account_input_border_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_RADIUS      = RISING_BAMBOO_PREFIX . 'component_account_input_border_radius';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_EDIT         = RISING_BAMBOO_PREFIX . 'component_account_button_edit';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON         = RISING_BAMBOO_PREFIX . 'component_account_button_edit_icon';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON_COLOR   = RISING_BAMBOO_PREFIX . 'component_account_button_edit_icon_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT       = RISING_BAMBOO_PREFIX . 'component_account_button_logout';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON       = RISING_BAMBOO_PREFIX . 'component_account_button_logout_icon';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON_COLOR = RISING_BAMBOO_PREFIX . 'component_account_button_logout_icon_color';
// </editor-fold>

// <editor-fold desc="Search">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SEARCH                   = RISING_BAMBOO_PREFIX . 'section_component_search';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_GROUP_TITLE         = RISING_BAMBOO_PREFIX . 'component_search_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY             = RISING_BAMBOO_PREFIX . 'component_search_overlay';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_BY_CATEGORY         = RISING_BAMBOO_PREFIX . 'component_search_by_category';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_AJAX                = RISING_BAMBOO_PREFIX . 'component_search_ajax';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_RESULT_LIMIT        = RISING_BAMBOO_PREFIX . 'component_search_result_limit';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_RESULT_COLUMN       = RISING_BAMBOO_PREFIX . 'component_search_result_column';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_POPULAR_KEYWORD     = RISING_BAMBOO_PREFIX . 'component_search_popular_keyword';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON                = RISING_BAMBOO_PREFIX . 'component_search_icon';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_SIZE           = RISING_BAMBOO_PREFIX . 'component_search_icon_size';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_COLOR          = RISING_BAMBOO_PREFIX . 'component_search_icon_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER         = RISING_BAMBOO_PREFIX . 'component_search_icon_border';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER_COLOR   = RISING_BAMBOO_PREFIX . 'component_search_icon_border_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER_RADIUS  = RISING_BAMBOO_PREFIX . 'component_search_icon_border_radius';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_COLOR         = RISING_BAMBOO_PREFIX . 'component_search_input_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER        = RISING_BAMBOO_PREFIX . 'component_search_input_border';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER_COLOR  = RISING_BAMBOO_PREFIX . 'component_search_input_border_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER_RADIUS = RISING_BAMBOO_PREFIX . 'component_search_input_border_radius';
// </editor-fold>

// <editor-fold desc="Mobile Navigation">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION                = RISING_BAMBOO_PREFIX . 'section_component_mobile_navigation';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_GROUP_TITLE      = RISING_BAMBOO_PREFIX . 'component_mobile_navigation_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STATUS           = RISING_BAMBOO_PREFIX . 'component_mobile_navigation_status';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_BACKGROUND_COLOR = RISING_BAMBOO_PREFIX . 'component_mobile_navigation_background_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_TEXT_COLOR       = RISING_BAMBOO_PREFIX . 'component_mobile_navigation_text_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STICKY_BEHAVIOUR = RISING_BAMBOO_PREFIX . 'component_mobile_navigation_sticky_behaviour';
// </editor-fold>

// <editor-fold desc="Rating">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_RATING           = RISING_BAMBOO_PREFIX . 'section_component_rating';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'component_rating_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON        = RISING_BAMBOO_PREFIX . 'component_rating_icon';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON_SIZE   = RISING_BAMBOO_PREFIX . 'component_rating_icon_size';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON_COLOR  = RISING_BAMBOO_PREFIX . 'component_rating_icon_color';
// </editor-fold>

// <editor-fold desc="Scroll to top">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP                = RISING_BAMBOO_PREFIX . 'section_component_scroll_to_top';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_GROUP_TITLE      = RISING_BAMBOO_PREFIX . 'component_scroll_to_top_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP                  = RISING_BAMBOO_PREFIX . 'component_scroll_to_top';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON             = RISING_BAMBOO_PREFIX . 'component_scroll_to_top_icon';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_SIZE        = RISING_BAMBOO_PREFIX . 'component_scroll_to_top_icon_size';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_COLOR       = RISING_BAMBOO_PREFIX . 'component_scroll_to_top_icon_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_BACKGROUND_COLOR = RISING_BAMBOO_PREFIX . 'component_scroll_to_top_background_color';
// </editor-fold>

// <editor-fold desc="Modal">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MODAL                    = RISING_BAMBOO_PREFIX . 'section_component_modal';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_GROUP_TITLE          = RISING_BAMBOO_PREFIX . 'component_modal_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER      = RISING_BAMBOO_PREFIX . 'component_modal_backdrop-filter';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER_SIZE = RISING_BAMBOO_PREFIX . 'component_modal_backdrop-filter-size';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKGROUND_COLOR     = RISING_BAMBOO_PREFIX . 'component_modal_background_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKGROUND_OPACITY   = RISING_BAMBOO_PREFIX . 'component_modal_background_opacity_color';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE  = RISING_BAMBOO_PREFIX . 'component_modal_click_outside_close';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_ESC_CLOSE            = RISING_BAMBOO_PREFIX . 'component_modal_esc_close';

const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT = RISING_BAMBOO_PREFIX . 'component_modal_effect';
// </editor-fold>

// <editor-fold desc="Loading">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_LOADING           = RISING_BAMBOO_PREFIX . 'section_component_loading';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'component_loading_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BLOCK       = RISING_BAMBOO_PREFIX . 'component_loading_block';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BUTTON      = RISING_BAMBOO_PREFIX . 'component_loading_button';
// </editor-fold>

// <editor-fold desc="Promotion Popup">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_PROMOTION_POPUP                       = RISING_BAMBOO_PREFIX . 'section_component_promotion_popup';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_GROUP_TITLE             = RISING_BAMBOO_PREFIX . 'component_promotion_popup_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_ENABLE                  = RISING_BAMBOO_PREFIX . 'component_promotion_popup_enable';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_TYPE                    = RISING_BAMBOO_PREFIX . 'component_promotion_popup_type';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_NEWSLETTER_IMAGE        = RISING_BAMBOO_PREFIX . 'component_promotion_popup_newsletter_image';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_IMAGE                   = RISING_BAMBOO_PREFIX . 'component_promotion_popup_image';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_LINK                    = RISING_BAMBOO_PREFIX . 'component_promotion_popup_link';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_TITLE                   = RISING_BAMBOO_PREFIX . 'component_promotion_popup_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_SUB_TITLE               = RISING_BAMBOO_PREFIX . 'component_promotion_popup_sub_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DESC                    = RISING_BAMBOO_PREFIX . 'component_promotion_popup_desc';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DONT_SHOW_AGAIN         = RISING_BAMBOO_PREFIX . 'component_promotion_popup_dont_show_again';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DONT_SHOW_AGAIN_EXPIRED = RISING_BAMBOO_PREFIX . 'component_promotion_popup_dont_show_again_expired';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_DELAY                   = RISING_BAMBOO_PREFIX . 'component_promotion_popup_delay';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_REPEAT                  = RISING_BAMBOO_PREFIX . 'component_promotion_popup_show_again_time';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_PROMOTION_POPUP_NEWSLETTER_FORM         = RISING_BAMBOO_PREFIX . 'component_promotion_popup_newsletter_form';
// </editor-fold>

// </editor-fold>

// <editor-fold desc="Blog">
const RISING_BAMBOO_KIRKI_PANEL_BLOG             = RISING_BAMBOO_PREFIX . 'panel_blog';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_GROUP_TITLE = RISING_BAMBOO_PREFIX . 'section_blog_group_title';
// <editor-fold desc="Blog Category">
const RISING_BAMBOO_KIRKI_SECTION_BLOG_CATEGORY                  = RISING_BAMBOO_PREFIX . 'section_blog_category';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT             = RISING_BAMBOO_PREFIX . 'section_blog_category_layout';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT_COLUMNS     = RISING_BAMBOO_PREFIX . 'section_blog_category_layout_columns';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT_SIDEBAR     = RISING_BAMBOO_PREFIX . 'section_blog_category_layout_sidebar';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_AUTHOR        = RISING_BAMBOO_PREFIX . 'section_blog_category_show_author';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_PUBLISH_DATE  = RISING_BAMBOO_PREFIX . 'section_blog_category_show_publish_date';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_EXCERPT       = RISING_BAMBOO_PREFIX . 'section_blog_category_show_excerpt';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_COMMENT_COUNT = RISING_BAMBOO_PREFIX . 'section_blog_category_show_comment_count';
// </editor-fold>
// <editor-fold desc="Blog Detail">
const RISING_BAMBOO_KIRKI_SECTION_BLOG_DETAIL                         = RISING_BAMBOO_PREFIX . 'section_blog_detail';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT                    = RISING_BAMBOO_PREFIX . 'section_blog_detail_layout';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_THUMBNAIL_POSITION = RISING_BAMBOO_PREFIX . 'section_blog_detail_layout_thumbnail_position';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_SIDEBAR            = RISING_BAMBOO_PREFIX . 'section_blog_detail_layout_sidebar';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_AUTHOR               = RISING_BAMBOO_PREFIX . 'section_blog_detail_show_author';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_PUBLISH_DATE         = RISING_BAMBOO_PREFIX . 'section_blog_detail_show_publish_date';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_CATEGORY             = RISING_BAMBOO_PREFIX . 'section_blog_detail_show_category';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_TAG                  = RISING_BAMBOO_PREFIX . 'section_blog_detail_show_tag';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_SOCIAL_SHARE         = RISING_BAMBOO_PREFIX . 'section_blog_detail_show_social_share';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_COMMENT              = RISING_BAMBOO_PREFIX . 'section_blog_detail_show_comment';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_COMMENT_FORM         = RISING_BAMBOO_PREFIX . 'section_blog_detail_show_comment_form';

// <editor-fold desc="Related Post">
const RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW            = RISING_BAMBOO_PREFIX . 'section_blog_related_post_show';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_LIMIT           = RISING_BAMBOO_PREFIX . 'section_blog_related_post_limit';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW_NAVIGATION = RISING_BAMBOO_PREFIX . 'section_blog_related_post_show_navigation';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW_PAGINATION = RISING_BAMBOO_PREFIX . 'section_blog_related_post_show_pagination';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_AUTO_PLAY       = RISING_BAMBOO_PREFIX . 'section_blog_related_post_auto_play';
const RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_AUTO_PLAY_SPEED = RISING_BAMBOO_PREFIX . 'section_blog_related_post_auto_speed';
// </editor-fold>
// </editor-fold>
// <editor-fold desc="Post Navigation">
const RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION                = RISING_BAMBOO_PREFIX . 'section_component_post_navigation';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_GROUP_TITLE      = RISING_BAMBOO_PREFIX . 'component_post_navigation_group_title';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_SINGLE           = RISING_BAMBOO_PREFIX . 'component_post_navigation_single';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_PAGE             = RISING_BAMBOO_PREFIX . 'component_post_navigation_page';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_ATTACHMENT       = RISING_BAMBOO_PREFIX . 'component_post_navigation_attachment';
const RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_CUSTOM_POST_TYPE = RISING_BAMBOO_PREFIX . 'component_post_navigation_custom_post_type';
// </editor-fold>
// </editor-fold>
