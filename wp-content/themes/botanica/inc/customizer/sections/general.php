<?php
/**
 * The Color Section in Color Panel
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Customizer\Helper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;

/**
 * General.
 */

$priority = 1;

RisingBambooKirki::add_section(
	RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
	[
		'title'       => esc_html__('Colors', 'botanica'),
		'description' => esc_html__('General colors.', 'botanica'),
		'panel'       => RISING_BAMBOO_KIRKI_PANEL_GENERAL,
		'priority'    => 160,
	]
);

// <editor-fold desc="General">
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('General', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'color',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_HEADING_COLOR,
		'label'       => __('Heading Color', 'botanica'),
		'description' => esc_html__('H1, H2 ... H6', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority'    => $priority++,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_HEADING_COLOR),
		'choices'     => [
			'alpha' => true,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'color',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_TEXT,
		'label'       => __('Body Text', 'botanica'),
		'description' => esc_html__('Set the color for the body text.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority'    => $priority++,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_TEXT),
		'choices'     => [
			'alpha' => true,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'color',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_BACKGROUND,
		'label'       => __('Background color', 'botanica'),
		'description' => esc_html__('Visible if the grid is contained.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority'    => $priority++,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BODY_BACKGROUND),
		'choices'     => [
			'alpha' => true,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'color',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_PRIMARY_COLOR,
		'label'    => __('Primary color', 'botanica'),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority' => $priority++,
		'default'  => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_PRIMARY_COLOR),
		'choices'  => [
			'alpha' => true,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'color',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_SECONDARY_COLOR,
		'label'    => __('Secondary color', 'botanica'),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority' => $priority++,
		'default'  => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_SECONDARY_COLOR),
		'choices'  => [
			'alpha' => true,
		],
	]
);
// </editor-fold>

// <editor-fold desc="Link">
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Link', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'multicolor',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_LINK,
		'label'       => __('Choose color', 'botanica'),
		'description' => esc_html__('This is a color of the link.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority'    => $priority++,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_LINK),
		'choices'     => [
			'link'  => esc_html__('Default', 'botanica'),
			'hover' => esc_html__('Hover', 'botanica'),
		],
	]
);
// </editor-fold>

// <editor-fold desc="General Button">
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('General Button', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'multicolor',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR,
		'label'       => __('Text Color', 'botanica'),
		'description' => esc_html__('This is a color of text.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority'    => $priority++,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_TEXT_COLOR),
		'choices'     => [
			'link'  => esc_html__('Default', 'botanica'),
			'hover' => esc_html__('Hover', 'botanica'),
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'multicolor',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND,
		'label'       => __('Background Color', 'botanica'),
		'description' => esc_html__('This is a color of background button.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COLOR_GENERAL,
		'priority'    => $priority++,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_COLOR_GENERAL_BUTTON_BACKGROUND),
		'choices'     => [
			'link'  => esc_html__('Default', 'botanica'),
			'hover' => esc_html__('Hover', 'botanica'),
		],
	]
);
// </editor-fold>
