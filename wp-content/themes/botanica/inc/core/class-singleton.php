<?php
/**
 * RisingBambooCore Package.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooTheme\Core;

/**
 * Singleton.
 */
abstract class Singleton {

	/**
	 * Instance variable.
	 *
	 * @var array|null
	 */
	protected static ?array $instance = null;

	/**
	 * Init instance.
	 *
	 * @return Singleton|null
	 */
	public static function instance(): ?Singleton {
		$class = static::class;
		if ( ! isset(self::$instance[ $class ]) ) {
			self::$instance[ $class ] = new static();
		}
		return self::$instance[ $class ];
	}

	/**
	 *  Do not clone the object
	 */
	protected function __clone() {}

	/**
	 *  Do not allow serialization of this object
	 */
	public function __wakeup() {}
}
