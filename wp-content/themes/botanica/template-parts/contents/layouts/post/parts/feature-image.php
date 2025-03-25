<?php
/**
 * Feature Image.
 *
 * @package RisingBambooTheme.
 */

use RisingBambooTheme\App\App;
use RisingBambooTheme\Helper\Setting;
use RisingBambooTheme\Helper\Tag;

global $post;
$position         = Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_LAYOUT_THUMBNAIL_POSITION);
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>
<?php echo ( 'on_top' === $position ) ? '<div class="container mx-auto">' : ''; ?>
<div
	class="overflow-hidden relative thumbnail-position-<?php echo esc_attr($position); ?> <?php echo ( 'on_top' === $position ) ? 'rounded-[20px]' : ''; ?>"
	<?php
	if ( 'on_header' === $position ) {
		?>
		style="height: 800px; background-image:url('<?php echo esc_url($featured_img_url); ?>'); " <?php } ?> >
	<?php
	if ( 'on_header' !== $position ) {
		Tag::post_thumbnail('full', [ 'class' => 'w-full' ]);
	}
	$categories = wp_get_post_categories($post->ID);
	if ( $categories && Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_CATEGORY) ) {
		?>

		<?php
		if ( 'on_header' === $position ) {
			?>
			<div
				class="absolute z-10 feature-image w-full p-4 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
				<h1 class="entry-title text-center text-2xl pt-2 pb-4">
					<?php echo esc_html(get_the_title($post)); ?>
				</h1>
				<div class="text-center">
					<div class="mb-9 font-semibold text-[10px] uppercase text-white">
						<?php
						if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_AUTHOR) ) {
							?>
							<span class="pr-7">
								<i class="rbb-icon-human-user-10 pr-3 text-[13px]"></i>
								<?php echo esc_html__('By ', 'botanica'); ?><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?>
							</span>
							<?php
						}
						?>
						<?php
						if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_BLOG_DETAIL_SHOW_PUBLISH_DATE) ) {
							?>
							<span class="pr-7">
								<i class="rbb-icon-calendar-1 align-text-top leading-3 text-[22px]"></i>
								<span class="pl-2"><?php echo get_the_date(get_option('date_format'), $post); ?></span>
							</span>
							<?php
						}
						?>
					</div>
				</div>
				<div class="rbb-post-categories text-center">
					<?php
					wp_list_categories(
						[
							'include'  => $categories,
							'title_li' => '',
						]
					);
					?>
				</div>
			</div>
			<?php
		} else {
			?>
			<ul class="absolute rbb-post-categories bottom-2.5 left-4">
				<?php
				wp_list_categories(
					[
						'include'  => $categories,
						'title_li' => '',
					]
				);
				?>
			</ul>
			<?php
		}
		?>
		<?php
	}
	?>
</div>

<?php echo ( 'on_top' === $position ) ? '</div>' : ''; ?>
