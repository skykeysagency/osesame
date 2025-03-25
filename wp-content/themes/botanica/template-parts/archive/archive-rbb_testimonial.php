<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;

get_header();
?>

	<main id="primary"
		class="w-full aspect-square site-main primary-archive container mx-auto <?php echo esc_attr(get_post_type()); ?>">
		<?php if ( apply_filters('rising_bamboo_page_title', true) ) : ?>
		<?php endif; ?>
		<div class="pagetestimonial md:flex bg-[#f1f1f1] py-11 px-4 rounded-[20px] my-[30px] text-center md:text-left">
			<div class="block_quote w-[100%] md:w-[40%] lg:w-[21%] d-flex align-items-center md:px-[23px]">
				<img class="img-fluid inline" src="<?php echo esc_url(RBB_THEME_DIST_URI . 'images/elementor/widgets/testimonials/quote0.png'); ?>" alt="quote">
			</div>
			<div class="w-[100%] md:w-[60%] lg:w-[79%] flex items-center">
				<div class="content relative pl-[35px] md:pl-[70px]">
					<span
						class="font-medium uppercase tracking-[2px] leading-10"><?php echo esc_html__("what's happy customers say", 'botanica'); ?></span>
					<div
						class="text-[color:var(--rbb-general-heading-color)] text-[1.5rem] font-bold max-w-[450px] leading-10"><?php echo esc_html__('See Why Thousands of Customer Love Us!', 'botanica'); ?></div>
				</div>
			</div>
		</div>
		<div class="columns-2 gap-[15px] md:columns-3 md:gap-[30px] xl:columns-4 pb-[120px]">
			<?php
			$args      = [
				'post_type'      => 'rbb_testimonial',
				'posts_per_page' => -1, //phpcs:ignore WPThemeReview.CoreFunctionality.PostsPerPage.posts_per_page_posts_per_page
			];
			$the_query = new WP_Query($args);

			if ( $the_query->have_posts() ) :

				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					get_template_part('template-parts/contents/content', get_post_type());
				endwhile;

			else :

				get_template_part('template-parts/contents/content', 'none');

			endif;
			?>

		</div>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
