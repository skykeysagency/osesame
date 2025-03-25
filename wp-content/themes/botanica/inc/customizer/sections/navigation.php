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
	RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION,
	[
		'title'         => esc_html__('Post Navigation', 'botanica'),
		'panel'         => RISING_BAMBOO_KIRKI_PANEL_BLOG,
		'description'   => esc_html__('This section contains advanced configurations for "Post Navigation".', 'botanica'),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Post Navigation', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_SINGLE,
		'label'       => esc_html__('Single Post', 'botanica'),
		'description' => esc_html__('Display navigation for single post (or attachment, or custom post type)', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_SINGLE),
		'priority'    => $priority++,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_PAGE,
		'label'       => esc_html__('Page', 'botanica'),
		'description' => esc_html__('Display navigation for page', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_PAGE),
		'priority'    => $priority++,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_ATTACHMENT,
		'label'       => esc_html__('Attachment', 'botanica'),
		'description' => esc_html__('Display navigation for attachment', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_ATTACHMENT),
		'priority'    => $priority++,
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_CUSTOM_POST_TYPE,
		'label'       => esc_html__('Other Post Type', 'botanica'),
		'description' => esc_html__('Display navigation for other post type', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_POST_NAVIGATION,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_POST_NAVIGATION_CUSTOM_POST_TYPE),
		'priority'    => $priority++,
	]
);
