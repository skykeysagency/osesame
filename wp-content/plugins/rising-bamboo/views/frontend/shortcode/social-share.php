<?php
/**
 * Social share shortcode template.
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\App\App;

if ( $facebook || $twitter || $linkedin || $pinterest || $tumblr || $email ) {
	$html_link = '';
	if ( $facebook ) {
		$html_link .= '<a href="http://www.facebook.com/sharer.php?u=' . esc_url($permalink) . '&i=' . esc_url($image) . '" title="' . esc_attr__('Facebook', App::get_domain()) . '" class="share-facebook pl-5 " target="_blank"><i class="rbb-icon-social-facebook-5"></i></a>';
	}
	if ( $twitter ) {
		$html_link .= '<a href="https://twitter.com/intent/tweet?url=' . esc_url($permalink) . '"  title="' . esc_attr__('Twitter', App::get_domain()) . '" class="share-twitter pl-5"><i class="rbb-icon-social-twitter-5"></i></a>';
	}
	if ( $linkedin ) {
		$html_link .= '<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . esc_url($permalink) . '"  title="' . esc_attr__('LinkedIn', App::get_domain()) . '" class="share-linkedin pl-5"><i class="rbb-icon-linkedin-01"></i></a>';
	}
	if ( $pinterest ) {
		$html_link .= '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url($permalink) . '&amp;media=' . esc_url($image) . '"  title="' . esc_attr__('Pinterest', App::get_domain()) . '" class="share-pinterest pl-5"><i class="rbb-icon-social-pinterest-2"></i></a>';
	}
	if ( $tumblr ) {
		$html_link .= '<a href="https://tumblr.com/share/link?url=' . esc_url($permalink) . '"  title="' . esc_attr__('Tumblr', App::get_domain()) . '" class="share-tumblr pl-5"><i class="rbb-icon-social-tumblr-1"></i></a>';
	}
	if ( $email ) {
		$html_link .= '<a href="mailto:?subject=' . $title . '&body=' . esc_url($permalink) . '"  title="' . esc_attr__('Email', App::get_domain()) . '" class="share-email pl-5"><i class="rbb-icon-email-4"></i></a>';
	}
	if ( 'enable' === $config['popup'] ) {
		?>
		<div class="rbb-social text-xs"><a class="flex items-center" onclick="RisingBambooModal.modal('.rbb-social-share', event)" href="#"><i
						class="rbb-icon-social-share-3 pr-[14px] text-base"></i><span><?php echo esc_html__('Social Share', App::get_domain()); ?></span></a>
		</div>
		<div class="rbb-social-share hidden">
			<div class="rbb-social-share-inner rounded-lg">
				<div class="copy-link">
					<div class="social-title font-bold mb-3"><?php echo esc_html__('Copy link', App::get_domain()); ?></div>
				</div>
				<div class="nov-copy flex pb-[15px]"><input class="w-full rounded" type="text"
															value="<?php echo esc_url($permalink); ?>"/>
					<div class="copy-btn bg_pr white font-semibold text-center cursor-pointer" data-copy="Copy" data-copied="Copied"><?php echo esc_html__('Copy', App::get_domain()); ?></div>
				</div>
				<div class="mb-[10px] font-bold share-title"><?php echo esc_html__('Share', App::get_domain()); ?></div>
				<div class="share-list text-xs"><?php echo wp_kses_post($html_link); ?></div>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="rbb-social-share-wrapper flex">
			<label class="uppercase font-bold"><i class="rbb-icon-social-share-3 pr-3"></i>
				<?php echo esc_html__('Social Share :', App::get_domain()); ?>
			</label>
			<div class="rbb-social-share_2">
				<?php echo wp_kses_post($html_link); ?>
			</div>
		</div>
		<?php
	}
}
?>
