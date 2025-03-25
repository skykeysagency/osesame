/**
 * OverlayScrollBar
 *
 * @version 1.0.0
 * @since 1.0.0
 */
'use strict';

import { OverlayScrollbars } from 'overlayscrollbars';

class RbbThemeOverlayScrollBar {
	constructor() {
		document.addEventListener( 'DOMContentLoaded', function () {
			//The first argument are the elements to which the plugin shall be initialized
			//The second argument has to be at least an empty object or an object with your desired options
			OverlayScrollbars(
				document.querySelectorAll( '.rbb-overlay-scroll-bar' ),
				{}
			);
		} );
	}
}

export default RbbThemeOverlayScrollBar;
