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
use RisingBambooCore\Helper\Setting;
use RisingBambooCore\Helper\Woocommerce as RbbCoreHelperWoocommerce;

/**
 * Product Categories Widget.
 */
class BrandFilter extends WoocommerceBase {

	public const TAXONOMY_NAME = 'product_brand';
	/**
	 * Chosen categories;
	 *
	 * @var array
	 */
	public array $chosen;

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
		$this->id          = 'rbb-widget--product-brands';
		$this->css_class   = 'rbb-widget--product-brands';
		$this->name        = '[Rbb] ' . __('Filter Products By Brands', App::get_domain());
		$this->description = __('Filter products by brands', App::get_domain());

		$this->settings = [
			'title' => [
				'type'    => 'text',
				'default' => __('Brands', App::get_domain()),
				'label'   => __('Title', App::get_domain()),
			],
			'order_by' => [
				'type'    => 'select',
				'default' => 'name',
				'label'   => __('Order by', App::get_domain()),
				'options' => [
					'none'    => __('None', App::get_domain()),
					'term_id' => __('ID', App::get_domain()),
					'name'    => __('Name', App::get_domain()),
					'count'   => __('Count', App::get_domain()),
				],
			],
			'order' => [
				'type'    => 'select',
				'default' => 'asc',
				'label'   => __('Order', App::get_domain()),
				'options' => [
					'asc'  => __('Ascending', App::get_domain()),
					'desc' => __('Descending', App::get_domain()),
				],
			],
			'multiple_choose' => [
				'type'    => 'select',
				'default' => 'no',
				'label'   => __('Multiple Choose', App::get_domain()),
				'options' => [
					'yes' => esc_html__('Yes', App::get_domain()),
					'no'  => esc_html__('No', App::get_domain()),
				],
			],
			'hide_empty' => [
				'type'    => 'checkbox',
				'default' => 0,
				'label'   => __('Hide empty categories', App::get_domain()),
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
		if ( Setting::get_option('woocommerce_brands', true) && ( is_shop() || is_product_taxonomy() ) ) {

			$order_by   = $this->get_setting($instance, 'order_by');
			$order      = $this->get_setting($instance, 'order');
			$hide_empty = (bool) $this->get_setting($instance, 'hide_empty');
			$term_args  = [
				'taxonomy'   => self::TAXONOMY_NAME,
				'hide_empty' => $hide_empty,
				'menu_order' => false,
				'orderby'    => $order_by,
				'order'      => $order,
			];

			$this->chosen        = $this->get_chosen();
			$term_args['parent'] = 0;
			$terms               = get_terms($term_args);
			if ( $terms ) {
				ob_start();

				$this->widget_start($args, $instance);

				$this->list($terms, $term_args, $instance);

				$this->widget_end($args);

                //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo ob_get_clean();
			}
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
	public function list( $terms, array $term_args, $instance, int $depth = 0 ): void {
		$widget          = $this;
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
				'relative',
			],
		);

		$show_count = $this->get_setting($instance, 'count');

		$count = $this->get_filtered_term_product_counts(wp_list_pluck($terms, 'term_id'), self::TAXONOMY_NAME, 'or');

		$base_link = RbbCoreHelperWoocommerce::get_current_page_url();

		View::instance()->load('widgets/brand-filter/default', compact('terms', 'term_args', 'depth', 'instance', 'widget', 'base_link', 'wrapper_classes', 'li_classes', 'show_count', 'count'));
	}

	/**
	 * Get chosen brand.
	 *
	 * @return array
	 */
	public function get_chosen(): array {
		$return = [];
		//phpcs:ignore
		$brands = $_GET[ 'filter_' . self::TAXONOMY_NAME ] ?? null;
		if ( ! empty($brands) ) {
			//phpcs:ignore
			$return = array_map('intval', explode(',', $brands));
		}
		return $return;
	}
}
