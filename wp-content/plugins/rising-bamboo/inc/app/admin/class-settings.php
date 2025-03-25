<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\Setting;

/**
 * Settings.
 */
class Settings extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action('init', [ $this, 'register_settings' ]);
	}

	/**
	 * Get settings.
	 *
	 * @return array[]
	 */
	public function get_settings(): array {
		$settings = [
			'google' => [
				[
					'name'    => 'analytics_status',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => false,
					],
				],
				[
					'name'    => 'analytics_key',
					'options' => [
						'type'         => 'string',
						'show_in_rest' => true,
					],
				],
				[
					'name'    => 'tag_manager_status',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => false,
					],
				],
				[
					'name'    => 'tag_manager_container',
					'options' => [
						'type'         => 'string',
						'show_in_rest' => true,
					],
				],
			],
			'facebook' => [
				[
					'name'    => 'pixel_status',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => false,
					],
				],
				[
					'name'    => 'pixel_id',
					'options' => [
						'type'         => 'string',
						'show_in_rest' => true,
					],
				],
			],

			'social_share' => [
				[
					'name'    => 'share_facebook',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => true,
					],
				],
				[
					'name'    => 'share_twitter',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => true,
					],
				],
				[
					'name'    => 'share_linkedin',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => true,
					],
				],
				[
					'name'    => 'share_pinterest',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => true,
					],
				],
				[
					'name'    => 'share_tumblr',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => true,
					],
				],
				[
					'name'    => 'share_email',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => true,
					],
				],
			],
			'contact' => [
				[
					'name'    => 'contact_phone',
					'options' => [
						'type'         => 'string',
						'show_in_rest' => true,
						'default'      => '',
					],
				],
				[
					'name'    => 'contact_email',
					'options' => [
						'type'         => 'string',
						'show_in_rest' => true,
						'default'      => '',
					],
				],
				[
					'name'    => 'contact_address',
					'options' => [
						'type'         => 'string',
						'show_in_rest' => true,
						'default'      => '',
					],
				],
			],
			'development' => [
				[
					'name'    => 'development_mode',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => false,
					],
				],
				[
					'name'    => 'development_override_settings',
					'options' => [
						'type'         => 'boolean',
						'show_in_rest' => true,
						'default'      => false,
					],
				],
			],
		];

		if ( Helper::woocommerce_activated() ) {
			$settings = wp_parse_args(
				[
					'woocommerce' => [
						[
							'name'    => 'woocommerce_brands',
							'options' => [
								'type'         => 'boolean',
								'show_in_rest' => true,
								'default'      => true,
							],
						],
						[
							'name'    => 'woocommerce_swatches',
							'options' => [
								'type'         => 'boolean',
								'show_in_rest' => true,
								'default'      => true,
							],
						],
						[
							'name'    => 'woocommerce_product_category_operator',
							'options' => [
								'type'         => 'string',
								'show_in_rest' => true,
								'default'      => 'or',
							],
						],
						[
							'name'    => 'woocommerce_shipping_estimate_time',
							'options' => [
								'type'         => 'boolean',
								'show_in_rest' => true,
								'default'      => true,
							],
						],
						[
							'name'    => 'woocommerce_shipping_estimate_time_type',
							'options' => [
								'type'         => 'string',
								'show_in_rest' => true,
								'default'      => 'day',
							],
						],
						[
							'name'    => 'woocommerce_shipping_estimate_time_round',
							'options' => [
								'type'         => 'number',
								'show_in_rest' => true,
								'default'      => 30,
							],
						],
						[
							'name'    => 'woocommerce_free_shipping_calculator',
							'options' => [
								'type'         => 'boolean',
								'show_in_rest' => true,
								'default'      => true,
							],
						],
						[
							'name'    => 'woocommerce_free_shipping_calculator_position',
							'options' => [
								'type'         => 'string',
								'show_in_rest' => true,
								'default'      => 'woocommerce_before_cart_table',
							],
						],
						[
							'name'    => 'woocommerce_free_shipping_calculator_mini_cart',
							'options' => [
								'type'         => 'boolean',
								'show_in_rest' => true,
								'default'      => true,
							],
						],
					],
				],
				$settings
			);
		}
		return $settings;
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		$setting_arr = $this->get_settings();
		foreach ( $setting_arr as  $group => $settings ) {
			foreach ( $settings as $setting ) {
				if ( isset($setting['name'], $setting['options']) ) {
					Setting::register_setting(
						$group,
						$setting['name'],
						$setting['options']
					);
				}
			}
		}
	}
}
