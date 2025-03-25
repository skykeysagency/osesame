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
use RisingBambooCore\Helper\Woocommerce as RbbCoreHelperWoocommerce;

/**
 * Product tag Widget.
 */
class ProductTags extends WoocommerceBase {

	public const TAXONOMY_NAME = 'product_tag';
	/**
	 * Chosen tag;
	 *
	 * @var array
	 */
	protected array $chosen;

	/**
	 * Current Cat ID.
	 *
	 * @var int
	 */
	protected int $current_cat;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->id          = 'rbb-widget--product-tags';
		$this->css_class   = 'rbb-widget--product-tags';
		$this->name        = '[Rbb] ' . __('Products Tags', App::get_domain());
		$this->description = __('products Tags', App::get_domain());

		$this->settings = [
			'title' => [
				'type'    => 'text',
				'default' => __('Product Tags', App::get_domain()),
				'label'   => __('Title', App::get_domain()),
			],
			'order_by' => [
				'type'    => 'select',
				'default' => 'name',
				'label'   => __('Order by', App::get_domain()),
				'options' => [
					'order' => __('Category order', App::get_domain()),
					'name'  => __('Name', App::get_domain()),
				],
			],
			'count' => [
				'type'    => 'checkbox',
				'default' => 0,
				'label'   => __('Show product counts', App::get_domain()),
			],
			'hide_empty' => [
				'type'    => 'checkbox',
				'default' => 0,
				'label'   => __('Hide empty tags', App::get_domain()),
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
		if ( is_shop() || is_product_taxonomy() ) {

			$order_by   = $this->get_setting($instance, 'order_by');
			$hide_empty = (bool) $this->get_setting($instance, 'hide_empty');
			$term_args  = [
				'taxonomy'   => self::TAXONOMY_NAME,
				'hide_empty' => $hide_empty,
			];

			if ( 'order' === $order_by ) {
				$term_args['orderby'] = 'meta_value_num';
				//phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$term_args['meta_key'] = 'order';
			}

			$this->chosen = RbbCoreHelperWoocommerce::get_chosen_term(self::TAXONOMY_NAME);

			$this->current_cat = RbbCoreHelperWoocommerce::get_term_id(self::TAXONOMY_NAME);

			ob_start();

			$this->widget_start($args, $instance);
				$term_args['parent'] = 0;
				$terms               = get_terms($term_args);
				$this->layered_nav_list($terms, $term_args, $instance);

			$this->widget_end($args);

			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo ob_get_clean();
		}
	}

	/**
	 * Layered List.
	 *
	 * @param mixed $terms Term.
	 * @param array $term_args Term Args.
	 * @param mixed $instance Widget Instance.
	 * @param int   $depth Depth.
	 * @return void
	 */
	public function layered_nav_list( $terms, array $term_args, $instance, int $depth = 0 ): void {
		$wrapper_classes = apply_filters(
		//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'rbb_' . self::TAXONOMY_NAME . '_widget_list_wrapper_class',
			[
				'level-' . $depth,
			],
			$depth
		);

		$li_classes = apply_filters(
		//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'rbb_' . self::TAXONOMY_NAME . '_widget_list_item_class',
			[
				'item',
				'text-center',
			],
		);

		$show_count = $this->get_setting($instance, 'count');

		$count = $this->get_filtered_term_product_counts(wp_list_pluck($terms, 'term_id'), self::TAXONOMY_NAME, 'or');

		echo "<ul class='" . esc_attr(implode(' ', $wrapper_classes)) . "'>";

		$base_link = RbbCoreHelperWoocommerce::get_current_page_url();
		foreach ( $terms as $term ) {
			$term_args['parent'] = $term->term_id;
			$child               = get_terms($term_args);
			$has_child           = ( $child ) ? ' has-child' : '';

			echo "<li class='item mr-2.5 inline-block float-left mb-2.5'>";
			echo "<a href='" . esc_url(get_term_link($term)) . "' class='h-[42px] leading-[42px] rounded-[4px] bg-white md:px-[30px] px-5 block text-[11px] duration-300 border border-[#dbdbdb] text-[color:var(--rbb-general-body-text-color)] hover:text-white hover:bg-[color:var(--rbb-general-primary-color)]'>";
			echo esc_html($term->name);
			echo "<span class='pl-1'>";
			if ( $show_count && isset($count[ $term->term_id ]) ) {
				echo '(' . esc_html($count[ $term->term_id ]) . ')';
			}
			if ( $child ) {
				$this->layered_nav_list($child, $term_args, $instance, $depth + 1);
			}
			echo '</span>';
			echo '</a>';
			echo '</li>';
		}

		echo '</ul>';
	}
}
