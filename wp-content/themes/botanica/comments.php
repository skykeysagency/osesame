<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-[22px] mb-10">
			<?php
			$rising_bamboo_comment_count = get_comments_number();
			if ( '1' === $rising_bamboo_comment_count ) {
				echo esc_attr($rising_bamboo_comment_count) . esc_html__(' Comments', 'botanica');
			} else {
				echo esc_attr($rising_bamboo_comment_count) . esc_html__(' Comments', 'botanica');
			}
			?>
		</h2><!-- .comments-title -->
		<ol class="comment-list pt-3">
			<?php
			wp_list_comments(
				[
					'avatar_size' => get_theme_support('rbb-avatar')[0]['small'] ?? 60,
					'style'       => 'ol',
					'short_ping'  => true,
				]
			);
			?>
		</ol><!-- .comment-list -->
		<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e('Comments are closed.', 'botanica'); ?></p>
			<?php
		endif;
	endif; // Check for have_comments().

	?>
	<div class="pagination" >
		<div class="nav-links">
			<?php
			$args = [
				'type'      => 'list',
				'next_text' => '<i class="rbb-icon-direction-39"></i>',
				'prev_text' => '<i class="rbb-icon-direction-36"></i>',
			];
			paginate_comments_links($args);
			?>
		</div>
	</div>
	<?php
	comment_form();
	?>
</div><!-- #comments -->
