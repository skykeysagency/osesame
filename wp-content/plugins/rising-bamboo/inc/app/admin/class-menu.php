<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;

/**
 * Register plugin menu in WordPress admin.
 *
 * @package RisingBamboo
 */
class Menu extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('admin_menu', [ $this, 'rbb_admin_menu' ], 9); // add priority < 10 to fix bug : Sorry, you are not allowed to access this page.
	}

	/**
	 * Admin menu.
	 *
	 * @return void
	 */
	public function rbb_admin_menu(): void {
		$current = get_site_transient('update_themes');
		//phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_menu_page
		add_menu_page(
			'Rising Bamboo',
			'Rising Bamboo',
			'manage_options',
			'rbb-core',
			[ Page::instance(), 'getting_started' ],
			null,
			4
		);
		//phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
		add_submenu_page('rbb-core', 'Getting Started', 'Getting Started', 'manage_options', 'rbb-core', null, 0);
		//phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
		add_submenu_page('rbb-core', 'Theme License', 'Theme License', 'manage_options', 'rbb-core-license', [ Page::instance(), 'license' ], 1);
        //phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
		add_submenu_page('rbb-core', 'Settings', 'Settings', 'manage_options', 'rbb-core-settings', [ Page::instance(), 'settings' ], 2);
		//phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
		add_submenu_page('rbb-core', 'Customize', 'Customize', 'edit_theme_options', 'customize.php', null, 3);
	}
}
