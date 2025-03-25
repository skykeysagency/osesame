<?php
/**
 * The default header.
 *
 * @package Rising_Bamboo
 */

use RisingBambooCore\Woocommerce\FreeShippingCalculator;
use RisingBambooCore\App\Admin\RbbIcons;
use RisingBambooTheme\App\Menu\Menu;
use RisingBambooTheme\Helper\Helper;
use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Helper\Tag;
use RisingBambooTheme\Woocommerce\Woocommerce as RisingBambooWoo;
$modal_effect              = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_EFFECT);
$outside                   = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_CLICK_OUTSIDE_CLOSE);
$backdrop_filter           = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_MODAL_BACKDROP_FILTER);
$classes                   = [ 'account-form-popup rbb-modal invisible fixed inset-0 z-50 ' ];
$classes[]                 = ( true === $backdrop_filter ) ? 'backdrop' : 'backdrop-none';
$classes[]                 = ( false === $outside ) ? 'outside-modal' : '';
$class_string              = implode(' ', array_filter($classes));
$free_shipping_calculators = class_exists(FreeShippingCalculator::class) ? FreeShippingCalculator::instance() : false;
if ( $free_shipping_calculators ) {
	$min_amount = $free_shipping_calculators->get_min_amount()['min_amount'];
} else {
	$min_amount = null;
}
?>
<header id="rbb-default-header" class="rbb-default-header relative z-30 header-1 w-full">
	<div class="rbb-header-sticky relative md:h-[110px] h-[70px] md:block hidden">
		<div class="header-inner relative mx-auto h-full 2xl:px-[50px] px-5 bg-[color:var(--rbb-header-background-color)]">
			<div class="flex flex-nowrap items-center h-full grid 2xl:grid-cols-8 grid-cols-10 md:gap-4">
				<?php
				if ( Helper::show_logo() ) {
					?>
					<div id="rbb-branding" class="rbb-branding md:flex items-center rbb-header-left md:text-left text-center 2xl:col-span-1 lg:col-span-2 col-span-4">
						<span class="toggle-megamenu w-[18px] h-[14px] relative mr-5 cursor-pointer lg:hidden">
							<i class="icon-directional"></i>
						</span>
						<div id="_desktop_logo" class="flex items-center">
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
				<div id="desktop_menu" class="rbb-header-center lg:block hidden 2xl:col-span-3 lg:col-span-4 col-span-4 md:-mr-9">
					<?php if ( Helper::show_search_product_form_mobile() ) { ?>
					<div class="search_desktop animate-[500ms] lg:hidden"></div>
					<?php } ?>
					<div id="rbb-site-navigation" class="rbb-main-navigation screen">
						<nav id="menu-main" class="menu primary-menu h-full">
							<?php echo Menu::primary_menu(); // phpcs:ignore ?>
						</nav>
					</div>
					<?php if ( shortcode_exists('rbb_contact') ) { ?>
					<div class="lg:hidden mt-8 items-center mobile_bottom">
						<div class="flex mb-1.5 animate-[1500ms]">
							<span class="pr-1 font-bold"><?php echo esc_html__('Call Us:', 'botanica'); ?> </span>
							<?php echo do_shortcode('[rbb_contact type="phone"]'); ?>
						</div>
						<div class="flex mb-[25px] animate-[1600ms]">
							<span class="pr-1 font-bold"><?php echo esc_html__('Email:', 'botanica'); ?></span>
							<?php echo do_shortcode('[rbb_contact type="email"]'); ?>
						</div>
						<div class="animate-[1700ms] social_content"></div>
					</div>
					<?php } ?>
				</div>
				<?php
				if ( Helper::show_login_form() || Helper::show_search_product_form() || Helper::show_mini_cart() || Helper::show_wishlist() ) {
					?>
					<div id="rbb-header-right" class="rbb-header-right flex-none h-full ml-3 flex ml-auto mr-0 2xl:col-span-4 lg:col-span-4 col-span-6">
						<?php if ( Helper::show_search_product_form() && false === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY) ) { ?>
							<div class="rbb-product-search 2xl:mr-20 mr-2.5 relative flex items-center justify-center cursor-pointer">
								<div id="_desktop_search" class="rbb-product-search-content2 results-full">
									<?php Tag::search(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY)); // phpcs:ignore ?>
								</div>
							</div>
						<?php } ?>
						<?php if ( Helper::show_login_form() && class_exists(RbbIcons::class) ) { ?>
							<div class="rbb-account relative flex items-center justify-center mr-2.5 <?php echo ( false === is_user_logged_in() && true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP) ) ? 'popup-account' : 'toggle-login'; ?> "
								<?php
								if ( false === is_user_logged_in() && true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_POPUP) ) {
									?>
									onclick="RisingBambooModal.modal('.account-form-popup', event)" <?php } ?>
									>
									<div class="rbb-account-icon-wrap relative duration-300 cursor-pointer h-[50px] w-[50px] flex items-center justify-center">
										<span class="rbb-account-icon tracking-normal <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_ACCOUNT_ICON)); ?>"></span>
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
														echo '<div class="' . esc_attr($class_string) . '" data-modal-animation="' . esc_attr($modal_effect) . '">' . Tag::login_form() . '</div>';
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
							<?php if ( Helper::show_search_product_form() && true === Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY) ) { ?>
								<div class="rbb-product-search mr-2.5 relative flex items-center justify-center" onclick="RbbThemeSearch.openSearchForm(event)">
									<div class="rbb-product-search-icon-wrap cursor-pointer flex items-center justify-center h-[50px] w-[50px]">
										<span class="rbb-product-search-icon <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON)); ?>"></span>
									</div>
									<div id="search-mobile" class="rbb-product-search-content2 results-full">
										<?php Tag::search(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_OVERLAY)); // phpcs:ignore ?>
									</div>
								</div>
							<?php } ?>
							<?php if ( Helper::show_wishlist() ) { ?>
								<div class="rbb-wishlist flex items-center justify-center mr-2.5">
									<div class="relative text-center">
										<a class="wishlist-icon-link duration-300 h-[50px] w-[50px] flex items-center justify-center " aria-label="wishlist" href="<?php echo esc_url(WPcleverWoosw::get_url()); ?>">
											<span class="wishlist-icon duration-300 <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_WOOCOMMERCE_WISHLIST_ICON)); ?>"></span>
										</a>
										<span class="wishlist-count text-black rounded-full"><?php echo WPcleverWoosw::get_count(); // phpcs:ignore ?></span>
									</div>
								</div>
							<?php } ?>
							<?php
							if ( Helper::show_mini_cart() ) {
								RisingBambooWoo::instance()->mini_cart();
							}
							?>
							<div class="rbb-category flex items-center justify-center">
								<span class="category-mobile text-black lg:hidden block h-[50px] flex items-center justify-center text-center w-[22px] py-3 ml-5 cursor-pointer text-white text-[22px]">
									<i class="rbb-icon-menu-5 w-[18px]"></i>
								</span>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
		$phone          = shortcode_exists('rbb_contact') ? do_shortcode('[rbb_contact type="phone"]') : false;
		$email          = shortcode_exists('rbb_contact') ? do_shortcode('[rbb_contact icon="rbb-icon-email-11" type="email"]') : false;
		$address        = shortcode_exists('rbb_contact') ? do_shortcode('[rbb_contact icon="rbb-icon-location-2" type="address"]') : false;
		$sidebar_active = is_active_sidebar('sidebar-top');
		if ( $sidebar_active ) {
			?>
			<div class="policy md:flex hidden items-center bg-[#D0FFBD]">
				<?php
				dynamic_sidebar('sidebar-top');
				?>
			</div>
			<?php
		} elseif ( $phone || $email || $address ) {
			?>
			<div class="policy md:flex hidden items-center bg-[#D0FFBD] text-[#1a1a1a]">
				<?php if ( $phone || $email ) { ?>
					<div class="policy-left">
						<?php
						if ( $phone ) {
							echo wp_kses_post($phone);
						}
						if ( $email ) {
							echo wp_kses_post($email);
						}
						?>
					</div>
				<?php } ?>
				<div class="policy-right flex justify-end">
					<?php
					if ( $address ) {
						echo wp_kses_post($address);
					}
					if ( $min_amount ) {
						?>
						<div class="content">
							<i class="rbb-icon-delivery-11 !text-[22px]"></i>
							<p>
								<?php echo __('Free shipping for order above ', 'botanica') . wc_price($min_amount); //phpcs:ignore ?>
							</p>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}
		?>
		<div class="header-mobile shadow-md relative bg-white px-[15px] md:hidden">
			<div class="flex items-center justify-between max-h-[60px]">
				<div class="menu-mobile flex items-center min-w-[60px] h-[60px]">
					<span class="toggle-megamenu w-[18px] h-[14px] relative mr-5 cursor-pointer">
						<i class="icon-directional"></i>
					</span>
					<?php if ( Helper::show_search_product_form() ) { ?>
					<div class="w-[22px] text-center">
						<span class="search-mobile text-center text-black w-[18px] h-[18px] flex justify-center items-center cursor-pointer text-[22px]">
							<i class="rbb-icon-search-10"></i>
						</span>
						<div id="_mobile_search" class="product-search-mobile bg-white absolute z-10 inset-x-0 top-full w-full opacity-0 invisible">
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
