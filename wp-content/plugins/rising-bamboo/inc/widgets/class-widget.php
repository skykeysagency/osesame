<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 * @version 1.0.0
 * @since 1.0.0
 */

namespace RisingBambooCore\Widgets;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Helper as RbbCoreHelper;
use RisingBambooCore\Helper\Woocommerce;
use RisingBambooCore\Helper\Woocommerce as RbbCoreHelperWoocommerce;

/**
 * Manager Widget.
 */
class Widget extends Singleton {
	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('widgets_init', [ $this, 'register_widgets' ], 11);
		add_action('rbb_filter_active_bar', [ $this, 'rbb_filter_active_bar' ]);
	}

	/**
	 * Register widgets.
	 *
	 * @return void
	 */
	public function register_widgets(): void {
		if ( Helper::woocommerce_activated() ) {
			register_widget(ProductCategories::class);
			register_widget(PriceFilter::class);
			register_widget(BrandFilter::class);
			register_widget(ProductTags::class);
			register_widget(RatingFilter::class);
			register_widget(AttributeFilter::class);
		}
	}

	/**
	 * Filter Active Bar.
	 *
	 * @return void
	 */
	public function rbb_filter_active_bar(): void {
		$filter_link = RbbCoreHelperWoocommerce::get_current_page_url();
        //phpcs:ignore
		$filters     = $_GET;
		$clear_links = [];
		foreach ( $filters as $filter_name => $value ) {
			$taxonomy_name  = ( 0 === strpos($filter_name, 'filter_') ) ? wc_sanitize_taxonomy_name(str_replace('filter_', '', $filter_name)) : '';
			$attribute_name = wc_attribute_taxonomy_name($taxonomy_name);
			$attribute_id   = ! empty($attribute_name) ? wc_attribute_taxonomy_id_by_name($attribute_name) : 0;
			// This is taxonomy filter like category, tag, brand...
			if ( ! empty($taxonomy_name) && taxonomy_exists($taxonomy_name) ) {
				$taxonomy = get_taxonomy($taxonomy_name);
				if ( 'product_cat' === $taxonomy_name ) {
					$filter_terms = Woocommerce::get_chosen_term(ProductCategories::TAXONOMY_NAME);
				} else {
					$filter_terms = ! empty($value) ? explode(',', wc_clean(wp_unslash($value))) : [];
				}

				$clear_links[ $taxonomy_name ] = [
					'name'  => $taxonomy->labels->singular_name,
					'clear' => remove_query_arg($filter_name, $filter_link),
				];

				foreach ( $filter_terms as $key => $term_id ) {
					$clear_link = $filter_link;
					$clear_link = remove_query_arg($filter_name, $clear_link);
					$term       = get_term_by('id', $term_id, $taxonomy_name);
					if ( empty($term) ) {
						continue;
					}
					$clone_terms = $filter_terms;
					unset($clone_terms[ $key ]);
					if ( empty($clone_terms) ) {
						$clear_link = remove_query_arg($filter_name, $clear_link);
					} else {
						// Re add.
						$clear_link = add_query_arg($filter_name, implode(',', $clone_terms), $clear_link);
					}
					$tooltip                                  = $taxonomy->labels->singular_name ?? __('filter', App::get_domain());
					$clear_links[ $taxonomy_name ]['links'][] = [
						'url'     => $clear_link,
						'text'    => $term->name,
						// translators: 1: Name.
						'tooltip' => sprintf(__('Remove this %s', App::get_domain()), $tooltip),
						'class'   => 'remove-filter filter-link',
					];
				}
			} elseif ( $attribute_id && taxonomy_exists($attribute_name) ) {
				// This is attribute filter like color, size...
				$filter_terms = ! empty($value) ? explode(',', wc_clean(wp_unslash($value))) : [];
				if ( ! empty($filter_terms) ) {
					$attribute_info                          = wc_get_attribute($attribute_id);
					$clear_links[ $attribute_name ]['name']  = $attribute_info->name;
					$clear_links[ $attribute_name ]['clear'] = remove_query_arg($filter_name, $filter_link);
					foreach ( $filter_terms as $key => $term_slug ) {
						$clear_link = $filter_link;
						$clear_link = remove_query_arg($filter_name, $clear_link);
						$term       = get_term_by('slug', $term_slug, $attribute_name);
						if ( ! empty($term) ) {
							$clone_terms = $filter_terms;
							unset($clone_terms[ $key ]);
							if ( empty($clone_terms) ) {
								$clear_link = remove_query_arg($filter_name, $clear_link);
							} else {
								// Re add.
								$clear_link = add_query_arg($filter_name, implode(',', $clone_terms), $clear_link);
							}

							$clear_links[ $attribute_name ]['links'][] = [
								'url'     => $clear_link,
								'text'    => $term->name,
								// translators: 1 Atrribute name.
								'tooltip' => sprintf(__('Remove This %s', App::get_domain()), $attribute_info->name),
								'class'   => 'remove-filter filter-link',
							];
						}
					}
				}
			} elseif ( 'rating_filter' === $filter_name ) {
				$filter_values = ! empty($value) ? explode(',', wc_clean(wp_unslash($value))) : [];

				if ( ! empty($filter_values) ) {
					$clear_links[ $filter_name ]['name']  = __('Rating', App::get_domain());
					$clear_links[ $filter_name ]['clear'] = remove_query_arg($filter_name, $filter_link);
					foreach ( $filter_values as $key => $filter_value ) {
						$clear_link = $filter_link;
						$clear_link = remove_query_arg($filter_name, $clear_link);

						$clone_values = $filter_values;
						unset($clone_values[ $key ]);

						if ( empty($clone_values) ) {
							$clear_link = remove_query_arg($filter_name, $clear_link);
						} else {
							// Re add.
							$clear_link = add_query_arg($filter_name, implode(',', $clone_values), $clear_link);
						}

						$clear_links[ $filter_name ]['links'][] = [
							'url'     => $clear_link,
							// translators: 1 single 2 plural.
							'text'    => sprintf(_n('%s star', '%s stars', $filter_value, App::get_domain()), $filter_value),
							// translators: 1 single 2 plural.
							'tooltip' => sprintf(__('Remove This %s', App::get_domain()), __('Rating', App::get_domain())),
							'class'   => 'remove-filter filter-link',
						];
					}
				}
			}
		}

		View::instance()->load('widgets/filter-active-bar', compact('clear_links'));
	}
}
