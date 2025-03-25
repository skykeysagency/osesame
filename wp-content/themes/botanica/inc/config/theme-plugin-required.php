<?php
/**
 * List required plugins
 *
 * @package Rising_Bamboo
 */

return [
	[
		'name'             => esc_html__('Elementor', 'botanica'),
		'slug'             => 'elementor',
		'required'         => true,
		'force_activation' => true,
	],
	[
		'name'               => esc_html__('Rising Bamboo Core', 'botanica'),
		'slug'               => 'rising-bamboo',
		'source'             => 'https://plugins.risingbamboo.com/rising-bamboo/rising-bamboo-v1-3-1.zip',
		'required'           => true,
		'force_activation'   => true,
		'force_deactivation' => false,
		'external_url'       => 'https://plugins.risingbamboo.com/rising-bamboo/rising-bamboo-v1-3-1.zip',
		'version'            => '1.3.1',
	],
	[
		'name'     => esc_html__('One Click Demo Import', 'botanica'),
		'slug'     => 'one-click-demo-import',
		'required' => true,
	],
	[
		'name'     => esc_html__('WooCommerce', 'botanica'),
		'slug'     => 'woocommerce',
		'required' => true,
	],
	[
		'name'               => esc_html__('WPC Smart Wishlist for WooCommerce', 'botanica'),
		'slug'               => 'woo-smart-wishlist',
		'required'           => false,
	],
	[
		'name'               => esc_html__('WPC Smart Compare for WooCommerce', 'botanica'),
		'slug'               => 'woo-smart-compare',
		'required'           => false,
	],
	[
		'name'               => esc_html__('Contact Form 7', 'botanica'),
		'slug'               => 'contact-form-7',
		'required'           => false,
	],
];
