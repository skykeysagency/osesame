<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use Elementor\Modules\PageTemplates\Module as ElementorPageTemplates;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;

/**
 * Mega menu class.
 *
 * @package Rising_Bamboo
 */
class FooterPostType extends Singleton {


	/**
	 * Post Type.
	 */
	public const POST_TYPE = 'rbb_footer';

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
		add_action('init', [ $this, 'make_elementor_as_default_for_footer' ]);
		add_action('admin_menu', [ $this, 'add_postype_to_rbb_core_menu' ]);
	}

	/**
	 * Register Mega_Menu Post Type
	 */
	public function register_post_type(): void {

		$labels = [
			'name'               => _x('Footers', 'Post Type General Name', App::get_domain()),
			'singular_name'      => _x('Footers', 'Post Type Singular Name', App::get_domain()),
			'menu_name'          => esc_html__('Footers', App::get_domain()),
			'name_admin_bar'     => esc_html__('Footers', App::get_domain()),
			'parent_item_colon'  => esc_html__('Parent Menu:', App::get_domain()),
			'all_items'          => esc_html__('Footers', App::get_domain()),
			'add_new_item'       => esc_html__('Add Footer', App::get_domain()),
			'add_new'            => esc_html__('Add New', App::get_domain()),
			'new_item'           => esc_html__('New Footer', App::get_domain()),
			'edit_item'          => esc_html__('Edit Footer', App::get_domain()),
			'update_item'        => esc_html__('Update Footer', App::get_domain()),
			'view_item'          => esc_html__('View Footer', App::get_domain()),
			'search_items'       => esc_html__('Search Footer', App::get_domain()),
			'not_found'          => esc_html__('Not found', App::get_domain()),
			'not_found_in_trash' => esc_html__('Not found in Trash', App::get_domain()),
		];

		$args = [
			'label'               => esc_html__('Footers', App::get_domain()),
			'description'         => esc_html__('Footer for Elementor', App::get_domain()),
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
			'menu_icon'           => 'dashicons-table-row-before',
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
	public function add_postype_to_rbb_core_menu(): void {
		add_submenu_page(
			App::get_domain(),
			esc_html__('Footers', App::get_domain()),
			esc_html__('Footers', App::get_domain()),
			'manage_options',
			'edit.php?post_type=' . self::POST_TYPE
		);
	}

	/**
	 * Make elementor as default editor for footer post type.
	 *
	 * @return void
	 */
	public function make_elementor_as_default_for_footer(): void {
		if ( Helper::elementor_activated() ) {
			add_action('add_meta_boxes', [ $this, 'set_default_page_template_meta_box' ]);
			//phpcs:disable
			/** Replace hyperlink in post titles on Page, Post, or Template lists with Elementor's editor link */
			// add_filter('get_edit_post_link', [ $this, 'make_elementor_default_edit_link' ], 10, 3);
			// add_filter('post_row_actions', [ $this, 'add_back_default_edit_link' ], 10, 2);
			// add_filter('post_row_actions', [ $this, 'remove_default_edit_with_elementor' ], 10, 2);
			//phpcs:enable
		}
	}

	/**
	 * Make elementor as default edit link.
	 *
	 * @param mixed $link Link.
	 * @param mixed $post_id Post ID.
	 * @param mixed $context Context.
	 * @return mixed|string|void|null
	 */
	public function make_elementor_default_edit_link( $link, $post_id, $context ) {
		if ( function_exists('get_current_screen') && is_admin() ) {
			try {
				$screen = get_current_screen();
				if ( is_object($screen) ) {
					$post_types_for_elementor = [
						self::POST_TYPE,
					];
					if ( 'display' === $context && in_array($screen->post_type, $post_types_for_elementor, true) ) {
						$link = admin_url('post.php?post=' . $post_id . '&action=elementor');
					}
					return $link;
				}
			} catch ( \Exception $e ) {
				echo wp_kses($e->getMessage(), 'rbb-kses');
			}
		}
	}

	/**
	 * Set Default template for footer.
	 *
	 * @return void
	 */
	public function set_default_page_template_meta_box(): void {
		global $post;
		$post_type = get_post_type($post);
		if ( self::POST_TYPE === $post_type && '' === $post->page_template && (int) get_option('page_for_posts') !== $post->ID && 0 !== count(get_page_templates($post))
		) {
			$post->page_template = ElementorPageTemplates::TEMPLATE_HEADER_FOOTER ?? ElementorPageTemplates::TEMPLATE_CANVAS;
		}
	}

	/**
	 * Add back to WordPress default editor.
	 *
	 * @param mixed $actions Action.
	 * @param mixed $post Post.
	 * @return mixed
	 */
	public function add_back_default_edit_link( $actions, $post ) {
		$url       = admin_url('post.php?post=' . $post->ID . '&action=edit');
		$post_type = get_post_type($post);
		if ( self::POST_TYPE === $post_type ) {
			$actions['edit'] =
				sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url($url),
					esc_html(__('WordPress Editor', App::get_domain()))
				);
		}

		return $actions;
	}

	/**
	 * Remove edit with elementor.
	 *
	 * @param mixed $actions Action.
	 * @param mixed $post Post.
	 * @return mixed
	 */
	public function remove_default_edit_with_elementor( $actions, $post ) {
		$post_type = get_post_type($post);
		if ( self::POST_TYPE === $post_type ) {
			unset($actions['edit_with_elementor']);
		}
		return $actions;
	}
}
