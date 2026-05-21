( function() {
	function closeAllPopovers() {
		document.querySelectorAll( '.directorist-rejection-popover.is-open' ).forEach( function( p ) {
			p.classList.remove( 'is-open' );
			var trigger = p.closest( '.directorist-see-why-wrap' );
			if ( trigger ) {
				var btn = trigger.querySelector( '.directorist-see-why' );
				if ( btn ) {
					btn.setAttribute( 'aria-expanded', 'false' );
				}
			}
		} );
	}

	document.addEventListener( 'click', function( e ) {
		var trigger = e.target.closest ? e.target.closest( '.directorist-see-why' ) : null;

		if ( trigger ) {
			var popover = trigger.nextElementSibling;
			var isOpen  = popover.classList.contains( 'is-open' );

			closeAllPopovers();

			if ( ! isOpen ) {
				popover.classList.add( 'is-open' );
				trigger.setAttribute( 'aria-expanded', 'true' );
			}

			return;
		}

		if ( ! e.target.closest || ! e.target.closest( '.directorist-see-why-wrap' ) ) {
			closeAllPopovers();
		}
	} );

	document.addEventListener( 'keydown', function( e ) {
		if ( e.key === 'Escape' ) {
			closeAllPopovers();
		}
	} );
} )();
