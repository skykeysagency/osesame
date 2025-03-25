<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Tag;

?>
<div class="container mx-auto">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php Tag::post_thumbnail(); ?>
		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages(
				[
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'botanica'),
					'after'  => '</div>',
				]
			);
			?>
		</div><!-- .entry-content -->

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__('Edit <span class="screen-reader-text">%s</span>', 'botanica'),
							[
								'span' => [
									'class' => [],
								],
							]
						),
						wp_kses_post(get_the_title())
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</article><!-- #post-<?php the_ID(); ?> -->
</div>
