<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Customizer\Helper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;

$priority = 1;

$font_weights = [
	'200',
	'200italic',
	'300',
	'300italic',
	'regular',
	'italic',
	'500',
	'500italic',
	'600',
	'600italic',
	'700',
	'700italic',
	'800',
	'800italic',
	'900',
	'900italic',
];

RisingBambooKirki::add_section(
	RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
	[
		'title'          => esc_html__('Typography', 'botanica'),
		'description'    => esc_html__('This section contains general typography options.', 'botanica'),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority' => $priority++,
		'default'  => '<div class="desc"><strong class="risingbamboo-label-info">' . esc_html__('NOTICE: ', 'botanica') . '</strong>' . esc_html__('Other typography options for specific areas can be found within other sections. Example: For breadcrumb typography options go to the breadcrumb section.', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Body Typography', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'typography',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY,
		'label'       => esc_html__('Font family', 'botanica'),
		'description' => esc_html__('These settings control the typography for all.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'transport'   => 'auto',
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BODY),
		'choices'     => [
			'variant' => $font_weights,
		],
		'output'      => [
			[
				'element' => 'body',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Heading Typography', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'typography',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING,
		'label'       => esc_html__('Font family', 'botanica'),
		'description' => esc_html__('These settings control the typography for all heading text.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'transport'   => 'auto',
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_HEADING),
		'choices'     => [
			'variant' => $font_weights,
		],
		'output'      => [
			[
				'element' => 'h1,h2,h3,h4,h5,h6,[class*="hint--"]:after,
			.main-title,
			.title,
			.heading,
			.heading-typography,
			.elementor-accordion .elementor-tab-title a,
			.elementor-counter .elementor-counter-title
			',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => 'h1_font_size',
		'label'       => esc_html__('Font size', 'botanica'),
		'description' => esc_html__('H1', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'default'     => 3,
		'transport'   => 'auto',
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.05,
		],
		'output'      => [
			[
				'element'  => 'h1',
				'property' => 'font-size',
				'units'    => 'rem',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => 'h2_font_size',
		'description' => esc_html__('H2', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'default'     => 2.25,
		'transport'   => 'auto',
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.05,
		],
		'output'      => [
			[
				'element'  => 'h2',
				'property' => 'font-size',
				'units'    => 'rem',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => 'h3_font_size',
		'description' => esc_html__('H3', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'default'     => 1.75,
		'transport'   => 'auto',
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.05,
		],
		'output'      => [
			[
				'element'  => 'h3',
				'property' => 'font-size',
				'units'    => 'rem',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => 'h4_font_size',
		'description' => esc_html__('H4', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'default'     => 1.125,
		'transport'   => 'auto',
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.05,
		],
		'output'      => [
			[
				'element'  => 'h4',
				'property' => 'font-size',
				'units'    => 'rem',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => 'h5_font_size',
		'description' => esc_html__('H5', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'default'     => 1,
		'transport'   => 'auto',
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.05,
		],
		'output'      => [
			[
				'element'  => 'h5',
				'property' => 'font-size',
				'units'    => 'rem',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => 'h6_font_size',
		'description' => esc_html__('H6', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'default'     => 0.75,
		'transport'   => 'auto',
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.05,
		],
		'output'      => [
			[
				'element'  => 'h6',
				'property' => 'font-size',
				'units'    => 'rem',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'select',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_STRONG_WEIGHT,
		'label'       => esc_html__('Strong Tag Weight', 'botanica'),
		'description' => esc_html__('Controls font weight of &lt;strong&gt;, &lt;b&gt; tags', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_STRONG_WEIGHT),
		'transport'   => 'auto',
		'choices'     => [
			'400' => esc_html__('400 - Regular', 'botanica'),
			'500' => esc_html__('500 - Medium', 'botanica'),
			'600' => esc_html__('600 - Semi Bold', 'botanica'),
			'700' => esc_html__('700 - Bold', 'botanica'),
			'800' => esc_html__('800 - Extra Bold', 'botanica'),
			'900' => esc_html__('900 - Ultra Bold (Black)', 'botanica'),
		],
		'output'      => [
			[
				'element'  => 'b, strong',
				'property' => 'font-weight',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Buttons', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'typography',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON,
		'label'       => esc_html__('Font family', 'botanica'),
		'description' => esc_html__('These settings control the typography for buttons.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'transport'   => 'auto',
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_BUTTON),
		'choices'     => [
			'variant' => $font_weights,
		],
		'output'      => [
			[
				'element' => 'button,
                            input[type="button"],
                            input[type="reset"],
                            input[type="submit"],
                            .wp-block-button__link,
                            .rev-btn,
                            .tm-button,
                            .button,
                            .elementor-button',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Form Inputs', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'typography',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM,
		'label'       => esc_html__('Font family', 'botanica'),
		'description' => esc_html__('These settings control the typography for form inputs.', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_TYPOGRAPHY,
		'priority'    => $priority++,
		'transport'   => 'auto',
		'default'     => Helper::get_default(RISING_BAMBOO_KIRKI_FIELD_TYPOGRAPHY_FORM),
		'choices'     => [
			'variant' => $font_weights,
		],
		'output'      => [
			[
				'element' => "input[type='text'],
                            input[type='email'],
                            input[type='url'],
                            input[type='password'],
                            input[type='search'],
                            input[type='number'],
                            input[type='tel'],
                            select,
                            textarea,
                            .woocommerce .select2-container--default .select2-selection--single,
                            .woocommerce .select2-container--default .select2-search--dropdown .select2-search__field,
                            .elementor-field-group .elementor-field-textual",
			],
		],
	]
);
