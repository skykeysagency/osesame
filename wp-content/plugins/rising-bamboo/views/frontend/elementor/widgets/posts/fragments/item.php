<?php
/**
 * Elementor widget
 *
 * @package RisingBambooTheme
 */

use RisingBambooTheme\App\App;
?>
<div class="item px-[15px]">
	<div class="blog_item overflow-hidden p-[15px] rounded-[20px] bg-white">
		<div class="blog_image rounded-[20px] overflow-hidden mb-[14px]">
			<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="w-full">
				<?php
				if ( has_post_thumbnail($_post) ) {
					echo get_the_post_thumbnail(
						$_post,
						'post-thumbnail',
						[
							'class' => 'w-full',
							'alt'   => get_the_title($_post),
						]
					);
				} else {
					?>
					<img
						src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/default-thumbnail.png'); ?>"
						alt="<?php echo esc_html__('Default post thumbnail', App::get_domain()); ?>">
					<?php
				}
				?>
			</a>
		</div>
		<div class="blog_info mb-[23px]">
			<div class="blog_meta flex items-center font-semibold uppercase text-[#909090]">
				<?php if ( $show_author ) { ?>
					<div class="blog_author">
						<i class="rbb-icon-human-user-10"></i>
						<span><?php echo esc_html__('By ', App::get_domain()); ?><?php echo esc_html(get_the_author_meta('display_name', $_post->post_author)); ?></span>
					</div>
				<?php } ?>
				<?php if ( $show_date ) { ?>
					<div class="blog_date">
						<i class="far fa fa-calendar-alt"></i>
						<span><?php echo get_the_date('dS M Y', $_post); ?></span>
					</div>
				<?php } ?>
			</div>
			<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="blog_title font-bold text-lg mt-4 mb-7 block leading-[30px]"><?php echo get_the_title($_post); ?></a>
			<?php if ( $show_read_more ) { ?>
				<a href="<?php echo esc_url(get_permalink($_post)); ?>" class="blog_readmore font-semibold relative mt-6 mb-4">
					<span class="mr-[6px] relative"><?php echo esc_html__('Read more', App::get_domain()); ?></span>
					<i class="rbb-icon-direction-711 relative"></i></a>
			<?php } ?>
		</div>
	</div>
</div>
