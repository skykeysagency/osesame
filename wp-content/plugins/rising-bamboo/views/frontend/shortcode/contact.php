<?php
/**
 * Contact shortcode template.
 *
 * @package RisingBambooCore
 */

?>

<div class="flex items-center content <?php echo esc_attr('rbb_contact_shortcode_' . $type); ?>">
	<i class="<?php echo esc_attr($icon); ?>"></i>
	<p class="pl-1"><?php echo esc_html($data); ?></p>
</div>
