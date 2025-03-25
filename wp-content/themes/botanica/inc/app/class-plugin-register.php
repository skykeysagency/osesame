<?php
/**
 * RisingBambooTheme Package.
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\App;

/**
 * Register Plugins.
 * Class PluginRegister
 *
 * @package RisingBambooTheme\App
 */
class PluginRegister {

	/**
	 * PluginRegister constructor.
	 */
	public function __construct() {
		add_action('tgmpa_register', [ $this, 'register_required_plugins' ], 15, 1);
	}

	/**
	 * Register Plugin.
	 */
	public function register_required_plugins(): void {
		$plugins = require RBB_THEME_INC_PATH . '/config/theme-plugin-required.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$plugins = apply_filters('rising_bamboo_tgmpa_plugin', $plugins);

		$config = [
			'id'           => 'tgmpa',         // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		];

		$config = apply_filters('rising_bamboo_tgmpa_plugin_config', $config);

		tgmpa($plugins, $config);
	}

}
