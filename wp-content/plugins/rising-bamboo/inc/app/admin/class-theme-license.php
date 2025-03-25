<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use JsonException;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\License as LicenseHelper;
use RisingBambooCore\Helper\Setting;
use WP_Theme;
use WpOrg\Requests\Exception;

/**
 * Admin pages.
 *
 * @package Rising_Bamboo
 */
class ThemeLicense extends Singleton {


	/**
	 * API Config Array.
	 *
	 * @var array|false|mixed
	 */
	protected $api_config = [];

	/**
	 * API URL
	 *
	 * @var string
	 */
	protected string $api_url;

	/**
	 * Current Theme.
	 *
	 * @var WP_Theme
	 */
	protected WP_Theme $current_theme;

	/**
	 * Option Name for license.
	 *
	 * @var string
	 */
	protected string $license_option_name;

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->api_config          = LicenseHelper::get_api_config();
		$this->api_url             = untrailingslashit($this->api_config['url'] ?? App::get_info('api'));
		$this->current_theme       = wp_get_theme();
		$this->license_option_name = LicenseHelper::get_license_option_name();
		add_action('wp_ajax_rbb_active_theme_license', [ $this, 'active' ]); // nonce - ok.
		add_action('wp_ajax_rbb_deactivate_theme_license', [ $this, 'deactivate' ]); // nonce - ok.
		add_action('after_setup_theme', [ $this, 'force_setting' ]);
		add_action('rbb_license_activated', [ $this, 'set_default_settings' ], 10, 1);
	}

	/**
	 * Activate License.
	 *
	 * @return void
	 * @throws JsonException Throw Error.
	 */
	public function active(): void {
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			// phpcs:ignore
			$purchase_code = sanitize_text_field(wp_unslash($_POST['purchase_code']));
			if ( ! empty($purchase_code) ) {
				$api_url = $this->api_url . '/license/active';
				$params  = [
					'purchase_code'  => $purchase_code,
					'identification' => get_site_url(),
				];
				if ( ! empty($this->api_config['marketplace_account']) ) {
					$params['account'] = $this->api_config['marketplace_account'];
				}
				if ( ! empty($this->api_config['product_id']) ) {
					$params['product'] = $this->api_config['product_id'];
				}
				$response = wp_remote_post(
					$api_url,
					[
						'body' => $params,
					]
				);
				if ( ! is_wp_error($response) && wp_remote_retrieve_response_code($response) !== 500 ) {
					$result      = json_decode($response['body'], true, 512, JSON_THROW_ON_ERROR);
					$status_code = $result['status_code'];
					$message     = $result['message'];
					if ( 200 === $status_code ) {
						if ( ! update_option($this->license_option_name, $result['data']) ) {
							$status_code = 503;
							$message     = __('Cannot save license token. Please contact theme author!', App::get_domain());
						} else {
							//phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
							do_action('rbb_license_activated', $this->license_option_name);
						}
					}
				} else {
					$status_code = 400;
					$message     = __('Unable to connect to the Rising Bamboo API. Please try again or contact theme author!', App::get_domain());
				}
			} else {
				$status_code = 406;
				$message     = __('Please enter the purchase code.', App::get_domain());
			}

			$result = [
				'message'     => $message,
				'status_code' => $status_code,
			];
			wp_send_json(
				[
					'result' => $result,
				],
				$status_code
			);
		}
	}

	/**
	 * Deactivate License
	 *
	 * @return void
	 * @throws JsonException Error Throw.
	 */
	public function deactivate(): void {
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			//phpcs:ignore
			$license_key = sanitize_text_field(wp_unslash($_POST['license_key']));
			if ( ! empty($license_key) ) {
				$api_url  = $this->api_url . '/license/deactivate';
				$params   = [
					'token' => $license_key,
				];
				$response = wp_remote_post(
					$api_url,
					[
						'method' => 'PUT',
						'body'   => $params,
					]
				);
				if ( ! is_wp_error($response) ) {
					$result      = json_decode($response['body'], true, 512, JSON_THROW_ON_ERROR);
					$status_code = $result['status_code'];
					$message     = $result['message'];
					if ( in_array($status_code, [ 404, 200 ], true) ) {
						$status_code = 200;
						delete_option(LicenseHelper::get_license_option_name());
					}
				} else {
					$status_code = 400;
					$message     = __('Bad Request. Unable to connect to the API', App::get_domain());
				}
			} else {
				$status_code = 406;
				$message     = __('Please enter the purchase code.', App::get_domain());
			}

			$result = [
				'message'     => $message,
				'status_code' => $status_code,
			];
			wp_send_json(
				[
					'result' => $result,
				],
				$status_code
			);
		}
	}

	/**
	 * Disable feature.
	 *
	 * @return void
	 */
	public function force_setting(): void {
		if ( ! LicenseHelper::is_activated() ) {
			Setting::update_option('woocommerce_brands', 0, true);
			Setting::update_option('woocommerce_swatches', 0, true);
			Setting::update_option('woocommerce_shipping_estimate_time', 0, true);
			Setting::update_option('woocommerce_free_shipping_calculator', 0, true);
		}
	}

	/**
	 * Set Default Setting After Activate.
	 *
	 * @return void
	 */
	public function set_default_settings(): void {
		$settings = Settings::instance()->get_settings();
		if ( isset($settings['woocommerce']) ) {
			foreach ( $settings['woocommerce'] as $woo_setting ) {
				$name    = $woo_setting['name'];
				$default = $woo_setting['options']['default'] ?? null;
				if ( ! empty($name) && null !== $default ) {
					if ( is_bool($default) ) {
						$default = (int) $default;
					}
					Setting::update_option($name, $default, true);
				}
			}
		}
	}

	/**
	 * Verify License.
	 *
	 * @param array $license The license info.
	 * @throws JsonException Exception.
	 */
	public function verify( array $license ) {
		$params = [
			'token' => $license['token'] ?? '',
		];

		$api_url = $this->api_url . '/license?' . http_build_query($params);

		$response = wp_remote_get($api_url);
		if ( is_wp_error($response) ) {
			return (object) [
				'status_code' => $response->get_error_code(),
				'message'     => $response->get_error_message(),
			];
		}
		return json_decode(wp_remote_retrieve_body($response), false, 512, JSON_THROW_ON_ERROR);
	}
}
