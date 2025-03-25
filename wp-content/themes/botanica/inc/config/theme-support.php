<?php
/**
 * List theme support features.
 *
 * @package Rising_Bamboo
 */

return [
	
	/**
	 * The automatic-feed-links and title-tag features have been manually added via theme support.
	 * Fixed for Envato theme check plugin.
	 */
	
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	[ 'feature' => 'post-thumbnails' ],

	[
		'feature' => 'rbb-post-thumbnails',
		'args'    => [
			'width' => 600,
		],
	],

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	[
		'feature' => 'html5',
		'args'    => [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		],
	],

	// Add theme support for selective refresh for widgets.
	[ 'feature' => 'customize-selective-refresh-widgets' ],
	[ 'feature' => 'align-wide' ],

	// Woocommerce Feature.
	[
		'feature' => 'woocommerce',
		'args'    => [
			'thumbnail_image_width'         => 300,
			'gallery_thumbnail_image_width' => 200,
			'single_image_width'            => 600,
			'product_grid'                  => [
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			],
		],
	],

	// Rbb Feature.
	[ 'feature' => 'rbb-core' ],
	[ 'feature' => 'rbb-mega-menu' ],
	[ 'feature' => 'rbb-icons' ],
	[ 'feature' => 'rbb-quick-view' ],
	[ 'feature' => 'rbb-tracking' ],
	[ 'feature' => 'rbb-modal' ],
	[ 'feature' => 'rbb-tabs' ],
	// New look of subcategories in product loop. Remove when use default.
	[ 'feature' => 'rbb-woo-subcategories' ],
	[ 'feature' => 'rbb-testimonial' ],
	[
		'feature' => 'rbb_slider',
		'args'    => [
			'images_per_slider' => 4,
		],
	],
	[
		'feature' => 'rbb-avatar',
		'args'    => [
			'small'  => 80,
			'medium' => 120,
			'large'  => 240,
		],
	],
	[
		'feature' => 'rbb-menu-item-image',
		'args'    => [ 24, 24 ],
	],
	[
		'feature' => 'rbb-pace',
		'args'    => [
			'color' => 'green',
			'style' => 'minimal',
		],
	],
];
