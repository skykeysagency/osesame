<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 * @sine 1.0.0
 */

use RisingBambooCore\Woocommerce\Swatches;

?>

<ul class="<?php echo esc_attr(implode($wrapper_classes)); ?>">
	<?php
	$attr_id     = wc_attribute_taxonomy_id_by_name($taxonomy);
	$attr_info   = wc_get_attribute($attr_id);
	$filter_name = 'filter_' . wc_attribute_taxonomy_slug($taxonomy);
    //phpcs:ignore
    foreach ($terms as $term) {
        //phpcs:ignore
        $current_filter = isset($_GET[$filter_name]) ? explode(',', wc_clean(wp_unslash($_GET[$filter_name]))) : [];
		$current_filter = array_map('sanitize_title', $current_filter);
		$active_class   = '';
		if ( in_array($term->slug, $current_filter, true) ) {
			$active_class = 'active-item';
		}
		if ( in_array($term->slug, $current_filter, true) ) {
			$key = array_search($term->slug, $current_filter, true);
			if ( false !== $key ) {
				unset($current_filter[ $key ]);
			}
		} else {
			if ( $multiple_choose && ! in_array($term->slug, $current_filter, true) ) {
				$current_filter[] = $term->slug;
			} else {
				$current_filter = [
					$term->slug,
				];
			}
		}
		$attr_link = remove_query_arg($filter_name, $base_link);
		if ( $current_filter ) {
			$attr_link = add_query_arg($filter_name, implode(',', $current_filter), $attr_link);
		}
		?>
		<?php
		if ( 'swatches' === $style ) {
			?>
				<?php
				switch ( $attr_info->type ) {
					case 'color':
						$val = get_term_meta($term->term_id, Swatches::get_field('color'), true) ?: '#fff';

						?>
						<li class="rbb-attribute-filter-item item <?php echo esc_attr($active_class); ?> <?php echo ( 1 === $show_count ) ? 'show_count block py-2 first:pt-0 last:pb-0 ' : 'inline-block float-left m-1.5'; ?>">
							<a href="<?php echo esc_url($attr_link); ?>" class="filter-link h-[30px]">
								<div class="rounded-full h-[30px] w-[30px] duration-150 border-[1px] block <?php echo ( '#ffffff' === $val ) ? ' border-inherit ' : 'border-transparent'; ?> ">
									<span class="inline-block w-full h-full rounded-full" style=" background-color: <?php echo esc_attr($val); ?>"></span>
								</div>
							<?php
							if ( $show_count && isset($count[ $term->term_id ]) ) {
								?>
								<span class="pl-4 text-sm pr-1"><?php echo esc_html($term->name); ?></span>
								<span class="filter-count text-[#909090] text-xs font-light">
									(<?php echo esc_html($count[ $term->term_id ]); ?>)
								</span>
								<?php
							}
							?>
							</a>
						</li>
						<?php
						break;
					case 'text':
						?>
						<li class="rbb-attribute-filter-item item m-1.5 inline-block <?php echo ( 'inline' === $display ) ? ' float-left' : 'block'; ?> <?php echo esc_attr($active_class); ?> <?php echo ( 1 === $show_count ) ? 'show_count' : ''; ?>">
							<a href="<?php echo esc_url($attr_link); ?>" class="filter-link inline-block px-2 relative rounded-[3px] cursor-pointer rbb-swatch__term bg-white h-[45px] min-w-[45px] leading-10 border border-[#ececec] text-center" title="<?php echo esc_attr($term->name); ?>">
								<span class="text-sm"><?php echo esc_html($term->name); ?></span>
								<?php
								if ( $show_count && isset($count[ $term->term_id ]) ) {
									?>
									<span class="filter-count text-[#909090] pl-1 text-xs font-light">
										(<?php echo esc_html($count[ $term->term_id ]); ?>)
									</span>
									<?php
								}
								?>
							</a>
						</li>
						<?php
						break;
					case 'image':
						$val = get_term_meta($term->term_id, Swatches::get_field('image'), true) ? wp_get_attachment_thumb_url(get_term_meta($term->term_id, Swatches::get_field('image'), true)) : wc_placeholder_img_src();
						?>
						<a href="<?php echo esc_url($attr_link); ?>" class="filter-link inline-block p-1 relative cursor-pointer" aria-label="<?php echo esc_attr($term->name); ?>" title="<?php echo esc_attr($term->name); ?>">
							<img class="w-[40px] h-[40px]" src="<?php echo esc_url($val); ?>" alt="<?php echo esc_attr($term->name); ?>"/>
						</a>
					<?php } ?>
		<?php } else { ?>
			<li class="rbb-attribute-filter-item pt-3 first:pt-1 mb-2 pl-1.5 <?php echo esc_attr(( 'inline' === $display ) ? 'inline-block' : 'block'); ?> <?php echo esc_attr($active_class); ?> <?php echo esc_attr($style); ?>-style">
				<a class="filter-link relative block pl-[34px] text-[color:var(--rbb-general-body-text-color)] hover:text-[color:var(--rbb-general-link-hover-color)]" href="<?php echo esc_url($attr_link); ?>">
					<?php echo esc_html($term->name); ?>
					<?php
					if ( $show_count && isset($count[ $term->term_id ]) ) {
						?>
						<span class="filter-count text-[#909090] text-xs font-light">
							(<?php echo esc_html($count[ $term->term_id ]); ?>)
						</span>
						<?php
					}
					?>
				</a>
			</li>
		<?php } ?>

	<?php } ?>
</ul>
