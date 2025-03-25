/**
 * Promotion Popup.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
'use strict';

import { parseInt } from 'lodash/string';
class RbbThemePromotionPopup {
	constructor() {
		const $id = '#rbb-promotion-popup';
		const $default = {
			delay: 500, // milliseconds
			repeat: 5, // minutes
			dont_show_again: false,
			dont_show_again_expired: 1440,
		};
		const $dataConfig = jQuery( $id ).data( 'promotion' );
		const $config = {
			...$default,
			...$dataConfig,
		};

		const $lastShow = localStorage.getItem( 'rbb_promotion_last_show' );
		this.delete_dont_show_again(
			$lastShow,
			$config.dont_show_again_expired
		);
		if ( ! $lastShow || this.can_show( $lastShow, $config.repeat ) ) {
			setTimeout( function () {
				RisingBambooModal.modal( $id );
				localStorage.setItem( 'rbb_promotion_last_show', Date.now() );
				const $dontShowAgainInput = jQuery( $id ).find(
					'input#rbb_dont_show_again'
				);
				jQuery( $dontShowAgainInput ).on( 'change', function () {
					if ( this.checked ) {
						localStorage.setItem(
							'rbb_promotion_dont_show_again',
							true
						);
					} else {
						localStorage.removeItem(
							'rbb_promotion_dont_show_again'
						);
					}
				} );
			}, parseInt( $config.delay ) );
		}
	}

	can_show( $time, $repeat ) {
		if ( $time ) {
			const $checkPoint = new Date(
				parseInt( $time ) + parseInt( $repeat * 60000 )
			).getTime();
			const $dontShowAgain =
				localStorage.getItem( 'rbb_promotion_dont_show_again' ) ??
				false;
			if ( $checkPoint < Date.now() && ! $dontShowAgain ) {
				return true;
			}
		}
		return false;
	}
	delete_dont_show_again( $lastShow, $expired ) {
		const $dontShowAgain = localStorage.getItem(
			'rbb_promotion_dont_show_again'
		);
		if ( $lastShow && $dontShowAgain ) {
			const $checkPoint = new Date(
				parseInt( $lastShow ) + parseInt( $expired * 60000 )
			).getTime();
			if ( $checkPoint < Date.now() ) {
				localStorage.removeItem( 'rbb_promotion_dont_show_again' );
			}
		} else {
			localStorage.removeItem( 'rbb_promotion_dont_show_again' );
		}
	}
}

export default RbbThemePromotionPopup;
