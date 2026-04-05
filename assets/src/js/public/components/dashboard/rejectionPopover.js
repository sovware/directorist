( function() {
	document.addEventListener( 'click', function( e ) {
		var trigger = e.target.closest ? e.target.closest( '.directorist-see-why' ) : null;

		if ( trigger ) {
			e.preventDefault();

			var popover = trigger.nextElementSibling;
			var isOpen  = popover.classList.contains( 'is-open' );

			document.querySelectorAll( '.directorist-rejection-popover.is-open' ).forEach( function( p ) {
				p.classList.remove( 'is-open' );
			} );

			if ( ! isOpen ) {
				popover.classList.add( 'is-open' );
			}

			return;
		}

		if ( ! e.target.closest || ! e.target.closest( '.directorist-see-why-wrap' ) ) {
			document.querySelectorAll( '.directorist-rejection-popover.is-open' ).forEach( function( p ) {
				p.classList.remove( 'is-open' );
			} );
		}
	} );
} )();
