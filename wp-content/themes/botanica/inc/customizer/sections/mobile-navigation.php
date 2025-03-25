<?php
/**
 * RisingBambooTheme Package
 *
 * @package rising-bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Customizer\Helper as CustomizerHelper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;

$priority = 1;

RisingBambooKirki::add_section(
	RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION,
	[
		'title'         => esc_html__('Mobile Navigation', 'botanica'),
		'panel'         => RISING_BAMBOO_KIRKI_PANEL_COMPONENT,
		'description'   => esc_html__('This section contains configurations for Mobile Navigation. Please turn on mobile mode when configuring.', 'botanica'),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Mobile Navigation', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STATUS,
		'label'       => esc_html__('Enable', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STATUS),
		'priority'    => $priority++,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'color',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_BACKGROUND_COLOR,
		'label'           => esc_html__('Background Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_BACKGROUND_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STATUS,
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
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_TEXT_COLOR,
		'label'           => esc_html__('Text Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_TEXT_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STATUS,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'radio',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STICKY_BEHAVIOUR,
		'label'           => esc_html__('Behaviour', 'botanica'),
		'description'     => esc_html__('Behaviour of mobile navigation when you scroll down/up the page', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_MOBILE_NAVIGATION,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STICKY_BEHAVIOUR),
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
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MOBILE_NAVIGATION_STATUS,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);
