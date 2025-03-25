<?php
/**
 * The header for our theme
 *
 * This is the template that displays all the <head> section and everything up until <div id="content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\Helper\Helper;
use RisingBambooTheme\Helper\Tag;
use RisingBambooTheme\Helper\Setting;

$header = Setting::get(RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER);

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
	<meta name="theme-color" content="">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php Tag::wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" tabindex="0" role="link"><?php esc_html_e('Skip to content', 'botanica'); ?></a>
	<?php
	if ( ! function_exists('elementor_theme_do_location') || ! elementor_theme_do_location('header') ) {
		get_template_part('template-parts/headers/' . Tag::get_header());
	}

	if ( Helper::show_page_title() || Helper::show_breadcrumb() ) {
		$sub_cat            = Helper::woocommerce_activated() ? woocommerce_get_loop_display_mode() : '';
		$page_title_classes = 'rbb-page-title ' . trim(esc_attr($header . ' ' . $sub_cat));
		if ( is_single() || ( Helper::woocommerce_activated() && is_product() ) ) {
			$page_title_classes .= ' page-title-single py-[30px]';
		} else {
			$page_title_classes .= ' ' . ( ( 'both' === $sub_cat && ( Helper::woocommerce_activated() && ( is_product_category() || is_shop() ) ) ) ? 'lg:py-40 py-20' : 'lg:py-40 py-[60px]' );
		}
		?>
		<div id="rbb-page-title" class="<?php echo esc_attr($page_title_classes); ?>">
			<div class="container mx-auto">
				<?php
				if ( Helper::show_page_title() && ( ! ( Helper::woocommerce_activated() && is_product() ) && ! ( Helper::woocommerce_activated() && is_single() ) ) ) {
					?>
					<h1 class="heading font-extrabold uppercase md:text-[1.875rem] text-[1rem] text-center mb-[14px]">
						<?php Tag::page_title(); ?>
					</h1>
				<?php } ?>
				<?php
				if ( Helper::show_breadcrumb() ) {
					Tag::breadcrumb();
				}
				?>

			</div>
		</div>
		<?php
	}
	?>
