/* eslint-disable camelcase */
import axios from 'axios';
import Swal from 'sweetalert2';
import DOMPurify from 'dompurify';

const Merlin = ( function ( $ ) {
	'use strict';
	// callbacks from form button clicks.
	const callbacks = {
		install_child( btn ) {
			const installer = new ChildTheme();
			installer.init( btn );
		},
		activate_license( btn ) {
			const license = new ActivateLicense();
			license.init( btn );
		},
		install_plugins( btn ) {
			const plugins = new PluginManager();
			plugins.init( btn );
		},
		install_content( btn ) {
			const content = new ContentManager();
			content.init( btn );
		},
	};

	function windowLoaded() {
		const body = $( '.merlin__body' ),
			drawerTrigger = $( '#merlin__drawer-trigger' ),
			drawerOpened = 'merlin__drawer--open';

		setTimeout( function () {
			body.addClass( 'loaded' );
		}, 100 );

		drawerTrigger.on( 'click', function () {
			body.toggleClass( drawerOpened );
		} );

		$( '.merlin__button--proceed:not(.merlin__button--closer)' ).on(
			'click',
			function ( e ) {
				e.preventDefault();
				const goTo = this.getAttribute( 'href' );

				body.addClass( 'exiting' );

				setTimeout( function () {
					window.location = goTo;
				}, 400 );
			}
		);

		$( '.merlin__button--closer' ).on( 'click', function ( e ) {
			body.removeClass( drawerOpened );

			e.preventDefault();
			const goTo = this.getAttribute( 'href' );

			setTimeout( function () {
				body.addClass( 'exiting' );
			}, 600 );

			setTimeout( function () {
				window.location = goTo;
			}, 1100 );
		} );

		$( '.button-next' ).on( 'click', function ( e ) {
			e.preventDefault();
			const loadingButton = merlinLoadingButton( this );
			if ( ! loadingButton ) {
				return false;
			}
			const dataCallback = $( this ).data( 'callback' );
			if (
				dataCallback &&
				typeof callbacks[ dataCallback ] !== 'undefined'
			) {
				// We have to process a callback before continue with form submission.
				callbacks[ dataCallback ]( this );
				return false;
			}
			return true;
		} );

		$( document ).on(
			'change',
			'.js-merlin-demo-import-select',
			function () {
				const selectedIndex = $( this ).val();
				$( '.button-next' ).attr( 'disabled', true );
				axios( {
					method: 'post',
					url: merlin_params.ajaxurl,
					data: convertObjToFormData( {
						action: 'merlin_update_selected_import_data_info',
						wpnonce: merlin_params.wpnonce,
						selected_index: selectedIndex,
					} ),
				} )
					.then( function ( { data } ) {
						if ( data.success ) {
							$( '.js-merlin-drawer-import-content' ).html(
								DOMPurify.sanitize( data.data )
							);
						}
					} )
					.catch( function () {
						sAlert(
							{
								text: 'An error occurred while selecting content to import. Please refresh the page and try again.',
							},
							null,
							{
								timer: 10000,
								timerProgressBar: true,
							}
						);
					} );
			}
		);
	}

	function ChildTheme() {
		const body = $( '.merlin__body' ),
			notice = $( '#child-theme-text' ),
			drawerOpened = 'merlin__drawer--open';
		let complete;

		function ajax_callback( r ) {
			if ( typeof r.done !== 'undefined' ) {
				setTimeout( function () {
					notice.addClass( 'lead' );
				}, 0 );
				setTimeout( function () {
					notice.addClass( 'success' );
					notice.html( DOMPurify.sanitize( r.message ) );
				}, 600 );

				complete();
			} else {
				notice.addClass( 'lead error' );
				notice.html( DOMPurify.sanitize( r.error ) );
			}
		}

		function do_ajax() {
			axios( {
				method: 'post',
				url: merlin_params.ajaxurl,
				data: convertObjToFormData( {
					action: 'merlin_child_theme',
					wpnonce: merlin_params.wpnonce,
				} ),
			} )
				.then( ajax_callback )
				.catch( ajax_callback );
		}

		return {
			init( btn ) {
				complete = function () {
					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'js--finished' );
					}, 1500 );

					body.removeClass( drawerOpened );

					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'exiting' );
					}, 3500 );

					setTimeout( function () {
						window.location.href = btn.href;
					}, 4000 );
				};
				do_ajax();
			},
		};
	}

	function ActivateLicense() {
		const body = $( '.merlin__body' );
		const wrapper = $( '.merlin__content--license-key' );
		const notice = $( '#license-text' );
		const drawerOpened = 'merlin__drawer--open';
		let complete;

		function ajax_callback( { data } ) {
			if ( typeof data.success !== 'undefined' && data.success ) {
				notice.siblings( '.error-message' ).remove();
				setTimeout( function () {
					notice.addClass( 'lead' );
				}, 0 );
				setTimeout( function () {
					notice.addClass( 'success' );
					notice.html( DOMPurify.sanitize( data.message ) );
				}, 600 );
				complete();
			} else {
				$( '.js-merlin-license-activate-button' )
					.removeClass( 'merlin__button--loading' )
					.data( 'done-loading', 'no' );
				notice.siblings( '.error-message' ).remove();
				wrapper.addClass( 'has-error' );
				notice.html( DOMPurify.sanitize( data.message ) );
				notice.siblings( '.error-message' ).addClass( 'lead error' );
			}
		}

		function do_ajax() {
			wrapper.removeClass( 'has-error' );

			axios( {
				method: 'post',
				url: merlin_params.ajaxurl,
				data: convertObjToFormData( {
					action: 'merlin_activate_license',
					wpnonce: merlin_params.wpnonce,
					license_key: $( '.js-license-key' ).val(),
				} ),
			} )
				.then( ajax_callback )
				.catch( ajax_callback );
		}

		return {
			init( btn ) {
				complete = function () {
					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'js--finished' );
					}, 1500 );

					body.removeClass( drawerOpened );

					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'exiting' );
					}, 3500 );

					setTimeout( function () {
						window.location.href = btn.href;
					}, 4000 );
				};
				do_ajax();
			},
		};
	}

	function PluginManager() {
		const body = $( '.merlin__body' );
		const drawerOpened = 'merlin__drawer--open';
		const $li = $( '.merlin__drawer--install-plugins li' );
		let complete; // eslint-disable-line
		let items_completed = items_completed_init();
		let current_item = '';
		let $current_node;
		let current_item_hash = '';
		let install_failed = 0;
		function items_completed_init() {
			let _items_completed = 0;
			$li.each( function () {
				if ( $( this ).data( 'done_item' ) ) {
					_items_completed++;
				}
			} );
			return _items_completed;
		}
		function ajax_callback( { data } ) {
			const currentSpan = $current_node.find( 'label' );
			if (
				typeof data === 'object' &&
				typeof data.message !== 'undefined'
			) {
				currentSpan
					.removeClass( 'installing success error' )
					.addClass( data.message.toLowerCase() );

				// The plugin is done (installed, updated and activated).
				if ( typeof data.done !== 'undefined' && data.done ) {
					find_next();
				} else if ( typeof data.url !== 'undefined' ) {
					// we have an ajax url action to perform.
					if ( data.hash === current_item_hash ) {
						currentSpan
							.removeClass( 'installing success' )
							.addClass( 'error' );
						install_failed++;
						$current_node.data( 'failure_item', 1 );
						find_next();
					} else {
						current_item_hash = data.hash;
						axios( {
							method: 'post',
							url: data.url,
							data: convertObjToFormData( data ),
						} )
							.then( ajax_callback )
							.catch( ajax_callback );
					}
				} else {
					// error processing this plugin
					find_next();
				}
			} else {
				// The TGMPA returns a whole page as response, so check, if this plugin is done.
				process_current();
			}
		}

		function process_current() {
			if ( current_item ) {
				const $check = $current_node.find( 'input:checkbox' );
				if ( $check.is( ':checked' ) ) {
					axios( {
						method: 'post',
						url: merlin_params.ajaxurl,
						data: convertObjToFormData( {
							action: 'merlin_plugins',
							wpnonce: merlin_params.wpnonce,
							slug: current_item,
						} ),
					} )
						.then( ajax_callback )
						.catch( ajax_callback );
				} else {
					$current_node.addClass( 'skipping' );
					setTimeout( find_next, 300 );
				}
			}
		}

		function find_next() {
			if ( $current_node ) {
				if ( ! $current_node.data( 'done_item' ) ) {
					items_completed++;
					$current_node.data( 'done_item', 1 );
				}
				$current_node.find( '.spinner' ).css( 'visibility', 'hidden' );
			}
			$li.each( function () {
				const $item = $( this );
				if ( $item.data( 'done_item' ) ) {
					return true;
				}

				current_item = $item.data( 'slug' );
				$current_node = $item;
				process_current();
				return false;
			} );
			if ( items_completed >= $li.length ) {
				// finished all plugins!
				if ( install_failed === 0 ) {
					complete();
				} else {
					const current_btn = $(
						'.merlin__button[data-callback="install_plugins"]'
					);
					current_btn
						.removeClass( 'merlin__button--loading' )
						.data( 'done-loading', 'no' );
					current_btn
						.find( '.merlin__button--loading__text' )
						.text( 'Reinstall' );
					$li.each( function () {
						const $item = $( this );
						if ( $item.data( 'failure_item' ) ) {
							$item.removeData( 'failure_item' );
							$item.removeData( 'done_item' );
						}
					} );
					sAlert(
						{
							text: 'Some plugins encountered errors during installation. Please reinstall now or skip this step and install them manually later.',
						},
						null,
						{
							timer: 10000,
							timerProgressBar: true,
						}
					);
				}
			}
		}

		return {
			init( btn ) {
				const drawerInstallPlg = $(
					'.merlin__drawer--install-plugins'
				);
				drawerInstallPlg.addClass( 'installing' );
				drawerInstallPlg.find( 'input' ).prop( 'disabled', true );
				complete = function () {
					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'js--finished' );
					}, 1000 );

					body.removeClass( drawerOpened );

					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'exiting' );
					}, 3000 );

					setTimeout( function () {
						window.location.href = btn.href;
					}, 3500 );
				};
				find_next();
			},
		};
	}

	function ContentManager() {
		const body = $( '.merlin__body' );
		const drawerOpened = 'merlin__drawer--open';
		let items_completed = 0;
		let current_item = '';
		let $current_node;
		let current_item_hash = '';
		let current_content_import_items = 1;
		let total_content_import_items = 0;
		let progress_bar_interval;
		let complete;
		let get_total_times = 0;

		function ajax_callback( { data } ) {
			const currentSpan = $current_node.find( 'label' );
			if (
				typeof data === 'object' &&
				typeof data.message !== 'undefined' &&
				data.name !== 'AxiosError'
			) {
				currentSpan.addClass( data.message.toLowerCase() );

				if (
					typeof data.num_of_imported_posts !== 'undefined' &&
					0 < total_content_import_items
				) {
					current_content_import_items =
						'all' === data.num_of_imported_posts
							? total_content_import_items
							: data.num_of_imported_posts;
					if (
						current_content_import_items !== 'all' &&
						current_content_import_items >
							total_content_import_items
					) {
						get_total_content_import_items();
					} else {
						update_progress_bar();
					}
				}

				if ( typeof data.url !== 'undefined' ) {
					// we have an ajax url action to perform.
					if ( data.hash === current_item_hash ) {
						currentSpan.addClass( 'status--failed' );
						find_next();
					} else {
						current_item_hash = data.hash;
						// Fix the undefined selected_index issue on new AJAX calls.
						if ( typeof data.selected_index === 'undefined' ) {
							data.selected_index =
								$( '.js-merlin-demo-import-select' ).val() || 0;
						}
						axios( {
							method: 'post',
							url: data.url,
							data: convertObjToFormData( data ),
						} )
							.then( ajax_callback )
							.catch( function ( error ) {
								ajax_callback( {
									data: error,
								} );
							} );
					}
				} else if ( typeof data.done !== 'undefined' ) {
					find_next();
				} else {
					find_next();
				}
			} else {
				currentSpan.addClass( 'error' );
				currentSpan.removeClass( 'installing' );
				clearInterval( progress_bar_interval );
				$( '.js-merlin-progress-bar-percentage' )
					.addClass( 'error' )
					.html( 'Error!' );
				$( '.merlin__progress-bar .js-merlin-progress-bar' ).addClass(
					'error'
				);
				sAlert( {
					text: data.message,
				} );
			}
		}

		function process_current() {
			if ( current_item ) {
				const $check = $current_node.find( 'input:checkbox' );
				if ( $check.is( ':checked' ) ) {
					axios( {
						method: 'post',
						url: merlin_params.ajaxurl,
						data: convertObjToFormData( {
							action: 'merlin_content',
							_wpnonce: merlin_params.wpnonce,
							content: current_item,
							selected_index:
								$( '.js-merlin-demo-import-select' ).val() || 0,
						} ),
					} )
						.then( ajax_callback )
						.catch( ajax_callback );
				} else {
					$current_node.addClass( 'skipping' );
					setTimeout( find_next, 300 );
				}
			}
		}

		function find_next() {
			let do_next = false;
			if ( $current_node ) {
				if ( ! $current_node.data( 'done_item' ) ) {
					items_completed++;
					$current_node.data( 'done_item', 1 );
				}
				$current_node.find( '.spinner' ).css( 'visibility', 'hidden' );
			}
			const $items = $( '.merlin__drawer--import-content__list-item' );
			$items.each( function () {
				if ( current_item === '' || do_next ) {
					current_item = $( this ).data( 'content' );
					$current_node = $( this );
					process_current();
					do_next = false;
				} else if ( $( this ).data( 'content' ) === current_item ) {
					do_next = true;
				}
			} );
			if ( items_completed >= $items.length ) {
				complete();
			}
		}

		function init_content_import_progress_bar() {
			if (
				! $(
					'.merlin__drawer--import-content__list-item .checkbox-content'
				).is( ':checked' )
			) {
				return false;
			}
			get_total_content_import_items().then( function () {} );
		}

		async function get_total_content_import_items( selected_index ) {
			if ( selected_index === undefined ) {
				selected_index =
					$( '.js-merlin-demo-import-select' ).val() || 0;
			}
			get_total_times++;
			await axios( {
				method: 'post',
				url: merlin_params.ajaxurl,
				data: convertObjToFormData( {
					action: 'merlin_get_total_content_import_items',
					wpnonce: merlin_params.wpnonce,
					selected_index,
				} ),
			} )
				.then( function ( { data } ) {
					total_content_import_items = data.data;
					if ( 0 < total_content_import_items ) {
						update_progress_bar();
						// Change the value of the progress bar constantly for a small amount (0,2% per sec), to improve UX.
						progress_bar_interval = setInterval( function () {
							current_content_import_items =
								current_content_import_items +
								( total_content_import_items * 0.15 ) / 100;
							update_progress_bar();
						}, 1000 );
					} else if ( get_total_times < 3 ) {
						get_total_content_import_items( selected_index );
					}
				} )
				.catch( function () {
					if ( get_total_times < 3 ) {
						get_total_content_import_items( selected_index );
					}
				} );
		}

		function valBetween( v, min, max ) {
			return Math.min( max, Math.max( min, v ) );
		}

		function update_progress_bar() {
			$( '.js-merlin-progress-bar' ).css(
				'width',
				( current_content_import_items / total_content_import_items ) *
					100 +
					'%'
			);

			const $percentage = valBetween(
				( current_content_import_items / total_content_import_items ) *
					100,
				0,
				99
			);

			$( '.js-merlin-progress-bar-percentage' ).html(
				Math.round( $percentage ) + '%'
			);

			if (
				1 ===
				current_content_import_items / total_content_import_items
			) {
				clearInterval( progress_bar_interval );
			}
		}

		return {
			init( btn ) {
				const drawerImportContent = $(
					'.merlin__drawer--import-content'
				);
				drawerImportContent.addClass( 'installing' );
				drawerImportContent.find( 'input' ).prop( 'disabled', true );
				complete = function () {
					axios( {
						method: 'post',
						url: merlin_params.ajaxurl,
						data: convertObjToFormData( {
							action: 'merlin_import_finished',
							wpnonce: merlin_params.wpnonce,
							selected_index:
								$( '.js-merlin-demo-import-select' ).val() || 0,
						} ),
					} );

					setTimeout( function () {
						$( '.js-merlin-progress-bar-percentage' ).html(
							'100%'
						);
					}, 100 );

					setTimeout( function () {
						body.removeClass( drawerOpened );
					}, 500 );

					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'js--finished' );
					}, 1500 );

					setTimeout( function () {
						$( '.merlin__body' ).addClass( 'exiting' );
					}, 3400 );

					setTimeout( function () {
						window.location.href = btn.href;
					}, 4000 );
				};
				init_content_import_progress_bar();
				find_next();
			},
		};
	}

	function merlinLoadingButton( btn ) {
		const $button = jQuery( btn );

		if ( $button.data( 'done-loading' ) === 'yes' ) {
			return false;
		}

		$button.data( 'done-loading', 'yes' );

		$button.addClass( 'merlin__button--loading' );

		return {
			done() {
				// eslint-disable-next-line no-unused-vars
				const completed = true;
				$button.attr( 'disabled', false );
			},
		};
	}

	function convertObjToFormData( obj ) {
		const form_data = new FormData();

		for ( const key in obj ) {
			form_data.append( key, obj[ key ] );
		}
		return form_data;
	}

	function sAlert( options = {}, callback = function () {}, mixin = {} ) {
		const default_mixin = {
			toast: true,
			position: 'bottom-end',
			showConfirmButton: false,
			showCloseButton: true,
			didOpen: ( toast ) => {
				toast.onmouseenter = Swal.stopTimer;
				toast.onmouseleave = Swal.resumeTimer;
			},
		};
		const Toast = Swal.mixin( {
			...default_mixin,
			...mixin,
		} );
		const default_options = {
			title: 'Error!',
			text: 'Something went wrong!',
			icon: 'error',
			confirmButtonText: 'OK',
		};
		const _options = {
			...default_options,
			...options,
		};
		Toast.fire( _options ).then( callback );
	}

	return {
		init() {
			$( windowLoaded );
		},
		callback( func ) {
			// eslint-disable-next-line no-console
			console.log( func );
			// eslint-disable-next-line no-console
			console.log( this );
		},
	};
} )( jQuery );

Merlin.init();
