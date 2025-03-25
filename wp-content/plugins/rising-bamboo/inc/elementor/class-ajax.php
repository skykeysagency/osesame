<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor;

use RisingBambooCore\App\Admin\TestimonialPostType;
use RisingBambooCore\App\App;
use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Core\View;
use RisingBambooCore\Helper\Woocommerce as WoocommerceHelper;
use WP_Query;

/**
 * Ajax control.
 */
class Ajax extends Singleton {

	/**
	 * Construct.
	 */
	public function __construct() {
		$this->load_product();
	}

	/**
	 * Load products.
	 *
	 * @return void
	 */
	public function load_product(): void {
		add_action('wp_ajax_rbb_get_product', [ $this, 'rbb_get_product' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_product', [ $this, 'rbb_get_product' ]); // nonce - ok.

		add_action('wp_ajax_rbb_get_category', [ $this, 'rbb_get_category' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_category', [ $this, 'rbb_get_category' ]); // nonce - ok.

		add_action('wp_ajax_rbb_get_products_by_category', [ $this, 'get_products_by_category' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_products_by_category', [ $this, 'get_products_by_category' ]); // nonce - ok.

		add_action('wp_ajax_rbb_get_testimonials', [ $this, 'rbb_get_testimonials' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_testimonials', [ $this, 'rbb_get_testimonials' ]); // nonce - ok.

		add_action('wp_ajax_rbb_get_posts_category', [ $this, 'rbb_get_posts_category' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_posts_category', [ $this, 'rbb_get_posts_category' ]); // nonce - ok.

		add_action('wp_ajax_rbb_get_posts', [ $this, 'rbb_get_posts' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_posts', [ $this, 'rbb_get_posts' ]); // nonce - ok.

		add_action('wp_ajax_rbb_get_posts_by_category', [ $this, 'rbb_get_posts_by_category' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_posts_by_category', [ $this, 'rbb_get_posts_by_category' ]); // nonce - ok.

		add_action('wp_ajax_rbb_get_meta_key', [ $this, 'rbb_get_meta_key' ]); // nonce - ok.
		add_action('wp_ajax_nopriv_rbb_get_meta_key', [ $this, 'rbb_get_meta_key' ]); // nonce - ok.
	}

	/**
	 * Get products.
	 *
	 * @return void
	 */
	public function rbb_get_product(): void {
		$return = [];
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			if ( isset($_POST['ids']) ) {
				$products = wc_get_products(
					[
                        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						'include' => (array) wp_unslash($_POST['ids']),
					]
				);
				foreach ( $products as $product ) {
					$return[] = [
						'id'   => $product->get_id(),
						'text' => $product->get_name() . '(ID: ' . $product->get_id() . ')',
					];
				}
			} elseif ( isset($_GET['q']) ) {
				$search_results = new WP_Query(
					[
						's'                   => sanitize_text_field(wp_unslash($_GET['q'])),
						'ignore_sticky_posts' => 1,
						'posts_per_page'      => 20,
						'post_type'           => 'product',
					]
				);

				if ( $search_results->have_posts() ) {
					while ( $search_results->have_posts() ) {
						$search_results->the_post();
						$return[] = [
							'id'   => $search_results->post->ID,
							'text' => $search_results->post->post_title . ' (ID:' . $search_results->post->ID . ')',
						];
					}
				}
			}
		}
		wp_send_json([ 'results' => $return ]);
	}

	/**
	 * Get category.
	 *
	 * @return void
	 */
	public function rbb_get_category(): void {
		$return = [];
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			if ( isset($_POST['ids']) ) {
                // phpcs:ignore
				$ids        = array_map('esc_attr', $_POST['ids']);
				$categories = WoocommerceHelper::get_categories_by_ids($ids, 'include');
				foreach ( $categories as $category ) {
					$return[] = [
						'id'   => $category->term_id,
						'text' => $category->name . '(ID: ' . $category->term_id . ')',
					];
				}
			} elseif ( isset($_GET['q']) ) {
				$search_results = get_terms(
					[
						'taxonomy'   => 'product_cat',
						'name__like' => sanitize_text_field(wp_unslash($_GET['q'])),
					]
				);
				if ( $search_results ) {
					foreach ( $search_results as $result ) {
						$return[] = [
							'id'   => $result->term_id,
							'text' => $result->name . ' (ID:' . $result->term_id . ')',
						];
					}
				}
			}
		}
		wp_send_json([ 'results' => $return ]);
	}

	/**
	 * Get product by category.
	 *
	 * @return void
	 */
	public function get_products_by_category(): void {
		$products = [];
		$fragment = 'item';
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			$ids      = isset($_POST['id']) ? (int) sanitize_text_field(wp_unslash($_POST['id'])) : [];
			$order_by = isset($_POST['order_by']) ? sanitize_text_field(wp_unslash($_POST['order_by'])) : 'latest';
			$order    = isset($_POST['order']) ? sanitize_text_field(wp_unslash($_POST['order'])) : 'desc';
			$limit    = isset($_POST['limit']) ? (int) sanitize_text_field(wp_unslash($_POST['limit'])) : -1;
			$products = WoocommerceHelper::get_products($ids, 'category', $order_by, $order, $limit);
			$fragment = isset($_POST['fragment']) ? sanitize_text_field(wp_unslash($_POST['fragment'])) : $fragment;
		}
		global $product;
		foreach ( $products as $product ) {
			View::instance()->load(
				'elementor/widgets/woo-products/fragments/' . $fragment,
				wp_parse_args(
					$_POST,
					[
						'product' => $product,
					]
				)
			);
		}
	}

	/**
	 * Get Testimonial.
	 *
	 * @return void
	 */
	public function rbb_get_testimonials(): void {
		$return = [];
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			if ( isset($_POST['ids']) ) {
				$testimonials = get_posts(
					[
                        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						'include'   => (array) wp_unslash($_POST['ids']),
						'post_type' => TestimonialPostType::POST_TYPE,
					]
				);
				foreach ( $testimonials as $testimonial ) {
					$return[] = [
						'id'   => $testimonial->ID,
						'text' => $testimonial->post_title . '(ID: ' . $testimonial->ID . ')',
					];
				}
			} elseif ( isset($_GET['q']) ) {
				$search_results = new WP_Query(
					[
						's'                   => sanitize_text_field(wp_unslash($_GET['q'])),
						'ignore_sticky_posts' => 1,
						'posts_per_page'      => 20,
						'post_type'           => TestimonialPostType::POST_TYPE,
					]
				);

				if ( $search_results->have_posts() ) {
					while ( $search_results->have_posts() ) {
						$search_results->the_post();
						$return[] = [
							'id'   => $search_results->post->ID,
							'text' => $search_results->post->post_title . ' (ID:' . $search_results->post->ID . ')',
						];
					}
				}
			}
		}
		wp_send_json([ 'results' => $return ]);
	}

	/**
	 * Get Category.
	 *
	 * @return void
	 */
	public function rbb_get_posts_category(): void {
		$return = [];
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			if ( isset($_POST['ids']) ) {
				$categories = get_categories(
					[
                        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						'include'    => (array) wp_unslash($_POST['ids']),
						'orderby'    => 'include',
						'hide_empty' => false,
					]
				);
				foreach ( $categories as $category ) {
					$return[] = [
						'id'   => $category->term_id,
						'text' => $category->name . '(ID: ' . $category->term_id . ')',
					];
				}
			} elseif ( isset($_GET['q']) ) {
				$search_results = get_categories(
					[
						'name__like' => sanitize_text_field(wp_unslash($_GET['q'])),
						'hide_empty' => false,
					]
				);

				foreach ( $search_results as $result ) {
					$return[] = [
						'id'   => $result->term_id,
						'text' => $result->name . ' (ID:' . $result->term_id . ')',
					];
				}
			}
		}
		wp_send_json([ 'results' => $return ]);
	}

	/**
	 * Get Posts.
	 *
	 * @return void
	 */
	public function rbb_get_posts(): void {
		$return = [];
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			if ( isset($_POST['ids']) ) {
				$posts = get_posts(
					[
                        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						'include' => (array) wp_unslash($_POST['ids']),
						'orderby' => 'post__in',
					]
				);
				foreach ( $posts as $post ) {
					$return[] = [
						'id'   => $post->ID,
						'text' => $post->post_title . '(ID: ' . $post->ID . ')',
					];
				}
			} elseif ( isset($_GET['q']) ) {
				$search_results = get_posts(
					[
						's' => sanitize_text_field(wp_unslash($_GET['q'])),
					]
				);

				foreach ( $search_results as $result ) {
					$return[] = [
						'id'   => $result->ID,
						'text' => $result->post_title . ' (ID:' . $result->ID . ')',
					];
				}
			}
		}
		wp_send_json([ 'results' => $return ]);
	}

	/**
	 * Get posts by category.
	 *
	 * @return void
	 */
	public function rbb_get_posts_by_category(): void {
		$posts = [];
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			$id       = isset($_POST['id']) ? (int) $_POST['id'] : 0;
			$order_by = isset($_POST['order_by']) ? sanitize_text_field(wp_unslash($_POST['order_by'])) : 'random';
			$order    = isset($_POST['order']) ? sanitize_text_field(wp_unslash($_POST['order'])) : 'desc';
			$limit    = isset($_POST['limit']) ? sanitize_text_field(wp_unslash($_POST['limit'])) : -1;
			$args     = [
				'numberposts' => $limit,
				'category'    => $id,
			];

			switch ( $order_by ) {
				case 'random':
					$args['orderby'] = 'rand';
					break;
				case 'date':
					$args['orderby'] = 'date';
					$args['order']   = $order;
					break;
				case 'relevance':
					$args['orderby'] = 'relevance';
					break;
				case 'title':
					$args['orderby'] = 'title';
					$args['order']   = $order;
					break;
				case 'comment':
					$args['orderby'] = 'comment_count';
					$args['order']   = $order;
					break;
			}
			$posts = get_posts($args);
		}

		foreach ( $posts as $_post ) {
			View::instance()->load(
				'elementor/widgets/posts/fragments/item',
				wp_parse_args(
					$_POST,
					[
						'_post' => $_post,
					]
				)
			);
		}
	}

	/**
	 * Get the meta keys.
	 *
	 * @return void
	 */
	public function rbb_get_meta_key(): void {
		global $wpdb;
		$return = [];
		if ( ( check_ajax_referer(App::get_nonce(), 'nonce') ) ) {
			if ( isset($_POST['ids']) ) {
				// phpcs:disable
				$meta_keys = implode("','",array_map('esc_attr', $_POST['ids']));
				$sql       = 'SELECT DISTINCT `meta_key` FROM `' . $wpdb->postmeta . "` WHERE `meta_key` IN ('" . $meta_keys . "')";
				$results   = $wpdb->get_results($wpdb->prepare($sql));
				foreach ( $results as $result ) {
					$return[] = [
						'id'   => $result->meta_key,
						'text' => $result->meta_key,
					];
				}
				// phpcs:enable
			} elseif ( isset($_GET['q']) ) {
				$search_key = sanitize_text_field(wp_unslash($_GET['q']));
				// phpcs:ignore
				$search_results = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT `meta_key` FROM $wpdb->postmeta WHERE meta_key LIKE %s", '%' . $search_key . '%'));
				if ( $search_results ) {
					foreach ( $search_results as $result ) {
						$return[] = [
							'id'   => $result->meta_key,
							'text' => $result->meta_key,
						];
					}
				}
			}
		}
		wp_send_json([ 'results' => $return ]);
	}
}
