var $ = jQuery;

// Open Delete Modal
$('.atbdp-directory-delete-link-action').on( 'click', function( e ) {
    e.preventDefault();

    let delete_link = $( this ).data( 'delete-link' );
    $( '.atbdp-directory-delete-link' ).prop( 'href', delete_link );
});

// Delete Action
$( '.atbdp-directory-delete-link' ).on( 'click', function( e ) {
    // e.preventDefault();
    $( this ).prepend( '<i class="fas fa-circle-notch fa-spin"></i> ' );

    $( '.atbdp-directory-delete-cancel-link' ).removeClass( 'cptm-modal-toggle' );
    $( '.atbdp-directory-delete-cancel-link' ).addClass( 'atbdp-disabled' );
});