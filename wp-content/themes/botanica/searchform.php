<?php
/**
 * Search form template call at : get_search_form().
 *
 * @package RisingBambooTheme.
 */

use RisingBambooTheme\Helper\Setting;
use RisingBambooCore\Helper\Woocommerce as RisingBambooCoreWoocommerceHelper;

$categories = class_exists(RisingBambooCoreWoocommerceHelper::class) ? RisingBambooCoreWoocommerceHelper::get_flat_categories() : [];
$limit      = Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_RESULT_LIMIT);
$class_form = 'rbb-search-form';
if ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_AJAX) ) {
	$class_form .= ' rbb-ajax-search';
}
$category_slug = get_query_var('product_cat');
/**
 * Argument pass from get_search_form.
 *
 * @var array $args The args variable
 */
if ( isset($args['overlay']) && true === $args['overlay'] ) {

	?>
<form role="search" method="get" class="<?php echo esc_attr($class_form); ?>" action="<?php echo esc_url(home_url('/')); ?>" data-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-noresult="<?php echo esc_attr__('No Result', 'botanica'); ?>" data-limit="<?php echo esc_attr($limit); ?>">
	<div class="relative input-group z-20 flex bg-white rounded-[30px]">
		<?php if ( $categories && Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_BY_CATEGORY) ) { ?>
			<div class="rbb-search-categories relative flex-grow h-12">
				<div class="current-category flex items-center h-full w-full inline-block py-2 px-5 cursor-pointer leading-[48px]" onclick="RbbThemeSearch.openSearchCategories(event)">
								<span class="flex-grow font-semibold uppercase text-[11px] whitespace-nowrap">
								<?php
								if ( isset($categories[ $category_slug ]) ) {
									echo wp_kses_post($categories[ $category_slug ]);
								} else {
									echo esc_html__('Category', 'botanica');
								}
								?>
								</span>
					<i class="rbb-icon"></i>
				</div>
				<ul class="categories absolute left-0 top-full z-50 py-2 bg-white rounded-[3px] shadow-lg">
					<li class="dropdown-item cursor-pointer font-bold text-[11px] px-5 py-1.5 active"
						onclick="RbbThemeSearch.chooseCategory(event, '')"><?php echo esc_html__('All Categories', 'botanica'); ?></li>
					<?php foreach ( $categories as $cat_slug => $cat_name ) { ?>
						<li class="dropdown-item cursor-pointer text-[11px] whitespace-nowrap <?php echo esc_attr(( $cat_slug === $category_slug ) ? 'active' : ''); ?>"
							onclick="RbbThemeSearch.chooseCategory(event,'<?php echo esc_attr($cat_slug); ?>')">
							<?php echo wp_kses_post(str_replace(' ', '-', $cat_name)); ?>
						</li>
					<?php } ?>
				</ul>
				<input type="hidden" name="product_cat" class="product-cat" value=""/>
			</div>
		<?php } ?>
		<input class="input-search s w-full pb-0 border-solid" type="text" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php echo esc_attr__('What Are You Looking For ?', 'botanica'); ?>" autocomplete="off"/>
		<button id="search" class="button-icon btn-search duration-300 absolute w-[70px] h-[52px] text-center md:-right-[2px] right-0 -top-[2px]"
				type="submit">
			<span class="search-icon <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON)); ?> text-xl"></span>
		</button>
		<div class="hidden btn-search_clear-text absolute right-5 top-5 cursor-pointer text-[#cdcdcd]"><i class="rbb-icon-close-1"></i></div>
	</div>
	<input type="hidden" name="post_type" value="product"/>
</form>
	<?php
} else {
	$header = Setting::get(RISING_BAMBOO_KIRKI_SECTION_LAYOUT_HEADER);
	?>
<form role="search" method="get" class="<?php echo esc_attr($class_form); ?>" action="<?php echo esc_url(home_url('/')); ?>" data-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-noresult="<?php echo esc_attr__('No Result', 'botanica'); ?>" data-limit="<?php echo esc_attr($limit); ?>">
	<div class="relative input-group flex bg-white rounded-[--rbb-search-input-border-radius]">
		<?php if ( $categories && ( Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_BY_CATEGORY) ) ) { ?>
			<div class="inline-table items-center search-categories">
				<div class="rbb-search-categories relative flex h-[50px]">
					<div class="current-category flex items-center h-full w-full inline-block py-2 px-5 cursor-pointer md:leading-[49px] leading-[58px]" onclick="RbbThemeSearch.openSearchCategories(event)">
						<span class="flex-grow font-semibold uppercase text-[11px] whitespace-nowrap">
							<?php
							if ( isset($categories[ $category_slug ]) ) {
								echo wp_kses_post($categories[ $category_slug ]);
							} else {
								echo esc_html__('Category', 'botanica');
							}
							?>
						</span>
						<i class="rbb-icon"></i>
					</div>
					<ul class="categories absolute left-0 top-full z-50 py-2 bg-white rounded-[3px] shadow-lg">
						<li class="dropdown-item cursor-pointer font-bold text-[11px] px-5 py-1.5 active"
							onclick="RbbThemeSearch.chooseCategory(event, '')"><?php echo esc_html__('All Categories', 'botanica'); ?></li>
						<li class="dropdown-item cursor-pointer text-[11px] px-5 py-1.5"
							onclick="RbbThemeSearch.chooseCategory(event, '')"><?php echo esc_html__('Home', 'botanica'); ?></li>
						<?php foreach ( $categories as $cat_slug => $cat_name ) { ?>
							<li class="dropdown-item cursor-pointer text-[11px] px-5 py-1.5 whitespace-nowrap <?php echo esc_attr(( $cat_slug === $category_slug ) ? 'active' : ''); ?>"
								onclick="RbbThemeSearch.chooseCategory(event,'<?php echo esc_attr($cat_slug); ?>')">
								<?php echo wp_kses_post(preg_replace('/^\s+(?=\S)/', '-', $cat_name)); ?>
							</li>
						<?php } ?>
					</ul>
					<input type="hidden" name="product_cat" class="product-cat" value=""/>
				</div>
			</div>
		<?php } ?>
		<div class="relative w-full">
			<?php if ( in_array($header, [ 'header-07', 'header-08', 'header-10' ], true) ) { ?>
				<span class="icon-search <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON)); ?>"></span>
			<?php } ?>
			<input class="input-search s w-full pb-0 border-solid" type="text" placeholder="<?php echo esc_attr__('Enter Your Keyword', 'botanica'); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off"/>
			<label class="whitespace-nowrap md:block hidden duration-300 pointer-events-none text-[13px] text-[#a0a0a0] absolute top-[51%] -translate-y-1/2 left-6"><?php echo esc_html__('Enter Your Keyword', 'botanica'); ?></label>
			<button class="button-icon btn-search absolute flex items-center justify-center w-[70px] h-[52px] text-center md:-right-[2px] right-0 -top-[2px] duration-300" type="submit">
				<span class="search-icon <?php echo esc_attr(Setting::get(RISING_BAMBOO_KIRKI_FIELD_COMPONENT_SEARCH_ICON)); ?> md:text-xl text-[0px]"></span>
				<span class="text-search text-[0px]"><?php echo esc_html__('search', 'botanica'); ?></span>
			</button>
			<div class="hidden btn-search_clear-text absolute right-5 top-5 cursor-pointer text-[#cdcdcd]"><i class="rbb-icon-close-1 md:text-[0px]"></i></div>
		</div>
	</div>
	<input type="hidden" name="post_type" value="product"/>
</form>
<?php } ?>
