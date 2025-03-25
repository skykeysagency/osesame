<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme.
 */

namespace RisingBambooTheme\Helper;

use Elementor\Plugin as ElementorPlugin;
use RisingBambooCore\App\Admin\RbbIcons;
use WPCF7;
use WooCommerce;
use WPCleverWoosc;
use WPCleverWoosw;

/**
 * Helper Class.
 */
class Helper {
	
	/**
	 * Check Kirki is Activated.
	 *
	 * @return bool
	 */
	public static function kirki_activated(): bool {
		return class_exists('Kirki');
	}
	
	/**
	 * Check elementor is activated.
	 *
	 * @return int|null
	 */
	public static function elementor_activated(): ?int {
		return did_action('elementor/loaded');
	}
	
	/**
	 * Check Jetpack is activated.
	 *
	 * @return bool
	 */
	public static function jetpack_activated(): bool {
		return defined('JETPACK__VERSION');
	}
	
	/**
	 * Check Woocommerce is activated.
	 *
	 * @return bool
	 */
	public static function woocommerce_activated(): bool {
		return class_exists(WooCommerce::class);
	}
	
	/**
	 * Check Wishlist is activated.
	 *
	 * @return bool
	 */
	public static function woocommerce_wishlist_activated(): bool {
		return class_exists(WPCleverWoosw::class);
	}
	
	/**
	 * Check Compare is activated.
	 *
	 * @return bool
	 */
	public static function woocommerce_compare_activated(): bool {
		return class_exists(WPCleverWoosc::class);
	}

	/**
	 * List variable for on state.
	 *
	 * @var array
	 */
	private static array $on = [ true, 'on', '1', 1 ];

	/**
	 * Check wishlist config.
	 *
	 * @return bool
	 */
	public static function show_wishlist(): bool {
		return self::woocommerce_wishlist_activated() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_WISH_LIST), self::$on, true);
	}

	/**
	 * Check search form config.
	 *
	 * @return bool
	 */
	public static function show_search_product_form(): bool {
		return self::woocommerce_activated() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM), self::$on, true);
	}

	/**
	 * Check search form config mobile.
	 *
	 * @return bool
	 */
	public static function show_search_product_form_mobile(): bool {
		return self::woocommerce_activated() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM_MOBILE), self::$on, true);
	}
	
	/**
	 * Get popular keyword.
	 *
	 * @return array|false|string[]
	 */
	public static function get_popular_keyword() {
		$keywords = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_POPULAR_KEYWORD);
		return ! empty($keywords) ? preg_split('/[ \n,]+/', $keywords) : [];
	}

	/**
	 * Get popular keyword custom fields.
	 *
	 * @return array|false|string[]
	 */
	public static function get_popular_keyword_custom_fields() {
		$keywords = Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_PRODUCT_ITEM_SHOW_CUSTOM_FIELDS_KEYWORD);
		return ! empty($keywords) ? preg_split('/[ \n,]+/', $keywords) : [];
	}

	/**
	 * Check minicart config.
	 *
	 * @return bool
	 */
	public static function show_mini_cart(): bool {
		return self::woocommerce_activated() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_MINI_CART), self::$on, true);
	}

	/**
	 * Check login form config.
	 *
	 * @return bool
	 */
	public static function show_login_form(): bool {
		return in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_LOGIN_FORM), self::$on, true);
	}

	/**
	 * Check logo config.
	 *
	 * @return bool
	 */
	public static function show_logo(): bool {
		return in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LOGO_STATUS), self::$on, true);
	}

	/**
	 * Check page title config.
	 *
	 * @return bool
	 */
	public static function show_page_title(): bool {
		$status = in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_TITLE), self::$on, true);
		return apply_filters('rbb_show_page_title', $status); //phpcs:ignore
	}

	/**
	 * Check breadcrumb config.
	 *
	 * @return bool
	 */
	public static function show_breadcrumb(): bool {
		$status = in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB), self::$on, true);
		return apply_filters('rbb_show_breadcrumb', $status); //phpcs:ignore
	}


	/**
	 * Get Rbb Icons.
	 *
	 * @param null $type Type of icon.
	 * @return array|null
	 */
	public static function get_rbb_icons( $type = null ): ?array {
		$result = [];
		$type   = (array) $type;
		if ( class_exists(RbbIcons::class) ) {
			if ( ! empty($type) ) {
				foreach ( (array) $type as $t ) {
					if ( isset(RbbIcons::get_rbb_icons()[ $t ]) ) {
						foreach ( RbbIcons::get_rbb_icons()[ $t ] as $icon ) {
							if ( ! in_array($icon['name'], $result, true) ) {
								$result[] = $icon['name'];
							}
						}
					}
				}
			} else {
				$icons_group = array_values(RbbIcons::get_rbb_icons());
				foreach ( $icons_group as $group => $icons ) {
					foreach ( $icons as $icon ) {
						if ( ! in_array($icon['name'], $result, true) ) {
							$result[] = $icon['name'];
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	 * Get icon content.
	 *
	 * @param string|null $icon Name of icons.
	 * @return string
	 */
	public static function get_rbb_icon_content( ?string $icon ): string {
		$result = "\e900";
		if ( class_exists(RbbIcons::class) && ! is_null($icon) ) {
			$result = RbbIcons::get_rbb_icon_content($icon);
		}
		return $result;
	}

	/**
	 * Show/hide post navigation.
	 *
	 * @return bool
	 */
	public static function show_post_navigation(): bool {
		$meta_key = defined('RBB_CORE_META_FIELD_COMPONENT_POST_NAVIGATION') ? RBB_CORE_META_FIELD_COMPONENT_POST_NAVIGATION : null;
		return ( is_page() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_PAGE, $meta_key), [ 'on', true ], true) ) ||
			( is_single() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_SINGLE, $meta_key), [ 'on', true ], true) ) ||
			( is_attachment() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_ATTACHMENT, $meta_key), [ 'on', true ], true) ) ||
			( ! is_page() && ! is_single() && ! is_attachment() && in_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_CUSTOM_POST_TYPE, $meta_key), [ 'on', true ], true) );
	}

	/**
	 * Get icons.
	 *
	 * @param string $item Item.
	 * @param string $class Class.
	 * @return string
	 */
	public static function woo_get_account_menu_item_icon( string $item, string $class = '' ): string {
		switch ( $item ) {
			case 'dashboard':
				$key = RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DASHBOARD_ICON;
				break;
			case 'orders':
				$key = RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_ORDERS_ICON;
				break;
			case 'downloads':
				$key = RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DOWNLOADS_ICON;
				break;
			case 'edit-address':
				$key = RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_ADDRESS_ICON;
				break;
			case 'edit-account':
				$key = RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_DETAIL_ICON;
				break;
			case 'customer-logout':
				$key = RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_ACCOUNT_LOGOUT_ICON;
				break;
		}
		return isset($key) ? '<i class="' . $class . ' ' . Setting::get($key) . '"></i>' : '';
	}

	/**
	 * Check ask a question.
	 *
	 * @return bool
	 */
	public static function check_ask_question(): bool {
		return ( class_exists(WPCF7::class) && Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_SHOW) && Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_CONTACT_FORM) );
	}

	/**
	 * Get contact Form 7 List.
	 *
	 * @return array
	 */
	public static function get_contact_form_7_list(): array {
		$args           = [
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => -1, //phpcs:ignore
		];
		$contact_form_7 = get_posts($args);
		return wp_list_pluck($contact_form_7, 'post_title', 'ID');
	}
	
	/**
	 * Remove filter helper.
	 *
	 * @param mixed $hook The hook.
	 * @param mixed $callback The callback.
	 * @param int   $priority The Priority.
	 * @return mixed
	 */
	public static function remove_f( $hook, $callback, int $priority = 10 ) {
		$func_name = str_replace('_bc_', '_', 'remove_bc_filter');
		return $func_name($hook, $callback, $priority);
	}
	
	/**
	 * Check post is build with elementor.
	 *
	 * @param mixed $post_id Post ID.
	 * @return bool
	 */
	public static function is_built_with_elementor( $post_id ): bool {
		if ( self::elementor_activated() ) {
			$elementor_post = ElementorPlugin::$instance->documents->get($post_id);
			if ( $elementor_post ) {
				return $elementor_post->is_built_with_elementor();
			}
		}
		return false;
	}
}
