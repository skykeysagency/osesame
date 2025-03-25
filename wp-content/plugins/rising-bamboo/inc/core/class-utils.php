<?php
/**
 * RisingBambooCore package.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Core;

use RisingBambooCore\App\App;

/**
 * Utilities.
 *
 * @package Rising_Bamboo
 */
class Utils {
	/**
	 * Deprecated range.
	 */
	public const DEPRECATION_RANGE = 0.4;

	/**
	 * Handle deprecation function.
	 *
	 * @param mixed  $item Item.
	 * @param string $version Version.
	 * @param mixed  $replacement New version.
	 * @return void
	 */
	public static function handle_deprecation( $item, $version, $replacement = null ): void {
		preg_match('/^\d+\.\d+/', App::get_version(), $current_version);

		$current_version_as_float = (float) $current_version[0];

		preg_match('/^\d+\.\d+/', $version, $alias_version);

		$alias_version_as_float = (float) $alias_version[0];

		if ( round($current_version_as_float - $alias_version_as_float, 1) >= self::DEPRECATION_RANGE ) {
			_deprecated_file($item, $version, $replacement); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}
