/**
 * Mobile Navigation.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
'use strict';

class RbbMobileNavigation {
	constructor( el, options ) {
		this.el = el;

		this._defaults = {
			behaviour: 'both',
		};

		this.options = Object.assign( this._defaults, options );

		this.$el = jQuery( el );

		this.scrollUp = this.scrollUp.bind( this );
		this.scrollDown = this.scrollDown.bind( this );
		this.init();
	}

	init() {
		if ( ! this.initalized ) {
			if ( this.options.behaviour === 'both' ) {
				this.show();
			}
			jQuery( window ).on( 'scroll:up', this.scrollUp );
			jQuery( window ).on( 'scroll:down', this.scrollDown );
			this.initalized = true;
		}
	}

	scrollUp() {
		if (
			this.options.behaviour === 'up' ||
			this.options.behaviour === 'both'
		) {
			this.show();
		} else {
			this.hide();
		}
	}

	scrollDown() {
		if (
			this.options.behaviour === 'down' ||
			this.options.behaviour === 'both'
		) {
			this.show();
		} else {
			this.hide();
		}
	}

	show() {
		this.$el.removeClass( 'hidden' );
	}

	hide() {
		this.$el.addClass( 'hidden' );
	}

	destroy() {
		jQuery( window ).off( 'scroll:up', this.scrollUp );
		jQuery( window ).off( 'scroll:down', this.scrollDown );
		this.initalized = false;
	}

	trigger( hook, ...args ) {
		if ( typeof this.options[ hook ] === 'function' ) {
			this.options[ hook ].apply( this, args );
		}
	}
}

export default RbbMobileNavigation;
