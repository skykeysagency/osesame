<?php
/**
 * Rising Bamboo functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\PluginRegister;
use RisingBambooTheme\Core\Autoloader;
use RisingBambooTheme\App\App;
use RisingBambooTheme\App\ThemeSetup;

define('RBB_THEME_PATH', trailingslashit(get_template_directory()));
define('RBB_THEME_INC_PATH', trailingslashit(RBB_THEME_PATH . 'inc'));
define('RBB_THEME_DIST_PATH', trailingslashit(RBB_THEME_PATH . 'dist'));
define('RBB_THEME_CONFIG_PATH', trailingslashit(RBB_THEME_INC_PATH . 'config'));

define('RBB_THEME_URL', get_template_directory_uri());
define('RBB_THEME_DIST_URI', trailingslashit(RBB_THEME_URL . '/dist/'));

/**
 * TGM Plugin Activation
 */
// phpcs:disable
require_once RBB_THEME_INC_PATH . 'tgm/class-tgm-plugin-activation.php';
require_once RBB_THEME_INC_PATH . 'app/class-plugin-register.php';
// phpcs:enable
new PluginRegister();


/**
 * Theme setup.
 */
// phpcs:disable
require_once RBB_THEME_INC_PATH . 'merlin/class-merlin.php';
require_once RBB_THEME_INC_PATH . 'app/class-setup.php';
// phpcs:enable
new ThemeSetup();

/**
 * Load Autoloader;
 */
// phpcs:ignore
require_once RBB_THEME_INC_PATH . 'core/class-autoloader.php';
Autoloader::run('class-', 'RisingBambooTheme');


/**
 *  Theme Initial.
 */
App::instance();
