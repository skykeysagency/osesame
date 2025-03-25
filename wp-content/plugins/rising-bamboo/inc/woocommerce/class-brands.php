<?php
/**
 * RisingBambooCore Package.
 *
 * @package RisingBamboo
 */

namespace RisingBambooCore\Woocommerce;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Setting;

/**
 * Woocommerce Brands.
 */
class Brands extends Singleton {


	/**
	 * Taxonomy name.
	 */
	public const TAXONOMY_NAME = 'product_brand';

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('woocommerce_register_taxonomy', [ $this, 'create_taxonomies' ]);
		if ( Setting::get_option('woocommerce_brands', true) ) {
			add_action('delete_term', [ $this, 'delete_term' ], 5);
			add_action(self::TAXONOMY_NAME . '_add_form_fields', [ $this, 'add_brand_fields' ]);
			add_action(self::TAXONOMY_NAME . '_edit_form_fields', [ $this, 'edit_brand_fields' ]);
			add_action('created_term', [ $this, 'save_brands_fields' ], 10, 3);
			add_action('edit_term', [ $this, 'save_brands_fields' ], 10, 3);
			add_action('admin_enqueue_scripts', [ $this, 'admin_scripts' ]);
			add_filter('wp_terms_checklist_args', [ $this, 'checklist_args' ]);
		}
	}

	/**
	 * Create Taxonomies.
	 *
	 * @return void
	 */
	public function create_taxonomies(): void {
		$shop_page_id = wc_get_page_id('shop');
		$base_slug    = $shop_page_id > 0 && get_post($shop_page_id) ? get_page_uri($shop_page_id) : 'shop';
		$brands_base  = 'yes' === get_option('woocommerce_prepend_shop_page_to_urls') ? trailingslashit($base_slug) : '';
		$labels       = [
			'name'              => __('Brands', App::get_domain()),
			'singular_name'     => __('Brand', App::get_domain()),
			'search_items'      => __('Search Brands', App::get_domain()),
			'all_items'         => __('All Brands', App::get_domain()),
			'parent_item'       => __('Parent Brand', App::get_domain()),
			'parent_item_colon' => __('Parent Brand:', App::get_domain()),
			'edit_item'         => __('Edit Brand', App::get_domain()),
			'update_item'       => __('Update Brand', App::get_domain()),
			'add_new_item'      => __('Add New Brand', App::get_domain()),
			'new_item_name'     => __('New Brand Name', App::get_domain()),
			'menu_name'         => __('Brands', App::get_domain()),
		];

		$args = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'sort'              => true,
			'capabilities'      => [
				'manage_terms' => 'manage_product_terms',
				'edit_terms'   => 'edit_product_terms',
				'delete_terms' => 'delete_product_terms',
				'assign_terms' => 'assign_product_terms',
			],
			'rewrite'           => [
				'slug'         => $brands_base . __('brand', App::get_domain()),
				'with_front'   => false,
				'hierarchical' => true,
			],
		];
		//phpcs:ignore
		register_taxonomy(self::TAXONOMY_NAME, ['product'], apply_filters('rbb_register_taxonomy_' . self::TAXONOMY_NAME, $args));
	}

	/**
	 * Delete Term.
	 *
	 * @param int $term_id Term ID.
	 * @return void
	 */
	public function delete_term( int $term_id ): void {
		if ( ! $term_id ) {
			return;
		}
		global $wpdb;
        //phpcs:ignore
		$wpdb->query("DELETE FROM $wpdb->woocommerce_termmeta WHERE `woocommerce_term_id` = " . $term_id);
	}

	/**
	 * Add brand fields.
	 *
	 * @return void
	 */
	public function add_brand_fields(): void {
		?>
		<div class="form-field">
			<label for="<?php echo esc_attr(self::TAXONOMY_NAME); ?>_url" id=""><?php echo esc_html__('URL', App::get_domain()); ?></label>
			<input id="<?php echo esc_attr(self::TAXONOMY_NAME); ?>_url" type="text" name="<?php echo esc_attr(self::TAXONOMY_NAME); ?>_url"/>
			<p><?php esc_html__('This external URL will be displayed instead of brand page.', App::get_domain()); ?></p>
		</div>
		<div class="form-field">
			<label><?php esc_html_e('Thumbnail', App::get_domain()); ?></label>
			<div class="rbb-media-wrap">
				<div style="float: left; margin-right: 10px;" class="rbb-media-image">
					<img alt="thumbnail" src="<?php echo esc_url(Helper::get_image_placeholder()); ?>" width="60px" height="60px" data-src-placeholder="<?php echo esc_attr(Helper::get_image_placeholder()); ?>"
					/></div>
				<div style="line-height: 60px;">
					<input type="hidden" class="rbb-media-input" name="<?php echo esc_attr(self::TAXONOMY_NAME . '_thumbnail_id'); ?>"/>
					<button type="button"
							style="margin-top:15px"
							class="rbb-media-upload button"><?php esc_html_e('Upload/Add image', App::get_domain()); ?></button>
					<button type="button"
							style="margin-top:15px; margin-left:10px"
							class="rbb-media-remove button"><?php esc_html_e('Remove image', App::get_domain()); ?></button>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Edit brand.
	 *
	 * @param mixed $term Term.
	 * @return void
	 */
	public function edit_brand_fields( $term ): void {

		$thumbnail_id = absint(get_term_meta($term->term_id, 'thumbnail_id', true));

		if ( $thumbnail_id ) {
			$thumbnail = wp_get_attachment_thumb_url($thumbnail_id);
		} else {
			$thumbnail = Helper::get_image_placeholder();
		}

		$url = get_term_meta($term->term_id, 'url', true);
		?>
		<tr class="form-field">
			<th scope="row"><label for="<?php echo esc_attr(self::TAXONOMY_NAME . '_url'); ?>"><?php esc_html_e('URL', App::get_domain()); ?></label></th>
			<td>
				<input id="<?php echo esc_attr(self::TAXONOMY_NAME . '_url'); ?>" type="text" name="<?php echo esc_attr(self::TAXONOMY_NAME . '_url'); ?>" value="<?php echo esc_attr($url); ?>"/>
				<p><?php esc_html__('This external URL will be displayed instead of brand page.', App::get_domain()); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label><?php esc_html__('Thumbnail', App::get_domain()); ?></label>
			</th>
			<td>
				<div class="rbb-media-wrap">
					<div style="float: left; margin-right: 10px;" class="rbb-media-image">
						<img alt="thumbnail" src="<?php echo esc_url($thumbnail); ?>" width="60px" height="60px" data-src-placeholder="<?php echo esc_attr(Helper::get_image_placeholder()); ?>"/>
					</div>
					<div style="line-height: 60px;">
						<input type="hidden" class="rbb-media-input" name="<?php echo esc_attr(self::TAXONOMY_NAME . '_thumbnail_id'); ?>" value="<?php echo esc_attr($thumbnail_id); ?>"/>
						<button type="button" class="rbb-media-upload button" style="margin-top:15px;">
							<?php echo esc_html__('Upload/Add image', App::get_domain()); ?>
						</button>
						<button type="button" class="rbb-media-remove button" style="margin-top:15px;">
							<?php echo esc_html__('Remove image', App::get_domain()); ?>
						</button>
					</div>
					<div class="clear"></div>
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save Brand.
	 *
	 * @param mixed $term_id Term ID.
	 * @param mixed $tt_id Term taxonomy ID.
	 * @param mixed $taxonomy Taxonomy slug.
	 * @return void
	 */
	public function save_brands_fields( $term_id, $tt_id, $taxonomy ): void {
		//phpcs:ignore
        if ( self::TAXONOMY_NAME === $taxonomy ) {
			$thumbnail_id_var = self::TAXONOMY_NAME . '_thumbnail_id';
            //phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( ! empty($_POST[ $thumbnail_id_var ]) ) {
				//phpcs:ignore WordPress.Security.NonceVerification.Missing
				update_term_meta($term_id, 'thumbnail_id', absint($_POST[ $thumbnail_id_var ]));
			} else {
				delete_term_meta($term_id, 'thumbnail_id');
			}
			$url_var = self::TAXONOMY_NAME . '_url';
	        //phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( isset($_POST[ $url_var ]) ) {
				//phpcs:ignore
				update_term_meta($term_id, 'url', esc_url_raw($_POST[ $url_var ]));
			}
			delete_transient('wc_term_counts');
		}
	}

	/**
	 * Add script to admin.
	 *
	 * @return void
	 */
	public function admin_scripts(): void {
		$screen = get_current_screen();
		if ( $screen && 'edit-' . self::TAXONOMY_NAME === $screen->id ) {
			wp_enqueue_media();
			wp_enqueue_script('rbb-media-upload');
		}
	}

	/**
	 * Modify Metabox Args.
	 *
	 * @param mixed $args Args.
	 * @return mixed
	 */
	public function checklist_args( $args ) {
		if ( self::TAXONOMY_NAME === $args['taxonomy'] ) {
			$args['checked_ontop'] = false;
		}
		return $args;
	}
}

?>
