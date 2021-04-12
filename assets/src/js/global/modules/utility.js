var $ = jQuery;

document.querySelectorAll('.la-icon i').forEach(function(item) {
    className.push(item.getAttribute('class'))
});

// Handle Disabled Link Action
$('.atbdp-disabled').on( 'click', function( e ) {
    e.preventDefault();
});

// Toggle Modal
$( '.cptm-modal-toggle' ).on( 'click', function( e ) {
    e.preventDefault();
    let target_class = $( this ).data( 'target' );
    
    $( '.' + target_class ).toggleClass('active');
});

// Change label on file select/change
$('.cptm-file-field').on( 'change', function( e ) {
    let target_id = $( this ).attr( 'id' );
    $( 'label[for='+ target_id +']').text( 'Change' );
});


