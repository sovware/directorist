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

        this.navLinksSetup = function (selector) {
            var elements = document.querySelectorAll(selector);
            if (!elements.length) return;

            elements.forEach((el) => {
                const links = el.querySelectorAll('.directorist-tab__nav__link:not(.atbd-dash-nav-dropdown)');
                links.forEach((link) => {
                    link.style.cursor = 'pointer';
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        event.stopPropagation();

                        var ul = event.target.closest('.directorist-tab__nav'),
                            main = ul.nextElementSibling,
                            item_link = ul.querySelectorAll('.directorist-tab__nav__link'),
                            section = main.querySelectorAll('.directorist-tab__pane');

                        // Activate Navigation Panel
                        item_link.forEach((link) => {
                            link.classList.remove('directorist-tab__nav__active');
                        });

                        const parentNavRef = event.target.getAttribute( 'data-parent-nav' );

                        if ( parentNavRef ) {
                            const parentNav = document.querySelector( parentNavRef );
                            if ( parentNav ) {
                                parentNav.classList.add('directorist-tab__nav__active');
                            }
                        } else {
                            event.target.classList.add('directorist-tab__nav__active');
                            var dropDownToggler = event.target.closest('.atbdp_tab_nav--has-child')?.querySelector('.atbd-dash-nav-dropdown');
                            if (dropDownToggler && !dropDownToggler.classList.contains('directorist-tab__nav__active')) {
                                dropDownToggler.classList.add('directorist-tab__nav__active');
                            }
                        }

                        // Activate Content Panel
                        section.forEach((sectionItem) => {
                            sectionItem.classList.remove('directorist-tab__pane--active');
                        });

                        const content_id = event.target.getAttribute('target');
                        document.getElementById(content_id).classList.add('directorist-tab__pane--active');

                        // Add Hash To Window Location
                        let hashID = content_id;
                        const link = event.target.getAttribute('href');

                        if (link) {
                            const matchLink = link.match(/#(.+)/);
                            hashID = matchLink ? matchLink[1] : hashID;
                        }

                        const hasMatch = window.location.hash.match( new RegExp( `^${link}$` ) );
                        window.location.hash = hasMatch ? hasMatch[0] : "#" + hashID;

                        var newHash = window.location.hash;
                        var newUrl = window.location.pathname + newHash;

                        window.history.replaceState(null, null, newUrl);
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
