<?php
/**
 * RisingBamboo
 *
 * @package RisingBambooCore
 * @version 1.0.0
 * @since 1.0.0
 */

?>
<ul class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
	<?php
    //phpcs:ignore
	foreach ($terms as $term) {
		$active_item         = ( in_array($term->term_id, $widget->chosen, true) ) ? ' active-item' : '';
		$term_args['parent'] = $term->term_id;
		$child               = get_terms($term_args);
		$has_child           = ( $child ) ? ' has-child' : '';
		$_chosen             = $widget->chosen;

		if ( in_array($term->term_id, $_chosen, true) ) {
			$key = array_search($term->term_id, $_chosen, true);
			if ( false !== $key ) {
				unset($_chosen[ $key ]);
			}
		} elseif ( 'yes' === $widget->get_setting($instance, 'multiple_choose') ) {
				$_chosen[] = $term->term_id;
		} else {
			$_chosen = [ $term->term_id ];
		}

		if ( ! empty($_chosen) ) {
			$filter_link = add_query_arg('filter_' . $widget::TAXONOMY_NAME, implode(',', $_chosen), $base_link);
		} else {
			$filter_link = remove_query_arg('filter_' . $widget::TAXONOMY_NAME, $base_link);
		}
		?>
		<li class="item-<?php echo esc_attr($term->term_id) . ' ' . esc_attr(implode(' ', $li_classes)) . ' ' . esc_attr($has_child) . ' ' . esc_attr($active_item); ?>">
			<a href="<?php echo esc_url($filter_link); ?>" class="filter-link text-sm">
				<?php
				echo esc_html($term->name);
				if ( $show_count && isset($count[ $term->term_id ]) ) {
					?>
					<span class="text-[#909090] text-xs font-light"> (<?php echo esc_html($count[ $term->term_id ]); ?>)
				<?php } ?>
			</a>
			<?php
			if ( $child ) {
				$widget->list($child, $term_args, $instance, $depth + 1);
			}
			?>
		</li>
		<?php
	}
	?>
</ul>
