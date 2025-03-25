<?php
/**
 * Default image layout.
 *
 * @package RisingBambooTheme
 * @version 1.0.0
 * @since 1.0.0
 */

?>

<div class="rbb-product-single-image-default sticky top-24 col-span-1 <?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>">
	<div class="product-single__sticky">
		<div class="overflow-hidden rounded-[6px] border border-[#f0f0f0]">
			<div class="rbb-single-product-slick flex" data-slick='{
					"dots": false,
					"arrows": true,
					"slidesToShow": 1,
					"fade": true,
					"adaptiveHeight": true,
					"infinite": true,
					"useTransform": true,
					"speed": 500,
					"cssEase": "cubic-bezier(0.77, 0, 0.18, 1)",
					"draggable": "draggable"
			}'>
				<?php
				foreach ( $attachment_ids as $attachment_id ) {
					$attachment_props = wc_get_product_attachment_props($attachment_id);
					if ( ! $attachment_props['url'] ) {
						continue;
					}
					?>
					<div class="rbb-slick-product-gallery__image">
						<a href="<?php echo esc_url($attachment_props['url']); ?>" data-image-id="<?php echo esc_attr($attachment_id); ?>" data-pswp-width="2000" data-pswp-height="2000" target="_blank">
							<img src="<?php echo esc_url($attachment_props['url']); ?>" alt="<?php echo esc_attr($attachment_props['alt']); ?>" class="zoomImg max-w-full">
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="rbb-single-product-thumbs-content">
			<div class="rbb-single-product-thumbs" data-slick='{
				"dots": false,
				"arrows": false,
				"slidesToShow": 5,
				"fade": false,
				"vertical" : false,
				"verticalSwiping":false,
				"infinite": true,
				"useTransform": false,
				"speed": 500,
				"cssEase": "cubic-bezier(0.77, 0, 0.18, 1)",
				"draggable": "draggable",
				"responsive": [
					{
						"breakpoint": 1310,
						"settings": {
							"vertical":false,
							"verticalSwiping": false,
							"slidesToShow": 4,
							"slidesToScroll": 4
						}
					},
					{
						"breakpoint": 992,
						"settings": {
							"vertical":false,
							"slidesToShow": 3,
							"slidesToScroll": 3
							}
						},
					{
						"breakpoint": 768,
						"settings": {
							"vertical":false,
							"slidesToShow": 4,
							"slidesToScroll": 2
						}
					},
					{
						"breakpoint": 576,
						"settings": {
							"vertical":false,
							"slidesToShow": 4,
							"slidesToScroll": 1
						}
					}
				]
			}'
			>
				<?php
				foreach ( $attachment_ids as $attachment_id ) {
					$attachment_props = wc_get_product_attachment_props($attachment_id);
					?>
					<div class="item px-[5px]">
						<div class="thumbnail overflow-hidden rounded-[6px] border border-[#f0f0f0] block w-full">
							<img data-image-id="<?php echo esc_attr($attachment_id); ?>" src="<?php echo esc_url($attachment_props['gallery_thumbnail_src']); ?>" alt="<?php echo esc_attr($attachment_props['alt']); ?>" class="rounded-lg w-full">
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
