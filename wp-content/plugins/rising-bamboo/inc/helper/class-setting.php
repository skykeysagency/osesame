<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Helper;

/**
 * Setting Helper.
 */
class Setting {

	/**
	 * Register settings.
	 *
	 * @param string $option_group  Group.
	 * @param string $option_name   Name.
	 * @param array  $args   Args.
	 */
	public static function register_setting( string $option_group, string $option_name, array $args = [] ): void {
		register_setting(
			RBB_CORE_SETTING_PREFIX . '_' . $option_group,
			RBB_CORE_SETTING_PREFIX . '_' . $option_name,
			$args
		);
	}

	/**
	 * Get option.
	 *
	 * @param string $option Option.
	 * @param mixed  $default Default.
	 * @return false|mixed|void
	 */
	public static function get_option( string $option, $default = false ) {
		return get_option(RBB_CORE_SETTING_PREFIX . '_' . $option, $default);
	}

	/**
	 * Update Option.
	 *
	 * @param string $option Option.
	 * @param mixed  $value Value.
	 * @param mixed  $autoload Autoload.
	 * @return bool
	 */
	public static function update_option( string $option, $value, $autoload = null ): bool {
		return update_option(RBB_CORE_SETTING_PREFIX . '_' . $option, $value, $autoload);
	}
}
