<?php
/**
 * View Loader
 * ----------------------
 * Allows for loading views, injecting vars into them, and
 * either displaying them instantly or returning the contents.
 *
 * @package   RisingBambooCore
 */

namespace RisingBambooTheme\Core;

use RisingBambooCore\Core\View as RisingBambooCoreView;
/**
 * View class.
 */
class View extends Singleton {
	/**
	 * Load a View from the filesystem
	 *
	 * @param string  $view View.
	 * @param array   $data View Data.
	 * @param boolean $return Return.
	 */
	public function load( string $view, array $data = [], bool $return = false ) {
		if ( class_exists(RisingBambooCoreView::class) ) {
			return RisingBambooCoreView::instance()->load($view, $data, $return);
		}
		
		$file = 'template-parts' . DIRECTORY_SEPARATOR . $view . '.php';
		ob_start();
		get_template_part($file);
		return ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
