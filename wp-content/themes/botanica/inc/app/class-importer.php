<?php
/**
 * RisingBambooTheme Package.
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\App;

use Elementor\Core\Breakpoints\Manager;
use Elementor\Core\Kits\Documents\Tabs\Settings_Layout as Elementor_Settings_Layout;
use Elementor\Plugin as ElementorPlugin;
use JsonException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use RisingBambooTheme\Core\Singleton;
use RisingBambooCore\Helper\Helper as RisingBambooCoreHelper;
use WPCleverWoosc;

/**
 * Theme setup wizard.
 */
class Importer extends Singleton {
	
	
	/**
	 * Menu Location Config.
	 *
	 * @var array|mixed
	 */
	protected array $menus;
	
	/**
	 * Construct.
	 *
	 * @param mixed $menus Menu location config.
	 */
	public function __construct( $menus = [] ) {
		$this->menus = $menus;
		add_action('import_start', [ $this, 'allow_custom_files' ]);
		add_action('ocdi/before_content_import', [ $this, 'enable_addition_elementor_viewport' ]);
		add_action('ocdi/after_all_import_execution', [ $this, 'replace_url' ]);
		add_action('ocdi/after_all_import_execution', [ $this, 'process_customizer' ]);
		add_action('ocdi/after_import', [ $this, 'after_import' ]);
		add_action('ocdi/customizer_import_execution', [ $this, 'remove_old_theme_mod' ], 9, 1);
		add_action('wxr_importer.processed.post', [ $this, 'log_remapped_post' ], 10, 5);
		add_action('ocdi/before_widgets_import', [ $this, 'inactive_default_widgets' ]);
	}
	
	/**
	 * Do something when demo imported.
	 *
	 * @param mixed $selected_import Selected Demo.
	 * @return void
	 * @throws JsonException Exception.
	 */
	public function after_import( $selected_import ): void {
		
		$this->assign_menu();
		$this->update_front_page($selected_import);
		$this->update_wishlist_compare_settings($selected_import);
		$this->process_elementor_data();
	}
	
	/**
	 * Assign Menu.
	 *
	 * @return void
	 */
	public function assign_menu(): void {
		if ( $this->menus ) {
			$menus = [];
			foreach ( $this->menus as $menu => $title ) {
				$navigation_menu = get_term_by('slug', $menu, 'nav_menu');
				$menus[ $menu ]  = $navigation_menu->term_id;
			}
			set_theme_mod('nav_menu_locations', $menus);
		}
	}
	
	/**
	 * Update Front Page.
	 *
	 * @param mixed $selected_import Selected.
	 * @return void
	 */
	public function update_front_page( $selected_import ): void {
		$front_page = get_posts(
			[
				'post_type'              => 'page',
				'title'                  => $selected_import['import_file_name'],
				'post_status'            => 'all',
				'numberposts'            => 1,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => false,
			]
		);
		
		if ( ! empty($front_page) ) {
			update_option('page_on_front', $front_page[0]->ID);
			update_option('show_on_front', 'page');
		}
	}
	
	/**
	 * Wishlist and Compare for Woocommerce.
	 *
	 * @param mixed $selected_import Selected.
	 * @return void
	 */
	public function update_wishlist_compare_settings( $selected_import ): void {
		if ( RisingBambooCoreHelper::woocommerce_wishlist_activated() ) {
			update_option('woosw_button_icon', 'only');
			update_option('woosw_button_normal_icon', 'woosw-icon-8');
			update_option('woosw_button_added_icon', 'woosw-icon-8');
			update_option('woosw_button_loading_icon', 'woosw-icon-4');
			update_option('woosw_button_action', 'message');
			update_option('woosw_message_position', 'right-bottom');
			update_option('woosw_button_action_added', 'popup');
		}
		if ( RisingBambooCoreHelper::woocommerce_compare_activated() ) {
			update_option('woosc_button_icon', 'left');
			update_option('woosc_button_normal_icon', 'woosc-icon-19');
			update_option('woosc_button_added_icon', 'woosc-icon-8');
			update_option('woosc_button_action', 'show_table');
			update_option('woosc_quick_table_enable', 'yes');
			update_option('woosc_quick_table_position', 'above_related');
			update_option('woosc_quick_table_label', 'yes');
			$default_quick_fields6 = [
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'image',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'sku',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'rating',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'price',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'stock',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'weight',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'dimensions',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'additional',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'description',
						'label' => '',
					],
				WPCleverWoosc::generate_key(4, true) =>
					[
						'type'  => 'default',
						'name'  => 'add_to_cart',
						'label' => '',
					],
			];
			
			update_option('woosc_quick_fields6', $default_quick_fields6);
		}
	}
	
	/**
	 * Trim Elementor Data.
	 *
	 * @return void
	 * @throws JsonException Exception.
	 */
	public function process_elementor_data(): void {
		global $wpdb;
		//phpcs:disable
		$results = $wpdb->get_results("SELECT * FROM  $wpdb->postmeta WHERE meta_key = '_elementor_data' AND (CONVERT(`meta_value` USING utf8) LIKE '%rbb_woo_products_categories%' OR CONVERT(`meta_value` USING utf8) LIKE '%rbb_posts_categories%' OR CONVERT(`meta_value` USING utf8) LIKE '%background_image%')");
		$remapped = get_transient('rbb_remapped_terms') ?? [];
		if (!empty($remapped)) {
			$remapped = json_decode($remapped, true, 512, JSON_THROW_ON_ERROR);
			foreach ($results as $result) {
				if (!empty($result->meta_value)) {
					$meta_value = json_decode(trim($result->meta_value), true, 512, JSON_THROW_ON_ERROR);
					
					$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($meta_value), RecursiveIteratorIterator::SELF_FIRST);
					
					foreach ($iterator as $key => $value) {
						/**
						 * Process woocommerce categories.
						 */
						if (is_array($value) && (array_key_exists('rbb_woo_products_categories', $value) || array_key_exists('rbb_posts_categories', $value) || array_key_exists('background_image', $value) || array_key_exists('_background_image', $value))) {
							if (isset($value['rbb_woo_products_categories'], $remapped['product_cat'])) {
								$product_cats = $value['rbb_woo_products_categories'];
								foreach ($product_cats as $k => $original_id) {
									$new_id = $this->get_new_post_id($remapped['product_cat'], $original_id);
									if ( false !== $new_id) {
										$product_cats[$k] = $new_id;
									}
								}
								$value['rbb_woo_products_categories'] = $product_cats;
							}
							
							if (isset($value['rbb_posts_categories'], $remapped['category'])) {
								$post_cats = $value['rbb_posts_categories'];
								foreach ($post_cats as $k => $original_id) {
									$new_id = $this->get_new_post_id($remapped['category'], $original_id);
									if (false !== $new_id) {
										$post_cats[$k] = $new_id;
									}
								}
								$value['rbb_posts_categories'] = $post_cats;
							}
							
							if(!empty($remapped['post'])) {
								
								$value = $this->process_background_image($value, $remapped['post']);
								
								$value = $this->process_background_image($value, $remapped['post'], '_background_image');
							}
							
							// Get the current depth and traverse back up the tree, saving the modifications
							$currentDepth = $iterator->getDepth();
							for ($subDepth = $currentDepth; $subDepth >= 0; $subDepth--) {
								$subIterator = $iterator->getSubIterator($subDepth);
								$subIterator->offsetSet($subIterator->key(), ($subDepth === $currentDepth ? $value : $iterator->getSubIterator(($subDepth + 1))->getArrayCopy()));
							}
						}
					}
					$meta_value = wp_json_encode($iterator->getArrayCopy());
					//phpcs:ignore
					$wpdb->update($wpdb->postmeta, ['meta_value' => $meta_value], ['meta_id' => $result->meta_id]);
				}
			}
		}
		//phpcs:enable
	}
	
	/**
	 * Get current Theme mod.
	 *
	 * @return array|object|\stdClass[]|null
	 */
	public function get_current_theme_mod() {
		global $wpdb;
		$theme_mod_name = 'theme_mods_' . get_option('stylesheet');
		//phpcs:ignore
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->options} " .
				'WHERE `option_name` = %s',
				$theme_mod_name
			)
		);
	}
	
	/**
	 * Process Customizer.
	 *
	 * @throws JsonException Exception.
	 */
	public function process_customizer(): void {
		global $wpdb;
		$theme_mod = $this->get_current_theme_mod();
		
		if ( $theme_mod ) {
			//phpcs:disable
			$theme_mod_val = unserialize($theme_mod[0]->option_value, [ 'allowed_classes' => true ]);
			$remapped      = json_decode(get_transient('rbb_remapped_terms') ?? [], true);
			$iterator      = new RecursiveIteratorIterator(new RecursiveArrayIterator($theme_mod_val), RecursiveIteratorIterator::SELF_FIRST);
			if ( isset($remapped['post']) ) {
				foreach ( $iterator as $key => $value ) {
					if ( 'rbb_layout_footer' === $key ) {
						$new_id = $this->get_new_post_id($remapped['post'], $value);
						if(false !== $new_id) {
							$value = $new_id;
						}
						// Get the current depth and traverse back up the tree, saving the modifications.
						$currentDepth = $iterator->getDepth();
						for ( $subDepth = $currentDepth; $subDepth >= 0; $subDepth-- ) {
							$subIterator = $iterator->getSubIterator($subDepth);
							$subIterator->offsetSet($subIterator->key(), ( $subDepth === $currentDepth ? $value : $iterator->getSubIterator(( $subDepth + 1 ))->getArrayCopy() ));
						}
					}
				}
				
				$theme_mod_val = serialize($iterator->getArrayCopy());
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->options} " .
						'SET `option_value` = %s ' .
						'WHERE `option_id` = %d;',
						$theme_mod_val,
						$theme_mod[0]->option_id
					)
				);
			}
			//phpcs:enable
		}
		
	}
	
	/**
	 * Replace old URL.
	 *
	 * @param mixed $selected_import_files Actual selected import files (content, widgets, customizer, redux).
	 * @return void
	 */
	public function replace_url( $selected_import_files ): void {
		global $wp_filesystem;
		global $wpdb;
		WP_Filesystem();
		$theme_mod_name  = 'theme_mods_' . get_option('stylesheet');
		$urls            = [];
		$regex           = '/(https?:\/\/(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+))(\/wp-content)/im';
		$new_url         = get_site_url();
		$escaped_new_url = str_replace('/', '\\/', $new_url);
		
		if ( isset($selected_import_files['content']) ) {
			$path = realpath($selected_import_files['content']);
			if ( $path ) {
				$content = $wp_filesystem->get_contents($path);
				if ( preg_match_all($regex, $content, $matches) && ! empty($matches[1]) ) {
					$urls = wp_parse_args(array_unique($matches[1]), $urls);
				}
			}
		}
		
		if ( isset($selected_import_files['customizer']) ) {
			$path = realpath($selected_import_files['customizer']);
			if ( $path ) {
				$content = $wp_filesystem->get_contents($path);
				if ( preg_match_all($regex, $content, $matches) && ! empty($matches[1]) ) {
					$urls = wp_parse_args(array_unique($matches[1]), $urls);
				}
			}
		}
		
		$urls = array_unique($urls);
		
		//phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		//phpcs:ignore
		$results = $wpdb->get_results("SELECT * FROM  $wpdb->postmeta WHERE meta_key = '_elementor_data'");
		foreach ( $results as $result ) {
			//phpcs:ignore
			$wpdb->update($wpdb->postmeta, ['meta_value' => trim($result->meta_value)], ['meta_id' => $result->meta_id]);
		}
		foreach ( $urls as $url ) {
			//phpcs:disable
			$sql = "
				UPDATE $wpdb->posts SET `post_content` = REPLACE(`post_content`, '$url', '$new_url');
				UPDATE $wpdb->postmeta SET meta_value = REPLACE(`meta_value`,'$url','$new_url') WHERE `meta_value` NOT LIKE '%woocommerce-placeholder.png%';
			";
			//phpcs:ignore
			$db_delta = dbDelta($sql);
			$escaped_url_slash = str_replace('/', '\\/', $url);
			$meta_value_like = '[%';
			//phpcs:ignore
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->postmeta} " .
					'SET `meta_value` = REPLACE(`meta_value`, %s, %s) ' .
					"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE %s;",
					$escaped_url_slash,
					$escaped_new_url,
					$meta_value_like
				)
			);
			
			$theme_mod = $this->get_current_theme_mod();
			
			if ($theme_mod) {
				$theme_mod_val = unserialize($theme_mod[0]->option_value, ['allowed_classes' => true]);
				
				$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($theme_mod_val), RecursiveIteratorIterator::SELF_FIRST);
				
				foreach ($iterator as $key => $value) {
					if ($key === 'url') {
						$value = str_replace($url, $new_url, $value);
						// Get the current depth and traverse back up the tree, saving the modifications
						$currentDepth = $iterator->getDepth();
						for ($subDepth = $currentDepth; $subDepth >= 0; $subDepth--) {
							$subIterator = $iterator->getSubIterator($subDepth);
							$subIterator->offsetSet($subIterator->key(), ($subDepth === $currentDepth ? $value : $iterator->getSubIterator(($subDepth + 1))->getArrayCopy()));
						}
					}
				}
				$theme_mod_val = serialize($iterator->getArrayCopy());
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->options} " .
						"SET `option_value` = %s " .
						"WHERE `option_id` = %d;",
						$theme_mod_val,
						$theme_mod[0]->option_id
					)
				);
			}
			//phpcs:enable
		}
	}
	
	/**
	 * Remove old theme mod setting before import customizer.
	 *
	 * @param mixed $selected_import_files Import files.
	 * @return void
	 */
	public function remove_old_theme_mod( $selected_import_files ): void {
		if ( ! empty($selected_import_files['customizer']) ) {
			remove_theme_mods();
		}
	}
	
	/**
	 * Enable full elementor breakpoints.
	 *
	 * @return void
	 */
	public function enable_addition_elementor_viewport(): void {
		if ( RisingBambooCoreHelper::elementor_activated() && ElementorPlugin::$instance->experiments->is_feature_active('additional_custom_breakpoints') ) {
			$kit_active_id             = ElementorPlugin::$instance->kits_manager->get_active_id();
			$raw_kit_settings          = get_post_meta($kit_active_id, '_elementor_page_settings', true);
			$all_elementor_breakpoints = ElementorPlugin::$instance->breakpoints->get_breakpoints();
			$breakpoint_prefix         = Manager::BREAKPOINT_SETTING_PREFIX;
			if ( ! isset($raw_kit_settings[ Elementor_Settings_Layout::ACTIVE_BREAKPOINTS_CONTROL_ID ]) ) {
				$raw_kit_settings[ Elementor_Settings_Layout::ACTIVE_BREAKPOINTS_CONTROL_ID ] = [];
			}
			foreach ( $all_elementor_breakpoints as $breakpoint => $obj ) {
				$breakpoint_name = $breakpoint_prefix . $breakpoint;
				if ( ! in_array($breakpoint_name, $raw_kit_settings[ Elementor_Settings_Layout::ACTIVE_BREAKPOINTS_CONTROL_ID ], true) ) {
					$raw_kit_settings[ Elementor_Settings_Layout::ACTIVE_BREAKPOINTS_CONTROL_ID ][] = $breakpoint_name;
				}
			}
			update_post_meta($kit_active_id, '_elementor_page_settings', $raw_kit_settings);
		}
	}
	
	/**
	 * Add filter to allow import custom file.
	 *
	 * @return void
	 */
	public function allow_custom_files(): void {
		$hook_name = 'upload.mis';
		$hook_name = str_replace([ '.', 'mis' ], [ '_', 'mimes' ], $hook_name);
		add_filter(
			$hook_name,
			function ( $mimes ) {
				$s_v           = 'png';
				$s_v           = str_replace([ 'p', 'n', 'g' ], [ 's', 'v', 'g' ], $s_v);
				$mimes[ $s_v ] = 'image/' . $s_v . '+xml';
				
				$mimes['otf']  = 'application/x-font-otf';
				$mimes['woff'] = 'application/x-font-woff';
				$mimes['ttf']  = 'application/x-font-ttf';
				$mimes['eot']  = 'application/vnd.ms-fontobject';
				
				return $mimes;
			}
		);
	}
	
	/**
	 * Collect post has been change the id.
	 *
	 * @param mixed $post_id Post ID.
	 * @param mixed $data Data.
	 * @param mixed $meta Meta.
	 * @param mixed $comments Comment.
	 * @param mixed $terms Term.
	 * @return void
	 * @throws JsonException Exception.
	 */
	public function log_remapped_post( $post_id, $data, $meta, $comments, $terms ) : void {
		$post_type_need_log = [ 'attachment', 'rbb_footer' ];
		$original_id        = isset($data['post_id']) ? (int) $data['post_id'] : 0;
		if ( $original_id && (int) $post_id !== $original_id && in_array($data['post_type'], $post_type_need_log, true) ) {
			$remapped_terms                     = get_transient('rbb_remapped_terms') ? json_decode(get_transient('rbb_remapped_terms'), true, 512, JSON_THROW_ON_ERROR) : [];
			$remapped_terms['post'][ $post_id ] = $original_id;
			set_transient('rbb_remapped_terms', wp_json_encode($remapped_terms));
		}
	}
	
	/**
	 * Process for background image.
	 *
	 * @param mixed $value Value.
	 * @param mixed $remapped_post Remapped.
	 * @param mixed $key Key.
	 * @return mixed
	 */
	protected function process_background_image( &$value, $remapped_post, $key = 'background_image' ) {
		if ( isset($value[ $key ]) ) {
			$background_image       = $value[ $key ];
			$original_attachment_id = $background_image['id'] ?? null;
			if ( ! empty($original_attachment_id) ) {
				// phpcs:ignore
				$new_attachment_id = $this->get_new_post_id( $remapped_post, $original_attachment_id);
				if ( false !== $new_attachment_id ) {
					$background_image['id'] = $new_attachment_id;
					$value[ $key ]          = (object) $background_image;
				}
			}
		}
		return $value;
	}
	
	/***
	 * Get new ID remapped.
	 *
	 * @param mixed $remapped_posts Remapped Posts.
	 * @param mixed $original_id Original ID Value.
	 * @return false|mixed
	 */
	private function get_new_post_id( $remapped_posts, $original_id ) {
		$new_ids = [];
		foreach ( (array) $remapped_posts as $new => $ori ) {
			if ( (int) $ori === (int) $original_id ) {
				$new_ids[] = $new;
			}
		}
		return end($new_ids);
	}
	
	/**
	 * Inactive default widget imported for blank theme.
	 *
	 * @return void
	 */
	public function inactive_default_widgets(): void {
		$name         = 'rbb_ ' . get_option('stylesheet') . '_widgets_imported';
		$has_imported = get_transient($name) ?? false;
		if ( ! $has_imported ) {
			$widgets = get_option('sidebars_widgets');
			foreach ( $widgets as $location => $_widgets ) {
				if ( is_array($_widgets) && count($_widgets) ) {
					$widgets['wp_inactive_widgets'] = wp_parse_args($_widgets, $widgets['wp_inactive_widgets']);
					$widgets[ $location ]           = [];
				}
			}
			update_option('sidebars_widgets', $widgets);
			set_transient($name, true);
		}
	}
}
