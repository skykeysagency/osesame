/**
 * Pagination.
 *
 * @version 1.0.0
 * @since 1.0.0
 */

'use strict';

const RbbThemePagination = {
	init() {
		this.pagination();
		this.load_more();
		this.infinity();
	},
	pagination() {
		jQuery( document.body ).on(
			'click',
			'.rbb-woo-pagination a.page-numbers',
			function ( evt ) {
				evt.preventDefault();

				const $this = jQuery( this ),
					url = $this.attr( 'href' );
				window.RbbWoocommerceFilters.filterByUrl( url );
			}
		);
	},
	load_more() {
		jQuery( document.body ).on(
			'click',
			'.rbb-woo-pagination .rbb-load-more',
			function ( evt ) {
				evt.preventDefault();

				const $this = jQuery( this ),
					url = $this.data( 'url' );
				if ( ! $this.hasClass( 'loading' ) ) {
					window.RbbWoocommerceFilters.filterByUrl( url, true );
				}
			}
		);
	},
	infinity() {
		const $el = jQuery( '.woocommerce-pagination' );
		if ( 'infinity' === $el.data( 'type' ) ) {
			let $last = 0;
			const $window = jQuery( window );

			$window.on( 'scroll', function () {
				const current = jQuery( this ).scrollTop();
				if ( current > $last ) {
					const $windowHeight = $window.height(),
						// 90% window height.
						$halfWH = parseInt( ( 90 / 100 ) * $windowHeight ),
						$elOffsetTop = $el.offset().top,
						$elHeight = $el.outerHeight( true ),
						$offsetTop = $elOffsetTop + $elHeight,
						$finalOffset = $offsetTop - $halfWH;

					if ( current >= $finalOffset ) {
						const $button = $el.find( '.rbb-load-more' );
						if ( ! $button.hasClass( 'loading' ) ) {
							$button.trigger( 'click' );
						}
					}
				}

				$last = current;
			} );
		}
	},
};

export default RbbThemePagination;
