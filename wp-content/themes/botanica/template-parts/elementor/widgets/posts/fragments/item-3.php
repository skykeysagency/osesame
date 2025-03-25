<?php
/**
 * Elementor widget
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;

?>
<div class="item px-[15px] basis-0 grow">
	<div class="blog_item duration-300 hover:md:translate-y-[-15px] group overflow-hidden bg-white">
		<div class="blog_image rounded-[20px] overflow-hidden mb-5 xl:min-h-[300px]">
			<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="w-full">
				<?php
				if ( has_post_thumbnail($_post) ) {
					echo get_the_post_thumbnail(
						$_post,
						'post-thumbnail',
						[
							'class' => 'w-full object-cover duration-300 scale-100 group-hover:scale-105 xl:min-h-[300px]',
							'alt'   => get_the_title($_post),
						]
					);
				} else {
					?>
					<img class="object-cover duration-300 scale-100 group-hover:scale-105 xl:min-h-[300px]"
						src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/default-thumbnail.png'); ?>"
						alt="<?php echo esc_attr__('Default post thumbnail', 'botanica'); ?>">
					<?php
				}
				?>
			</a>
		</div>
		<div class="blog_info mb-[23px]">
			<div class="blog_meta text-[0.625rem] flex items-center font-semibold uppercase text-[#b8b8b8]">
				<?php if ( $show_author ) { ?>
					<div class="blog_author pr-[45px]">
						<i class="rbb-icon-human-user-10 text-[13px] text-[#c9c9c9] pr-2"></i>
						<span><?php echo esc_html__('By ', 'botanica'); ?><?php echo esc_html(get_the_author_meta('display_name', $_post->post_author)); ?></span>
					</div>
				<?php } ?>
				<?php if ( $show_date ) { ?>
					<div class="blog_date">
						<i class="rbb-icon-calendar-1 text-[#c9c9c9] pr-2 align-text-top leading-3 text-[22px]"></i>
						<span><?php echo get_the_date('dS M Y', $_post); ?></span>
					</div>
				<?php } ?>
			</div>
			<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="blog_title font-bold text-lg mt-3 mb-4 block leading-[30px]"><span class="blog_title line-clamp-2 min-h-[60px]"><?php echo get_the_title($_post); ?></span></a>
			<div class="pb-4"><?php echo esc_html(wp_trim_words(get_the_content($_post), 25, '...')); ?></div>
			<?php if ( $show_read_more ) { ?>
				<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="text-[bebebe] font-bold text-[0.625rem]">
					<span class="uppercase"><?php echo esc_html__('Read more', 'botanica'); ?></span>
				</a>
			<?php } ?>
		</div>
	</div>
</div>
