<?php
/**
 * Shipping free goal template.
 *
 * @package RisingBambooCore
 * @since 1.0.0
 * @version 1.0.0
 */

use RisingBambooTheme\App\App;
?>

<div class="rbb-free-ship-goal w-full py-4 <?php echo ( ! $amount_left ) ? 'free-ship-eligible' : esc_attr($percent_class); ?> <?php echo esc_attr(str_replace('_', '-', $position)); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<div class="rbb-free-ship-goal-text text-center mb-10">
		<?php if ( $amount_left ) { ?>
			<span class="text-sm amount-left">
				<?php
				/* translators: 1: Product price */
				printf(__('By %s more to enjoy <strong>FREE SHIP</strong>', App::get_domain()), wc_price($min_amount - $amount)); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
				</span>
		<?php } else { ?>
			<span><?php echo wp_kses_post(__('Congrats! You are eligible for <strong>FREE Shipping</strong>', App::get_domain())); ?></span>
		<?php } ?>
	</div>
	<div class="rbb-free-ship-goal-process-bar w-full bg-gray-200 h-1">
		<div class="progress-bar h-1 relative" title="<?php echo esc_attr($percent) . '%'; ?>" style="width: <?php echo esc_attr($percent); ?>%">
			<div class="progress-mark absolute right-0 top-2/4 rounded-full text-center border w-[32px] h-[32px] translate-x-2/4 -translate-y-2/4 flex justify-center items-center bg-white">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="text-[12px] h-[12px] w-[12px]">
					<path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path>
				</svg>
			</div>
		</div>
	</div>
</div>
