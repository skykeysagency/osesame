<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\Helper\Setting;

get_header();
$content_layout = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT);
$columns        = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT_COLUMNS);
$sidebar        = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_LAYOUT_SIDEBAR);
?>

<main id="primary" class="site-main archive-category overflow-auto">
	<div class="container mx-auto">
		<div class="blog-category md:py-[120px] py-[60px] md:flex">
			<?php
			if ( 'left' === $sidebar ) {
				?>
				<div class="xl:w-[31.77%] lg:w-[35%] md:w-[40%] w-full xl:pr-[60px] lg:pr-[40px] md:pr-[30px]">
					<?php if ( is_active_sidebar('sidebar-blog') || is_active_sidebar('sidebar-blog-top') ) { ?>
						<div class="blog-left">
							<?php
							dynamic_sidebar('sidebar-top');
							dynamic_sidebar('sidebar-blog-top');
							dynamic_sidebar('sidebar-blog');
							?>
						</div>
					<?php } ?>
				</div>
				<?php
			}
			?>
			<div class="blog-content <?php echo ( 'none' !== $sidebar ) ? 'xl:w-[68.23%] lg:w-[65%] md:w-[60%]' : ''; ?> w-full">
				<?php if ( have_posts() ) : ?>
					<?php if ( apply_filters('rising_bamboo_page_title', true) ) : ?>
						<header class="page-header">
							<?php
							the_archive_description('<div class="archive-description pb-5 font-bold uppercase">', '</div>');
							?>
						</header><!-- .page-header -->
					<?php endif; ?>
					<div class="grid gap-[30px]
					<?php
					switch ( $columns ) {
						case 2:
							echo 'ms:grid-cols-1 lg:grid-cols-2';
							break;
						case 3:
							echo 'ms:grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
							break;
						default:
							echo 'grid-cols-1';
					}
					?>
					">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

							/*
							* Include the Post-Type-specific template for the content.
							* If you want to override this in a child theme, then include a file
							* called content-___.php (where ___ is the Post Type name) and that will be used instead.
							*/
							get_template_part('template-parts/contents/layouts/category/' . $content_layout);

						endwhile;
					?>
					</div>
					<?php
					$args = [
						'type'      => 'list',
						'next_text' => '<i class="rbb-icon-direction-36"></i>',
						'prev_text' => '<i class="rbb-icon-direction-39"></i>',
					];
					the_posts_pagination($args);
				else :
					get_template_part('template-parts/contents/content', 'none');

				endif;
				?>
			</div>
			<?php
			if ( 'right' === $sidebar ) {
				?>
				<div class="xl:w-[31.77%] lg:w-[35%] md:w-[40%] w-full lg:pl-[60px] lg:pl-[40px] md:pl-[30px]">
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
	</div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();



