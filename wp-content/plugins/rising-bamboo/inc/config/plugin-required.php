<?php
/**
 * List required plugins
 *
 * @package Rising_Bamboo
 */

use RisingBambooCore\App\App;

return [
	[
		'name'     => esc_html__('One Click Demo Import', App::get_domain()),
		'slug'     => 'one-click-demo-import',
		'required' => true,
	],
];
