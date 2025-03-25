<?php
/**
 * RisingBamboo Package
 *
 * @package RisingBamboo
 */

namespace RisingBambooTheme\Elementor;

use Elementor\Plugin;
use RisingBambooCore\App\Admin\FooterPostType;
use RisingBambooCore\App\Admin\MegaMenu;
use RisingBambooCore\App\Admin\TestimonialPostType;
use RisingBambooTheme\Core\Singleton;
use RisingBambooTheme\Helper\Helper;

/**
 * Elementor Location
 * https://developers.elementor.com/theme-locations-api/registering-locations/
 *
 * @package rising_bamboo
 */
class Elementor extends Singleton {


	/**
	 * Construction.
	 */
	public function __construct() {
		if ( Helper::elementor_activated() ) {
			add_action('elementor/theme/register_locations', [ $this, 'register_elementor_locations' ]);
			add_filter('rising_bamboo_page_title', [ $this, 'check_hide_title' ]);
			if ( current_user_can('manage_options') ) {
				add_action('elementor/init', [ $this, 'update_default_settings' ]);
				add_action('after_switch_theme', [ $this, 'add_cpt_support' ]);
				add_action('merlin_after_all_import', [ $this, 'add_cpt_support' ]);
			}
		}
	}

	/**
	 * Register Elementor Locations.
	 *
	 * @param mixed $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	public function register_elementor_locations( $elementor_theme_manager ): void {
		$elementor_theme_manager->register_all_core_location();
	}

	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	public function check_hide_title( bool $val ): bool {
		if ( defined('ELEMENTOR_VERSION') ) {
			$current_doc = Plugin::instance()->documents->get(get_the_ID());
			if ( $current_doc && 'yes' === $current_doc->get_settings('hide_title') ) {
				$val = false;
			}
		}
		return $val;
	}

	/**
	 * Disable default global setting of elementor.
	 *
	 * @return void
	 */
	public function update_default_settings(): void {
		$kit = Plugin::$instance->kits_manager->get_active_kit();
		$kit->update_settings(
			[
				'global_image_lightbox' => 0,
			]
		);
		update_option('elementor_disable_color_schemes', 'yes');
		update_option('elementor_disable_typography_schemes', 'yes');
	}

	/**
	 * Add elementor support for custom post type.
	 *
	 * @return void
	 */
	public function add_cpt_support(): void {
		$cpt_support = get_option('elementor_cpt_support');
		if ( ! $cpt_support ) {
			// Create array of our default supported post types.
			$cpt_support = [
				'page',
				'post',
			];
		}
		if ( class_exists(FooterPostType::class) && ! in_array(FooterPostType::POST_TYPE, $cpt_support, true) ) {
			$cpt_support[] = FooterPostType::POST_TYPE;
		}
		if ( class_exists(MegaMenu::class) && ! in_array(MegaMenu::POST_TYPE, $cpt_support, true) ) {
			$cpt_support[] = MegaMenu::POST_TYPE;
		}
		if ( class_exists(TestimonialPostType::class) && ! in_array(TestimonialPostType::POST_TYPE, $cpt_support, true) ) {
			$cpt_support[] = TestimonialPostType::POST_TYPE;
		}
		update_option('elementor_cpt_support', $cpt_support);
	}
}
