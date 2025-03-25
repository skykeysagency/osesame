<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Widgets;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\Helper as RbbCoreHelper;
use WC_Query;
use WC_Tax;
use WP_Meta_Query;
use WP_Tax_Query;

/**
 * Product Categories Widget.
 */
class PriceFilter extends WoocommerceBase {


	/**
	 * Construct.
	 */
	public function __construct() {
		RbbCoreHelper::register_asset('rbb-widget-price-filter-slider', 'js/frontend/widgets/price-filter/slider.js', [ 'jquery' ], '1.0.0');
		$this->id          = 'rbb-widget--price-filters';
		$this->css_class   = 'rbb-widget--price-filters';
		$this->name        = '[Rbb] ' . __('Filter Products By Price', App::get_domain());
		$this->description = __('Filter products by Price', App::get_domain());

		$this->settings = [
			'title' => [
				'type'    => 'text',
				'default' => __('Price', App::get_domain()),
				'label'   => __('Title', App::get_domain()),
			],
			'display' => [
				'type'    => 'select',
				'default' => 'slider',
				'label'   => __('Display as', App::get_domain()),
				'options' => [
					'slider' => esc_html__('Slider', App::get_domain()),
					'list'   => esc_html__('List', App::get_domain()),
				],
			],
			'step' => [
				'type'      => 'number',
				'default'   => 5,
				'label'     => __('Step', App::get_domain()),
				'desc'      => __('This setting will be applied when its value is less than 10% of the price range.', App::get_domain()),
				'condition' => [
					'display' => 'slider',
				],
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
		// Requires lookup table added in 3.6.

		if ( version_compare(get_option('woocommerce_db_version', null), '3.6', '<') ) {
			return;
		}

		if ( is_shop() || is_product_taxonomy() ) {

			$display = $this->get_setting($instance, 'display');

			$price = $this->get_filtered_price();

			$min_price = $price->min_price;
			$max_price = $price->max_price;

			// Check to see if we should add taxes to the prices if store are excl tax but display incl.
			$tax_display_mode = get_option('woocommerce_tax_display_shop');

			if ( 'incl' === $tax_display_mode && wc_tax_enabled() && ! wc_prices_include_tax() ) {

				//phpcs:ignore
				$tax_class = apply_filters('woocommerce_price_filter_widget_tax_class', '');
				$tax_rates = WC_Tax::get_rates($tax_class);

				if ( $tax_rates ) {
					$min_price += WC_Tax::get_tax_total(WC_Tax::calc_exclusive_tax($min_price, $tax_rates));
					$max_price += WC_Tax::get_tax_total(WC_Tax::calc_exclusive_tax($max_price, $tax_rates));
				}
			}

			if ( $min_price === $max_price ) {
				return;
			}

			$step = $this->get_step($min_price, $max_price, $this->get_setting($instance, 'step'));

			wp_enqueue_script('rbb-widget-price-filter-slider');

			$min_price_processed = floor($min_price / $step) * $step;
			$max_price_processed = ceil($max_price / $step) * $step;

			//phpcs:ignore
			$current_min_price = isset($_GET['min_price']) ? floor((float)wp_unslash($_GET['min_price']) / $step) * $step : $min_price_processed;
			//phpcs:ignore
			$current_max_price = isset($_GET['max_price']) ? ceil((float)wp_unslash($_GET['max_price']) / $step) * $step : $max_price_processed;

			ob_start();

			$this->widget_start($args, $instance);
			if ( 'slider' === $display ) {

				$left      = ( $current_min_price - $min_price_processed ) / ( $max_price_processed - $min_price_processed ) * 100 . '%';
				$right     = ( $max_price_processed - $current_max_price ) / ( $max_price_processed - $min_price_processed ) * 100 . '%';
				$price_gap = ceil(( ( $max_price - $min_price ) * 10 ) / 100);

				View::instance()->load(
					'widgets/price-filter/slider',
					[
						'step'              => $step,
						'min_price'         => (int) $min_price_processed,
						'max_price'         => (int) $max_price_processed,
						'current_min_price' => (int) $current_min_price,
						'current_max_price' => (int) $current_max_price,
						'left'              => $left,
						'right'             => $right,
						'price_gap'         => $price_gap,
					]
				);
			} else {
				View::instance()->load(
					'widgets/price-filter/list',
					[
						'step'              => $step,
						'min_price'         => $min_price,
						'max_price'         => $max_price,
						'current_min_price' => $current_min_price,
						'current_max_price' => $current_max_price,
					]
				);
			}

			$this->widget_end($args);

			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo ob_get_clean();
		}
	}

	/**
	 * Get Filtered Price.
	 *
	 * @return array|object|\stdClass|null
	 */
	protected function get_filtered_price() {
		global $wpdb;

		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = $args['tax_query'] ?? [];
		$meta_query = $args['meta_query'] ?? [];

		if ( ! empty($args['taxonomy']) && ! empty($args['term']) && ! is_post_type_archive('product') ) {
			$tax_query[] = WC()->query->get_main_tax_query();
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty($query['price_filter']) || ! empty($query['rating_filter']) ) {
				unset($meta_query[ $key ]);
			}
		}

		$meta_query = new WP_Meta_Query($meta_query);
		$tax_query  = new WP_Tax_Query($tax_query);
		$search     = WC_Query::get_main_search_query_sql();

		$meta_query_sql   = $meta_query->get_sql('post', $wpdb->posts, 'ID');
		$tax_query_sql    = $tax_query->get_sql($wpdb->posts, 'ID');
		$search_query_sql = $search ? ' AND ' . $search : '';
        //phpcs:disable
		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_post_type', [ 'product' ]))) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';
		$sql = apply_filters('woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql);
        //phpcs:enable
		return $wpdb->get_row($sql); // phpcs:ignore
	}

	/**
	 * Get Step.
	 *
	 * @param mixed $min  Min Price.
	 * @param mixed $max  Max Price.
	 * @param mixed $step Step.
	 * @return int
	 */
	public function get_step( $min, $max, $step ): int {
		$step  = $step ?? 1;
		$check = ceil(( ( $max - $min ) * 10 ) / 100);
		if ( $step > $check ) {
			$step = $check;
		}
		return (int) $step;
	}
}
