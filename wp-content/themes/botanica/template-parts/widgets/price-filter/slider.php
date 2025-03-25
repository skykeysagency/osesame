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

<div class="rbb-price-filter relative pt-[14px]" data-price-gap="<?php echo esc_attr($price_gap); ?>">
	<div class="rbb-price-filter__slider h-[7px] relative rounded-[5px] bg-[#fff] z-[2] border border-[#ababab]">
		<div style="left: <?php echo esc_attr($left); ?>; right: <?php echo esc_attr($right); ?>" class="progress -top-[1px] -bottom-[1px] absolute rounded-[5px] bg-[color:var(--rbb-general-primary-color)] border border-[color:var(--rbb-general-primary-color)]"></div>
	</div>
	<div class="rbb-price-filter__range-input leading-[15px] mx-[1px] text-[0.625rem] font-semibold relative pb-[30px] mb-7">
		<input autocomplete="off" type="range" class="rbb-price-filter__range-min bg-transparent z-[3] absolute w-full h-[7px] top-[-7px] pointer-events-none appearance-none" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($current_min_price); ?>" step="<?php echo esc_attr($step); ?>">
		<input autocomplete="off" type="range" class="rbb-price-filter__range-max bg-transparent z-[3] absolute w-full h-[7px] top-[-7px] pointer-events-none appearance-none" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>" value="<?php echo esc_attr($current_max_price); ?>" step="<?php echo esc_attr($step); ?>">
		<span class="absolute bottom-0 left-0"><?php echo wp_kses_post(wc_price($min_price)); ?></span>
		<span class="absolute bottom-0 right-0"><?php echo wp_kses_post(wc_price($max_price)); ?></span>
	</div>
	<div class="rbb-price-filter__input items-center font-medium overflow-hidden text-[0.75rem]">
		<div class="w-full leading-6 h-6 mb-4">
			<span><?php echo esc_html__('Ranger ', 'botanica'); ?>(<?php echo esc_html(get_woocommerce_currency_symbol()); ?>):</span>
			<span class="inline-flex items-center pl-1">
			<input autocomplete="off" type="number" class="rbb-price-filter__input-min max-w-[60px] min-w-[50px] focus:outline-none inline border-none m-0 p-0 arrow-hide" value="<?php echo esc_attr($current_min_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>">
			</span>
			<span class="pr-8">-</span>
			<span class="inline-flex items-center">
				<input autocomplete="off" type="number" class="rbb-price-filter__input-max max-w-[60px] min-w-[50px] inline border-none m-0 p-0 arrow-hide" value="<?php echo esc_attr($current_max_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($max_price); ?>">
			</span>
		</div>
		<a class="mb-2.5 button inline-block filter-link duration-300 text-[color:var(--rbb-general-button-color)] hover:text-[color:var(--rbb-general-button-hover-color)] bg-[color:var(--rbb-general-button-bg-color)] hover:bg-[color:var(--rbb-general-button-bg-hover-color)] inline-block text-sm rounded-[3px] px-5 h-9 leading-9 mr-0 ml-auto" href="<?php echo esc_url(RbbCoreHelperWoocommerce::get_current_page_url()); ?>"><?php echo esc_html__('Filter', 'botanica'); ?></a>
	</div>
</div>
