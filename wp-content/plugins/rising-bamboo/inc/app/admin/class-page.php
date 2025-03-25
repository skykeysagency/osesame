<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use Exception;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\License;

/**
 * Admin pages.
 *
 * @package Rising_Bamboo
 */
class Page extends Singleton {

	/**
	 * Getting Started Page.
	 *
	 * @throws Exception Exception.
	 */
	public static function getting_started(): void {
		$items = [];
		View::instance()->load(
			'admin/pages/getting-started',
			compact('items')
		);
	}

	/**
	 * Settings Page.
	 *
	 * @throws Exception Exception.
	 */
	public static function settings(): void {
		View::instance()->load('admin/pages/settings');
	}

	/**
	 * Theme license.
	 *
	 * @return void
	 */
	public static function license(): void {
        // phpcs:ignore
		if ( $api_config = License::get_api_config() ) {
			$current_theme = wp_get_theme();
			$key           = License::is_activated();
			if ( $key ) {
				View::instance()->load('admin/pages/license/deactivated', compact('current_theme', 'api_config', 'key'));
			} else {
				View::instance()->load('admin/pages/license/active', compact('current_theme', 'api_config'));
			}
		} else {
			$message = __('Missing API config file. Please <a href="mailto:support@risingbamboo.com">contact us</a> to fix it.', App::get_domain());
			View::instance()->load('admin/pages/error', compact('message'));
		}
	}
}
