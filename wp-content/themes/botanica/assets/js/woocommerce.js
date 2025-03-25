/**
 * Woocommerce.
 *
 * @version 1.0.0
 * @since 1.0.0
 */

'use strict';

import RbbThemeSlickJs from './component/slick';
import RbbThemeSwatches from './component/woocommerce/swatches';
import RbbThemePagination from './component/woocommerce/pagination';

class RisingBambooWoocommerce {
	/**
	 * Construct.
	 */
	constructor() {
		const $this = this;
		$this.quantity();
		$this.slickJsForDefaultImageLayout();
		$this.slickJsForSliderImageLayout();
		$this.slickJsForScrollImageLayout();
		$this.reInitSlickSliderForQuickView();
		$this.reInitAddToCartFormVariation();
		$this.ajaxAddToCartRedirect();
		$this.tabProduct();
		$this.onWindowResize();
		$this.updateTotalWishlist();
		$this.categoryTopBar();
		$this.filterSortBy();
		$this.filterSidebar();
		$this.rbbAccordion();
		$this.imageZoom();
		$this.selectAjaxBeforeSend();
		$this.selectAjaxComplete();
		$this.rbbAjaxProductLoaded();
		$this.canvasFilter();
		$this.filterClickLoading();
		jQuery( window ).on( 'resize', function () {
			$this.onWindowResize();
		} );
		jQuery( document ).on( 'click', '.rbb-filter-title', function () {
			$this.filterClickLoading();
		} );
		RbbThemePagination.init();
	}
	/**
	 * Quantity.
	 */
	quantity() {
		jQuery( document ).on( 'click', '.plus, .minus', function () {
			const $qtyInput = jQuery( this )
				.closest( '.quantity' )
				.find( '.qty' );
			let $qty = parseFloat( $qtyInput.val() ),
				$qtyMax = parseFloat( $qtyInput.attr( 'max' ) ),
				$qtyMin = parseFloat( $qtyInput.attr( 'min' ) ),
				$qtyStep = $qtyInput.attr( 'step' );

			// eslint-disable-next-line no-unused-expressions
			( $qty && '' !== $qty && 'NaN' !== $qty ) || ( $qty = 0 );
			// eslint-disable-next-line no-unused-expressions
			( '' === $qtyMax || 'NaN' === $qtyMax ) && ( $qtyMax = '' );
			// eslint-disable-next-line no-unused-expressions
			( '' === $qtyMin || 'NaN' === $qtyMin ) && ( $qtyMin = 0 );
			// eslint-disable-next-line no-unused-expressions
			( 'any' === $qtyStep ||
				'' === $qtyStep ||
				void 0 === $qtyStep ||
				'NaN' === parseFloat( $qtyStep ) ) &&
				( $qtyStep = 1 );

			// eslint-disable-next-line no-nested-ternary,no-unused-expressions
			jQuery( this ).is( '.plus' )
				? $qtyInput.val(
						$qtyMax && ( $qtyMax === $qty || $qty > $qtyMax )
							? $qtyMax
							: $qty + parseFloat( $qtyStep )
				  )
				: $qtyMin && ( $qtyMin === $qty || $qtyMin > $qty )
				? $qtyInput.val( $qtyMin )
				: $qty > 0 && $qtyInput.val( $qty - parseFloat( $qtyStep ) );
			$qtyInput.trigger( 'change' );
		} );
		jQuery( document.body ).on( 'change', 'input.qty', function () {
			const $qty = jQuery( this ).val();
			jQuery( this )
				.closest( 'form.cart' )
				.find( '.ajax_add_to_cart' )
				.attr( 'data-quantity', $qty );
		} );
	}
	slickJsForDefaultImageLayout() {
		const $galleryElement =
			'.rbb-product-single-image-default .rbb-slick-product-gallery';
		const $thumbElement =
			'.rbb-product-single-image-default .rbb-slick-product-thumb';
		new RbbThemeSlickJs( $galleryElement, {
			asNavFor: $thumbElement,
		} );
		new RbbThemeSlickJs( $thumbElement, {
			asNavFor: $galleryElement,
			focusOnSelect: true,
		} );
		RbbThemeSwatches.integrateSwatchesToDefaultSlider(
			$galleryElement,
			$thumbElement
		);
	}
	slickJsForSliderImageLayout() {
		const $galleryElement =
			'.rbb-product-single-image-slider .rbb-slick-product-gallery';
		new RbbThemeSlickJs( $galleryElement, {} );
		RbbThemeSwatches.integrateSwatchesToSlider( $galleryElement );
	}
	slickJsForScrollImageLayout() {
		const $galleryElement = jQuery(
			'.rbb-product-single-image-scroll .rbb-product-gallery'
		);
		const $thumbElement = jQuery(
			'.rbb-product-single-image-scroll .rbb-slick-product-thumb'
		);
		new RbbThemeSlickJs( $thumbElement, {} );
		if ( jQuery( window ).width() > 991 ) {
			jQuery( window ).on(
				'mousewheel DOMMouseScroll wheel',
				function ( e ) {
					$galleryElement.find( '.item.act' ).each( function () {
						const item = jQuery( this ),
							$position = item.data( 'position' ),
							$hd = item.height() / 2,
							$srt = jQuery( window ).scrollTop(),
							$deltaY = e.originalEvent.deltaY,
							$offsetTop = item.offset().top;
						if ( $deltaY > 0 ) {
							let $npd = $position;
							if (
								$position <
								$galleryElement.find( '.item' ).length
							) {
								$npd = $position + 1;
							}
							if ( $srt > $offsetTop + $hd ) {
								item.removeClass( 'act' );
								$galleryElement
									.find(
										'.item[data-position="' + $npd + '"]'
									)
									.addClass( 'act' );
								$thumbElement
									.find(
										'.item[data-position="' +
											$position +
											'"]'
									)
									.removeClass( 'active' );
								$thumbElement
									.find(
										'.item[data-position="' + $npd + '"]'
									)
									.addClass( 'active' );
							}
						} else {
							let npu = $position;
							if ( $position > 1 ) {
								npu = $position - 1;
							}
							if ( $srt < $offsetTop - $hd ) {
								item.removeClass( 'act' );
								$galleryElement
									.find(
										'.item[data-position="' + npu + '"]'
									)
									.addClass( 'act' );
								$thumbElement
									.find(
										'.item[data-position="' +
											$position +
											'"]'
									)
									.removeClass( 'active' );
								$thumbElement
									.find(
										'.item[data-position="' + npu + '"]'
									)
									.addClass( 'active' );
							}
						}
						if ( typeof jQuery.fn.slick !== 'undefined' ) {
							jQuery( $thumbElement ).slick(
								'slickGoTo',
								$position
							);
						}
					} );
				}
			);
			jQuery( $thumbElement )
				.find( '.item' )
				.on( 'click', function () {
					const $position = jQuery( this ).data( 'position' );
					jQuery( $thumbElement )
						.find( '.item' )
						.removeClass( 'active' );
					jQuery( this ).addClass( 'active' );
					$galleryElement.find( '.item' ).removeClass( 'act' );
					$galleryElement
						.find( '.item[data-position="' + $position + '"]' )
						.addClass( 'act' );
					const ost = $galleryElement
						.find( '.item.act' )
						.offset().top;
					jQuery( 'body,html' ).animate(
						{ scrollTop: ost - 60 },
						'normal'
					);
				} );
		}
		RbbThemeSwatches.integrateSwatchesToScrollImage(
			$galleryElement,
			$thumbElement
		);
	}
	onWindowResize() {
		this.widthProduct(
			'.rbb_woo_products .swiper-right',
			'.rbb_woo_products .swiper-right .swiper-container'
		);
		this.widthProduct(
			'.rbb_woo_products .swiper-left',
			'.rbb_woo_products .swiper-left .swiper-container'
		);
		this.widthProduct(
			'.rbb_woo_products .content_right',
			'.rbb_woo_products .content_right'
		);
	}
	widthProduct( margin, padding ) {
		if ( jQuery( window ).width() > 768 ) {
			const $container = document.querySelector( '.container' );
			if ( $container ) {
				const $contentWrapper = jQuery( window ).width();
				const $widthContainer = $container.offsetWidth;
				const $product = ( $contentWrapper - $widthContainer + 30 ) / 2;
				if ( jQuery( 'body' ).hasClass( 'rtl' ) ) {
					if ( jQuery( margin ).hasClass( 'product-right' ) ) {
						jQuery( margin ).css( { 'margin-right': -$product } );
						jQuery( padding ).css( { 'padding-right': $product } );
					} else {
						jQuery( margin ).css( { 'margin-left': -$product } );
						jQuery( padding ).css( { 'padding-left': $product } );
					}
				} else if ( jQuery( margin ).hasClass( 'product-left' ) ) {
					jQuery( margin ).css( { 'margin-left': -$product } );
					jQuery( padding ).css( { 'padding-left': $product } );
				} else {
					jQuery( margin ).css( { 'margin-right': -$product } );
					jQuery( padding ).css( { 'padding-right': $product } );
				}
			}
		}
	}
	tabProduct() {
		jQuery( '.tabs-product .tab-a:first-child' ).addClass( 'active' );
		jQuery( '.tabs-product .tab-content:first-child' ).addClass(
			'tab-active'
		);
		jQuery( document ).on( 'click', '.tab-a', function () {
			jQuery( '.tab-content' ).removeClass( 'tab-active' );
			jQuery(
				".tab-content[data-id='" +
					jQuery( this ).attr( 'data-id' ) +
					"']"
			).addClass( 'tab-active' );
			jQuery( '.tab-a' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
		} );
	}
	updateTotalWishlist() {
		jQuery( document ).on( 'woosw_change_count', function ( event, count ) {
			const counter = jQuery( '.wishlist-count' );
			counter.html( count );
		} );
	}
	categoryTopBar() {
		jQuery( document ).on( 'click', '.grid-list span', function ( e ) {
			e.preventDefault();
			const view = jQuery( this ).data( 'type' );
			if ( ! jQuery( this ).hasClass( 'active' ) ) {
				const $productCat = jQuery( '.products-category' );
				$productCat.removeClass( 'grid_4 grid_3 grid_2 grid_1' );
				$productCat.addClass( view );
				jQuery( '.grid-list span' ).removeClass( 'active' );
				jQuery( this ).addClass( 'active' );
			}
		} );
	}
	filterSortBy() {
		const val = jQuery( '[data-sortby-item].active' ).attr( 'data-value' );
		jQuery( '[data-sortby-filter] .sort-by__label' ).text( val );
		jQuery( document ).on( 'click', '[data-sortby-item]', function () {
			const $valueSort = jQuery( this ).attr( 'data-value' );
			const $newText = jQuery( this ).text();
			jQuery( '[data-sortby-item]' ).removeClass( 'act' );
			jQuery( this ).addClass( 'act' );
			jQuery( '[data-sortby-filter] .sort-by__label' ).text( $newText );
			jQuery( '[name="sort_by"]' ).val( $valueSort );
		} );
	}
	filterSidebar() {
		jQuery(
			'.rbb-sidebar-shop-filter:not(.rbb-sidebar-shop-filter-top) .sidebar-shop-filter-title'
		).addClass( 'act' );
		if ( jQuery( window ).width() < 1024 ) {
			jQuery(
				'.rbb-sidebar-shop-filter.rbb-sidebar-shop-filter-top .sidebar-shop-filter-title'
			).addClass( 'act' );
		}
		jQuery( document ).on(
			'click',
			'.sidebar-shop-filter-title',
			function ( event ) {
				const currentTarget = jQuery( event.currentTarget );
				const content = currentTarget
					.parent()
					.find( '.widget-content' );
				if ( currentTarget.hasClass( 'act' ) ) {
					currentTarget.removeClass( 'act' );
					content.slideUp();
				} else {
					currentTarget.addClass( 'act' );
					content.slideDown();
				}
			}
		);
		jQuery( document ).on(
			'click',
			'.remove-filter-all, li .filter-link',
			function () {
				jQuery( '.level-0' )
					.find( 'li.active-item' )
					.parents( 'li' )
					.addClass( 'active' )
					.find( '.opener' )
					.addClass( 'active' );
			}
		);
		jQuery( '.level-0' )
			.find( 'li.active-item' )
			.find( '.opener' )
			.addClass( 'active' );
		jQuery( '.level-0' )
			.find( 'li.active-item' )
			.parents( 'li' )
			.addClass( 'active' )
			.find( '.opener' )
			.addClass( 'active' );
		jQuery( document ).on(
			'click',
			'.rbb-sidebar-shop-filter .opener',
			function () {
				if ( jQuery( this ).hasClass( 'active' ) ) {
					jQuery( this ).removeClass( 'active' );
					jQuery( this ).parent().find( 'ul' ).slideUp( 300 );
				} else {
					jQuery( this ).addClass( 'active' );
					jQuery( this ).parent().find( 'ul' ).slideDown( 300 );
				}
			}
		);
	}
	canvasFilter() {
		this.clickCanvasFilter(
			'.sidebar-filter',
			'.rbb-sidebar-shop-filter',
			'.filter-overlay'
		);
		this.clickCanvasFilter(
			'.filter-overlay',
			'.rbb-sidebar-shop-filter',
			'.sidebar-filter'
		);
	}
	clickCanvasFilter( selector, content, overlaySelector ) {
		jQuery( document ).on( 'click', selector, function () {
			if ( jQuery( selector ).hasClass( 'active' ) ) {
				jQuery( selector ).removeClass( 'active' );
				jQuery( content ).removeClass( 'active' );
				jQuery( overlaySelector ).removeClass( 'active' );
			} else {
				jQuery( selector ).addClass( 'active' );
				jQuery( content ).addClass( 'active' );
				jQuery( overlaySelector ).addClass( 'active' );
			}
		} );
	}
	filterClickLoading() {
		jQuery( document ).on(
			'click',
			'.rbb-sidebar-shop-filter',
			function () {
				jQuery( 'body' ).toggleClass( 'filter-product' );
			}
		);
		setInterval( function () {
			if ( ! jQuery( 'body' ).hasClass( 'pace-running' ) ) {
				jQuery( 'body' ).removeClass( 'filter-product' );
			}
		}, 100 );
	}
	rbbAccordion() {
		jQuery( document ).on( 'click', '.rbb-accordion-title', function () {
			if ( jQuery( this ).hasClass( 'act' ) ) {
				jQuery( this ).removeClass( 'act' );
				jQuery( this )
					.parent()
					.find( '.rbb-accordion-content' )
					.slideUp();
				jQuery( '.rbb-accordion' ).removeClass( 'active' );
			} else {
				jQuery( this )
					.parents( '.rbb-accordion' )
					.find( '.rbb-accordion-title' )
					.removeClass( 'act' );
				jQuery( this )
					.parents( '.rbb-accordion' )
					.find( '.rbb-accordion-content' )
					.slideUp();
				jQuery( this ).addClass( 'act' );
				jQuery( '.rbb-accordion' ).addClass( 'active' );
				jQuery( this )
					.parent()
					.find( '.rbb-accordion-content' )
					.slideDown();
			}
		} );
	}
	/**
	 * Init Image Zoom.
	 */
	imageZoom() {
		if ( jQuery( window ).width() > 768 ) {
			jQuery( '.rbb-slick-product-gallery__image a' ).zoom();
			jQuery( '.rbb-product-gallery__image a' ).zoom();
		}
	}
	/**
	 * Re-Init Slick slider after quick-view loaded.
	 */
	reInitSlickSliderForQuickView() {
		jQuery( document.body ).on( 'rbb-quick-view-loaded', function () {
			jQuery( '.rbb-slick-product_quick-view' ).each( function () {
				const bodyRtl = jQuery( 'body' ).hasClass( 'rtl' );
				const el = jQuery( this );
				if ( ! el.hasClass( 'slick-initialized' ) ) {
					if ( typeof jQuery.fn.slick !== 'undefined' ) {
						setTimeout( function () {
							el.slick( {
								arrows: true,
								infinite: true,
								slidesToShow: 1,
								slidesToScroll: 1,
								rtl: bodyRtl,
							} );
						}, 200 );
					}
				}
			} );
		} );
	}
	reInitAddToCartFormVariation() {
		jQuery( document.body ).on(
			'rbb-quick-view-loaded',
			function ( event, $button, $modalId ) {
				const $modal = jQuery( '#' + $modalId );
				const $variationsForm = $modal.find( '.cart.variations_form' );
				if (
					$variationsForm.length > 0 &&
					// eslint-disable-next-line camelcase
					typeof wc_add_to_cart_variation_params !== 'undefined'
				) {
					$variationsForm.wc_variation_form();
				}
			}
		);
	}
	ajaxAddToCartRedirect() {
		jQuery( document.body ).on(
			'added_to_cart',
			function ( event, $fragments, $cartHash, $button ) {
				const $redirectUrl = jQuery( $button ).data( 'redirect' );
				const $miniCartCanvas = jQuery( '.rbb-mini-cart-canvas' );
				if (
					$miniCartCanvas.length &&
					( $redirectUrl === undefined || $redirectUrl === '' )
				) {
					jQuery( $button )
						.closest( '.rbb-modal' )
						.removeClass( 'show' );
					RisingBambooModal.modal( '.rbb-mini-cart-canvas' );
				}
				if ( $redirectUrl !== undefined ) {
					window.location.href = $redirectUrl;
				}
			}
		);
	}
	selectAjaxBeforeSend() {
		jQuery( document.body ).on(
			'rbb-select-ajax-before-send',
			function ( event, element ) {
				jQuery( element ).addClass( 'loading' );
			}
		);
	}
	selectAjaxComplete() {
		jQuery( document.body ).on(
			'rbb-select-ajax-complete',
			function ( event, element ) {
				jQuery( element ).removeClass( 'loading' );
				// eslint-disable-next-line no-undef
				RbbCountdown.render(
					jQuery( element ).find( '[data-countdown-date]' )
				);
			}
		);
	}
	rbbAjaxProductLoaded() {
		jQuery( document.body ).on(
			'rbb_product_products_loaded',
			function ( event, element ) {
				// eslint-disable-next-line no-undef
				RbbCountdown.render(
					jQuery( element ).find( '[data-countdown-date]' )
				);
			}
		);
	}
}

jQuery( function () {
	window.RisingBambooWoocommerce = new RisingBambooWoocommerce();
} );
