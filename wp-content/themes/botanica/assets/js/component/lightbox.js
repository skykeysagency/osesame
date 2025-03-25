/**
 * Light Box.
 *
 * @version 1.0.0
 * @since 1.0.0
 */

'use strict';
// eslint-disable-next-line import/no-unresolved
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import PhotoSwipe from 'photoswipe';
class RbbThemeLightBox {
	constructor( $id ) {
		const leftArrowSVGString =
			'<svg aria-hidden="true" class="pswp__icn" viewBox="0 0 11 21"> <polyline fill="none" stroke="currentColor"  points="10.5 0.5 0.5 10.5 10.5 20.5" stroke-width="1.25"></polyline></svg>';
		const rightArrowSVGString =
			'<svg aria-hidden="true" class="pswp__icn" viewBox="0 0 11 21"><polyline fill="none" stroke="currentColor" points="0.5 0.5 10.5 10.5 0.5 20.5" stroke-width="1.25"></polyline></svg>';
		const closeArrowSVGString =
			'<svg aria-hidden="true" class="pswp__icn" viewBox="0 0 16 14"><path d="M15 0L1 14m14 0L1 0" stroke="currentColor" fill="none" fill-rule="evenodd"></path></svg>';
		const lightbox = new PhotoSwipeLightbox( {
			gallery: $id,
			children: 'a',
			bgOpacity: 1,
			showHideAnimationType: 'zoom',
			loop: false,
			counter: false,
			zoom: false,
			bgClickAction: false,
			arrowPrevSVG: leftArrowSVGString,
			arrowNextSVG: rightArrowSVGString,
			closeSVG: closeArrowSVGString,
			pswpModule: PhotoSwipe,
		} );
		lightbox.on( 'uiRegister', function () {
			lightbox.pswp.ui.registerElement( {
				name: 'bulletsIndicator',
				className: 'pswp__bullets-navigation',
				appendTo: 'wrapper',
				// eslint-disable-next-line no-unused-vars
				onInit: ( el, pswp ) => {
					let $prev;
					let $close;
					let $next;
					// eslint-disable-next-line prefer-const
					$prev = document.createElement( 'div' );
					$prev.className = 'pswp__bullets-prev';
					// eslint-disable-next-line prefer-const
					$close = document.createElement( 'div' );
					$close.className = 'pswp__bullets-close';
					// eslint-disable-next-line prefer-const
					$next = document.createElement( 'next' );
					$next.className = 'pswp__bullets-next';
				},
			} );
		} );
		lightbox.init();
	}
}
export default RbbThemeLightBox;
