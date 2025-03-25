<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets\WooProducts;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Elementor\Controls\Control as RisingBambooElementorControl;
use RisingBambooCore\Elementor\Widgets\Base;
use RisingBambooCore\Elementor\Widgets\Widget;
use RisingBambooCore\Helper\Elementor as ElementorHelper;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Woocommerce;

/**
 * Elementor Products Widget.
 */
class Single extends Base {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	protected string $prefix = 'rbb_woo_single_product';
	/**
	 * Construct.
	 *
	 * @param array $data Data.
	 * @param mixed $args Args.
	 * @throws \Exception Exception.
	 */
	public function __construct( array $data = [], $args = null ) {
		parent::__construct($data, $args);
		Helper::register_asset('rbb-woo-single-product-slick', 'js/frontend/elementor/widgets/woo-products/single-product-slick.js', [ 'slick-carousel' ], '1.0.0');
	}
	/**
	 * The method is a simple one, you just need to return a widget name that will be used in the code.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'rbb_woo_single_product';
	}

	/**
	 * The method, which again, is a very simple one,
	 * you need to return the widget title that will be displayed as the widget label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __('Woocommerce Single Product', App::get_domain());
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
		return [ 'rbb-woo-single-product-slick' ];
	}

	/**
	 * Get dependency style.
	 *
	 * @return string[]
	 */
	public function get_style_depends(): array {
		return [];
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
			$this->get_name_setting('product_ids'),
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
			]
		);

		$this->add_control(
			'hr' . $this->uniqID(),
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			$this->get_name_setting('show_product_excerpt'),
			[
				'label'        => __('Show Product Excerpt', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_product_sku'),
			[
				'label'        => __('Show Product SKU', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_product_category'),
			[
				'label'        => __('Show Product Category', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_product_tag'),
			[
				'label'        => __('Show Product Tag', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_product_guarantee'),
			[
				'label'        => __('Show Product Guarantee', App::get_domain()),
				'desc'         => __('Show Product Guarantee', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Show', App::get_domain()),
				'label_off'    => esc_html__('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			'hr' . $this->uniqID(),
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			$this->get_name_setting('general_layout'),
			[
				'label'   => __('Layout', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => Helper::list_templates('elementor/widgets/woo-single-product'),
			]
		);

		$this->add_control(
			$this->get_name_setting('image_layout'),
			[
				'label'   => __('Image Layout', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => Helper::list_templates('elementor/widgets/woo-single-product/fragments/image-layouts'),
			]
		);
		$this->end_controls_section();
	}

	/**
	 * The method, which is where you actually render the code and generate the final HTML on the frontend using PHP.
	 */
	protected function style_tab(): void {
		$this->style_background_section();
		$this->style_title_section();
		$this->style_subtitle_section();
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
				'selector' => '{{WRAPPER}} .rbb_woo_single_product',
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
					'{{WRAPPER}} .rbb_woo_single_product .title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .rbb_woo_single_product .title' => 'background: {{VALUE}};padding:5px;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $this > $this->get_name_setting('style_title_typography'),
				'selector' => '{{WRAPPER}} .rbb_woo_single_product .title',
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
					'{{WRAPPER}} .rbb_woo_single_product .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .rbb_woo_single_product .sub-title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .rbb_woo_single_product .sub-title' => 'background: {{VALUE}};padding:5px;',
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
				'selector' => '{{WRAPPER}} .rbb_woo_single_product .sub-title',
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
					'{{WRAPPER}} .rbb_woo_single_product .sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
					'top'    => '10',
					'right'  => 5,
					'bottom' => '5',
					'left'   => 5,
				],
				'default'         => [
					'top'       => 10,
					'left'      => 5,
					'right'     => 5,
					'bottom'    => 5,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'desktop_default' => [
					'top'       => 10,
					'left'      => 5,
					'right'     => 5,
					'bottom'    => 5,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'tablet_default'  => [
					'top'       => 10,
					'left'      => 5,
					'right'     => 5,
					'bottom'    => 5,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'mobile_default'  => [
					'top'       => 10,
					'left'      => 5,
					'right'     => 5,
					'bottom'    => 5,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'selectors'       => [
					'{{WRAPPER}} .rbb_woo_single_product .slick-list ' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} .rbb_woo_single_product .swiper-container ' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} .rbb_woo_single_product .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$layout       = $this->get_value_setting('general_layout', 'default');
		$image_layout = $this->get_value_setting('image_layout', 'default');
		View::instance()->load(
			'elementor/widgets/woo-single-product/' . strtolower($layout),
			[
				'widget'                         => $this,
				'id'                             => $this->uniqID(),
				'products'                       => Woocommerce::get_products($this->get_value_setting('product_ids'), 'id', 'include', 'desc'),
				'show_title'                     => $this->get_value_setting('general_show_title'),
				'show_excerpt'                   => $this->get_value_setting('show_product_excerpt'),
				'show_sku'                       => $this->get_value_setting('show_product_sku'),
				'show_category'                  => $this->get_value_setting('show_product_category'),
				'show_tag'                       => $this->get_value_setting('show_product_tag'),
				'show_guarantee'                 => $this->get_value_setting('show_product_guarantee'),
				'title'                          => $this->get_value_setting('general_title'),
				'subtitle'                       => $this->get_value_setting('general_subtitle'),
				'layout'                         => strtolower($layout),
				'image_layout'                   => strtolower($image_layout),
			]
		);
	}

	/**
	 * Product Meta.
	 *
	 * @return mixed
	 */
	public function meta_production() {
		return View::instance()->load(
			'elementor/widgets/woo-single-product/fragments/meta',
			[
				'show_sku'      => $this->get_value_setting('show_product_sku'),
				'show_category' => $this->get_value_setting('show_product_category'),
				'show_tag'      => $this->get_value_setting('show_product_tag'),
			]
		);
	}
}
