<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;

?>
<div class="container mx-auto">
	<?php
	View::instance()->load('admin/pages/parts/header');
	?>
	<div class="grid grid-cols-3 gap-4 bg-white my-20 p-12">
		<div class="col-span-2 flex items-center">
			<div class="relative px-4 py-3 leading-normal text-red-700" role="alert">
			<span class="absolute inset-y-0 left-0 flex items-center ml-4">
				<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
					<path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
							clip-rule="evenodd" fill-rule="evenodd"></path>
				</svg>
			</span>
				<p class="ml-6 md:text-sm font-semibold"><?php echo wp_kses_post($message); ?></p>
			</div>
		</div>
		<div class="col-span-1">
			<img src="<?php echo esc_url(RBB_CORE_DIST_URL . 'images/error.gif'); ?>">
		</div>
	</div>
</div>
