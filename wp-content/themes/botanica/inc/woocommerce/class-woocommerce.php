<?php
/**
 * Woocommerce.
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\Woocommerce;

use RisingBambooTheme\Core\Singleton;
use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Helper as RisingBambooThemeHelper;
use RisingBambooTheme\Helper\Setting;
use WC_Template_Loader;
use WP_Query;

/**
 * WooCommerce Compatibility File
 *
 * @link    https://woocommerce.com/
 *
 * @package Rising_Bamboo
 */
class Woocommerce extends Singleton {


	/**
	 * Construction
	 */
	public function __construct() {
		if ( RisingBambooThemeHelper::woocommerce_activated() ) {
			add_action('after_setup_theme', [ $this, 'setup' ], 30);
			add_action('wp_enqueue_scripts', [ $this, 'scripts' ]);
			/**
			 * Disable the default WooCommerce stylesheet.
			 *
			 * Removing the default WooCommerce stylesheet and enqueuing your own will
			 * protect you during WooCommerce core updates.
			 *
			 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
			 */
			add_filter('woocommerce_enqueue_styles', '__return_empty_array');
			add_filter('body_class', [ $this, 'active_body_class' ]);
			// Ajax Search.
			add_action('wp_ajax_rbb_ajax_product_search', [ $this, 'rbb_ajax_product_search' ]); // nonce - ok.
			add_action('wp_ajax_nopriv_rbb_ajax_product_search', [ $this, 'rbb_ajax_product_search' ]); // nonce - ok.
			add_filter('woocommerce_product_get_rating_html', [ $this, 'product_get_rating_html' ], 20, 3);
			// Remove default WooCommerce wrapper.
			remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper');
			remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end');
			add_action('woocommerce_before_main_content', [ $this, 'show_shop_archive_categories' ]);
			add_action('woocommerce_before_main_content', [ $this, 'wrapper_before' ]);
			add_action('woocommerce_after_main_content', [ $this, 'wrapper_after' ]);
			add_filter('woocommerce_add_to_cart_fragments', [ $this, 'cart_fragment' ]);
			// Modify Fields.
			add_filter('woocommerce_form_field_args', [ $this, 'woocommerce_form_field_args' ], 10, 3);
			// Plus, Minus Quantity.
			add_action('woocommerce_before_quantity_input_field', [ $this, 'woocommerce_before_quantity_input_field' ]);
			add_action('woocommerce_after_quantity_input_field', [ $this, 'woocommerce_after_quantity_input_field' ]);
			// Notifications in checkout page.
			remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices');
			add_action('woocommerce_before_checkout_form_notification', 'woocommerce_output_all_notices');
			// Remove default breadcrumb.
			remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
			
			// <editor-fold desc="Product Related & Cross-Sell">
			add_action('wp_loaded', [ $this, 'related_product_and_cross_sells_tab' ]);
			add_action('wp_loaded', [ $this, 'related_product_setup' ]);
			add_action('wp_loaded', [ $this, 'upsells_setup' ]);
			add_action('wp_loaded', [ $this, 'cross_sells_setup' ]);
			// </editor-fold>
			
			// <editor-fold desc="Product Detail Summary">
			add_action('wp_loaded', [ $this, 'product_detail_excerpt' ]);
			add_action('woocommerce_share', [ $this, 'product_detail_sharing' ]);
			add_action('woocommerce_after_add_to_cart_button', [ $this, 'add_buy_now_button' ]);
			add_action('wp_loaded', [ $this, 'ask_a_question' ]);
			add_action('woocommerce_single_product_summary', [ $this, 'guarantee_and_safe_checkout' ], 60);
			add_filter('woocommerce_product_tabs', [ $this, 'format_review_tab' ]);
			// </editor-fold>
			
			// <editor-fold desc="Product Detail Data">
			add_action('wp_loaded', [ $this, 'product_data_setup' ]);
			// </editor-fold>
			
			add_filter(
				'woocommerce_review_gravatar_size',
				function ( $size ) {
					return get_theme_support('rbb-avatar')[0]['small'] ?? $size;
				}
			);
			
			add_filter('rbb_theme_single_product_image_wrapper_classes', [ $this, 'single_product_image_wrapper_classes' ]);
			add_filter('rbb_theme_single_product_summary_wrapper_classes', [ $this, 'product_summary_wrapper_classes' ]);
			
			// <editor-fold desc="Product Catalog">
			add_action('wp_loaded', [ $this, 'product_catalog_filter_setup' ]);
			add_filter('rbb_theme_woocommerce_before_main_content', [ $this, 'product_catalog_layout_setup' ]);
			// </editor-fold>
			add_filter('woocommerce_loop_add_to_cart_args', [ $this, 'remove_redundant_aria_describedby' ]);
		}
	}

	/**
	 * WooCommerce setup function.
	 * Move all theme_support config to /inc/config/theme-support.php
	 *
	 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
	 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
	 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
	 *
	 * @return void
	 */
	public function setup(): void {
		if ( get_theme_support('rbb-woo-subcategories') ) {
			add_filter('woocommerce_product_loop_start', [ $this, 'show_product_categories_loop' ]);
		}
	}

	/**
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @return void
	 */
	public function scripts(): void {
		wp_enqueue_style('rising-bamboo-photoswipe-lightbox', get_template_directory_uri() . '/dist/js/plugins/photoswipe/photoswipe.css', [], App::$version);
		wp_enqueue_style('rising-bamboo-woocommerce-style', get_template_directory_uri() . '/dist/css/woocommerce.css', [], App::$version);

		$font_path   = WC()->plugin_url() . '/assets/fonts/';
		$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
			url("' . $font_path . 'star.woff") format("woff"),
			url("' . $font_path . 'star.ttf") format("truetype"),
			url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';
		wp_add_inline_style('rising-bamboo-woocommerce-style', $inline_font);
		wp_enqueue_script('rising-bamboo-woocommerce-js', get_template_directory_uri() . '/dist/js/woocommerce.js', [ 'jquery' ], App::$version, true);
		wp_enqueue_script('rising-bamboo-jquery-zoom', get_template_directory_uri() . '/dist/js/plugins/jquery.zoom.min.js', [ 'jquery' ], App::$version, true);
	}

	/**
	 * Add 'woocommerce-active' class to the body tag.
	 *
	 * @param array $classes CSS classes applied to the body tag.
	 * @return array $classes modified to include 'woocommerce-active' class.
	 */
	public function active_body_class( array $classes ): array {
		$classes[] = 'woocommerce-active';

		return $classes;
	}

	/**
	 * Setup related product.
	 *
	 * @return void
	 */
	public function related_product_setup(): void {
		$status = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_STATUS);
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		if ( true === $status ) {
			if ( 'tabs' === $this->get_related_and_cross_sell_layout() ) {
				add_filter(
					'rbb_woocommerce_after_single_product_tabs',
					function ( $tabs = [] ) {
						global $product;
						$has_related_product = wc_get_related_products($product->get_id(), 1, $product->get_upsell_ids());
						if ( ! empty($has_related_product) ) {
							$tabs['related_product'] = [
								'title'    => apply_filters('woocommerce_product_related_products_heading', __('Related Products', 'botanica')),
								'priority' => 20,
								'callback' => 'woocommerce_output_related_products',
							];
						}
						return $tabs;
					}
				);
			} else {
				add_action('woocommerce_after_single_product', 'woocommerce_output_related_products', 15);
			}
			add_filter(
				'woocommerce_output_related_products_args',
				function ( $args ) {
					$defaults = [
                        // phpcs:ignore
						'posts_per_page' => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_PER_PAGE),
						'columns'        => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_COLS),
						'orderby'        => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_ORDER_BY),
						'order'          => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_ORDER),
					];
					return wp_parse_args($defaults, $args);
				}
			);
		}
	}

	/**
	 * Get layout.
	 *
	 * @return mixed|string
	 */
	protected function get_related_and_cross_sell_layout() {
		return Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_RELATED_UP_CROSS_SELLS_LAYOUT);
	}

	/**
	 * Setup Upsells product.
	 *
	 * @return void
	 */
	public function upsells_setup(): void {
		$status = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_STATUS);
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
		if ( true === $status ) {
			if ( 'tabs' === $this->get_related_and_cross_sell_layout() ) {
				add_filter(
					'rbb_woocommerce_after_single_product_tabs',
					function ( $tabs = [] ) {
						global $product;
						if ( $product->get_upsell_ids() ) {
							$tabs['upsells'] = [
								'title'    => __('Recommended', 'botanica'),
								'priority' => 30,
								'callback' => 'woocommerce_upsell_display',
							];
						}
						return $tabs;
					}
				);
			} else {
				add_action('woocommerce_after_single_product', 'woocommerce_upsell_display', 20);
			}
			add_filter(
				'woocommerce_upsell_display_args',
				function ( $args ) {
					$defaults = [
                        // phpcs:ignore
						'posts_per_page' => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_PER_PAGE),
						'columns'        => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_COLS),
						'orderby'        => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_ORDER_BY),
						'order'          => Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_UP_SELLS_ORDER),
					];
					return wp_parse_args($defaults, $args);
				}
			);
		}
	}

	/**
	 * Rbb tabs.
	 *
	 * @return void
	 */
	public function related_product_and_cross_sells_tab(): void {
		if ( 'tabs' === $this->get_related_and_cross_sell_layout() ) {
			add_action(
				'woocommerce_after_single_product',
				function () {
					wc_get_template_part('single-product/tabs/rbb', 'tabs');
				}
			);
		}
	}

	/**
	 * Setup cross-sells product.
	 *
	 * @return void
	 */
	public function cross_sells_setup(): void {
		add_filter(
			'woocommerce_cross_sells_total',
			function ( $limit ) {
				return Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_PER_PAGE);
			}
		);
		add_filter(
			'woocommerce_cross_sells_columns',
			function ( $column ) {
				return Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_COLS);
			}
		);
		add_filter(
			'woocommerce_cross_sells_orderby',
			function ( $orderby ) {
				return Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_ORDER_BY);
			}
		);
		add_filter(
			'woocommerce_cross_sells_order',
			function ( $order ) {
				return Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_CROSSS_SELLS_ORDER);
			}
		);
	}

	/**
	 * Setup product data (descript, review ..)
	 *
	 * @return void
	 */
	public function product_data_setup(): void {
		$layout = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_DATA_LAYOUT);
		if ( 'accordion' === $layout ) {
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs');
			add_action('woocommerce_single_product_summary', [ $this, 'woocommerce_output_product_data_accordion' ], 90);
		}
	}

	/**
	 * Get accordion template.
	 *
	 * @return void
	 */
	public function woocommerce_output_product_data_accordion(): void {
		wc_get_template('single-product/tabs/accordion.php');
	}

	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	public function wrapper_before(): void {
		$default         = [
			'site-main',
		];
		$wrapper_classes = apply_filters(
			'rbb_theme_woocommerce_before_main_content',
			$default
		);
		echo '<main id="rbb-primary" class="' . esc_attr(implode(' ', $wrapper_classes)) . '">';
	}

	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	public function wrapper_after(): void {
		echo '</main><!-- #main -->';
	}

	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	public function cart_fragment( array $fragments ): array {
		$fragments['.rbb-mini-cart .cart-count'] = self::cart_count();
		if ( 'dropdown' === Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_MINI_CART_LAYOUT) ) {
			$fragments['.rbb-mini-cart .mini-cart-content-inner'] = self::mini_cart_content();
		} else {
			$fragments['.rbb-mini-cart-canvas .mini-cart-content-inner'] = self::mini_cart_content();
			$fragments['.rbb-mini-cart-canvas .cart-count']              = self::cart_count();
		}
		return $fragments;
	}

	/**
	 * Cart count.
	 *
	 * @return string
	 */
	public static function cart_count(): string {
		return '<span class="cart-count tracking-normal md:w-5 md:h-5 md:leading-[20px] w-[17px] h-[17px] leading-[17px] md:text-[0.6875rem] text-[0.625rem] absolute text-center rounded-full">' . esc_attr(WC()->cart->cart_contents_count) . '</span>';
	}

	/**
	 * Mini cart content.
	 *
	 * @return string
	 */
	public static function mini_cart_content(): string {
		ob_start();
		woocommerce_mini_cart();
		$result = ob_get_clean();
		return '<div class="mini-cart-content-inner">' . $result . '</div>';
	}

	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	public function mini_cart(): void {
		get_template_part('woocommerce/cart/mini-cart', 'ajax');
	}

	/**
	 * Product Ajax Search
	 */
	public function rbb_ajax_product_search(): void {
		$json = [];
		if ( ( check_ajax_referer(\RisingBambooCore\App\App::get_nonce(), 'nonce') ) ) {
			$keyword  = isset($_POST['keyword']) ? trim(sanitize_text_field(wp_unslash($_POST['keyword']))) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$limit    = isset($_POST['limit']) ? trim(sanitize_text_field(wp_unslash($_POST['limit']))) : Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_RESULT_LIMIT); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$category = isset($_POST['product_cat']) ? trim(sanitize_text_field(wp_unslash($_POST['product_cat']))) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$args     = [
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				's'                   => $keyword,
				'posts_per_page'      => $limit,
			];
			
			if ( $category ) {
		        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$args['tax_query'] = [
					[
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $category,
					],
					[
						'taxonomy'         => 'product_visibility',
						'terms'            => [ 'exclude-from-catalog', 'exclude-from-search' ],
						'field'            => 'name',
						'operator'         => 'NOT IN',
						'include_children' => false,
					],
				];
			} else {
		        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$args['tax_query'] = [
					[
						'taxonomy'         => 'product_visibility',
						'terms'            => [ 'exclude-from-catalog', 'exclude-from-search' ],
						'field'            => 'name',
						'operator'         => 'NOT IN',
						'include_children' => false,
					],
				];
			}
			$list = new WP_Query($args);
			
			if ( $list->have_posts() ) {
				while ( $list->have_posts() ) :
					$list->the_post();
					global $product;
					$image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'shop_catalog');
					
					$json[] = [
						'product_id' => $product->get_id(),
						'name'       => $product->get_title(),
						'image'      => $image[0],
						'link'       => get_permalink($product->get_id()),
						'price'      => $product->get_price_html(),
						'rating'     => wc_get_rating_html($product->get_average_rating()),
					];
				endwhile;
			}
		}
		
		die(wp_json_encode($json));
	}

	/**
	 * Get rating html.
	 *
	 * @param string $html   HTML.
	 * @param mixed  $rating Rating.
	 * @param mixed  $count  Count.
	 * @return string
	 */
	public function product_get_rating_html( string $html, $rating, $count ): string {
		if ( empty($html) && 0 === (int) $rating ) {
			// translators: RisingBamboo.
			$label = sprintf(esc_html__('Rated %s out of 5', 'botanica'), $rating);
			$html  = '<div class="star-rating" role="img" aria-label="' . esc_attr($label) . '">' . wc_get_star_rating_html($rating, $count) . '</div>';
		}
		return $html;
	}

	/**
	 * Modify Fields.
	 *
	 * @param array  $args  Args.
	 * @param string $key   Key.
	 * @param mixed  $value Value.
	 * @return array
	 */
	public function woocommerce_form_field_args( array $args, string $key, $value ): array {
		$args['class'][]       = 'mb-7';
		$args['label_class'][] = 'rbb-address__label font-semibold text-sm mb-6 inline-block';
		$args['input_class'][] = 'rbb-address__input duration-300 outline-none w-full border px-4 h-12 rounded';
		if ( 'order_comments' === $key ) {
			$args['input_class'][] = 'border-dashed py-4 h-40 min-h-full';
		}
		return $args;
	}

	/**
	 * Minus quantity.
	 */
	public function woocommerce_before_quantity_input_field(): void {
		echo "<button type='button' class='minus'>-</button>";
	}

	/**
	 * Plus quantity.
	 */
	public function woocommerce_after_quantity_input_field(): void {
		echo "<button type='button' class='plus'>+</button>";
	}

	/**
	 * Show/Hide product excerpt.
	 *
	 * @return void
	 */
	public function product_detail_excerpt(): void {
		if ( ! Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_EXCERPT) ) {
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
		}
	}

	/**
	 * Show/Hide product sharing.
	 *
	 * @return void
	 */
	public function product_detail_sharing(): void {
		if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_SUMMARY_SHOW_SHARING) && shortcode_exists('rbb_social_share') ) {
			echo do_shortcode('[rbb_social_share popup=enable]');
		}
	}

	/**
	 * Guarantee & safe checkout.
	 *
	 * @return void
	 */
	public function guarantee_and_safe_checkout(): void {
		wc_get_template_part('single-product/guarantee');
	}

	/**
	 * Ask a question.
	 *
	 * @return void
	 */
	public function ask_a_question(): void {
		if ( RisingBambooThemeHelper::check_ask_question() ) {
			add_action(
				'woocommerce_single_product_summary',
				function () {
					wc_get_template_part('single-product/ask-question');
				},
				40
			);
		}
	}

	/**
	 * Add buy now button.
	 *
	 * @return void
	 */
	public function add_buy_now_button(): void {
		wc_get_template_part('single-product/add-to-cart/buy-it-now');
	}

	/**
	 * Change review tab.
	 *
	 * @param mixed $tabs Tabs.
	 * @return array
	 */
	public function format_review_tab( $tabs ): array {
		global $product;
		if ( isset($tabs['reviews']) ) {
			/* translators: %d:Review count */
			$tabs['reviews']['title'] = sprintf(__('Reviews <span class="text-xs font-semibold">(%d)</span>', 'botanica'), $product->get_review_count());
		}
		return $tabs;
	}

	/**
	 * Single product image wrapper class.
	 *
	 * @param array $wrapper_classes Classes.
	 * @return array
	 */
	public function single_product_image_wrapper_classes( array $wrapper_classes ): array {
		$image_layout       = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_LAYOUT);
		$thumbnail_position = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_THUMBNAIL_POSITION);
		if ( 'scroll' === $image_layout ) {
			$thumbnail_position = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_SCROLL_THUMBNAIL_POSITION);
		}
		if ( 'slider' !== $image_layout ) {
			if ( 'left' === $thumbnail_position || 'right' === $thumbnail_position ) {
				$wrapper_classes[] = 'md:col-span-6 xl:col-span-7 md:pr-[45px]';
			} else {
				$wrapper_classes[] = 'md:col-span-6 xl:col-span-6 lg:pr-[15px]';
			}
		} else {
			$thumbnail_position = false;
		}

		return $wrapper_classes;
	}

	/**
	 * Summary wrapper classes.
	 *
	 * @param array $wrapper_classes Classes.
	 * @return array
	 */
	public function product_summary_wrapper_classes( array $wrapper_classes ): array {
		$image_layout       = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_LAYOUT);
		$thumbnail_position = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_THUMBNAIL_POSITION);
		if ( 'scroll' === $image_layout ) {
			$thumbnail_position = Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_PRODUCT_DETAIL_IMAGE_SCROLL_THUMBNAIL_POSITION);
		}
		if ( 'slider' !== $image_layout ) {
			if ( 'left' === $thumbnail_position || 'right' === $thumbnail_position ) {
				$wrapper_classes[] = 'md:col-span-6 xl:col-span-5 lg:pl-[15px]';
			} else {
				$wrapper_classes[] = 'md:col-span-6 xl:col-span-6 lg:pl-[75px] md:pl-[30px]';
			}
		} else {
			$wrapper_classes[]  = 'product-slider max-w-[630px] mx-auto text-center';
			$thumbnail_position = false;
		}
		return $wrapper_classes;
	}

	/**
	 * Setup product catalog filter.
	 *
	 * @return void
	 */
	public function product_catalog_filter_setup(): void {
		$position = Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION);
		switch ( $position ) {
			case 'left':
			case 'right':
				add_action('woocommerce_before_main_content', [ $this, 'render_filter_sidebar' ], 20);
				add_filter(
					'rbb_theme_sidebar_shop_filter',
					function ( $wrapper_classes ) use ( $position ) {
						if ( $this->show_product_filters() ) {
							$wrapper_classes[] = 'lg:w-[25%] md:w-[35%] w-full md:py-[100px] pb-[100px]';
							$wrapper_classes[] = ( 'left' === $position ) ? 'lg:pr-[30px] md:pr-[30px]' : 'lg:pl-[30px] md:pl-[30px]';
						}
						return $wrapper_classes;
					}
				);
				add_filter(
					'rbb_theme_woocommerce_before_main_content',
					function ( $wrapper_classes ) use ( $position ) {
						if ( $this->show_product_filters() && ( is_product_category() || is_shop() || is_product_tag() ) ) {
							$wrapper_classes[] = 'lg:flex grid';
							if ( 'right' === $position ) {
								$wrapper_classes[] = 'flex-row-reverse';
							}
						}
						return $wrapper_classes;
					}
				);
				add_filter(
					'rbb_theme_archive_product_classes',
					function ( $wrapper_classes ) {
						if ( $this->show_product_filters() ) {
							$wrapper_classes[] = 'category-right lg:w-[75%] md:w-[65%] w-full';
						}
						return $wrapper_classes;
					}
				);
				break;
			case 'top':
				add_action('woocommerce_before_main_content', [ $this, 'render_filter_sidebar' ], 20);
				break;
			case 'canvas_left':
			case 'canvas_right':
			case 'canvas_top':
			case 'canvas_bottom':
				add_action('woocommerce_before_main_content', [ $this, 'render_filter_title' ], 20);
				add_action('wp_footer', [ $this, 'render_filter_sidebar' ]);
				add_filter(
					'rbb_theme_sidebar_shop_filter',
					function ( $wrapper_classes ) use ( $position ) {
						if ( $this->show_product_filters() ) {
							$wrapper_classes[] = 'rbb-modal';
						}
						return $wrapper_classes;
					}
				);
				break;
		}
	}

	/**
	 * Setup layout type for catalog.
	 *
	 * @param array $wrapper_classes Wrapper classes.
	 * @return array
	 */
	public function product_catalog_layout_setup( array $wrapper_classes ): array {
		$product_catalog_layout_type = Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_LAYOUT_TYPE) ?? 'container';
		if ( is_product_category() || is_shop() || is_product_tag() ) {
			if ( 'container' === $product_catalog_layout_type ) {
				$wrapper_classes[] = 'container';
				$wrapper_classes[] = 'mx-auto';
			} else {
				$wrapper_classes[] = 'xl:mx-[90px] mx-[15px]';
				$wrapper_classes[] = 'layout-' . $product_catalog_layout_type;
			}
		}
		return $wrapper_classes;
	}

	/**
	 * Render filter sidebar.
	 *
	 * @return void
	 */
	public function render_filter_sidebar(): void {
		$position = Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION);
		$classes  = ( true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER) ) ? 'backdrop' : 'backdrop-none';
		if ( $this->show_product_filters() && is_active_sidebar('sidebar-shop-filter') && ( is_product_category() || is_shop() || is_product_tag() ) ) {
			$wrapper_classes = apply_filters(
				'rbb_theme_sidebar_shop_filter',
				[
					'rbb-sidebar-shop-filter ' . $classes . '',
					'rbb-sidebar-shop-filter-' . str_replace('_', '-', Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION)),
				]
			);
			?>
			<?php if ( 'left' === $position || 'right' === $position || 'top' === $position ) { ?>
				<h2 class="sidebar-filter mt-[100px] text-xl lg:hidden uppercase">
					<i class="rbb-icon-settings-outline2 text-sm mr-2"></i>
					<?php echo esc_html__('Filter By', 'botanica'); ?>
				</h2>
			<?php } ?>
			<div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
				<h2 class="rbb-sidebar-shop-filter-title whitespace-nowrap md:pt-2 pb-[23px] text-xl uppercase flex items-center">
					<i class="rbb-icon-settings-outline2 text-sm mr-2"></i>
					<?php echo esc_html__('Filter By', 'botanica'); ?>
				</h2>
				<?php
				if ( 'left' === $position || 'right' === $position || 'top' === $position ) {
					if ( 'top' === $position ) {
						echo '<div class="w-full">';
						dynamic_sidebar('sidebar-shop-filter');
						echo '</div>';
					}
					?>
					<div class="overflow-hidden w-full">
						<?php do_action('rbb_filter_active_bar'); ?>
					</div>
					<?php
				}
				if ( 'top' !== $position ) {
					dynamic_sidebar('sidebar-shop-filter');
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Render filter title.
	 *
	 * @return void
	 */
	public function render_filter_title(): void {
		if ( $this->show_product_filters() && is_active_sidebar('sidebar-shop-filter') && ( is_product_category() || is_shop() ) ) {
			$wrapper_filter = apply_filters(
				'rising_bamboo_filter',
				[
					'md:pt-[145px] pt-[100px] lg:flex overflow-hidden',
					'rbb-shop-filter-' . str_replace('_', '-', Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION)),
				]
			);
			$wrapper_title  = apply_filters(
				'rising_bamboo_title',
				[
					'rbb-filter-title float-left inline-block mr-2.5 mb-2.5 px-5',
					'whitespace-nowrap text-sm uppercase text-[#222] cursor-pointer',
				]
			);
			$wrapper_class  = apply_filters(
				'rising_bamboo_onclick',
				[
					'.rbb-sidebar-shop-filter-' . str_replace('_', '-', Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION)),
				]
			);
			?>
			<div class="<?php echo esc_attr(implode(' ', $wrapper_filter)); ?>">
				<h2 class="<?php echo esc_attr(implode(' ', $wrapper_title)); ?>"
					onclick="RisingBambooModal.modal('<?php echo esc_attr(implode(' ', $wrapper_class)); ?>', event) ">
					<i class="rbb-icon-settings-outline2 text-sm mr-2"></i>
					<?php echo esc_html__('Filter By', 'botanica'); ?>
				</h2>
				<?php do_action('rbb_filter_active_bar'); ?>
			</div>
			<?php
		}
	}

	/**
	 * Show/Hide product filters.
	 *
	 * @return bool
	 */
	public function show_product_filters(): bool {
		$display = woocommerce_get_loop_display_mode();
		return 'subcategories' !== $display;
	}

	/**
	 * Show categories in shop or category.
	 *
	 * @return void
	 */
	public function show_shop_archive_categories(): void {
		$display = woocommerce_get_loop_display_mode();
		if ( 'both' === $display ) {
			wc_get_template_part('content', 'categories-loop');
		}
	}

	/**
	 * Show product categories in loop product
	 *
	 * @param mixed $loop_html HTML.
	 * @return mixed|string
	 */
	public function show_product_categories_loop( $loop_html ) {
		if ( wc_get_loop_prop('is_shortcode') && ! WC_Template_Loader::in_content_filter() ) {
			return $loop_html;
		}

		$display_type = woocommerce_get_loop_display_mode();

		if ( 'subcategories' === $display_type ) {
			ob_start();
			wc_get_template_part('content', 'categories-loop');
			$loop_html .= ob_get_clean();

			wc_set_loop_prop('total', 0);

			// This removes pagination and products from display for themes not using wc_get_loop_prop in their product loops.  @todo Remove in future major version.
			global $wp_query;

			if ( $wp_query->is_main_query() ) {
				$wp_query->post_count    = 0;
				$wp_query->max_num_pages = 0;
			}
		}

		return $loop_html;
	}
	
	/**
	 * Remove redundant aria_describedby. WooCommerce has an incorrect aria-describedby attribute: it uses a string instead of a list ID.
	 * We need to remove it to fix HTML validation at https://validator.w3.org.
	 *
	 * @param mixed $args Args.
	 * @return mixed
	 */
	public function remove_redundant_aria_describedby( $args ) {
		if ( isset($args['attributes']['aria-describedby']) ) {
			unset($args['attributes']['aria-describedby']);
		}
		return $args;
	}
}
