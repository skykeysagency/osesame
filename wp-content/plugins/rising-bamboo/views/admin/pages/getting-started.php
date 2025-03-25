<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;

?>
<div class="mx-auto pl-[2px] pr-5">
	<?php
	View::instance()->load('admin/pages/parts/header');
	?>
	<div class="getting-started">
		<div class="getting-started-top pt-4 pb-[33px]">
			<div class="text-[13px] font-bold leading-6 text-[#1d2327]"><?php echo esc_html__('First of all, thank you so much for choosing our Theme!', App::get_domain()); ?></div>
			<p class="text-[13px] max-w-[900px] leading-6"><?php echo esc_html__('Before you get started, please be sure to always check out theme documentation. You can find documentation in the unpacked theme package or available online documentation, many basic and technical questions are answered in this documentation, so please refer to our Documentation first. Please read it carefully and follow the steps from the top to the bottom. You will find detailed instructions to set up elements in the theme.', App::get_domain()); ?></p>
		</div>
		<div class="why-choose-us py-[22px] text-[13px] leading-6">
			<h2 class="text-lg font-bold pb-3"><?php echo esc_html__('Why Choose Us', App::get_domain()); ?></h2>
			<p class="font-bold pb-[5px] text-[#1d2327]"><?php echo esc_html__('We Create Professional And Unique WordPress Themes', App::get_domain()); ?></p>
			<p class="max-w-[900px]"><?php echo esc_html__('All our themes are easy to install and customize, so you can start your own web-site just in a few minutes. Our dedicated support will provide you with fast useful answers if you have any questions concerning theme setup or configuration.', App::get_domain()); ?></p>
			<div class="why-choose-us-bottom overflow-hidden md:flex pt-9 py-4 px-[5px] -mx-[10px]">
				<div class="px-[5px] rounded-lg overflow-hidden">
					<img class="md:max-w-[160px] max-w-[100%]" alt="uxui"
						src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/uxui.png'); ?>"/>
				</div>
				<div class="px-[5px] rounded-lg overflow-hidden">
					<img class="md:max-w-[160px] max-w-[100%]" alt="customize"
						src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/customize.png'); ?>"/>
				</div>
				<div class="px-[5px] rounded-lg overflow-hidden">
					<img class="md:max-w-[160px] max-w-[100%]" alt="help"
						src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/help.png'); ?>"/>
				</div>
				<div class="px-[5px] rounded-lg overflow-hidden">
					<img class="md:max-w-[160px] max-w-[100%]" alt="uptodate"
						src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/uptodate.png'); ?>"/>
				</div>
			</div>
		</div>
		<?php
		if ( $items ) {
			?>
			<div class="more-items max-w-[1360px] py-5">
				<h3 class="text-lg font-bold mb-[38px]"><?php echo esc_html__('More items by Rising Bamboo', App::get_domain()); ?></h3>
				<div class="group-portfolio grid lg:grid-cols-2 grid-cols-1 gap-5">
					<div class="inline-block flex pt-5 max-w-[615px]">
						<div class="w-[90px] mr-2.5">
							<a href="#">
								<img class="max-w-20 max-h-20" alt="portfolio"
									src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/80x80.png'); ?>"/>
							</a>
						</div>
						<div class="max-w-[435px] w-full">
							<h3 class="text-black font-bold">
								<a class="hover:underline duration-300"
									href="#"><?php echo esc_html__('TeeMax | Fashion & POD T-Shirt Store Shopify Theme', App::get_domain()); ?></a>
							</h3>
							<span class="text-sm text-[#666666]"><?php echo esc_html__('by', App::get_domain()); ?><a
										class="px-1 text-[#0084B4] hover:underline"
										href="#"><?php echo esc_html__('Nova-Creative', App::get_domain()); ?></a><?php echo esc_html__('on ThemeForest', App::get_domain()); ?></span>
							<div class="pt-[7px]">
								<a class="bg-[#82b440] hover:bg-[#7aa93c] duration-300 rounded block text-center w-20 h-[25px] leading-[25px] !text-white text-sm !shadow-[0px_2px_0px_rgba(84,84,84,1)]"
									title="Add to Cart" href="#"><i class="rbb-icon-shopping-cart-13"></i></a>
							</div>
						</div>
						<div class="author-sale-info min-w-[90px] text-right">
							<h3 class="text-lg font-bold"><?php echo esc_html__('$59', App::get_domain()); ?></h3>
							<div class="rating text-[#f0b802] text-[11px]">
								<i class="rbb-icon-rating-start-filled-3"></i>
								<i class="rbb-icon-rating-start-filled-3"></i>
								<i class="rbb-icon-rating-start-filled-3"></i>
								<i class="rbb-icon-rating-start-filled-3"></i>
								<i class="rbb-icon-rating-start-filled-3"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="view-author mt-[60px]">
					<a class="bg-[#0074a7] hover:bg-[#005177] duration-300 h-[48px] leading-[43px] inline-block !text-white text-xs font-bold rounded px-5 py-1"
						target="_blank"
						href="https://themeforest.net/user/risingbamboo"><?php echo esc_html__('View All Portfolio', App::get_domain()); ?></a>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	<div id="rbb-core-getting"></div>
</div>
