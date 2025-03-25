<?php
/**
 * Page Title HTML for Demo Import.
 *
 * @package RisingBambooCore
 */

use RisingBambooCore\App\App;
use RisingBambooCore\Core\View;

?>
<div class="pl-[30px] pr-[30px]">
	<?php View::instance()->load('admin//pages/parts/header'); ?>
</div>
<div class="text-xl pt-[20px] pl-[30px] pr-[30px]">
	<h2><?php echo esc_html__('Demo Import', App::get_domain()); ?></h2>
</div>
