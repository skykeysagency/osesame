<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme.
 */

use RisingBambooTheme\Helper\Helper;
use RisingBambooTheme\Helper\Setting;

$logout = wp_logout_url();
if ( is_user_logged_in() ) {
	?>
<div class="rbb-account-links border-t-[1px] border-t-[#e2e2e2] -mx-8 pt-4 flex items-center">
	<?php if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT) ) { ?>
		<div class="logout flex-grow text-left">
			<a class="pl-8 group duration-300 font-bold flex leading-6" href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>">
				<?php echo esc_html__('Logout', 'botanica'); ?>
				<span class="pl-[6px] duration-300 text-base text-[color:var(--rbb-account-button-logout-icon-color)] group-hover:text-[color:var(--rbb-general-link-hover-color)] <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON)); ?>"></span>
			</a>
		</div>
	<?php } ?>
	<?php if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_EDIT) && Helper::woocommerce_activated() ) { ?>
		<div class="edit-account">
			<a class="pl-4 pr-8 group duration-300 font-bold flex-grow text-center whitespace-nowrap" href="<?php echo esc_url(home_url('my-account/edit-account')); ?>">
				<?php echo esc_html__('Edit Account', 'botanica'); ?>
				<span class="pl-1 duration-300 text-[color:var(--rbb-account-button-edit-icon-color)] group-hover:text-[color:var(--rbb-general-link-hover-color)] <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_EDIT_ICON)); ?>"></span>
			</a>
		</div>
	<?php } ?>
</div>
<?php } else { ?>
<div class="rbb-account-links border-t-[1px] border-t-[#e2e2e2] -mx-8 pt-4 flex items-center">
	<?php if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_SHOW_BUTTON_LOGOUT) ) { ?>
		<div class="logout flex-grow text-left">
			<a class="pl-8 group duration-300 font-bold flex leading-6" href="<?php echo esc_url(home_url('my-account')); ?>">
				<?php echo esc_html__('Login', 'botanica'); ?>
				<span class="pl-[6px]  duration-300 text-base text-[color:var(--rbb-account-button-logout-icon-color)] group-hover:text-[color:var(--rbb-general-link-hover-color)] <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_BUTTON_LOGOUT_ICON)); ?>"></span>
			</a>
		</div>
	<?php } ?>
</div>
	<?php
}

