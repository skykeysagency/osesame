<?php
/**
 * List Sidebars.
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;

return [
	[
		'name'          => esc_html__('Top Bar', 'botanica'),
		'id'            => 'sidebar-top',
		'class'         => 'sidebar-top',
		'description'   => esc_html__('Add widgets here.', 'botanica'),
		'before_widget' => '<div id="%1$s" class="sidebar-top-content %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title sidebar-top-title">',
		'after_title'   => '</h5>',
	],
	[
		'name'          => esc_html__('Header 9 Top', 'botanica'),
		'id'            => 'sidebar-header9-top',
		'class'         => 'sidebar-header9-top',
		'description'   => esc_html__('Add widgets here.', 'botanica'),
		'before_widget' => '<div id="%1$s" class="sidebar-header9-top-content %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title sidebar-header9-top-title">',
		'after_title'   => '</h5>',
	],
	[
		'name'          => esc_html__('Sidebar Blog Top', 'botanica'),
		'id'            => 'sidebar-blog-top',
		'class'         => 'sidebar-blog-top',
		'description'   => esc_html__('Add widgets here.', 'botanica'),
		'before_widget' => '<div id="%1$s" class="sidebar-blog-top-content %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title sidebar-blog-top-title"><i class="rbb-icon-menu-1 pr-5"></i>',
		'after_title'   => '</h5>',
	],
	[
		'name'          => esc_html__('Sidebar Blog', 'botanica'),
		'id'            => 'sidebar-blog',
		'class'         => 'sidebar-blog',
		'description'   => esc_html__('Add widgets here.', 'botanica'),
		'before_widget' => '<div id="%1$s" class="sidebar-blog-content %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="widget-title sidebar-blog-title">',
		'after_title'   => '</h5>',
	],
	[
		'name'          => esc_html__('Sidebar Shop Filter', 'botanica'),
		'id'            => 'sidebar-shop-filter',
		'class'         => 'sidebar-shop-filter',
		'description'   => esc_html__('Add shop filter widgets here.', 'botanica'),
		'before_widget' => '<div id="%1$s" class="sidebar-shop-filter-widget %2$s"><div class="sidebar-shop-filter-group">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h5 class="widget-title sidebar-shop-filter-title cursor-pointer pb-[15px]">',
		'after_title'   => '<i class="rbb-icon-direction-42 float-right text-[10px] text-[#222] leading-[23px] font-bold"></i></h5>',
	],
	[
		'name'          => esc_html__('Product Category Bottom', 'botanica'),
		'id'            => 'product-category-bottom',
		'class'         => 'product-category-bottom',
		'description'   => esc_html__('Add widgets here.', 'botanica'),
		'before_widget' => '<div id="%1$s" class="category-bottom-widget %2$s"><div class="container mx-auto overflow-hidden">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h5 class="widget-title sidebar-category-bottom  mb-7">',
		'after_title'   => '</h5>',
	],
];
