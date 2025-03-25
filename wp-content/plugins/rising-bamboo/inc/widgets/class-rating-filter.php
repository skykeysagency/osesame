<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Widgets;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\Woocommerce as RbbCoreHelperWoocommerce;
use WC_Query;
use WP_Meta_Query;
use WP_Tax_Query;

/**
 * Product Categories Widget.
 */
class RatingFilter extends WoocommerceBase {

	/**
	 * Variable to check result return.
	 *
	 * @var bool
	 */
	public bool $found = false;

	/**
	 * Construction.
	 */
	public function __construct() {
		add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
		$this->id          = 'rbb-widget--product-rating';
		$this->css_class   = 'rbb-widget--product-rating';
		$this->name        = '[Rbb] ' . __('Filter Products By Rating', App::get_domain());
		$this->description = __('Filter products by rating', App::get_domain());

		$this->settings = [
			'title' => [
				'type'    => 'text',
				'default' => __('Average rating', App::get_domain()),
				'label'   => __('Title', App::get_domain()),
			],
			'count' => [
				'type'    => 'checkbox',
				'default' => 0,
				'label'   => __('Show product counts', App::get_domain()),
			],
		];

		parent::__construct();
	}

	/**
	 * The Widget HTML.
	 *
	 * @param mixed $args     Params.
	 * @param mixed $instance Instance.
	 * @return void
	 */
	public function widget( $args, $instance ): void {
		if ( ( is_shop() || is_product_taxonomy() ) && WC()->query->get_main_query()->post_count ) {
			$widget        = $this;
			$rating_filter = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['rating_filter'] ) ) ) ) : array(); // phpcs:ignore
			$base_link     = RbbCoreHelperWoocommerce::get_current_page_url();
			$show_count    = $this->get_setting($instance, 'count');

			ob_start();

			$this->widget_start($args, $instance);

			View::instance()->load('widgets/rating-filter/default', compact('widget', 'rating_filter', 'base_link', 'show_count', 'instance'));

			$this->widget_end($args);
			if ( $this->found ) {
                //phpcs:ignore
				echo ob_get_clean();
			} else {
				ob_end_clean();
			}
			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}
	}

	/**
	 * Count products after other filters have occurred by adjusting the main query.
	 *
	 * @param int $rating Rating.
	 * @return int
	 */
	public function get_filtered_product_count( int $rating ): int {
		global $wpdb;

		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();

		// Unset current rating filter.
		foreach ( $tax_query as $key => $query ) {
			if ( ! empty($query['rating_filter']) ) {
				unset($tax_query[ $key ]);
				break;
			}
		}

		// Set new rating filter.
		$product_visibility_terms = wc_get_product_visibility_term_ids();
		$tax_query[]              = [
			'taxonomy'      => 'product_visibility',
			'field'         => 'term_taxonomy_id',
			'terms'         => $product_visibility_terms[ 'rated-' . $rating ],
			'operator'      => 'IN',
			'rating_filter' => true,
		];

		$meta_query     = new WP_Meta_Query($meta_query);
		$tax_query      = new WP_Tax_Query($tax_query);
		$meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
		$tax_query_sql  = $tax_query->get_sql($wpdb->posts, 'ID');

		$sql  = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
		$sql .= $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		$search = WC_Query::get_main_search_query_sql();
		if ( $search ) {
			$sql .= ' AND ' . $search;
		}

		return absint($wpdb->get_var($sql)); // phpcs:ignore
	}
}
