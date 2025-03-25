<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use OCDI_Plugin;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\License;

/**
 * Register plugin menu in WordPress admin.
 *
 * @package RisingBamboo
 */
class Importer extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		if ( class_exists(OCDI_Plugin::class) ) {
			add_filter('ocdi/plugin_page_setup', [ $this, 'page_setup' ]);
			add_filter('ocdi/plugin_page_title', [ $this, 'page_title' ]);
			add_filter('ocdi/plugin_intro_text', [ $this, 'intro_text' ]);
			add_filter('ocdi/register_plugins', [ $this, 'register_plugins' ]);
			add_filter('ocdi/import_files', [ $this, 'import_files' ]);
			add_filter('ocdi/plugin_page_display_callback_function', [ $this, 'check_license' ]);
			add_filter('ocdi/plugin_page_display_callback_function', [ $this, 'check_theme_setup_completed' ]);
			add_filter('ocdi/time_for_one_ajax_call', [ $this, 'time_of_single_ajax_call' ]);
		}
	}

	/**
	 * Check License for import data.
	 *
	 * @param mixed $data Data.
	 * @return array
	 */
	public function check_license( $data ): array {
		if ( License::is_activated() ) {
			return $data;
		}
		return [ $this, 'check_license_display' ];
	}

	/**
	 * Alert.
	 *
	 * @return void
	 */
	public function check_license_display(): void {
		View::instance()->load('admin/pages/error', [ 'message' => __('To import data, you must activate the theme license!', App::get_domain()) ]);
	}

	/**
	 * Check Theme setup completed before import data.
	 *
	 * @param mixed $data Data.
	 * @return array
	 */
	public function check_theme_setup_completed( $data ): array {
		$current_theme      = wp_get_theme();
		$current_theme_slug = strtolower(preg_replace('#[^a-zA-Z]#', '', $current_theme->template));
		$already_setup      = get_option('merlin_' . $current_theme_slug . '_completed');
		if ( $already_setup ) {
			return $data;
		}
		return [ $this, 'check_theme_setup_display' ];
	}

	/**
	 * Alert.
	 *
	 * @return void
	 */
	public function check_theme_setup_display(): void {
		if ( current_user_can('install_themes') ) {
			$setup_url = admin_url() . 'themes.php?page=rbb-wizard';
		} else {
			$setup_url = '#';
		}
		/* translators: 1: setup url. */
		View::instance()->load('admin/pages/error', [ 'message' => sprintf(__('You\'ll need to complete the <a style="color:#135e96" href="%1$s">theme setup</a> process first ( or click to Skip button ) before you can import the demo.', App::get_domain()), $setup_url) ]);
	}

	/**
	 * Add Demo Import to Rbb Menu.
	 *
	 * @param array $default_settings Default Settings.
	 * @return array
	 */
	public function page_setup( array $default_settings ): array {
		$default_settings['parent_slug'] = 'rbb-core';
		$default_settings['page_title']  = esc_html__('Demo Import', App::get_domain());
		$default_settings['menu_title']  = esc_html__('Import Demo Data', App::get_domain());
		$default_settings['capability']  = 'import';
		$default_settings['menu_slug']   = 'one-click-demo-import';

		return $default_settings;
	}

	/**
	 * Register Plugins.
	 *
	 * @param mixed $plugins Plugins.
	 * @return array
	 */
	public function register_plugins( $plugins ): array {
		$theme_plugins = [
			[ // A WordPress.org plugin repository example.
				'name'     => 'Advanced Custom Fields', // Name of the plugin.
				'slug'     => 'advanced-custom-fields', // Plugin slug - the same as on WordPress.org plugin repository.
				'required' => true,                     // If the plugin is required or not.
			],
		];

		return array_merge($plugins, $theme_plugins);
	}

	/**
	 * Load Import files config.
	 *
	 * @return mixed|void
	 */
	public function import_files() {
		$import_config_file = realpath(trailingslashit(get_template_directory()) . 'inc/config/theme-demo-import.php');
		if ( $import_config_file ) {
            // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			return require $import_config_file;
		}
		return [];
	}

	/**
	 * Override OCDI title.
	 *
	 * @param mixed $plugin_title Title.
	 * @return string
	 */
	public function page_title( $plugin_title ): string {
		ob_start();
		View::instance()->load(
			'admin/plugins/ocdi/page-title'
		);
		return ob_get_clean();
	}

	/**
	 * Override Intro text.
	 *
	 * @return string
	 */
	public function intro_text(): string {
		return '';
	}

	/**
	 * This will “slice” the requests to smaller chunks, and it might bypass the low server settings (timeouts and memory per request).
	 *
	 * @return int
	 */
	public function time_of_single_ajax_call(): int {
		return 10;
	}
}
