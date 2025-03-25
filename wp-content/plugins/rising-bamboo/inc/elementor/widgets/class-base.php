<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Repeater;
use Elementor\Widget_Base;
use RisingBambooCore\App\App;
use RisingBambooCore\Helper\Helper;

/**
 * Widget Base Abstract.
 */
abstract class Base extends Widget_Base {


	/**
	 * Construct.
	 *
	 * @param array $data Data.
	 * @param mixed $args Args.
	 * @throws \Exception Exception.
	 */
	public function __construct( array $data = [], $args = null ) {

		parent::__construct($data, $args);
		// <editor-fold desc="Slick">
		Helper::register_asset('slick-carousel', 'js/plugins/slick/slick.js', [], '1.0.0');
		Helper::register_asset('slick-style', 'js/plugins/slick/slick.css', [], '1.0.0');
		Helper::register_asset('slick-theme-style', 'js/plugins/slick/slick-theme.css', [ 'slick-style' ], '1.0.0');
		// </editor-fold>
		// <editor-fold desc="Parallax">
		Helper::register_asset('parallax-js', 'js/plugins/parallax-js/parallax.min.js', [], '1.0.0');
		// </editor-fold>
		// <editor-fold desc="Swiper">
		Helper::register_asset('swiper-js', 'js/plugins/swiper/swiper-bundle.min.js', [], '1.0.0');
		Helper::register_asset('swiper-style', 'js/plugins/swiper/swiper-bundle.min.css', [], '1.0.0');
		// </editor-fold>
		// <editor-fold desc="Slick">
		Helper::register_asset('rbb-select-ajax', 'js/frontend/components/select-ajax.js', [], '1.0.0');
		// </editor-fold>
	}

	/**
	 * The method is an optional but recommended method, it lets you set the widget icon.
	 * You can use any of the eicon or font-awesome icons, simply return the class name as a string.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return 'rbb-icon-brand-rising-bamboo';
	}

	/**
	 * Get name setting.
	 *
	 * @param string $name Name of setting.
	 * @return string
	 */
	public function get_name_setting( string $name ): string {
		return ( ( ! empty($this->prefix) ) ? $this->prefix . '_' : '' ) . $name;
	}

	/**
	 * Get setting value.
	 *
	 * @param string $name Name of setting.
	 * @param null   $default Default value.
	 * @return mixed|null
	 */
	public function get_value_setting( string $name, $default = null ) {
		$settings = $this->get_settings_for_display();
		return $settings[ $this->get_name_setting($name) ] ?? $default;
	}

	/**
	 * Generate uniq ID.
	 *
	 * @return string
	 */
	public function uniqID(): string {
		return uniqid($this->get_name() . '-');
	}

	/**
	 * Get mark for current items.
	 *
	 * @param mixed $control Control.
	 * @return string
	 */
	protected function get_current_item_mark( $control ): string {
		$current_item_mark = '';

		if ( $control instanceof Repeater ) {
			$current_item_mark = '{{CURRENT_ITEM}}';
		}
		return $current_item_mark;
	}

	/**
	 * Render offset.
	 *
	 * @param mixed  $control Control.
	 * @param string $name Name.
	 * @param bool   $important Add Important for Css.
	 * @return void
	 */
	public function offset_group_control( $control, string $name, bool $important = false ): void {
		$start      = is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());
		$end        = ! is_rtl() ? __('Right', App::get_domain()) : __('Left', App::get_domain());
		$_important = $important ? '!important' : '';

		$current_item_mark = $this->get_current_item_mark($control);

		if ( $control instanceof Repeater ) {
			$current_item_mark = '{{CURRENT_ITEM}}';
		}
		$control->add_control(
			$this->get_name_setting($name . '_position'),
			[
				'label'     => __('Position', App::get_domain()),
				'type'      => Controls_Manager::SELECT,
				'toggle'    => false,
				'default'   => 'relative',
				'options'   => [
					'relative' => esc_html__('Relative', App::get_domain()),
					'absolute' => esc_html__('Absolute', App::get_domain()),
				],
				'condition' => [
					$this->get_name_setting($name) . '[id]!' => '',
				],
				'separator' => 'before',
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_z_index'),
			[
				'label'     => __('Index', App::get_domain()),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 5,
				'step'      => 1,
				'default'   => 2,
				'condition' => [
					$this->get_name_setting('button') . '[id]!' => '',
				],
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_offset_orientation_h'),
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
					$this->get_name_setting($name) . '[id]!' => '',
					$this->get_name_setting($name) . '_position' => 'absolute',
				],
			]
		);

		$control->add_responsive_control(
			$this->get_name_setting($name . '_offset_x'),
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
					'body:not(.rtl) {{WRAPPER}} ' . $current_item_mark => 'right: initial ' . $_important . ' ;left: {{SIZE}}{{UNIT}} ' . $_important,
					'body.rtl {{WRAPPER}} ' . $current_item_mark => 'left:initial ' . $_important . '; right: {{SIZE}}{{UNIT}} ' . $_important,
				],
				'condition'  => [
					$this->get_name_setting($name . '_offset_orientation_h') . '!' => 'end',
					$this->get_name_setting($name) . '[id]!' => '',
					$this->get_name_setting($name) . '_position' => 'absolute',
				],
			]
		);

		$control->add_responsive_control(
			$this->get_name_setting($name . '_offset_x_end'),
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
					'body:not(.rtl) {{WRAPPER}} ' . $current_item_mark => 'left: initial ' . $_important . '; right: {{SIZE}}{{UNIT}} ' . $_important,
					'body.rtl {{WRAPPER}} ' . $current_item_mark => 'right: initial ' . $_important . '; left: {{SIZE}}{{UNIT}} ' . $_important,
				],
				'condition'  => [
					$this->get_name_setting($name . '_offset_orientation_h') => 'end',
					$this->get_name_setting($name) . '[id]!' => '',
					$this->get_name_setting($name) . '_position' => 'absolute',
				],
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_offset_orientation_v'),
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
					$this->get_name_setting($name) . '[id]!' => '',
					$this->get_name_setting($name) . '_position' => 'absolute',
				],
			]
		);

		$control->add_responsive_control(
			$this->get_name_setting($name . '_offset_y'),
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
					'{{WRAPPER}} ' . $current_item_mark => 'bottom: initial ' . $_important . '; top: {{SIZE}}{{UNIT}} ' . $_important,
				],
				'condition'  => [
					$this->get_name_setting($name . '_offset_orientation_v') . '!' => 'end',
					$this->get_name_setting($name) . '[id]!' => '',
					$this->get_name_setting($name) . '_position' => 'absolute',
				],
			]
		);

		$control->add_responsive_control(
			$this->get_name_setting($name . '_offset_y_end'),
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
					'{{WRAPPER}} ' . $current_item_mark => 'top: initial ' . $_important . '; bottom: {{SIZE}}{{UNIT}} ' . $_important,
				],
				'condition'  => [
					$this->get_name_setting($name . '_offset_orientation_v') => 'end',
					$this->get_name_setting($name) . '[id]!' => '',
					$this->get_name_setting($name) . '_position' => 'absolute',
				],
			]
		);
	}

	/**
	 * URL group.
	 *
	 * @param mixed  $control Control.
	 * @param string $name Name.
	 * @return void
	 */
	public function link_group_control( $control, string $name ): void {
		$control->add_control(
			$this->get_name_setting($name . '_type'),
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
					$this->get_name_setting($name) . '!' => '',
				],
				'separator' => 'before',
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_url'),
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
					$this->get_name_setting($name) . '!' => '',
					$this->get_name_setting($name . '_type') => [ 'url' ],
				],
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_video'),
			[
				'label'       => __('Video', App::get_domain()),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => [ 'video' ],
				'condition'   => [
					$this->get_name_setting($name) . '!' => '',
					$this->get_name_setting($name . '_type') => 'video',
				],
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_youtube'),
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
					$this->get_name_setting($name) . '!' => '',
					$this->get_name_setting($name . '_type') => 'youtube',
				],
				'frontend_available' => true,
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_vimeo'),
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
					$this->get_name_setting($name) . '!' => '',
					$this->get_name_setting($name . '_type') => 'vimeo',
				],
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_dailymotion'),
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
					$this->get_name_setting($name) . '!' => '',
					$this->get_name_setting($name . '_type') => 'dailymotion',
				],
			]
		);
	}

	/**
	 * Icon Group.
	 *
	 * @param mixed  $control Control.
	 * @param string $name Name.
	 * @return void
	 */
	public function icon_group_control( $control, string $name ): void {

		$control->add_control(
			$this->get_name_setting($name . '_icon_status'),
			[
				'label'        => __('Show Icon', App::get_domain()),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __('Show/Hide icon', App::get_domain()),
				'label_on'     => __('Show', App::get_domain()),
				'label_off'    => __('Hide', App::get_domain()),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_icon_position'),
			[
				'label'       => __('Icon Position', App::get_domain()),
				'type'        => Controls_Manager::CHOOSE,
				'description' => __('Choose where to show the icon', App::get_domain()),
				'options'     => [
					'before' => [
						'title' => esc_html__('Before', App::get_domain()),
						'icon'  => 'eicon-h-align-left',
					],
					'after' => [
						'title' => esc_html__('After', App::get_domain()),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'     => 'after',
				'toggle'      => true,
				'condition'   => [
					$this->get_name_setting($name . '_icon_status') => 'yes',
				],
			]
		);

		$control->add_control(
			$this->get_name_setting($name . '_icon'),
			[
				'label'       => __('Icon', App::get_domain()),
				'type'        => Controls_Manager::ICONS,
				'description' => __('Choose or upload your custom icon to override the default icon', App::get_domain()),
				'condition'   => [
					$this->get_name_setting($name . '_icon_status') => 'yes',
				],
				'separator'   => 'after',
			]
		);
	}

	/**
	 * Border group.
	 *
	 * @param mixed  $control Control.
	 * @param string $name Name.
	 * @return void
	 */
	public function border_group_control( $control, string $name ): void {
		$current_item_mark = $this->get_current_item_mark($control);
		$control->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $this->get_name_setting($name . '_border'),
				'selector'       => '{{WRAPPER}} ' . $current_item_mark,
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

		$control->add_control(
			$this->get_name_setting($name . '_border_radius'),
			[
				'label'      => __('Border Radius', App::get_domain()),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} ' . $current_item_mark => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
}
