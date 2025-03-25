<?php
/**
 * View Loader
 * ----------------------
 * Allows for loading views, injecting vars into them, and
 * either displaying them instantly or returning the contents.
 *
 * @package   RisingBambooCore
 */

namespace RisingBambooCore\Core;

use Exception;
use RuntimeException;

/**
 * View class.
 */
class View extends Singleton {

	/**
	 * List of paths to load views from.
	 * Internal loader selects the first path with file that exists.
	 * Paths that are loaded with add_path are prepended to the array.
	 *
	 * @var array $view_paths
	 */
	protected array $view_paths = [];

	/**
	 * View data
	 *
	 * @var array
	 */
	protected array $view_data = [];

	/**
	 * Load a View from the filesystem
	 *
	 * @param string  $view View.
	 * @param array   $data View Data.
	 * @param boolean $return Return.
	 * @return false|string|null
	 * @throws Exception Ex.
	 */
	public function load( string $view, array $data = [], bool $return = false ) {
		return $this->_load(
			[
				'_view'   => $view,
				'_vars'   => $this->object_to_array($data),
				'_return' => $return,
			]
		);
	}

	/**
	 * Set variable.
	 *
	 * @param mixed  $vars Variables.
	 * @param string $val Value.
	 */
	public function set_vars( $vars = [], string $val = '' ): void {
		// If string and supplied for $vars.
		if ( is_string($vars) ) {
			$vars = [ $vars => $val ];
		}

		// If object supplied for $vars, convert to array.
		if ( is_object($vars) ) {
			$vars = $this->object_to_array($vars);
		}

		// If array supplied for $vars and array not empty.
		if ( is_array($vars) && count($vars) > 0 ) {
			foreach ( $vars as $key => $v ) {
				$this->view_data[ $key ] = $v;
			}
		}
	}

	/**
	 * Add View Path
	 *
	 * @param string $path Path.
	 * @return void
	 */
	public function add_path( string $path ): void {
		$this->set_default_paths();
		// Prepend the paths array with the new path.
		$path = realpath($path) . DIRECTORY_SEPARATOR;
		if ( ! in_array($path, $this->view_paths, true) ) {
			array_unshift($this->view_paths, $path);
		}
	}

	/**
	 * Set the default paths. First is app/views, second is vendor/isolated/views.
	 */
	protected function set_default_paths(): void {
		// The default view directories always need to be loaded first.
		if ( empty($this->view_paths) ) {
			$this->view_paths = [
				get_template_directory() . DIRECTORY_SEPARATOR . 'template-parts' . DIRECTORY_SEPARATOR,
				RBB_CORE_PATH . 'views' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR,
				RBB_CORE_PATH . 'views' . DIRECTORY_SEPARATOR,
			];
		}
	}

	/**
	 * Load view.
	 *
	 * @param mixed $_data Data.
	 * @return false|string|void
	 * @throws Exception Exception.
	 */
	protected function _load( $_data ) { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->set_default_paths();

		[$_view, $_vars, $_return] = $this->process_load_data($_data);

		// Add a file extension the view.
		$_file = $_view . '.php';

		// Get the view path.
		$view_path = $this->get_view_path($_file);

		// Display error if view not found.
		if ( ! $view_path ) {
			$this->view_not_found_error($_file);
		}

		// Combine view variables.
		if ( is_array($_vars) ) {
			$this->view_data = array_merge($this->view_data, $_vars);
		}

		// Make view variables available in included view.
		extract($this->view_data, EXTR_OVERWRITE); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		// Start output buffering if returning view.
		if ( true === $_return ) {
			ob_start();
		}

		// Include view.
		include $view_path; //phpcs:ignore

		// Return processed view if requested.
		if ( true === $_return ) {
			return ob_get_clean();
		}
	}

	/**
	 * Object to Array
	 *
	 * Takes an object as input and converts the class variables to array key/val
	 *
	 * @param mixed $object Object.
	 * @return array
	 */
	protected function object_to_array( $object ): array {
		return ( is_object($object) )
			? get_object_vars($object)
			: $object;
	}

	/**
	 * Explode data for use in _load method
	 *
	 * @param mixed $data Data.
	 * @return array
	 */
	protected function process_load_data( $data ): array {
		foreach ( [ '_view', '_vars', '_return' ] as $val ) {
			$$val = $data[ $val ] ?? false;
		}
		return [ $_view, $_vars, $_return ];
	}

	/**
	 * Get the view path.
	 *
	 * @param string $file File.
	 * @return false|string
	 */
	protected function get_view_path( string $file ) {
		foreach ( $this->view_paths as $view_dir ) {
			if ( file_exists($view_dir . $file) ) {
				return $view_dir . $file;
			}
		}
		return false;
	}

	/**
	 * Display error when no view found.
	 *
	 * @param string $_file File.
	 * @throws RuntimeException Exception.
	 */
	protected function view_not_found_error( string $_file ): void {
		$err_text = PHP_EOL .
			'View file "' . $_file . '" not found.' . PHP_EOL .
			'Directories checked: ' . PHP_EOL .
			'[' . implode('],' . PHP_EOL . '[', $this->view_paths) . ']' . PHP_EOL;
        // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
		throw new RuntimeException($err_text);
	}

	/**
	 * Get view data for debugging.
	 */
	public function get_view_data(): array {
		return $this->view_data;
	}
}
