<?php
/**
 * The footer section.
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooTheme\Customizer\Helper as RisingBambooCustomizerHelper;
use RisingBambooCore\Helper\Helper as RisingBambooCoreHelper;

RisingBambooKirki::add_section(
	RISING_BAMBOO_KIRKI_SECTION_LAYOUT_FOOTER,
	[
		'title'          => esc_html__('Footer', 'botanica'),
		'description'    => esc_html__('Theme footer.', 'botanica'),
		'panel'          => RISING_BAMBOO_KIRKI_PANEL_LAYOUT,
		'priority'       => 20,
	]
);
$priority = 1;
/**
 * The fields of this section.
 */
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_FOOTER_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_FOOTER,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Layout', 'botanica') . '</div>',
	]
);

$_layout_list = RisingBambooCustomizerHelper::get_files_assoc(get_template_directory() . '/template-parts/footers/');
if ( RisingBambooCoreHelper::elementor_activated() ) {
	$_layout_list = RisingBambooCustomizerHelper::get_elementor_footers();
}

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'select',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_LAYOUT_FOOTER,
		'label'       => esc_html__('Layout', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_LAYOUT_FOOTER,
		'default'     => RisingBambooCustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_LAYOUT_FOOTER),
		'placeholder' => esc_html__('Select a footer...', 'botanica'),
		'priority'    => $priority++,
		'multiple'    => 1,
		'choices'     => $_layout_list,
	] 
);
