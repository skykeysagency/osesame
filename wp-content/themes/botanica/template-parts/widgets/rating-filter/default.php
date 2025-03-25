<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme
 * @version 1.0.0
 * @since 1.0.0
 */

?>
<ul class="rbb-filter-rating">
<?php
for ( $rating = 5; $rating >= 1; $rating-- ) {
	$count = $widget->get_filtered_product_count($rating);
	if ( empty($count) ) {
		continue;
	}

	$widget->found = true;

	$filter_link = $base_link;

	if ( in_array($rating, $rating_filter, true) ) {
		$link_ratings = implode(',', array_diff($rating_filter, [ $rating ]));
	} else {
		$link_ratings = implode(',', array_merge($rating_filter, [ $rating ]));
	}

	$class = in_array($rating, $rating_filter, true) ? 'wc-layered-nav-rating pl-[34px] h-[22px] pt-1 mb-4 last:mb-0 relative chosen' : 'wc-layered-nav-rating pl-[34px] h-[22px] pt-1 mb-4 last:mb-0 relative';
	//phpcs:ignore
	$filter_link        = apply_filters('woocommerce_rating_filter_link', $link_ratings ? add_query_arg('rating_filter', $link_ratings, $filter_link) : remove_query_arg('rating_filter'));
	$rating_html = wc_get_star_rating_html($rating);
	$count_html  = wp_kses(
		//phpcs:ignore
		apply_filters('woocommerce_rating_filter_count', "({$count})", $count, $rating),
		[
			'em'     => [],
			'span'   => [],
			'strong' => [],
		]
	);
	?>
	<li class="<?php echo esc_attr($class); ?>">
		<a class="filter-link flex items-center text-[#909090]" href="<?php echo esc_url($filter_link); ?>">
			<span class="star-rating block mr-1">
				<?php echo wp_kses($rating_html, 'rbb-kses'); ?>
			</span>
			<?php
			if ( $show_count ) {
				echo '<span class="text-xs font-light">' . esc_html($count_html) . '</span>';
			}
			?>
		</a>
	</li>
	<?php
}
?>
</ul>
