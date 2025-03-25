<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Widgets;

use Elementor\Plugin;
use RisingBambooCore\App\App;
use RisingBambooCore\Elementor\Widgets\Banner\Banner;
use RisingBambooCore\Elementor\Widgets\Menu\Menu;
use RisingBambooCore\Elementor\Widgets\Posts\Posts;
use RisingBambooCore\Elementor\Widgets\Slider\Slider;
use RisingBambooCore\Elementor\Widgets\Testimonial\Testimonials;
use RisingBambooCore\Elementor\Widgets\WooProducts\Products;
use RisingBambooCore\Elementor\Widgets\WooProducts\Single;

/**
 * RisingBamboo Elementor Widget
 */
class Widget {

	/**
	 * Elementor Category.
	 */
	public const ELEMENTOR_CATEGORY = 'rising-bamboo';

	/**
	 * Register Elementor Category.
	 *
	 * @param mixed $elements_manager Elementor Manager.
	 */
	public static function widget_categories( $elements_manager ): void {
		$elements_manager->add_category(
			self::ELEMENTOR_CATEGORY,
			[
				'title' => __('Rising Bamboo', App::get_domain()),
				'icon'  => 'rbb-icon-rising-bamboo',
			]
		);
	}

	/**
	 * Register Widgets.
	 *
	 * @return void
	 */
	public static function register(): void {
		Plugin::instance()->widgets_manager->register(new Slider());
		Plugin::instance()->widgets_manager->register(new Products());
		Plugin::instance()->widgets_manager->register(new Single());
		Plugin::instance()->widgets_manager->register(new Banner());
		Plugin::instance()->widgets_manager->register(new Testimonials());
		Plugin::instance()->widgets_manager->register(new Posts());
		Plugin::instance()->widgets_manager->register(new Menu());
	}
}
