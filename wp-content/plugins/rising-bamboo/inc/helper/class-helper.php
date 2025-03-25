<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\Helper;

use MailPoetVendor\Monolog\DateTimeImmutable;
use RisingBambooCore\App\App;
use WooCommerce;
use WPCleverWoosc;
use WPCleverWoosw;

/**
 * Include file function when list_files not exists.
 */
if ( ! function_exists('list_files') ) {
	//phpcs:ignore
	require_once ABSPATH . '/wp-admin/includes/file.php';
}

/**
 * Include formatting functions
 */
if ( ! function_exists('wp_basename') ) {
	//phpcs:ignore
	require_once ABSPATH . WPINC . '/formatting.php';
}

if ( ! class_exists('WP_Filesystem_Direct') ) {
	//phpcs:ignore
	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
	//phpcs:ignore
	require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
}

/**
 * Helper Class.
 */
class Helper {

	/**
	 * Check Kirki is Activated.
	 *
	 * @return bool
	 */
	public static function kirki_activated(): bool {
		return class_exists('Kirki');
	}

	/**
	 * Check elementor is activated.
	 *
	 * @return int|null
	 */
	public static function elementor_activated(): ?int {
		return did_action('elementor/loaded');
	}

	/**
	 * Check Jetpack is activated.
	 *
	 * @return bool
	 */
	public static function jetpack_activated(): bool {
		return defined('JETPACK__VERSION');
	}

	/**
	 * Check Woocommerce is activated.
	 *
	 * @return bool
	 */
	public static function woocommerce_activated(): bool {
		return class_exists(WooCommerce::class);
	}

	/**
	 * Check Wishlist is activated.
	 *
	 * @return bool
	 */
	public static function woocommerce_wishlist_activated(): bool {
		return class_exists(WPCleverWoosw::class);
	}

	/**
	 * Check Compare is activated.
	 *
	 * @return bool
	 */
	public static function woocommerce_compare_activated(): bool {
		return class_exists(WPCleverWoosc::class);
	}

	/**
	 * Get All menu.
	 *
	 * @return array
	 */
	public static function get_menus(): array {
		$return = [];
		$menus  = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			$return[ $menu->term_id ] = $menu->name;
		}
		return $return;
	}

	/**
	 * Flat taxonomy hierarchy.
	 *
	 * @param mixed  $taxonomy Taxonomy.
	 * @param int    $parent   Parent.
	 * @param array  $args     Args.
	 * @param string $pad      Pad.
	 * @param array  $return   Return.
	 * @param array  $list     List.
	 * @param int    $level    level.
	 * @return array
	 */
	public static function get_flat_taxonomy_hierarchy( $taxonomy, int $parent = 0, array $args = [], string $pad = ' ', array $return = [], array &$list = [], int $level = 0 ): array {

		$taxonomy         = is_array($taxonomy) ? array_shift($taxonomy) : $taxonomy;
		$args['parent']   = $parent;
		$args['taxonomy'] = $taxonomy;
		$_args            = wp_parse_args(
			$args,
			[
				'orderby'       => 'name',
			]
		);

		$terms = get_terms($_args);

		$_return = wp_parse_args(
			$return,
			[
				'key'   => 'slug',
				'value' => 'name',
			]
		);

		foreach ( $terms as $term ) {

			if ( 'object' === $_return['value'] ) {
				$_val = $term;
				if ( ! empty($pad) ) {
					$_val->name = str_pad($term->name, mb_strlen($term->name) + $level, $pad, STR_PAD_LEFT);
				}
			} elseif ( ! empty($pad) ) {
				$_val = str_pad($term->name, mb_strlen($term->name) + $level, $pad, STR_PAD_LEFT);
			} else {
				$_val = $term->name;
			}

			if ( isset($term->{$_return['key']}) && in_array($_return['key'], [ 'slug', 'term_id' ], true) ) {
				$list[ $term->{$_return['key']} ] = $_val;
			} else {
				$list[] = $_val;
			}

			if ( get_terms(
				wp_parse_args(
					[
						'parent'   => $term->term_id,
					],
					$_args
				)
			) ) {
				self::get_flat_taxonomy_hierarchy($taxonomy, $term->term_id, $args, $pad, $return, $list, $level + 1);
			}
		}
		return $list;
	}

	/**
	 * Get taxonomy hierarchy.
	 *
	 * @param mixed $taxonomy Taxonomy.
	 * @param int   $parent Parent.
	 * @param array $args Args.
	 * @return array
	 */
	public static function get_taxonomy_hierarchy( $taxonomy, int $parent = 0, array $args = [] ): array {

		$taxonomy = is_array($taxonomy) ? array_shift($taxonomy) : $taxonomy;

		$_args = wp_parse_args(
			$args,
			[
				'taxonomy'      => $taxonomy,
				'parent'        => $parent,
				'orderby'       => 'name',
			]
		);

		$terms = get_terms($_args);

		$children = [];

		foreach ( $terms as $term ) {

			$term->children = self::get_taxonomy_hierarchy($taxonomy, $term->term_id, $args);

			$children[ $term->term_id ] = $term;
		}

		return $children;
	}

	/**
	 * Get List Template files in theme & plugin.
	 *
	 * @param string $path Path.
	 * @return array
	 */
	public static function list_templates( string $path = '' ): array {
		$theme_path  = realpath(get_template_directory() . DIRECTORY_SEPARATOR . 'template-parts' . DIRECTORY_SEPARATOR . $path);
		$plugin_path = realpath(RBB_CORE_VIEW_DIR . 'frontend' . DIRECTORY_SEPARATOR . $path);
		$theme_files = [];
		if ( $theme_path ) {
			$theme_files = self::get_files_assoc($theme_path, '.php', 1);
		}
		$plugin_files = [];
		if ( $plugin_path ) {
			$plugin_files = self::get_files_assoc($plugin_path, '.php', 1);
		}
		return wp_parse_args($theme_files, $plugin_files);
	}

	/**
	 * Format files as assoc array.
	 *
	 * @param string $path The Path of folder.
	 * @param string $suffix The suffix of file.
	 * @param int    $level Level.
	 * @param array  $exclusions Exclusions.
	 * @return array
	 */
	public static function get_files_assoc( string $path, string $suffix = '.php', int $level = 100, array $exclusions = [] ): array {
		$files = self::get_files($path, $suffix, $level, $exclusions);
		return self::format_assoc($files);
	}

	/**
	 * Get files.
	 *
	 * @param string $path Path of folder.
	 * @param string $suffix Suffix of file.
	 * @param int    $level Level.
	 * @param array  $exclusions Exclusions.
	 * @return array|string[]
	 */
	public static function get_files( string $path = '', string $suffix = '.php', int $level = 100, array $exclusions = [] ): ?array {
		$result = [];
		$files  = list_files($path, $level, $exclusions);
		foreach ( $files as $k => $file ) {
			if ( is_file($file) ) {
				$result[ $k ] = wp_basename($file, $suffix);
			}
		}
		return $result;
	}

	/**
	 * Format to Assoc Array.
	 *
	 * @param array $arr The array.
	 * @return array
	 */
	public static function format_assoc( array $arr ): array {
		$return = [];
		foreach ( $arr as $item ) {
			$return[ $item ] = esc_html__(ucwords(str_replace('-', ' ', $item)), App::get_domain()); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
		}
		return $return;
	}

	/**
	 * Get Youtube Embed link.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	public static function get_youtube_embed( string $url ): string {
		$short_url_regex = '/youtu.be\/(\w+)\??/i';
		$long_url_regex  = '/youtube.com\/(embed|watch)(\?v=|\/)(\w+)/i';
		$youtube_id      = null;
		if ( preg_match($long_url_regex, $url, $matches) ) {
			$youtube_id = $matches[ count($matches) - 1 ];
		}

		if ( preg_match($short_url_regex, $url, $matches) ) {
			$youtube_id = $matches[ count($matches) - 1 ];
		}
		return 'https://www.youtube.com/embed/' . $youtube_id;
	}

	/**
	 * Vimeo embed.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	public static function get_vimeo_embed( string $url ): string {
		$pattern = '/^.*vimeo\.com\/(?:[a-z]*\/)*([‌​\d]{6,11})[?]?.*/u';
		preg_match($pattern, $url, $matches);
		return 'https://player.vimeo.com/video/' . $matches[1];
	}

	/**
	 * Dailymotion embed.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	public static function get_dailymotion_embed( string $url ): string {
		$pattern = '/^.*dailymotion.com\/(?:video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/';
		preg_match($pattern, $url, $matches);
		return 'https://www.dailymotion.com/embed/video/' . $matches[1];
	}

	/**
	 * Register js & css fallback.
	 *
	 * @param string           $handle Handle name.
	 * @param string           $src Src.
	 * @param array | string   $deps Dependency.
	 * @param string | boolean $ver Version.
	 * @param bool             $in_footer Put to footer only for Js.
	 * @return void
	 */
	public static function register_asset( string $handle, string $src, $deps = [], $ver = false, bool $in_footer = true ): void {
		$ext = pathinfo($src, PATHINFO_EXTENSION);
		if ( file_exists(realpath(get_template_directory() . '/dist/' . trim($src, '/'))) ) {
			$src = get_template_directory_uri() . '/dist/' . trim($src, '/');
		} else {
			$src = RBB_CORE_DIST_URL . trim($src, '/');
		}
		if ( strtolower($ext) === 'js' ) {
			wp_register_script($handle, $src, $deps, $ver, $in_footer);
		} elseif ( strtolower($ext) === 'css' ) {
			wp_register_style($handle, $src, $deps, $ver);
		}
	}

	/**
	 * Round up time to.
	 *
	 * @param mixed $time Time.
	 * @param mixed $round_up_to Round up to.
	 * @return false|string|null
	 */
	public static function round_up_time( $time, $round_up_to = false ) {
		$round_up_to = ( false !== $round_up_to ) ? $round_up_to : (int) Setting::get_option('woocommerce_shipping_estimate_time_round', 30);
		if ( $time instanceof DateTimeImmutable ) {
			$time = $time->getTimestamp();
		} else {
			$time = strtotime($time);
		}
		if ( $time ) {
			return gmdate('H:i:s', ceil($time / ( 60 * $round_up_to )) * ( 60 * $round_up_to ));
		}
		return null;
	}

	/**
	 * Get Query String.
	 *
	 * @return array|string|string[]
	 */
	public static function get_query_string() {
		global $wp_rewrite;
		$request  = remove_query_arg([ 'paged', 'page' ]);
		$home_url = wp_parse_url(esc_url(home_url()));
		$home_url = $home_url['path'] ?? '';
		$home_url = preg_quote($home_url, '|');
		$request  = preg_replace('|^' . $home_url . '|i', '', $request);
		$request  = preg_replace("|$wp_rewrite->pagination_base/\d+/?$|", '', $request);
		$request  = preg_replace('|^' . preg_quote($wp_rewrite->index, '|') . '|i', '', $request);
		$request  = ltrim($request, '/');
		$regex    = '|\?.*?$|';
		preg_match($regex, $request, $qs_match);
		if ( ! empty($qs_match[0]) ) {
			$query_string = $qs_match[0];
			$query_string = str_replace('?', '', $query_string);
		} else {
			$query_string = '';
		}
		return $query_string;
	}

	/**
	 * Get current taxonomy
	 *
	 * @return array|int|mixed|string
	 */
	public static function get_current_taxonomy() {
		return is_tax() ? get_queried_object()->taxonomy : '';
	}

	/**
	 * Get image placeholder.
	 *
	 * @return string
	 */
	public static function get_image_placeholder(): string {
		$placeholder_part = 'images' . DIRECTORY_SEPARATOR . 'placeholders' . DIRECTORY_SEPARATOR . 'brand.png';
		$placeholder      = RBB_CORE_DIST_URL . $placeholder_part;
		if ( file_exists(realpath(RBB_THEME_DIST_PATH . $placeholder_part)) ) {
			$placeholder = RBB_THEME_DIST_URI . $placeholder_part;
		}
		return $placeholder;
	}
}
