<?php
/**
 * RisingBambooTheme Package.
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\Customizer;

use RisingBambooCore\App\Admin\FooterPostType;
use RisingBambooCore\Helper\Helper as CoreHelper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirKi;
use RisingBambooTheme\Helper\Setting;

/**
 * Bamboo Customizer Helper.
 */
class Helper {

	/**
	 * Rising_Bamboo_Customizer_Helper constructor.
	 */
	public function __construct() {
	}

	/**
	 * Format files as assoc array.
	 *
	 * @param string $path The Path of folder.
	 * @param int    $level Level.
	 * @param string $suffix The suffix of file.
	 * @return array
	 */
	public static function get_files_assoc( string $path, int $level = 1, string $suffix = '.php' ): array {
		if ( method_exists(CoreHelper::class, 'get_files_assoc') ) {
			return CoreHelper::get_files_assoc($path, $suffix, $level);
		}
		return [];
	}

	/**
	 * List of elementor footers.
	 *
	 * @return array
	 */
	public static function get_elementor_footers(): array {
		$result = [];
		if ( class_exists(FooterPostType::class) ) {
			$footers = get_posts(
				[
					'post_type'   => FooterPostType::POST_TYPE,
					'order'       => 'ASC',
					'orderby'     => 'name',
					'numberposts' => -1, // phpcs:ignore WPThemeReview.CoreFunctionality.PostsPerPage.posts_per_page_numberposts
				]
			);
			foreach ( $footers as $footer ) {
				$result[ $footer->ID ] = $footer->post_title;
			}
		}
		return $result;
	}

	/**
	 * Get default value.
	 *
	 * @param string $setting Setting.
	 * @return mixed|null
	 */
	public static function get_default( string $setting ) {
		$rising_bamboo_default_fields = require RBB_THEME_INC_PATH . 'config/theme-customizer-default.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		return isset($rising_bamboo_default_fields[ $setting ]) ? $rising_bamboo_default_fields[ $setting ]['default'] : null;
	}

	/**
	 * Get value of multicolor type of Kirki.
	 * Problem when change one of values in customizer (link or hover) then it only return changed value (missing other).
	 *
	 * @param string $key Key.
	 * @param string $index Index.
	 * @return mixed|null
	 */
	public static function get_multi_color( string $key, string $index ) {
		$value   = Setting::get($key);
		$default = self::get_default($key);
		$args    = wp_parse_args($value, $default);
		return $args[ $index ] ?? null;
	}
}
