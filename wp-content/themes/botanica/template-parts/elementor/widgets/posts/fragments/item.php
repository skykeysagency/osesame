<?php
/**
 * Elementor widget
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;
?>
<div class="item px-[15px] basis-0 grow">
	<div class="blog_item duration-300 hover:md:translate-y-[-15px] hover:shadow-[10px_11px_20px_0px_rgba(0,0,0,0.15)] group overflow-hidden p-[15px] rounded-[20px] bg-white">
		<div class="blog_image rounded-[20px] overflow-hidden mb-5">
			<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="w-full">
				<?php
				if ( has_post_thumbnail($_post) ) {
					echo get_the_post_thumbnail(
						$_post,
						'post-thumbnail',
						[
							'class' => 'w-full duration-300 scale-100 group-hover:scale-105',
							'alt'   => get_the_title($_post),
						]
					);
				} else {
					?>
					<img class="duration-300 scale-100 group-hover:scale-105"
						src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/default-thumbnail.png'); ?>"
						alt="<?php echo esc_attr__('Default post thumbnail', 'botanica'); ?>">
					<?php
				}
				?>
			</a>
		</div>
		<div class="blog_info mb-[23px]">
			<div class="blog_meta text-[0.625rem] flex items-center font-semibold uppercase text-[#909090]">
				<?php if ( $show_author ) { ?>
					<div class="blog_author pr-[45px]">
						<i class="rbb-icon-human-user-10 text-[13px] pr-2 text-[#c9c9c9]"></i>
						<span><?php echo esc_html__('By ', 'botanica'); ?><?php echo esc_html(get_the_author_meta('display_name', $_post->post_author)); ?></span>
					</div>
				<?php } ?>
				<?php if ( $show_date ) { ?>
					<div class="blog_date">
						<i class="rbb-icon-calendar-1 pr-2 text-[#c9c9c9] align-text-top leading-3 text-[22px]"></i>
						<span><?php echo get_the_date('dS M Y', $_post); ?></span>
					</div>
				<?php } ?>
			</div>
			<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="blog_title font-bold text-lg mt-4 mb-7 block leading-[30px]"><span class="blog_title line-clamp-2 min-h-[60px]"><?php echo get_the_title($_post); ?></span></a>
			<?php if ( $show_read_more ) { ?>
				<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="blog_readmore text-[bebebe] font-semibold relative mt-6 mb-4 pt-3 pr-[15px] pb-[13px]">
					<span class="mr-1.5 text-xs relative"><?php echo esc_html__('Read more', 'botanica'); ?></span>
					<i class="rbb-icon-direction-711 align-middle text-lg leading-[17px] relative"></i></a>
			<?php } ?>
		</div>
	</div>
</div>
