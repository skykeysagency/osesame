<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('testimonial'); ?>>
	<div class="content mb-[30px] border border-[#e6e6e6] shadow-[14px_14px_25px_0px_rgba(0,0,0,0.1)] px-[15px] md:px-[30px] pt-5 pb-[30px] rounded-[20px]">
		<div class="overflow-hidden">
		<div class="text text-[color:var(--rbb-general-heading-color)] text-sm leading-6 relative pt-11">
			<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'botanica'),
						[
							'span' => [
								'class' => [],
							],
						]
					),
					wp_kses_post(get_the_title())
				)
			);
			?>
		</div>
		<div class="author flex items-center mt-8">
			<div class="overflow-hidden rounded-full cursor-pointer">
			<?php
			the_post_thumbnail(
				[ 70, 70 ],
				[
					'alt' => the_title_attribute(
						[
							'echo' => false,
						]
					),
				]
			);
			?>
		</div>
			<div class="entry-content ml-2.5">
				<div class="text-sm font-bold mb-1.5"><?php the_title(); ?> </div>
				<div class="text-xs"><?php the_excerpt(); ?> </div>
				<?php
				wp_link_pages(
					[
						'before' => '<div class="page-links">' . esc_html__('Pages:', 'botanica'),
						'after'  => '</div>',
					]
				);
				?>
			</div>
		</div>
		</div>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
