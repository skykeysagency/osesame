<?php
/**
 * RisingBambooTheme Package.
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\Customizer;

use RisingBambooCore\Helper\Woocommerce as WoocommerceHelper;
use RisingBambooCore\Helper\Helper as CoreHelper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooTheme\Core\Singleton;
use RisingBambooTheme\App\App;

/**
 * Rising Bamboo Theme Customizer
 *
 * @package Rising_Bamboo
 */
class Customizer extends Singleton {
	/**
	 * Construction.
	 */
	public function __construct() {
		if ( class_exists(RisingBambooKirki::class) ) {
			add_action('customize_register', [ $this, 'customize_register' ]);
			
			/**
			 * Set config scope
			 */
			RisingBambooKirki::add_config(
				RISING_BAMBOO_KIRKI_CONFIG,
				[
					'capability'    => 'edit_theme_options',
					'option_type'   => 'theme_mod',
				]
			);
			$this->load('panels');
			$this->load('sections');
			add_action('customize_controls_print_scripts', [ $this, 'add_scripts' ], 30);
		}
	}
	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param mixed $wp_customize Theme Customizer object.
	 */
	public function customize_register( $wp_customize ): void {
		// Remove sections not required - all in our main customizer options.
		$wp_customize->remove_section('nav');
		$wp_customize->remove_section('colors');
		$wp_customize->remove_section('background_image');
		$wp_customize->remove_section('header_image');
		$wp_customize->remove_control('blogdescription');
		$wp_customize->remove_control('display_header_text');

		// Reassign default sections to panels.
		if ( class_exists('Kirki') ) {
			$wp_customize->get_section('title_tagline')->panel     = RISING_BAMBOO_KIRKI_PANEL_GENERAL;
			$wp_customize->get_section('static_front_page')->panel = RISING_BAMBOO_KIRKI_PANEL_GENERAL;
		}
	}
	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @return void
	 */
	public function customize_partial_blogname(): void {
		bloginfo('name');
	}

	/**
	 * Render the site description for the selective refresh partial.
	 */
	public function customize_partial_blogdescription(): void {
		bloginfo('description');
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function customize_preview_js(): void {
		wp_enqueue_script('rising-bamboo-customizer', get_template_directory_uri() . '/dist/js/admin/customizer.js', [ 'customize-preview' ], App::$version, true);
	}

	/**
	 * Add script impove form.
	 *
	 * @return void
	 */
	public function add_scripts(): void {
		$product_detail_url = null;
		// <editor-fold desc="Woocommerce Product Detail">
		$product_has_upsell = WoocommerceHelper::get_products_has_upsell(1);
		if ( count($product_has_upsell) ) {
			$product_has_upsell = $product_has_upsell[0];
			$product_detail_url = $product_has_upsell->get_permalink();
		} else {
			$product = [];
			if ( function_exists('wc_get_products') ) {
				$product = wc_get_products(
					[
						'status'     => 'publish',
						'type'       => 'simple',
						'limit'      => 1,
						'orderby'    => 'rand',
					]
				);
			}

			if ( count($product) ) {
				$product_detail_url = $product[0]->get_permalink();
			}
		}

		if ( $product_detail_url ) {
			?>
			<script type="text/javascript">
				jQuery( function() {
					function set_product_preview(section) {
						section.expanded.bind( function( isExpanded ) {
							if ( isExpanded ) {
								wp.customize.previewer.previewUrl.set( '<?php echo esc_js($product_detail_url); ?>' );
							}
						} );
					}
					wp.customize.section('<?php echo RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_DETAIL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>', function( section ) {
						set_product_preview(section);
					} );
				} );
			</script>
			<?php
		}
		// </editor-fold>
		// <editor-fold desc="Blog Category">
		$categories = get_terms(
			[
				'taxonomy'     => 'category',
				'orderby'      => 'count',
				'order'        => 'DESC',
				'hide_empty'   => true,
				'number'       => 1,
			]
		);
		if ( $categories ) {
			$category      = $categories[0];
			$category_link = get_category_link($category->term_id);
			?>
			<script type="text/javascript">
				jQuery( function() {
					wp.customize.section( '<?php echo RISING_BAMBOO_KIRKI_SECTION_BLOG_CATEGORY; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>', function( section ) {
						section.expanded.bind( function( isExpanded ) {
							if ( isExpanded ) {
								wp.customize.previewer.previewUrl.set( '<?php echo esc_js($category_link); ?>' );
							}
						} );
					} );
				} );
			</script>
			<?php
		}
		// </editor-fold>
		// <editor-fold desc="Blog Detail">
		$posts = get_posts(
			[
				'numberposts' => 1,
				'orderby'     => 'comment_count',
				'order'       => 'DESC',
			]
		);
		if ( $posts ) {
			$post      = $posts[0];
			$post_link = get_permalink($post->ID);
			?>
			<script type="text/javascript">
				jQuery( function() {
					wp.customize.section( '<?php echo RISING_BAMBOO_KIRKI_SECTION_BLOG_DETAIL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>', function( section ) {
						section.expanded.bind( function( isExpanded ) {
							if ( isExpanded ) {
								wp.customize.previewer.previewUrl.set( '<?php echo esc_js($post_link); ?>' );
							}
						} );
					} );
				} );
			</script>
			<?php
		}
		// </editor-fold>
	}

	/**
	 * Load files.
	 *
	 * @param string $folder Folder to load.
	 */
	public function load( string $folder ): void {
		$folder = __DIR__ . DIRECTORY_SEPARATOR . $folder;
		$files  = method_exists(CoreHelper::class, 'get_files') ? CoreHelper::get_files($folder) : [];
		foreach ( $files as $file ) {
			$file = $folder . DIRECTORY_SEPARATOR . $file . '.php';
			if ( is_readable($file) ) {
				require $file; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
	}
}
