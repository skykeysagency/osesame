<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Woocommerce;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Helper as RbbCoreHelper;
use RisingBambooCore\Helper\Setting;
use RisingBambooCore\Helper\Woocommerce as RbbCoreWoocommerceHelper;
use RisingBambooCore\Widgets\ProductCategories;
use WC_AJAX;

/**
 * Woocommerce.
 */
class Woocommerce extends Singleton {

	/**
	 * Minimum Woocommerce Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Woocommerce version required to run the plugin.
	 */
	public const MINIMUM_VERSION = '3.3.2';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		add_action('plugins_loaded', [ $this, 'on_plugins_loaded' ]);
		add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
		add_action('after_setup_theme', [ $this, 'add_theme_support' ], 99);
	}

	/**
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_plugins_loaded(): void {
		if ( $this->is_compatible() ) {
			add_filter('wc_get_template', [ $this, 'wc_get_template' ], 20, 5);
			add_filter('wc_get_template_part', [ $this, 'wc_get_template_part' ], 10, 3);
			add_action('woocommerce_product_query_tax_query', [ $this, 'brands_filter' ]);
			add_action('woocommerce_product_query', [ $this, 'categories_filter' ]);
			add_action('woocommerce_show_admin_notice', [ $this, 'woocommerce_show_admin_notice' ], 10, 2);
			Cart::instance();
			Shipping::instance();
			FreeShippingCalculator::instance();
			Brands::instance();
			Swatches::instance();
			HPOS::instance();
		}
	}

	/**
	 * Change feature which theme support.
	 *
	 * @return void
	 */
	public function add_theme_support(): void {
		// remove default categories in loop.
		if ( App::theme_support('rbb-woo-subcategories') ) {
			remove_filter('woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories');
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		RbbCoreHelper::register_asset('rbb-woocommerce-filters', 'js/frontend/components/woocommerce-filters.js', [ 'jquery' ], '1.0.0');
		wp_enqueue_script('rbb-woocommerce-filters');
	}

	/**
	 * Template base.
	 *
	 * @return string
	 */
	protected function template_base(): string {
		return RBB_CORE_VIEW_DIR . 'frontend' . DIRECTORY_SEPARATOR . WC()->template_path();
	}

	/**
	 * Hook to wc_get_template.
	 *
	 * @param string $template Template.
	 * @param string $template_name Template name.
	 * @param mixed  $args Args.
	 * @param mixed  $template_path Template path.
	 * @param mixed  $default_path Template path default.
	 * @return mixed|string
	 */
	public function wc_get_template( string $template, string $template_name, $args, $template_path, $default_path ) {
		if ( strpos($template, '/themes/') !== false ) {
			return $template;
		}
		static $cache = [];
		if ( isset($cache[ $template_name ]) ) {
			return $cache[ $template_name ];
		}
		$plugin_template = wc_locate_template($template_name, WC()->template_path(), $this->template_base());
		if ( $plugin_template && file_exists($plugin_template) ) {
			$template                = $plugin_template;
			$cache[ $template_name ] = $template;
		}
		return $template;
	}

	/**
	 * Hook to wc_get_template_part
	 *
	 * @param string $template Template.
	 * @param string $slug Slug.
	 * @param string $name Name.
	 * @return mixed|string
	 */
	public function wc_get_template_part( string $template, string $slug, string $name ) {
		if ( strpos($template, '/themes/') !== false ) {
			return $template;
		}
		$template_name = '';
		if ( $name ) {
			$template_name = "{$slug}-{$name}.php";
		} elseif ( $slug ) {
			$template_name = "{$slug}.php";
		}
		if ( ! $template_name ) {
			return $template;
		}

		static $cache = [];
		if ( isset($cache[ $template_name ]) ) {
			return $cache[ $template_name ];
		}

		$plugin_template = $this->template_base() . $template_name;
		if ( $plugin_template && file_exists($plugin_template) ) {
			$template                = $plugin_template;
			$cache[ $template_name ] = $template;
		}
		return $template;
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Woocommerce meets the plugin's minimum requirement.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function is_compatible(): bool {
		if ( ! Helper::woocommerce_activated() ) {
			add_action('admin_notices', [ $this, 'admin_notice_missing_main_plugin' ]);
			return false;
		}
		if ( ! version_compare(WC_VERSION, self::MINIMUM_VERSION, '>=') ) {
			add_action('admin_notices', [ $this, 'admin_notice_minimum_version' ]);
			return false;
		}
		return true;
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Woocommerce installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin(): void {
		if ( isset($_GET['activate']) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset($_GET['activate']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		$message = sprintf(
		// translators: 1: Theme, 2: Plugin name.
			esc_html__('"%1$s" requires "%2$s" to be installed and activated.', App::get_domain()),
			'<strong>' . esc_html__('Rising Bamboo', App::get_domain()) . '</strong>',
			'<strong>' . esc_html__('Woocommerce', App::get_domain()) . '</strong>'
		);
		// translators: 1: Message.
		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Woocommerce version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_version(): void {
		if ( isset($_GET['activate']) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset($_GET['activate']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		$message = sprintf(
		/* translators: 1: Theme 2: Plugin name 3: Required version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', App::get_domain()),
			'<strong>' . esc_html__('Rising Bamboo', App::get_domain()) . '</strong>',
			'<strong>' . esc_html__('Woocommerce', App::get_domain()) . '</strong>',
			self::MINIMUM_VERSION
		);
		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Filter product by categories.
	 *
	 * @param mixed $query Query.
	 */
	public function categories_filter( $query ): void {

		if ( ! is_admin() && $query->is_main_query() && ( is_tax('product_cat') || is_post_type_archive('product') ) ) {
			$operator          = Setting::get_option('woocommerce_product_category_operator', 'or');
			$chosen_categories = RbbCoreWoocommerceHelper::get_chosen_term(ProductCategories::TAXONOMY_NAME);
			if ( $chosen_categories ) {
				// For archive page.
				if ( $query->queried_object_id && ! in_array($query->queried_object_id, $chosen_categories, true) ) {
					array_unshift($chosen_categories, $query->queried_object_id);
				}

				$cat_slugs     = '';
				$cat_separator = ',';
				if ( 'and' === $operator ) {
					$cat_separator = '+';
				}
				foreach ( $chosen_categories as $cat_id ) {
					$_cat       = ( RbbCoreWoocommerceHelper::get_category_by_id($cat_id) ) ? RbbCoreWoocommerceHelper::get_category_by_id($cat_id)->slug : '';
					$cat_slugs .= $_cat . $cat_separator;
				}
				$query->set('product_cat', trim($cat_slugs, $cat_separator));
			}
		}
	}

	/**
	 * Brand Filters.
	 *
	 * @param mixed $tax_query Tax Query.
	 * @return mixed
	 */
	public function brands_filter( $tax_query ) {
		if ( ! is_admin() && is_main_query() && ( is_tax('product_cat') || is_post_type_archive('product') ) ) {
			$chosen = RbbCoreWoocommerceHelper::get_chosen_term(Brands::TAXONOMY_NAME);
			if ( $chosen ) {
				$tax_query[] = [
					'taxonomy'         => Brands::TAXONOMY_NAME,
					'terms'            => $chosen,
					'include_children' => false,
				];
			}
		}
		return $tax_query;
	}

	/**
	 * Not show template check in dashboard.
	 *
	 * @param mixed $flag Flag.
	 * @param mixed $notice Notice.
	 * @return false|mixed
	 */
	public function woocommerce_show_admin_notice( $flag, $notice ) {
		if ( 'template_files' === $notice ) {
			$flag = false;
		}
		return $flag;
	}
}
