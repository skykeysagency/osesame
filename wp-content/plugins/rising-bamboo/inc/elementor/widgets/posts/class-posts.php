<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets\Posts;

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
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;

/**
 * Elementor Testimonial Widget.
 */
class Posts extends Base {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	protected string $prefix = 'rbb_posts';

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
		Helper::register_asset('rbb-posts', 'js/frontend/elementor/widgets/posts/posts.js', [ 'slick-carousel', 'rbb-select-ajax' ], '1.0.0');
	}

	/**
	 * The method is a simple one, you just need to return a widget name that will be used in the code.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'rbb_posts';
	}

	/**
	 * The method, which again, is a very simple one,
	 * you need to return the widget title that will be displayed as the widget label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __('Posts', App::get_domain());
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
		return [ 'rbb-posts' ];
	}

	/**
	 * Get dependency style.
	 *
	 * @return string[]
	 */
	public function get_style_depends(): array {
		return [ 'slick-theme-style' ];
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

		// <editor-fold desc="Posts">

		$this->add_control(
			$this->get_name_setting('type'),
			[
				'label'   => __('Type', App::get_domain()),
				'type'    => Controls_Manager::SELECT2,
				'default' => 'category',
				'options' => [
					'category' => __('Category', App::get_domain()),
					'posts'    => __('Specify Posts', App::get_domain()),
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('categories'),
			[
				'label'          => __('Categories', App::get_domain()),
				'description'    => __('If no category is selected, then all posts will be shown.', App::get_domain()),
				'multiple'       => true,
				'type'           => RisingBambooElementorControl::SELECT2,
				'select2options' => [
					'placeholder'        => __('Write Title of Category', App::get_domain()),
					'ajax'               => [
						'url'      => admin_url('admin-ajax.php') . '?action=rbb_get_posts_category&nonce=' . wp_create_nonce(App::get_nonce()),
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
			$this->get_name_setting('mode'),
			[
				'label'     => __('Mode', App::get_domain()),
				'type'      => Controls_Manager::SELECT2,
				'default'   => 'separate',
				'options'   => [
					'separate' => __('Posts in a separate category', App::get_domain()),
					'all'      => __('All posts in categories', App::get_domain()),
				],
				'condition' => [
					$this->get_name_setting('type') => 'category',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('posts'),
			[
				'label'          => __('Posts', App::get_domain()),
				'multiple'       => true,
				'type'           => RisingBambooElementorControl::SELECT2,
				'select2options' => [
					'placeholder'        => __('Write Title of Post', App::get_domain()),
					'ajax'               => [
						'url'      => admin_url('admin-ajax.php') . '?action=rbb_get_posts&nonce=' . wp_create_nonce(App::get_nonce()),
						'dataType' => 'json',
						'delay'    => 500,
						'cache'    => 'true',
					],
					'minimumInputLength' => 3,
				],
				'condition'      => [
					$this->get_name_setting('type') => 'posts',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('order_by'),
			[
				'label'     => __('Order By', App::get_domain()),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'date',
				'options'   => [
					'date' => [
						'title' => esc_html__('Date', App::get_domain()),
						'icon'  => 'eicon-clock-o',
					],
					'relevance' => [
						'title' => esc_html__('Relevance', App::get_domain()),
						'icon'  => 'eicon-editor-link',
					],
					'title' => [
						'title' => esc_html__('Title', App::get_domain()),
						'icon'  => 'eicon-post-title',
					],
					'comment' => [
						'title' => esc_html__('Comment', App::get_domain()),
						'icon'  => 'eicon-comments',
					],
					'random' => [
						'title' => esc_html__('Random', App::get_domain()),
						'icon'  => 'eicon-flip',
					],
				],
				'condition' => [
					$this->get_name_setting('type') => 'category',
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
					$this->get_name_setting('type')     => 'category',
					$this->get_name_setting('order_by') => [ 'date', 'comment', 'title' ],
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

		// </editor-fold>

		// <editor-fold desc="Item">
		$this->add_control(
			'hr' . $this->uniqID(),
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			$this->get_name_setting('show_author'),
			[
				'label'        => __('Show Author', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_date'),
			[
				'label'        => __('Show Post Date', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			]
		);

		$this->add_control(
			$this->get_name_setting('show_read_more'),
			[
				'label'        => __('Show Read more', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			]
		);
		// </editor-fold>

		$this->end_controls_section();
	}

	/**
	 * Style Tab.
	 *
	 * @return void
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
				'selector' => '{{WRAPPER}} .rbb_posts',
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
					'{{WRAPPER}} .title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .title' => 'background: {{VALUE}};padding:5px;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => $this > $this->get_name_setting('style_title_typography'),
				'selector'       => '{{WRAPPER}} .title',
				'global'         => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],

                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				'fields_options' => [
					'font_weight' => [
						'default' => '800',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'rem',
							'size' => 1.6,
						],
					],
				],
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
					'{{WRAPPER}} .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		// </editor-fold>
	}
	/**
	 * Subtitle section on Style Tab.
	 *
	 * @title  Subtitle Section in Style Tab
	 * @return void
	 */
	protected function style_subtitle_section(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_sub_title_section'),
			[
				'label' => __('Sub Title', App::get_domain()),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			$this->get_name_setting('style_sub_title_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => RisingBambooKirki::get_option(RISING_BAMBOO_KIRKI_CONFIG, RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_PRIMARY_COLOR),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .sub_title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$this->get_name_setting('style_sub_title_bg_color'),
			[
				'label'     => __('Background', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .sub_title' => 'background: {{VALUE}};padding:5px;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => $this->get_name_setting('style_sub_title_typography'),
				'selector'       => '{{WRAPPER}} .sub_title',
				'global'         => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				'fields_options' => [
					'font_family' => [
						'default' => 'Playball',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'rem',
							'size' => 2,
						],
					],
				],
			]
		);
		$this->add_responsive_control(
			$this->get_name_setting('style_sub_title_bottom_space'),
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
					'{{WRAPPER}} .sub_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		// </editor-fold>
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
					'{{WRAPPER}} .rbb_posts .slick-list ' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} .rbb_posts .item'        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
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

		// <editor-fold desc="Title">
		$this->add_control(
			$this->get_name_setting('general_title'),
			[
				'label' => esc_html__('Title', App::get_domain()),
				'type'  => Controls_Manager::TEXT,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Sub Title">
		$this->add_control(
			$this->get_name_setting('general_sub_title'),
			[
				'label' => esc_html__('Sub Title', App::get_domain()),
				'type'  => Controls_Manager::TEXT,
			]
		);
		// </editor-fold>

		$this->add_control(
			$this->get_name_setting('general_show_filter'),
			[
				'label'        => __('Show Filter', App::get_domain()),
				'description'  => __('Show/Hide the filter when has only one category', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'condition'    => [
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
			$this->get_name_setting('general_show_nav'),
			[
				'label'        => __('Show Navigation', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			]
		);
		$this->add_control(
			$this->get_name_setting('general_show_pagination'),
			[
				'label'        => __('Show Pagination', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
			]
		);
		$this->add_control(
			$this->get_name_setting('general_autoplay'),
			[
				'label'        => __('Auto Play', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
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
				'label'     => __('Pause', App::get_domain()),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					$this->get_name_setting('general_autoplay') => 'yes',
				],
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
					'size' => 1,
				],
			]
		);

		// <editor-fold desc="Slides Per Row">
		$this->add_control(
			$this->get_name_setting('general_layout_slides_responsive_header'),
			[
				'label'       => esc_html__('Sliders Per Row', App::get_domain()),
				'description' => esc_html__('You should set up 2 to 6 products on the Desktop. 2 - 3 products on Tablet, 1 - 2 products on Mobile', App::get_domain()),
				'type'        => Controls_Manager::HEADING,
				'separator'   => 'before',
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

		$this->add_control(
			$this->get_name_setting('general_layout'),
			[
				'label'   => __('Layout', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => Helper::list_templates('elementor/widgets/posts'),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render.
	 *
	 * @return void
	 */
	protected function render(): void {
		$layout = $this->get_value_setting('general_layout', 'default');
		View::instance()->load(
			'elementor/widgets/posts/' . strtolower($layout),
			[
				'widget'              => $this,
				'id'                  => $this->uniqID(),
				'layout'              => strtolower($layout),
				'autoplay'            => $this->get_value_setting('general_autoplay', false),
				'autoplay_speed'      => $this->get_value_setting('general_autoplay_speed', [ 'size' => 3000 ])['size'],
				'autoplay_pause'      => $this->get_value_setting('general_pause'),
				'show_arrows'         => $this->get_value_setting('general_show_nav', true),
				'show_pagination'     => $this->get_value_setting('general_show_pagination', true),
				'row'                 => ceil(abs($this->get_value_setting('general_layout_row', [ 'size' => 2 ])['size'])),
				'limit'               => $this->get_value_setting('limit', -1),
				'order_by'            => $this->get_value_setting('order_by'),
				'order'               => $this->get_value_setting('order'),
				'slides_per_row'      => $this->getDefaultSliderPerRow(),
				'active_break_points' => $this->active_break_points,
				'posts'               => $this->get_posts_data($this->get_value_setting('type', 'posts')),
				'title'               => $this->get_value_setting('general_title'),
				'sub_title'           => $this->get_value_setting('general_sub_title'),
				'show_filter'         => $this->get_value_setting('general_show_filter', true),
				'show_author'         => $this->get_value_setting('show_author', true),
				'show_date'           => $this->get_value_setting('show_date', true),
				'show_read_more'      => $this->get_value_setting('show_read_more', true),
			]
		);
	}

	/**
	 * Get Posts Data.
	 *
	 * @param string $type Type.
	 * @return array
	 */
	protected function get_posts_data( string $type ): array {
		$result = [
			'type'  => $type,
			'title' => $this->get_widget_title(),
			'posts' => [],
		];

		$args = $this->build_query_args($type, $result);

		if ( ! empty($args) ) {
			$args['post_type'] = 'post';
			$result['posts']   = get_posts($args);
		}

		return $result;
	}

	/**
	 * Build Query.
	 *
	 * @param string $type Type.
	 * @param array  $result Result.
	 * @return array|int[]
	 */
	private function build_query_args( string $type, array &$result ): array {
		$args = [];

		if ( 'posts' === $type ) {
			$ids = $this->get_value_setting('posts');
			if ( ! empty($ids) ) {
				$args = [
					'include'   => $ids,
					'orderby'   => 'post__in',
					'post_type' => 'post',
				];
			}
		} elseif ( 'category' === $type ) {
			$args = $this->build_category_args($result);
		}

		return $args;
	}

	/**
	 * Build Query Args.
	 *
	 * @param array $result Result.
	 * @return int[]
	 */
	private function build_category_args( array &$result ): array {
		$ids      = $this->get_value_setting('categories');
		$order_by = $this->get_value_setting('order_by');
		$order    = $this->get_value_setting('order') ?? 'desc';
		$mode     = $this->get_value_setting('mode', 'separate');
		$limit    = $this->get_value_setting('limit') ?? -1;

		$args = [ 'numberposts' => $limit ];

		$cats = get_categories(
			[
				'include' => $ids,
				'orderby' => 'include',
			]
		);

		if ( 'separate' === $mode ) {
			$result['categories'] = array_column($cats, 'name', 'term_id');
			$args['category']     = key($result['categories']) ?? '';
		} else {
			$args['category'] = implode(',', array_column($cats, 'term_id'));
		}

		$args['orderby'] = $this->map_order_by($order_by);
		if ( in_array($order_by, [ 'date', 'title', 'comment' ], true) ) {
			$args['order'] = $order;
		}

		return $args;
	}

	/**
	 * Map Order.
	 *
	 * @param string $order_by Orderby.
	 * @return string
	 */
	private function map_order_by( string $order_by ): string {
		$order_by_map = [
			'random'    => 'rand',
			'date'      => 'date',
			'relevance' => 'relevance',
			'title'     => 'title',
			'comment'   => 'comment_count',
		];

		return $order_by_map[ $order_by ] ?? 'date';
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
			case 'date':
				$result = __('Latest', App::get_domain());
				if ( 'asc' === $order ) {
					$result = __('Older', App::get_domain());
				}
				break;
			case 'relevance':
				$result = __('Relevance', App::get_domain());
				break;
			case 'comment':
				$result = __('Comment', App::get_domain());
				break;
			case 'rating':
				$result = __('Favourite', App::get_domain());
				break;
			default:
				$result = __('Posts', App::get_domain());
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
		$result = $mobile_first ? 1 : 3;

		if ( $this->active_break_points ) {
			$device = $mobile_first ? array_key_first($this->active_break_points) : array_key_last($this->active_break_points);
			if ( $device && $this->get_value_setting('general_layout_slides_per_row_' . $device) ) {
				$result = (int) ceil(abs($this->get_value_setting('general_layout_slides_per_row_' . $device)['size']));
			}
		}
		return $result;
	}
}
