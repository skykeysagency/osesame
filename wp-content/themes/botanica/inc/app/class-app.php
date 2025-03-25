<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\App;

use RisingBambooTheme\Core\Singleton;
use RisingBambooTheme\App\Components\MobileNavigation;
use RisingBambooTheme\App\Components\PromotionPopup;
use RisingBambooTheme\App\Menu\Menu;
use RisingBambooTheme\Customizer\Customizer;
use RisingBambooTheme\Customizer\Helper as CustomizerHelper;
use RisingBambooTheme\Customizer\Style;
use RisingBambooTheme\Elementor\Elementor;
use RisingBambooTheme\Helper\Helper as RisingBambooThemeHelper;
use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Jetpack;
use RisingBambooTheme\Woocommerce\Woocommerce;

/**
 * Theme Init.
 * Class App
 *
 * @package RisingBambooTheme\App
 */
class App extends Singleton {
	
	/**
	 * Variable.
	 *
	 * @var string Name of theme.
	 */
	public static string $name;

	/**
	 * Variable.
	 *
	 * @var string Text domain of theme.
	 */
	public static string $text_domain;

	/**
	 * Variable
	 *
	 * @var string Version of theme.
	 */
	public static string $version;

	/**
	 * Addition information.
	 *
	 * @var array Information.
	 */
	public static array $info;
	
	/**
	 * Side Bar Config.
	 *
	 * @var array
	 */
	protected array $sidebars = [];

	/**
	 * Construction.
	 */
	public function __construct() {
		$this->set_theme_info();
		$this->load_kirki_const();
		add_action('after_setup_theme', [ $this, 'rising_bamboo_setup' ]);

		add_action('after_setup_theme', [ $this, 'content_width' ], 0);

		add_action('widgets_init', [ $this, 'register_sidebar' ]);

		add_action('wp_enqueue_scripts', [ $this, 'register_scripts' ]);

		add_action('wp_head', [ $this, 'ping_back_header' ]);

		add_action('wp_footer', [ $this, 'scroll_to_top' ]);

		add_filter('body_class', [ $this, 'body_classes' ]);

		add_filter('rbb_layout_header_list', [ $this, 'fill_header_list' ]);

		add_filter('rbb_layout_footer_list', [ $this, 'fill_footer_list' ]);

		add_filter('wp_kses_allowed_html', [ $this, 'rbb_kses_allowed_html' ], 10, 2);

		Customizer::instance();

		Style::instance();
		
		Woocommerce::instance();
		
		Jetpack::instance();
		
		Elementor::instance();

		add_action('wp_loaded', [ $this, 'post_feature_image_position' ]);

		add_action(
			'wp_loaded',
			function () {
				PromotionPopup::instance();
				MobileNavigation::instance();
			}
		);
		
		add_action('after_switch_theme', [ $this, 'set_default_theme_widgets' ], 10, 2);
		
		add_filter('doing_it_wrong_trigger_error', [ $this, 'prevent_wrong_trigger_wp_editor' ], 10, 4);
	}
	
	/**
	 * Load Kirki const.
	 *
	 * @return void
	 */
	private function load_kirki_const():void {
		if ( ! defined('RISING_BAMBOO_KIRKI_CONFIG') ) {
			require_once RBB_THEME_CONFIG_PATH . 'theme-customizer-const.php'; // phpcs:ignore
		}
	}

	/**
	 * Set theme information.
	 *
	 * @return void
	 */
	private function set_theme_info(): void {
		$theme             = wp_get_theme();
		self::$version     = $theme->get('Version');
		self::$name        = $theme->get('Name');
		self::$text_domain = $theme->get('TextDomain') ?? 'rising-bamboo';
		$info_path         = realpath(RBB_THEME_INC_PATH . 'config/theme-info.php');
		if ( $info_path ) {
            //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			self::$info = require $info_path;
		}
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function rising_bamboo_setup(): void {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain(self::$text_domain, RBB_THEME_PATH . 'languages');
		$this->register_menu();
		Menu::instance();
		$this->theme_support();
		add_editor_style('dist/css/editor-style.css');
		$this->set_post_thumbnail();
	}

	/**
	 * Register Menu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		$configs = require RBB_THEME_INC_PATH . '/config/theme-menu-location.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		register_nav_menus($configs);
		new Importer($configs);
	}

	/**
	 * Add theme support.
	 *
	 * @return void
	 */
	public function theme_support(): void {
		/**
		 * Fixed theme support.
		 */
		add_theme_support('automatic-feed-links');
		add_theme_support('title-tag');
		$configs = require RBB_THEME_INC_PATH . '/config/theme-support.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		foreach ( $configs as $config ) {
			if ( $config['feature'] ) {
				isset($config['args']) ? add_theme_support($config['feature'], $config['args']) : add_theme_support($config['feature']);
			}
		}
	}

	/**
	 * Set Post Thumbnail.
	 *
	 * @return void
	 */
	public function set_post_thumbnail(): void {
		if ( current_theme_supports('post-thumbnails') ) {
			$size_width  = get_theme_support('rbb-post-thumbnails')[0]['width'] ?? 400;
			$size_height = get_theme_support('rbb-post-thumbnails')[0]['height'] ?? 0;
			if ( $size_height ) {
				set_post_thumbnail_size($size_width, $size_height, true);
			} else {
				set_post_thumbnail_size($size_width, $size_height);
			}
		}
	}
	
	/**
	 * Set default widget for blank theme.
	 *
	 * @param mixed $old_theme Old theme name.
	 * @param mixed $WP_theme Old theme obj.
	 * @return void
	 */
	//phpcs:ignore
	public function set_default_theme_widgets( $old_theme, $WP_theme = null ): void {
		$widgets = get_option('sidebars_widgets');
		if ( count($widgets['sidebar-top']) ) {
			$widgets['wp_inactive_widgets'] = $widgets['sidebar-top'];
			$widgets['sidebar-top']         = [];
			update_option('sidebars_widgets', $widgets);
		}
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * @global int $content_width
	 */
	public function content_width(): void {
		$GLOBALS['content_width'] = apply_filters('rising_bamboo_content_width', 640);
	}

	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function register_sidebar(): void {
		$this->sidebars = require RBB_THEME_INC_PATH . 'config/theme-sidebars.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$defaults       = [
			'name'          => 'RisingBamboo Sidebar',
			'id'            => 'rising-bamboo-sidebar',
			'description'   => 'The Rising Bamboo default sidebar.',
			'class'         => '',
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		];

		foreach ( $this->sidebars as $sidebar ) {
			$args = wp_parse_args($sidebar, $defaults);
			register_sidebar($args);
		}
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function register_scripts(): void {
		wp_enqueue_style('rising-bamboo-style', get_template_directory_uri() . '/dist/css/style.css', [], self::$version);
		wp_style_add_data('rising-bamboo-style', 'rtl', 'replace');
		wp_add_inline_style('rising-bamboo-style', $this->inline_style());
		wp_enqueue_script('slick-carousel');
		wp_enqueue_style('slick-theme-style');
		wp_enqueue_script('rising-bamboo-main-js', get_template_directory_uri() . '/dist/js/main.js', [ 'jquery', 'slick-carousel' ], self::$version, true);
		wp_add_inline_script('rising-bamboo-main-js', 'window.rbb_config = ' . $this->inline_script(), 'before');
		if ( is_singular() && comments_open() && get_option('thread_comments') ) {
			wp_enqueue_script('comment-reply');
		}
	}

	/**
	 * Inline Style.
	 *
	 * @return string
	 */
	public function inline_style(): string {
		$logo_padding                       = Setting::get(RISING_BAMBOO_KIRKI_FIELD_LOGO_PADDING);
		$logo_padding                       = $logo_padding ? $logo_padding['padding-top'] . ' ' . $logo_padding['padding-right'] . ' ' . $logo_padding['padding-bottom'] . ' ' . $logo_padding['padding-left'] : '0px';
		$logo_sticky_padding                = Setting::get(RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_PADDING);
		$logo_sticky_padding                = $logo_sticky_padding ? $logo_sticky_padding['padding-top'] . ' ' . $logo_sticky_padding['padding-right'] . ' ' . $logo_sticky_padding['padding-bottom'] . ' ' . $logo_sticky_padding['padding-left'] : '0px';
		$breadcrumb_background_image_enable = Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE_ENABLE);
		$breadcrumb_background_image        = ( 'off' !== $breadcrumb_background_image_enable ) ? "url('" . trim(is_array(Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE)) ? Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE)['url'] : Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE)) . "')" : 'none';
		$block_loading_url                  = RBB_THEME_DIST_URI . 'images/loading/block/' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BLOCK) . '.svg';
		$button_loading_url                 = RBB_THEME_DIST_URI . 'images/loading/button/' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BUTTON) . '.svg';
		$button_loading_hover_url           = RBB_THEME_DIST_URI . 'images/loading/button/hover/' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BUTTON) . '.svg';
		
		$inline_style = '
		/*================ Font Family ================*/
           	/*================ Font Body ================*/
            	--typography-body: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY, 'font-family') . ';
            	--typography-body-font-size: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY, 'font-size') . ';
            	--typography-body-variant: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY, 'variant') . ';
            	--typography-body-line-height: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY, 'line-height') . ';
				--typography-body-letter-spacing: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY, 'letter-spacing') . ';
			/*================ Font Heading ================*/
            	--typography-heading: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING, 'font-family') . ';
            	--typography-heading-variant: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING, 'variant') . ';
            	--typography-heading-line-height: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING, 'line-height') . ';
            	--typography-heading-letter-spacing: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING, 'letter-spacing') . ';
            /*================ Font Button ================*/
            	--typography-button: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON, 'font-family') . ';
            	--typography-button-font-size: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON, 'font-size') . ';
            	--typography-button-variant: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON, 'variant') . ';
            	--typography-button-text-transform: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON, 'text-transform') . ';
            	--typography-button-letter-spacing: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON, 'letter-spacing') . ';
            /*================ Font Form ================*/
            	--typography-form: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM, 'font-family') . ';
            	--typography-form-font-size: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM, 'font-size') . ';
            	--typography-form-variant: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM, 'variant') . ';
            	--typography-form-letter-spacing: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM, 'letter-spacing') . ';
            /*================ General Color Variables ================*/
                --rbb-general-body-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_BACKGROUND) . ';
                --rbb-general-body-text-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_TEXT) . ';
                --rbb-general-heading-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_HEADING_COLOR) . ';
                --rbb-general-primary-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_PRIMARY_COLOR) . ';
                --rbb-general-secondary-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_SECONDARY_COLOR) . ';
                --rbb-general-link-color: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_LINK, 'link') . ';
                --rbb-general-link-hover-color: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_LINK, 'hover') . ';
                --rbb-general-button-color: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR, 'link') . ';
                --rbb-general-button-hover-color: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR, 'hover') . ';
                --rbb-general-button-bg-color: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND, 'link') . ';
                --rbb-general-button-bg-hover-color: ' . CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND, 'hover') . ';
            /*================ Page Title & Breadcrumb Variables ================*/
                --rbb-title-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_TITLE_COLOR) . ';
                --rbb-breadcrumb-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_COLOR) . ';
                --rbb-breadcrumb-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_COLOR) . ';
                --rbb-breadcrumb-background-image: ' . $breadcrumb_background_image . ';
            /*================ HEADER - NAVIGATION ================*/
                --rbb-header-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_BACKGROUND_COLOR) . ';
            /*================ MOBILE - NAVIGATION ================*/
                --rbb-mobile-navigation-bg-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_BACKGROUND_COLOR) . ';
                --rbb-mobile-navigation-text-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_TEXT_COLOR) . ';
            /*================ Menu Variables ================*/
                --rbb-menu-link-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_LINK) . ';
                --rbb-menu-link-hover-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_LINK_HOVER) . ';
                --rbb-menu-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COLOR_MENU_BACKGROUND) . ';
                --rbb-menu-sticky-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BACKGROUND_COLOR) . ';
            /*================ Logo Variables ================*/
                --rbb-logo-max-width: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_LOGO_MAX_WIDTH) . 'px;
                --rbb-logo-padding: ' . $logo_padding . ';
                --rbb-logo-sticky-max-width: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_MAX_WIDTH) . 'px;
                --rbb-logo-sticky-padding: ' . $logo_sticky_padding . ';
            /*================ Mini Card Variables ================*/
                --rbb-mini-cart-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_SIZE) . 'px;
                --rbb-mini-cart-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_COLOR) . ';
                --rbb-mini-cart-icon-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER) . 'px;
                --rbb-mini-cart-icon-border-radius: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER_RADIUS) . ';
                --rbb-mini-cart-icon-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_ICON_BORDER_COLOR) . ';
                --rbb-mini-cart-count-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_COLOR) . ';
                --rbb-mini-cart-count-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_BACKGROUND_COLOR) . ';
                --rbb-mini-cart-count-position-top: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_POSITION)['top'] . ';
                --rbb-mini-cart-count-position-bottom: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_POSITION)['bottom'] . ';
                --rbb-mini-cart-count-position-left: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_POSITION)['left'] . ';
                --rbb-mini-cart-count-position-right: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_COUNT_POSITION)['right'] . ';
                --rbb-mini-cart-content-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BACKGROUND_COLOR) . ';
                --rbb-mini-cart-content-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BORDER) . 'px;
                --rbb-mini-cart-content-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_CONTENT_BORDER_COLOR) . ';
                --rbb-mini-cart-product-image-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_PRODUCT_IMAGE_SIZE) . 'px;
                --rbb-mini-cart-remove-button-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_SIZE) . 'px;
                --rbb-mini-cart-remove-button-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_COLOR) . ';
                --rbb-mini-cart-remove-button-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BACKGROUND_COLOR) . ';
                --rbb-mini-cart-remove-button-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BORDER) . 'px;
                --rbb-mini-cart-remove-button-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_REMOVE_BUTTON_BORDER_COLOR) . ';
            /*================ Quick View Variables ================*/
                --rbb-quick-view-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_ICON_SIZE) . 'px;
            /*================ Account Variables ================*/
                --rbb-account-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_SIZE) . 'px;
                --rbb-account-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_COLOR) . ';
                --rbb-account-icon-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER) . 'px;
                --rbb-account-icon-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_COLOR) . ';
                --rbb-account-icon-border-radius: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_RADIUS) . ';
                --rbb-account-content-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_CONTENT_BACKGROUND_COLOR) . ';
                --rbb-account-input-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER) . 'px;
                --rbb-account-input-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_COLOR) . ';
                --rbb-account-input-border-radius: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_RADIUS) . ';
                --rbb-account-button-edit-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON_COLOR) . ';
                --rbb-account-button-logout-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON_COLOR) . ';
            /*================ Search Variables ================*/
                --rbb-search-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_COLOR) . ';
                --rbb-search-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_SIZE) . 'px;
                --rbb-search-icon-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER) . 'px;
                --rbb-search-icon-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER_COLOR) . ';
                --rbb-search-icon-border-radius: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER_RADIUS) . ';
                --rbb-search-input-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_COLOR) . ';
                --rbb-search-input-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER) . 'px;
                --rbb-search-input-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER_COLOR) . ';
                --rbb-search-input-border-radius: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_INPUT_BORDER_RADIUS) . ';
            /*================ Rating Variables ================*/
                --rbb-rating-icon-content: ' . RisingBambooThemeHelper::get_rbb_icon_content(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON)) . ';
                --rbb-rating-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON_SIZE) . 'rem;
                --rbb-rating-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_RATING_ICON_COLOR) . ';
            /*================ Scroll To Top Variables ================*/
                --rbb-scroll-top-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_COLOR) . ';
                --rbb-scroll-top-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_SIZE) . 'px;
                --rbb-scroll-top-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_BACKGROUND_COLOR) . ';
            /*================ Modal Variables ================*/
            	--rbb-modal-backdrop-filter: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER) . ';
            	--rbb-modal-backdrop-filter-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER_SIZE) . 'px;
                --rbb-modal-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKGROUND_COLOR) . ';
                --rbb-modal-background-opacity: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKGROUND_OPACITY) . ';
            /*================ Modal Variables ================*/
                --rbb-block-loading: url(' . $block_loading_url . ');
                --rbb-button-loading: url(' . $button_loading_url . ');
                 --rbb-button-loading-hover: url(' . $button_loading_hover_url . ');
		';
		
		return '
            :root {
            ' . $inline_style . '
            ' . $this->inline_style_for_woo_wishlist() . '
            ' . $this->inline_style_for_woo_compare() . '
            }
        ';
	}
	
	/**
	 * Inline Style for Wishlist.
	 *
	 * @return string
	 */
	public function inline_style_for_woo_wishlist(): string {
		$result = '';
		if ( RisingBambooThemeHelper::woocommerce_wishlist_activated() ) {
			$result = '
				/*================ Wishlist Variables ================*/
                --rbb-wishlist-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_SIZE) . 'px;
                --rbb-wishlist-icon-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_COLOR) . ';
                --rbb-wishlist-icon-border: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER) . 'px;
                --rbb-wishlist-icon-border-radius: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER_RADIUS) . ';
                --rbb-wishlist-icon-border-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON_BORDER_COLOR) . ';
                --rbb-wishlist-count-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_COLOR) . ';
                --rbb-wishlist-count-background-color: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_BACKGROUND) . ';
                --rbb-wishlist-count-position-top: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_POSITION)['top'] . ';
                --rbb-wishlist-count-position-bottom: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_POSITION)['bottom'] . ';
                --rbb-wishlist-count-position-left: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_POSITION)['left'] . ';
                --rbb-wishlist-count-position-right: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_COUNT_POSITION)['right'] . ';
                --rbb-wishlist-general-icon: ' . RisingBambooThemeHelper::get_rbb_icon_content(Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_GENERAL_ICON)) . ';
			';
		}
		return $result;
	}
	
	/**
	 * Inline Style for Compare.
	 *
	 * @return string
	 */
	public function inline_style_for_woo_compare(): string {
		$result = '';
		if ( RisingBambooThemeHelper::woocommerce_compare_activated() ) {
			$result = '
			/*================ Compare Variables ================*/
                --rbb-compare-general-icon: ' . RisingBambooThemeHelper::get_rbb_icon_content(Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_COMPARE_GENERAL_ICON)) . ';
                --rbb-compare-icon-size: ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_COMPARE_GENERAL_ICON_SIZE) . 'px;
			';
		}
		return $result;
	}

	/**
	 * Inline Script.
	 *
	 * @return false|string
	 */
	public function inline_script() {
		$data = [];

		/**
		 * Header Sticky.
		 */
		$data['header_sticky']     = [
			'enable'    => Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY),
			'behaviour' => Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BEHAVIOUR),
			'height'    => Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_HEIGHT),
		];
		$data['mobile_navigation'] = [
			'behaviour' => Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STICKY_BEHAVIOUR),
		];
		return wp_json_encode($data, JSON_THROW_ON_ERROR);
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( array $classes ): array {
		global $post;
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}
		if ( ! empty($post->post_name) && ( is_single() || is_page() ) ) {
			$classes[] = $post->post_name;
		}
		// Adds a class of no-sidebar when there is no sidebar present.
		$has_sidebar = false;
		foreach ( $this->sidebars as $sidebar ) {
			if ( isset($sidebar['id']) && is_active_sidebar($sidebar['id']) ) {
				$has_sidebar = true;
				break;
			}
		}
		if ( ! $has_sidebar ) {
			$classes[] = 'no-sidebar';
		}
		// Custom class.
		$custom_body_class = '';
		if ( defined('RBB_CORE_META_FIELD_BODY_CUSTOM_CSS_CLASS') ) {
			$custom_body_class = Setting::get('', RBB_CORE_META_FIELD_BODY_CUSTOM_CSS_CLASS);
		}
		if ( ! empty($custom_body_class) ) {
			$custom_body_class = array_map('trim', preg_split('/([;,|])/', trim($custom_body_class), -1, PREG_SPLIT_NO_EMPTY));
			$classes           = wp_parse_args($custom_body_class, $classes);
		}
		return $classes;
	}

	/**
	 * Add a ping back url auto-discovery header for single posts, pages, or attachments.
	 */
	public function ping_back_header(): void {
		if ( is_singular() && pings_open() ) {
			printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
		}
	}

	/**
	 * Add ksec context.
	 *
	 * @param array  $allowed_tags Allow tag.
	 * @param string $context Context.
	 * @return array
	 */
	public function rbb_kses_allowed_html( array $allowed_tags, string $context ): array {
		if ( 'rbb-kses' === $context ) {
			$allowed_tags = [
				'a'          => [
					'class' => [],
					'href'  => [],
					'rel'   => [],
					'title' => [],
				],
				'abbr'       => [
					'title' => [],
				],
				'b'          => [],
				'blockquote' => [
					'cite' => [],
				],
				'cite'       => [
					'title' => [],
				],
				'code'       => [],
				'br'         => [],
				'del'        => [
					'datetime' => [],
					'title'    => [],
				],
				'dd'         => [],
				'div'        => [
					'class'      => [],
					'title'      => [],
					'style'      => [],
					'data-title' => [],
				],
				'dl'         => [],
				'dt'         => [],
				'em'         => [],
				'h1'         => [],
				'h2'         => [],
				'h3'         => [],
				'h4'         => [],
				'h5'         => [],
				'h6'         => [],
				'i'          => [
					'class' => [],
				],
				'img'        => [
					'alt'      => [],
					'class'    => [],
					'height'   => [],
					'src'      => [],
					'width'    => [],
					'srcset'   => [],
					'sizes'    => [],
					'loading'  => [],
					'decoding' => [],
				],
				'li'         => [
					'class'        => [],
					'data-content' => [],
				],
				'ol'         => [
					'class' => [],
				],
				'p'          => [
					'class' => [],
				],
				'q'          => [
					'cite'  => [],
					'title' => [],
				],
				'span'       => [
					'class' => [],
					'title' => [],
					'style' => [],
				],
				'strike'     => [],
				'strong'     => [],
				'ul'         => [
					'class' => [],
				],
				'button'     => [
					'class'   => [],
					'type'    => [],
					'data-id' => [],
				],
				'bdi'        => [
					'class' => [],
					'title' => [],
					'style' => [],
				],
				'label'      => [
					'for' => [],
				],
				'input'      => [
					'class'   => [],
					'type'    => [],
					'name'    => [],
					'id'      => [],
					'value'   => [],
					'checked' => [],
					'data'    => [],
				],
			];
		}
		return $allowed_tags;
	}

	/**
	 * Header list.
	 *
	 * @param mixed $header Header.
	 * @return array
	 */
	public function fill_header_list( $header ): array {
		return wp_parse_args(CustomizerHelper::get_files_assoc(get_template_directory() . '/template-parts/headers/'), $header);
	}

	/**
	 * Fill footer list.
	 *
	 * @param mixed $footer list footer.
	 * @return array
	 */
	public function fill_footer_list( $footer ): array {
		if ( RisingBambooThemeHelper::elementor_activated() ) {
			$list = CustomizerHelper::get_elementor_footers();
		} else {
			$list = CustomizerHelper::get_files_assoc(get_template_directory() . '/template-parts/footers/');
		}
		return wp_parse_args($list, $footer);
	}

	/**
	 * Scroll to top.
	 *
	 * @return void
	 */
	public function scroll_to_top(): void {
		if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP) ) {
			ob_start();
			get_template_part('template-parts/components/scroll-to-top');
			echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Thumbnail position.
	 *
	 * @return void
	 */
	public function post_feature_image_position(): void {
		$position = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_THUMBNAIL_POSITION);
		switch ( $position ) {
			case 'before_title':
				add_action('rbb_single_content_before_title', [ $this, 'post_feature_image_render' ]);
				break;
			case 'after_title':
				add_action('rbb_single_content_after_title', [ $this, 'post_feature_image_render' ]);
				break;
			case 'on_top':
				add_action('rbb_single_post_before', [ $this, 'post_feature_image_render' ]);
				break;
			case 'on_header':
				add_action('rbb_single_post_before_main', [ $this, 'post_feature_image_render' ]);
				add_filter(
					'rbb_show_page_title',
					function ( $status ) {
						if ( is_singular() && 'post' === get_post_type() ) {
							return false;
						}
						return $status;
					}
				);
				add_filter(
					'rbb_show_breadcrumb',
					function ( $status ) {
						if ( is_singular() && 'post' === get_post_type() ) {
							return false;
						}
						return $status;
					}
				);
				break;
		}
	}

	/**
	 * Post thumbnail.
	 *
	 * @return void
	 */
	public function post_feature_image_render(): void {
		get_template_part('template-parts/contents/layouts/post/parts/feature-image');
	}
	
	/**
	 * Prevent wp-editor error notice.
	 *
	 * @param mixed $trigger Trigger.
	 * @param mixed $function_name Func Name.
	 * @param mixed $message Message.
	 * @param mixed $version Version.
	 * @return bool
	 */
	public function prevent_wrong_trigger_wp_editor( $trigger, $function_name, $message, $version ): bool {
		if ( 'wp_enqueue_script()' === $function_name && '5.8.0' === $version && stripos($message, 'wp-editor') !== false ) {
			return false;
		}
		return $trigger;
	}
}
