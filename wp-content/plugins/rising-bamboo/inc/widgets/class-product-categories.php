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
class ProductCategories extends WoocommerceBase {

	public const TAXONOMY_NAME = 'product_cat';
	/**
	 * Chosen categories;
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
		$this->id          = 'rbb-widget--product-categories';
		$this->css_class   = 'rbb-widget--product-categories';
		$this->name        = '[Rbb] ' . __('Filter Products By Category', App::get_domain());
		$this->description = __('Filter products by category', App::get_domain());

		$this->settings = [
			'title' => [
				'type'    => 'text',
				'default' => __('Product Categories', App::get_domain()),
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
			'multiple_choose' => [
				'type'    => 'select',
				'default' => 'no',
				'label'   => __('Multiple Choose', App::get_domain()),
				'options' => [
					'yes' => esc_html__('Yes', App::get_domain()),
					'no'  => esc_html__('No', App::get_domain()),
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
				'label'   => __('Hide empty categories', App::get_domain()),
			],
		];

		parent::__construct();

		add_action($this->get_hook_name_form('after'), [ $this, 'after_admin_widget_form' ]);
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
				'menu_order' => false,
			];

			if ( 'order' === $order_by ) {
				$term_args['orderby'] = 'meta_value_num';
				//phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$term_args['meta_key'] = 'order';
			}
			$this->chosen = RbbCoreHelperWoocommerce::get_chosen_term(self::TAXONOMY_NAME);

			$this->current_cat = RbbCoreHelperWoocommerce::get_term_id(self::TAXONOMY_NAME);

			$term_args['parent'] = 0;

			$terms = get_terms($term_args);

			if ( $terms ) {
				ob_start();

				$this->widget_start($args, $instance);

				$this->layered_nav_list($terms, $term_args, $instance);

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
	public function layered_nav_list( $terms, array $term_args, $instance, int $depth = 0 ): void {
		$widget          = $this;
		$current_cat     = $this->current_cat;
		$chosen          = $this->chosen;
		$multiple_choose = $this->get_setting($instance, 'multiple_choose');
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

		View::instance()->load('widgets/product-categories/default', compact('terms', 'term_args', 'chosen', 'current_cat', 'depth', 'instance', 'widget', 'base_link', 'wrapper_classes', 'li_classes', 'show_count', 'count', 'multiple_choose'));
	}

	/**
	 * Add note in admin form.
	 *
	 * @return void
	 */
	public function after_admin_widget_form(): void {
		?>
		<p style="color:darkred;">
			<small>
				<?php
            //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo __('The default operator of the product categories filter is AND. You can change the operator in <i> Rising Bamboo > Settings > Woocommerce </i>', App::get_domain());
				?>
			</small>
		</p>
		<?php
	}
}
