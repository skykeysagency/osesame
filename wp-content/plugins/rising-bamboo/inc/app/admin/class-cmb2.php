<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;

/**
 * CMB2 Class.
 */
class CMB2 extends Singleton {

	/**
	 * INIT CMB2.
	 */
	public function __construct() {
		require_once RBB_CORE_INC_DIR . 'config/meta-fields.php';
		if ( file_exists(RBB_CORE_VENDOR_PATH . 'cmb2/cmb2/init.php') ) {
			require_once RBB_CORE_VENDOR_PATH . 'cmb2/cmb2/init.php';
		}
		$this->load_custom_field('select2/cmb-field-select2');
		$this->load_custom_field('tabs/cmb2-tabs');
		add_action('cmb2_admin_init', [ $this, 'page_metabox' ]);
	}

	/**
	 * Load custom field.
	 *
	 * @param string $name Field Name.
	 * @return void
	 */
	public function load_custom_field( string $name ): void {
		$path = RBB_CORE_INC_DIR . 'cmb2' . DIRECTORY_SEPARATOR . 'custom_fields' . DIRECTORY_SEPARATOR . $name . '.php';
		if ( file_exists($path) ) {
			require_once $path;
		}
	}

	/**
	 * Page meta box.
	 *
	 * @return void
	 */
	public function page_metabox(): void {
		$prefix = 'rbb_layout_';

		$cmb = new_cmb2_box(
			[
				'id'            => RBB_CORE_META_FIELD_LAYOUT_ID,
				'title'         => __('Options', App::get_domain()),
				'object_types'  => [ 'page', 'post' ], // Post type.
				'vertical_tabs' => true, // Set vertical tabs, default false.
				'tabs'          => [
					[
						'id'     => 'logo',
						'icon'   => 'rbb-icon-brand-rising-bamboo',
						'title'  => __('Logo', App::get_domain()),
						'fields' => [
							RBB_CORE_META_FIELD_LOGO_STATUS,
							RBB_CORE_META_FIELD_LOGO,
						],
					],
					[
						'id'     => 'title',
						'icon'   => 'rbb-icon-brand-rising-bamboo',
						'title'  => __('Title & Breadcrumb', App::get_domain()),
						'fields' => [
							RBB_CORE_META_FIELD_LAYOUT_TITLE,
							RBB_CORE_META_FIELD_LAYOUT_TITLE_COLOR,
							RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB,
							RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_COLOR,
							RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE,
							RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE_ENABLE,
						],
					],
					[
						'id'     => 'header',
						'icon'   => 'rbb-icon-brand-rising-bamboo',
						'title'  => __('Header', App::get_domain()),
						'fields' => [
							RBB_CORE_META_FIELD_LAYOUT_HEADER,
							RBB_CORE_META_FIELD_MENU_PRIMARY,
							RBB_CORE_META_FIELD_LAYOUT_HEADER_LOGIN_FORM,
							RBB_CORE_META_FIELD_LAYOUT_HEADER_SEARCH_FORM,
							RBB_CORE_META_FIELD_LAYOUT_HEADER_MINI_CART,
							RBB_CORE_META_FIELD_LAYOUT_HEADER_WISH_LIST,
						],
					],
					[
						'id'     => 'footer',
						'icon'   => 'rbb-icon-brand-rising-bamboo',
						'title'  => __('Footer', App::get_domain()),
						'fields' => [
							RBB_CORE_META_FIELD_LAYOUT_FOOTER,
						],
					],
					[
						'id'     => 'components',
						'icon'   => 'rbb-icon-brand-rising-bamboo',
						'title'  => __('Components', App::get_domain()),
						'fields' => [
							RBB_CORE_META_FIELD_COMPONENT_POST_NAVIGATION,
						],
					],
					[
						'id'     => 'custom_css',
						'icon'   => 'rbb-icon-brand-rising-bamboo',
						'title'  => __('Custom Style', App::get_domain()),
						'fields' => [
							RBB_CORE_META_FIELD_BODY_CUSTOM_CSS_CLASS,
						],
					],
				],
			]
		);

		/**
		 * Logo
		 */
		$cmb->add_field(
			[
				'name'             => __('Logo', App::get_domain()),
				'desc'             => __('Show/Hide the logo.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LOGO_STATUS,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		$cmb->add_field(
			[
				'name'             => __('Logo', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LOGO,
				'type'             => 'file',
				'options'          => [
					'url' => false, // Hide the text input for the url.
				],
				'text'             => [
					'add_upload_file_text' => __('Add File', App::get_domain()), // Change upload button text. Default: "Add or Upload File".
				],
				// query_args are passed to wp.media's library query.
				'query_args'       => [
					'type' => [
						'image/gif',
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size'     => 'large', // Image size to use when previewing in the admin.
			]
		);

		/**
		 * Page Title.
		 */
		$cmb->add_field(
			[
				'name'             => __('Page Title', App::get_domain()),
				'desc'             => __('Show/Hide the page title.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_TITLE,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		$cmb->add_field(
			[
				'name'       => __('Page Title Color', App::get_domain()),
				'id'         => RBB_CORE_META_FIELD_LAYOUT_TITLE_COLOR,
				'type'       => 'colorpicker',
				'options'    => [
					'alpha' => true, // Make this a rgba color picker.
				],
				'attributes' => [
					'data-colorpicker' => wp_json_encode(
						[
							'width' => '220',
						]
					),
				],
			]
		);

		/**
		 * Breadcrumb.
		 */
		$cmb->add_field(
			[
				'name'             => __('Breadcrumb', App::get_domain()),
				'desc'             => __('Show/Hide the breadcrumb.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		$cmb->add_field(
			[
				'name'       => __('Breadcrumb Color', App::get_domain()),
				'id'         => RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_COLOR,
				'type'       => 'colorpicker',
				'options'    => [
					'alpha' => true, // Make this a rgba color picker.
				],
				'attributes' => [
					'data-colorpicker' => wp_json_encode(
						[
							'width' => '220',
						]
					),
				],
			]
		);

		$cmb->add_field(
			[
				'name'             => __('Show Breadcrumb Background Image', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE_ENABLE,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		$cmb->add_field(
			[
				'name'             => __('Breadcrumb Background Image', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_IMAGE,
				'type'             => 'file',
				'options'          => [
					'url' => false, // Hide the text input for the url.
				],
				'text'             => [
					'add_upload_file_text' => __('Add File', App::get_domain()), // Change upload button text. Default: "Add or Upload File".
				],
				// query_args are passed to wp.media's library query.
				'query_args'       => [
					'type' => [
						'image/gif',
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size'     => 'large', // Image size to use when previewing in the admin.
			]
		);

		$cmb->add_field(
			[
				'name'       => __('Breadcrumb Background Color', App::get_domain()),
				'id'         => RBB_CORE_META_FIELD_LAYOUT_BREADCRUMB_BACKGROUND_COLOR,
				'type'       => 'colorpicker',
				'options'    => [
					'alpha' => true, // Make this a rgba color picker.
				],
				'attributes' => [
					'data-colorpicker' => wp_json_encode(
						[
							'width' => '220',
						]
					),
				],
			]
		);

		/**
		 * Header.
		 */
		$headers = apply_filters($prefix . 'header_list', []); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
		$cmb->add_field(
			[
				'name'             => __('Header', App::get_domain()),
				'desc'             => __('Select the header style.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_HEADER,
				'show_option_none' => true,
				'type'             => 'pw_select',
				'options'          => $headers,
			]
		);

		$menus = Helper::get_menus();
		$cmb->add_field(
			[
				'name'             => __('Primary Menu', App::get_domain()),
				'desc'             => __('Select a menu.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_MENU_PRIMARY,
				'show_option_none' => true,
				'type'             => 'pw_select',
				'options'          => $menus,
			]
		);

		$cmb->add_field(
			[
				'name'             => __('Login Form', App::get_domain()),
				'desc'             => __('Show/Hide the login form.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_HEADER_LOGIN_FORM,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		$cmb->add_field(
			[
				'name'             => __('Search Form', App::get_domain()),
				'desc'             => __('Show/Hide the search form.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_HEADER_SEARCH_FORM,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		$cmb->add_field(
			[
				'name'             => __('Mini Cart', App::get_domain()),
				'desc'             => __('Show/Hide the mini cart.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_HEADER_MINI_CART,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		$cmb->add_field(
			[
				'name'             => __('Wish List', App::get_domain()),
				'desc'             => __('Show/Hide the wish list.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_HEADER_WISH_LIST,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		/**
		 * Footer.
		 */

		$footers = apply_filters($prefix . 'footer_list', []); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
		$cmb->add_field(
			[
				'name'             => __('Footer', App::get_domain()),
				'desc'             => __('Select footer.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_LAYOUT_FOOTER,
				'show_option_none' => true,
				'type'             => 'pw_select',
				'options'          => $footers,
			]
		);

		/**
		 * Components.
		 */
		$cmb->add_field(
			[
				'name'             => __('Post Navigation', App::get_domain()),
				'desc'             => __('Show/Hide the post navigation.', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_COMPONENT_POST_NAVIGATION,
				'type'             => 'select',
				'show_option_none' => __('Using Customize Setting', App::get_domain()),
				'options'          => [
					'on'  => __('Show', App::get_domain()),
					'off' => __('Hide', App::get_domain()),
				],
			]
		);

		/**
		 * Custom Css Class.
		 */
		$cmb->add_field(
			[
				'name'             => __('Body Custom Class', App::get_domain()),
				'desc'             => __('Add a custom CSS class to the body. Separate each class with commas or semicolons or | .', App::get_domain()),
				'id'               => RBB_CORE_META_FIELD_BODY_CUSTOM_CSS_CLASS,
				'type'             => 'text',
			]
		);
	}
}
