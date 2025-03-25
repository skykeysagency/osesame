<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets\Menu;

use Elementor\Controls_Manager;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;
use RisingBambooCore\Elementor\Widgets\Base;
use RisingBambooCore\Elementor\Widgets\Widget;
use RisingBambooCore\Helper\Helper;
use RisingBambooTheme\App\Menu\WalkerNavMenu;

/**
 * Elementor Products Widget.
 */
class Menu extends Base {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	protected string $prefix = 'rbb_menu';

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
		return __('Menu', App::get_domain());
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
		return [];
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
		$this->add_control(
			$this->get_name_setting('menu'),
			[
				'label'   => __('Menu', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'primary',
				'options' => $this->get_menus(),
			]
		);

		$this->add_control(
			$this->get_name_setting('depth'),
			[
				'label'       => __('Depth', App::get_domain()),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '0',
				'description' => __('How many levels of the hierarchy are to be included. 0 means all', App::get_domain()),
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
			$this->get_name_setting('general_layout'),
			[
				'label'   => __('Layout', App::get_domain()),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => Helper::list_templates('elementor/widgets/menu'),
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

		$class = 'menu-container';

		if ( is_rtl() ) {
			$class .= ' rtl-direction';
		}

		$args = [
			'container'      => 'ul',
			'menu_class'     => $class,
			'depth'          => (int) $this->get_value_setting('depth'),
			'menu'           => $this->get_value_setting('menu'),
		];

		if ( class_exists(WalkerNavMenu::class) ) {
			$args['walker'] = new WalkerNavMenu();
		}

		View::instance()->load(
			'elementor/widgets/menu/' . strtolower($layout),
			[
				'widget'              => $this,
				'id'                  => $this->uniqID(),
				'layout'              => strtolower($layout),
				'title'               => $this->get_value_setting('title'),
				'args'                => $args,
			]
		);
	}

	/**
	 * Get All Menu.
	 *
	 * @return array
	 */
	protected function get_menus(): array {
		$result = [];
		$menus  = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			$result[ $menu->slug ] = $menu->name;
		}
		return $result;
	}
}
