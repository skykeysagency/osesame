<?php
/**
 * The default header.
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Helper;
use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Helper\Tag;
use RisingBambooTheme\App\Menu\Menu;
use RisingBambooTheme\Woocommerce\Woocommerce as RisingBambooWoo;
$modal_effect    = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT);
$outside         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE);
$backdrop_filter = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER);
$classes         = [ 'rbb-modal invisible fixed inset-0 z-50 ' ];
$classes[]       = ( true === $backdrop_filter ) ? 'backdrop' : 'backdrop-none';
$classes[]       = ( false === $outside ) ? 'outside-modal' : '';
$class_string    = implode(' ', array_filter($classes));
?>
<header id="rbb-default-header" class="rbb-default-header header-2 lg:absolute w-full z-20">
	<div class="rbb-header-sticky relative md:block hidden">
		<div class="md:container mx-auto h-full lg:pt-10 md:px-[15px] px-5">
			<div class="header-inner relative mx-auto md:h-[90px] h-[70px] lg:px-[15px] md:rounded-[90px] bg-[color:var(--rbb-header-background-color)]">
				<div class="flex flex-nowrap items-center h-full grid grid-cols-8 md:gap-4">
					<div class="rbb-header-center flex items-center md:col-span-3 col-span-2">
						<span class="menu_close w-6 h-4 overflow-hidden relative lg:ml-4 lg:mr-[30px] mr-5 cursor-pointer">
							<i class="line icon-1 top-0"></i>
							<i class="line icon-2 top-[7px]"></i>
							<i class="line icon-3 bottom-0"></i>
						</span>
						<?php
						add_action(
							'wp_footer',
							function () use ( $class_string ) {
								echo '<div class="rbb-menu-canvas ' . esc_attr($class_string) . '">';
									echo '<div class="menu-canvas-left canvas-menu w-[300px] fixed top-0 left-0 bottom-0 p-5 bg-white z-30 overflow-hidden shadow-[0px_0_15px_0_rgba(0,0,0,0.1)]">';
										$search_visibility = Helper::show_search_product_form_mobile() ? '' : ' hidden';
										echo '<div class="search_desktop md:block mt-[30px] mb-9 animate-[500ms] fadeInLeft ' . esc_attr($search_visibility) . '">';
											Tag::search(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY));
										echo '</div>';
										echo '<div id="rbb-site-navigation" class="rbb-main-navigation">';
											echo '<div id="menu-main" class="menu primary-menu relative">';
												echo Menu::primary_menu(); // phpcs:ignore
											echo '</div>';
										echo '</div>';
										echo '<div class="mt-8 mobile_bottom">';
										echo '<div class="flex mb-1.5 animate-[1500ms] fadeInLeft">';
												echo '<span class="pr-1 font-bold">';
													echo esc_html__('Call Us:', 'botanica');
												echo '</span>';
											echo do_shortcode('[rbb_contact type="phone"]');
										echo '</div>';
										echo '<div class="flex mb-[25px] animate-[1600ms] fadeInLeft">';
											echo '<span class="pr-1 font-bold">';
												echo esc_html__('Email:', 'botanica');
												echo '</span>';
											echo do_shortcode('[rbb_contact type="email"]');
										echo '</div>';
											echo '<div class="animate-[1700ms] fadeInLeft social_content">';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
						);
						?>
						<?php if ( Helper::show_search_product_form() && true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY) ) { ?>
							<span class="search-mobile text-center w-[22px] py-3 mr-2 text-[#222] cursor-pointer text-[22px] md:hidden">
								<i class="rbb-icon-search-10"></i>
							</span>
							<div class="rbb-product-search relative flex items-center justify-center cursor-pointer" onclick="RbbThemeSearch.openSearchForm(event)">
								<div class="rbb-product-search-icon-wrap justify-center items-center flex !bg-transparent <?php echo ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON_BORDER) > 0 ) ? 'h-[50px] w-[50px]' : ''; ?>">
									<span class="rbb-product-search-icon <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON)); ?>"></span>
								</div>
								<div class="rbb-product-search-content2">
									<?php Tag::search(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY)); // phpcs:ignore ?>
								</div>
							</div>
						<?php } else { ?>
							<?php if ( Helper::show_search_product_form() ) { ?>
							<div class="rbb-product-search relative flex items-center justify-center cursor-pointer">
								<div id="_desktop_search" class="rbb-product-search-content2">
									<?php Tag::search(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY)); // phpcs:ignore ?>
								</div>
							</div>
							<?php } ?>
						<?php } ?>
					</div>
					<?php
					if ( Helper::show_logo() ) {
						?>
						<div id="rbb-branding" class="rbb-branding flex-none rbb-header-left text-center md:col-span-2 col-span-4">
							<div id="_desktop_logo">
								<a class="inline-block" href="<?php echo esc_url(home_url()); ?>">
									<?php
									$logo        = Tag::get_logo_uri();
									$logo_sticky = Tag::get_logo_uri('sticky');
									if ( $logo === $logo_sticky ) {
										?>
										<img class="logo w-[var(--rbb-logo-max-width)]" src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr__('logo', 'botanica'); ?>">
										<?php
									} else {
										?>
										<img class="sticky-logo w-[var(--rbb-logo-sticky-max-width)]" src="<?php echo esc_url($logo_sticky); ?>" alt="<?php echo esc_attr__('Sticky Logo', 'botanica'); ?>">
										<img class="logo w-[var(--rbb-logo-max-width)]" src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr__('logo', 'botanica'); ?>">
									<?php } ?>
								</a>
							</div>
						</div>
					<?php } ?>
					<?php
					if ( Helper::show_login_form() || Helper::show_mini_cart() || Helper::show_wishlist() ) {
						is_super_admin()
						?>
						<div id="rbb-header-right" class="rbb-header-right flex-none h-full max-w-[390px] ml-3 flex ml-auto mr-0 md:col-span-3 col-span-2">
							<?php if ( Helper::show_login_form() ) { ?>
								<div class="rbb-account relative flex items-center justify-center mr-2.5
								<?php echo ( false === is_user_logged_in() && true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP) ) ? 'popup-account' : 'toggle-login'; ?> "
								<?php
								if ( false === is_user_logged_in() && true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP) ) {
									?>
									onclick="RisingBambooModal.modal('.account-form-popup', event)" <?php } ?>
									>
									<div class="rbb-account-icon-wrap relative duration-300 cursor-pointer bg-white h-[50px] w-[50px] flex items-center justify-center">
										<span class="rbb-account-icon cursor-pointer duration-300 <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON)); ?>"></span>
									</div>
									<div class="rbb-account-content-wrap">
										<?php
										$content = '';
										if ( false === is_user_logged_in() && true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP) ) {
											$welcome = __('Account', 'botanica');
											if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP) ) {
												add_action(
													'wp_footer',
													function () use ( $modal_effect, $class_string ) {
                                                   // phpcs:ignore
														echo '<div class="account-form-popup ' . esc_attr($class_string) . '" data-modal-animation="' . esc_attr($modal_effect) . '">' . Tag::login_form() . '</div>';
													}
												);
											}
										} else {
											$content = Menu::account_menu();
											if ( ! empty($content) ) {
												?>
											<div class="rbb-account-content duration-300 mt-14 absolute z-10 visibility invisible rounded-lg overflow-hidden opacity-0 top-full right-0 shadow-[6px_5px_11px_rgba(0,0,0,0.1)]">
												<div class="relative bg-white min-w-[220px] px-8 pt-3 pb-5">
													<?php echo wp_kses($content, 'rbb-kses'); ?>
												</div>
											</div>
												<?php
											} 
										}
										?>
									</div>
								</div>
							<?php } ?>

							<?php if ( Helper::show_wishlist() ) { ?>
								<div class="rbb-wishlist flex items-center justify-center mr-2.5">
									<div class="relative">
										<a class="wishlist-icon-link group bg-white duration-300 h-[50px] w-[50px] flex items-center justify-center text-center" aria-label="wishlist" href="<?php echo esc_url(WPcleverWoosw::get_url()); ?>">
											<span class="wishlist-icon duration-300 <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON)); ?>"></span>
										</a>
										<span class="wishlist-count text-center rounded-full"><?php echo WPcleverWoosw::get_count(); // phpcs:ignore ?></span>
									</div>
								</div>
							<?php } ?>
							<?php
							if ( Helper::show_mini_cart() ) {
								RisingBambooWoo::instance()->mini_cart();
							}
							?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="header-mobile shadow-md relative bg-white px-[15px] md:hidden">
		<div class="flex items-center justify-between max-h-[60px]">
			<div class="menu-mobile flex items-center min-w-[60px] h-[60px]">
				<span class="menu_close w-6 h-4 overflow-hidden relative lg:ml-4 lg:mr-[30px] mr-5 cursor-pointer">
					<i class="line icon-1 top-0"></i>
					<i class="line icon-2 top-[7px]"></i>
					<i class="line icon-3 bottom-0"></i>
				</span>
				<?php if ( Helper::show_search_product_form() ) { ?>
				<div class="w-[22px] text-center">
					<span class="search-mobile text-center text-black w-[18px] h-[18px] flex justify-center items-center cursor-pointer text-[22px]">
						<i class="rbb-icon-search-10"></i>
					</span>
					<div id="_mobile_search" class="product-search-mobile absolute z-10 inset-x-0 top-full w-full opacity-0 invisible">
					</div>
				</div>
				<?php } ?>
			</div>
			<div id="_mobile_logo" class="px-5 flex items-center justify-center"></div>
			<div class="header-mobile-right min-w-[60px] flex items-center justify-end">
				<div id="_mobile_cart" class="rbb-mini-cart"></div>
			</div>
		</div>
	</div>
</header><!-- #masthead -->
