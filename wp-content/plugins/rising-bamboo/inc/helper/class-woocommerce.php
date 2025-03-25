<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\Helper;

use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Setting as RbbCoreSettingHelper;
use WC_Product_Query;
use WC_Query;

/**
 * Rising Bamboo Woocommerce Class.
 */
class Woocommerce extends Singleton {



	/**
	 * Get woocommerce version.
	 *
	 * @return false|mixed|null
	 */
	public static function get_version() {
		return get_option('woocommerce_version', null);
	}

	/**
	 * Get category by ID.
	 *
	 * @param int | array $id ID.
	 * @return array|\WP_Error|null
	 */
	public static function get_category_by_id( $id ) {
		return get_term_by('id', $id, 'product_cat');
	}

	/**
	 * Get products has upsell.
	 *
	 * @param mixed $limit Limit.
	 * @return array|object
	 */
	public static function get_products_has_upsell( $limit = -1 ) {
		$result = [];
		$args   = [
			'status'   => 'publish',
			'meta_key' => '_upsell_ids', //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'limit'    => $limit,
		];
		if ( class_exists(WC_Product_Query::class) ) {
			$result = ( new WC_Product_Query($args) )->get_products();
		}
		return $result;
	}

	/**
	 * Get products.
	 *
	 * @param int|array $ids Ids of product or category.
	 * @param string    $by ID or category id.
	 * @param string    $order_by Order By.
	 * @param string    $order Asc or desc.
	 * @param int       $limit Limit.
	 * @return array|object
	 */
	public static function get_products( $ids, string $by = 'id', string $order_by = 'latest', string $order = 'desc', int $limit = -1 ) {
		$args = [
			'status' => 'publish',
		];
		if ( 'category' === $by ) {
			$cats     = self::get_categories_by_ids($ids);
			$cat_slug = [];
			foreach ( $cats as $cat ) {
				$cat_slug[] = $cat->slug;
			}
			$args['category'] = $cat_slug;
			$args['limit']    = $limit;
		} elseif ( 'id' === $by ) {
			$args['include'] = (array) $ids;
			$args['limit']   = -1;
		}
		switch ( $order_by ) {
			case 'random':
				$args['orderby'] = 'rand';
				break;
			case 'latest':
				$args['orderby'] = 'date';
				$args['order']   = 'desc';
				break;
			case 'price':
				$args['meta_key'] = '_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = 'meta_value_num';
				$args['order']    = $order;
				break;
			case 'best-selling':
				$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = 'meta_value_num';
				break;
			case 'rating':
				$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$args['orderby']  = '_wc_average_rating';
				$args['order']    = $order;
				break;
			default:
				$args['orderby'] = 'include';
		}
		return ( new WC_Product_Query($args) )->get_products();
	}

	/**
	 * Get categories by ids.
	 *
	 * @param mixed  $ids Ids.
	 * @param string $oder_by Order By.
	 * @param string $oder Order.
	 * @return int[]|string|string[]|\WP_Error|\WP_Term[]
	 */
	public static function get_categories_by_ids( $ids, string $oder_by = 'none', string $oder = 'ASC' ) {
		return get_terms(
			[
				'taxonomy' => 'product_cat',
				'include'  => (array) $ids,
				'orderby'  => $oder_by,
				'order'    => $oder,
			]
		);
	}

	/**
	 * Get attachment image.
	 *
	 * @param \WC_Product $product Product.
	 * @param mixed       $size Size.
	 * @return array
	 */
	public static function get_gallery_image( \WC_Product $product, $size = 'thumbnail' ): array {
		$attachment_ids = $product->get_gallery_image_ids();
		$images         = [];
		foreach ( $attachment_ids as $attachment_id ) {
			$image_query = wp_get_attachment_image_src($attachment_id, $size);
			if ( $image_query ) {
				$image      = new \StdClass();
				$image->src = $image_query[0];
				$images[]   = $image;
			}
		}
		return $images;
	}

	/**
	 * Get flat categories.
	 *
	 * @param int    $parent Parent.
	 * @param array  $args Args.
	 * @param string $pad Pad.
	 * @param array  $return Return.
	 * @return array
	 */
	public static function get_flat_categories( int $parent = 0, array $args = [], string $pad = ' ', array $return = [] ): array {
		return Helper::get_flat_taxonomy_hierarchy('product_cat', $parent, $args, $pad, $return);
	}

	/**
	 * Chosen categories.
	 *
	 * @param string $term Taxonomy.
	 * @return array
	 */
	public static function get_chosen_term( string $term = 'product_cat' ): array {
		$return = [];
        //phpcs:ignore
        $filter_term = $_GET['filter_' . $term] ?? null;
		if ( ! empty($filter_term) ) {
            //phpcs:ignore
            $return = array_map('intval', explode(',', $filter_term));
		}
		$current_term = self::get_term_id($term);
		if ( $current_term && ! in_array($current_term, $return, true) ) {
			array_unshift($return, $current_term);
		}
		return $return;
	}

	/**
	 * Get term id.
	 *
	 * @param string $taxonomy Taxonomy Name.
	 * @return int
	 */
	public static function get_term_id( string $taxonomy = 'product_cat' ): int {
		return absint(is_tax($taxonomy) ? get_queried_object()->term_id : 0);
	}

	/**
	 * Get term slug.
	 *
	 * @return array|int|mixed|string
	 */
	public static function get_term_slug() {
		return is_tax('product_cat') ? get_queried_object()->slug : '';
	}

	/**
	 * Detect product archive page.
	 *
	 * @return bool
	 */
	public static function is_product_archive(): bool {
		return is_tax('product_cat') || is_post_type_archive('product');
	}

	/**
	 * Method in Woocommerce.
	 *
	 * @return mixed|null
	 */
	public static function get_current_page_url() {
		$link = self::get_base_url();

        // phpcs:disable
        if (RbbCoreSettingHelper::get_option('development_override_settings') && !empty($_GET['override_profile'])) {
            $link = add_query_arg('override_profile', wc_clean(wp_unslash($_GET['override_profile'])), $link);
        }
        if (isset($_GET['min_price'])) {
            $link = add_query_arg('min_price', wc_clean(wp_unslash($_GET['min_price'])), $link);
        }
        if (isset($_GET['max_price'])) {
            // phpcs:ignore
            $link = add_query_arg('max_price', wc_clean(wp_unslash($_GET['max_price'])), $link);
        }
        if (isset($_GET['orderby'])) {
            $link = add_query_arg('orderby', wc_clean(wp_unslash($_GET['orderby'])), $link);
        }

        /**
         * Search Arg.
         * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
         */
        if (get_search_query()) {
            $link = add_query_arg('s', rawurlencode(htmlspecialchars_decode(get_search_query())), $link);
        }

        if (isset($_GET['post_type'])) {

            $link = add_query_arg('post_type', wc_clean(wp_unslash($_GET['post_type'])), $link);

            // Prevent post type and page id when pretty permalinks are disabled.
            if (is_shop()) {
                $link = remove_query_arg('page_id', $link);
            }
        }

        if (isset($_GET['rating_filter'])) {

            $link = add_query_arg('rating_filter', wc_clean(wp_unslash($_GET['rating_filter'])), $link);
        }

        if (isset($_GET['filter_product_cat'])) {

            $link = add_query_arg('filter_product_cat', wc_clean(wp_unslash($_GET['filter_product_cat'])), $link);
        }

        if (isset($_GET['filter_product_brand'])) {

            $link = add_query_arg('filter_product_brand', wc_clean(wp_unslash($_GET['filter_product_brand'])), $link);
        }

        //phpcs:enable

		// All current filters.
		$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
		if ( $_chosen_attributes ) {
			foreach ( $_chosen_attributes as $name => $data ) {
				$filter_name = wc_attribute_taxonomy_slug($name);
				if ( ! empty($data['terms']) ) {
					$link = add_query_arg('filter_' . $filter_name, implode(',', $data['terms']), $link);
				}
				if ( 'or' === $data['query_type'] ) {
					$link = add_query_arg('query_type_' . $filter_name, 'or', $link);
				}
			}
		}
        //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		return apply_filters('rbb_woocommerce_get_current_url', $link);
	}

	/**
	 * Get base url.
	 *
	 * @return mixed
	 */
	public static function get_base_url() {
		if ( defined('SHOP_IS_ON_FRONT') ) {
			$link = home_url();
		} elseif ( is_shop() ) {
			$link = get_permalink(wc_get_page_id('shop'));
		} elseif ( is_product_tag() ) {
			$link = get_term_link(get_query_var('product_tag'), 'product_tag');
		} else {
			$queried_object = get_queried_object();
			$link           = $queried_object ? get_term_link($queried_object->slug, $queried_object->taxonomy) : '';
		}
		return $link;
	}

	/**
	 * Get thumbnail image width.
	 *
	 * @return int
	 */
	public static function get_thumbnail_image_width() {
		return absint(wc_get_theme_support('thumbnail_image_width', get_option('woocommerce_thumbnail_image_width', 300)));
	}
}
