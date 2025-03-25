<?php
/**
 * RisingBambooCore Package
 *
 * @package RisingBambooCore
 */

namespace RisingBambooTheme\Core;

use RisingBambooCore\Core\Utils;

/**
 * RisingBamboo autoloader.
 *
 * RisingBamboo autoloader handler class is responsible for loading the different
 * classes needed to run the plugin.
 *
 * @since 1.0.0
 */
class Autoloader {

	/**
	 * Classes map.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var mixed Class Map.
	 */
	private static $classes_map;

	/**
	 * Classes aliases.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var mixed
	 */
	private static $classes_aliases;

	/**
	 * Config.
	 *
	 * @var array
	 */
	private static array $default_config;

	/**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 *
	 * @param string $file_prefix File prefix.
	 * @param string $default_namespace Default namespace.
	 * @param string $default_path Default path.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function run( string $file_prefix = '', string $default_namespace = __NAMESPACE__, string $default_path = RBB_THEME_INC_PATH ): void {
		self::$default_config[ $default_namespace ] = [
			'path'      => $default_path,
			'namespace' => $default_namespace,
			'prefix'    => $file_prefix,
		];

		spl_autoload_register([ __CLASS__, 'autoload' ]);
	}

	/**
	 * Autoload.
	 *
	 * For a given class, check if it exists and load it.
	 *
	 * @param string $class Class name.
	 * @since 1.0.0
	 * @access private
	 * @static
	 */
	private static function autoload( string $class ): void {
		$config = self::get_config($class);

		if ( empty($config) ) {
			return;
		}

		$relative_class_name = preg_replace('/^' . $config['namespace'] . '\\\\/', '', $class);

		$classes_aliases = self::get_classes_aliases($config['namespace']);

		$has_class_alias = isset($classes_aliases[ $relative_class_name ]);

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded.
		if ( $has_class_alias ) {
			$alias_data = $classes_aliases[ $relative_class_name ];

			$relative_class_name = $alias_data['replacement'];
		}

		$final_class_name = $config['namespace'] . '\\' . $relative_class_name;

		if ( ! class_exists($final_class_name) ) {
			self::load_class($relative_class_name, $config['namespace']);
		}

		if ( $has_class_alias ) {
			class_alias($final_class_name, $class);
			if ( class_exists(Utils::class) ) {
				Utils::handle_deprecation($class, $alias_data['version'], $final_class_name);
			}
		}
	}

	/**
	 * Get Class config.
	 *
	 * @param string $class Class.
	 * @return array|mixed
	 */
	public static function get_config( string $class ) {
		preg_match('/(\w*\\\{1,2})/', $class, $match);
		if ( empty($match) ) {
			return [];
		}
		$namespace = str_replace('\\', '', $match[0]);
		return self::$default_config[ $namespace ] ?? [];
	}

	/**
	 * Get classes aliases.
	 *
	 * Retrieve the classes aliases names.
	 *
	 * @param string $namespace Namespace.
	 * @return array Classes aliases.
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function get_classes_aliases( string $namespace ): array {
		if ( ! self::$classes_aliases ) {
			self::init_classes_aliases();
		}

		return self::$classes_aliases[ $namespace ] ?? [];
	}

	/**
	 * Init class alias.
	 *
	 * @return void
	 */
	private static function init_classes_aliases(): void {
		$_alias = [];
		foreach ( self::$default_config as $config ) {
			$_path = realpath($config['path'] . 'config/classes-alias.php');
			//phpcs:ignore
			$current_map = require $_path;
			$_alias      = wp_parse_args($current_map, $_alias);
		}
		self::$classes_aliases = $_alias;
	}

	/**
	 * Load class.
	 *
	 * For a given class name, require the class file.
	 *
	 * @param string $relative_class_name Class name.
	 * @param string $namespace Namespace.
	 * @since 1.0.0
	 * @access private
	 * @static
	 */
	private static function load_class( string $relative_class_name, string $namespace ): void {
		$classes_map = self::get_classes_map($namespace);
		$_path       = self::$default_config[ $namespace ]['path'];
		$_prefix     = self::$default_config[ $namespace ]['prefix'];
		if ( isset($classes_map[ $relative_class_name ]) ) {
			$filename = $_path . $classes_map[ $relative_class_name ];
		} else {
			$filename  = strtolower(
				preg_replace(
					[ '/([a-z])([A-Z])/', '/_/', '/\\\\/' ],
					[ '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$relative_class_name
				)
			);
			$path_info = pathinfo($filename);
			$filename  = $_path . $path_info['dirname'] . DIRECTORY_SEPARATOR . $_prefix . $path_info['filename'] . '.php';
		}
		if ( is_readable($filename) ) {
			//phpcs:ignore
			require_once $filename;
		}
	}

	/**
	 * Get class map.
	 *
	 * @param string $namespace Namespace.
	 * @return mixed
	 */
	public static function get_classes_map( string $namespace ) {
		if ( ! self::$classes_map ) {
			self::init_classes_map();
		}
		return self::$classes_map[ $namespace ] ?? [];
	}

	/**
	 * Init class map.
	 *
	 * @return void
	 */
	private static function init_classes_map(): void {
		$_map = [];
		foreach ( self::$default_config as $config ) {
			$_path = realpath($config['path'] . 'config/classes-map.php');
			//phpcs:ignore
			$current_map = require $_path;
			$_map        = wp_parse_args($current_map, $_map);
		}
		self::$classes_map = $_map;
	}
}
