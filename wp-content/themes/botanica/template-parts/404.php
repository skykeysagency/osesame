<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Helper\Tag;
$page_404_image = Setting::get(RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_IMAGE);
$page_404_title = Setting::get(RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_TITLE);
$page_404_desc  = Setting::get(RISING_BAMBOO_KIRKI_SECTION_ADVANCED_PAGE_404_DESC);
get_header();
?>

<main id="primary" class="site-main primary-404 overflow-auto" >
	<section class="error-404 not-found">
		<div class="container mx-auto">
			<div class="page-content text-center my-[100px]">
				<div class="image mb-[76px]">
					<?php if ( empty($page_404_image) ) { ?>
						<img class="img-fluid inline" src="<?php echo esc_url(RBB_THEME_DIST_URI . 'images/404-Error/404-error-page.gif'); ?>" alt="Image 404">
					<?php } else { ?>
						<img class="img-fluid inline" src="<?php echo esc_url($page_404_image); ?>" alt="Image 404">
					<?php } ?>
				</div>
				<div class="content text-center">
					<h1 class="font-black uppercase mb-4 text-[30px]"><?php echo esc_html($page_404_title); ?></h1>
					<div class="sub_title font-medium text-[22px]">
						<span><?php echo esc_html($page_404_desc); ?></span>
					</div>
					<div class="button-action pt-[53px]">
					<div class="md:flex justify-center text-[10px]">
						<a href="<?php echo esc_url(home_url('/')); ?>" class="button inline-block duration-300 mb-xs-10 h-12 leading-[22px] rounded-[5px] min-w-[250px] py-4 px-10 mx-[5px]">
							<span class="h-12"><?php echo esc_html__('Back to Homepage', 'botanica'); ?></span>
						</a>
						<a href="<?php echo esc_url(home_url('shop')); ?>" class="button inline-block md:mt-0 mt-5 duration-300 h-12 leading-[22px] rounded-[5px] min-w-[250px] py-4 px-10 mx-[5px]">
							<span class="h-12" ><?php echo esc_html__('Continue shopping', 'botanica'); ?></span>
						</a>
					</div>
				</div>
				</div>
			</div><!-- .page-content -->
		</div>
	</section><!-- .error-404 -->
</main><!-- #main -->
<?php
get_footer();
