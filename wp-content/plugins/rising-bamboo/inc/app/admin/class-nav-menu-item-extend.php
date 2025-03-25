<?php
/**
 * RisingBambooTheme.
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;
use RisingBambooTheme\App\Menu\NavMenuItem;

/**
 * Adding Custom Fields to WordPress Menu Items.
 */
class NavMenuItemExtend extends Singleton {

	/**
	 * Meta key.
	 */
	public const ICON_META_KEY  = '_menu_item_icon_class';
	public const IMAGE_META_KEY = '_menu_item_image';

	/**
	 * Construction.
	 */
	public function __construct() {

		if ( class_exists(NavMenuItem::class) ) {
			return;
		}

		add_action('wp_nav_menu_item_custom_fields', [ $this, 'add' ], 10, 5);

		add_action('wp_update_nav_menu_item', [ $this, 'update' ], 10, 3);

		add_filter('wp_setup_nav_menu_item', [ $this, 'setup' ]);

		add_action('admin_enqueue_scripts', [ $this, 'admin_scripts' ]);
	}

	/**
	 * Add input field to add icon class.
	 *
	 * @param int       $item_id Menu item ID.
	 * @param \WP_Post  $item Menu item data object.
	 * @param int       $depth Depth of menu item. Used for padding.
	 * @param \stdClass $args An object of menu item arguments.
	 * @param int       $id the Navigation Menu ID.
	 */
	public function add( int $item_id, \WP_Post $item, int $depth, \stdClass $args, int $id ): void {
		?>
		<p class="description description-wide">
			<label for="edit-menu-item-icon-class-<?php echo esc_attr($item_id); ?>">
				<?php esc_html_e('Icon Class', App::get_domain()); ?><br/>
				<input type="text" id="edit-menu-item-icon-class-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-icon-class" name="menu-item-icon-class[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->icon_class); ?>"/>
			</label>
		</p>
		<div class="description description-wide" style="margin: 10px 0">
			<div class="rbb-media-wrap">
				<div style="float: left; margin-right: 10px;" class="rbb-media-image">
					<?php
					if ( $item->image ) {
						echo wp_get_attachment_image($item->image, [ 60 ]);
					} else {
						?>
					<img alt="thumbnail" src="<?php echo esc_url(Helper::get_image_placeholder()); ?>" width="60px" height="60px" data-src-placeholder="<?php echo esc_attr(Helper::get_image_placeholder()); ?>" />
						<?php
					}
					?>
				</div>
				<div style="line-height: 60px;">
					<input type="hidden" class="rbb-media-input" value="<?php echo esc_attr($item->image ?? ''); ?>" name="menu-item-image[<?php echo esc_attr($item_id); ?>]"/>
					<button type="button"
							style="margin-top:15px"
							class="rbb-media-upload button"><?php esc_html_e('Upload/Add image', App::get_domain()); ?></button>
					<button type="button"
							style="margin-top:15px; margin-left:10px"
							class="rbb-media-remove button"><?php esc_html_e('Remove image', App::get_domain()); ?></button>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Update.
	 *
	 * @param mixed $menu_id Menu ID.
	 * @param mixed $menu_item_db_id Menu ID in DB.
	 * @param mixed $args Args.
	 * @return void
	 */
	public function update( $menu_id, $menu_item_db_id, $args ): void {
		// Icon.
		if ( isset($_REQUEST['menu-item-icon-class']) && is_array($_REQUEST['menu-item-icon-class']) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset($_REQUEST['menu-item-icon-class'][ $menu_item_db_id ]) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$new_value = sanitize_text_field(wp_unslash($_REQUEST['menu-item-icon-class'][ $menu_item_db_id ])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				update_post_meta($menu_item_db_id, self::ICON_META_KEY, $new_value);
			}
		} else {
			update_post_meta($menu_item_db_id, self::ICON_META_KEY, '');
		}
		// Image.
		if ( isset($_REQUEST['menu-item-image']) && is_array($_REQUEST['menu-item-image']) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset($_REQUEST['menu-item-image'][ $menu_item_db_id ]) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$new_value = sanitize_text_field(wp_unslash($_REQUEST['menu-item-image'][ $menu_item_db_id ])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				update_post_meta($menu_item_db_id, self::IMAGE_META_KEY, $new_value);
			}
		} else {
			update_post_meta($menu_item_db_id, self::ICON_META_KEY, '');
		}
	}

	/**
	 * Setup
	 *
	 * @param mixed $menu_item Menu Item.
	 * @return mixed
	 */
	public function setup( $menu_item ) {
		$icon_class = get_post_meta($menu_item->ID, self::ICON_META_KEY, true) ?? '';
		$image      = get_post_meta($menu_item->ID, self::IMAGE_META_KEY, true) ?? '';

		$menu_item->icon_class = $icon_class;
		$menu_item->image      = $image;

		return $menu_item;
	}

	/**
	 * Add script to admin.
	 *
	 * @return void
	 */
	public function admin_scripts(): void {
		$screen = get_current_screen();
		if ( $screen && 'nav-menus' === $screen->id ) {
			wp_enqueue_media();
			wp_enqueue_script('rbb-media-upload');
		}
	}
}
