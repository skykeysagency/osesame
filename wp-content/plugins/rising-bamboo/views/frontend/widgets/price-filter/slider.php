<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 * @sine 1.0.0
 */

use RisingBambooCore\Helper\Woocommerce as RbbCoreHelperWoocommerce;
use RisingBambooTheme\App\App;

?>

<div class="rbb-price-filter pt-[14px]" data-price-gap="<?php echo esc_attr($price_gap); ?>">
	<div class="rbb-price-filter__slider h-[7px] relative rounded-[5px] bg-[#fff]">
		<div style="left: <?php echo esc_attr($left); ?>; right: <?php echo esc_attr($right); ?>" class="progress -top-[1px] -bottom-[1px] absolute rounded-[5px]"></div>
	</div>
	<div class="rbb-price-filter__range-input text-[0.625rem] font-semibold relative pb-[30px] mb-9">
		<input autocomplete="off" type="range" class="rbb-price-filter__range-min absolute w-full h-[7px] top-[-7px] pointer-events-none appearance-none" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($current_min_price); ?>" step="<?php echo esc_attr($step); ?>">
		<input autocomplete="off" type="range" class="rbb-price-filter__range-max absolute w-full h-[7px] top-[-7px] pointer-events-none appearance-none" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($current_max_price); ?>" step="<?php echo esc_attr($step); ?>">
		<span class="absolute bottom-0 left-0"><?php echo esc_html__('0', App::get_domain()); ?></span>
		<span class="border-top absolute bottom-0 -z-10 left-1/4"><?php echo esc_html__('25', App::get_domain()); ?></span>
		<span class="border-top absolute bottom-0 -z-10 left-1/2"><?php echo esc_html__('50', App::get_domain()); ?></span>
		<span class="border-top absolute bottom-0 -z-10 left-3/4"><?php echo esc_html__('75', App::get_domain()); ?></span>
		<span class="absolute bottom-0 right-0"><?php echo esc_html__('100', App::get_domain()); ?></span>
	</div>
	<div class="rbb-price-filter__input items-center font-medium xl:flex text-[0.75rem]">
		<div class="flex mb-[10px]">
			<span><?php echo esc_html__('Ranger :', App::get_domain()); ?></span>
			<span class="flex items-center h-4 pl-1"><?php echo esc_html(get_woocommerce_currency_symbol()); ?>
			<input autocomplete="off" type="number" class="rbb-price-filter__input-min inline border-none m-0 p-0 arrow-hide" value="<?php echo esc_attr($current_min_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>">
		</span>
		<span class="pr-3">-</span>
		<span class="flex items-center h-4 "><?php echo esc_html(get_woocommerce_currency_symbol()); ?>
		<input autocomplete="off" type="number" class="rbb-price-filter__input-max inline border-none m-0 p-0 arrow-hide" value="<?php echo esc_attr($current_max_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>">
	</span>
</div>
<a class="mb-[10px] filter-link inline-block text-sm uppercase font-extrabold rounded-[30px] px-5 h-9 leading-9 mr-0 ml-auto" href="<?php echo esc_url(RbbCoreHelperWoocommerce::get_current_page_url()); ?>"><?php echo esc_html__('Filter', App::get_domain()); ?></a>
</div>
</div>
