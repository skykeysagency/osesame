<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme
 */

namespace RisingBambooTheme\App\Menu;

use Elementor\Plugin as ElementorPlugin;
use RisingBambooCore\App\Admin\MegaMenu;
use RisingBambooCore\Helper\Helper;
use RisingBambooTheme\Helper\Setting;
use Walker_Nav_Menu;

/**
 * Menu Walker.
 *
 * @package Rising_Bamboo
 */
class WalkerNavMenu extends Walker_Nav_Menu {

	/**
	 * Enable mega menu.
	 *
	 * @var bool
	 */
	private bool $mega_menu = false;

	/**
	 * Display Menu Element
	 *
	 * @param mixed $element Element.
	 * @param mixed $children_elements Children.
	 * @param mixed $max_depth Max Depth.
	 * @param mixed $depth Depth.
	 * @param mixed $args Args.
	 * @param mixed $output Output.
	 * @return void
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) : void {
		$id_field = $this->db_fields['id'];
		if ( is_object($args[0]) ) {
			$args[0]->has_children = ! empty($children_elements[ $element->$id_field ]);
		}

		parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}

	/**
	 * Start element.
	 *
	 * @param mixed $output Output.
	 * @param mixed $item Item.
	 * @param mixed $depth Depth.
	 * @param mixed $args Args.
	 * @param mixed $id Id.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) :void {
		if ( is_object($args) ) {
			$args = get_object_vars($args);
		}
		$indent          = ( $depth ) ? str_repeat("\t", $depth) : '';
		$this->mega_menu = false;
		$classes         = ! empty($item->classes) ? (array) $item->classes : [];
		if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_ADVANCED_MEGA_MENU_NORMALIZE_CLASSES) ) {
			$classes = $this->normalize_classes($classes);
		}
		$classes[] = 'menu-item-' . $item->ID;
		$post_args = [
			'post_type'   => 'nav_menu_item',
			'nopaging'    => true,
			'numberposts' => 1,
			'meta_key'    => '_menu_item_menu_item_parent', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'  => $item->ID, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		];
		if ( '0' === $item->menu_item_parent ) {
			$classes[] = 'level-1';
		}
		if ( ! empty($item->icon_class) ) {
			$classes[] = 'has-icon';
		}
		if ( ! empty($item->image) ) {
			$classes[] = 'has-image';
		}
		if ( $args['has_children'] ) {
			$classes[] = 'hoverable';
		}
		$children = get_posts($post_args);
		foreach ( $children as $child ) {
			$obj = get_post_meta($child->ID, '_menu_item_object');
			if ( class_exists(MegaMenu::class) && MegaMenu::POST_TYPE === $obj[0] ) {
				$classes[]       = apply_filters('rbb_core_mega_menu_css_class', 'has-mega-menu', $item, $args, $depth);
				$this->mega_menu = true;
			}
		}

		$class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth)); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = [];
		$atts['title']  = $item->attr_title;
		$atts['target'] = $item->target;
		$atts['rel']    = $item->xfn;
		$atts['href']   = $item->url;
		$atts['class']  = 'relative block';

		$atts       = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty($value) ) {
				if ( 'href' === $attr ) {
					$value = esc_url($value);
				} else {
					$value = esc_attr($value);
				}
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args['before'];
		if ( $args['has_children'] ) {
			$item_output .= '<div class="opener bg-white h-10 w-10 leading-10 absolute top-1 right-0 text-black z-10 cursor-pointer text-right"><i class="rbb-icon-direction-39 text-[10px]"></i></div>';
			$item_output .= '<div class="opener2 bg-white h-10 w-14 pr-5 leading-10 absolute top-1 left-[180%] text-black z-10 cursor-pointer text-right"><i class="rbb-icon-direction-52 text-lg"></i></div>';
		}
		$item_output .= '<a' . $attributes . ' data-title="' . $args['link_before'] . apply_filters('the_title', $item->title, $item->ID) . $args['link_after'] . '">'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		if ( ! empty($item->icon_class) ) {
			$item_output .= '<i class="' . $item->icon_class . '"></i>';
		}
		
		if ( ! empty($item->image) ) {
			$image_class  = apply_filters('rbb_theme_menu_item_image_class', [ 'rbb-menu-item-image-class' ]);
			$image_size   = apply_filters('rbb_theme_menu_item_image_size', [ 32 ]);
			$item_output .= wp_get_attachment_image($item->image, $image_size, true, [ 'class' => implode(' ', $image_class) ]);
		}

		$item_output .= '<span class="menu-item-title">' . $args['link_before'] . apply_filters('the_title', $item->title, $item->ID) . $args['link_after'] . '</span>'; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		$item_output .= '</a>';
		$item_output .= $args['after'];
		if ( class_exists(MegaMenu::class) && MegaMenu::POST_TYPE === $item->object && Helper::elementor_activated() ) {
			$mega_menu_content_class = apply_filters('rbb_core_mega_menu_content_css_class', 'mega-menu-content', $item, $args, $depth);
			$mega_menu_content       = ElementorPlugin::instance()->frontend->get_builder_content_for_display($item->object_id);
			$output                 .= '<div class="' . esc_attr($mega_menu_content_class) . '">' . $mega_menu_content . '</div>';
		} else {
			$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		}
	}

	/**
	 * Start LVL
	 *
	 * @param mixed $output Output.
	 * @param mixed $depth Depth.
	 * @param mixed $args Args.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) : void {
		$class = 'sub-menu children';

		if ( $this->mega_menu ) {
			$class .= ' mega-menu';
		} else {
			$class .= ' standard-menu';
		}

		$indent  = str_repeat("\t", $depth);
		$output .= $indent . '<ul class="' . $class . '">';
	}

	/**
	 * Normalize Class.
	 *
	 * @param mixed $classes Css class.
	 * @return array
	 */
	private function normalize_classes( $classes ): array {
		// old class --> new class.
		$replacements = [
			'current-menu-item'     => 'active',
			'current-menu-parent'   => 'active-parent',
			'current-menu-ancestor' => 'active-parent',
			'current_page_item'     => 'active',
			'current_page_parent'   => 'active-parent',
			'current_page_ancestor' => 'active-parent',
			'current-page-item'     => 'active',
			'current-page-parent'   => 'active-parent',
			'current-page-ancestor' => 'active-parent',
		];

		$classes = strtr(implode(',', $classes), $replacements);
		$classes = explode(',', $classes);

		// remove any classes that are not present in the replacements array, and return the result.
		return array_unique(array_intersect(array_values($replacements), $classes));
	}
}
