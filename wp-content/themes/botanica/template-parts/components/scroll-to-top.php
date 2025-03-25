<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme.
 * @version 1.0.0
 * @since 1.0.0
 */

use RisingBambooTheme\Helper\Setting;
?>

<div class="scroll-to-top fixed z-10 md:block hidden cursor-pointer opacity-0 bottom-5 right-5 p-3 bg-[color:var(--rbb-scroll-top-background-color)]">
	<span class="text-[length:var(--rbb-scroll-top-icon-size)] text-[color:var(--rbb-scroll-top-icon-color)] <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SCROLL_TO_TOP_ICON)); ?>"></span>
</div>
