<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Setting;
use RisingBambooCore\Kirki\Control\RbbIcons as RbbIconsControl;

/**
 * DevMode Class.
 */
class RbbIcons extends Singleton {
	/**
	 * List Icons.
	 *
	 * @var array
	 */
	protected static array $icons = [];

	/**
	 * Construct.
	 */
	public function __construct() {
		if ( Helper::kirki_activated() ) {
			add_action(
				'customize_register',
				[ $this, 'register' ]
			);
			self::$icons = $this->fetch();
		}
	}

	/**
	 * Register RBb Icons.
	 *
	 * @param mixed $wp_customize Customizer.
	 * @return void
	 */
	public function register( $wp_customize ): void {
		add_filter(
			'kirki_control_types',
			function ( $controls ) {
				$controls['rbb-icons'] = RbbIconsControl::class;
				return $controls;
			}
		);
		$wp_customize->register_control_type(RbbIconsControl::class);
	}

	/**
	 * Fetch Icons.
	 *
	 * @return array
	 */
	public function fetch(): array {
		$icons = [];
		$path  = realpath(RBB_CORE_ASSETS_PATH . 'scss/font/rbb-font/variables.scss');
		if ( $path ) {
			$wp_filesystem = new \WP_Filesystem_Direct(null);
			$content       = $wp_filesystem->get_contents_array($path);
			if ( $content ) {
				foreach ( $content as $line ) {
					if ( preg_match('/^\$rbb-icon/', $line) ) {
						$icon                = trim(str_replace('$rbb-icon-', '', $line));
						$icon_splice         = explode(':', $icon);
						$icon                = $icon_splice[0];
						$type                = explode('-', $icon);
						$icons[ $type[0] ][] = [
							'name'    => 'rbb-icon-' . $icon,
							'content' => trim($icon_splice[1], ' ;\''),
						];
					}
				}
			}
		} else {
			$path          = realpath(RBB_CORE_DIST_PATH . 'rbb-icons.json');
			$wp_filesystem = new \WP_Filesystem_Direct(null);
			$content       = $wp_filesystem->get_contents($path);
			if ( $content ) {
				$list = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
				foreach ( $list as  $icon_obj ) {
					if ( preg_match('/^\$rbb-icon/', $icon_obj->name) ) {
						$icon                = str_replace('$', '', $icon_obj->name);
						$type                = explode('-', $icon);
						$icons[ $type[2] ][] = [
							'name'    => $icon,
							'content' => '"' . $icon_obj->value . '"',
						];
					}
				}
			}
		}
		return $icons;
	}

	/**
	 * Get Rbb Icons.
	 *
	 * @return array
	 */
	public static function get_rbb_icons(): array {
		return self::$icons;
	}

	/**
	 * Get Rbb Icon Content.
	 *
	 * @param string $icon Icon.
	 * @return string
	 */
	public static function get_rbb_icon_content( string $icon ): string {
		$result = '';
		$group  = explode('-', $icon)[2];
		if ( isset(self::$icons[ $group ]) ) {
			$found_key = array_search($icon, array_column(self::$icons[ $group ], 'name'), true);
			if ( $found_key ) {
				$result = self::$icons[ $group ][ $found_key ]['content'];
			}
		}
		return $result;
	}
}
