<?php
/**
 * RisingBambooTheme package.
 *
 * @package RisingBambooTheme
 */

global $post, $wp_query;

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Tag;
use RisingBambooCore\App\Admin\RbbIcons;

if ( class_exists(RbbIcons::class) ) {
	$delimiter_class = 'delimiter px-2 rbb-icon-direction-39';  
} else {
	$delimiter_class = 'delimiter px-2 icon-svg';
}
$current_class = 'current';
?>
<div id="rbb-breadcrumb" class="rbb-breadcrumb text-xs text-center">
	<div class="rbb-breadcrumb-inner capitalize">
		<a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Home', 'botanica'); ?></a>
		<?php echo wp_kses_post(Tag::parse_breadcrumb($delimiter_class, $current_class)); ?>
	</div>
</div>
