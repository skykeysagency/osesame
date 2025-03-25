<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets\Slider;

use Elementor\Controls_Manager;
use Elementor\Core\Breakpoints\Manager as ElementorBreakpointManager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Repeater;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Elementor\Widgets\Base;
use RisingBambooCore\Elementor\Widgets\Widget;
use RisingBambooCore\Helper\Elementor as ElementorHelper;
use RisingBambooCore\Helper\Helper;

/**
 * Slick Widget.
 */
class Slider extends Base {
	/**
	 * Prefix.
	 *
	 * @var string
	 */
	protected string $prefix = 'rbb_slider';

	/**
	 * Active Break Points.
	 *
	 * @var array|\Elementor\Core\Breakpoints\Breakpoint|\Elementor\Core\Breakpoints\Breakpoint[]
	 */
	public array $active_break_points = [];

	/**
	 * Number of images in slider.
	 *
	 * @var int
	 */
	public int $images_per_slider = 4;

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
		Helper::register_asset('rbb-slider-slick', 'js/frontend/elementor/widgets/slider/slick.js', [ 'slick-carousel', 'parallax-js' ], '1.0.0');
		Helper::register_asset('rbb-slider-swiper', 'js/frontend/elementor/widgets/slider/swiper.js', [ 'swiper-js' ], '1.0.0');
		$theme_support = get_theme_support($this->get_name(), 'images_per_slider');
		if ( isset($theme_support[0]['images_per_slider']) ) {
			$this->images_per_slider = $theme_support[0]['images_per_slider'];
		}
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
	 * @return string|null
	 */
	public function get_title(): ?string {
		return __('Slider', App::get_domain());
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
	 * Get scripts.
	 *
	 * @return string[]
	 */
	public function get_script_depends(): array {
		return [ 'rbb-slider-slick', 'rbb-slider-swiper', 'rbb-countdown' ];
	}

	/**
	 * Get style.
	 *
	 * @return string[]
	 */
	public function get_style_depends(): array {
		return [ 'slick-theme-style' ];
	}

	/**
	 * The method lets you define which controls (setting fields) your widget will have.
	 */
	protected function register_controls(): void { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		$this->contentSingleLayout();

		$this->contentMultipleLayout();

		$this->contentGeneralSection();

		$this->styleNavigationSection();

		$this->stylePaginationSection();

		$this->styleSpacingForMultipleLayout();
	}

	/**
	 * Content field for single layout type.
	 *
	 * @return void
	 */
	protected function contentSingleLayout(): void {

		$start = is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());
		$end   = ! is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());

		$this->start_controls_section(
			$this->get_name_setting('content_section'),
			[
				'label'     => esc_html__('Content for Slider', App::get_domain()),
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'single',
				],
			]
		);

		$repeater = new Repeater();

		// <editor-fold desc="Title Section">
		$repeater->add_control(
			'title_heading',
			[
				'label'     => __('Title', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => '',
			]
		);

		$repeater->add_control(
			$this->get_name_setting('title'),
			[
				'label'       => __('Text', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your title here', App::get_domain()),
				'show_label'  => false,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			$this->get_name_setting('title_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .title' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_name_setting('title') . '!' => '',
				],
			]
		);
		$repeater->add_control(
			$this->get_name_setting('title_bg_color'),
			[
				'label'     => __('Background', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .title' => 'background: {{VALUE}};padding:5px;',
				],
				'condition' => [
					$this->get_name_setting('title') . '!' => '',
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => $this > $this->get_name_setting('title_typography'),
				'selector'  => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .title',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => [
					$this->get_name_setting('title') . '!' => '',
				],
				/* phpcs:ignore
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
		$repeater->add_responsive_control(
			$this->get_name_setting('title_bottom_space'),
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
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('title') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('title_animation'),
			[
				'label'              => __('Animation', App::get_domain()),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'condition'          => [
					$this->get_name_setting('title') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('title_animation_duration'),
			[
				'label'        => __('Animation Duration', App::get_domain()),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					'slow' => __('Slow', App::get_domain()),
					''     => __('Normal', App::get_domain()),
					'fast' => __('Fast', App::get_domain()),
				],
				'prefix_class' => 'animated-',
				'condition'    => [
					$this->get_name_setting('title_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('title') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('title_animation_delay'),
			[
				'label'              => __('Animation Delay', App::get_domain()) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'default'            => '',
				'min'                => 0,
				'step'               => 100,
				'condition'          => [
					$this->get_name_setting('title_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('title') . '!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Subtitle Section">
		$repeater->add_control(
			'subtitle_heading',
			[
				'label'     => __('Subtitle', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			$this->get_name_setting('subtitle'),
			[
				'label'       => __('Text', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your sub title here', App::get_domain()),
				'show_label'  => false,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			$this->get_name_setting('subtitle_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .sub-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_name_setting('subtitle') . '!' => '',
				],
			]
		);
		$repeater->add_control(
			$this->get_name_setting('subtitle_bg_color'),
			[
				'label'     => __('Background', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .sub-title' => 'background: {{VALUE}};padding:5px;',
				],
				'condition' => [
					$this->get_name_setting('subtitle') . '!' => '',
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => $this->get_name_setting('subtitle_typography'),
				'selector'  => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .sub-title',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					$this->get_name_setting('subtitle') . '!' => '',
				],
			]
		);
		$repeater->add_responsive_control(
			$this->get_name_setting('subtitle_bottom_space'),
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
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .sub-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('subtitle') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('subtitle_animation'),
			[
				'label'              => __('Animation', App::get_domain()),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'condition'          => [
					$this->get_name_setting('subtitle') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('subtitle_animation_duration'),
			[
				'label'        => __('Animation Duration', App::get_domain()),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					'slow' => __('Slow', App::get_domain()),
					''     => __('Normal', App::get_domain()),
					'fast' => __('Fast', App::get_domain()),
				],
				'prefix_class' => 'animated-',
				'condition'    => [
					$this->get_name_setting('subtitle_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('subtitle') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('subtitle_animation_delay'),
			[
				'label'              => __('Animation Delay', App::get_domain()) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'default'            => '',
				'min'                => 0,
				'step'               => 100,
				'condition'          => [
					$this->get_name_setting('subtitle_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('subtitle') . '!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Description Section">
		$repeater->add_control(
			'description_heading',
			[
				'label'     => __('Description', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			$this->get_name_setting('description'),
			[
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => '',
				'placeholder' => __('Type your description here', App::get_domain()),
			]
		);

		$repeater->add_control(
			$this->get_name_setting('description_color'),
			[
				'label'     => __('Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .description' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_name_setting('description') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('description_bg_color'),
			[
				'label'     => __('Background', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .description' => 'background: {{VALUE}};padding: 5px;',
				],
				'condition' => [
					$this->get_name_setting('description') . '!' => '',
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => $this->get_name_setting('description_typography'),
				'selector'  => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .description',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => [
					$this->get_name_setting('description') . '!' => '',
				],
			]
		);
		$repeater->add_responsive_control(
			$this->get_name_setting('description_bottom_space'),
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
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('description') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('description_animation'),
			[
				'label'              => __('Animation', App::get_domain()),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'condition'          => [
					$this->get_name_setting('description') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('description_animation_duration'),
			[
				'label'        => __('Animation Duration', App::get_domain()),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					'slow' => __('Slow', App::get_domain()),
					''     => __('Normal', App::get_domain()),
					'fast' => __('Fast', App::get_domain()),
				],
				'prefix_class' => 'animated-',
				'condition'    => [
					$this->get_name_setting('description_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('description') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('description_animation_delay'),
			[
				'label'              => __('Animation Delay', App::get_domain()) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'default'            => '',
				'min'                => 0,
				'step'               => 100,
				'condition'          => [
					$this->get_name_setting('description_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('description') . '!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		// </editor-fold>

		// <editor-fold desc="Button 01">
		$repeater->add_control(
			'button_1_heading',
			[
				'label'     => __('Button 01', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			$this->get_name_setting('button_1'),
			[
				'label'       => __('Text', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your button here', App::get_domain()),
			]
		);
		/* Button 01 Type */
		$repeater->add_control(
			$this->get_name_setting('button_1_type'),
			[
				'label'     => __('Type', App::get_domain()),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'url'         => __('URL', App::get_domain()),
					'video'       => __('Hosted Video', App::get_domain()),
					'youtube'     => __('Youtube', App::get_domain()),
					'vimeo'       => __('Vimeo', App::get_domain()),
					'dailymotion' => __('Dailymotion', App::get_domain()),
				],
				'default'   => 'url',
				'condition' => [
					$this->get_name_setting('button_1') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_url'),
			[
				'label'       => __('Url', App::get_domain()),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'media_type'  => 'video',
				'placeholder' => __('Type your button url here', App::get_domain()),
				'condition'   => [
					$this->get_name_setting('button_1') . '!' => '',
					$this->get_name_setting('button_1_type') => [ 'url' ],
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_video'),
			[
				'label'       => __('Video', App::get_domain()),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => [ 'video' ],
				'condition'   => [
					$this->get_name_setting('button_1') . '!' => '',
					$this->get_name_setting('button_1_type') => 'video',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_youtube'),
			[
				'label'              => __('Link', App::get_domain()),
				'type'               => Controls_Manager::TEXT,
				'dynamic'            => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder'        => __('Enter your URL', App::get_domain()) . ' (YouTube)',
				'default'            => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block'        => true,
				'condition'          => [
					$this->get_name_setting('button_1') . '!' => '',
					$this->get_name_setting('button_1_type') => 'youtube',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_vimeo'),
			[
				'label'       => __('Link', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __('Enter your URL', App::get_domain()) . ' (Vimeo)',
				'default'     => 'https://vimeo.com/235215203',
				'label_block' => true,
				'condition'   => [
					$this->get_name_setting('button_1') . '!' => '',
					$this->get_name_setting('button_1_type') => 'vimeo',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_dailymotion'),
			[
				'label'       => __('Link', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __('Enter your URL', App::get_domain()) . ' (Dailymotion)',
				'default'     => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition'   => [
					$this->get_name_setting('button_1') . '!' => '',
					$this->get_name_setting('button_1_type') => 'dailymotion',
				],
			]
		);

		// Button 1 Style.
		$repeater->start_controls_section(
			$this->get_name_setting('button_01_section'),
			[
				'label'     => __('Button 01', App::get_domain()),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'single',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_icon'),
			[
				'label'       => __('Icon', App::get_domain()),
				'type'        => Controls_Manager::ICONS,
				'description' => __('Choose or upload your custom icon to override the default icon', App::get_domain()),
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $this->get_name_setting('button_01_typography'),
				'selector' => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1 .button-text',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => $this->get_name_setting('button_01_border'),
				'selector'  => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1',
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			$this->get_name_setting('button_01_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('button_01_border_border') . '!' => '',
				],
			]
		);
		$repeater->add_responsive_control(
			$this->get_name_setting('button_01_padding'),
			[
				'label'      => __('Padding', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$repeater->start_controls_tabs($this->get_name_setting('button_01_tabs'));

		$repeater->start_controls_tab(
			$this->get_name_setting('button_01_tab'),
			[
				'label' => __('Normal', App::get_domain()),
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_01_text_color'),
			[
				'label'     => __('Text Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_01_background'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color'      => [],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_1_icon_background'),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1 .button-icon',
				'fields_options' => [
					'background' => [
						'label'   => __('Icon Background Type', App::get_domain()),
						'default' => 'classic',
					],
					'color' => [
						'label' => __('Icon Color', App::get_domain()),
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			$this->get_name_setting('button_01_hover_tab'),
			[
				'label' => __('Hover', App::get_domain()),
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_01_text_hover_color'),
			[
				'label'     => __('Text Hover Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1:hover, {{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_01_background_hover'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1:hover, {{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_1_icon_background_hover'),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-1:hover .button-icon',
				'fields_options' => [
					'background' => [
						'label'   => __('Icon Background Type', App::get_domain()),
						'default' => 'classic',
					],
					'color' => [
						'label' => __('Icon Color', App::get_domain()),
						/* phpcs:ignore
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
						*/
					],
				],
			]
		);
		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();
		$repeater->end_controls_section();

		/* Button 1 Animation */
		$repeater->add_control(
			$this->get_name_setting('button_1_animation'),
			[
				'label'              => __('Animation', App::get_domain()),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'condition'          => [
					$this->get_name_setting('button_1') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_animation_duration'),
			[
				'label'        => __('Animation Duration', App::get_domain()),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					'slow' => __('Slow', App::get_domain()),
					''     => __('Normal', App::get_domain()),
					'fast' => __('Fast', App::get_domain()),
				],
				'prefix_class' => 'animated-',
				'condition'    => [
					$this->get_name_setting('button_1_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('button_1') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_1_animation_delay'),
			[
				'label'              => __('Animation Delay', App::get_domain()) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'default'            => '',
				'min'                => 0,
				'step'               => 100,
				'condition'          => [
					$this->get_name_setting('button_1_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('button_1') . '!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);
		// </editor-fold>

		// <editor-fold desc="Button 02">

		// <editor-fold desc="Button 02 Title">
		$repeater->add_control(
			'button_2_heading',
			[
				'label'     => __('Button 02', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			$this->get_name_setting('button_2'),
			[
				'label'       => __('Text', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your sub button here', App::get_domain()),
			]
		);
		// </editor-fold>

		// <editor-fold desc="Button 02 Type">
		$repeater->add_control(
			$this->get_name_setting('button_2_type'),
			[
				'label'     => __('Type', App::get_domain()),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'url'         => __('URL', App::get_domain()),
					'video'       => __('Hosted Video', App::get_domain()),
					'youtube'     => __('Youtube', App::get_domain()),
					'vimeo'       => __('Vimeo', App::get_domain()),
					'dailymotion' => __('Dailymotion', App::get_domain()),
				],
				'default'   => 'url',
				'condition' => [
					$this->get_name_setting('button_2') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_url'),
			[
				'label'       => __('Url', App::get_domain()),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'media_type'  => 'video',
				'placeholder' => __('Type your button url here', App::get_domain()),
				'condition'   => [
					$this->get_name_setting('button_2') . '!' => '',
					$this->get_name_setting('button_2_type') => [ 'url' ],
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_video'),
			[
				'label'       => __('Video', App::get_domain()),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => [ 'video' ],
				'condition'   => [
					$this->get_name_setting('button_2') . '!' => '',
					$this->get_name_setting('button_2_type') => 'video',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_youtube'),
			[
				'label'              => __('Link', App::get_domain()),
				'type'               => Controls_Manager::TEXT,
				'dynamic'            => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder'        => __('Enter your URL', App::get_domain()) . ' (YouTube)',
				'default'            => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block'        => true,
				'condition'          => [
					$this->get_name_setting('button_2') . '!' => '',
					$this->get_name_setting('button_2_type') => 'youtube',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_vimeo'),
			[
				'label'       => __('Link', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __('Enter your URL', App::get_domain()) . ' (Vimeo)',
				'default'     => 'https://vimeo.com/235215203',
				'label_block' => true,
				'condition'   => [
					$this->get_name_setting('button_2') . '!' => '',
					$this->get_name_setting('button_2_type') => 'vimeo',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_dailymotion'),
			[
				'label'       => __('Link', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __('Enter your URL', App::get_domain()) . ' (Dailymotion)',
				'default'     => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition'   => [
					$this->get_name_setting('button_2') . '!' => '',
					$this->get_name_setting('button_2_type') => 'dailymotion',
				],
			]
		);
		// </editor-fold>

		// <editor-fold desc="Button 02 Style">
		$repeater->start_controls_section(
			$this->get_name_setting('button_02_section'),
			[
				'label'     => __('Button 02', App::get_domain()),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'single',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_icon'),
			[
				'label'       => __('Icon', App::get_domain()),
				'type'        => Controls_Manager::ICONS,
				'description' => __('Choose or upload your custom icon to override the default icon', App::get_domain()),
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $this->get_name_setting('button_02_typography'),
				'selector' => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2 .button-text',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => $this->get_name_setting('button_02_border'),
				'selector'  => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2',
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			$this->get_name_setting('button_02_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('button_02_border_border') . '!' => '',
				],
			]
		);
		$repeater->add_responsive_control(
			$this->get_name_setting('button_02_padding'),
			[
				'label'      => __('Padding', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$repeater->start_controls_tabs($this->get_name_setting('button_02_tabs'));

		$repeater->start_controls_tab(
			$this->get_name_setting('button_02_tab'),
			[
				'label' => __('Normal', App::get_domain()),
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_02_text_color'),
			[
				'label'     => __('Text Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_02_background'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						/* phpcs:ignore
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
						*/
					],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_2_icon_background'),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2 .button-icon',
				'fields_options' => [
					'background' => [
						'label'   => __('Icon Background Type', App::get_domain()),
						'default' => 'classic',
					],
					'color' => [
						'label' => __('Icon Background Color', App::get_domain()),
						/* phpcs:ignore
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
						*/
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			$this->get_name_setting('button_02_hover_tab'),
			[
				'label' => __('Hover', App::get_domain()),
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_02_text_hover_color'),
			[
				'label'     => __('Text Hover Color', App::get_domain()),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2:hover, {{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_02_background_hover'),
				'label'          => __('Background', App::get_domain()),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2:hover, {{WRAPPER}} .rbb-elementor-slider .rbb-slick-button.button-2:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('button_2_icon_background_hover'),
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .rbb-slick-button.button-2:hover .button-icon',
				'fields_options' => [
					'background' => [
						'label'   => __('Icon Background Type', App::get_domain()),
						'default' => 'classic',
					],
					'color' => [
						'label' => __('Icon Color', App::get_domain()),
						/* phpcs:ignore
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
						*/
					],
				],
			]
		);
		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();
		$repeater->end_controls_section();
		// </editor-fold>

		// <editor-fold desc="Background 02 Animation">
		$repeater->add_control(
			$this->get_name_setting('button_2_animation'),
			[
				'label'              => __('Animation', App::get_domain()),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'condition'          => [
					$this->get_name_setting('button_2') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_animation_duration'),
			[
				'label'        => __('Animation Duration', App::get_domain()),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					'slow' => __('Slow', App::get_domain()),
					''     => __('Normal', App::get_domain()),
					'fast' => __('Fast', App::get_domain()),
				],
				'prefix_class' => 'animated-',
				'condition'    => [
					$this->get_name_setting('button_2_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('button_2') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('button_2_animation_delay'),
			[
				'label'              => __('Animation Delay', App::get_domain()) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'default'            => '',
				'min'                => 0,
				'step'               => 100,
				'condition'          => [
					$this->get_name_setting('button_2_animation') . '!' => [ 'none', '' ],
					$this->get_name_setting('button_2') . '!' => '',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);
		// </editor-fold>

		// </editor-fold>

		// <editor-fold desc="Align">
		$repeater->add_control(
			'align_heading',
			[
				'label'     => __('Align', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			$this->get_name_setting('horizontal_align'),
			[
				'label'   => __('Horizontal Align', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'start'  => __('Start', App::get_domain()),
					'center' => __('Center', App::get_domain()),
					'end'    => __('End', App::get_domain()),
				],
				'default' => 'start',
			]
		);
		$repeater->add_control(
			$this->get_name_setting('vertical_align'),
			[
				'label'   => __('Vertical Align', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'top'    => __('Top', App::get_domain()),
					'middle' => __('Middle', App::get_domain()),
					'bottom' => __('Bottom', App::get_domain()),
				],
				'default' => 'middle',
			]
		);
		// </editor-fold/>

		// <editor-fold desc="Background">
		$repeater->add_control(
			'image_heading',
			[
				'label'     => __('Background', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_responsive_control(
			$this->get_name_setting('image_height'),
			[
				'label'                => __('Slider Height', App::get_domain()),
				'type'                 => Controls_Manager::SLIDER,
				'range'                => [
					'px' => [
						'min'  => 100,
						'max'  => 2000,
						'step' => 10,
					],
				],
				'widescreen_default'   => [
					'unit' => 'px',
					'size' => 1100,
				],
				'default'              => [
					'unit' => 'px',
					'size' => 990,
				],
				'laptop_default'       => [
					'unit' => 'px',
					'size' => 990,
				],
				'tablet_extra_default' => [
					'unit' => 'px',
					'size' => 800,
				],
				'tablet_default'       => [
					'unit' => 'px',
					'size' => 600,
				],
				'mobile_extra_default' => [
					'unit' => 'px',
					'size' => 600,
				],
				'mobile_default'       => [
					'unit' => 'px',
					'size' => 400,
				],
				'size_units'           => [ 'px' ],
				'selectors'            => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .item-content' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => $this->get_name_setting('image'),
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
				'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} .item-content',
			]
		);

		// </editor-fold>

		// <editor-fold desc="Images">
		$this->add_image($repeater);
		// </editor-fold>

		// <editor-fold desc="Countdown">

		$repeater->add_control(
			'count_down_heading',
			[
				'label'     => __('Countdown', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			$this->get_name_setting('countdown_due_date'),
			[
				'label'        => __('Due Date', App::get_domain()),
				'type'         => Controls_Manager::DATE_TIME,
				'description'  => __('Countdown Due Date', App::get_domain()),
			],
		);

		$repeater->add_control(
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

		$repeater->add_control(
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

		$repeater->add_responsive_control(
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

		$repeater->add_responsive_control(
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

		$repeater->add_control(
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

		$repeater->add_responsive_control(
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

		$repeater->add_responsive_control(
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

		// Add repeater to sliders control.
		$this->add_control(
			$this->get_name_setting('sliders_single'),
			[
				'label'  => esc_html__('Sliders', App::get_domain()),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Content field for multiple layout type.
	 *
	 * @return void
	 */
	protected function contentMultipleLayout(): void {
		$this->start_controls_section(
			$this->get_name_setting('content_section_multiple'),
			[
				'label'     => esc_html__('Content for Multiple Layout', App::get_domain()),
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
			]
		);

		$repeater = new Repeater();

		// <editor-fold desc="Title">
		$repeater->add_control(
			$this->get_name_setting('multiple_content_title'),
			[
				'label' => esc_html__('Title', App::get_domain()),
				'type'  => Controls_Manager::TEXT,
			]
		);
		// </editor-fold >

		// <editor-fold desc="Description">
		$repeater->add_control(
			$this->get_name_setting('multiple_content_description'),
			[
				'label' => esc_html__('Description', App::get_domain()),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);
		// </editor-fold >

		// <editor-fold desc="Image">
		$repeater->add_control(
			$this->get_name_setting('multiple_content_image'),
			[
				'type' => Controls_Manager::MEDIA,
			]
		);
		// </editor-fold >

		// <editor-fold desc="Background">
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => $this->get_name_setting('multiple_content_background'),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .rbb-elementor-slider.multiple {{CURRENT_ITEM}} .block__content',
			]
		);

		// </editor-fold >

		// <editor-fold desc="Link">
		$repeater->add_control(
			$this->get_name_setting('multiple_content_link'),
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
		// </editor-fold>

		// <editor-fold desc="Button">
		$repeater->add_control(
			'multiple_content_button_heading',
			[
				'label'     => __('Button', App::get_domain()),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			$this->get_name_setting('multiple_content_button'),
			[
				'label'       => __('Text', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('Type your button here', App::get_domain()),
			]
		);

		$repeater->add_control(
			$this->get_name_setting('multiple_content_button_icon'),
			[
				'label'       => __('Icon', App::get_domain()),
				'type'        => Controls_Manager::ICONS,
				'description' => __('Choose or upload your custom icon to override the default icon', App::get_domain()),
			]
		);

		$repeater->add_control(
			$this->get_name_setting('multiple_content_button_type'),
			[
				'label'     => __('Type', App::get_domain()),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'url'         => __('URL', App::get_domain()),
					'video'       => __('Hosted Video', App::get_domain()),
					'youtube'     => __('Youtube', App::get_domain()),
					'vimeo'       => __('Vimeo', App::get_domain()),
					'dailymotion' => __('Dailymotion', App::get_domain()),
				],
				'default'   => 'url',
				'condition' => [
					$this->get_name_setting('multiple_content_button') . '!' => '',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('multiple_content_button_url'),
			[
				'label'       => __('Url', App::get_domain()),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'media_type'  => 'video',
				'placeholder' => __('Type your button url here', App::get_domain()),
				'condition'   => [
					$this->get_name_setting('multiple_content_button') . '!' => '',
					$this->get_name_setting('multiple_content_button_type') => [ 'url' ],
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('multiple_content_button_video'),
			[
				'label'       => __('Video', App::get_domain()),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => [ 'video' ],
				'condition'   => [
					$this->get_name_setting('multiple_content_button') . '!' => '',
					$this->get_name_setting('multiple_content_button_type') => 'video',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('multiple_content_button_youtube'),
			[
				'label'              => __('Link', App::get_domain()),
				'type'               => Controls_Manager::TEXT,
				'dynamic'            => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder'        => __('Enter your URL', App::get_domain()) . ' (YouTube)',
				'default'            => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block'        => true,
				'condition'          => [
					$this->get_name_setting('multiple_content_button') . '!' => '',
					$this->get_name_setting('multiple_content_button_type') => 'youtube',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			$this->get_name_setting('multiple_content_button_vimeo'),
			[
				'label'       => __('Link', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __('Enter your URL', App::get_domain()) . ' (Vimeo)',
				'default'     => 'https://vimeo.com/235215203',
				'label_block' => true,
				'condition'   => [
					$this->get_name_setting('multiple_content_button') . '!' => '',
					$this->get_name_setting('multiple_content_button_type') => 'vimeo',
				],
			]
		);

		$repeater->add_control(
			$this->get_name_setting('multiple_content_button_dailymotion'),
			[
				'label'       => __('Link', App::get_domain()),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => __('Enter your URL', App::get_domain()) . ' (Dailymotion)',
				'default'     => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition'   => [
					$this->get_name_setting('multiple_content_button') . '!' => '',
					$this->get_name_setting('multiple_content_button_type') => 'dailymotion',
				],
			]
		);
		// </editor-fold >

		$this->add_control(
			$this->get_name_setting('sliders_multiple'),
			[
				'label'  => esc_html__('Items', App::get_domain()),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content General.
	 *
	 * @return void
	 */
	protected function contentGeneralSection(): void {
		$this->start_controls_section(
			$this->get_name_setting('general_section'),
			[
				'label' => __('General', App::get_domain()),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		// <editor-fold desc="General Title">
		$this->add_control(
			$this->get_name_setting('general_title'),
			[
				'label' => __('Title', App::get_domain()),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => $this->get_name_setting('general_title_typo'),
				'selector'       => '{{WRAPPER}} .title_block .main-title',
				'fields_options' => [
					'font_family' => [
						'default' => '',
					],
					'font_size' => [
						'default' => [
							'unit' => 'rem',
							'size' => 2,
						],
					],
				],
				'condition'      => [
					$this->get_name_setting('general_title') . '!' => '',
				],
			]
		);
		// </editor-fold>

		// <editor-fold desc="General Description">
		$this->add_control(
			$this->get_name_setting('general_sub_title'),
			[
				'label' => __('Sub Title', App::get_domain()),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => $this->get_name_setting('general_sub_title_typo'),
				'selector'       => '{{WRAPPER}} .title_block .sub-title',
				'fields_options' => [
					'font_family' => [
						'default' => 'Playball',
					],
					'font_size' => [
						'default' => [
							'unit' => 'rem',
							'size' => 1.5,
						],
					],
				],
				'condition'      => [
					$this->get_name_setting('general_description') . '!' => '',
				],
			]
		);
		// </editor-fold>

		$this->add_control(
			$this->get_name_setting('general_autoplay'),
			[
				'label'   => __('Auto Play', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'true'  => __('Yes', App::get_domain()),
					'false' => __('No', App::get_domain()),
				],
				'default' => 'false',
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
					$this->get_name_setting('general_autoplay') => 'true',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('general_show_nav'),
			[
				'label'   => __('Show Navigation', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'true'  => __('Yes', App::get_domain()),
					'false' => __('No', App::get_domain()),
				],
				'default' => 'true',
			]
		);

		$this->add_control(
			$this->get_name_setting('general_show_pagination'),
			[
				'label'   => __('Show Pagination', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'true'  => __('Yes', App::get_domain()),
					'false' => __('No', App::get_domain()),
				],
				'default' => 'false',
			]
		);

		$this->add_control(
			$this->get_name_setting('general_pause'),
			[
				'label'   => __('Pause', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'false'               => __('No', App::get_domain()),
					'pause_on_focus'      => __('Pause On Focus', App::get_domain()),
					'pause_on_hover'      => __('Pause On Hover', App::get_domain()),
					'pause_on_dots_hover' => __('Pause On Dots Hover', App::get_domain()),
				],
				'default' => 'false',
			]
		);

		$this->add_control(
			$this->get_name_setting('general_layout_type'),
			[
				'label'   => __('Layout Type', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'single',
				'options' => [
					'single'   => esc_html__('Single', App::get_domain()),
					'multiple' => esc_html__('Multiple', App::get_domain()),
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('general_layout_single'),
			[
				'label'     => __('Layout', App::get_domain()),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => Helper::list_templates('elementor/widgets/slider/single'),
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'single',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('general_layout_multiple'),
			[
				'label'     => __('Layout', App::get_domain()),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => Helper::list_templates('elementor/widgets/slider/multiple'),
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
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
				'condition'    => [
					$this->get_name_setting('general_layout_type') => 'single',
				],
			],
		);

		$this->multipleLayoutGeneral();

		$this->end_controls_section();
	}

	/**
	 * Navigation in style tab.
	 *
	 * @return void
	 */
	protected function styleNavigationSection(): void {

		/**
		 * Style : Navigation section.
		 */
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-arrow' => 'visibility: hidden;opacity: 0;',
					'{{WRAPPER}} .rbb-elementor-slider:hover .slick-arrow' => 'visibility: visible;opacity: 1;',
				],
				'separator'    => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $this->get_name_setting('style_navigation_border'),
				'selector' => '{{WRAPPER}} .rbb-elementor-slider .slick-arrow',
			]
		);
		$this->add_control(
			$this->get_name_setting('style_navigation_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .rbb-elementor-slider .slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-arrow::before' => 'fill: {{VALUE}}; color: {{VALUE}};',
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
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider .slick-arrow',
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-arrow:hover::before, {{WRAPPER}} .rbb-elementor-slider .slick-arrow:focus::before' => 'color: {{VALUE}};',
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
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider .slick-arrow:hover, {{WRAPPER}} .rbb-elementor-slider .slick-arrow:focus',
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-arrow:hover, {{WRAPPER}} .rbb-elementor-slider .slick-arrow:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Pagination in style tab.
	 *
	 * @return void
	 */
	protected function stylePaginationSection(): void {
		// <editor-fold desc="Style Pagination Section">
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots' => 'text-align: {{VALUE}};',
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
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider .slick-dots',
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// <editor-fold desc="Style Pagination Tabs">
		$this->start_controls_tabs($this->get_name_setting('style_pagination_tabs'));

		// <editor-fold desc="Style Pagination Normal">
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots li:not(.slick-active) button::before' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $this->get_name_setting('style_pagination_normal_border'),
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider .slick-dots li:not(.slick-active)',
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('style_pagination_normal_border') . '_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		// </editor-fold>

		// <editor-fold desc="Style Pagination Active">
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots li.slick-active button::before' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $this->get_name_setting('style_pagination_active_border'),
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider .slick-dots li.slick-active',
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots li.slick-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('style_pagination_active_border') . '_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		// </editor-fold>

		// <editor-fold desc="Style Pagination Hover">
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots li:not(.slick-active):hover button::before, {{WRAPPER}} .rbb-elementor-slider .slick-dots li:not(.slick-active):focus button::before' => 'fill: {{VALUE}};color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $this->get_name_setting('style_pagination_hover_border'),
				'selector'       => '{{WRAPPER}} .rbb-elementor-slider .slick-dots li:not(.slick-active):hover',
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
					'{{WRAPPER}} .rbb-elementor-slider .slick-dots li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					$this->get_name_setting('style_pagination_hover_border') . '_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		// </editor-fold>
		$this->end_controls_tabs();
		// </editor-fold>

		$this->end_controls_section();
		// </editor-fold>
	}

	/**
	 * General field for multiple layout
	 *
	 * @return void
	 */
	protected function multipleLayoutGeneral(): void {
		$default_sliders = [
			'mobile'       => 2,
			'mobile_extra' => 2,
			'tablet'       => 3,
			'tablet_extra' => 4,
			'laptop'       => 4,
			'widescreen'   => 5,
		];
		// <editor-fold desc="Surrounding Animation Image">
		$this->add_control(
			$this->get_name_setting('general_layout_multiple_surrounding_animation_image_header'),
			[
				'label'       => esc_html__('Surrounding Animation Images', App::get_domain()),
				'type'        => Controls_Manager::HEADING,
				'separator'   => 'before',
				'condition'   => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('general_layout_multiple_surrounding_animation_image_01'),
			[
				'type'      => Controls_Manager::MEDIA,
				'label'     => __('Image 01', App::get_domain()),
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('general_layout_multiple_surrounding_animation_image_02'),
			[
				'type'      => Controls_Manager::MEDIA,
				'label'     => __('Image 02', App::get_domain()),
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
			]
		);

		// </editor-fold>
		// <editor-fold desc="Slides Per Row">
		$this->add_control(
			$this->get_name_setting('general_layout_slides_responsive_header'),
			[
				'label'       => esc_html__('Sliders Per Row', App::get_domain()),
				'description' => esc_html__('You should set up 2 to 6 products on the Desktop. 2 - 3 products on Tablet, 1 - 2 products on Mobile', App::get_domain()),
				'type'        => Controls_Manager::HEADING,
				'separator'   => 'before',
				'condition'   => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
			]
		);
		foreach ( $this->active_break_points as $break_point ) {
			$this->add_control(
				$this->get_name_setting('general_layout_multiple_sliders_to_show_') . $break_point->get_name(),
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
						'size' => $default_sliders[ $break_point->get_name() ] ?? 4,
					],
					'condition'  => [
						$this->get_name_setting('general_layout_type') => 'multiple',
					],
				]
			);
		}
		// </editor-fold>
	}

	/**
	 * Spacing for multiple layout.
	 *
	 * @return void
	 */
	protected function styleSpacingForMultipleLayout(): void {
		$this->start_controls_section(
			$this->get_name_setting('style_spacing_section'),
			[
				'label'     => __('Spacing', App::get_domain()),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
			]
		);

		$this->add_responsive_control(
			$this->get_name_setting('style_spacing_padding'),
			[
				'label'           => __('Padding', App::get_domain()),
				'type'            => Controls_Manager::DIMENSIONS,
				'size_units'      => [ 'px', 'em', '%' ],
				'placeholder'     => [
					'top'    => 50,
					'right'  => 30,
					'bottom' => 50,
					'left'   => 30,
				],
				'default'         => [
					'top'       => 50,
					'left'      => 30,
					'right'     => 30,
					'bottom'    => 50,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'desktop_default' => [
					'top'       => 50,
					'left'      => 30,
					'right'     => 30,
					'bottom'    => 50,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'tablet_default'  => [
					'top'       => 30,
					'left'      => 20,
					'right'     => 20,
					'bottom'    => 30,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'mobile_default'  => [
					'top'       => 20,
					'left'      => 10,
					'right'     => 10,
					'bottom'    => 20,
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'selectors'       => [
					'{{WRAPPER}} .rbb-elementor-slider.multiple .slick-list ' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right:-{{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} .rbb-elementor-slider.multiple .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'       => [
					$this->get_name_setting('general_layout_type') => 'multiple',
				],
			]
		);
	}

	/**
	 * The method, which is where you actually render the code and generate the final HTML on the frontend using PHP.
	 */
	protected function render(): void {
		$layout_type = $this->get_value_setting('general_layout_type', 'single');
		$layout      = $this->get_value_setting('general_layout_' . $layout_type, 'default'); // phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found

		$params = [
			'id'                      => $this->uniqID(),
			'slick'                   => $this,
			'sliders'                 => $this->get_value_setting('sliders' . '_' . $layout_type), // phpcs:ignore Generic.Strings.UnnecessaryStringConcat.Found
			'widget_title'            => $this->get_value_setting('general_title'),
			'widget_sub_title'        => $this->get_value_setting('general_sub_title'),
			'autoplay'                => $this->get_value_setting('general_autoplay', false),
			'autoplay_speed'          => $this->get_value_setting('general_autoplay_speed', [ 'size' => 3000 ])['size'],
			'show_arrows'             => $this->get_value_setting('general_show_nav', true),
			'show_pagination'         => $this->get_value_setting('general_show_pagination', true),
			'layout_type'             => $layout_type,
			'layout'                  => strtolower($layout),
			'active_break_points'     => $this->active_break_points,
			'sliders_to_show_default' => $this->getDefaultSliderPerRow(),
			'parallax'                => $this->get_value_setting('general_parallax_enable'),
		];

		if ( 'multiple' === $layout_type ) {
			$params['surrounding_animation_image_01'] = $this->get_value_setting('general_layout_multiple_surrounding_animation_image_01');
			$params['surrounding_animation_image_02'] = $this->get_value_setting('general_layout_multiple_surrounding_animation_image_02');
		}

		View::instance()->load(
			'elementor/widgets/slider/' . $layout_type . '/' . strtolower($layout),
			$params
		);
	}

	/**
	 * Render Editor Output
	 * The method is where you render the editor output to generate the live preview using a Backbone JavaScript template.
	 */
	protected function content_template(): void {
	}

	/**
	 * Add image.
	 *
	 * @param mixed  $control Control.
	 * @param string $name Name.
	 * @return void
	 */
	protected function add_image( $control, string $name = 'image' ): void {
		$start = is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());
		$end   = ! is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());
		for ( $i = 1; $i <= $this->images_per_slider; $i++ ) {
			$_name = $name . '_' . $i;
			// <editor-fold desc="Image">
			$control->add_control(
				$_name . '_heading',
				[
					'label'     => __('Image ', App::get_domain()) . str_pad($i, 2, '0', STR_PAD_LEFT),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$control->add_control(
				$this->get_name_setting($_name),
				[
					'type' => Controls_Manager::MEDIA,
				]
			);

			$control->add_control(
				$this->get_name_setting($_name . '_z_index'),
				[
					'label'     => __('Index', App::get_domain()),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 0,
					'max'       => 5,
					'step'      => 1,
					'default'   => 1,
					'condition' => [
						$this->get_name_setting($_name) . '[id]!' => '',
					],
				]
			);

			$control->add_responsive_control(
				$this->get_name_setting($_name . '_max_width'),
				[
					'label'     => __('Max Width', App::get_domain()),
					'type'      => Controls_Manager::NUMBER,
					'title'     => __('Set max-with of image', App::get_domain()),
					'selectors' => [
						'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .' . $_name => 'max-width: {{SIZE}}px',
					],
					'condition' => [
						$this->get_name_setting($_name) . '[id]!' => '',
					],
				]
			);

			$control->add_control(
				$this->get_name_setting($_name . '_position'),
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
						$this->get_name_setting($_name) . '[id]!' => '',
					],
					'separator'   => 'before',
				]
			);

			$control->add_control(
				$this->get_name_setting($_name . '_offset_orientation_h'),
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
						$this->get_name_setting($_name) . '[id]!' => '',
						$this->get_name_setting($_name) . '_position' => 'absolute',
					],
				]
			);

			$control->add_responsive_control(
				$this->get_name_setting($_name . '_offset_x'),
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
						'body:not(.rtl) {{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .' . $_name => 'right: initial !important;left: {{SIZE}}{{UNIT}} !important',
						'body.rtl {{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .' . $_name => 'left: initial !important; right: {{SIZE}}{{UNIT}} !important',
					],
					'condition'  => [
						$this->get_name_setting($_name . '_offset_orientation_h') . '!' => 'end',
						$this->get_name_setting($_name) . '[id]!' => '',
						$this->get_name_setting($_name) . '_position' => 'absolute',
					],
				]
			);

			$control->add_responsive_control(
				$this->get_name_setting($_name . '_offset_x_end'),
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
						'body:not(.rtl) {{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .' . $_name => 'left: initial !important; right: {{SIZE}}{{UNIT}} !important',
						'body.rtl {{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .' . $_name => 'right: initial !important; left: {{SIZE}}{{UNIT}} !important',
					],
					'condition'  => [
						$this->get_name_setting($_name . '_offset_orientation_h') => 'end',
						$this->get_name_setting($_name) . '[id]!' => '',
						$this->get_name_setting($_name) . '_position' => 'absolute',
					],
				]
			);

			$control->add_control(
				$this->get_name_setting($_name . '_offset_orientation_v'),
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
						$this->get_name_setting($_name) . '[id]!' => '',
						$this->get_name_setting($_name) . '_position' => 'absolute',
					],
				]
			);

			$control->add_responsive_control(
				$this->get_name_setting($_name . '_offset_y'),
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
						'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .' . $_name => 'bottom: initial !important; top: {{SIZE}}{{UNIT}} !important',
					],
					'condition'  => [
						$this->get_name_setting($_name . '_offset_orientation_v') . '!' => 'end',
						$this->get_name_setting($_name) . '[id]!' => '',
						$this->get_name_setting($_name) . '_position' => 'absolute',
					],
				]
			);

			$control->add_responsive_control(
				$this->get_name_setting($_name . '_offset_y_end'),
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
						'{{WRAPPER}} .rbb-elementor-slider {{CURRENT_ITEM}} .' . $_name => 'top: initial !important; bottom: {{SIZE}}{{UNIT}} !important',
					],
					'condition'  => [
						$this->get_name_setting($_name . '_offset_orientation_v') => 'end',
						$this->get_name_setting($_name) . '[id]!' => '',
						$this->get_name_setting($_name) . '_position' => 'absolute',
					],
				]
			);

			$control->add_control(
				$this->get_name_setting($_name . '_animation'),
				[
					'label'              => __('Animation', App::get_domain()),
					'type'               => Controls_Manager::ANIMATION,
					'frontend_available' => true,
					'condition'          => [
						$this->get_name_setting($_name) . '[id]!' => '',
					],
				]
			);

			$control->add_control(
				$this->get_name_setting($_name . '_animation_duration'),
				[
					'label'        => __('Animation Duration', App::get_domain()),
					'type'         => Controls_Manager::SELECT,
					'default'      => '',
					'options'      => [
						'slow' => __('Slow', App::get_domain()),
						''     => __('Normal', App::get_domain()),
						'fast' => __('Fast', App::get_domain()),
					],
					'prefix_class' => 'animated-',
					'condition'    => [
						$this->get_name_setting($_name . '_animation') . '!' => [ 'none', '' ],
						$this->get_name_setting($_name) . '[id]!' => '',
					],
				]
			);

			$control->add_control(
				$this->get_name_setting($_name . '_animation_delay'),
				[
					'label'              => __('Animation Delay', App::get_domain()) . ' (ms)',
					'type'               => Controls_Manager::NUMBER,
					'default'            => '',
					'min'                => 0,
					'step'               => 100,
					'condition'          => [
						$this->get_name_setting($_name . '_animation') . '!' => [ 'none', '' ],
						$this->get_name_setting($_name) . '[id]!' => '',
					],
					'render_type'        => 'none',
					'frontend_available' => true,
				]
			);
			// </editor-fold>
		}
	}

	/**
	 * Get default slider per row.
	 *
	 * @param bool $mobile_first Mobile first.
	 * @return int
	 */
	protected function getDefaultSliderPerRow( bool $mobile_first = false ): int {
		$result = $mobile_first ? 1 : 4;

		if ( $this->active_break_points ) {
			$device = $mobile_first ? array_key_first($this->active_break_points) : ElementorBreakpointManager::BREAKPOINT_KEY_DESKTOP;
			if ( $device && $this->get_value_setting('general_layout_multiple_sliders_to_show_' . $device) ) {
				$result = (int) ceil(abs($this->get_value_setting('general_layout_multiple_sliders_to_show_' . $device)['size']));
			}
		}
		return $result;
	}
}
