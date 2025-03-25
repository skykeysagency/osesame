/**
 * Sticker Header.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
'use strict';
class StickyHeader {
	constructor( el, options ) {
		this.el = el;

		this._defaults = {
			enable: true,
			height: false,
			behaviour: 'both',
		};

		this.options = Object.assign( this._defaults, options );

		if ( this.options.enable !== true ) return;

		this.$el = jQuery( el );
		this.$adminbar = jQuery( '#wpadminbar' );
		this.$dummy = jQuery( '<div class="sticky-dummy"></div>' );

		this.scrollUp = this.scrollUp.bind( this );
		this.scrollDown = this.scrollDown.bind( this );
		this.resize = this.resize.bind( this );

		this.stuck = false;
		this.hidden = false;

		if ( this.$el.height() < this.options.height ) {
			this.options.height = false;
		}

		this.init();
	}

	init() {
		if ( ! this.initalized ) {
			this.adminBarHeight =
				this.$adminbar.length > 0 ? this.$adminbar.height() : 0;
			this.adminBarFixed =
				this.$adminbar.length > 0
					? this.$adminbar.css( 'position' ) === 'fixed'
					: false;

			if ( ! this.adminBarFixed ) {
				this.adminBarHeight = 0;
			}

			this.elHeight = this.$el.outerHeight();
			this.elOffset = this.$el.offset().top;
			this.stuckHeight = this.options.height
				? this.options.height
				: this.elHeight;

			this.styleBackup = {
				height: this.el.style.height,
				left: this.el.style.left,
				position: this.el.style.position,
				top: this.el.style.top,
				transition: this.el.style.transition,
				transform: this.el.style.transform,
				visibility: this.el.style.visibility,
				opacity: this.el.style.opacity,
				width: this.el.style.width,
			};

			this.$dummy.height( this.elHeight );
			this.$dummy.insertAfter( this.el ).hide();

			jQuery( window ).on( 'scroll:up', this.scrollUp );
			jQuery( window ).on( 'scroll:down', this.scrollDown );
			jQuery( window ).on( 'resize', this.resize );

			this.scrollDown();

			this.initalized = true;

			this.trigger( 'onInit' );
		}
	}

	scrollUp() {
		const position = jQuery( window ).scrollTop();
		const heightAndOffset = this.elHeight + this.elOffset;

		if (
			position <=
			heightAndOffset - ( this.stuckHeight + this.adminBarHeight )
		) {
			const height = heightAndOffset - position - this.adminBarHeight;

			if ( height < this.elHeight ) {
				this.$el.height( height );
			} else {
				this.$el.height( this.elHeight );
				this.unstick();
			}
		}
		if ( position <= heightAndOffset ) {
			this.unstick();
		}
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
		const position = jQuery( window ).scrollTop();
		const heightAndOffset = this.elHeight + this.elOffset;
		if ( position >= heightAndOffset && jQuery( window ).width() >= 1024 ) {
			this.stick();
			if (
				this.options.behaviour === 'down' ||
				this.options.behaviour === 'both'
			) {
				this.show();
			} else {
				this.hide();
			}
		}
		if ( jQuery( window ).width() < 768 ) {
			if (
				position >= 70 &&
				jQuery( '.rbb_results' ).hasClass( 'active' )
			) {
				jQuery( '.rbb_results' ).removeClass( 'active' );
			}
		}
	}

	stick() {
		if ( ! this.stuck ) {
			this.$dummy.show();

			this.$el
				.height( this.stuckHeight )
				.addClass( 'header-stuck' )
				.css( {
					left: this.$el.offset().left,
					position: 'fixed',
					'z-index': 99999,
					top: this.adminBarHeight,
					width: '100%',
				} );
			jQuery( '.rbb_results' ).css( {
				top: this.$el.outerHeight(),
			} );
			this.stuck = true;
		}
	}

	unstick() {
		if ( this.stick ) {
			jQuery( '.rbb_results' ).css( {
				top: this.elHeight,
			} );
			this.$el.removeClass( 'header-stuck' ).css( this.styleBackup );

			this.$dummy.hide();

			this.stuck = false;
		}
	}

	show() {
		if ( this.hidden ) {
			this.$el.addClass( 'header-visible' ).css( {
				opacity: 1,
				visibility: 'visible',
				transition: 'transform 200ms linear',
				transform: 'translateY(0)',
			} );

			jQuery( window ).triggerHandler( 'header-sticky-shown' );

			this.hidden = false;
		}
	}

	hide() {
		if ( ! this.hidden ) {
			this.$el.removeClass( 'header-visible' ).css( {
				opacity: 0,
				transition: 'none',
				transform: 'translateY(-100%)',
				visibility: 'hidden',
			} );

			jQuery( window ).triggerHandler( 'header-sticky-hidden' );

			this.hidden = true;
		}
	}

	resize() {
		this.init();
	}

	destroy() {
		jQuery( window ).off( 'scroll:up', this.scrollUp );
		jQuery( window ).off( 'scroll:down', this.scrollDown );
		jQuery( window ).off( 'resize', this.resize );

		this.unstick();
		this.show();
		this.initalized = false;
		this.hidden = false;
	}

	trigger( hook, ...args ) {
		if ( typeof this.options[ hook ] === 'function' ) {
			this.options[ hook ].apply( this, args );
		}
	}
}

jQuery.fn.RbbStickyHeader = function () {
	const args = [];
	for ( let i = 0; i < arguments.length; i++ ) {
		args.push( arguments[ i ] );
	}
	return this.each( function () {
		const opts = args[ 0 ];
		let data = jQuery.data( this, 'RbbStickyHeader' );

		if ( ! data ) {
			data = jQuery.data(
				this,
				'RbbStickyHeader',
				new StickyHeader( this, opts )
			);
			return;
		}

		if (
			'string' === typeof opts &&
			'function' === typeof data[ opts ] &&
			opts.charAt( 0 ) !== '_'
		) {
			return data[ opts ].apply( data, args.slice( 1 ) );
		}
	} );
};

export default StickyHeader;
