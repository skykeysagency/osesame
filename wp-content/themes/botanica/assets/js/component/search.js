/**
 * Search component.
 *
 * @version 1.0.0
 * @since 1.0.0
 */

'use strict';

import _ from 'lodash';
import axios from 'axios';
import Qs from 'qs';
import DOMPurify from 'dompurify';

const lodash = _.noConflict();
const RbbThemeSearch = {
	openSearchForm( event, id = 'rbb-search-content' ) {
		event.stopPropagation();
		const $element = jQuery( '#' + id );
		$element
			.removeClass( 'invisible' )
			.removeClass( 'opacity-0' )
			.removeClass( 'active' );
		$element.addClass( 'visible opacity-100 active' );
		jQuery( '.rbb-product-search-content' ).addClass( 'active' );
	},
	closeSearchForm( event, id = 'rbb-search-content' ) {
		event.stopPropagation();
		const $element = jQuery( '#' + id );
		$element.removeClass( 'visible opacity-100 active' );
		$element.addClass( 'invisible opacity-0' );
		jQuery( '.rbb_results' ).removeClass( 'active' );
		jQuery( '.rbb-product-search-content' ).removeClass( 'active' );
	},
	openSearchCategories( event ) {
		const element = event.target;
		const content = jQuery( element ).parent().siblings( '.categories' );
		if ( content.hasClass( 'active' ) ) {
			content.removeClass( 'active' );
			jQuery( element )
				.siblings( 'i' )
				.removeClass( 'rbb-icon-direction-35' );
			jQuery( element )
				.siblings( 'i' )
				.addClass( 'rbb-icon-direction-42' );
		} else {
			content.addClass( 'active' );
			jQuery( element )
				.siblings( 'i' )
				.removeClass( 'rbb-icon-direction-42' );
			jQuery( element )
				.siblings( 'i' )
				.addClass( 'rbb-icon-direction-35' );
		}
		jQuery( document ).mouseup( function ( e ) {
			if ( jQuery( e.target ).closest( content.parent() ).length === 0 ) {
				jQuery( content ).removeClass( 'active' );
				jQuery( element )
					.siblings( 'i' )
					.removeClass( 'rbb-icon-direction-35' );
				jQuery( element )
					.siblings( 'i' )
					.addClass( 'rbb-icon-direction-42' );
			}
		} );
	},
	chooseCategory( event, slug ) {
		const $element = event.target;
		jQuery( $element )
			.parent()
			.siblings( 'input[name="product_cat"]' )
			.val( slug );
		jQuery( $element )
			.parent()
			.siblings( '.current-category' )
			.find( 'span' )
			.html( jQuery( $element ).html() );
		const listItems = document.querySelectorAll(
			'.rbb-search-categories .current-category > span'
		);
		listItems.forEach( ( item ) => {
			const textContent = item.textContent.trim();
			item.textContent = textContent.replace( /-/g, '' );
		} );
	},
	ajaxSearch() {
		const $this = this;
		const $forms = jQuery(
			'.rbb-default-header .rbb-ajax-search,#rbb-search-content .rbb-ajax-search'
		);
		const $resultElement = jQuery( '.rbb-search-result' );
		const $resultOffset = $resultElement
			.parent()
			.siblings( '.rbb-search-top' )
			.outerHeight();
		if ( $resultOffset ) {
			$resultElement
				.find( '.result' )
				.css(
					'max-height',
					'calc(100vh - ' +
						lodash.parseInt( $resultOffset + 100 ) +
						'px)'
				);
		}
		$forms.each( function () {
			const $form = this;
			const $resultEle = jQuery( '.rbb_results' );
			jQuery( '.input-search', $form ).on(
				'keyup',
				lodash.debounce( function ( e ) {
					if (
						! lodash.isEmpty(
							lodash.trim( jQuery( e.target ).val() )
						)
					) {
						$this.doAjaxSearch(
							jQuery( $form ),
							$resultElement,
							$resultOffset
						);
						$resultEle.addClass( 'active' );
						jQuery( 'body' )
							.css( 'overflow', 'hidden' )
							.addClass( 'active' );
						if (
							jQuery( '.rbb-product-search-content2' ).hasClass(
								'results-full'
							)
						) {
							$resultEle.addClass( ' style-results-2' );
						} else {
							$resultEle.addClass( ' 2xl:w-[1280px] mx-auto' );
						}
					} else {
						$resultElement.find( '.result' ).html( '' );
						$resultEle.removeClass( 'active' );
						jQuery( 'body' )
							.css( 'overflow', 'auto' )
							.removeClass( 'active' );
					}
				}, 1000 )
			);
			jQuery( '.rbb-search-popular li' ).on( 'click', function () {
				const $keyword = jQuery( this ).html();
				jQuery( '.input-search', $form )
					.val( $keyword )
					.trigger( 'keyup' );
			} );
		} );
	},
	doAjaxSearch( $form, $resultElement ) {
		const $url = $form.data( 'url' );
		const $limit = $form.data( 'limit' );
		const $keyword = $form.find( 'input.input-search' ).val().toLowerCase();
		const $productCat = $form.find( "input[name='product_cat']" ).val();
		const $noResultText = $form.data( 'noresult' );
		const $noResultElement = $resultElement.find( '.no-result' );
		const $result = $resultElement.find( '.result' );

		$resultElement.find( '.rbb-spinner' ).removeClass( 'invisible' );
		$noResultElement.html( '' );
		$result.html( '' );

		const $data = {
			action: 'rbb_ajax_product_search',
			limit: $limit,
			no_result_text: $noResultText,
			keyword: $keyword,
			product_cat: $productCat,
			nonce: window.rbb_vars.nonce,
		};
		axios
			.post( $url, Qs.stringify( $data ) )
			.then( function ( response ) {
				const $resData = response.data;
				const $length = $resData.length;
				if ( $length ) {
					let html = '';
					for ( let x = 0; x < $length; x++ ) {
						const obj = $resData[ x ];
						const name = obj.name
							.toLowerCase()
							.replace(
								$keyword,
								'<strong>' + $keyword + '</strong>'
							);
						const link = obj.link;
						html +=
							'<div class="search-item item-' + x + ' md:pb-6" >';
						html +=
							'<div class="rounded-xl border overflow-hidden">';
						html +=
							'<a class="block text-center" href="' +
							link +
							'"><img class="item-image inline-block w-full" alt="' +
							name +
							'" src="' +
							obj.image +
							'" /></a>';
						html += '</div><!-- img -->';
						html += '<div class="item-content mt-4">';
						html +=
							'<a class="product_name capitalize block text-sm mb-3 font-bold" href="' +
							link +
							'">' +
							name +
							'</a>';
						html +=
							'<div class="item-price text-xs font-extrabold">' +
							obj.price +
							'</div><!-- .item-price -->';
						html += '</div><!-- .search-item-content -->';
						html += '</div><!-- .search-item -->';
					}
					$result.html( DOMPurify.sanitize( html ) );
				} else {
					$noResultElement.html( $noResultText );
				}
			} )
			.catch( function ( error ) {
				// eslint-disable-next-line no-console
				console.warn( 'Ajax product search occur error: ' + error );
			} )
			.finally( function () {
				$resultElement.find( '.rbb-spinner' ).addClass( 'invisible' );
			} );
		if ( jQuery( window ).width() < 768 ) {
			const $mobileHeight = jQuery( window ).height() - 240;
			jQuery( '.rbb-search-result-ajax' ).css( {
				height: $mobileHeight,
				overflow: 'hidden',
			} );
		}
	},
};

export default RbbThemeSearch;
