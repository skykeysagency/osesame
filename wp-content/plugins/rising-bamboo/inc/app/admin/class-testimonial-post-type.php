<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;

/**
 * Testimonial Post Type class.
 *
 * @package RisingBambooCore
 */
class TestimonialPostType extends Singleton {

	/**
	 * Post Type.
	 */
	public const POST_TYPE = 'rbb_testimonial';

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @return void
	 */
	protected function hooks(): void {
		add_action('init', [ $this, 'register_post_type' ]);
		add_action('admin_menu', [ $this, 'add_postype_to_rbb_core_menu' ]);
	}

	/**
	 * Register Mega_Menu Post Type
	 */
	public function register_post_type(): void {

		$labels = [
			'name'               => _x('Testimonials', 'Post Type General Name', App::get_domain()),
			'singular_name'      => _x('Testimonial', 'Post Type Singular Name', App::get_domain()),
			'menu_name'          => esc_html__('Testimonials', App::get_domain()),
			'name_admin_bar'     => esc_html__('Testimonials', App::get_domain()),
			'parent_item_colon'  => esc_html__('Parent Menu:', App::get_domain()),
			'all_items'          => esc_html__('Testimonials', App::get_domain()),
			'add_new_item'       => esc_html__('Add Testimonial', App::get_domain()),
			'add_new'            => esc_html__('Add New', App::get_domain()),
			'new_item'           => esc_html__('New Testimonial', App::get_domain()),
			'edit_item'          => esc_html__('Edit Testimonial', App::get_domain()),
			'update_item'        => esc_html__('Update Testimonial', App::get_domain()),
			'view_item'          => esc_html__('View Testimonial', App::get_domain()),
			'search_items'       => esc_html__('Search Testimonial', App::get_domain()),
			'not_found'          => esc_html__('Not found', App::get_domain()),
			'not_found_in_trash' => esc_html__('Not found in Trash', App::get_domain()),
		];

		$args = [
			'label'               => esc_html__('Testimonials', App::get_domain()),
			'description'         => esc_html__('Testimonials for Elementor', App::get_domain()),
			'labels'              => $labels,
			'supports'            => [
				'title',
				'excerpt',
				'editor',
				'thumbnail',
				'revisions',
			],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-format-status',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => 'testimonials',
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => true,
			'capability_type'     => 'page',
		];

		register_post_type(self::POST_TYPE, $args);
	}

	/**
	 * Add mega menu to rbb core menu.
	 *
	 * @return void
	 */
	public function add_postype_to_rbb_core_menu(): void {
		add_submenu_page(
			App::get_domain(),
			esc_html__('Testimonials', App::get_domain()),
			esc_html__('Testimonials', App::get_domain()),
			'manage_options',
			'edit.php?post_type=' . self::POST_TYPE
		);
	}
}
