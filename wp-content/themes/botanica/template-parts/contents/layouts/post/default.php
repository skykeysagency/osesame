<?php
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

use RisingBambooCore\App\Frontend\ShortCode;
use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Helper\Tag;
use RisingBambooCore\App\Admin\RbbIcons;

$position = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_THUMBNAIL_POSITION);
$sidebar  = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_SIDEBAR);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('mx-auto' . ( ( 'on_top' !== $position ) && ( 'none' !== $sidebar ) ? ' max-w-[880px]' : '' ) . ' ' . esc_attr($args['layout'])); ?>>
    <?php do_action('rbb_single_content_before_title'); //phpcs:ignore ?>
	<div class="relative">
		<div class="entry-content mb-[30px]">
			<?php if ( 'on_header' !== $position ) { ?>
				<h1 class="entry-title text-2xl -mt-2 pb-3"><?php echo esc_html(the_title()); ?></h1>
				<?php
				if ( 'post' === get_post_type() ) :
					?>
					<div class="mb-12 flex font-semibold text-[#909090] text-[10px] uppercase">
						<?php
						if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_AUTHOR) ) {
							?>
						<span class="pr-7 flex items-center">
							<?php if ( class_exists(RbbIcons::class) ) { ?>
							<i class="rbb-icon-human-user-10 pr-3 text-[13px]"></i>
							<?php } else { ?>
								<svg class="mr-2 bi bi-person" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#909090" viewBox="0 0 16 16">
									<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
								</svg>
							<?php } ?>
							<?php echo esc_html__('By ', 'botanica'); ?><?php echo get_the_author(); ?>
						</span>
							<?php
						}
						?>
						<?php
						if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_PUBLISH_DATE) ) {
							?>
						<span class="pr-7 flex items-center">
							<?php if ( class_exists(RbbIcons::class) ) { ?>
							<i class="rbb-icon-calendar-1 align-text-top leading-3 text-[22px] pr-2"></i>
							<?php } else { ?>
							<svg class="mr-2" width="12" height="12" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
							<g clip-path="url(#clip0_63_679)">
							<path d="M452 40H428V0H388V40H124V0H84V40H60C26.916 40 0 66.916 0 100V452C0 485.084 26.916 512 60 512H452C485.084 512 512 485.084 512 452V100C512 66.916 485.084 40 452 40ZM472 452C472 463.028 463.028 472 452 472H60C48.972 472 40 463.028 40 452V188H472V452ZM472 148H40V100C40 88.972 48.972 80 60 80H84V120H124V80H388V120H428V80H452C463.028 80 472 88.972 472 100V148Z" fill="#909090"/>
							<path d="M116 230H76V270H116V230Z" fill="#909090"/>
							<path d="M196 230H156V270H196V230Z" fill="#909090"/>
							<path d="M276 230H236V270H276V230Z" fill="#909090"/>
							<path d="M356 230H316V270H356V230Z" fill="#909090"/>
							<path d="M436 230H396V270H436V230Z" fill="#909090"/>
							<path d="M116 310H76V350H116V310Z" fill="#909090"/>
							<path d="M196 310H156V350H196V310Z" fill="#909090"/>
							<path d="M276 310H236V350H276V310Z" fill="#909090"/>
							<path d="M356 310H316V350H356V310Z" fill="#909090"/>
							<path d="M116 390H76V430H116V390Z" fill="#909090"/>
							<path d="M196 390H156V430H196V390Z" fill="#909090"/>
							<path d="M276 390H236V430H276V390Z" fill="#909090"/>
							<path d="M356 390H316V430H356V390Z" fill="#909090"/>
							<path d="M436 310H396V350H436V310Z" fill="#909090"/>
							<rect x="40" y="80" width="433" height="68" fill="#909090"/>
							</g>
							<defs>
							<clipPath>
							<rect width="12" height="12" fill="white"/>
							</clipPath>
							</defs>
							</svg>
							<?php } ?>
							<?php echo get_the_date(); ?>
						</span>
							<?php
						}
						?>
					</div>
				<?php endif; ?>
			<?php } ?>
            <?php do_action('rbb_single_content_after_title'); //phpcs:ignore ?>
			<div>
				<div class="post-content"><?php the_content(); ?></div>
				<?php
				if ( shortcode_exists('rbb_social_share') && Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_SOCIAL_SHARE) ) {
					?>
					<div class="social-share mb-[22px]">
						<?php echo do_shortcode('[rbb_social_share popup=no]'); ?>
					</div>
					<?php
				}
				?>
				<?php
				if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_TAG) ) {
					$tags_list = get_the_tag_list('', ' ');
					?>
					<div class="tags-link pt-8 pb-[52px] border-t-[1px] border-[#f2f2f2]">
							<span class="tag-links pr-5 text-xs text-[color:var(--rbb-general-heading-color)]">
								<i class="rbb-icon-tag-2 rotate-[270]" aria-hidden="true"></i>
								<strong class="uppercase"><?php echo esc_html__('Tags :', 'botanica'); ?> </strong>
							</span>
						<?php echo trim($tags_list); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php } ?>
				<?php wp_link_pages(); ?>
			</div>
		</div>
	</div><!-- .entry-content -->
	<!-- If comments are open, or we have at least one comment, load up the comment template. -->
	<?php
	if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_COMMENT) && ( comments_open() || get_comments_number() ) ) :
		comments_template();
	endif;
	?>
	<footer class="entry-footer hidden">
		<?php Tag::entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
