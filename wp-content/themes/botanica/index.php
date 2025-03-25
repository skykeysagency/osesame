<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;

if ( ! class_exists(App::class) ) {
	return;
}

get_header();
$rising_bamboo_check_is_elementor_theme_exist = function_exists('elementor_theme_do_location');

if ( is_singular() ) {
	if ( ! $rising_bamboo_check_is_elementor_theme_exist || ! elementor_theme_do_location('single') ) {
		get_template_part('template-parts/single/single', get_post_type());
	}
} elseif ( is_category() ) {
	if ( ! $rising_bamboo_check_is_elementor_theme_exist || ! elementor_theme_do_location('category') ) {
		get_template_part('template-parts/category');
	}
} elseif ( is_archive() || is_home() ) {
	if ( ! $rising_bamboo_check_is_elementor_theme_exist || ! elementor_theme_do_location('archive') ) {
		get_template_part('template-parts/archive/archive', get_post_type());
	}
} elseif ( is_search() ) {
	if ( ! $rising_bamboo_check_is_elementor_theme_exist || ! elementor_theme_do_location('archive') ) {
		get_template_part('template-parts/search');
	}
} elseif ( ! $rising_bamboo_check_is_elementor_theme_exist || ! elementor_theme_do_location('single') ) {

	get_template_part('template-parts/404');
}
get_sidebar();
get_footer();
