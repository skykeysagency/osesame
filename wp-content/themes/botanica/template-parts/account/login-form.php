<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme.
 */

use RisingBambooTheme\App\App;
?>
<div class="modal-login md:w-[420px] mx-auto md:p-10 p-[35px] bg-white rounded-[20px]">
	<div class="login-title text-sm p-2.5 rounded-[50px] w-[230px] inline-block relative bg-[color:var(--rbb-general-button-bg-color)]">
		<div class="login_switch_title absolute h-10 bg-white rounded-[50px] duration-300 shadow-[5px_5px_8px_rgba(0,0,0,0.1)]"></div>
		<div class="inline-flex relative w-full font-bold text-black text-center leading-10">
			<div class="login_switch w-1/2 bg-transparent rounded-[40px] cursor-pointer active login-btn"><?php echo esc_html__('Login', 'botanica'); ?></div>
			<div class="login_switch w-1/2 bg-transparent rounded-[40px] cursor-pointer register-btn"><?php echo esc_html__('Register', 'botanica'); ?></div>
		</div>
	</div>
	<div id="rbb_login" class="block-form-login w-full">
		<form method="post" class="rbb-login-form">
			<div class="content">
				<div class="pt-9 pb-8 text-sm font-bold"><?php echo esc_html__('Insert your account information:', 'botanica'); ?></div>
				<?php do_action('woocommerce_login_form_start'); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>
				<div class="username mb-[19px] text-xs">
					<label class="block font-semibold mb-2.5"><?php echo esc_html__('Email address', 'botanica'); ?>&nbsp;<span class="text-red-700">*</span></label>
					<input class="input-text block rounded-[5px] h-[50px] w-full mb-2 px-5 focus:outline-none" required="required" type="text" name="username" id="username" placeholder="<?php echo esc_attr__('Enter your email', 'botanica'); ?>" />
				</div>
				<div class="password relative text-xs">
					<label class="block font-semibold mb-2.5"><?php echo esc_html__('Password', 'botanica'); ?>&nbsp;<span class="text-red">*</span></label>
					<input class="input-text block rounded-[5px] h-[50px] w-full mb-2 px-5 focus:outline-none" required="required" type="password" name="password" id="password" placeholder="<?php echo esc_attr__('Password', 'botanica'); ?>" />
				</div>
				<?php do_action('woocommerce_login_form'); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>
				<div class="remember-lost flex pt-3 text-xs">
					<div class="remember flex-grow text-left">
						<input class="hidden" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<label for="rememberme" class="inline-block relative cursor-pointer">
							<?php echo esc_html__('Remember me', 'botanica'); ?>
						</label>
					</div>
					<div class="lost-password flex-grow text-right pt-1">
						<a class="text-[color:var(--rbb-general-body-text-color)] hover:text-[color:var(--rbb-general-link-hover-color)]" href="<?php echo esc_url(wc_lostpassword_url()); ?>"><?php echo esc_html__('Lost your password?', 'botanica'); ?></a>
					</div>
				</div>
				<div class="button-login text-center mt-[19px]">
					<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
					<button type="submit" class="button text-white border-none w-full p-3 h-[50px] rounded-[5px] duration-300 !text-[0.6875rem] cursor-pointer tracking-[2px] bg-[color:var(--rbb-general-primary-color)] hover:bg-[color:var(--rbb-general-secondary-color)]" name="login" ><?php echo esc_html__('Login', 'botanica'); ?></button>
				</div>
				<?php do_action('woocommerce_login_form_end'); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>
			</div>
		</form>
	</div>
	<div id="rbb_register" class="block-form-login hidden">
		<form method="post" class="rbb-form-register register" >
			<div class="content">
				<div class="pt-9 pb-8 text-sm font-bold"><?php echo esc_html__('Create your account:', 'botanica'); ?></div>
				<?php if ( 'no' === get_option('woocommerce_registration_generate_username') ) : ?>
					<div class="mb-[19px] text-xs">
						<label class="block font-semibold mb-2.5" for="reg_username"><?php esc_html_e('Username', 'botanica'); ?>&nbsp;<span class="required">*</span></label>
						<input class="input-text block rounded-[5px] h-[50px] w-full mb-2 px-5 focus:outline-none" type="text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" placeholder="<?php echo esc_attr__('User name', 'botanica'); ?>" /><?php // @codingStandardsIgnoreLine ?>
					</div>
				<?php endif; ?>
				<div class="mb-[19px] text-xs">
					<label class="block font-semibold mb-2.5" for="reg_email"><?php esc_html_e('Email address', 'botanica'); ?>&nbsp;<span class="required text-[#ff2a2a]">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text input-text block rounded-[5px] h-[50px] w-full mb-2 px-5 focus:outline-none" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" placeholder="<?php echo esc_attr__('email', 'botanica'); ?>" /><?php // @codingStandardsIgnoreLine ?>
				</div>
				<?php if ( 'no' === get_option('woocommerce_registration_generate_password') ) : ?>
					<div class="mb-[19px] text-xs">
						<label class="block font-semibold mb-2.5" for="reg_password"><?php esc_html_e('Password', 'botanica'); ?>&nbsp;<span class="required text-[#ff2a2a]">*</span></label>
						<input class="input-text block rounded-[5px] h-[50px] w-full mb-2 px-5 focus:outline-none" type="password" name="password"  id="reg_password" autocomplete="new-password" placeholder="<?php echo esc_attr__('password', 'botanica'); ?>"/>
					</div>
				<?php else : ?>
					<div><?php esc_html_e('A link to set a new password will be sent to your email address.', 'botanica'); ?></div>
				<?php endif; ?>
				<?php
                    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				do_action('woocommerce_register_form');
				?>
				<div class="button-login text-center mt-4">
					<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
					<button class="button text-white border-none w-full p-3 h-[50px] rounded-[5px] duration-300 !text-[0.6875rem] cursor-pointer tracking-[2px] bg-[color:var(--rbb-general-primary-color)] hover:bg-[color:var(--rbb-general-secondary-color)]" type="submit" name="register" value="<?php esc_attr_e('Register', 'botanica'); ?>"><?php esc_html_e('Register', 'botanica'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>

