<?php
/**
 * RisingBambooCore Package
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Kirki;

use RisingBambooCore\Core\Singleton;
use RisingBambooCore\Helper\License;

/**
 * This is a wrapper class for Kirki.
 * If the Kirki plugin is installed, then all CSS & Google fonts
 * will be handled by the plugin.
 * In case the plugin is not installed, this acts as a fallback
 * ensuring that all CSS & fonts still work.
 * It does not handle the customizer options, simply the frontend CSS.
 */
class Kirki extends Singleton {

	/**
	 * The config ID.
	 *
	 * @static
	 * @access protected
	 * @var array
	 */
	protected static array $config = [];
	
	/**
	 *
	 */
	public function __construct()
	{
		if(!class_exists(\Kirki::class)) {
			require_once RBB_CORE_INC_DIR. 'kirki/kirki.php';
		}
	}
	/**
	 * Get the value of an option from the db.
	 *
	 * @param string $config_id The ID of the configuration corresponding to this field.
	 * @param string $field_id  The field_id (defined as 'settings' in the field arguments).
	 * @return mixed            The saved value of the field.
	 */
	public static function get_option( string $config_id = '', string $field_id = '' ) {
		return \Kirki::get_option($config_id, $field_id);
	}

	/**
	 * Create a new panel.
	 *
	 * @param string $id   The ID for this panel.
	 * @param array  $args The panel arguments.
	 */
	public static function add_panel( string $id = '', array $args = [] ): void {
		\Kirki::add_panel($id, $args);
	}

	/**
	 * Create a new section.
	 *
	 * @param string $id   The ID for this section.
	 * @param array  $args The section arguments.
	 */
	public static function add_section( string $id, array $args ): void {
		if ( class_exists(\Kirki::class) ) {
			\Kirki::add_section($id, $args);
		}
		/* If Kirki does not exist then there's no reason to add any sections. */
	}

	/**
	 * Sets the configuration options.
	 *
	 * @param string $config_id The configuration ID.
	 * @param array  $args      The configuration arguments.
	 */
	public static function add_config( string $config_id, array $args = [] ): void {
		\Kirki::add_config($config_id, $args);
	}

	/**
	 * Create a new field
	 *
	 * @param string $config_id The configuration ID.
	 * @param array  $args The field's arguments.
	 * @return void
	 */
	public static function add_field( string $config_id, array $args ): void {

		// if Kirki exists, use it.
		if ( class_exists(\Kirki::class) ) {
            if(!License::is_activated()) {
                if('toggle' === $args['type']) {
                    $args['input_attrs']['disabled'] = true;
                } elseif('select' === $args['type']) {
                    $args['placeholder'] = __('Please activate the theme license to change this setting!');
                    if(isset($args['choices'])) {
                        if(!empty($args['default'])) {
                            foreach ($args['choices'] as $key => $val) {
                                if($key !== $args['default']) {
                                    unset($args['choices'][$key]);
                                }
                            }
                        } elseif(count($args['choices'])) {
	                        $first_key = array_key_first($args['choices']);
	                        $args['choices'] = [
		                        $first_key => $args['choices'][$first_key]
	                        ];
                        }
                    }
                } else {
                    $args = wp_parse_args($args, [
                        'input_attrs' => [
                            'readonly' => true
                        ],
                    ]);
                }
            }
			\Kirki::add_field($config_id, $args);
		}
	}
}
