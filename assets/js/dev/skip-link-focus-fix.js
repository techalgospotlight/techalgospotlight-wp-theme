/**
 * File skip-link-focus-fix.js
 *
 * Helps with accessibility for keyboard only users.
 * This is the source file for what is minified in the astra_skip_link_focus_fix() PHP function.
 *
 * Learn more: https://github.com/Automattic/_s/pull/136
 */

( function() {
	var isWebkit = -1 < navigator.userAgent.toLowerCase().indexOf( 'webkit' ),
		isOpera  = -1 < navigator.userAgent.toLowerCase().indexOf( 'opera' ),
		isIE     = -1 < navigator.userAgent.toLowerCase().indexOf( 'msie' );

	if ( ( isWebkit || isOpera || isIE ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
}() );
