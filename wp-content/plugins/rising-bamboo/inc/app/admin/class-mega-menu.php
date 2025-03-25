<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\Core\Singleton;

/**
 * Mega menu class.
 *
 * @package Rising_Bamboo
 */
class MegaMenu extends Singleton {

	/**
	 * Post Type.
	 */
	public const POST_TYPE = 'rbb_mega_menu';

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
		add_action('init', [ $this, 'register_mega_menu' ]);
		add_action('admin_menu', [ $this, 'add_mega_menu_to_rbb_core_menu' ]);
	}

	/**
	 * Register Mega_Menu Post Type
	 */
	public function register_mega_menu(): void {

		$labels = [
			'name'               => _x('Mega Menus', 'Post Type General Name', 'rbb-core'),
			'singular_name'      => _x('Mega Menu', 'Post Type Singular Name', 'rbb-core'),
			'menu_name'          => esc_html__('Mega Menu', 'rbb-core'),
			'name_admin_bar'     => esc_html__('Mega Menu', 'rbb-core'),
			'parent_item_colon'  => esc_html__('Parent Menu:', 'rbb-core'),
			'all_items'          => esc_html__('Mega Menu', 'rbb-core'),
			'add_new_item'       => esc_html__('Add New Menu', 'rbb-core'),
			'add_new'            => esc_html__('Add New', 'rbb-core'),
			'new_item'           => esc_html__('New Menu', 'rbb-core'),
			'edit_item'          => esc_html__('Edit Menu', 'rbb-core'),
			'update_item'        => esc_html__('Update Menu', 'rbb-core'),
			'view_item'          => esc_html__('View Menu', 'rbb-core'),
			'search_items'       => esc_html__('Search Menu', 'rbb-core'),
			'not_found'          => esc_html__('Not found', 'rbb-core'),
			'not_found_in_trash' => esc_html__('Not found in Trash', 'rbb-core'),
		];

		$args = [
			'label'               => esc_html__('Mega Menus', 'rbb-core'),
			'description'         => esc_html__('Mega Menu For Rising Bamboo', 'rbb-core'),
			'labels'              => $labels,
			'supports'            => [
				'title',
				'editor',
				'revisions',
			],
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-menu-alt2',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'page',
		];

		register_post_type(self::POST_TYPE, $args);
	}

	/**
	 * Add mega menu to rbb core menu.
	 *
	 * @return void
	 */
	public function add_mega_menu_to_rbb_core_menu(): void {
		add_submenu_page(
			'rbb-core',
			esc_html__('Mega Menu', 'rbb-core'),
			esc_html__('Mega Menu', 'rbb-core'),
			'manage_options',
			'edit.php?post_type=' . self::POST_TYPE
		);
	}
}
