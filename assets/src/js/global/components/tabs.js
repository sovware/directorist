document.addEventListener( 'load', init, false );

function Tasks() {
    return {
        init: function() {
            this.initToggleTabLinks();
        },
    
        initToggleTabLinks: function() {
            const links = document.querySelectorAll( '.directorist-toggle-tab' );

            if ( ! links ) {
                return;
            }

            const self = this;

            [ ...links ].forEach( item => {
                item.addEventListener( 'click', function( event ) {
                    self.handleToggleTabLinksEvent( item, event )
                });
            });

        },

        handleToggleTabLinksEvent: function( item, event ) {
            event.preventDefault();

            const navContainerClass = item.getAttribute( 'data-nav-container' );
            const tabContainerClass = item.getAttribute( 'data-tab-container' );
            const tabClass          = item.getAttribute( 'data-tab' );

            if ( ! navContainerClass || ! tabContainerClass || ! tabClass ) {
                return;
            }

            const navContainer = item.closest( '.' + navContainerClass );
            const tabContainer = document.querySelector( '.' + tabContainerClass );
           
            if ( ! navContainer || ! tabContainer ) {
                return;
            }

            const tab = tabContainer.querySelector( '.' + tabClass );

            if ( ! tab ) {
                return;
            }

            // Remove Active Class
            const removeActiveClass = item => {
                item.classList.remove( '--is-active' );
            };

            // Toggle Nav
            const activeNavItems = navContainer.querySelectorAll( '.--is-active' );

            if ( activeNavItems ) {
                [ ...activeNavItems ].forEach( removeActiveClass );
            }

            item.classList.add( '--is-active' );

            // Toggle Tab
            const activeTabItems = tabContainer.querySelectorAll( '.--is-active' );

            if ( activeTabItems ) {
                [ ...activeTabItems ].forEach( removeActiveClass );
            }

            tab.classList.add( '--is-active' );

            // Update Query Var
            const queryVarKey   = item.getAttribute( 'data-query-var-key' );
            const queryVarValue = item.getAttribute( 'data-query-var-value' );

            if ( ! queryVarKey || ! queryVarValue ) {
                return;
            }

            this.addQueryParam( queryVarKey, queryVarValue );
        },

        addQueryParam: ( key, value ) => {
            const url = new URL( window.location.href );

            url.searchParams.set( key, value );
            window.history.pushState( {}, '', url.toString() );
        }
    };
}


function init() {
    const tasks = new Tasks();
    tasks.init();
}