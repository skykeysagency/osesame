<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Setting;

/**
 * DevMode Class.
 */
class DevMode extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('admin_bar_menu', [ $this, 'admin_bar_menu_dev_mode' ], 500);
	}

	/**
	 * Custom Admin menu bar.
	 *
	 * @return void
	 */
	public function admin_bar_menu_dev_mode(): void {
		if ( Setting::get_option('development_mode') ) {
			global $wp_admin_bar;
			$wp_admin_bar->add_node(
				[
					'parent' => 'top-secondary',
					'id'     => 'rbb-dev-mode',
					'title'  => '<div style="background-color: rgb(220 38 38); color: rgb(248 250 252); padding: 0 10px">' . __('RBb Debug mode !', App::get_domain()) . '</div>',
					'meta'   => [
						'class'    => 'admin-bar-rbb-dev',
						'tabindex' => -1,
					],
				]
			);
			$override_settings        = Setting::get_option('development_override_settings');
			$override_settings_status = '<i class="rbb-icon ' . ( $override_settings ? 'rbb-icon-check-3' : 'rbb-icon-close-1' ) . '"></i>';
			$wp_admin_bar->add_node(
				[
					'parent' => 'rbb-dev-mode',
					'id'     => 'rbb-dev-override-settings',
					'title'  => '<div>' . __('Override Settings ', App::get_domain()) . $override_settings_status . '</div>',
					'meta'   => [
						'class'    => 'admin-bar-rbb-dev-override-settings',
						'tabindex' => -1,
					],
				]
			);
		}
	}
}
