<?php
/**
Plugin Name: Rising Bamboo Core
Description: Core functions for Rising Bamboo theme
Author: Rising Bamboo
Author URI: https://risingbamboo.com
Version: 1.3.1
Plugin URI: https://wp.risingbamboo.com
Text Domain: rbb-core
Domain Path: /languages/
Requires PHP: 5.6
Requires at least: 4.0
WC requires at least: 3.0
WC tested up to: 9.1
 *
 * @package RisingBambooCore
 */

defined('ABSPATH') || exit;

/**
 * Constants.
 */
const RBB_CORE_PATH_FILE = __FILE__;
define('RBB_CORE_BASENAME', plugin_basename(RBB_CORE_PATH_FILE));
define('RBB_CORE_URL', plugin_dir_url(RBB_CORE_PATH_FILE));
define('RBB_CORE_PATH', plugin_dir_path(RBB_CORE_PATH_FILE));

const RBB_CORE_INC_DIR        = RBB_CORE_PATH . 'inc/';
const RBB_CORE_VIEW_DIR       = RBB_CORE_PATH . 'views/';
const RBB_CORE_VENDOR_PATH    = RBB_CORE_PATH . 'vendor/';
const RBB_CORE_ASSETS_PATH    = RBB_CORE_PATH . 'assets/';
const RBB_CORE_ASSETS_URL     = RBB_CORE_URL . 'assets/';
const RBB_CORE_DIST_PATH      = RBB_CORE_PATH . 'dist/';
const RBB_CORE_DIST_URL       = RBB_CORE_URL . 'dist/';
const RBB_CORE_SETTING_PREFIX = 'rbb_core';

/**
 * Vendor Autoload.
 */
require_once RBB_CORE_PATH . 'vendor/autoload.php';

if ( ( ! PHP_VERSION_ID ) >= 70400 ) {
	add_action('admin_notices', 'rbb_core_fail_php_version');
} elseif ( ! version_compare(get_bloginfo('version'), '5.3', '>=') ) {
	add_action('admin_notices', 'rbb_core_fail_wp_version');
} else {
	require RBB_CORE_INC_DIR . 'core/class-autoloader.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	RisingBambooCore\Core\Autoloader::run(RBB_CORE_INC_DIR, 'RisingBambooCore', 'class-');
	RisingBambooCore\App\App::instance();
}

/**
 * Rising Bamboo admin notice for minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @return void
 */
function rbb_core_fail_php_version() {
	/* translators: %s: PHP version */
	$message      = sprintf(esc_html__('Rising Bamboo requires PHP version %s+, plugin is currently NOT RUNNING.', 'rbb-core'), '7.4');
	$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
	echo wp_kses_post($html_message);
}

/**
 * Rising Bamboo admin notice for minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @return void
 */
function rbb_core_fail_wp_version() {
	// translators: %s: WordPress version.
	$message      = sprintf(esc_html__('Rising Bamboo Core requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'rbb-core'), '5.3');
	$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
	echo wp_kses_post($html_message);
}
