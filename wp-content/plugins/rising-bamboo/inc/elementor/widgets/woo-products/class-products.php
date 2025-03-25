<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets\WooProducts;

use Automattic\WooCommerce\Internal\Admin\Orders\MetaBoxes\CustomMetaBox;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Elementor\Controls\Control as RisingBambooElementorControl;
use RisingBambooCore\Elementor\Widgets\Base;
use RisingBambooCore\Elementor\Widgets\Widget;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Elementor as ElementorHelper;
use RisingBambooCore\Helper\Woocommerce as WoocommerceHelper;

/**
 * Elementor Products Widget.
 */
class Products extends Base {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	protected string $prefix = 'rbb_woo_products';

	/**
	 * Default values for slider per row.
	 *
	 * @var array|int[]
	 */
	public array $default_sliders = [
		'mobile'       => 2,
		'mobile_extra' => 2,
		'tablet'       => 3,
		'tablet_extra' => 4,
		'laptop'       => 5,
		'widescreen'   => 6,
	];

	/**
	 * Active Break Points.
	 *
	 * @var array|\Elementor\Core\Breakpoints\Breakpoint|\Elementor\Core\Breakpoints\Breakpoint[]
	 */
	public array $active_break_points = [];

	/**
	 * Construct.
	 *
	 * @param array $data Data.
	 * @param mixed $args Args.
	 * @throws \Exception Exception.
	 */
	public function __construct( array $data = [], $args = null ) {
		parent::__construct($data, $args);
		$this->active_break_points = ElementorHelper::get_active_breakpoints();
		Helper::register_asset('rbb-woo-products-slick', 'js/frontend/elementor/widgets/woo-products/products-slick.js', [ 'slick-carousel', 'rbb-select-ajax' ], '1.0.0');
		Helper::register_asset('rbb-woo-products-swiper', 'js/frontend/elementor/widgets/woo-products/products-swiper.js', [ 'swiper-js', 'rbb-select-ajax' ], '1.0.0');
	}
	/**
	 * The method is a simple one, you just need to return a widget name that will be used in the code.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'rbb_woo_products';
	}

	/**
	 * The method, which again, is a very simple one,
	 * you need to return the widget title that will be displayed as the widget label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __('Woocommerce Products', App::get_domain());
	}

	/**
	 * The method lets you set the category of the widget, return the category name as a string.
	 *
	 * @return string[]
	 */
	public function get_categories(): array {
		return [ Widget::ELEMENTOR_CATEGORY ];
	}

	/**
	 * Get dependency script.
	 *
	 * @return string[]
	 */
	public function get_script_depends(): array {
		return [ 'rbb-woo-products-slick', 'rbb-woo-products-swiper' ];
	}

	/**
	 * Get dependency style.
	 *
	 * @return string[]
	 */
	public function get_style_depends(): array {
		return [ 'slick-theme-style', 'swiper-style' ];
	}

	/**
	 * The method lets you define which controls (setting fields) your widget will have.
	 */
	protected function register_controls(): void { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		$this->content_section();

		$this->general_section();

		$this->style_tab();
	}

	/**
	 * Content section.
	 *
	 * @return void
	 */
	protected function content_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('content_section'),
			[
				'label' => esc_html__('Content', App::get_domain()),
			]
		);

		$this->add_control(
			$this->get_name_setting('type'),
			[
				'label'   => __('Type', App::get_domain()),
				'type'    => Controls_Manager::SELECT2,
				'default' => 'category',
				'options' => [
					'category' => __('Category', App::get_domain()),
					'product'  => __('Product', App::get_domain()),
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('categories'),
			[
				'label'          => __('Categories', App::get_domain()),
				'description'    => __('If no category is selected, then all products will be shown.', App::get_domain()),
				'multiple'       => true,
				'type'           => RisingBambooElementorControl::SELECT2,
				'default'        => [],
				'select2options' => [
					'placeholder'        => __('Write Title Category', App::get_domain()),
					'ajax'               => [
						'url'      => admin_url('admin-ajax.php') . '?action=rbb_get_category&nonce=' . wp_create_nonce(App::get_nonce()),
						'dataType' => 'json',
						'delay'    => 500,
						'cache'    => 'true',
					],
					'minimumInputLength' => 3,
				],
				'condition'      => [
					$this->get_name_setting('type') => 'category',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('products'),
			[
				'label'          => __('Products', App::get_domain()),
				'multiple'       => true,
				'type'           => RisingBambooElementorControl::SELECT2,
				'select2options' => [
					'placeholder'        => __('Write Title Product', App::get_domain()),
					'ajax'               => [
						'url'      => admin_url('admin-ajax.php') . '?action=rbb_get_product&nonce=' . wp_create_nonce(App::get_nonce()),
						'dataType' => 'json',
						'delay'    => 500,
						'cache'    => 'true',
					],
					'minimumInputLength' => 3,
				],
				'condition'      => [
					$this->get_name_setting('type') => 'product',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('order_by'),
			[
				'label'   => __('Order By', App::get_domain()),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'include',
				'options' => [
					'include' => [
						'title' => esc_html__('Input list', App::get_domain()),
						'icon'  => 'eicon-editor-list-ol',
					],
					'best-selling' => [
						'title' => esc_html__('Best Selling', App::get_domain()),
						'icon'  => 'eicon-banner',
					],
					'latest' => [
						'title' => esc_html__('Latest', App::get_domain()),
						'icon'  => 'eicon-clock-o',
					],
					'rating' => [
						'title' => esc_html__('Rating', App::get_domain()),
						'icon'  => 'eicon-product-rating',
					],
					'price' => [
						'title' => esc_html__('Price', App::get_domain()),
						'icon'  => 'eicon-product-price',
					],
					'random' => [
						'title' => esc_html__('Random', App::get_domain()),
						'icon'  => 'eicon-flip',
					],
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('order'),
			[
				'label'     => __('Order', App::get_domain()),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'desc',
				'options'   => [
					'desc' => [
						'title' => esc_html__('DESC', App::get_domain()),
						'icon'  => 'eicon-arrow-down',
					],
					'asc' => [
						'title' => esc_html__('ASC', App::get_domain()),
						'icon'  => 'eicon-arrow-up',
					],
				],
				'condition' => [
					$this->get_name_setting('order_by') => [ 'rating', 'price' ],
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('limit'),
			[
				'label'     => __('Limit', App::get_domain()),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 32,
				'condition' => [
					$this->get_name_setting('type') => 'category',
				],
			]
		);

		$this->add_control(
			'hr' . $this->uniqID(),
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			$this->get_name_setting('show_add_to_cart'),
			[
				'label'        => __('Show Add to cart', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_wishlist'),
			[
				'label'        => __('Show Wishlist', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_quickview'),
			[
				'label'        => __('Show Quick View', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_compare'),
			[
				'label'        => __('Show Compare', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_rating'),
			[
				'label'        => __('Show Rating', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_percentage_discount'),
			[
				'label'        => __('Show Percentage Discount', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_countdown'),
			[
				'label'        => __('Show Countdown', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_stock'),
			[
				'label'        => __('Show Stock', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_custom_field'),
			[
				'label'        => __('Show Custom Fields', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			$this->get_name_setting('custom_fields'),
			[
				'label'          => __('Custom Fields', App::get_domain()),
				'description'    => __('If no custom field is selected, then all fields will be shown.', App::get_domain()),
				'multiple'       => true,
				'type'           => RisingBambooElementorControl::SELECT2,
				'select2options' => [
					'placeholder'        => __('Write the meta key', App::get_domain()),
					'ajax'               => [
						'url'      => admin_url('admin-ajax.php') . '?action=rbb_get_meta_key&nonce=' . wp_create_nonce(App::get_nonce()),
						'dataType' => 'json',
						'delay'    => 500,
						'cache'    => 'true',
					],
					'minimumInputLength' => 3,
				],
				'condition'      => [
					$this->get_name_setting('show_custom_field') => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * General Section.
	 *
	 * @return void
	 */
	protected function general_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('general_section'),
			[
				'label' => __('General', App::get_domain()),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			$this->get_name_setting('general_show_title'),
			[
				'label'        => __('Show Title', App::get_domain()),
				'description'  => __('Show/Hide the title', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			$this->get_name_setting('general_title'),
			[
				'label'       => __('Title', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your title here', App::get_domain()),
				'description' => __('If the title is not entered, the title will be displayed by Order.', App::get_domain()),
			]
		);
		$this->add_control(
			$this->get_name_setting('general_subtitle'),
			[
				'label'       => __('Subtitle', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your Extra Subtitle here', App::get_domain()),
			]
		);
		$this->add_control(
			$this->get_name_setting('general_show_filter'),
			[
				'label'        => __('Show Filter', App::get_domain()),
				'description'  => __('Show/Hide the filter if there is only one category available. Otherwise, always show the filter.', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'hr' . $this->uniqID(),
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			$this->get_name_setting('general_show_nav'),
			[
				'label'        => __('Show Navigation', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			$this->get_name_setting('general_show_pagination'),
			[
				'label'        => __('Show Pagination', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'default'      => 'yes',
			]
		);
		$this->add_control(
			$this->get_name_setting('general_autoplay'),
			[
				'label'        => __('Auto Play', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', App::get_domain()),
				'label_off'    => esc_html__('No', App::get_domain()),
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('general_autoplay_speed'),
			[
				'label'      => __('Auto Play Speed', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'ms' ],
				'range'      => [
					'ms' => [
						'min'  => 1000,
						'max'  => 10000,
						'step' => 500,
					],
				],
				'default'    => [
					'unit' => 'ms',
					'size' => 3000,
				],
				'condition'  => [
					$this->get_name_setting('general_autoplay') => 'yes',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('general_pause'),
			[
				'label'        => __('Pause', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', App::get_domain()),
				'label_off'    => esc_html__('No', App::get_domain()),
				'default'      => 'yes',
				'condition'    => [
					$this->get_name_setting('general_autoplay') => 'yes',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('general_layout'),
			[
				'label'   => __('Layout', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => Helper::list_templates('elementor/widgets/woo-products'),
			]
		);
		$this->add_control(
			$this->get_name_setting('general_layout_row'),
			[
				'label'      => esc_html__('Rows', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'number' ],
				'range'      => [
					'number' => [
						'min'  => 1,
						'max'  => 5,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'number',
					'size' => 2,
				],
			]
		);

		// <editor-fold desc="Slides Per Row">
		$this->add_control(
			$this->get_name_setting('general_layout_slides_responsive_header'),
			[
				'label'     => esc_html__('Sliders Per Row', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		foreach ( $this->active_break_points as $break_point ) {
			$this->add_control(
				$this->get_name_setting('general_layout_slides_per_row_') . $break_point->get_name(),
				[
					'label'      => $break_point->get_label(),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'number' ],
					'range'      => [
						'number' => [
							'min'  => 1,
							'max'  => 10,
							'step' => 1,
						],
					],
					'default'    => [
						'unit' => 'number',
						'size' => $this->default_sliders[ $break_point->get_name() ] ?? 4,
					],
				]
			);
		}
		// </editor-fold>

		// <editor-fold desc="Surrounding Animation Image">
		$this->add_control(
			$this->get_name_setting('general_surrounding_animation_image_header'),
			[
				'label'       => esc_html__('Surrounding Animation Images', App::get_domain()),
				'type'        => Controls_Manager::HEADING,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			$this->get_name_setting('general_surrounding_animation_image_01'),
			[
				'type'      => Controls_Manager::MEDIA,
				'label'     => __('Image 01', App::get_domain()),
			]
		);

		$this->add_control(
			$this->get_name_setting('general_surrounding_animation_image_02'),
			[
				'type'      => Controls_Manager::MEDIA,
				'label'     => __('Image 02', App::get_domain()),
			]
		);

		// </editor-fold>
		$this->end_controls_section();
	}

	/**
	 * The method, which is where you actually render the code and generate the final HTML on the frontend using PHP.
	 */
	protected function style_tab(): void {
		$this->style_background_section();
		$this->style_title_section();
		$this->style_subtitle_section();
		$this->style_navigation_section();
		$this->style_pagination_section();
		$this->style_spacing_section();
	}

	/**
	 * Background section in style tab.
	 *
	 * @return void
	 */
	protected function style_background_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_background_section'),
			[
				'label' => __('Background', App::get_domain()),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => $this->get_name_setting('style_background'),
				'label'    => __('Background', App::get_domain()),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rbb_woo_products',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Title section in Style Tab
	 *
	 * @title Title section in Style tab.
	 * @return void
	 */
	protected function style_title_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_title_section'),
			[
				'label' => __('Title', App::get_domain()),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			$this->get_name_setting('style_title_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$this->get_name_setting('style_title_bg_color'),
			[
				'label'     => __('Background', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .title' => 'background: {{VALUE}};padding:5px;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $this > $this->get_name_setting('style_title_typography'),
				'selector' => '{{WRAPPER}} .rbb_woo_products .title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],

                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				/**
				 * Fields Options.
				 *
				'fields_options' => [
					'font_weight' => [
						'default' => '800',
					],
					'font_size'   => [
						'default' => [ 'unit' => 'rem', 'size' => 2.25 ]
					],
				],
				*/
			]
		);
		$this->add_responsive_control(
			$this->get_name_setting('style_title_bottom_space'),
			[
				'label'      => __('Spacing', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
					'rem' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rbb_woo_products .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Subtitle section on Style Tab.
	 *
	 * @title  Subtitle Section in Style Tab
	 * @return void
	 */
	protected function style_subtitle_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_subtitle_section'),
			[
				'label' => __('Subtitle', App::get_domain()),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			$this->get_name_setting('style_subtitle_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .sub-title' => 'color: {{VALUE}};',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);
		$this->add_control(
			$this->get_name_setting('style_subtitle_bg_color'),
			[
				'label'     => __('Background', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .sub-title' => 'background: {{VALUE}};padding:5px;',
				],
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $this->get_name_setting('style_subtitle_typography'),
				'selector' => '{{WRAPPER}} .rbb_woo_products .sub-title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);
		$this->add_responsive_control(
			$this->get_name_setting('style_subtitle_bottom_space'),
			[
				'label'      => __('Spacing', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
					'rem' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rbb_woo_products .sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Navigation section in Style tab.
	 *
	 * @title Navigation section in Style tab
	 * @return void
	 */
	protected function style_navigation_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_navigation_section'),
			[
				'label' => __('Navigation', App::get_domain()),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			$this->get_name_setting('style_navigation_show_when_hover'),
			[
				'label'        => __('Show when hovering', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __('On', App::get_domain()),
				'label_off'    => __('Off', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => [
					'{{WRAPPER}} .rbb_woo_products .slick-arrow' => 'visibility: hidden;opacity: 0;',
					'{{WRAPPER}} .rbb_woo_products:hover .slick-arrow' => 'visibility: visible;opacity: 1;',
				],
				'separator'    => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $this->get_name_setting('style_navigation_border'),
				'selector' => '{{WRAPPER}} .rbb_woo_products .slick-arrow',
			]
		);
		$this->add_control(
			$this->get_name_setting('style_navigation_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb_woo_products .slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('style_navigation_border_border') . '!' => '',
				],
			]
		);

		$this->start_controls_tabs($this->get_name_setting('style_navigation_tabs'));
		$this->start_controls_tab(
			$this->get_name_setting('style_navigation_normal_tab'),
			[
				'label' => __('Normal', App::get_domain()),
			]
		);

		$this->add_control(
			$this->get_name_setting('style_navigation_text_color'),
			[
				'label'     => __('Icon Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .slick-arrow::before' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('style_navigation_background'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb_woo_products .slick-arrow',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$this->get_name_setting('style_navigation_hover_tab'),
			[
				'label' => __('Hover', App::get_domain()),
			]
		);

		$this->add_control(
			$this->get_name_setting('style_navigation_hover_color'),
			[
				'label'     => __('Icon Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .slick-arrow:hover::before, {{WRAPPER}} .rbb_woo_products .slick-arrow:focus::before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('style_navigation_hover_background'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb_woo_products .slick-arrow:hover, {{WRAPPER}} .rbb_woo_products .slick-arrow:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'label' => __('Background Color', App::get_domain()),
					],
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('style_navigation_hover_border_color'),
			[
				'label'     => __('Border Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					$this->get_name_setting('style_navigation_border_border') . '!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .slick-arrow:hover, {{WRAPPER}} .rbb_woo_products .slick-arrow:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Pagination section in style tab.
	 *
	 * @title Pagination Section in Style Tab
	 * @return void
	 */
	protected function style_pagination_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_pagination_section'),
			[
				'label' => __('Pagination', App::get_domain()),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('style_pagination_align'),
			[
				'label'     => __('Alignment', App::get_domain()),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => __('Left', App::get_domain()),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', App::get_domain()),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', App::get_domain()),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __('Justified', App::get_domain()),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('style_pagination_background'),
				'label'          => __('Background', App::get_domain()),
				'exclude'        => [ 'image' ],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .rbb_woo_products .slick-dots',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'label' => __('Background Color', App::get_domain()),
					],
				],
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('style_pagination_padding'),
			[
				'label'      => __('Padding Pagination', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs($this->get_name_setting('style_pagination_tabs'));
		// <editor-fold desc="Pagination Normal Tab">
		$this->start_controls_tab(
			$this->get_name_setting('style_pagination_normal_tabs'),
			[
				'label' => __('Normal', App::get_domain()),
			]
		);

		$this->add_control(
			$this->get_name_setting('style_pagination_normal_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots li:not(.slick-active) button::before' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $this->get_name_setting('style_pagination_normal_border'),
				'selector'       => '{{WRAPPER}} .rbb_woo_products .slick-dots li:not(.slick-active)',
				'fields_options' => [
					'width' => [
						'label' => __('Border Width', App::get_domain()),
					],
					'color' => [
						'label' => __('Border Color', App::get_domain()),
					],
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('style_pagination_normal_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('style_pagination_normal_border') . '_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		// </editor-fold>
		// <editor-fold desc="Pagination Active Tab">
		$this->start_controls_tab(
			$this->get_name_setting('style_pagination_active_tab'),
			[
				'label' => __('Active', App::get_domain()),
			]
		);

		$this->add_control(
			$this->get_name_setting('style_pagination_active_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots li.slick-active button::before' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $this->get_name_setting('style_pagination_active_border'),
				'selector'       => '{{WRAPPER}} .rbb_woo_products .slick-dots li.slick-active',
				'fields_options' => [
					'width' => [
						'label' => __('Border Width', App::get_domain()),
					],
					'color' => [
						'label' => __('Border Color', App::get_domain()),
					],
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('style_pagination_active_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots li.slick-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('style_pagination_active_border') . '_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		// </editor-fold>
		// <editor-fold desc="Pagination Hover Tab">
		$this->start_controls_tab(
			$this->get_name_setting('style_pagination_hover_tab'),
			[
				'label' => __('Hover', App::get_domain()),
			]
		);

		$this->add_control(
			$this->get_name_setting('style_pagination_hover_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots li:not(.slick-active):hover button::before, {{WRAPPER}} .rbb_woo_products .slick-dots li:not(.slick-active):focus button::before' => 'fill: {{VALUE}};color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $this->get_name_setting('style_pagination_hover_border'),
				'selector'       => '{{WRAPPER}} .rbb_woo_products .slick-dots li:not(.slick-active):hover',
				'fields_options' => [
					'width' => [
						'label' => __('Border Width', App::get_domain()),
					],
					'color' => [
						'label' => __('Border Color', App::get_domain()),
					],
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('style_pagination_hover_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb_woo_products .slick-dots li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('style_pagination_hover_border') . '_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		// </editor-fold>
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Spacing section in style tab.
	 *
	 * @return void
	 */
	protected function style_spacing_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_spacing_section'),
			[
				'label' => __('Spacing', App::get_domain()),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('style_spacing_padding'),
			[
				'label'           => __('Padding', App::get_domain()),
				'type'            => Controls_Manager::DIMENSIONS,
				'size_units'      => [ 'px', 'em', '%' ],
				'placeholder'     => [
					'top'    => '',
					'right'  => 20,
					'bottom' => '',
					'left'   => 20,
				],
				'default'         => [
					'top'       => 0,
					'left'      => 20,
					'right'     => 20,
					'bottom'    => 0,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'desktop_default' => [
					'top'       => 0,
					'left'      => 20,
					'right'     => 20,
					'bottom'    => 0,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'tablet_default'  => [
					'top'       => 0,
					'left'      => 10,
					'right'     => 10,
					'bottom'    => 0,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'mobile_default'  => [
					'top'       => 0,
					'left'      => 5,
					'right'     => 5,
					'bottom'    => 0,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'selectors'       => [
					'{{WRAPPER}} .rbb_woo_products .slick-list ' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} .rbb_woo_products .swiper-container ' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} .rbb_woo_products .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render(): void {
		$layout = $this->get_value_setting('general_layout', 'default');
		View::instance()->load(
			'elementor/widgets/woo-products/' . strtolower($layout),
			[
				'widget'                         => $this,
				'id'                             => $this->uniqID(),
				'data'                           => $this->get_product_data($this->get_value_setting('type', 'products')),
				'title'                          => $this->get_widget_title(),
				'subtitle'                       => $this->get_value_setting('general_subtitle'),
				'show_title'                     => $this->get_value_setting('general_show_title'),
				'show_filter'                    => $this->get_value_setting('general_show_filter'),
				'autoplay'                       => $this->get_value_setting('general_autoplay'),
				'autoplay_speed'                 => $this->get_value_setting('general_autoplay_speed', [ 'size' => 3000 ])['size'],
				'autoplay_pause'                 => $this->get_value_setting('general_pause'),
				'show_arrows'                    => $this->get_value_setting('general_show_nav'),
				'show_pagination'                => $this->get_value_setting('general_show_pagination'),
				'layout'                         => strtolower($layout),
				'row'                            => ceil(abs($this->get_value_setting('general_layout_row', [ 'size' => 2 ])['size'])),
				'slides_per_row'                 => $this->getDefaultSliderPerRow(),
				'active_break_points'            => $this->active_break_points,
				'order_by'                       => $this->get_value_setting('order_by'),
				'order'                          => $this->get_value_setting('order') ?? 'desc',
				'limit'                          => $this->get_value_setting('limit') ?? -1,
				'show_add_to_cart'               => $this->get_value_setting('show_add_to_cart'),
				'show_wishlist'                  => $this->get_value_setting('show_wishlist'),
				'show_compare'                   => $this->get_value_setting('show_compare'),
				'show_rating'                    => $this->get_value_setting('show_rating'),
				'show_quickview'                 => $this->get_value_setting('show_quickview'),
				'show_percentage_discount'       => $this->get_value_setting('show_percentage_discount'),
				'show_countdown'                 => $this->get_value_setting('show_countdown'),
				'show_stock'                     => $this->get_value_setting('show_stock'),
				'show_custom_field'              => $this->get_value_setting('show_custom_field'),
				'custom_fields'                  => $this->get_value_setting('custom_fields'),
				'custom_field_ignore'            => $this->get_custom_field_ignore(),
				'surrounding_animation_image_01' => $this->get_value_setting('general_surrounding_animation_image_01'),
				'surrounding_animation_image_02' => $this->get_value_setting('general_surrounding_animation_image_02'),
			]
		);
	}

	/**
	 * Get title.
	 *
	 * @return mixed|string|null
	 */
	protected function get_widget_title() {
		return ! empty($this->get_value_setting('general_title')) ? $this->get_value_setting('general_title') : $this->get_title_by_order($this->get_value_setting('order_by', 'best-selling'), $this->get_value_setting('order', 'desc'));
	}

	/**
	 * Get title by order.
	 *
	 * @param string $type Type.
	 * @param string $order Order.
	 * @return string
	 */
	protected function get_title_by_order( string $type, string $order = 'desc' ): string {
		switch ( $type ) {
			case 'latest':
				$result = __('Latest', App::get_domain());
				break;
			case 'rating':
				$result = __('Favourite', App::get_domain());
				break;
			case 'price':
				$result = __('Price (High to Low)', App::get_domain());
				if ( 'asc' === $order ) {
					$result = __('Price (Low to High)', App::get_domain());
				}
				break;
			case 'best-selling':
			default:
				$result = __('Best Selling', App::get_domain());
		}
		return $result;
	}

	/**
	 * Get data.
	 *
	 * @param mixed $type Type.
	 * @return array
	 */
	protected function get_product_data( $type ): array {
		$result   = [
			'type'     => $type,
			'title'    => $this->get_widget_title(),
			'products' => [],
		];
		$order_by = $this->get_value_setting('order_by');
		$order    = $this->get_value_setting('order') ?? 'desc';
		$limit    = $this->get_value_setting('limit') ?? -1;
		if ( 'product' === $type ) {
			$ids = $this->get_value_setting('products');
			if ( ! empty($ids) ) {
				$result['products'] = WoocommerceHelper::get_products($ids, 'id', $order_by, $order);
			}
		} elseif ( 'category' === $type ) {
			$ids                  = $this->get_value_setting('categories');
			$cats                 = WoocommerceHelper::get_categories_by_ids($ids, 'include');
			$result['categories'] = [];
			foreach ( $cats as $cat ) {
				$result['categories'][ $cat->term_id ] = $cat->name;
			}
			$result['products'] = WoocommerceHelper::get_products(( ! empty($result['categories']) ) ? array_key_first($result['categories']) : [], 'category', $order_by, $order, $limit);
		}
		return $result;
	}

	/**
	 * Get default silder per row.
	 *
	 * @param bool $mobile_first Mobile first.
	 * @return int
	 */
	protected function getDefaultSliderPerRow( bool $mobile_first = false ): int {
		$result = $mobile_first ? 1 : 4;

		if ( $this->active_break_points ) {
			$device = $mobile_first ? array_key_first($this->active_break_points) : array_key_last($this->active_break_points);
			if ( $device && $this->get_value_setting('general_layout_slides_per_row_' . $device) ) {
				$result = (int) ceil(abs($this->get_value_setting('general_layout_slides_per_row_' . $device)['size']));
			}
		}
		return $result;
	}

	/**
	 * Custom field ignore.
	 *
	 * @return string[]
	 */
	protected function get_custom_field_ignore(): array {
		return [
			'total_sales',
			'woosw_count',
			'woosw_add',
			'woosw_remove',
		];
	}
}
