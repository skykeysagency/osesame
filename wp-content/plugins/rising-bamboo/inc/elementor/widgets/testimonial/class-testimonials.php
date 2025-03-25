<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets\Testimonial;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use RisingBambooCore\App\Admin\TestimonialPostType;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Elementor\Controls\Control as RisingBambooElementorControl;
use RisingBambooCore\Elementor\Widgets\Base;
use RisingBambooCore\Elementor\Widgets\Widget;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooTheme\Customizer\Helper as CustomizerHelper;

/**
 * Elementor Testimonial Widget.
 */
class Testimonials extends Base {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	protected string $prefix = 'rbb_testimonials';

	/**
	 * Construct.
	 *
	 * @param array $data Data.
	 * @param mixed $args Args.
	 * @throws \Exception Exception.
	 */
	public function __construct( array $data = [], $args = null ) {
		parent::__construct($data, $args);
		Helper::register_asset('rbb-testimonials', 'js/frontend/elementor/widgets/testimonials/testimonial.js', [ 'slick-carousel' ], '1.0.0');
	}

	/**
	 * The method is a simple one, you just need to return a widget name that will be used in the code.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return 'rbb_testimonials';
	}

	/**
	 * The method, which again, is a very simple one,
	 * you need to return the widget title that will be displayed as the widget label.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __('Testimonials', App::get_domain());
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
		return [ 'rbb-testimonials' ];
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

		// <editor-fold desc="Testimonial">
		$this->add_control(
			$this->get_name_setting('testimonials'),
			[
				'label'          => __('Testimonials', App::get_domain()),
				'multiple'       => true,
				'type'           => RisingBambooElementorControl::SELECT2,
				'select2options' => [
					'placeholder'        => __('Write Title Testimonial', App::get_domain()),
					'ajax'               => [
						'url'      => admin_url('admin-ajax.php') . '?action=rbb_get_testimonials&nonce=' . wp_create_nonce(App::get_nonce()),
						'dataType' => 'json',
						'delay'    => 500,
						'cache'    => 'true',
					],
					'minimumInputLength' => 3,
				],
			]
		);

		$this->add_control(
			$this->get_name_setting('image'),
			[
				'type'    => Controls_Manager::MEDIA,
				'label'   => __('Image', App::get_domain()),
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
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
			$this->get_name_setting('general_show_nav'),
			[
				'label'   => __('Show Navigation', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'true'  => __('Yes', App::get_domain()),
					'false' => __('No', App::get_domain()),
				],
				'default' => 'false',
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
				'default' => 'true',
			]
		);

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
			$this->get_name_setting('general_layout'),
			[
				'label'   => __('Layout', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => Helper::list_templates('elementor/widgets/testimonial'),
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
			'elementor/widgets/testimonial/' . strtolower($layout),
			[
				'widget'              => $this,
				'id'                  => $this->uniqID(),
				'layout'              => $layout,
				'autoplay'            => $this->get_value_setting('general_autoplay', false),
				'autoplay_speed'      => $this->get_value_setting('general_autoplay_speed', [ 'size' => 3000 ])['size'],
				'show_arrows'         => $this->get_value_setting('general_show_nav', true),
				'show_pagination'     => $this->get_value_setting('general_show_pagination', true),
				'testimonials'        => $this->get_testimonials(),
				'title'               => $this->get_value_setting('title'),
				'sub_title'           => $this->get_value_setting('sub_title'),
				'image'               => $this->get_value_setting('image'),
			]
		);
	}

	/**
	 * Get Testimonials.
	 *
	 * @return int[]|\WP_Post[]
	 */
	protected function get_testimonials(): array {
		return get_posts(
			[
				'include'   => $this->get_value_setting('testimonials'),
				'orderby'   => 'post__in',
				'post_type' => TestimonialPostType::POST_TYPE,
			]
		);
	}
}
