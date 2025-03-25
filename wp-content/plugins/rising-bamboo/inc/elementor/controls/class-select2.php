<?php
/**
 * RisingBambooCore
 *
 * @package RisingBambooCore
 */

namespace RisingBambooCore\Elementor\Controls;

use Elementor\Control_Select2;
use RisingBambooCore\Helper\Helper;

/**
 * Select2 Control.
 */
class Select2 extends Control_Select2 {


	/**
	 * Get type.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return Control::SELECT2;
	}

	/**
	 * Enqueue scripts and style.
	 *
	 * @return void
	 */
	public function enqueue(): void {
		Helper::register_asset('rbb-select2-control', 'js/admin/elementor/controls/select2/select2.js', [ 'jquery', 'jquery-elementor-select2' ], '1.0.0');
		wp_enqueue_script('rbb-select2-control');
	}

	/**
	 * Content Template.
	 *
	 * @return void
	 */
	public function content_template(): void {
		?>
		<div class="elementor-control-field">
			<# if ( data.label ) {#>
			<label for="<?php $this->print_control_uid(); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php $this->print_control_uid(); ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
					<# _.each( data.options, function( option_title, option_value ) {
					var value = data.controlValue;
					if ( typeof value == 'string' ) {
					var selected = ( option_value === value ) ? 'selected' : '';
					} else if ( null !== value ) {
					var value = _.values( value );
					var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
					}
					#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
