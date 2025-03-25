<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\Helper;

use DateTime;
use RisingBambooTheme\App\App;
use WC_Breadcrumb;
use WC_Product;
use WCV_Vendors;
use RisingBambooCore\Helper\Helper as RisingBambooCoreHelper;

/**
 * Custom template tags for this theme
 * Eventually, some functionality here could be replaced by core features.
 *
 * @package RisingBambooTheme\Helper
 */
class Tag {

	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	public static function posted_on(): void {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time('U') !== get_the_modified_time('U') ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
		/* translators: %s: post date. */
			esc_html_x('Posted on %s', 'post date', 'botanica'),
			'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Prints HTML with meta information for the current author.
	 */
	public static function posted_by(): void {
		$byline = sprintf(
		/* translators: %s: post author. */
			esc_html_x('by %s', 'post author', 'botanica'),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	public static function entry_footer(): void {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(esc_html__(', ', 'botanica'));
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'botanica') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'botanica'));
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'botanica') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
					/* translators: %s: post title */
						__('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'botanica'),
						[
							'span' => [
								'class' => [],
							],
						]
					),
					wp_kses_post(get_the_title())
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
					__('Edit <span class="screen-reader-text">%s</span>', 'botanica'),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				wp_kses_post(get_the_title())
			),
			'<span class="edit-link">',
			'</span>'
		);
	}

	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * @param mixed $size Size.
	 * @param array $attr Attributes.
	 */
	public static function post_thumbnail( $size = 'post-thumbnail', array $attr = [] ): void {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail($size, $attr); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					$size,
					wp_parse_args(
						$attr,
						[
							'alt' => the_title_attribute(
								[
									'echo' => false,
								]
							),
						]
					)
				);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}

	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	public static function wp_body_open(): void {
		do_action('wp_body_open');
	}

	/**
	 * Get login form.
	 *
	 * @return string | null
	 */
	public static function login_form(): ?string {
		ob_start();
		get_template_part('template-parts/account/login-form');
		return ob_get_clean();
	}

	/**
	 * Add Search.
	 *
	 * @param bool $overlay Config overlay.
	 */
	public static function search( bool $overlay = false ): void {
		if ( $overlay ) {
			add_action('wp_footer', [ self::class, 'overlay_search_form' ], 9);
		} else {
			self::search_form();
			add_action('wp_footer', [ self::class, 'search_result' ]);
		}
	}

	/**
	 * Overlay Form.
	 */
	public static function overlay_search_form(): void {
		ob_start();
		get_template_part('template-parts/components/search/overlay-form');
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Search Form.
	 *
	 * @return void
	 */
	public static function search_form(): void {
		ob_start();
		get_template_part('template-parts/components/search/form');
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ob_get_clean();
	}

	/**
	 * Search Result.
	 *
	 * @return void
	 */
	public static function search_result(): void {
		ob_start();
		get_template_part('template-parts/components/search/search-result');
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo ob_get_clean();
	}

	/**
	 * Page Title.
	 */
	public static function page_title(): void {
		if ( is_category() ) {
			single_cat_title();
		} elseif ( class_exists('WCV_Vendors') && WCV_Vendors::is_vendor_page() ) {
			$vendor_shop = urldecode(get_query_var('vendor_shop'));
			$vendor_id   = WCV_Vendors::get_vendor_id($vendor_shop);
			$shop_name   = WCV_Vendors::get_vendor_shop_name(stripslashes($vendor_id));
			echo esc_html($shop_name);
		} elseif ( class_exists('WeDevs_Dokan') && dokan()->vendor->get(get_query_var('author')) && ! empty(get_query_var('author')) ) {
			$store_user = dokan()->vendor->get(get_query_var('author'));
			$shop_name  = $store_user->get_shop_name();
			echo esc_html($shop_name);
		} elseif ( is_archive() && is_author() ) {
			esc_html_e('Posts by " ', 'botanica') . the_author() . esc_html_e(' " ', 'botanica');
		} elseif ( function_exists('is_shop') && is_shop() ) {
			esc_html_e('Shop', 'botanica');
		} elseif ( is_archive() && ! is_search() ) {
			single_term_title();
		} elseif ( is_search() ) {
			// translators: Search for.
			printf(esc_html__('Search for: %s', 'botanica'), get_search_query());
		} elseif ( is_404() ) {
			esc_html_e('404 Error', 'botanica');
		} elseif ( is_singular('knowledge') ) {
			esc_html_e('Knowledge Base', 'botanica');
		} elseif ( is_home() ) {
			esc_html_e('Posts', 'botanica');
		} else {
			echo get_the_title();
		}
	}

	/**
	 * Breadcrumb.
	 *
	 * @return void
	 */
	public static function breadcrumb(): void {
		if ( function_exists('is_woocommerce') && is_woocommerce() ) {
			if ( class_exists('WCV_Vendors') && WCV_Vendors::is_vendor_page() ) {
				get_template_part('template-parts/breadcrumb');
			} else {
				self::woocommerce_breadcrumb();
			}
		} else {
			get_template_part('template-parts/breadcrumb');
		}
	}

	/**
	 * Woocommerce Breadcrumb.
	 *
	 * @param array $args Args.
	 * @return void
	 */
	public static function woocommerce_breadcrumb( array $args = [] ): void {
		$args        = wp_parse_args(
			$args,
			apply_filters(
				'woocommerce_breadcrumb_defaults', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				[
					'delimiter'   => '<span class="delimiter px-2.5 rbb-icon-direction-39"></span>',
					'wrap_before' => '<div class="rbb-breadcrumb text-xs text-center">',
					'wrap_after'  => '</div>',
					'before'      => '',
					'after'       => '',
					'home'        => _x('Home', 'breadcrumb', 'botanica'),
				]
			)
		);
		$breadcrumbs = new WC_Breadcrumb();
		if ( $args['home'] ) {
			$breadcrumbs->add_crumb($args['home'], apply_filters('woocommerce_breadcrumb_home_url', home_url())); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		}
		$args['breadcrumb'] = $breadcrumbs->generate();
		wc_get_template('global/breadcrumb.php', $args);
	}

	/**
	 * Get link of logo.
	 *
	 * @param string $type Type of logo.
	 * @return mixed
	 */
	public static function get_logo_uri( $type = 'default' ) {
		$id  = self::get_id();
		$url = get_post_meta($id, 'rbb_logo', true);
		if ( empty($url) ) {
			$mode         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_LOGO_MODE);
			$logo_field   = RISING_BAMBOO_KIRKI_FIELD_LOGO_DARK;
			$sticky_field = RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_DARK;
			if ( 'light' === $mode ) {
				$logo_field   = RISING_BAMBOO_KIRKI_FIELD_LOGO_LIGHT;
				$sticky_field = RISING_BAMBOO_KIRKI_FIELD_LOGO_STICKY_LIGHT;
			}
			$uri = Setting::get($logo_field);
			if ( 'sticky' === $type ) {
				$uri = Setting::get($sticky_field) ?: $uri;
			}
			$url = $uri['url'];
		}
		return $url;
	}

	/**
	 * Get Header Layout.
	 *
	 * @return mixed|string
	 */
	public static function get_header() {
		$global_header = Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER);
		$id            = self::get_id();
		return get_post_meta($id, 'rbb_layout_header', true) ?: $global_header;
	}

	/**
	 * Get Footer Layout.
	 *
	 * @return mixed|string
	 */
	public static function get_footer() {
		$global_footer = Setting::get(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_FOOTER);
		$id            = self::get_id();
		return get_post_meta($id, 'rbb_layout_footer', true) ?: $global_footer;
	}

	/**
	 * Get current ID.
	 *
	 * @return false|int|mixed|void
	 */
	public static function get_id() {
		return ( is_home() ) ? get_option('page_for_posts') : get_the_ID();
	}

	/**
	 * Parse Breadcrumb.
	 *
	 * @param string $delimiter_class Class of delimiter.
	 * @param string $current_class Current class.
	 * @return string
	 */
	public static function parse_breadcrumb( string $delimiter_class, string $current_class ): string {
		global $post, $wp_query;
		$delimiter = wp_kses('<span class="' . $delimiter_class . '"></span>', 'rbb-kses');
		$before    = "<span class='" . $current_class . "'>";
		$after     = '</span>';
		$result    = $delimiter;
		if ( is_category() ) {
			$term_cat = $wp_query->get_queried_object();
			if ( 0 !== $term_cat->category_parent ) {
				$result .= get_category_parents($term_cat->category_parent, true, $delimiter);
			}
			$result .= wp_kses($before, 'rbb-kses') . esc_html(single_cat_title('', false)) . $after;
		} elseif ( class_exists('WCV_Vendors') && WCV_Vendors::is_vendor_page() ) {
			$shop      = urldecode(get_query_var('vendor_shop'));
			$vendor_id = WCV_Vendors::get_vendor_id($shop);
			$shop_name = WCV_Vendors::get_vendor_shop_name(stripslashes($vendor_id));
			$result   .= wp_kses($before, 'rbb-kses') . esc_html($shop_name) . $after;
		} elseif ( class_exists('WeDevs_Dokan') && dokan()->vendor->get(get_query_var('author'))->data ) {
			$author    = dokan()->vendor->get(get_query_var('author'));
			$shop_name = $author->get_shop_name();
			$result   .= wp_kses($before, 'rbb-kses') . esc_html($shop_name) . $after;
		} elseif ( is_day() ) {
			$result .= '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a>' . $delimiter;
			$result .= '<a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . esc_html(get_the_time('F')) . '</a>' . $delimiter;
			$result .= wp_kses($before, 'rbb-kses') . esc_html__('Archive by date ', 'botanica') . '"' . esc_html(get_the_time('d')) . '"' . $after;
		} elseif ( is_month() ) {
			$result .= '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a>' . $delimiter;
			$result .= wp_kses($before, 'rbb-kses') . esc_html__('Archive by month ', 'botanica') . '"' . esc_html(get_the_time('F')) . '"' . $after;
		} elseif ( is_year() ) {
			$week                 = get_query_var('w');
			$year                 = get_the_time('Y');
			$dto                  = new DateTime();
			$week_start           = $dto->setISODate($year, $week)->getTimestamp();
			$week_start_formatted = date_i18n(get_option('date_format'), $week_start);
			$week_end             = $dto->modify('+6 days')->getTimestamp();
			$week_end_formatted   = date_i18n(get_option('date_format'), $week_end);
			if ( 0 !== $week ) {
				$result .= wp_kses($before, 'rbb-kses') . esc_html__('Archive by year ', 'botanica') . '"' . esc_html($week_start_formatted . ' - ' . $week_end_formatted) . '"' . $after;
			} else {
				$result .= wp_kses($before, 'rbb-kses') . esc_html__('Archive by week ', 'botanica') . '"' . esc_html($year) . '"' . $after;
			}
		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() !== 'post' ) {
				$post_type     = get_post_type_object(get_post_type());
				$slug          = $post_type->rewrite;
				$post_type_url = $slug ? esc_url(home_url('/')) . '/' . esc_attr($slug['slug']) : get_post_type_archive_link($post_type->name);
				if ( $post_type_url ) {
					$result .= '<a href="' . $post_type_url . '/">' . esc_html($post_type->labels->singular_name) . '</a>' . $delimiter;
				}
				$result .= wp_kses($before, 'rbb-kses') . esc_html(get_the_title()) . $after;
			} else {
				$cat = get_the_category();
				if ( ! empty($cat) ) {
					$cat     = $cat[0];
					$result .= get_category_parents($cat, true, $delimiter);
				}
				$result .= wp_kses($before, 'rbb-kses') . get_the_title() . $after;
			}
		} elseif ( is_attachment() ) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = [];
			while ( $parent_id ) {
				$page          = get_post($parent_id);
				$breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
				$parent_id     = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ( $breadcrumbs as $crumb ) {
				$result .= wp_kses($crumb, 'rbb-kses') . $delimiter;
			}
			$result .= wp_kses($before, 'rbb-kses') . esc_html(get_the_title()) . $after;
		} elseif ( is_page() ) {
			if ( $post->post_parent ) {
				$parent_id   = $post->post_parent;
				$breadcrumbs = [];
				while ( $parent_id ) {
					$page          = get_post($parent_id);
					$breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
					$parent_id     = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ( $breadcrumbs as $crumb ) {
					$result .= wp_kses($crumb, 'rbb-kses') . $delimiter;
				}
			}
			$result .= wp_kses($before, 'rbb-kses') . esc_html(get_the_title()) . $after;
		} elseif ( is_search() ) {
			$result .= wp_kses($before, 'rbb-kses') . esc_html__('Search results for : ', 'botanica') . '"' . esc_html(get_search_query()) . '"' . $after;
		} elseif ( is_tag() ) {
			$result .= wp_kses($before, 'rbb-kses') . esc_html__('Archive by tag ', 'botanica') . '"' . esc_html(single_tag_title('', false)) . '"' . $after;
		} elseif ( is_author() ) {
			global $author;
			$user_data = get_userdata($author);
			$result   .= wp_kses($before, 'rbb-kses') . esc_html__(' Articles posted by ', 'botanica') . '"' . esc_html($user_data->display_name) . '"' . $after;
		} elseif ( is_404() ) {
			$result .= wp_kses($before, 'rbb-kses') . esc_html__(' Error 404 not Found ', 'botanica') . $after;
		} elseif ( ! is_single() && ! is_page() && get_post_type() !== 'post' && ! is_404() ) {
			$post_type = get_post_type_object(get_post_type());
			if ( $post_type ) {
				$result .= wp_kses($before, 'rbb-kses') . esc_html($post_type->labels->singular_name) . $after;
			}
		} else {
			$result .= wp_kses($before, 'rbb-kses') . esc_html__('Blog', 'botanica') . $after;
		}
		return $result;
	}

	/**
	 * Render compare link.
	 *
	 * @param \WC_Product $product Product.
	 * @return string
	 */
	public static function compare_button( WC_Product $product ): string {
		$product_id = $product->get_id();
		$html       = '';
		if ( class_exists('WPCleverWoosc') ) {
			$html .= '<div class="wooscp-compare mb-1 font-normal" data-title="' . esc_html__('Compare', 'botanica') . '">';
			$html .= do_shortcode('[woosc id=' . esc_attr($product_id) . ']');
			$html .= '</div>';
		}
		return wp_kses_post($html);
	}

	/**
	 * Wish list.
	 *
	 * @param \WC_Product $product Product.
	 * @return string
	 */
	public static function wish_list_button( WC_Product $product ): string {
		$product_id = $product->get_id();
		$html       = '';
		if ( RisingBambooCoreHelper::woocommerce_wishlist_activated() ) {
			$html .= '<div class="woosw-wishlist" data-title="' . esc_html__('Wishlist', 'botanica') . '">';
			$html .= do_shortcode('[woosw id=' . esc_attr($product_id) . ']');
			$html .= '</div>';
		}
		return wp_kses_post($html);
	}

	/**
	 * Quick view button.
	 *
	 * @param \WC_Product $product Product.
	 * @return string
	 */
	public static function quick_view_button( WC_Product $product ): string {
		$html = '';
		if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_STATUS) ) {
			$icon = '<i class=" w-[45px] h-[45px] text-center rounded-full block ' . Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_QUICK_VIEW_ICON) . '"></i>';
			$html = '<span class="product_quick-view mb-1 md:flex hidden"><a href="#" data-title="' . esc_html__('Quick View', 'botanica') . '" data-product-id="' . esc_attr($product->get_id()) . '" class="quick-view quick-view-button quick-view-' . esc_attr($product->get_id()) . '" >' . apply_filters('out_of_stock_add_to_cart_text', 'Quick View') . $icon . '</a></span>'; //  phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		}
		return wp_kses_post($html);
	}
}
