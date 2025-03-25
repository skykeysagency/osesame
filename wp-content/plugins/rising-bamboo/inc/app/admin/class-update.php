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
use RisingBambooCore\Helper\License;
use RisingBambooCore\Helper\Setting;
use RisingBambooCore\Helper\Theme;
use stdClass;

/**
 * Check update RisingBamboo Plg and Theme.
 */
class Update extends Singleton {

	/**
	 * API URL.
	 *
	 * @var string|mixed|null
	 */
	protected string $api_url;

	/**
	 * ID of Plg on API.
	 *
	 * @var string|mixed|null
	 */
	protected string $plugin_id;

	/**
	 * Plugin Slug.
	 *
	 * @var string
	 */
	protected string $plugin_slug;

	/**
	 * Plugin Data.
	 *
	 * @var false|mixed
	 */
	protected $plugin_data;

	/**
	 * All RBB Theme.
	 *
	 * @var array
	 */
	protected array $themes = [];


	/**
	 * Cache allow.
	 *
	 * @var bool
	 */
	protected bool $cache_allowed;

	/**
	 * Cache Key.
	 *
	 * @var string
	 */
	protected string $plugin_cache_key;

	/**
	 * Cache Key.
	 *
	 * @var string
	 */
	protected string $theme_cache_key;

	/**
	 * The account to which the product belongs.
	 *
	 * @var string|mixed|null
	 */
	protected string $market_account;

	/**
	 * Construct.
	 */
	public function __construct() {
		if ( is_admin() ) {
			$this->api_url = App::get_info('api');
			if ( Setting::get_option('development_mode') ) {
				$this->cache_allowed = false;
			} else {
				$this->cache_allowed = true;
			}
			$this->init_plugin_data();

			$this->init_theme_data();

			add_filter('plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 3);
			add_filter('plugins_api', [ $this, 'info' ], 10, 3);
			add_filter('pre_set_site_transient_update_plugins', [ $this, 'plugin_update' ]);
			add_filter('pre_set_site_transient_update_themes', [ $this, 'theme_update' ]);
			add_filter('http_request_args', [ $this, 'allow_http_update' ], 999);

			add_action('upgrader_process_complete', [ $this, 'purge' ], 10, 2);

			$this->force_update_check();
		}
	}

	/**
	 * Init Plugin Data.
	 *
	 * @return void
	 * @throws JsonException Throw Error.
	 */
	public function init_plugin_data(): void {
		$this->plugin_id      = App::get_info('id');
		$this->market_account = App::get_info('account');

		$this->plugin_slug = dirname(RBB_CORE_BASENAME);

		$this->plugin_cache_key = strtolower('plg-' . $this->plugin_slug . '-update');

		$this->plugin_data = $this->request(
			[
				'id'      => $this->plugin_id,
				'account' => $this->market_account,
			],
			$this->plugin_cache_key
		);
	}

	/**
	 * Init Theme Data.
	 *
	 * @return void
	 * @throws JsonException Throw Error.
	 */
	public function init_theme_data(): void {
		$themes = Theme::instance()->get_themes();
		foreach ( $themes as $stylesheet => $theme ) {
			$license = License::is_activated($stylesheet);
			if ( $license ) {
				$this->theme_cache_key                   = strtolower('rbb-theme-' . $stylesheet . '-update');
				$this->themes[ $stylesheet ] ['version'] = $theme->get('Version');
				$this->themes[ $stylesheet ] ['update']  = $this->request(
					[
						'token' => $license['token'],
					],
					$this->theme_cache_key
				);
			}
		}
	}

	/**
	 * Info.
	 *
	 * @param mixed $result Result.
	 * @param mixed $action Action.
	 * @param mixed $args Args.
	 * @return mixed
	 */
	public function info( $result, $action, $args ) {
		$data = $this->plugin_data;
		if ( 'plugin_information' !== $action || $this->plugin_slug !== $args->slug || ! $data ) {
			return $result;
		}

		$result = new stdClass();

		$result->name           = $data->name;
		$result->slug           = $data->slug;
		$result->version        = $data->version;
		$result->tested         = $data->tested;
		$result->requires       = $data->requires;
		$result->author         = $data->author;
		$result->author_profile = $data->author_profile;
		$result->download_link  = $data->download_url;
		$result->trunk          = $data->download_url;
		$result->requires_php   = $data->requires_php;
		$result->last_updated   = $data->last_updated;

		$result->sections = [
			'description'  => nl2br($data->sections->description),
			'installation' => nl2br($data->sections->installation),
			'changelog'    => nl2br($data->sections->changelog),
		];

		if ( ! empty($data->banners) ) {
			$result->banners = [
				'low'  => $data->banners->low,
				'high' => $data->banners->high,
			];
		}
		return $result;
	}

	/**
	 * Get Request.
	 *
	 * @param array  $params The Params.
	 * @param string $cache The Cache Key.
	 * @return false|mixed
	 * @throws JsonException Exception.
	 */
	public function request( array $params, string $cache ) {

		$data = get_transient($cache);

		if ( false === $data || ! $this->cache_allowed ) {
			$url    = $this->api_url . '/update/check';
			$remote = wp_remote_get(
				$url,
				[
					'timeout' => 10,
					'headers' => [
						'Accept' => 'application/json',
					],
					'body'    => $params,
				]
			);
            //phpcs:ignore
			if ( is_wp_error($remote) || 200 !== wp_remote_retrieve_response_code($remote) || empty($response = wp_remote_retrieve_body($remote)) ) {
				return false;
			}
			$data    = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
			$data    = $data->data;
			$expired = $data->download_url_expired ?? HOUR_IN_SECONDS;
			set_transient($cache, $data, $expired);
		}
		return $data;
	}

	/**
	 * Update.
	 *
	 * @param mixed $transient Transient.
	 * @return mixed
	 * @throws JsonException Exception.
	 */
	public function plugin_update( $transient ) {
		if ( ! empty($transient->checked) ) {
			$data = $this->plugin_data;
			if ( $this->can_update($data, untrailingslashit(App::get_info('Version'))) ) {
				$result              = new stdClass();
				$result->slug        = $this->plugin_slug;
				$result->plugin      = plugin_basename(RBB_CORE_PATH_FILE);
				$result->new_version = $data->version;
				$result->tested      = $data->tested;
				$result->package     = $data->download_url;
				$result->banners     = json_decode(wp_json_encode($data->banners, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
				$result->icons       = json_decode(wp_json_encode($data->icons, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

				$transient->response[ $result->plugin ] = $result;
			}
		}
		return $transient;
	}

	/**
	 * Update Theme.
	 *
	 * @param mixed $transient Transient.
	 * @return mixed
	 */
	public function theme_update( $transient ) {
		if ( ! empty($transient->checked) && $this->themes ) {
			foreach ( $this->themes as $slug => $theme ) {
				$update  = $theme['update'];
				$version = $theme['version'];
				if ( $this->can_update($update, $version) ) {
					$result                = [];
					$result['url']         = $update->url;
					$result['slug']        = $update->slug;
					$result['new_version'] = $update->version;
					$result['tested']      = $update->tested;
					$result['package']     = $update->download_url;

					$transient->response[ $slug ] = $result;
				}
			}
		}
		return $transient;
	}

	/**
	 * Pure.
	 *
	 * @param mixed $upgrader Upgrader.
	 * @param mixed $options Options.
	 * @return void
	 */
	public function purge( $upgrader, $options ): void {

		if ( $this->cache_allowed && 'update' === $options['action'] ) {
			if ( 'plugin' === $options['type'] ) {
				delete_transient($this->plugin_cache_key);
			}
			if ( 'theme' === $options['type'] ) {
				delete_transient($this->theme_cache_key);
			}
		}
	}

	/**
	 * Add Plugin Meta Link.
	 *
	 * @param mixed $plugin_meta Meta.
	 * @param mixed $plugin_file File.
	 * @param mixed $plugin_data Data.
	 * @return mixed
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data ) {
		if ( RBB_CORE_PATH_FILE === path_join(WP_PLUGIN_DIR, $plugin_file) ) {
			/* translators: %s: link */
			$plugin_meta[] = sprintf(
				'<a target="_blank" href="%s" aria-label="%s" data-title="%s">%s</a>',
				esc_url(App::get_info('docs')),
				/* translators: %s: link */
				esc_attr(sprintf(__('More information about %s', App::get_domain()), $plugin_data['Name'])),
				esc_attr($plugin_data['Name']),
				__('Docs', App::get_domain())
			);
		}
		return $plugin_meta;
	}

	/**
	 * Allow http when updating.
	 *
	 * @param mixed $args Args.
	 * @return mixed
	 */
	public function allow_http_update( $args ) {
        //phpcs:ignore
		if ( $this->plugin_data && $url = $this->plugin_data->download_url ) {
			$url = wp_parse_url($url, PHP_URL_SCHEME);
			if ( 'http' === $url ) {
				$args['reject_unsafe_urls'] = false;
			}
		}
		return $args;
	}

	/**
	 * Check update
	 *
	 * @param mixed  $data Data.
	 * @param string $version Version.
	 * @return bool
	 */
	public function can_update( $data, string $version ): bool {
		return $data && version_compare($data->requires_php, PHP_VERSION, '<') && version_compare($version, $data->version, '<') && version_compare($data->requires, get_bloginfo('version'), '<=');
	}

	/**
	 * Force WordPress check the update.
	 *
	 * @return void
	 */
	protected function force_update_check(): void {
		/**
		 * When enable this func, button Update Now at detail plugin will disappear.
		 */
		if ( Setting::get_option('development_mode') ) {
			set_site_transient('update_plugins', null);
			set_site_transient('update_themes', null);
		}
	}
}
