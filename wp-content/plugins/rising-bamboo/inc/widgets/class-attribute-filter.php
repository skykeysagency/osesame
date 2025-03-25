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

/**
 * Product Categories Widget.
 */
class AttributeFilter extends WoocommerceBase {
	/**
	 * The Taxonomy.
	 *
	 * @var string
	 */
	public string $taxonomy;
	/**
	 * Chosen attribute.
	 *
	 * @var array
	 */
	public array $chosen;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->id          = 'rbb-widget--attribute-filter';
		$this->css_class   = 'rbb-widget--attribute-filter';
		$this->name        = '[Rbb] ' . __('Filter By Attributes', App::get_domain());
		$this->description = __('Filter products by attributes', App::get_domain());

		$this->settings = [
			'title' => [
				'type'    => 'text',
				'default' => __('Filter By', App::get_domain()),
				'label'   => __('Title', App::get_domain()),
			],
			'attribute' => [
				'type'    => 'select',
				'default' => '',
				'label'   => esc_html__('Attribute', App::get_domain()),
				'options' => $this->get_attribute_options(),
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
			'query_type' => [
				'type'      => 'select',
				'default'   => 'and',
				'label'     => esc_html__('Query type', App::get_domain()),
				'options'   => [
					'and' => esc_html__('AND', App::get_domain()),
					'or'  => esc_html__('OR', App::get_domain()),
				],
				'condition' => [
					'multiple_choose' => 'yes',
				],
			],
			'display' => [
				'type'    => 'select',
				'default' => 'inline',
				'label'   => __('Display as', App::get_domain()),
				'options' => [
					'inline'   => esc_html__('Inline', App::get_domain()),
					'list'     => esc_html__('List', App::get_domain()),
				],
			],
			'list_style' => [
				'type'      => 'select',
				'default'   => 'normal',
				'label'     => esc_html__('List Style', App::get_domain()),
				'options'   => [
					'normal'   => esc_html__('Normal List', App::get_domain()),
					'checkbox' => esc_html__('Check List', App::get_domain()),
					'swatches' => esc_html__('Swatches', App::get_domain()),
				],
				'condition' => [
					'display' => [ 'list', 'inline' ],
				],
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
	 * Get attribute options.
	 *
	 * @return array
	 */
	protected function get_attribute_options(): array {
		$attributes           = [];
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( $attribute_taxonomies ) {
			foreach ( $attribute_taxonomies as $tax ) {
				$attributes[ $tax->attribute_name ] = $tax->attribute_label;
			}
		}
		return $attributes;
	}

	/**
	 * The Widget HTML.
	 *
	 * @param mixed $args Params.
	 * @param mixed $instance Instance.
	 * @return void
	 */
	public function widget( $args, $instance ): void {
		if ( is_shop() || is_product_taxonomy() ) {

			$this->chosen = \WC_Query::get_layered_nav_chosen_attributes();

			$attribute = $this->get_setting($instance, 'attribute');

			$this->taxonomy = $attribute ? wc_attribute_taxonomy_name($attribute) : '';

			if ( taxonomy_exists($this->taxonomy) ) {
				$terms_args = [
					'taxonomy'   => $this->taxonomy,
					'hide_empty' => '1',
				];

				$orderby = wc_attribute_orderby($this->taxonomy);

				switch ( $orderby ) {
					case 'name':
						$terms_args['orderby']    = 'name';
						$terms_args['menu_order'] = false;
						break;
					case 'id':
						$terms_args['orderby']    = 'id';
						$terms_args['order']      = 'ASC';
						$terms_args['menu_order'] = false;
						break;
					case 'menu_order':
						$terms_args['menu_order'] = 'ASC';
						break;
				}

				$terms = get_terms($terms_args);

				if ( ! empty($terms) ) {

					switch ( $orderby ) {
						case 'name_num':
							usort($terms, '_wc_get_product_terms_name_num_usort_callback');
							break;
						case 'parent':
							usort($terms, '_wc_get_product_terms_parent_usort_callback');
							break;
					}

					ob_start();

					$this->widget_start($args, $instance);

					$this->list($terms, $instance);

					$this->widget_end($args);

                    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo ob_get_clean();
				}
			}
		}
	}

	/**
	 * Layered List.
	 *
	 * @param mixed $terms Term.
	 * @param mixed $instance Widget Instance.
	 * @return void
	 */
	public function list( $terms, $instance ): void {

		$taxonomy = $this->taxonomy;

		$show_count = $this->get_setting($instance, 'count');

		$count = $this->get_filtered_term_product_counts(wp_list_pluck($terms, 'term_id'), $taxonomy, 'or');

		$base_link = RbbCoreHelperWoocommerce::get_current_page_url();

		$multiple_choose = ( $this->get_setting($instance, 'multiple_choose') ?? 'no' ) === 'yes';

		$display = $this->get_setting($instance, 'display') ?? 'list';

		$style = $this->get_setting($instance, 'list_style') ?? 'normal';

		$wrapper_classes = apply_filters(
		//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'rbb_' . $this->taxonomy . '_widget_list_wrapper_class',
			[
				'rbb-filter-attribute-' . $this->taxonomy,

			]
		);

		View::instance()->load('widgets/attribute-filter/default', compact('taxonomy', 'terms', 'show_count', 'count', 'display', 'style', 'multiple_choose', 'base_link', 'wrapper_classes', 'instance'));
	}
}
