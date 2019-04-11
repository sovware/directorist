
(function ($) {
    //Advance search
    // Populate atbdp child terms dropdown
    $( '.bdas-terms' ).on( 'change', 'select', function( e ) {

        e.preventDefault();

        var $this    = $( this );
        var taxonomy = $this.data( 'taxonomy' );
        var parent   = $this.data( 'parent' );
        var value    = $this.val();
        var classes  = $this.attr( 'class' );

        $this.closest( '.bdas-terms' ).find( 'input.bdas-term-hidden' ).val( value );
        $this.parent().find( 'div:first' ).remove();

        if( parent != value ) {
            $this.parent().append( '<div class="bdas-spinner"></div>' );

            var data = {
                'action': 'bdas_public_dropdown_terms',
                'taxonomy': taxonomy,
                'parent': value,
                'class': classes,
                'security': atbdp_search.ajaxnonce
            };

            $.post( atbdp_search.ajax_url, data, function( response ) {
                $this.parent().find( 'div:first' ).remove();
                $this.parent().append( response );
            });
        };

    });

    // load custom fields of the selected category in the search form
    $( 'body' ).on( 'change', '.bdas-category-search', function() {

        var $search_elem = $( this ).closest ( 'form' ).find( ".atbdp-custom-fields-search" );

        if( $search_elem.length ) {

            $search_elem.html( '<div class="atbdp-spinner"></div>' );

            var data = {
                'action': 'atbdp_custom_fields_search',
                'term_id': $( this ).val(),
                'security': atbdp_search.ajaxnonce
            };

            $.post( atbdp_search.ajax_url, data, function(response) {
                $search_elem.html( response );
            });

        };

    });

})(jQuery);

