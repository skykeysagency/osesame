<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\App\App;

// translators: 1: Name, 2: Version.
$welcome      = sprintf(esc_html__('Welcome to %1$s %2$s', App::get_domain()), App::get_info('Name'), App::get_info('Version'));
$welcome_desc = __('Thank you for using our theme, Please leave us a &#9733;&#9733;&#9733;&#9733;&#9733; rating. We really appreciate your support!', App::get_domain());
// translators: %s: Support Link.
$support = App::get_info('support') === '#' ? false : sprintf(__('<a href="%s">How To Get Support</a>', App::get_domain()), App::get_info('support'));
// translators: %s: Docs Link.
$docs = App::get_info('docs') === '#' ? false : sprintf(__('<a href="%s">Documentation</a>', App::get_domain()), App::get_info('docs'));
// translators: %s: Faq Link.
$faqs = App::get_info('faqs') === '#' ? false : sprintf(__('<a href="%s">FAQs</a>', App::get_domain()), App::get_info('faqs'));
?>

<div class="rbb-header pt-5  mb-5">
	<div class="md:flex items-center p-[30px] min-h-[183px]" style="background-image:url(<?php echo esc_url(RBB_CORE_DIST_URL . 'images/bg-header.jpg'); ?> ); border: 1px solid #fff;">
		<div class="logo md:mr-[30px] md:mb-0 mb-[20px] md:text-left text-center rounded-full overflow-hidden"><a href="https://risingbamboo.com/"><img alt="Rising Bamboo Logo" class="border-none inline-block" src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/logo.png'); ?>" /></a></div>
		<div class="info md:text-left text-center">
			<h1 class="text-lg font-bold"><?php echo wp_kses_post($welcome); ?></h1>
			<p class="text-xs py-3"><?php echo wp_kses_post($welcome_desc); ?></p>
			<ul class="text-xs font-bold pt-2.5 pb-2">
				<?php if ( $support ) { ?>
				<li class="inline"><?php echo wp_kses_post($support); ?></li>
				<?php } ?>
				<?php if ( $docs ) { ?>
				<li class="inline"><?php echo wp_kses_post($docs); ?></li>
				<?php } ?>
				<?php if ( $faqs ) { ?>
				<li class="inline"><?php echo wp_kses_post($faqs); ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
