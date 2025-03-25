<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore.
 */

namespace RisingBambooCore\App\Frontend;

use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\Setting;

/**
 * Add Tracking Tools to website.
 */
class Tracking extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		if ( Setting::get_option('pixel_status') && ! empty(Setting::get_option('pixel_id')) ) {
			add_action('wp_head', [ $this, 'facebook_pixel' ]);
		}
		if ( Setting::get_option('analytics_status') && ! empty(Setting::get_option('analytics_key')) ) {
			wp_enqueue_script('google-tag-manager-url', 'https://www.googletagmanager.com/gtag/js?id=' . esc_attr(Setting::get_option('analytics_key')), [], App::get_version(), false);
			add_action('wp_head', [ $this, 'google_analytics' ]);
		}

		if ( Setting::get_option('tag_manager_status') && ! empty(Setting::get_option('tag_manager_container')) ) {
			add_action('wp_body_open', [ $this, 'google_tag_manager_noscript' ]);
		}
		if ( Setting::get_option('tag_manager_status') && ! empty(Setting::get_option('tag_manager_container')) ) {
			add_action('wp_head', [ $this, 'google_tag_manager' ]);
		}
	}

	/**
	 * Google Tag Manger.
	 */
	public function google_tag_manager(): void {
		printf(
			"
        <!-- Google Tag Manager By RisingBamboo -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','%s');
        </script>
        <!-- End Google Tag Manager By RisingBamboo -->
        ",
			Setting::get_option('tag_manager_container') // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Google Tag Manager Script.
	 */
	public function google_tag_manager_noscript(): void {
		printf(
			"
            <!-- Google Tag Manager (noscript) by RisingBamboo -->
            <noscript>
                <iframe src='https://www.googletagmanager.com/ns.html?id=%s' height='0' width='0' style='display:none;visibility:hidden'></iframe>
            </noscript>
            <!-- End Google Tag Manager (noscript) by RisingBamboo -->
            ",
			Setting::get_option('tag_manager_container') // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Google Analytics.
	 */
	public function google_analytics(): void {
		printf(
			"
            <!-- Global site tag (gtag.js) - Google Analytics by RisingBamboo -->
            <script>
                window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '%s');
            </script>
            <!-- End Google Analytics by RisingBamboo -->
            ",
			Setting::get_option('analytics_key'), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Facebook Pixel.
	 */
	public function facebook_pixel(): void {
		printf(
			"
        <!-- Facebook Pixel by RisingBamboo -->
            <script>
                !function(f,b,e,v,n,t,s)
                  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                  n.queue=[];t=b.createElement(e);t.async=!0;
                  t.src=v;s=b.getElementsByTagName(e)[0];
                  s.parentNode.insertBefore(t,s)}(window, document,'script',
                  'https://connect.facebook.net/en_US/fbevents.js');
                  fbq('init', '%s');
                  fbq('track', 'PageView');
            </script>
            <noscript>
                <img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id=%s&ev=PageView&noscript=1'/>
	        </noscript>
	        <script>
                fbq('track', 'Search');
                fbq('track', 'ViewContent');
                fbq('track', 'Lead');
            </script>
          <!-- End Facebook Pixel by RisingBamboo -->
          ",
			Setting::get_option('pixel_id'), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			Setting::get_option('pixel_id') // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
}
