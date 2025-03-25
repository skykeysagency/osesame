<?php
/**
 * Setup Wizard file.
 *
 * @package RisingBambooTheme
 */

$info = [];

$info_path = realpath(RBB_THEME_INC_PATH . 'config/theme-info.php');
if ( $info_path ) {
    //phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	$info = require $info_path;
}

return [
	'config' => [
		'dev_mode'             => false, // Enable development mode for testing.
		'license_step'         => false, // EDD license activation step.
		'license_required'     => false, // Require the license activation step.
		'license_help_url'     => '', // URL for the 'license-tooltip'.
		'edd_remote_api_url'   => '', // EDD_Theme_Updater_Admin remote_api_url.
		'edd_item_name'        => '', // EDD_Theme_Updater_Admin item_name.
		'edd_theme_slug'       => '', // EDD_Theme_Updater_Admin item_slug.
	],
	'trans' => [
		'admin-menu'               => esc_html__('Theme Setup', 'botanica'),

		/* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
		'title%s%s%s%s'            => esc_html__('%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'botanica'),
		'return-to-dashboard'      => esc_html__('Return to the dashboard', 'botanica'),
		'ignore'                   => esc_html__('Disable this wizard', 'botanica'),

		'btn-skip'                 => esc_html__('Skip', 'botanica'),
		'btn-next'                 => esc_html__('Next', 'botanica'),
		'btn-start'                => esc_html__('Start', 'botanica'),
		'btn-no'                   => esc_html__('Cancel', 'botanica'),
		'btn-plugins-install'      => esc_html__('Install', 'botanica'),
		'btn-child-install'        => esc_html__('Install', 'botanica'),
		'btn-content-install'      => esc_html__('Install', 'botanica'),
		'btn-import'               => esc_html__('Import', 'botanica'),
		'btn-license-activate'     => esc_html__('Activate', 'botanica'),
		'btn-license-skip'         => esc_html__('Later', 'botanica'),

		/* translators: Theme Name */
		'license-header%s'         => esc_html__('Activate %s', 'botanica'),
		/* translators: Theme Name */
		'license-header-success%s' => esc_html__('%s is Activated', 'botanica'),
		/* translators: Theme Name */
		'license%s'                => esc_html__('Enter your license key to enable remote updates and theme support.', 'botanica'),
		'license-label'            => esc_html__('License key', 'botanica'),
		'license-success%s'        => esc_html__('The theme is already registered, so you can go to the next step!', 'botanica'),
		'license-json-success%s'   => esc_html__('Your theme is activated! Remote updates and theme support are enabled.', 'botanica'),
		'license-tooltip'          => esc_html__('Need help?', 'botanica'),

		/* translators: Theme Name */
		'welcome-header%s'         => esc_html__('Welcome to %s', 'botanica'),
		'welcome-header-success%s' => esc_html__('Hi. Welcome back', 'botanica'),
		'welcome%s'                => esc_html__('This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'botanica'),
		'welcome-success%s'        => esc_html__('You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'botanica'),

		'child-header'             => esc_html__('Install Child Theme', 'botanica'),
		'child-header-success'     => esc_html__('You\'re good to go!', 'botanica'),
		'child'                    => esc_html__('Let\'s build & activate a child theme so you may easily make theme changes.', 'botanica'),
		'child-success%s'          => esc_html__('Your child theme has already been installed and is now activated, if it wasn\'t already.', 'botanica'),
		'child-action-link'        => esc_html__('Learn about child themes', 'botanica'),
		'child-json-success%s'     => esc_html__('Awesome. Your child theme has already been installed and is now activated.', 'botanica'),
		'child-json-already%s'     => esc_html__('Awesome. Your child theme has been created and is now activated.', 'botanica'),

		'plugins-header'           => esc_html__('Install Plugins', 'botanica'),
		'plugins-header-success'   => esc_html__('You\'re up to speed!', 'botanica'),
		'plugins'                  => esc_html__('Let\'s install some essential WordPress plugins to get your site up to speed.', 'botanica'),
		'plugins-success%s'        => esc_html__('The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'botanica'),
		'plugins-action-link'      => esc_html__('Advanced', 'botanica'),

		'import-header'            => esc_html__('Import Content', 'botanica'),
		'import'                   => esc_html__('When creating a website from scratch, you should import "Extra Data"; otherwise, import only "Essential Data".', 'botanica'),
		'import-action-link'       => esc_html__('Advanced', 'botanica'),

		'ready-header'             => esc_html__('All done. Have fun!', 'botanica'),

		/* translators: Theme Author */
		'ready%s'                  => esc_html__('Your theme has been all set up. Enjoy your new theme by %s.', 'botanica'),
		'ready-action-link'        => esc_html__('Extras', 'botanica'),
		'ready-link-1'             => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://wordpress.org/support/', esc_html__('Explore WordPress', 'botanica')),
		'ready-link-2'             => sprintf('<a href="%1$s" target="_blank">%2$s</a>', $info['Support'] ?? 'https://wp.risingbamboo.com', esc_html__('Get Theme Support', 'botanica')),
		'ready-link-3'             => sprintf('<a href="%1$s">%2$s</a>', admin_url('customize.php'), esc_html__('Start Customizing', 'botanica')),
	],
];
