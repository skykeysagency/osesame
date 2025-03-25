<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme;

use RisingBambooTheme\Core\Singleton;
use RisingBambooTheme\Helper\Helper;

/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package Rising_Bamboo
 */
class Jetpack extends Singleton {

	/**
	 * Construction
	 */
	public function __construct() {
		if ( Helper::jetpack_activated() ) {
			add_action('after_setup_theme', [ $this, 'jetpack_setup' ]);
		}
	}

	/**
	 * Jetpack setup function.
	 *
	 * See: https://jetpack.com/support/infinite-scroll/
	 * See: https://jetpack.com/support/responsive-videos/
	 * See: https://jetpack.com/support/content-options/
	 */
	public function jetpack_setup(): void {
		// Add theme support for Infinite Scroll.
		add_theme_support(
			'infinite-scroll',
			[
				'container' => 'main',
				'render'    => [ $this, 'infinite_scroll_render' ],
				'footer'    => 'page',
			]
		);

		// Add theme support for Responsive Videos.
		add_theme_support('jetpack-responsive-videos');

		// Add theme support for Content Options.
		add_theme_support(
			'jetpack-content-options',
			[
				'post-details' => [
					'stylesheet' => 'rising-bamboo-style',
					'date'       => '.posted-on',
					'categories' => '.cat-links',
					'tags'       => '.tags-links',
					'author'     => '.byline',
					'comment'    => '.comments-link',
				],
				'featured-images' => [
					'archive' => true,
					'post'    => true,
					'page'    => true,
				],
			]
		);
	}
	/**
	 * Custom render function for Infinite Scroll.
	 */
	public function infinite_scroll_render():void {
		while ( have_posts() ) {
			the_post();
			if ( is_search() ) :
				get_template_part('template-parts/contents/content', 'search');
			else :
				get_template_part('template-parts/contents/content', get_post_type());
			endif;
		}
	}
}
