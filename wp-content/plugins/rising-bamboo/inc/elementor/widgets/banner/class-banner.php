<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets\Banner;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Elementor\Widgets\Base;
use RisingBambooCore\Elementor\Widgets\Widget;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooTheme\Customizer\Helper as CustomizerHelper;

/**
 * Elementor Products Widget.
 */
class Banner extends Base {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	protected string $prefix = 'rbb_banner';

	/**
	 * Construct.
	 *
	 * @param array $data Data.
	 * @param mixed $args Args.
	 * @throws \Exception Exception.
	 */
	public function __construct( array $data = [], $args = null ) {
		parent::__construct($data, $args);
		Helper::register_asset('rbb-banner', 'js/frontend/elementor/widgets/banner/banner.js', [ 'parallax-js' ], '1.0.0');
	}

	/**
	 * The method is a simple one, you just need to return a widget name that will be used in the code.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->prefix;
	}

	/**
	 * The method, which again, is a very simple one,
	 * you need to return the widget title that will be displayed as the widget label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __('Banner', App::get_domain());
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
		return [ 'rbb-banner', 'rbb-countdown' ];
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

		$start = is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());
		$end   = ! is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());

		$this->start_controls_section(
			$this->get_name_setting('content_section'),
			[
				'label' => esc_html__('Content', App::get_domain()),
			]
		);

		// <editor-fold desc="Title">
		$this->add_control(
			$this->get_name_setting('title'),
			[
				'label' => esc_html__('Title', App::get_domain()),
				'type'  => Controls_Manager::TEXT,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Sub Title">
		$this->add_control(
			$this->get_name_setting('sub_title'),
			[
				'label' => esc_html__('Sub Title', App::get_domain()),
				'type'  => Controls_Manager::TEXT,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Description">
		$this->add_control(
			$this->get_name_setting('description'),
			[
				'label' => esc_html__('Description', App::get_domain()),
				'type'  => Controls_Manager::WYSIWYG,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Countdown">

		$this->add_control(
			'count_down_heading',
			[
				'label'     => __('Countdown', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			$this->get_name_setting('countdown_due_date'),
			[
				'label'        => __('Due Date', App::get_domain()),
				'type'         => Controls_Manager::DATE_TIME,
				'description'  => __('Countdown Due Date', App::get_domain()),
			],
		);

		$this->add_control(
			$this->get_name_setting('countdown_position'),
			[
				'label'       => __('Position', App::get_domain()),
				'type'        => Controls_Manager::SELECT,
				'toggle'      => false,
				'default'     => 'absolute',
				'options'     => [
					'absolute' => esc_html__('Absolute', App::get_domain()),
					'relative' => esc_html__('Relative', App::get_domain()),
				],
				'condition'   => [
					$this->get_name_setting('countdown_due_date') . '!' => '',
				],
				'separator'   => 'before',
			]
		);

		$this->add_control(
			$this->get_name_setting('countdown_offset_orientation_h'),
			[
				'label'       => __('Horizontal Orientation', App::get_domain()),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					],
					'end' => [
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					],
				],
				'classes'     => 'elementor-control-start-end',
				'render_type' => 'ui',
				'condition'   => [
					$this->get_name_setting('countdown_due_date') . '!' => '',
					$this->get_name_setting('countdown_position') => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('countdown_offset_x'),
			[
				'label'      => __('Offset', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => '0',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} {{CURRENT_ITEM}} .rbb-countdown' => 'right: initial !important;left: {{SIZE}}{{UNIT}} !important',
					'body.rtl {{WRAPPER}} {{CURRENT_ITEM}} .rbb-countdown' => 'left: initial !important; right: {{SIZE}}{{UNIT}} !important',
				],
				'condition'  => [
					$this->get_name_setting('countdown_offset_orientation_h') . '!' => 'end',
					$this->get_name_setting('countdown_due_date') . '!' => '',
					$this->get_name_setting('countdown_position') => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('countdown_offset_x_end'),
			[
				'label'      => __('Offset', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 0.1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => '0',
				],
				'size_units' => [ 'px', '%', 'vw', 'vh' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} {{CURRENT_ITEM}} .rbb-countdown' => 'left: initial !important; right: {{SIZE}}{{UNIT}} !important',
					'body.rtl {{WRAPPER}} {{CURRENT_ITEM}} .rbb-countdown' => 'right: initial !important; left: {{SIZE}}{{UNIT}} !important',
				],
				'condition'  => [
					$this->get_name_setting('countdown_offset_orientation_h') => 'end',
					$this->get_name_setting('countdown_due_date') . '!' => '',
					$this->get_name_setting('countdown_position') => 'absolute',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('countdown_offset_orientation_v'),
			[
				'label'       => __('Vertical Orientation', App::get_domain()),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => [
					'start' => [
						'title' => __('Top', App::get_domain()),
						'icon'  => 'eicon-v-align-top',
					],
					'end' => [
						'title' => __('Bottom', App::get_domain()),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'render_type' => 'ui',
				'condition'   => [
					$this->get_name_setting('countdown_due_date') . '!' => '',
					$this->get_name_setting('countdown_position') => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('countdown_offset_y'),
			[
				'label'      => __('Offset', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vh', 'vw' ],
				'default'    => [
					'size' => '0',
				],
				'selectors'  => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-countdown' => 'bottom: initial !important; top: {{SIZE}}{{UNIT}} !important',
				],
				'condition'  => [
					$this->get_name_setting('countdown_offset_orientation_v') . '!' => 'end',
					$this->get_name_setting('countdown_due_date') . '!' => '',
					$this->get_name_setting('countdown_position') => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('countdown_offset_y_end'),
			[
				'label'      => __('Offset', App::get_domain()),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => -200,
						'max' => 200,
					],
					'vh' => [
						'min' => -200,
						'max' => 200,
					],
					'vw' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'vh', 'vw' ],
				'default'    => [
					'size' => '0',
				],
				'selectors'  => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-countdown' => 'top: initial !important; bottom: {{SIZE}}{{UNIT}} !important',
				],
				'condition'  => [
					$this->get_name_setting('countdown_offset_orientation_v') => 'end',
					$this->get_name_setting('countdown_due_date') . '!' => '',
					$this->get_name_setting('countdown_position') => 'absolute',
				],
			]
		);

		// </editor-fold>

		// <editor-fold desc="Button">
		$this->add_control(
			$this->get_name_setting('button_heading'),
			[
				'label'     => __('Buttons', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater_button = new Repeater();

		$repeater_button->add_control(
			$this->get_name_setting('button'),
			[
				'label'       => __('Text', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your button here', App::get_domain()),
			]
		);

		$repeater_button->add_control(
			$this->get_name_setting('button_css_class'),
			[
				'label'       => __('Css class', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your custom css class here', App::get_domain()),
			]
		);

		$repeater_button->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $this->get_name_setting('button_typography'),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		// <editor-fold desc="Color Tab">
		$repeater_button->start_controls_tabs($this->get_name_setting('button_tabs'));

		$repeater_button->start_controls_tab(
			$this->get_name_setting('button_tab'),
			[
				'label' => __('Normal', App::get_domain()),
			]
		);

		$repeater_button->add_control(
			$this->get_name_setting('button_text_color'),
			[
				'label'     => __('Text Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR, 'link'),
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .button-text' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$repeater_button->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_background'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}}',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color'      => [
						'label'   => __('Background Color', App::get_domain()),
						'default' => CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND, 'link'),
					],
				],
			]
		);

		$repeater_button->end_controls_tab();

		$repeater_button->start_controls_tab(
			$this->get_name_setting('button_hover_tab'),
			[
				'label' => __('Hover', App::get_domain()),
			]
		);

		$repeater_button->add_control(
			$this->get_name_setting('button_text_hover_color'),
			[
				'label'     => __('Text Hover Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR, 'hover'),
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .button-text, {{WRAPPER}} {{CURRENT_ITEM}}:focus .button-text' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater_button->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_background_hover'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}}:hover, {{WRAPPER}} {{CURRENT_ITEM}}:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color'      => [
						'label'   => __('Background Color', App::get_domain()),
						'default' => CustomizerHelper::get_multi_color(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND, 'hover'),
					],
				],
			]
		);

		$repeater_button->end_controls_tab();
		$repeater_button->end_controls_tabs();
		// </editor-fold>

		// <editor-fold desc="Margin & Padding">
		$repeater_button->add_responsive_control(
			$this->get_name_setting('button_margin'),
			[
				'label'       => __('Margin', App::get_domain()),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', 'em', '%' ],
				'placeholder' => [
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
				],
				'selectors'   => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'   => 'before',
			]
		);

		$repeater_button->add_responsive_control(
			$this->get_name_setting('button_padding'),
			[
				'label'           => __('Padding', App::get_domain()),
				'type'            => Controls_Manager::DIMENSIONS,
				'size_units'      => [ 'px', 'em', '%' ],
				'placeholder'     => [
					'top'    => 10,
					'right'  => 20,
					'bottom' => 10,
					'left'   => 20,
				],
				'default'         => [
					'top'       => 10,
					'left'      => 20,
					'right'     => 20,
					'bottom'    => 10,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'desktop_default' => [
					'top'       => 10,
					'left'      => 20,
					'right'     => 20,
					'bottom'    => 10,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'tablet_default'  => [
					'top'       => 5,
					'left'      => 10,
					'right'     => 10,
					'bottom'    => 5,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'mobile_default'  => [
					'top'       => 5,
					'left'      => 5,
					'right'     => 5,
					'bottom'    => 5,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'selectors'       => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'       => 'after',
			]
		);
		// </editor-fold>

		$this->border_group_control($repeater_button, 'button');

		$this->icon_group_control($repeater_button, 'button');

		$this->link_group_control($repeater_button, 'button');

		$this->offset_group_control($repeater_button, 'button');

		// Add repeater.
		$this->add_control(
			$this->get_name_setting('buttons'),
			[
				'label'      => esc_html__('Buttons', App::get_domain()),
				'type'       => Controls_Manager::REPEATER,
				'fields'     => $repeater_button->get_controls(),
				'show_label' => false,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Images">
		$this->add_control(
			$this->get_name_setting('image_heading'),
			[
				'label'     => __('Images', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater_image = new Repeater();

		$repeater_image->add_control(
			$this->get_name_setting('image'),
			[
				'type' => Controls_Manager::MEDIA,
			]
		);

		$repeater_image->add_control(
			$this->get_name_setting('image_link'),
			[
				'label'       => esc_html__('Link', App::get_domain()),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__('Put your URL here', App::get_domain()),
				'options'     => [ 'url', 'is_external', 'nofollow' ],
				'default'     => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'label_block' => true,
			]
		);

		$repeater_image->add_control(
			$this->get_name_setting('image_css_class'),
			[
				'label'       => __('Css class', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your custom css class here', App::get_domain()),
				'condition'   => [
					$this->get_name_setting('image') . '[id]!' => '',
				],
			]
		);

		$repeater_image->add_control(
			$this->get_name_setting('image_index'),
			[
				'label'     => __('Index', App::get_domain()),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'default'   => 2,
				'condition' => [
					$this->get_name_setting('image') . '[id]!' => '',
				],
			]
		);

		$repeater_image->add_responsive_control(
			$this->get_name_setting('image_max_width'),
			[
				'label'     => __('Max Width', App::get_domain()),
				'type'      => Controls_Manager::NUMBER,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'max-width: {{SIZE}}px',
				],
				'condition' => [
					$this->get_name_setting('image') . '[id]!' => '',
				],
			]
		);

		$this->offset_group_control($repeater_image, 'image', true);

		// Add repeater.
		$this->add_control(
			$this->get_name_setting('images'),
			[
				'label'      => esc_html__('Images', App::get_domain()),
				'type'       => Controls_Manager::REPEATER,
				'fields'     => $repeater_image->get_controls(),
				'show_label' => false,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Custom HTML Block"
		$this->add_control(
			$this->get_name_setting('custom_html_heading'),
			[
				'label'     => __('Custom HTML', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater_html = new Repeater();

		$repeater_html->add_control(
			$this->get_name_setting('custom_html'),
			[
				'label'     => __('Custom HTML', App::get_domain()),
				'type'      => Controls_Manager::CODE,
			]
		);

		$this->offset_group_control($repeater_html, 'custom_html');

		// Add repeater.
		$this->add_control(
			$this->get_name_setting('custom_html_list'),
			[
				'label'      => esc_html__('Custom HTML', App::get_domain()),
				'type'       => Controls_Manager::REPEATER,
				'fields'     => $repeater_html->get_controls(),
				'show_label' => false,
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

		// <editor-fold desc="Style Title">
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

                // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				'selectors'  => [
					'{{WRAPPER}} .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		// </editor-fold>

		// <editor-fold desc="Style Sub Title">
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
			$this->get_name_setting('general_layout'),
			[
				'label'   => __('Layout', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => Helper::list_templates('elementor/widgets/banner'),
			]
		);

		$this->add_control(
			$this->get_name_setting('general_parallax_enable'),
			[
				'label'        => __('Enable Parallax', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __('Using parallax effect ?', App::get_domain()),
				'label_on'     => __('Yes', App::get_domain()),
				'label_off'    => __('No', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			'elementor/widgets/banner/' . strtolower($layout),
			[
				'widget'              => $this,
				'id'                  => $this->uniqID(),
				'layout'              => strtolower($layout),
				'title'               => $this->get_value_setting('title'),
				'sub_title'           => $this->get_value_setting('sub_title'),
				'description'         => $this->get_value_setting('description'),
				'countdown'           => $this->get_value_setting('countdown_due_date'),
				'countdown_position'  => $this->get_value_setting('countdown_position'),
				'buttons'             => $this->get_value_setting('buttons'),
				'images'              => $this->get_value_setting('images'),
				'custom_html'         => $this->get_value_setting('custom_html_list'),
				'parallax'            => $this->get_value_setting('general_parallax_enable'),
			]
		);
	}
}
