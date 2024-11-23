/*
    Plugin: Dash Tab
    Version: 1.0.0
    License: MIT
*/
(function() {
    this.DashTab = function ( selector ) {
        this.globalSetup = function () {
            if (window.isInitializedDashTab) {
                return;
            }

            window.isInitializedDashTab = true;
            this.activateNavLinkByURL();
        };

        this.activateNavLinkByURL = function() {
            let hash         = window.location.hash;
            let queryStrings = null;

            // Split the URL into its components
            var urlParts = hash.split(/[?|&]/);

            if ( urlParts.length > 1 ) {
                // Get Hash Link
                const hashLink = urlParts[0];

                // Get the search parameters
                queryStrings = JSON.parse( JSON.stringify( urlParts ) );
                queryStrings.splice( 0, 1 );
                queryStrings = queryStrings.filter( item => `${item}`.length );
                queryStrings = queryStrings.join( '&' );

                window.location.hash = hashLink;
                hash = window.location.hash;
            }

            // Activate Current Navigation Item
            var navLinks = document.querySelectorAll('.directorist-tab__nav__link');

            for ( const link of navLinks ) {
                let href   = link.getAttribute( 'href' );
                let target = link.getAttribute( 'target' );

                if ( href === hash || `#${target}` === hash || window.location.hash.match( new RegExp( `^${href}$` ) ) ) {
                    const parent = link.closest( '.atbdp_tab_nav--has-child' );

                    if ( parent ) {
                        const dropdownMenu = parent.querySelector( '.atbd-dashboard-nav' );
                        if ( dropdownMenu ) {
                            dropdownMenu.style.display = 'block';
                        }
                    }

                    link.click();
                    break;
                }
            }

            // Update Window History
            if ( queryStrings ) {
                // Reconstruct the URL with the updated search parameters
                var newUrl = window.location.pathname + window.location.hash + "?" + queryStrings;
                window.history.replaceState( null, null, newUrl );
            }
        };

        this.navLinksSetup = function(selector) {
            var elements = document.querySelectorAll(selector);
            if (!elements.length) return;

            elements.forEach((el) => {
                const links = el.querySelectorAll('.directorist-tab__nav__link:not(.atbd-dash-nav-dropdown)');
                links.forEach((link) => {
                    link.style.cursor = 'pointer';
                    link.addEventListener('click', (event) => {
                        event.preventDefault();

                        const nav = event.target.closest('.directorist-tab__nav');
                        const main = nav?.nextElementSibling;
                        const items = nav?.querySelectorAll('.directorist-tab__nav__link');
                        const sections = main?.querySelectorAll('.directorist-tab__pane');

                        items?.forEach((item) => item.classList.remove('directorist-tab__nav__active'));
                        sections?.forEach((section) => section.classList.remove('directorist-tab__pane--active'));

                        const targetId = event.target.getAttribute('target');
                        const targetPane = document.getElementById(targetId);
                        if (targetPane) targetPane.classList.add('directorist-tab__pane--active');

                        event.target.classList.add('directorist-tab__nav__active');
                        window.location.hash = `#${targetId}`;
                    });
                });
            });
        };

        if ( document.querySelector( selector ) ) {
            this.navLinksSetup( selector );
            this.globalSetup();
        }
    };
})();
