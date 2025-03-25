<?php
/**
 * The header section.
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Customizer\Helper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;

$priority = 1;
RisingBambooKirki::add_section(
	RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
	[
		'title'       => esc_html__('Header', 'botanica'),
		'description' => esc_html__('Theme header.', 'botanica'),
		'panel'       => RISING_BAMBOO_KIRKI_PANEL_LAYOUT,
		'priority'    => 10,
	]
);

$headers = Helper::get_files_assoc(get_template_directory() . '/template-parts/headers/');
/**
 * The fields of this section.
 */

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Heading', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'select',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER,
		'label'       => esc_html__('Template', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER),
		'placeholder' => esc_html__('Select a header...', 'botanica'),
		'priority'    => $priority++,
		'multiple'    => 1,
		'choices'     => $headers,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'color',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_BACKGROUND_COLOR,
		'label'       => esc_html__('Navigation background color', 'botanica'),
		'description' => esc_html__('Background color of the navigation.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_BACKGROUND_COLOR),
		'priority'    => $priority++,
		'choices'     => [
			'alpha' => true,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_LOGIN_FORM,
		'label'       => esc_html__('Login Form', 'botanica'),
		'description' => esc_html__('On/Off login form in header', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_LOGIN_FORM),
		'priority'    => $priority++,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM,
		'label'       => esc_html__('Search', 'botanica'),
		'description' => esc_html__('On/Off search form in header', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM),
		'priority'    => $priority++,
	]
);
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM_MOBILE,
		'label'       => esc_html__('Search Mobile', 'botanica'),
		'description' => esc_html__('On/Off search in menu mobile', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_SEARCH_FORM_MOBILE),
		'priority'    => $priority++,
	]
);
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_MINI_CART,
		'label'       => esc_html__('Mini Cart', 'botanica'),
		'description' => esc_html__('On/Off mini cart feature in header', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_MINI_CART),
		'priority'    => $priority++,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_WISH_LIST,
		'label'       => esc_html__('Wish List', 'botanica'),
		'description' => esc_html__('On/Off wish list feature in header', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_WISH_LIST),
		'priority'    => $priority++,
	]
);

/**
 * Sticky.
 */

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Heading Sticky', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY,
		'label'       => esc_html__('Enable', 'botanica'),
		'description' => esc_html__('On/Off header sticky feature', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY),
		'priority'    => $priority++,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'radio',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BEHAVIOUR,
		'label'           => esc_html__('Behaviour', 'botanica'),
		'description'     => esc_html__('Behaviour of header sticky when you scroll down/up the page', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'         => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BEHAVIOUR),
		'priority'        => $priority++,
		'choices'         => [
			'both' => [
				esc_html__('Both', 'botanica'),
				esc_html__('Sticky on scroll down/up', 'botanica'),
			],
			'up' => [
				esc_html__('Scroll Up', 'botanica'),
				esc_html__('Sticky on scroll up', 'botanica'),
			],
			'down' => [
				esc_html__('Scroll Down', 'botanica'),
				esc_html__('Sticky on scroll down', 'botanica'),
			],
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'slider',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_HEIGHT,
		'label'           => esc_html__('Height', 'botanica'),
		'description'     => esc_html__('Height of header sticky.', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'         => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_HEIGHT),
		'priority'        => $priority++,
		'choices'         => [
			'min'  => 0,
			'max'  => 300,
			'step' => 1,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'color',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BACKGROUND_COLOR,
		'label'           => esc_html__('Background color', 'botanica'),
		'description'     => esc_html__('Background color of header sticky.', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER,
		'default'         => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY_BACKGROUND_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_HEADER_STICKY,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);
