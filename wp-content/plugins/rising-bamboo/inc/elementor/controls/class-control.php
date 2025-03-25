<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Controls;

use Elementor\Plugin;

/**
 * Control Class.
 */
class Control {
	/**
	 * Select2 Control.
	 *
	 * @const SELECT2
	 */
	public const SELECT2 = 'rbb-select2';

	/**
	 * Register Controls.
	 *
	 * @return void
	 */
	public static function register(): void {
		Plugin::$instance->controls_manager->register(new Select2());
	}
}
