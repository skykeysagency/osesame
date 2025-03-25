<?php
/**
 * RisingBamBooCore
 *
 * @package RisingBamBooCore
 */

namespace RisingBambooCore\Helper;

use RisingBambooCore\Core\Singleton;

/**
 *  Term Helper.
 */
class Term extends Singleton {

	/**
	 * Sort terms.
	 *
	 * @param array $cats Category.
	 * @param array $into Sorted.
	 * @param int   $parent_id Parent ID.
	 * @return void
	 */
	public static function sort_terms_hierarchically( array &$cats, array &$into, int $parent_id = 0 ): void {
		foreach ( $cats as $i => $cat ) {
			if ( $cat->parent === $parent_id ) {
				$into[ $cat->term_id ] = $cat;
				unset($cats[ $i ]);
			}
		}

		foreach ( $into as $top_cat ) {
			$top_cat->children = [];
			self::sort_terms_hierarchically($cats, $top_cat->children, $top_cat->term_id);
		}
	}

	/**
	 * Get Term hierarchy flat.
	 *
	 * @param array  $result Result.
	 * @param string $taxonomy Taxonomy.
	 * @param array  $args Args.
	 * @param int    $parent_id Parent ID.
	 * @param string $prefix Prefix.
	 * @param int    $depth Depth.
	 * @return void
	 */
	public static function get_terms_hierarchically_flat( array &$result, string $taxonomy = 'category', array $args = [], int $parent_id = 0, string $prefix = '-', int $depth = 1 ): void {
		$args  = wp_parse_args(
			[
				'taxonomy'     => $taxonomy,
				'hierarchical' => false,
				'parent'       => $parent_id,
				'depth'        => 1,
				'hide_empty'   => false,
			],
			$args
		);
		$terms = get_terms($args);
		foreach ( $terms as $term ) {
			if ( 0 === $term->parent ) {
				$name  = $term->name;
				$depth = 1;
			} else {
				$name = $prefix . $term->name;
				++$depth;
			}
			$result[ $term->term_id ] = $name;

			self::get_terms_hierarchically_flat($result, $taxonomy, [], $term->term_id, str_pad($prefix, $depth, $prefix), $depth);
		}
	}
}
