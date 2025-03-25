<?php
/**
 * Default template.
 *
 * @package RisingBambooCore.
 */

?>

<div class="rbb-elementor-menu-default-layout">
<?php
// override params https://developer.wordpress.org/reference/functions/wp_nav_menu/ .
$override_args = [];
$args          = wp_parse_args($override_args, $args);
wp_nav_menu($args);
?>
</div>