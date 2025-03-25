<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme.
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;

global $post;
$categories   = get_the_category($post->ID);
$category_ids = [];
foreach ( $categories as $category ) {
	$category_ids[] = $category->term_id;
}
$category_ids = implode(',', $category_ids);
if ( strlen($category_ids) > 0 ) {
	$arg = [
		'post_type'    => $post->post_type,
		'cat'          => $category_ids,
		'post__not_in' => [ $post->ID ],
	];
} else {
	$arg = [
		'post_type'    => $post->post_type,
		'post__not_in' => [ $post->ID ],
	];
}

/* Remove the quote post format */

//phpcs:ignore
$arg['tax_query'] = [
	[
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => [ 'post-format-quote' ],
		'operator' => 'NOT IN',
	],
];
$related_posts    = new WP_Query($arg);
if ( $related_posts->have_posts() ) {
	$is_slider = true;
	if ( isset($related_posts->post_count) && $related_posts->post_count <= 1 ) {
		$is_slider = false;
	}
	$show_arrows     = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW_NAVIGATION);
	$show_pagination = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_SHOW_PAGINATION);
	$autoplay        = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_AUTO_PLAY);
	$autoplay_speed  = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_RELATED_POST_AUTO_PLAY_SPEED);
	?>
	<div class="related-posts overflow-hidden bg-[#f2f2f2]">
		<div class="content container mx-auto pt-[110px] pb-[120px] px-[15px] xl:px-0">
			<div class="rbb-title text-center">
				<h3 class="related_title mb-8 pb-5 text-4xl font-extrabold">
					<span><?php esc_html_e('Related Posts', 'botanica'); ?></span>
				</h3>
			</div>
			<div class="rbb-related-posts rbb-slick-el slick-carousel slick-carousel-center" data-slick='{
				"arrows": <?php echo esc_attr(( $show_arrows ) ? 'true' : 'false'); ?>,
				"dots": <?php echo esc_attr(( $show_pagination ) ? 'true' : 'false'); ?>,
				"autoplay": <?php echo esc_attr(( $autoplay ) ? 'true' : 'false'); ?>,
				"autoplaySpeed": <?php echo esc_attr($autoplay_speed); ?>,
				"rows": 1,
				"slidesToScroll":1,
				"slidesToShow" : 3,
				"responsive": [
					{
						"breakpoint": 1024,
						"settings": {
							"slidesToShow": 2
						}
					},
					{
						"breakpoint": 767,
						"settings": {
							"slidesToShow": 2
						}
					},
					{
						"breakpoint": 480,
						"settings": {
							"slidesToShow": 1
						}
					}
				]
			}'>
				<?php
				while ( $related_posts->have_posts() ) :
					$related_posts->the_post();

					$post_format = get_post_format();
					if ( $is_slider && 'gallery' === $post_format ) {
						$post_format = false;
					}
					?>
					<div class="item md:px-[15px]">
						<div class="post-item group rounded-[18px] p-[15px] bg-white border border-[color:var(--rbb-general-button-bg-color)]">
							<div class="post-img rounded-[18px] overflow-hidden">
							<?php if ( 'gallery' === $post_format || false === $post_format || 'standard' === $post_format ) { ?>
								<a class="thumbnail <?php echo esc_attr($post_format); ?> <?php echo esc_attr(( 'gallery' === $post_format ) ? 'loading owl-carousel' : ''); ?>" href="<?php esc_url(get_the_permalink()); ?>">
										<?php
										if ( 'gallery' === $post_format ) {
											$gallery     = get_post_meta($post->ID, 'gallery', true);
											$gallery_ids = explode(',', $gallery);
											if ( is_array($gallery_ids) && has_post_thumbnail() ) {
												array_unshift($gallery_ids, get_post_thumbnail_id());
											}
											foreach ( $gallery_ids as $gallery_id ) {
												echo wp_get_attachment_image($gallery_id, 'post-thumbnail', 0, [ 'class' => ' duration-300 scale-100 group-hover:scale-105' ]);
											}
											if ( ! has_post_thumbnail() && empty($gallery) ) {
												$show_blog_thumbnail = 0;
											}
										}
										if ( false === $post_format || 'standard' === $post_format ) {
											if ( has_post_thumbnail() ) {
												the_post_thumbnail('post-thumbnail', [ 'class' => ' duration-300 scale-100 group-hover:scale-105' ]);
											} else {
												$show_blog_thumbnail = 0;
											}
										}
										?>
									</a>
									<?php
							}
							?>
							</div>
							<div class="post-info">
								<div class="mt-4 mb-4 font-semibold text-[#909090] text-[10px] uppercase">
									<span class="pr-7">
										<i class="rbb-icon-human-user-10 pr-3 text-[13px]"></i>
										<?php echo esc_html__('By ', 'botanica'); ?><?php echo get_the_author(); ?>
									</span>
									<span class="pr-7">
										<i class="rbb-icon-calendar-1 align-text-top leading-3 text-[22px]"></i>
										<span class="pl-2"><?php echo get_the_time('F j, Y'); //phpcs:ignore ?></span>
									</span>
								</div>
								<div class="blog-title text-lg font-bold leading-8">
									<a class="title line-clamp-2" href="<?php esc_url(get_the_permalink($post)); ?>"><?php the_title(); ?></a>
								</div>
								<div class="my-6">
									<a href="<?php esc_url(get_the_permalink($post)); ?>" class="blog_readmore text-[#bebebe] hover:!text-[color:var(--rbb-general-primary-color)] font-semibold relative text-xs mt-6 mb-4 ">
										<span class="mr-1.5"><?php echo esc_html__('Read more', 'botanica'); ?></span>
										<i class="rbb-icon-direction-711 relative top-[2px]"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
	<?php
	wp_reset_postdata();
}
?>
