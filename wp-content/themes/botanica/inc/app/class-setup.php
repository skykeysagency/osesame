<?php
/**
 * RisingBambooTheme Package.
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\App;

use Elementor\Plugin;
use Merlin;
use OCDI_Plugin;
use RisingBambooTheme\Helper\Helper  as RisingBambooThemeHelper;
use WC_Install;
use WP_Theme;

if ( ! class_exists(RisingBambooThemeHelper::class) ) {
	//phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	require_once RBB_THEME_INC_PATH . 'helper/class-helper.php';
}
/**
 * Theme setup wizard.
 */
class ThemeSetup {
	
	/**
	 * Merlin Object.
	 *
	 * @var mixed
	 */
	public $merlin;
	
	/**
	 * Settings.
	 *
	 * @var array
	 */
	public array $settings;
	
	/**
	 * Current theme.
	 *
	 * @var WP_Theme $theme
	 */
	public WP_Theme $theme;
	
	/**
	 * Construct.
	 */
	public function __construct() {
		$this->theme = wp_get_theme();
		// Fix error when missing current screen.
		add_action('admin_print_styles', [ $this, 'set_current_screen' ], 10);
		remove_action('wp_print_styles', 'print_emoji_styles');
		add_action('admin_init', [ $this, 'remove_elementor_redirect' ], 1);
		add_action('admin_init', [ $this, 'fix_deprecated' ], 10);
		add_action('admin_menu', [ $this, 'admin_menu' ]);
		add_filter('woocommerce_prevent_automatic_wizard_redirect', '__return_true');
		add_filter('woocommerce_create_pages', [ $this, 'prevent_create_pages' ]);
		add_filter('merlin_import_files', [ $this, 'import_files' ]);
		add_filter($this->theme->template . '_merlin_steps', [ $this, 'remove_install_child_theme_step' ]);
		add_filter('rbb-importer/time_for_one_ajax_call', [ $this, 'time_ajax_call' ]);
		
		add_action('wxr_importer.processed.term', [ $this, 'log_remapped_term' ], 10, 2);
		
		add_action('merlin_after_all_import', [ $this, 'update_permalink_structure' ]);
		add_action('merlin_after_all_import', [ $this, 'replace_url' ]);
		add_action('merlin_after_all_import', [ $this, 'update_term_count' ]);
		add_action('merlin_after_all_import', [ $this, 'update_comment_count' ]);
		
		add_action('merlin_after_all_import', [ $this, 'update_woocommerce_pages' ]);
		add_action('merlin_after_all_import', [ $this, 'update_product_meta_lookup' ]);
		add_action('merlin_after_all_import', [ $this, 'update_product_attribute_type' ]);
		add_action('merlin_after_all_import', [ $this, 'fix_missing_pages' ]);
		add_action('merlin_after_all_import', [ $this, 'disable_elementor_cache' ]);
		$this->setup();
	}
	
	/**
	 * Create merlin.
	 *
	 * @return void
	 */
	public function setup(): void {
		$theme_config_file = realpath(get_parent_theme_file_path('inc/config/theme-setup-wizard.php'));
		if ( $theme_config_file ) {
			$config_default = [
				'base_path'            => RBB_THEME_PATH,
				'base_url'             => RBB_THEME_URL,
				'directory'            => 'inc/merlin', // Location / directory where Merlin WP is placed in your theme.
				'merlin_url'           => 'rbb-wizard', // The wp-admin page slug where Merlin WP loads.
				'capability'           => 'manage_options',
				'child_action_btn_url' => 'https://developer.wordpress.org/themes/advanced-topics/child-themes/',
				'dev_mode'             => true,
			];
			$trans_default  = [
				'ready-big-button' => esc_html__('View your website', 'botanica'),
			];
			if ( class_exists(OCDI_Plugin::class) && class_exists(\RisingBambooCore\App\App::class) && ! $this->setup_complete() ) {
				$trans_default['ready-big-button']      = __('Import demo data', 'botanica');
				$config_default['ready_big_button_url'] = admin_url('admin.php?page=one-click-demo-import');
			}
			//phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			$theme_configs = require $theme_config_file;
			$config_theme  = $theme_configs['config'];
			$trans_theme   = $theme_configs['trans'];
			
			$this->settings = wp_parse_args($config_theme, $config_default);
			$trans          = wp_parse_args($trans_theme, $trans_default);
			if ( ! class_exists('TGM_Plugin_Activation') && realpath(get_parent_theme_file_path('inc/tgm/class-tgm-plugin-activation.php')) ) {
				//phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once get_parent_theme_file_path('inc/tgm/class-tgm-plugin-activation.php');
			}
			
			$this->merlin = new Merlin(
				$this->settings,
				$trans
			);
		}
	}
	
	/**
	 * Set current screen.
	 *
	 * @return void
	 */
	public function set_current_screen(): void {
		global $current_screen;
		if ( null === $current_screen ) {
			set_current_screen();
		}
	}
	
	/**
	 * Limit time.
	 *
	 * @param mixed $time Default time.
	 * @return float|int|mixed
	 */
	public function time_ajax_call( $time ) {
		$limit = ini_get('max_execution_time');
		if ( null !== $limit && '0' !== $limit ) {
			$limit = (int) $limit;
			if ( $limit < $time ) {
				return $limit;
			}
			
			$half = $limit / 2;
			if ( $half < $time && $half >= 15 ) {
				return $half;
			}
		}
		return $time;
	}
	
	/**
	 * Disable emoji.
	 *
	 * @return void
	 */
	public function fix_deprecated(): void {
		// Fix Emoji.
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		$this->remove_redundant_filter();
		// Fix Admin Bar.
		remove_action('wp_head', 'wp_admin_bar_header');
		remove_action('admin_head', 'wp_admin_bar_header');
	}
	
	/**
	 * Remove Redundant Filter to fix deprecated.
	 *
	 * @return void
	 */
	public function remove_redundant_filter(): void {
		$list_filter = [
			'the_content_feed' => 'wp_staticize_emoji',
			'comment_text_rss' => 'wp_staticize_emoji',
			'wp_mail'          => 'wp_staticize_emoji_for_email',
		];
		foreach ( $list_filter as  $filter => $callback ) {
			RisingBambooThemeHelper::remove_f($filter, $callback);
		}
	}
	
	/**
	 * Get theme slug.
	 *
	 * @return string
	 */
	public function get_theme_slug(): string {
		return strtolower(preg_replace('#[^a-zA-Z]#', '', $this->theme->template));
	}
	
	/**
	 * Footers Import.
	 *
	 * @return array|array[]|bool|string
	 */
	public function import_files() {
		$config_file = realpath(RBB_THEME_INC_PATH . 'config/theme-setup-wizard-import.php');
		if ( $config_file ) {
			//phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			return require $config_file;
		}
		return [];
	}
	
	/**
	 * Add Theme setup menu to Rbb Core.
	 *
	 * @return void
	 */
	public function admin_menu(): void {
		if ( class_exists(\RisingBambooCore\App\App::class) ) {
			if ( false === $this->settings['dev_mode'] && $this->setup_complete() ) {
				return;
			}
			//phpcs:ignore
			add_submenu_page('rbb-core', 'Theme Setup', 'Theme Setup', 'manage_options', 'rbb-wizard', [$this, 'redirect_theme_setup_page']);
		}
	}
	
	/**
	 * Redirect to set up page.
	 *
	 * @return void
	 */
	public function redirect_theme_setup_page(): void {
		$url = admin_url() . 'themes.php?page=rbb-wizard';
		wp_safe_redirect($url);
	}
	
	/**
	 * Check setup is complete.
	 * Look up inc/merlin/class-merlin.php : 281
	 *
	 * @return false|mixed|null
	 */
	public function setup_complete() {
		$slug = $this->get_theme_slug();
		return get_option('merlin_' . $slug . '_completed');
	}
	
	/**
	 * Remove Elementor Redirect when Installed.
	 *
	 * @return void
	 */
	public function remove_elementor_redirect(): void {
		if ( did_action('elementor/loaded') ) {
			remove_action('admin_init', [ Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ]);
		}
	}
	
	/**
	 * Remove step install child theme.
	 *
	 * @param mixed $steps Step.
	 * @return mixed
	 */
	public function remove_install_child_theme_step( $steps ) {
		unset($steps['child']);
		return $steps;
	}
	
	/**
	 * Replace old URL.
	 *
	 * @param mixed $selected_import_index The Index.
	 * @return void
	 */
	public function replace_url( $selected_import_index ): void {
		global $wp_filesystem;
		global $wpdb;
		WP_Filesystem();
		$urls             = [];
		$import_file_path = $this->merlin->get_import_files_paths($selected_import_index);
		$regex            = '/(https?:\/\/(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+))(\/wp-content\/uploads)/im';
		$this->merlin->logger->debug(__('Start replace url', 'botanica'));
		if ( isset($import_file_path['content']) ) {
			$path = realpath($import_file_path['content']);
			$this->merlin->logger->info(__('Import file path : ', 'botanica'), (array) $path);
			if ( $path ) {
				$content = $wp_filesystem->get_contents($path);
				if ( preg_match_all($regex, $content, $matches) && ! empty($matches[1]) ) {
					$urls = wp_parse_args(array_unique($matches[1]), $urls);
				}
			}
		}
		$this->merlin->logger->info(__('Urls : ', 'botanica'), $urls);
		$new_url         = get_site_url();
		$escaped_new_url = str_replace('/', '\\/', $new_url);
		//phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		//phpcs:ignore
		$results = $wpdb->get_results("SELECT * FROM  $wpdb->postmeta WHERE meta_key = '_elementor_data'");
		foreach ( $results as $result ) {
			//phpcs:ignore
			$wpdb->update($wpdb->postmeta, ['meta_value' => trim($result->meta_value)], ['meta_id' => $result->meta_id]);
		}
		
		foreach ( $urls as $url ) {
			$this->merlin->logger->debug(sprintf('Starting replace a %s with %s using dbDelta', $url, $new_url));
			//phpcs:disable
			$sql = "
				UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '$url', '$new_url');
				UPDATE $wpdb->postmeta SET meta_value = REPLACE(meta_value,'$url','$new_url') WHERE meta_value NOT LIKE '%woocommerce-placeholder.png%';
			";
			$this->merlin->logger->info(__('Sql replace : ', 'botanica'), [trim($sql)]);
			//phpcs:ignore
			$db_delta = dbDelta($sql);
			$this->merlin->logger->info(__('dbDelta result : ', 'botanica'), (array)$db_delta);
			
			$this->merlin->logger->debug(sprintf('Starting replace a %s with %s for _elementor_data using $wpdb', $url, $new_url));
			$escaped_url_slash = str_replace('/', '\\/', $url);
			$meta_value_like = '[%';
			//phpcs:ignore
			$wpdb_result = $wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->postmeta} " .
					'SET `meta_value` = REPLACE(`meta_value`, %s, %s) ' .
					"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE %s;",
					$escaped_url_slash,
					$escaped_new_url,
					$meta_value_like
				)
			);
			
			$this->merlin->logger->info(__('dbDelta result : ', 'botanica'), (array)$wpdb_result);
			
			$this->merlin->logger->debug(sprintf('End replace a %s with %s', $url, $new_url));
			
			//phpcs:enable
		}
		$this->merlin->logger->debug(__('End replace url', 'botanica'));
	}
	
	/**
	 * Update Permalink.
	 *
	 * @param mixed $selected_import_index The Index.
	 * @return void
	 */
	public function update_permalink_structure( $selected_import_index ): void {
		// WP Permalink.
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure('/%postname%/');
		
		if ( RisingBambooThemeHelper::woocommerce_activated() ) {
			// Woocommerce Permalink.
			$woocommerce_permalinks                  = get_option('woocommerce_permalinks');
			$woocommerce_permalinks['product_base']  = '/shop/%product_cat%';
			$woocommerce_permalinks['category_base'] = 'product-category';
			update_option('woocommerce_permalinks', $woocommerce_permalinks);
		}
		$wp_rewrite->flush_rules();
	}
	
	/**
	 * Update term count.
	 *
	 * @return void
	 */
	public function update_term_count(): void {
		global $wpdb;
		//phpcs:disable
		$this->merlin->logger->debug(__('Update term count', 'botanica'));
		$sql    = "UPDATE {$wpdb->term_taxonomy} SET count = (
				SELECT COUNT(*) FROM {$wpdb->term_relationships} rel
				LEFT JOIN {$wpdb->posts} po ON (po.ID = rel.object_id)
				WHERE
				rel.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
				AND
				{$wpdb->term_taxonomy}.taxonomy NOT IN ('link_category')
				AND
				po.post_status IN ('publish', 'future')
				)";
		$result = $wpdb->query($sql);
		//phpcs:enable
		$this->merlin->logger->info(__('Rows affected', 'botanica'), [ $result ]);
		$this->merlin->logger->debug(__('End update term count', 'botanica'));
	}
	
	/**
	 * Update comment count.
	 *
	 * @return void
	 */
	public function update_comment_count(): void {
		//phpcs:disable
		global $wpdb;
		$this->merlin->logger->debug(__('Update comment count', 'botanica'));
		$entries = $wpdb->get_results("SELECT * FROM {$wpdb->posts} WHERE post_type IN ('post', 'page')");
		foreach ($entries as $entry) {
			$post_id = $entry->ID;
			$comment_count = $wpdb->get_var("SELECT COUNT(*) AS comment_cnt FROM {$wpdb->comments} WHERE comment_post_ID = '$post_id' AND comment_approved = '1'");
			$result = $wpdb->query("UPDATE {$wpdb->posts} SET comment_count = '$comment_count' WHERE ID = '$post_id'");
			$this->merlin->logger->info(__('Update for ID : ', 'botanica') . $post_id, [$result]);
		}
		$this->merlin->logger->debug(__('# End update comment count', 'botanica'));
		//phpcs:enable
	}
	
	/**
	 * Update Woocommerce Pages.
	 *
	 * @param mixed $selected_import_index The Index.
	 * @return void
	 */
	public function update_woocommerce_pages( $selected_import_index ): void {
		$selected_import_data = empty($this->merlin->import_files[ $selected_import_index ]) ? false : $this->merlin->import_files[ $selected_import_index ];
		if ( ! empty($selected_import_data) && RisingBambooThemeHelper::woocommerce_activated() && strpos($selected_import_data['import_file_url'], 'extra.xml') !== false ) {
			$this->merlin->logger->debug(__('Update Woocommerce Default Pages', 'botanica'));
			$shop = get_posts(
				[
					'post_type'              => 'page',
					'title'                  => 'Shop',
					'post_status'            => 'all',
					'numberposts'            => 1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				]
			);
			if ( $shop ) {
				update_option('woocommerce_shop_page_id', $shop[0]->ID);
				$this->merlin->logger->info(__('Update Shop Page', 'botanica'), (array) $shop[0]->ID);
			}
			$cart = get_posts(
				[
					'post_type'              => 'page',
					'title'                  => 'Cart',
					'post_status'            => 'all',
					'numberposts'            => 1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				]
			);
			if ( $cart ) {
				update_option('woocommerce_cart_page_id', $cart[0]->ID);
				$this->merlin->logger->info(__('Update Cart Page', 'botanica'), (array) $cart[0]->ID);
			}
			
			$checkout = get_posts(
				[
					'post_type'              => 'page',
					'title'                  => 'Checkout',
					'post_status'            => 'all',
					'numberposts'            => 1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				]
			);
			if ( $checkout ) {
				update_option('woocommerce_checkout_page_id', $checkout[0]->ID);
				$this->merlin->logger->info(__('Update Checkout Page', 'botanica'), (array) $checkout[0]->ID);
			}
			$account = get_posts(
				[
					'post_type'              => 'page',
					'title'                  => 'My account',
					'post_status'            => 'all',
					'numberposts'            => 1,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
				]
			);
			if ( $account ) {
				update_option('woocommerce_myaccount_page_id', $account[0]->ID);
				$this->merlin->logger->info(__('Update Account Page', 'botanica'), (array) $account[0]->ID);
			}
		} else {
			$this->merlin->logger->info(__('Not update woocommerce pages', 'botanica'), (array) $selected_import_data['import_file_url']);
		}
	}
	
	/**
	 * Prevent Woocommerce create pages.
	 *
	 * @param array $pages Pages.
	 * @return array
	 */
	public function prevent_create_pages( array $pages ): array {
		unset($pages['cart'], $pages['checkout'], $pages['myaccount']);
		return $pages;
	}
	
	/**
	 * Update Product meta lookup.
	 *
	 * @return void
	 */
	public function update_product_meta_lookup(): void {
		if ( RisingBambooThemeHelper::woocommerce_activated() ) {
			$this->merlin->logger->debug(__('Start update product lookup tables', 'botanica'));
			wc_update_product_lookup_tables();
		}
	}
	
	/**
	 * Update Attribute Type.
	 *
	 * @return void
	 */
	public function update_product_attribute_type(): void {
		global $wpdb;
		if ( RisingBambooThemeHelper::woocommerce_activated() ) {
			$this->merlin->logger->debug(__('Update woocommerce product attribute type', 'botanica'));
			$sql = "UPDATE {$wpdb->prefix}woocommerce_attribute_taxonomies SET `attribute_type` = '%s' WHERE `attribute_name` = '%s'";
			//phpcs:ignore
			$result = $wpdb->query($wpdb->prepare($sql, 'color', 'color'));
			$this->merlin->logger->info(__('Result : ', 'botanica'), [ $sql, $result ]);
			$clear_cache = wp_cache_flush();
			$this->merlin->logger->info(__('Clear all cache : ', 'botanica'), [ $clear_cache ]);
			$attribute_transient = delete_transient('wc_attribute_taxonomies');
			$this->merlin->logger->info(__('Delete woocommerce attribute taxonomies transient : ', 'botanica'), [ $attribute_transient ]);
			$this->merlin->logger->debug(__('# End update woocommerce product attribute type', 'botanica'));
		}
	}
	
	/**
	 * Fix missing woocommerce default page.
	 *
	 * @return void
	 */
	public function fix_missing_pages(): void {
		if (
			RisingBambooThemeHelper::woocommerce_activated() &&
			! ( (int) get_option('woocommerce_shop_page_id') > 0 ) &&
			! ( (int) get_option('woocommerce_cart_page_id') > 0 ) &&
			! ( (int) get_option('woocommerce_checkout_page_id') > 0 ) &&
			! ( (int) get_option('woocommerce_myaccount_page_id') > 0 )
		) {
			$this->merlin->logger->debug(__('Create missing woocommerce pages', 'botanica'));
			RisingBambooThemeHelper::remove_f('woocommerce_create_pages', [ $this, 'prevent_create_pages' ]);
			WC_Install::create_pages();
		}
	}
	
	/**
	 * Disable Elementor cache to fix incorrect caching on all WooCommerce My Account pages.
	 *
	 * @return void
	 */
	public function disable_elementor_cache(): void {
		$elementor_cache = get_option('elementor_experiment-e_element_cache');
		if ( 'inactive' !== $elementor_cache ) {
			update_option('elementor_experiment-e_element_cache', 'inactive');
		}
	}
	
	/**
	 * Collect term has been change the id.
	 *
	 * @param mixed $term_id Term ID.
	 * @param mixed $data Data.
	 * @return void
	 */
	public function log_remapped_term( $term_id, $data ): void {
		$original_id = isset($data['id']) ? (int) $data['id'] : 0;
		if ( $original_id && (int) $term_id !== $original_id && isset($data['taxonomy']) ) {
			$remapped_terms                                  = get_transient('rbb_remapped_terms') ? json_decode(get_transient('rbb_remapped_terms'), true) : [];
			$remapped_terms[ $data['taxonomy'] ][ $term_id ] = $original_id;
			set_transient('rbb_remapped_terms', wp_json_encode($remapped_terms));
			if ( isset($this->merlin->logger) ) {
				$this->merlin->logger->info(__('Log remapped term', 'botanica'), [ $term_id, $original_id ]);
			}
		}
	}
}
