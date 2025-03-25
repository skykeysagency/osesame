<?php
namespace RisingBambooCore\Kirki\Control;

use RisingBambooCore\App\App;
use RisingBambooCore\Helper\Helper;
use RisingBambooCore\Helper\License;
use RisingBambooCore\App\Admin\RbbIcons as RbbIconsAdmin;

/**
 * @package RisingBambooCore.
 */

class RbbIcons extends \Kirki_Control_Base {

    /**
     * The control type.
     *
     * @access public
     * @var string
     */
    public $type = 'rbb-icons';
    
    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @access public
     */
    public function to_json() {
        parent::to_json();
        $this->json['icons'] = RbbIconsAdmin::get_rbb_icons();
    }

    /**
     * An Underscore (JS) template for this control's content (but not its container).
     *
     * Class variables for this control class are available in the `data` JS object;
     * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
     *
     * @see WP_Customize_Control::print_template()
     *
     * @access protected
     */
    protected function content_template() {
        ?>
        <# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
        <?php if(License::is_activated()) { ?>
        <# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>
        <?php } else { ?>
            <span class="description rbb-error-text customize-control-description"><?php echo esc_html__('Please activate the theme license to change the icon!'); ?></span>
        <?php } ?>
            <div class="icons-wrapper">
            <# if ( ! _.isUndefined( data.choices ) && 1 < _.size( data.choices ) ) { #>
            <# for ( key in data.choices ) { #>
            <input {{{ data.inputAttrs }}} <?php echo esc_attr(License::is_activated() ? '' : 'disabled'); ?> class="rbb-icons-select" type="radio" value="{{ data.choices[ key ] }}" name="_customize-rbb-icon-radio-{{ data.id }}" id="{{ data.id }}-{{ data.choices[ key ] }}" {{{ data.link }}}<# if ( data.value === data.choices[ key ] ) { #> checked="checked"<# } #>>
            <label for="{{ data.id }}-{{ data.choices[ key ] }}"><span class="{{ data.choices[ key ] }}"></span></label>
            </input>
            <# } #>

            <# } else { #>

            <# for ( type in data.icons ) { #>
            <h4>{{type.toUpperCase()}}</h4>
                <# for ( key in data.icons[type] ) { #>
                <input {{{ data.inputAttrs }}} <?php echo esc_attr(License::is_activated() ? '' : 'disabled'); ?> class="rbb-icon-select" type="radio" value="{{ data.icons[type][ key ] }}" name="_customize-rbb-icon-radio-{{ data.id }}" id="{{ data.id }}-{{ data.icons[type][ key ] }}" {{{ data.link }}}<# if ( data.value === data.icons[type][ key ] ) { #> checked="checked"<# } #>>
                <label for="{{ data.id }}-{{ data.icons[type][ key ] }}"><span class="{{ data.icons[type][ key ] }}"></span></label>
                </input>
                <# } #>
            <# } #>

            <# } #>
        </div>
        <?php
    }
}
