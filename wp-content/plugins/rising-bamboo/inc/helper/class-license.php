<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\Helper;

/**
 * Helper Class.
 */
class License {

	/**
	 * Check Theme is active
	 *
	 * @param string|null $theme Theme stylesheet.
	 * @return array|false
	 */
	public static function is_activated( string $theme = null ) {
		$license = get_option(self::get_license_option_name($theme));
		if ( $license ) {
			if ( empty($license['expired']) || ! self::is_expired($license['expired']) ) {
				return $license;
			}
		}
		return false;
	}

	/**
	 * Check expired.
	 *
	 * @param string $expired_date Expired Date.
	 * @return bool
	 */
	public static function is_expired( string $expired_date ): bool {
		$current = current_datetime();
		$expired = self::create_date_with_timezone($expired_date);
		return $current > $expired;
	}

	/**
	 * Format Date.
	 *
	 * @param string $date Date.
	 * @return \DateTimeImmutable|false
	 */
	public static function create_date_with_timezone( string $date ) {
		return \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s+', $date)->setTimezone(wp_timezone());
	}

	/**
	 * Get API config
	 */
	public static function get_api_config(): array {
		$api_config            = [];
		$current_theme         = wp_get_theme();
		$api_theme_config_path = realpath(untrailingslashit($current_theme->get_template_directory()) . '/inc/config/api.php');
		if ( $api_theme_config_path ) {
			$api_theme_config = require $api_theme_config_path;
			$api_config       = wp_parse_args($api_theme_config, $api_config);
		}
		if ( Setting::get_option('development_mode') ) {
			$api_mock_path = realpath(untrailingslashit($current_theme->get_template_directory()) . '/mock/api.php');
			if ( $api_mock_path ) {
				$api_mock   = require $api_mock_path;
				$api_config = wp_parse_args($api_mock, $api_config);
			}
		}
		return $api_config;
	}

	/**
	 * Get license option name.
	 *
	 * @param null $theme Directory name for the theme. Defaults to active theme.
	 * @return string
	 */
	public static function get_license_option_name( $theme = null ): string {
		if ( ! $theme ) {
			$theme = wp_get_theme()->stylesheet;
		}
		return strtolower('rbb-' . $theme . '-license');
	}
}
