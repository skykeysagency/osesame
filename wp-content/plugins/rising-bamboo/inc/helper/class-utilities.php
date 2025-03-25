<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Helper;

use RisingBambooCore\Core\Singleton;

/**
 * Setting Helper.
 */
class Utilities extends Singleton {

	/**
	 * Convert Camel to dashed.
	 *
	 * @param string $string Camel String.
	 * @return string
	 */
	public static function camel_2_dashed( string $string ): string {
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $string));
	}
}
