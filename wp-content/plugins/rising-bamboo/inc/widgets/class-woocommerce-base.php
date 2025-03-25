<?php
/**
 * RisingBambooCore Widget.
 *
 * @package RisingBambooCore
 * @version 1.0.0
 * @since 1.0.0
 */

namespace RisingBambooCore\Widgets;

use RisingBambooCore\Helper\Helper as RbbCoreHelper;
use RisingBambooCore\Woocommerce\Brands;
use WC_Query;
use WC_Tax;
use WP_Tax_Query;
use WP_Meta_Query;

/**
 * Widget Base.
 */
class WoocommerceBase extends Base {
	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
		parent::__construct();
		add_action('save_post', [ $this, 'flush_count_cache' ]);
	}

	/**
	 * Get Filtered Term Product Count.
	 *
	 * @param mixed $term_ids Term ID.
	 * @param mixed $taxonomy Taxonomy.
	 * @param mixed $query_type Query Type.
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ): array {
		global $wpdb;

		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();

		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array($query) && $taxonomy === $query['taxonomy'] ) {
					unset($tax_query[ $key ]);
				}
			}
		}

		$meta_query      = new WP_Meta_Query($meta_query);
		$tax_query       = new WP_Tax_Query($tax_query);
		$meta_query_sql  = $meta_query->get_sql('post', $wpdb->posts, 'ID');
		$tax_query_sql   = $tax_query->get_sql($wpdb->posts, 'ID');
		$price_query_sql = self::get_main_price_query_sql();

		// Generate query.
		$query           = [];
		$query['select'] = "SELECT COUNT( DISTINCT $wpdb->posts.ID ) as term_count, $wpdb->posts.ID, terms.term_id as term_count_id";
		$query['from']   = "FROM $wpdb->posts";
		$query['join']   = "
			INNER JOIN $wpdb->term_relationships AS term_relationships ON $wpdb->posts.ID = term_relationships.object_id
			INNER JOIN $wpdb->term_taxonomy AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN $wpdb->terms AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];

		$query['where'] = "
			WHERE $wpdb->posts.post_type IN ( 'product' )
			AND $wpdb->posts.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'] . '
			AND terms.term_id IN (' . implode(',', array_map('absint', $term_ids)) . ')
		';

		$search_query_sql = self::get_main_search_query_sql();
		if ( $search_query_sql ) {
			$query['where'] .= ' AND ' . $search_query_sql;
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
        //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$query     = apply_filters('woocommerce_get_filtered_term_product_counts_query', $query);
		$query_sql = implode(' ', $query);

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5($query_sql);
		// Maybe store a transient of the count values.
        //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$cache     = apply_filters('woocommerce_layered_nav_count_maybe_cache', true);
		$cache_key = 'wc_layered_nav_counts_' . sanitize_title($taxonomy);
		if ( true === $cache ) {
			$cached_counts = (array) get_transient($cache_key);
		} else {
			$cached_counts = [];
		}

		if ( ! isset($cached_counts[ $query_hash ]) ) {
            //phpcs:ignore
			$results = $wpdb->get_results($query_sql, ARRAY_A);
			$counts  = array_map('absint', wp_list_pluck($results, 'term_count', 'term_count_id'));

			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient($cache_key, $cached_counts, DAY_IN_SECONDS);
			}
		}

		return array_map('absint', (array) $cached_counts[ $query_hash ]);
	}

	/**
	 * Get Main Price SQL.
	 *
	 * @param mixed $min_price Min Price.
	 * @param mixed $max_price Max Price.
	 * @return string[]
	 */
	public static function get_main_price_query_sql( $min_price = null, $max_price = null ): array {
		global $wp_query;
		global $wpdb;

		$args = $wp_query->query_vars;

		$sql = [
			'join'  => '',
			'where' => '',
		];

		$current_min_price = null;

		if ( isset($min_price, $max_price) ) {
			$current_min_price = (float) $min_price;
			$current_max_price = (float) $max_price;
		} elseif ( isset($args['min_price'], $args['max_price']) ) {
			// phpcs:enable WordPress.Security.NonceVerification.Recommended
			$current_min_price = (float) wp_unslash($args['min_price']);
			$current_max_price = (float) wp_unslash($args['max_price']);
		}

		if ( isset($current_min_price, $current_max_price) ) {
			/**
			 * Adjust if the store taxes are not displayed how they are stored.
			 * Kicks in when prices excluding tax are displayed including tax.
			 */
			if ( wc_tax_enabled() && 'incl' === get_option('woocommerce_tax_display_shop') && ! wc_prices_include_tax() ) {
                //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				$tax_class = apply_filters('woocommerce_price_filter_widget_tax_class', ''); // Uses standard tax class.
				$tax_rates = WC_Tax::get_rates($tax_class);

				if ( $tax_rates ) {
					$current_min_price -= WC_Tax::get_tax_total(WC_Tax::calc_inclusive_tax($current_min_price, $tax_rates));
					$current_max_price -= WC_Tax::get_tax_total(WC_Tax::calc_inclusive_tax($current_max_price, $tax_rates));
				}
			}

			$sql['join']  = " LEFT JOIN $wpdb->wc_product_meta_lookup wc_product_meta_lookup ON $wpdb->posts.ID = wc_product_meta_lookup.product_id ";
			$sql['where'] = $wpdb->prepare(
				' AND NOT (%f<wc_product_meta_lookup.min_price OR %f>wc_product_meta_lookup.max_price ) ',
				$current_max_price,
				$current_min_price
			);
		}

		return $sql;
	}

	/**
	 * Get main search query sql.
	 *
	 * @return string
	 */
	public static function get_main_search_query_sql(): string {
		global $wpdb;
		global $wp_query;

		$args = $wp_query->query_vars;

		$search_terms = $args['search_terms'] ?? [];
		$sql          = [];

		foreach ( $search_terms as $term ) {
			// Terms prefixed with '-' should be excluded.
			$include = strpos($term, '-') !== 0;

			if ( $include ) {
				$like_op  = 'LIKE';
				$andor_op = 'OR';
			} else {
				$like_op  = 'NOT LIKE';
				$andor_op = 'AND';
				$term     = substr($term, 1);
			}

			$like = '%' . $wpdb->esc_like($term) . '%';
            // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$sql[] = $wpdb->prepare("(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_excerpt $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s))", $like, $like, $like);
		}

		if ( ! empty($sql) && ! is_user_logged_in() ) {
			$sql[] = "($wpdb->posts.post_password = '')";
		}

		return implode(' AND ', $sql);
	}

	/**
	 * Enqueue Scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		RbbCoreHelper::register_asset('rbb-ajax-load-link', 'js/frontend/widgets/ajax-load-link.js', [ 'rbb-woocommerce-filters' ], '1.0.0');
		wp_enqueue_script('rbb-ajax-load-link');
	}

	/**
	 * Flush all count cache.
	 *
	 * @param mixed $post_id Post ID.
	 * @return void
	 */
	public function flush_count_cache( $post_id ): void {
		if ( 'product' === get_post_type($post_id) ) {
			$exclude    = [ 'product_visibility', 'product_type', 'product_shipping_class' ];
			$taxonomies = array_diff(get_post_taxonomies($post_id), $exclude);
			foreach ( $taxonomies as $taxonomy ) {
				delete_transient('wc_layered_nav_counts_' . sanitize_title($taxonomy));
			}
		}
	}
}
