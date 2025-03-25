<?php
/**
 * RisingBambooTheme Package
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Customizer\Helper as CustomizerHelper;
use RisingBambooCore\Kirki\Kirki as RisingBambooKirki;
use RisingBambooTheme\Helper\Helper;

$priority = 1;

RisingBambooKirki::add_section(
	RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
	[
		'title'         => esc_html__('Account', 'botanica'),
		'panel'         => RISING_BAMBOO_KIRKI_PANEL_COMPONENT,
		'description'   => esc_html__('This section contains advanced all configurations for Account.', 'botanica'),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'     => 'custom',
		'settings' => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_GROUP_TITLE . '_' . ( $priority++ ),
		'section'  => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'priority' => $priority++,
		'default'  => '<div class="rising-bamboo-kirki-separator">' . esc_html__('Header Account', 'botanica') . '</div>',
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP,
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'label'       => esc_html__('Popup', 'botanica'),
		'description' => esc_html__('Show login/register form as popup.', 'botanica'),
		'priority'    => $priority++,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'rbb-icons',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON,
		'label'       => esc_html__('Icon', 'botanica'),
		'description' => esc_html__('Choose the wish list icon ?', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON),
		'priority'    => $priority++,
		'choices'     => Helper::get_rbb_icons('human'),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_SIZE,
		'label'       => esc_html__('Icon Size', 'botanica'),
		'description' => esc_html__('Unit : Pixel', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_SIZE),
		'priority'    => $priority++,
		'choices'     => [
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'color',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_COLOR,
		'label'       => esc_html__('Icon Color', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_COLOR),
		'priority'    => $priority++,
		'choices'     => [
			'alpha' => true,
		],
	] 
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER,
		'label'       => esc_html__('Icon Border', 'botanica'),
		'description' => esc_html__('Unit : Pixel', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER),
		'priority'    => $priority++,
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.5,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'dimension',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_RADIUS,
		'label'           => esc_html__('Icon Border Radius', 'botanica'),
		'description'     => __('Control <a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/CSS/border-radius"> border radius</a>.', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_RADIUS),
		'priority'        => $priority++,
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER,
				'operator' => '!==',
				'value'    => '0',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'color',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_COLOR,
		'label'           => esc_html__('Icon Border Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON_BORDER,
				'operator' => '!==',
				'value'    => '0',
			],
		],
	] 
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'color',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_CONTENT_BACKGROUND_COLOR,
		'label'       => esc_html__('Content Background Color', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_CONTENT_BACKGROUND_COLOR),
		'priority'    => $priority++,
		'choices'     => [
			'alpha' => true,
		],
	] 
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'slider',
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER,
		'label'       => esc_html__('Input Border', 'botanica'),
		'description' => esc_html__('Unit : Pixel', 'botanica'),
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER),
		'priority'    => $priority++,
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 0.5,
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'color',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_COLOR,
		'label'           => esc_html__('Input Border Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER,
				'operator' => '!==',
				'value'    => '0',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'dimension',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_RADIUS,
		'label'           => esc_html__('Input Border Radius', 'botanica'),
		'description'     => __('Control <a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/CSS/border-radius"> border radius</a>.', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER_RADIUS),
		'priority'        => $priority++,
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_INPUT_BORDER,
				'operator' => '!==',
				'value'    => '0',
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'label'       => esc_html__('Show Edit Account', 'botanica'),
		'description' => esc_html__('Show/Hide edit account button.', 'botanica'),
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_EDIT,
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'priority'    => $priority++,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_EDIT),
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'rbb-icons',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON,
		'label'           => esc_html__('Edit Account Icon', 'botanica'),
		'description'     => esc_html__('Choose the icon ?', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON),
		'priority'        => $priority++,
		'choices'         => Helper::get_rbb_icons('edit'),
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_EDIT,
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
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON_COLOR,
		'label'           => esc_html__('Edit Account Icon Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_EDIT,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);

RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'        => 'toggle',
		'label'       => esc_html__('Show Logout', 'botanica'),
		'description' => esc_html__('Show/Hide logout button.', 'botanica'),
		'settings'    => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT,
		'section'     => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'priority'    => $priority++,
		'default'     => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT),
	]
);
$logout_icon = [];
for ( $i = 1; $i <= 10; $i++ ) {
	$logout_icon[] = 'rbb-icon-account-logout-' . $i;
}
RisingBambooKirki::add_field(
	RISING_BAMBOO_KIRKI_CONFIG,
	[
		'type'            => 'rbb-icons',
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON,
		'label'           => esc_html__('Logout Icon', 'botanica'),
		'description'     => esc_html__('Choose the icon ?', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON),
		'priority'        => $priority++,
		'choices'         => $logout_icon,
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT,
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
		'settings'        => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON_COLOR,
		'label'           => esc_html__('Logout Icon Color', 'botanica'),
		'section'         => RISING_BAMBOO_KIRKI_SECTION_COMPONENT_ACCOUNT,
		'default'         => CustomizerHelper::get_default(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON_COLOR),
		'priority'        => $priority++,
		'choices'         => [
			'alpha' => true,
		],
		'active_callback' => [
			[
				'setting'  => RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT,
				'operator' => '==',
				'value'    => true,
			],
		],
	]
);
