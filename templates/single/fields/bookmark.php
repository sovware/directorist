<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<?php 

if ( is_user_logged_in() ) { ?>

    <a href="javascript:void(0)" class="directorist-single-listing-action directorist-action-bookmark directorist-btn directorist-btn-sm directorist-btn-light atbdp-favourites">
        <?php echo wp_kses_post( the_atbdp_favourites_link() ); ?>
    </a>

<?php } else { ?>
    <a href="javascript:void(0)" class="directorist-single-listing-action directorist-btn directorist-btn-sm directorist-btn-light directorist-action-authenticate atbdp-require-login">
        <?php echo directorist_icon( 'las la-heart', false ) ?><span class="directorist-single-listing-action__text">Bookmark</span>
    </a>
<?php } ?>


    
