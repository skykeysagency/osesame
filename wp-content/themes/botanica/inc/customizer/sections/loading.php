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
	RISING_BAMBOO_KIRKI_SECTION_COMPONENT_LOADING,
	[
		'title'         => esc_html__('Loading', 'botanica'),
		'panel'         => RISING_BAMBOO_KIRKI_PANEL_COMPONENT,
		'description'   => esc_html__('This section contains advanced configurations for "Loading ...".', 'botanica'),
	]
);

/**
 * Loading...
 */

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_LOADING,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Loading', 'botanica') . '</div>',
	]
);
$block_loading_preset = CustomizerHelper::get_files_assoc(get_template_directory() . '/dist/images/loading/block', 7, '.svg');
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'select',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BLOCK,
		'label'       => esc_html__('Block Loading', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_LOADING,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BLOCK),
		'placeholder' => esc_html__('Select an effect...', 'botanica'),
		'priority'    => $priority++,
		'choices'     => $block_loading_preset,
	]
);
$button_loading_preset = CustomizerHelper::get_files_assoc(get_template_directory() . '/dist/images/loading/button', 9, '.svg');
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'select',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BUTTON,
		'label'       => esc_html__('Button Loading', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_LOADING,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_LOADING_BUTTON),
		'placeholder' => esc_html__('Select an effect...', 'botanica'),
		'priority'    => $priority++,
		'choices'     => $button_loading_preset,
	]
);
