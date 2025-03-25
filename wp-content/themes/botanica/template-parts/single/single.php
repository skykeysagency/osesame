<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Helper;
?>

<main id="primary" class="site-main single-<?php echo esc_attr(get_post_type()); ?> overflow-auto">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part('template-parts/contents/content', get_post_type());
			if ( Helper::show_post_navigation() ) {
				the_post_navigation(
					[
						'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'botanica') . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'botanica') . '</span> <span class="nav-title">%title</span>',
					]
				);
			}
		endwhile; // End of the loop.
		?>
</main><!-- #main -->
