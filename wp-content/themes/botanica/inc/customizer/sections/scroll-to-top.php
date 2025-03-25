<?php
/**
 * RisingBambooTheme Package.
 *
 * @package rising-bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Customizer\Helper as CustomizerHelper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooTheme\Helper\Helper;

$priority = 1;

RisingBambooKirki::add_section(
	RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP,
	[
		'title'         => esc_html__('Scroll To Top', 'botanica'),
		'panel'         => RISING_BAMBOO_KIRKI_PANEL_COMPONENT,
		'description'   => esc_html__('This section contains advanced configurations for "Scroll To Top".', 'botanica'),
	]
);

/**
 * Scroll to top.
 */

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Scroll To Top', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'toggle',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP,
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP,
		'label'    => esc_html__('Enable', 'botanica'),
		'priority' => $priority++,
		'default'  => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'rbb-icons',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON,
		'label'           => esc_html__('Icon', 'botanica'),
		'description'     => esc_html__('Choose the icon ?', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON),
		'priority'        => $priority++,
		'choices'         => [
			'rbb-icon-direction-7', 
			'rbb-icon-direction-15',
			'rbb-icon-direction-19', 
			'rbb-icon-direction-23',
			'rbb-icon-direction-27',
			'rbb-icon-direction-35',
			'rbb-icon-direction-43', 
			'rbb-icon-direction-51',
			'rbb-icon-direction-59', 
			'rbb-icon-direction-63',
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP,
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
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_SIZE,
		'label'           => esc_html__('Icon Size', 'botanica'),
		'description'     => esc_html__('Unit : pixel', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_SIZE),
		'priority'        => $priority++,
		'choices'         => [
			'min'  => 5,
			'max'  => 50,
			'step' => 1,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP,
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
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_COLOR,
		'label'           => esc_html__('Icon Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP,
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
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_BACKGROUND_COLOR,
		'label'           => esc_html__('Background Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_SCROLL_TO_TOP,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_BACKGROUND_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);
