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
use RisingBambooTheme\Helper\Setting;
$position       = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_THUMBNAIL_POSITION);
$content_layout = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT);
$sidebar        = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_SIDEBAR);
?>
<?php do_action('rbb_single_post_before_main'); //phpcs:ignore ?>
<main id="primary" class="site-main single-<?php echo esc_attr(get_post_type()); ?> overflow-auto">
	<?php do_action('rbb_single_post_before'); //phpcs:ignore ?>
	<div class="blog-detail container mx-auto overflow-hidden lg:flex <?php echo ( 'on_top' === $position ) ? 'pt-[60px] md:pb-[120px] pb-[60px]' : 'md:pb-[120px] pb-[60px]'; ?>" >
		<?php
		if ( 'left' === $sidebar ) {
			?>
			<div class="sidebar-left lg:w-[31.77%] w-[100%] w-full xl:pr-[60px] lg:pr-[30px]">
				<?php if ( is_active_sidebar('sidebar-blog') || is_active_sidebar('sidebar-blog-top') ) { ?>
					<div class="blog-left">
						<?php
						dynamic_sidebar('sidebar-blog-top');
						dynamic_sidebar('sidebar-blog');
						?>
					</div>
				<?php } ?>
			</div>
			<?php
		}
		?>
		<div class="blog-content <?php echo ( 'none' !== $sidebar ) ? 'lg:w-[68.23%] md:w-[100%]' : ''; ?> w-full">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part('template-parts/contents/layouts/post/' . $content_layout, '', [ 'layout' => $content_layout ]);
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
		</div>
		<?php
		if ( 'right' === $sidebar ) {
			?>
			<div class="sidebar-right lg:w-[31.77%] w-[100%] w-full xl:pl-[60px] lg:pl-[30px]">
				<?php if ( is_active_sidebar('sidebar-blog') || is_active_sidebar('sidebar-blog-top') ) { ?>
					<div class="blog-right">
						<?php
						dynamic_sidebar('sidebar-blog-top');
						dynamic_sidebar('sidebar-blog');
						?>
					</div>
				<?php } ?>
			</div>
			<?php
		}
		?>
	</div>
	<?php do_action('rbb_single_post_after'); //phpcs:ignore ?>
</main><!-- #main -->
	<?php 
	if ( is_singular('post') && Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW) ) {
		get_template_part('template-parts/contents/related-posts');
	} 
	?>
<?php do_action('rbb_single_post_after_main'); //phpcs:ignore ?>
