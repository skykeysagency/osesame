/**
 * Main JavaScript file.
 *
 * @version 1.0.0
 */

'use strict';

import RbbThemeNavigation from './component/navigation';
import RbbThemeSkipLinkFocus from './component/skip-link-focus-fix';
import './component/sticky-header';
import RbbThemeSearch from './component/search';
import RbbThemeOverlayScrollBar from './component/overlay-scroll-bar';
import RbbThemeSlickJs from './component/slick';
import RbbThemePromotionPopup from './component/promotion-popup';
import RbbThemeLightBox from './component/lightbox';
import Wow from 'wow.js';
import RbbMobileNavigation from './component/mobile-navigation';

document.addEventListener( 'DOMContentLoaded', () => {
	const navigation = new RbbThemeNavigation();
	RbbThemeSkipLinkFocus();
	navigation.setupNavigation();
	navigation.enableTouchFocus();
} );
const RisingBamboo = {
	config: {},

	adminBarHeight: 0,

	scrollPosition: 0,

	init() {
		const $this = this;
		$this.setConfig();
		$this.setScrollPosition();
		$this.setAdminBarHeight();
		$this.initStickyElements();
		$this.initScrollToTop();
		$this.searchActive();
		$this.footerMobile();
		$this.menuNavigation();
		$this.menuCanvas();
		$this.copy();
		$this.deliveryBanner();
		$this.tabBanner();
		$this.speedSlider();
		$this.headerMobile();
		$this.gallery();
		$this.menuMobileBottom();
		$this.runParallax(
			'.parallax_bg_banner .elementor-background-overlay'
		);
		$this.runParallax( '.banner-delivery-img' );
		$this.parallax();
		$this.parallaxBannerImg();
		$this.closeSearch();
		$this.topCategory();
		$this.toggleElement( '.toggle-login', '.toggle-login ' );
		$this.toggleElement(
			'.click-search-mobile',
			'.rbb-product-search-content2 '
		);
		if ( jQuery( window ).width() < 768 ) {
			$this.toggleStyles( '_desktop_', '_mobile_' );
		}
		if ( jQuery( window ).width() < 1024 ) {
			$this.toggleStyles( 'desktop_', 'mobile_' );
		}
		RbbThemeSearch.ajaxSearch();
		let currentWidth = jQuery( window ).width();
		const minWidth = 768;
		const minWidth2 = 1024;
		jQuery( window ).on( 'resize', function () {
			const _cw = currentWidth;
			const _mw = minWidth;
			const _w = jQuery( window ).width();
			const _toggle =
				( _cw >= _mw && _w < _mw ) || ( _cw < _mw && _w >= _mw );
			currentWidth = _w;
			if ( _toggle ) {
				$this.toggleStyles( '_desktop_', '_mobile_' );
			}
			const _mw2 = minWidth2;
			const _toggle2 =
				( _cw >= _mw2 && _w < _mw2 ) || ( _cw < _mw2 && _w >= _mw2 );
			currentWidth = _w;
			if ( _toggle2 ) {
				$this.toggleStyles( 'desktop_', 'mobile_' );
			}
			$this.onWindowResize();
		} );
		new RbbThemeSlickJs( '.rbb-slick-el' );
		new RbbThemeOverlayScrollBar();
		new RbbThemePromotionPopup();
		new RbbThemeLightBox( '#rbb-gallery-lightbox' );
		jQuery( window ).on( 'scroll', function () {
			$this.onWindowScroll();
		} );
		window.addEventListener( 'click', () => {
			$this.loginSwitch();
		} );
		new Wow().init();
	},
	setConfig() {
		if ( typeof window.rbb_config === 'object' ) {
			this.config = window.rbb_config;
		}
	},

	setScrollPosition() {
		this.scrollPosition = jQuery( 'window' ).scrollTop();
	},

	setAdminBarHeight() {
		const adminBar = document.querySelector( '#wpadminbar' );

		if ( adminBar && window.outerWidth > 600 ) {
			this.adminBarHeight = adminBar.offsetHeight;
		}
	},

	initStickyElements() {
		jQuery( '.rbb-header-sticky' ).RbbStickyHeader(
			this.config.header_sticky
		);
		new RbbMobileNavigation(
			'#rbb-mobile-navigation',
			this.config.mobile_navigation
		);
	},

	initScrollToTop() {
		const $button = jQuery( '.scroll-to-top' );

		jQuery( window ).scroll( function () {
			const offset = jQuery( 'body' ).innerHeight() * 0.2;

			if ( jQuery( this ).scrollTop() > offset ) {
				$button.removeClass( 'opacity-0 pointer-events-none' );
			} else {
				$button.addClass( 'opacity-0 pointer-events-none' );
			}
		} );

		$button.on( 'click', function ( e ) {
			jQuery( 'body, html' ).animate(
				{
					scrollTop: 0,
				},
				400
			);

			e.preventDefault();
		} );
	},

	onWindowResize() {
		this.setAdminBarHeight();
	},

	onWindowScroll() {
		const isScrollUp = jQuery( window ).scrollTop() < this.scrollPosition;

		if ( isScrollUp ) {
			jQuery( window ).triggerHandler( 'scroll:up' );
		} else {
			jQuery( window ).triggerHandler( 'scroll:down' );
		}

		this.scrollPosition = jQuery( window ).scrollTop();
	},

	toggleElement( $clickAble, content ) {
		jQuery( $clickAble ).on( 'click', function () {
			if ( jQuery( content ).hasClass( 'active' ) ) {
				jQuery( content ).removeClass( 'active' );
			} else {
				jQuery( content ).addClass( 'active' );
			}
		} );
		jQuery( content )
			.find( '.close' )
			.on( 'click', function () {
				if ( jQuery( content ).hasClass( 'active' ) ) {
					jQuery( content ).removeClass( 'active' );
				}
			} );
		jQuery( document ).mouseup( function ( e ) {
			if ( jQuery( e.target ).closest( content ).length === 0 ) {
				jQuery( content ).removeClass( 'active' );
			}
		} );
	},
	menuMobile( menuLevel ) {
		const $document = jQuery( document );
		$document.on( 'click', menuLevel, function () {
			const $this = jQuery( this );
			const $parent = $this.parent();
			const $closestUl = $this.closest( 'ul' );
			if ( $parent.hasClass( 'menu-active' ) ) {
				$parent
					.removeClass( 'menu-active' )
					.css( { 'z-index': '1', position: 'relative' } );
				$closestUl
					.removeClass( 'active' )
					.css( { top: '50px', transform: 'translateX(0%)' } );
			} else {
				$parent
					.addClass( 'menu-active' )
					.css( { 'z-index': '9', position: 'static' } );
				const translateValue = jQuery( 'body' ).hasClass( 'rtl' )
					? '100%'
					: '-100%';
				$closestUl.addClass( 'active' ).css( {
					top: '0px',
					transform: `translateX(${ translateValue })`,
				} );
			}
			jQuery( '.mega-menu .rbb-slick-carousel' ).slick( 'refresh' );
		} );
	},
	menuNavigation() {
		const desktopSocial = document.getElementById( 'desktop-social' );
		const desktopSearch = document.getElementById( '_desktop_search' );
		const mobileTop = document.querySelector( '.search_desktop' );
		const mobileBottom = document.querySelector(
			'.mobile_bottom .social_content'
		);
		if ( desktopSocial && mobileBottom ) {
			mobileBottom.innerHTML = desktopSocial.innerHTML;
		}
		if ( desktopSearch && mobileTop ) {
			mobileTop.innerHTML = desktopSearch.innerHTML;
		}
		const checkAndAddBlocks = () => {
			if ( jQuery( '.rbb-main-navigation' ).hasClass( 'screen' ) ) {
				if ( jQuery( window ).width() < 1023 ) {
					this.menuMobile( '#mobile_menu .opener' );
					this.menuMobile( '#mobile_menu .opener2' );
					jQuery( document ).on(
						'click',
						'.toggle-megamenu, .canvas-overlay',
						function () {
							if (
								jQuery( '.search_desktop' ).hasClass(
									'fadeInLeft'
								)
							) {
								jQuery( '.search_desktop' ).removeClass(
									'fadeInLeft'
								);
								jQuery( '.mobile_bottom > div' ).removeClass(
									'fadeInLeft'
								);
								jQuery(
									'#mobile_menu .menu-container > li'
								).removeClass( 'fadeInLeft' );
							} else {
								jQuery( '.search_desktop' ).addClass(
									'fadeInLeft'
								);
								jQuery( '.mobile_bottom > div' ).addClass(
									'fadeInLeft'
								);
								jQuery(
									'#mobile_menu .menu-container > li'
								).addClass( 'fadeInLeft' );
							}
						}
					);
					const ul = document.getElementById( 'menu-main' );
					const liElements = ul.querySelectorAll(
						'.menu-container > li'
					);
					let $y = 800;
					for ( let i = 0; i < liElements.length; i++ ) {
						const li = liElements[ i ];
						jQuery( li ).css( 'animation-duration', $y + 'ms' );
						$y = $y + 150;
					}
				}
			}
		};
		checkAndAddBlocks();
		window.addEventListener( 'resize', checkAndAddBlocks );
	},
	menuCanvas() {
		this.menuMobile( '.rbb-menu-canvas .opener' );
		this.menuMobile( '.rbb-menu-canvas .opener2' );
		jQuery( document ).on( 'click', '.menu_close', function () {
			const $menuCanvas = jQuery( '.rbb-menu-canvas' );
			const $menuClose = jQuery( '.menu_close' );
			const $body = jQuery( 'body' );
			if ( $menuCanvas.hasClass( 'show' ) ) {
				$menuCanvas.removeClass( 'show' );
				$menuClose.removeClass( 'active' );
				$body.removeClass( 'active' );
				if ( jQuery( window ).width() < 767 ) {
					$body.css( 'overflow', 'initial' );
				}
			} else {
				RisingBambooModal.modal( '.rbb-menu-canvas' );
				$menuCanvas.addClass( 'show' );
				$menuClose.addClass( 'active' );
				$body.addClass( 'active' );
				if ( jQuery( window ).width() < 767 ) {
					$body.css( 'overflow', 'hidden' );
				}
			}
		} );
		jQuery( document ).on( 'click', '.rbb-modal-backdrop', function () {
			const $body = jQuery( 'body' );
			jQuery( '.menu_close' ).removeClass( 'active' );
			if ( jQuery( window ).width() < 767 ) {
				$body.css( 'overflow', 'initial' );
			}
		} );
		if ( jQuery( '.rbb-menu-canvas' ).hasClass( 'rbb-modal' ) ) {
			const ul = document.getElementById( 'menu-main' );
			const liElements = ul.querySelectorAll( '.menu-container > li' );
			let $y = 800;
			for ( let i = 0; i < liElements.length; i++ ) {
				const li = liElements[ i ];
				jQuery( li )
					.addClass( 'fadeInLeft' )
					.css( 'animation-duration', $y + 'ms' );
				$y = $y + 150;
			}
		}
	},
	clickCanvasMenu( selector, content, overlaySelector, ...closeElements ) {
		jQuery( document ).on( 'click', selector, function () {
			const isActive = jQuery( this )
				.toggleClass( 'active' )
				.hasClass( 'active' );
			jQuery( 'body' ).css( 'overflow', isActive ? 'hidden' : 'auto' );
			jQuery( [ overlaySelector, content ].join( ', ' ) ).toggleClass(
				'active',
				isActive
			);
			jQuery( closeElements.join( ', ' ) ).removeClass( 'active' );
		} );
	},
	handleOverlayClick( overlaySelector, content, closeElements ) {
		jQuery( document ).on( 'click', overlaySelector, function () {
			if ( jQuery( this ).hasClass( 'active' ) ) {
				jQuery( this ).removeClass( 'active' );
				jQuery( 'body' ).css( 'overflow', 'auto' );
				jQuery(
					[ overlaySelector, content, closeElements ].join( ', ' )
				).removeClass( 'active' );
			} else {
				jQuery( this ).addClass( 'active' );
				jQuery( 'body' ).css( 'overflow', 'hidden' );
				jQuery(
					[ overlaySelector, content, closeElements ].join( ', ' )
				).addClass( 'active' );
			}
		} );
	},
	headerMobile() {
		this.clickCanvasMenu(
			'.toggle-megamenu',
			'#mobile_menu',
			'.canvas-overlay',
			'.search-mobile',
			'#_mobile_search',
			'.rbb_results'
		);
		this.clickCanvasMenu(
			'.category-mobile',
			'.top-category',
			'.canvas-overlay2',
			'.search-mobile',
			'#_mobile_search',
			'.rbb_results',
			'.toggle-megamenu',
			'#mobile_menu',
			'.canvas-overlay'
		);
		this.handleOverlayClick(
			'.canvas-overlay',
			'#mobile_menu',
			'.toggle-megamenu'
		);
		this.handleOverlayClick(
			'.canvas-overlay2',
			'.top-category',
			'.category-mobile'
		);
		jQuery( document ).on( 'click', '.search-mobile', function () {
			if ( jQuery( '.search-mobile' ).hasClass( 'active' ) ) {
				jQuery(
					'.search-mobile, .product-search-mobile, .rbb_results, .canvas-overlay, #mobile_menu'
				).removeClass( 'active' );
				jQuery( 'body' ).css( 'overflow', 'auto' );
			} else {
				jQuery(
					'.search-mobile, .product-search-mobile, .rbb_results'
				).addClass( 'active' );
				jQuery( 'body' ).css( 'overflow', 'hidden' );
			}
			jQuery(
				'.canvas-overlay, .toggle-megamenu, #mobile_menu'
			).removeClass( 'active' );
		} );
	},
	searchActive() {
		const resultsTop = jQuery( '.rbb-header-sticky' ).height();
		jQuery( '.rbb_results' ).css( 'top', resultsTop + 'px' );
		if ( jQuery( window ).width() < 768 ) {
			jQuery( document ).on( 'keyup', '.input-search', function () {
				const term = jQuery( this ).val();
				if ( term.length > 0 ) {
					jQuery( '.btn-search_clear-text' ).show();
				} else {
					jQuery( '.btn-search_clear-text' ).hide();
				}
				jQuery( document ).on(
					'click',
					'.btn-search_clear-text',
					function () {
						jQuery( '.input-search' ).val( '' );
						jQuery( '.btn-search_clear-text' ).hide();
						jQuery( '.rbb_results' ).removeClass( 'active' );
						jQuery( 'body' ).removeClass( 'active' );
					}
				);
			} );
		}
	},

	footerElement( $footerTitle, $footerContent ) {
		if ( jQuery( window ).width() < 768 ) {
			jQuery( document ).on( 'click', $footerTitle, function () {
				jQuery( $footerContent ).slideToggle( 'slow', function () {} );
				const $hasActive = jQuery( $footerTitle ).hasClass( 'active' );
				if ( $hasActive ) {
					jQuery( $footerTitle ).removeClass( 'active' );
				} else {
					jQuery( $footerTitle ).addClass( 'active' );
				}
			} );
		}
	},
	footerMobile() {
		this.footerElement(
			'.footer-title1 .elementor-heading-title',
			'.footer-title1 .elementor-icon-list-items'
		);
		this.footerElement(
			'.footer-title2 .elementor-heading-title',
			'.footer-title2 .elementor-icon-list-items'
		);
		this.footerElement(
			'.footer-title3 .elementor-heading-title',
			'.footer-title3 .elementor-icon-list-items'
		);
		this.footerElement(
			'.footer-title4 .elementor-heading-title',
			'.footer-title4 .footer-content'
		);
	},
	loginSwitch() {
		jQuery( document ).on( 'click', '.login_switch', function () {
			if ( jQuery( 'body' ).hasClass( 'rtl' ) ) {
				if ( jQuery( this ).hasClass( 'login-btn' ) ) {
					jQuery( '.login_switch_title' ).css(
						'transform',
						'translate(0)'
					);
					jQuery( '#rbb_login' ).slideDown();
					jQuery( '#rbb_register' ).slideUp();
				} else {
					jQuery( '.login_switch_title' ).css(
						'transform',
						'translate(-100%)'
					);
					jQuery( '#rbb_login' ).slideUp();
					jQuery( '#rbb_register' ).slideDown();
				}
			} else if ( jQuery( this ).hasClass( 'login-btn' ) ) {
				jQuery( '.login_switch_title' ).css(
					'transform',
					'translate(0)'
				);
				jQuery( '#rbb_login' ).slideDown();
				jQuery( '#rbb_register' ).slideUp();
			} else {
				jQuery( '.login_switch_title' ).css(
					'transform',
					'translate(100%)'
				);
				jQuery( '#rbb_login' ).slideUp();
				jQuery( '#rbb_register' ).slideDown();
			}
		} );
	},
	copy() {
		jQuery( document ).on( 'click', '.copy-btn', function () {
			const el = jQuery( this );
			const copyText = el.siblings( 'input' )[ 0 ];
			copyText.select();
			const selection = copyText.ownerDocument.defaultView.getSelection();
			document.execCommand( 'copy' );
			selection.removeAllRanges();
			const copy = el.data( 'copy' );
			const copied = el.data( 'copied' );
			el.text( copied );
			setTimeout( () => el.text( copy ), 2500 );
		} );
	},
	deliveryBanner() {
		const $content1 = '.banner-content .hover_content1';
		const $content2 = '.banner-content .hover_content2';
		const $content3 = '.banner-content .hover_content3';
		const $content4 = '.banner-content .hover_content4';
		const $hoverContents = [ $content1, $content2, $content3, $content4 ];
		$hoverContents.forEach( ( $content, index ) => {
			jQuery( document ).on( 'mouseenter', $content, function () {
				for ( let i = 0; i <= index; i++ ) {
					jQuery( $hoverContents[ i ] ).addClass( 'active' );
				}
			} );

			jQuery( document ).on( 'mouseleave', $content, function () {
				for ( let i = 0; i <= index; i++ ) {
					jQuery( $hoverContents[ i ] ).removeClass( 'active' );
				}
			} );
		} );
	},
	tabBanner() {
		jQuery( '#tabs li:nth-child(2) a' ).addClass( 'active' );
		jQuery( '.tabcontent' ).hide();
		jQuery( '.tabcontent:nth-child(2)' ).show();
		jQuery( document ).on( 'click', '#tabs li a', function () {
			const t = jQuery( this ).attr( 'href' );
			jQuery( '#tabs li a' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );
			jQuery( '.tabcontent' ).hide();
			jQuery( t ).fadeIn( 'slow' );
			return false;
		} );

		const t = jQuery( '#tabs li:nth-child(2) a' ).attr( 'href' );
		if ( jQuery( '#tabs li:nth-child(2) a' ).hasClass( 'active' ) ) {
			jQuery( '#tabs li a' ).removeClass( 'active' );
			jQuery( '#tabs li:nth-child(2) a' ).addClass( 'active' );
			jQuery( '.tabcontent' ).hide();
			jQuery( t ).fadeIn( 'slow' );
		}
	},
	toggleStyles( prefix1, prefix2 ) {
		function swapChildren( obj1, obj2 ) {
			const temp = obj2.children().detach();
			obj2.empty().append( obj1.children().detach() );
			obj1.append( temp );
		}
		jQuery( `*[id^='${ prefix1 }']` ).each( function ( idx, el ) {
			const targetId = el.id.replace( prefix1, prefix2 );
			const target = jQuery( `#${ targetId }` );
			if ( target.length ) {
				swapChildren( jQuery( el ), target );
			}
		} );
	},
	gallery() {
		jQuery( window ).scroll( function () {
			jQuery( '.gallery' ).each( function () {
				const currentPosition = jQuery( window ).scrollTop(),
					offsetTop = jQuery( this ).offset().top;
				if ( currentPosition - offsetTop < 0 ) {
					const scrolled = ( offsetTop - currentPosition ) * 0.1;
					jQuery( '.itemy_parallax .gallery' ).css(
						'transform',
						'translateX(-' + scrolled + 'px)'
					);
					jQuery( '.reversex_parallax .gallery' ).css(
						'transform',
						'translateX(' + scrolled + 'px)'
					);
				} else {
					const scrolled = ( currentPosition - offsetTop ) * 0.1;
					jQuery( '.itemy_parallax .gallery' ).css(
						'transform',
						'translateX(' + scrolled + 'px)'
					);
					jQuery( '.reversex_parallax .gallery' ).css(
						'transform',
						'translateX(-' + scrolled + 'px)'
					);
				}
			} );
		} );
	},
	runParallax( selector ) {
		const el = jQuery( selector );
		el.each( function ( index, element ) {
			const intersectionObserver = new IntersectionObserver(
				( entries ) => {
					entries.forEach( ( entry ) => {
						if ( entry.isIntersecting ) {
							jQuery( element ).addClass( 'act' );
						} else {
							jQuery( element ).removeClass( 'act' );
						}
					} );
				}
			);
			const elementDOM = jQuery( element ).get( 0 );
			intersectionObserver.observe( elementDOM );
		} );
	},
	parallax() {
		jQuery( window ).scroll( function () {
			jQuery(
				'.parallax_bg_banner .elementor-background-overlay.act'
			).each( function () {
				const currentPosition = jQuery( window ).scrollTop(),
					offsetTop = jQuery( this ).offset().top;
				let scrolled = ( currentPosition - offsetTop ) * 0.1;
				scrolled = Math.min( 150, Math.abs( scrolled ) );

				if ( currentPosition - offsetTop < 0 ) {
					jQuery( this ).css(
						'transform',
						'translateY(' + scrolled + 'px)'
					);
				} else {
					jQuery( this ).css(
						'transform',
						'translateY(-' + scrolled + 'px)'
					);
				}
			} );
		} );
	},
	parallaxBannerImg() {
		jQuery( window ).scroll( function () {
			jQuery( '.banner-delivery-img.act' ).each( function () {
				const currentPosition =
					jQuery( window ).scrollTop() + jQuery( window ).height();
				const offsetTop = jQuery( this ).offset().top;
				const elHeight = jQuery( this ).height();
				const scrolledX =
					( currentPosition - offsetTop + elHeight ) / 2;
				jQuery( this )
					.find( 'img' )
					.css( {
						transform: 'translateX(' + scrolledX + 'px)',
						'transition-duration': '0.2s',
					} );
			} );
		} );
	},
	menuMobileBottom() {
		let thisurl = window.location;
		let urlmenu = '';
		thisurl = String( thisurl );
		thisurl = thisurl
			.replace( 'https://', '' )
			.replace( 'http://', '' )
			.replace( 'www.', '' )
			.replace( /#\w*/, '' );
		let thislink = '{/literal}{$current_link}{literal}';
		thislink = thislink
			.replace( 'https://', '' )
			.replace( 'http://', '' )
			.replace( 'www.', '' )
			.replace( /#\w*/, '' );
		jQuery( '#rbb-mobile-navigation a' ).each( function () {
			urlmenu = jQuery( this )
				.attr( 'href' )
				.replace( 'https://', '' )
				.replace( 'http://', '' )
				.replace( 'www.', '' )
				.replace( /#\w*/, '' );
			if (
				thisurl === urlmenu ||
				thisurl.replace( thislink, '' ) === urlmenu
			) {
				jQuery( this )
					.find( 'i' )
					.addClass( 'text-[var(--rbb-menu-link-hover-color)]' );
				return false;
			}
		} );
	},
	speedSlider() {
		const dotsElement = jQuery( '#slideshow-dot div' );
		const speed = jQuery( '.rbb-elementor-slider.single' ).data( 'speed' );
		const speedslick = speed / 1000;
		dotsElement.on( 'click', function () {
			const index = jQuery( this ).data( 'index' );
			jQuery( '.rbb-slick-slider' ).slick( 'slickGoTo', index );
		} );
		jQuery( '.rbb-slick-slider' ).on(
			'beforeChange',
			function ( event, slick, currentSlide, nextSlide ) {
				dotsElement.removeClass( 'active' );
				dotsElement.eq( nextSlide ).addClass( 'active' );
			}
		);
		jQuery( '#slideshow-dot svg circle' ).css(
			'transition',
			'all ' + speedslick + 's cubic-bezier(0.39, 0.58, 0.57, 1)'
		);
	},
	closeSearch() {
		jQuery( document ).on( 'click', '.close-search', function () {
			jQuery( 'body' ).css( 'overflow', 'auto' ).removeClass( 'active' );
			if ( jQuery( '.rbb_results' ).hasClass( 'active' ) ) {
				jQuery( '.rbb_results' ).removeClass( 'active' );
			} else {
				jQuery( '.rbb_results' ).addClass( 'active' );
			}
		} );
	},
	topCategory() {
		if ( jQuery( 'div' ).hasClass( 'top-category' ) ) {
			jQuery( '.grocery-01 .all-categories' ).css( 'display', 'flex' );
			jQuery(
				'<div class="category-mobile text-black text-lg ml-5 rbb-icon-view-grid-1"></div>'
			).appendTo( '.header-mobile-right' );
		}
	},
};
window.RisingBamboo = RisingBamboo;
window.RbbThemeSearch = RbbThemeSearch;
jQuery( function () {
	RisingBamboo.init();
} );
