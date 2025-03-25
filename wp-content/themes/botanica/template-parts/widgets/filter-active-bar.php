<?php
/**
 * Active Filters Bar.
 *
 * @package RisingBambooTheme.
 * @since 1.0.0
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;
$position = Setting::get(RISING_BAMBOO_KIRKI_SECTION_WOOCOMMERCE_PRODUCT_CATALOG_FILTER_POSITION);
?>

<div id="rbb-active-filters-bar" class="rbb-active-filters-bar">
	<?php
	if ( ! empty($clear_links) ) {
		if ( 'left' === $position || 'right' === $position ) { 
			?>
			<div class="clear-all text-center uppercase rounded mb-5">
				<a class="remove-filter-all button text-[color:var(--rbb-general-button-color)] hover:text-[color:var(--rbb-general-button-hover-color)] bg-[color:var(--rbb-general-button-bg-color)] hover:bg-[color:var(--rbb-general-button-bg-hover-color)] duration-300 clear-all text-[0.625rem] filter-link h-12 block rounded leading-[48px]" href="<?php echo esc_url(\RisingBambooCore\Helper\Woocommerce::get_base_url()); ?>">
					<i class="rbb-icon-delete-outline-2 text-sm pr-2"></i><?php echo esc_html__('Clear All', 'botanica'); ?>
				</a>
			</div>
			<?php
			foreach ( $clear_links as $_tax => $groups ) {
				if ( ! empty($groups['links']) ) {
					?>
				<div class="filter-group pt-1 flex relative text-xs">
					<span class="filter-group-title font-medium"><?php echo esc_html($groups['name']) . ': '; ?></span> 
					<div class="block">
					<?php foreach ( $groups['links'] as $clear_link ) { ?>
						<div class="item float-left pb-2.5 relative pl-2">
							<a href="<?php echo esc_url($clear_link['url']); ?>" class="<?php echo esc_attr($clear_link['class']); ?>">
								<?php echo esc_html($clear_link['text']); ?>
							</a>
						</div>
					<?php } ?>
				</div>
					<a href="<?php echo esc_url($groups['clear']); ?>" class="remove-filter-group filter-link ml-auto mr-0 text-[10px]">✕</a>
				</div>
					<?php
				}
			}       
		} else { 
			?>
			<div class="clear-all text-center float-left uppercase rounded pr-2.5 pb-2.5 inline-block">
				<a class="remove-filter-all button px-5 text-[color:var(--rbb-general-button-color)] hover:text-[color:var(--rbb-general-button-hover-color)] bg-[color:var(--rbb-general-button-bg-color)] hover:bg-[color:var(--rbb-general-button-bg-hover-color)] duration-300 inline-block whitespace-nowrap text-[0.625rem] filter-link h-11 block rounded leading-[44px]" href="<?php echo esc_url(\RisingBambooCore\Helper\Woocommerce::get_base_url()); ?>">
					<i class="rbb-icon-delete-outline-2 pr-2 text-sm"></i><?php echo esc_html__('Clear All', 'botanica'); ?>
				</a>
			</div>
			<?php
			foreach ( $clear_links as $_tax => $groups ) {
				?>
				<div class="filter-group float-left">
					<?php foreach ( $groups['links'] as $clear_link ) { ?>
						<div class="inline-block pb-2.5 pr-2.5">
							<a href="<?php echo esc_url($clear_link['url']); ?>" class="block <?php echo esc_attr($clear_link['class']); ?>">
								<?php echo esc_html($clear_link['text']); ?>
								<span class="remove-filter-group float-right pl-5 text-[10px] font-light">✕</span>
							</a>
						</div>
					<?php } ?>
				</div>
				<?php
			}   
		}
	}
	?>
</div>
