<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App;

use RisingBambooCore\App\Admin\CMB2;
use RisingBambooCore\App\Admin\Cron;
use RisingBambooCore\App\Admin\DevMode;
use RisingBambooCore\App\Admin\FooterPostType;
use RisingBambooCore\App\Admin\Importer;
use RisingBambooCore\App\Admin\MegaMenu;
use RisingBambooCore\App\Admin\Menu;
use RisingBambooCore\App\Admin\NavMenuItemExtend;
use RisingBambooCore\App\Admin\RbbIcons;
use RisingBambooCore\App\Admin\Settings;
use RisingBambooCore\App\Admin\TestimonialPostType;
use RisingBambooCore\App\Admin\ThemeLicense;
use RisingBambooCore\App\Admin\Update;
use RisingBambooCore\App\Frontend\QuickView;
use RisingBambooCore\App\Frontend\ShortCode;
use RisingBambooCore\App\Frontend\Tracking;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Elementor\Elementor;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Theme;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooCore\Widgets\Widget;
use RisingBambooCore\Woocommerce\Woocommerce;

if ( ! function_exists('get_plugin_data') ) {
    //phpcs:ignore
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * The main plugin handler class is responsible for initializing Rising Bamboo Plugin.
 *
 * @package Rising_Bamboo
 */
class App extends Singleton {


	/**
	 * Action name of nonce.
	 */
	protected const RBB_NONE = 'rbb_nonce';
	/**
	 * Info.
	 *
	 * @var array
	 */
	protected static array $info = [];
	/**
	 * Global variable for js.
	 *
	 * @var array
	 */
	protected array $rbb_vars = [];

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->required_plugin_register();
		$this->set_info();
		$this->set_js_rbb_vars_default();
		$this->initialize();
	}

	/**
	 * Register TGMA Plugin required.
	 */
	public function required_plugin_register(): void {
		add_filter('rising_bamboo_tgmpa_plugin', [ $this, 'tgmpa_plugin' ]);
		add_filter('rising_bamboo_tgmpa_plugin_config', [ $this, 'tgmpa_plugin_config' ]);
	}

	/**
	 * Set Info.
	 */
	public function set_info(): void {
		// Plugin Info.
		$plg_info   = get_plugin_data(RBB_CORE_PATH . 'rising-bamboo' . '.' . 'php', true, false); // phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
		self::$info = $plg_info;
		// Extra Info.
		$extra_info_path = realpath(RBB_CORE_INC_DIR . 'config/extra-data.php');

		if ( $extra_info_path ) {
			$extra_info = require $extra_info_path;
			self::$info = wp_parse_args($extra_info, self::$info);
		}
		// Theme for override extra-data.
		$current_theme     = wp_get_theme();
		$theme_config_path = realpath(untrailingslashit($current_theme->get_template_directory()) . '/inc/config/extra-data.php');

		if ( $theme_config_path ) {
			$theme_config = require $theme_config_path;
			self::$info   = wp_parse_args($theme_config, self::$info);
		}
		// API.
		$api_path = realpath(RBB_CORE_INC_DIR . 'config/api.php');

		if ( $api_path ) {
			$api        = require $api_path;
			self::$info = wp_parse_args($api, self::$info);
		}
	}

	/**
	 * Get Information.
	 *
	 * @param mixed $key The Key of array.
	 * @param mixed $default The default value.
	 * @return mixed|null
	 */
	public static function get_info( $key, $default = null ) {
		return self::$info[ $key ] ?? $default;
	}

	/**
	 * Set global variable for js.
	 *
	 * @return void
	 */
	protected function set_js_rbb_vars_default(): void {
		$this->rbb_vars = [
			'ajax_url' => admin_url('admin-ajax.php'),
		];
	}

	/**
	 * Register autoloader.
	 *
	 * Autoloader loads all the classes needed to run the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	public function initialize(): void {

		add_action('init', [ $this, 'load_text_domain' ], 99);

		CMB2::instance();

		Menu::instance();

		Settings::instance();

		NavMenuItemExtend::instance();

		ThemeLicense::instance();

		Update::instance();

		Cron::instance();

		RisingBambooKirki::instance();

		RbbIcons::instance();

		Elementor::instance();

		FooterPostType::instance();

		TestimonialPostType::instance();

		Woocommerce::instance();

		ShortCode::instance()->register();

		Widget::instance();

		Importer::instance();

		DevMode::instance();

		add_action('plugin_action_links_' . RBB_CORE_BASENAME, [ $this, 'plugin_settings_link' ], 10);

		add_filter('widget_text', 'do_shortcode');

		add_action('after_setup_theme', [ $this, 'after_setup_theme' ], 12);

		add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ]);

		add_action('wp_enqueue_scripts', [ $this, 'frontend_enqueue_scripts' ]);

		add_action('deleted_theme', [ $this, 'delete_merlin_completed' ], 10, 2);
	}

	/**
	 * Load Text Domain.
	 */
	public function load_text_domain(): void {
		load_plugin_textdomain(self::get_domain(), false, RBB_CORE_PATH . 'languages/');
	}

	/**
	 * Get Text Domain.
	 *
	 * @return mixed|string
	 */
	public static function get_domain() {
		return self::$info['TextDomain'] ?: 'rbb-core';
	}

	/**
	 * Add a settings link to the plugin page.
	 *
	 * @param mixed $links Links.
	 * @return mixed
	 */
	public function plugin_settings_link( $links ) {
		$label = esc_html__('Settings', self::get_domain());
		$slug  = 'rbb-core-settings';
		array_unshift($links, "<a href='admin.php?page=$slug'>$label</a>");
		return $links;
	}

	/**
	 * Do something after setup theme.
	 */
	public function after_setup_theme(): void {
		self::$info = apply_filters('rbb_core_info', self::$info);
		if ( self::theme_support('rbb-mega-menu') ) {
			MegaMenu::instance();
		}
		if ( self::theme_support('rbb-quick-view') ) {
			QuickView::instance();
		}
	}

	/**
	 * Check theme support.
	 *
	 * @param string $feature Feature.
	 * @return bool
	 */
	public static function theme_support( string $feature = 'rbb-core' ): bool {
		return current_theme_supports($feature);
	}

	/**
	 * Admin enqueue scripts.
	 *
	 * @param mixed $hook Hook name.
	 */
	public function admin_enqueue_scripts( $hook ): void {
		if ( ! wp_style_is('rbb-icons') ) {
			wp_enqueue_style('rbb-icons', RBB_CORE_URL . 'dist/css/rbb-icons.css', [], self::get_rbb_icons_version());
		}
		if ( is_customize_preview() ) {
			wp_enqueue_style('rbb-customizer', RBB_CORE_URL . 'dist/css/customizer.css', [], self::get_version());
		}
		if ( strpos($hook, 'rising-bamboo_page') !== false || strpos($hook, 'rbb-core') !== false ) {
			wp_enqueue_style('rbb-core', RBB_CORE_URL . 'dist/css/rising-bamboo-core.css', [ 'wp-components' ], self::get_version());
			wp_enqueue_script('rbb-core-js', RBB_CORE_URL . 'dist/js/admin/rising-bamboo-core.js', [ 'jquery', 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ], self::get_version(), true);
			wp_localize_script(
				'rbb-core-js',
				'rbb_vars',
				array_merge(
					$this->rbb_vars,
					[
						'nonce'       => wp_create_nonce(self::get_nonce()),
						'text_domain' => self::get_domain(),
						'info'        => [
							/* translators: 1:Name of Theme, 2:Version */
							'welcome'      => sprintf(esc_html__('Welcome to %1$s %2$s', 'rbb-core'), self::$info['Name'], self::$info['Version']),
							/* translators: %s: link support */
							'support'      => sprintf(__('<a href="%s">Support</a>', self::get_domain()), self::get_info('support')),
							'support_link' => self::get_info('support'),
							/* translators: %s: link docs */
							'docs'         => sprintf(__('<a href="%s">Document</a>', self::get_domain()), self::get_info('docs')),
							'docs_link'    => self::get_info('docs'),
							/* translators: %s: link faq */
							'faqs'         => sprintf(__('<a href="%s">FAQs</a>', self::get_domain()), self::get_info('faqs')),
							'faqs_link'    => self::get_info('faqs'),
							'logo'         => RBB_CORE_ASSETS_URL . 'images/logo.svg',
						],
						'rtl'         => is_rtl(),
					]
				)
			);
			if ( preg_match('/rbb-core$/', $hook) ) {
				wp_enqueue_script('rbb-core-js-getting', RBB_CORE_URL . 'dist/js/admin/rising-bamboo-core-getting.js', [ 'rbb-core-js' ], self::get_version(), true);
			}
			if ( preg_match('/rbb-core-settings$/', $hook) ) {
				wp_enqueue_script('rbb-core-js-settings', RBB_CORE_URL . 'dist/js/admin/rising-bamboo-core-settings.js', [ 'rbb-core-js' ], self::get_version(), true);
				wp_localize_script(
					'rbb-core-js-settings',
					'rbbReactData',
					[
						'woocommerce' => Helper::woocommerce_activated() ? 'true' : 'false',
					]
				);
			}

			if ( preg_match('/rbb-core-license$/', $hook) ) {
				wp_enqueue_script('rbb-core-js-license', RBB_CORE_URL . 'dist/js/admin/rising-bamboo-core-license.js', [ 'rbb-core-js' ], self::get_version(), true);
			}
		}

		Helper::register_asset('rbb-media-upload', 'js/admin/components/media-upload.js', [ 'jquery' ], self::get_version(), true);
		Helper::register_asset('rbb-swatches-js', 'js/admin/components/color-picker.js', [ 'jquery', 'wp-color-picker' ], self::get_version());
	}

	/**
	 * Get Rbb Icons version;
	 *
	 * @return string
	 */
	public static function get_rbb_icons_version(): string {
		$version = get_file_data(
			RBB_CORE_DIST_PATH . 'css' . DIRECTORY_SEPARATOR . 'rbb-icons.css',
			[
				'Version' => 'Version',
			]
		)['Version'];
		if ( ! $version ) {
			$version = self::get_version();
		}
		return $version;
	}

	/**
	 * Get Version.
	 *
	 * @return mixed
	 */
	public static function get_version() {
		return self::$info['Version'];
	}

	/**
	 * Get Nonce.
	 *
	 * @return string
	 */
	public static function get_nonce(): string {
		return self::RBB_NONE;
	}

	/**
	 * Using rbb-icons for frontend.
	 */
	public function frontend_enqueue_scripts(): void {
		if ( self::theme_support('rbb-core') ) {
			wp_enqueue_script('rbb-core-frontend-js', RBB_CORE_URL . 'dist/js/frontend/rising-bamboo-core.js', [ 'jquery' ], self::get_version(), true);
			wp_localize_script(
				'rbb-core-frontend-js',
				'rbb_vars',
				array_merge(
					$this->rbb_vars,
					[
						'nonce' => wp_create_nonce(self::get_nonce()),
						'rtl'   => is_rtl() ? 'true' : 'false',
					]
				)
			);
			// <editor-fold desc="Jquery Countdown">
			Helper::register_asset('rbb-countdown', 'js/frontend/components/countdown.js', [ 'jquery' ], self::get_version());
			wp_enqueue_script('rbb-countdown');
			// </editor-fold>

		}
		if ( self::theme_support('rbb-icons') && ! wp_style_is('rbb-icons') ) {
			wp_enqueue_style('rbb-icons', RBB_CORE_DIST_URL . 'css/rbb-icons.css', [], self::get_rbb_icons_version());
		}

		if ( self::theme_support('rbb-modal') ) {
			Helper::register_asset('rbb-modal', 'js/frontend/components/modal.js', [ 'jquery' ], self::get_version());
			wp_enqueue_script('rbb-modal');
		}

		if ( self::theme_support('rbb-pace') ) {
			$rbb_pace_args = get_theme_support('rbb-pace')[0] ?? [
				'color' => 'dark',
				'style' => 'minimal',
			];
			Helper::register_asset('rbb-pace', 'js/plugins/pace-js/pace.min.js', [], self::get_version());
			Helper::register_asset('rbb-pace-style', 'js/plugins/pace-js/themes/' . $rbb_pace_args['color'] . '/pace-theme-' . $rbb_pace_args['style'] . '.css', [], self::get_version());
			wp_enqueue_style('rbb-pace-style');
			wp_enqueue_script('rbb-pace');
		}
		if ( self::theme_support('rbb-tracking') ) {
			Tracking::instance();
		}
	}

	/**
	 * Load tgmpa plugins required config.
	 *
	 * @param mixed $plugins Plugins.
	 * @return array
	 */
	public function tgmpa_plugin( $plugins ): array {
        //phpcs:ignore
        $new = require RBB_CORE_INC_DIR . 'config/plugin-required.php';
		return wp_parse_args($plugins, $new);
	}

	/**
	 * Config TGMPA.
	 *
	 * @param array $config Config.
	 * @return array
	 */
	public function tgmpa_plugin_config( array $config ): array {
		$config['parent_slug'] = 'rbb-core';
		$config['capability']  = 'edit_theme_options';
        // phpcs:ignore
        if (isset($_GET['page']) && (strpos($_GET['page'], 'one-click-demo-import') !== false || strpos($_GET['page'], 'rbb-core') !== false)) {
			$config['has_notices'] = false;
			$config['dismissable'] = false;
		}
		return $config;
	}

	/**
	 * Delete complete setup data - Merlin.
	 *
	 * @param mixed $stylesheet Stylesheet.
	 * @param bool  $deleted Deleted.
	 * @return void
	 */
	public function delete_merlin_completed( $stylesheet, bool $deleted ): void {
		if ( $deleted ) {
			$theme = wp_get_theme($stylesheet);
			delete_option('merlin_' . Theme::get_theme_slug_merlin($theme) . '_completed');
		}
	}
}
