<?php
/**
 * RisingBambooTheme
 *
 * @package RisingBambooTheme.
 */

?>
<div id="rbb-search-content" class="style-2 invisible opacity-0 fixed inset-0 px-[15px] transition-all duration-500 bg-white">
	<div class="rbb-search-top max-w-[850px] z-50 mx-auto relative">
		<div onclick="RbbThemeSearch.closeSearchForm(event)" class="close-search relative mt-[74px] mx-auto mb-[100px] w-10 h-10 rounded-full text-center leading-10 cursor-pointer">✕
		</div>
		<div id="_desktop_search">
			<?php get_search_form([ 'overlay' => true ]); ?>
		<?php
		echo get_template_part('template-parts/components/search/search-result');
		?>
		</div>
	</div>
</div>
