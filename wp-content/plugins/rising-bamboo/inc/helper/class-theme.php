<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\Helper;

use RisingBambooCore\Core\Singleton;

/**
 * Theme Helper Class.
 */
class Theme extends Singleton {

	/**
	 * WordPress Theme Obj.
	 *
	 * @var \WP_Theme
	 */
	protected \WP_Theme $theme;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->theme = wp_get_theme();
	}

	/**
	 * Check theme belong to RisingBamboo.
	 *
	 * @param \WP_Theme $theme WP Theme Object.
	 * @return bool
	 */
	public function is_rbb_theme( \WP_Theme $theme ): bool {
		$flag = false;
		if ( stripos($theme->get('AuthorURI'), 'risingbamboo') || stripos($theme->get('Author'), 'Rising Bamboo') ) {
			$flag = true;
		}
		return $flag;
	}

	/**
	 * Get all theme belong to Rising Bamboo.
	 *
	 * @return array
	 */
	public function get_themes(): array {
		$results = [];
		$themes  = wp_get_themes();
		foreach ( $themes as $stylesheet => $theme ) {
			if ( $this->is_rbb_theme($theme) ) {
				$results[ $stylesheet ] = $theme;
			}
		}
		return $results;
	}

	/**
	 * Get Theme version.
	 *
	 * @return array|false|string
	 */
	public function get_version() {
		return $this->theme->get('Version');
	}

	/**
	 * Get slug of them same with merlin
	 *
	 * @param \WP_Theme $theme Theme.
	 * @return string
	 */
	public static function get_theme_slug_merlin( \WP_Theme $theme ): string {
		return strtolower(preg_replace('#[^a-zA-Z]#', '', $theme->template));
	}
}
