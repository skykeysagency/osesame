<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Frontend;

use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\Setting;
use RisingBambooCore\App\App;

/**
 * ShortCode Class.
 */
class ShortCode extends Singleton {

	/**
	 * Register shortcode.
	 *
	 * @return void
	 */
	public function register(): void {
        //phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.plugin_territory_add_shortcode
		add_shortcode('rbb_social_share', [ $this, 'social_share' ]);
		//phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.plugin_territory_add_shortcode
		add_shortcode('rbb_contact', [ $this, 'contact' ]);
	}

	/**
	 * Social share.
	 *
	 * @param mixed $atts Atts.
	 * @return string
	 */
	public function social_share( $atts ): string {
		global $post;
		$facebook  = (bool) Setting::get_option('share_facebook', true);
		$twitter   = (bool) Setting::get_option('share_twitter', true);
		$linkedin  = (bool) Setting::get_option('share_linkedin', true);
		$pinterest = (bool) Setting::get_option('share_pinterest', true);
		$tumblr    = (bool) Setting::get_option('share_tumblr', true);
		$email     = (bool) Setting::get_option('share_email', true);

		$default = [
			'popup' => 'enable',
		];
		$config  = shortcode_atts($default, $atts);

		$permalink = get_permalink($post->ID);
		$image     = esc_url(wp_get_attachment_url(get_post_thumbnail_id()));
		$title     = $post->post_title;

		ob_start();
		View::instance()->load('shortcode/social-share', compact('facebook', 'twitter', 'linkedin', 'pinterest', 'tumblr', 'email', 'config', 'permalink', 'image', 'title'));
		return ob_get_clean();
	}

	/**
	 * Get contact setting data.
	 *
	 * @param mixed $atts Attribute.
	 * @return string|null
	 */
	public function contact( $atts ): ?string {
		$type = $atts['type'] ?? 'phone';
		$icon = $atts['icon'] ?? null;
		$data = Setting::get_option('contact_' . $type, null);
		if ( $data && ! $icon ) {
			switch ( $type ) {
				case 'email':
					$icon = 'rbb-icon-email-4';
					break;
				case 'address':
					$icon = 'rbb-icon-home-filled-1';
					break;
				default:
					$icon = 'rbb-icon-phone-4';

			}
		}
		ob_start();
		View::instance()->load('shortcode/contact', compact('icon', 'type', 'data'));
		return ob_get_clean();
	}
}
