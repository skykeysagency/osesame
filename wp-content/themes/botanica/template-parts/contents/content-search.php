<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rising_Bamboo
 */

use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Tag;
use RisingBambooCore\App\Admin\RbbIcons;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-wrap overflow-hidden p-[15px] rounded-[18px] border grid lg:grid-cols-2 gap-[30px]">
		<?php if ( has_post_thumbnail() ) : ?>
		<div>
			<div class="overflow-hidden rounded-[18px]">
				<?php Tag::post_thumbnail(); ?>
			</div>
		</div>
		<?php endif; ?>
		<div class="relative">
			<div class="entry-content">
				<h3 class="entry-title text-[18px] lg:pt-4 pb-4 leading-6"><a href="<?php the_permalink(); ?>"><?php echo esc_html(the_title()); ?></a></h3>
				<?php
				if ( 'post' === get_post_type() ) :
					?>
					<div class="pb-6 flex font-semibold text-[#909090] text-[10px] uppercase">
						<?php if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_AUTHOR) ) { ?>
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
						<?php } ?>
						<?php if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_PUBLISH_DATE) ) { ?>
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
						<?php } ?>
						<?php if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_CATEGORY_SHOW_COMMENT_COUNT) ) { ?>
						<span class="comments-link inline-flex">
							<span class="pr-2 flex">
								<svg fill="none" height="14" width="14" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="#909090"><path d="m12 22.81c-.69 0-1.34-.35-1.8-.96l-1.5-2c-.03-.04-.15-.09-.2-.1h-.5c-4.17 0-6.75-1.13-6.75-6.75v-5c0-4.42 2.33-6.75 6.75-6.75h8c4.42 0 6.75 2.33 6.75 6.75v5c0 4.42-2.33 6.75-6.75 6.75h-.5c-.08 0-.15.04-.2.1l-1.5 2c-.46.61-1.11.96-1.8.96zm-4-20.06c-3.58 0-5.25 1.67-5.25 5.25v5c0 4.52 1.55 5.25 5.25 5.25h.5c.51 0 1.09.29 1.4.7l1.5 2c.35.46.85.46 1.2 0l1.5-2c.33-.44.85-.7 1.4-.7h.5c3.58 0 5.25-1.67 5.25-5.25v-5c0-3.58-1.67-5.25-5.25-5.25z"/><path d="m12 12c-.56 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.44 1-1 1z"/><path d="m16 12c-.56 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.44 1-1 1z"/><path d="m8 12c-.56 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.44 1-1 1z"/></g></svg>
							</span>
							<?php
							$comment_count = wp_count_comments(get_the_ID())->total_comments;
							if ( $comment_count > 0 ) {
								?>
								<?php if ( 1 === $comment_count ) { ?>
									<?php echo esc_attr($comment_count) . '<span>' . esc_html_e('Comment', 'botanica') . '</span>'; ?>
								<?php } else { ?>
									<?php echo esc_attr($comment_count) . '<span>' . esc_html__(' Comments', 'botanica') . '</span>'; ?>
								<?php } ?>
							<?php } else { ?>
								<?php echo esc_attr($comment_count) . '<span>' . esc_html__(' Comments', 'botanica') . '</span>'; ?>
							<?php } ?>
						</span>
						<?php } ?>
					</div>
				<?php endif; ?>
				<div class="text-sm leading-6">
					<?php echo esc_html(wp_trim_words(get_the_content(), 22, '...')); ?>
				</div>
				<div class="mt-5 mb-6">
					<a href="<?php the_permalink(); ?>" class="blog-readmore flex items-center text-[#bebebe] hover:text-[color:var(--rbb-general-primary-color)] font-semibold relative mt-6 mb-4"><span class="mr-1.5 relative"><?php echo esc_html__('Read more', 'botanica'); ?></span>
						<?php if ( class_exists(RbbIcons::class) ) { ?>
						<i class="rbb-icon-direction-711 top-[1px] relative"></i>
						<?php } else { ?>
							<svg fill="#bebebe" xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 6.3499999 6.3500002" width="12"><g transform="translate(0 -290.65)"><path d="m.53383012 294.09009h4.64777838l-.8707478.87075c-.250114.25011.1250569.62528.375171.37517l.7930187-.79426.529381-.53021c.1025988-.10321.1025988-.26989 0-.3731l-1.3223997-1.32395c-.050312-.0517-.1195649-.0807-.1917197-.0801-.2381777.00003-.3550648.29011-.1834513.45527l.8728149.87075h-4.66353979c-.36681596.0182-.33942735.54794.0136943.52968z"></path></g></svg>
						<?php } ?>	
					</a>
				</div>
			</div>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->
		<footer class="entry-footer hidden">
			<?php Tag::entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->


