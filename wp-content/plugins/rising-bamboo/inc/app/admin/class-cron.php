<?php
/**
 * RisingBambooCore.
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\App\Admin;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\License as LicenseHelper;

/**
 * Cron Job.
 *
 * @package Rising_Bamboo
 */
class Cron extends Singleton {


	/**
	 * Construct.
	 */
	public function __construct() {
		add_filter('cron_schedules', [ $this, 'add_cron_interval' ]);
		add_action('rbb_cron_check_license', [ $this, 'rbb_cron_check_license' ]);
		$this->register();
		register_deactivation_hook(plugin_basename(RBB_CORE_PATH_FILE), [ $this, 'unregister' ]);
	}

	/**
	 * Register.
	 *
	 * @return void
	 */
	public function register(): void {
		if ( ! wp_next_scheduled('rbb_cron_check_license') ) {
			wp_schedule_event(time(), 'rbb_weekly', 'rbb_cron_check_license');
		}
	}

	/**
	 * Unregister.
	 *
	 * @return void
	 */
	public function unregister(): void {
		$timestamp = wp_next_scheduled('rbb_cron_check_license');
		wp_unschedule_event($timestamp, 'rbb_cron_check_license');
	}

	/**
	 * Check License.
	 *
	 * @return void
	 */
	public function rbb_cron_check_license(): void {
		$license = LicenseHelper::is_activated();
		if ( $license ) {
			$validate = ThemeLicense::instance()->verify($license);
			if ( $validate ) {
				$code = $validate->status_code;
				if ( 404 === $code || 410 === $code ) {
					delete_option(LicenseHelper::get_license_option_name());
				}
			}
		}
	}

	/**
	 * Add cron interval.
	 *
	 * @return array
	 */
	public function add_cron_interval(): array {
		$schedules['rbb_weekly']      = [
			'interval' => 86400 * 7,
			'display'  => esc_html__('Once Every Week', App::get_domain()),
		];
		$schedules['rbb_fortnightly'] = [
			'interval' => 86400 * 14,
			'display'  => esc_html__('Once Every Fortnight', App::get_domain()),
		];
		return $schedules;
	}
}
