<?php
/**
 * RisingBambooThem Package.
 *
 * @package RisingBammBooTheme.
 */

namespace RisingBambooTheme\Helper;

use RisingBambooTheme\Core\Singleton;
use RisingBambooCore\Helper\Helper as RbbCoreHelper;
use RisingBambooCore\Helper\Setting as RbbCoreSettingHelper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;

/**
 * Setting Class.
 */
class Setting extends Singleton {
	/**
	 * Default value loaded from config file.
	 *
	 * @var array Default Value Kirki.
	 */
	protected static array $kirki_default_value = [];
	/**
	 * Override Data.
	 *
	 * @var array
	 */
	public static array $override_data = [];

	/**
	 * Construction.
	 */
	public function __construct() {
		$this->set_override_data();
		if ( ! class_exists(RisingBambooKirki::class) ) {
			self::$kirki_default_value = require RBB_THEME_CONFIG_PATH . 'theme-customizer-default.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 * Get key.
	 *
	 * @param string $key Key.
	 * @param null   $meta_key Meta key.
	 * @return mixed|string
	 */
	public static function get( string $key, $meta_key = null ) {
		return self::instance()->get_option($key, $meta_key);
	}

	/**
	 * Get key.
	 *
	 * @param string $key Key.
	 * @param null   $meta_key Meta key.
	 * @return mixed|string
	 */
	protected function get_option( string $key, $meta_key = null ) {
		$params = self::$override_data;
		if ( ! empty($params) ) {
			$_key = str_replace(RISING_BAMBOO_PREFIX, '', $key);
			if ( isset($params[ $_key ]) ) {
				return $params[ $_key ];
			}
		}
		if ( class_exists(RisingBambooKirki::class) ) {
			$value = RisingBambooKirki::get_option(RISING_BAMBOO_KIRKI_CONFIG, $key);
		} else {
			$value = $this->get_default_value($key);
		}
		
		return get_post_meta(self::get_id(), ( ! empty($meta_key) ? $meta_key : $key ), true) ?: $value; // phpcs:ignore WordPress.PHP.DisallowShortTernary.Found
	}
	
	/**
	 * Get default value from config file.
	 *
	 * @param string $key The key.
	 * @return mixed
	 */
	protected function get_default_value( string $key ) {
		return self::$kirki_default_value[ $key ]['default'] ?? '';
	}

	/**
	 * Get Id.
	 *
	 * @return false|int|mixed|void
	 */
	public static function get_id() {
		if ( is_home() ) {
			$id = get_option('page_for_posts');
		} elseif ( function_exists('is_shop') && is_shop() ) {
			$id = wc_get_page_id('shop');
		} else {
			$id = get_the_ID();
		}
		return $id;
	}

	/**
	 * Set override data.
	 *
	 * @return void
	 */
	protected function set_override_data(): void {
		if ( class_exists(RbbCoreSettingHelper::class) && empty(self::$override_data) && RbbCoreSettingHelper::get_option('development_override_settings') ) {
			$query_string = RbbCoreHelper::get_query_string();
			parse_str($query_string, $params);
			if ( isset($params['override_profile']) ) {
				$profile = realpath(RBB_THEME_PATH . 'mock/profiles/' . $params['override_profile'] . '.json');
				if ( $profile ) {
					$params = wp_parse_args($params, wp_json_file_decode($profile, [ 'associative' => true ]));
				}
				unset($params['override_profile']);
			}
			self::$override_data = $params;
		}
	}
}
