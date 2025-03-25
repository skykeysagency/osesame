<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 * @version 1.0.0
 * @since   1.0.0
 */

namespace RisingBambooCore\Woocommerce;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Setting;
use WC_AJAX;
use WP_Term;

/**
 * Swatches.
 */
class Swatches extends Singleton {
	/**
	 * Prefix of field.
	 *
	 * @var string
	 */
	public const PREFIX = 'rbb_sw';

	/**
	 * Construct.
	 */
	public function __construct() {
		if ( Setting::get_option('woocommerce_swatches', true) ) {
			add_action('wp_enqueue_scripts', [ $this, 'frontend_scripts' ]);
			add_action('admin_enqueue_scripts', [ $this, 'admin_scripts' ]);
			add_action('init', [ $this, 'init' ]);
			add_action(
				'woocommerce_variable_add_to_cart',
				function () {
					wp_enqueue_script('wc-add-to-cart-variation');
				}
			);
		}
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter('product_attributes_type_selector', [ $this, 'product_attributes_type_selector' ]);

		$attributes = wc_get_attribute_taxonomies();

		foreach ( $attributes as $attribute ) {
			add_action(
				'pa_' . $attribute->attribute_name . '_add_form_fields',
				[
					$this,
					'fields',
				]
			);
			add_action(
				'pa_' . $attribute->attribute_name . '_edit_form_fields',
				[
					$this,
					'fields',
				]
			);
			add_action(
				'create_pa_' . $attribute->attribute_name,
				[
					$this,
					'save',
				]
			);
			add_action('edited_pa_' . $attribute->attribute_name, [ $this, 'save' ]);
			add_filter(
				"manage_edit-pa_{$attribute->attribute_name}_columns",
				[
					$this,
					'custom_columns',
				]
			);
			add_filter(
				"manage_pa_{$attribute->attribute_name}_custom_column",
				[
					$this,
					'custom_column',
				],
				10,
				3
			);
		}
		remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
		add_action('woocommerce_single_product_summary', [ $this, 'swatches_product_detail' ], 20);
	}

	/**
	 * Frontend Scripts.
	 *
	 * @return void
	 */
	public function frontend_scripts(): void {
		Helper::register_asset('rbb-swatches', 'js/frontend/components/swatches.js', [ 'jquery' ], '1.0.0', true);
		wp_enqueue_script('rbb-swatches');
	}

	/**
	 * Admin Scripts
	 *
	 * @return void
	 */
	public function admin_scripts(): void {
	}

	/**
	 * Hook callback.
	 *
	 * @param mixed $types Type.
	 * @return mixed
	 */
	public function product_attributes_type_selector( $types ) {
		global $pagenow;
		if ( ( 'post-new.php' !== $pagenow ) && ( 'post.php' !== $pagenow ) && ! wp_doing_ajax() ) {
			$types['select'] = esc_html__('Select', App::get_domain());
			$types['text']   = esc_html__('Text', App::get_domain());
			$types['color']  = esc_html__('Color', App::get_domain());
			$types['image']  = esc_html__('Image', App::get_domain());
		}
		return $types;
	}

	/**
	 * Show Field.
	 *
	 * @param mixed $obj Taxonomy slug or Term.
	 * @return void
	 */
	public function fields( $obj ): void {
		$term_id   = $obj->term_id ?? 0;
		$attr_id   = wc_attribute_taxonomy_id_by_name($obj->taxonomy ?? $obj);
		$attr_info = wc_get_attribute($attr_id);

		if ( $obj instanceof WP_Term ) {
			$wrap_start = '<tr class="form-field"><th><label>';
			$wrap_mid   = '</label></th><td>';
			$wrap_end   = '</td></tr>';
		} else {
			$wrap_start = '<div class="form-field"><label>';
			$wrap_mid   = '</label>';
			$wrap_end   = '</div>';
		}

		if ( $attr_info ) {

			switch ( $attr_info->type ) {
				case 'text':
					$sw_val = get_term_meta($term_id, self::get_field('text'), true);
                    //phpcs:ignore
                    echo $wrap_start . esc_html__('Swatch Text', App::get_domain()) . $wrap_mid . '<input id="' . self::get_field('text') . '" name="' . self::get_field('text') . '" value="' . esc_attr($sw_val) . '" type="text"/>' . $wrap_end;
					break;
				case 'color':
					wp_enqueue_script('rbb-swatches-js');
					$sw_val = get_term_meta($term_id, self::get_field('color'), true);
                    //phpcs:ignore
                    echo $wrap_start . esc_html__('Swatch Color', App::get_domain()) . $wrap_mid . '<input class="rbb-swatches ' . self::get_field('color') . '" id="' . self::get_field('color') . '" name="' . self::get_field('color') . '" value="' . esc_attr($sw_val) . '" type="text"/>' . $wrap_end;
					break;
				case 'image':
					wp_enqueue_media();
					wp_enqueue_script('rbb-media-upload');
					$sw_val = get_term_meta($term_id, self::get_field('image'), true);
					if ( $sw_val ) {
						$image = wp_get_attachment_thumb_url($sw_val);
					} else {
						$image = wc_placeholder_img_src();
					}
                    //phpcs:ignore
                    echo $wrap_start . esc_html__('Swatch Image', App::get_domain()) . $wrap_mid; ?>
					<div class="rbb-media-wrap">
						<div class="rbb-media-image" style="float: left; margin-right: 10px;">
							<img alt="Swatch Image" src="<?php echo esc_url($image); ?>" width="60px" height="60px"/>
						</div>
						<div style="line-height: 60px;">
							<input type="hidden" class="rbb-media-input" id="<?php echo esc_attr(self::get_field('image')); ?>" name="<?php echo esc_attr(self::get_field('image')); ?>" value="<?php echo esc_attr($sw_val); ?>"/>
							<button type="button" class="rbb-media-upload button" style="margin-top:15px;">
								<?php esc_html_e('Upload/Add image', App::get_domain()); ?>
							</button>
							<button type="button" class="rbb-media-remove button" style="margin-top:15px;">
								<?php esc_html_e('Remove image', App::get_domain()); ?>
							</button>
						</div>
					</div>
					<?php
                    //phpcs:ignore
                    echo $wrap_end;
					break;
				default:
					echo '';
			}
		}
	}

	/**
	 * Get Field.
	 *
	 * @param string $name Name.
	 * @return string
	 */
	public static function get_field( string $name ): string {
		$field = '';
		if ( ! empty($name) ) {
			$field = self::PREFIX . '_' . $name;
		}
		return $field;
	}

	/**
	 * Save.
	 *
	 * @param int $term_id Term ID.
	 * @return void
	 */
	public function save( int $term_id ): void {
        //phpcs:ignore
        $action = $_POST['tag_ID'] ? 'update-tag_' . $_POST['tag_ID'] : 'add-tag';
        //phpcs:ignore
        $none = $_POST['_wpnonce'] ?? $_POST['_wpnonce_add-tag'];
		if ( wp_verify_nonce($none, $action) ) {
			if ( isset($_POST[ self::get_field('color') ]) ) {
				update_term_meta($term_id, self::get_field('color'), sanitize_text_field(wp_unslash($_POST[ self::get_field('color') ])));
			}
			if ( isset($_POST[ self::get_field('text') ]) ) {
				update_term_meta($term_id, self::get_field('text'), sanitize_text_field(wp_unslash($_POST[ self::get_field('text') ])));
			}
			if ( isset($_POST[ self::get_field('image') ]) ) {
				update_term_meta($term_id, self::get_field('image'), sanitize_text_field(wp_unslash($_POST[ self::get_field('image') ])));
			}
		}
	}

	/**
	 * Add custom column.
	 *
	 * @param mixed $columns Column.
	 * @return mixed
	 */
	public function custom_columns( $columns ) {
		$columns[ self::PREFIX . '_value' ] = 'Swatch Value';
		return $columns;
	}

	/**
	 * Custom column content.
	 *
	 * @param mixed $columns Columns.
	 * @param mixed $column Column.
	 * @param mixed $term_id Term ID.
	 * @return void
	 */
	public function custom_column( $columns, $column, $term_id ): void {
		if ( self::PREFIX . '_value' === $column ) {
			$term      = get_term($term_id);
			$attr_id   = wc_attribute_taxonomy_id_by_name($term->taxonomy);
			$attr_info = wc_get_attribute($attr_id);
			if ( $attr_info ) {
				switch ( $attr_info->type ) {
					case 'image':
						$val = get_term_meta($term_id, self::get_field('image'), true);
						if ( $val ) {
							echo '<img style="display: inline-block; width: 40px; height: 40px; background-color: #ccc; box-sizing: border-box; border: 1px solid #ccc;" src="' . esc_url(wp_get_attachment_thumb_url($val)) . '"/>';
						}
						break;
					case 'color':
						$val = get_term_meta($term_id, self::get_field('color'), true);
						if ( $val ) {
							echo '<span style="display: inline-block; width: 30px; height: 30px; box-sizing: border-box; background-color: ' . esc_attr($val) . '; border: 1px solid #ccc;border-radius: 100%;"></span>';
						}

						break;
					case 'text':
						$val = get_term_meta($term_id, self::get_field('text'), true);
						if ( $val ) {
							echo '<span style="display: inline-block; height: 40px; line-height: 40px; padding: 0 15px; border: 1px solid #ccc; background-color: #fff; min-width: 44px; box-sizing: border-box;">' . esc_html($val) . '</span>';
						}
						break;
				}
			}
		}
	}

	/**
	 * Show swatches to the product detail
	 *
	 * @return void
	 */
	public function swatches_product_detail(): void {
		global $product;
		if ( $product->is_type('variable') ) {
			$attributes           = $product->get_attributes();
			$available_variations = $product->get_available_variations();
			$variation_attributes = $product->get_variation_attributes();
			$selected_attributes  = $product->get_default_attributes();
			wc_get_template('single-product/swatches.php', compact('attributes', 'available_variations', 'variation_attributes', 'selected_attributes'));
		}
	}
}
