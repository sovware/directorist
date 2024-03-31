<?php
/**
 * @package Directorist
 * @since 7.2.0
 * @version 1.0
 */

$response_body       = ATBDP_Upgrade::promo_remote_get();
$display_promo       = ! empty( $response_body->display_promo ) ? $response_body->display_promo : '';
$promo_version       = ! empty( $response_body->promo_version ) ? $response_body->promo_version : '';
$directorist_promo_closed = get_user_meta( get_current_user_id(), '_directorist_promo_closed', true );

if( ! $display_promo || ( $directorist_promo_closed && ( $directorist_promo_closed == $promo_version ) ) ) {
	return;
}

$banner_title        = ! empty( $response_body->banner_title ) ? $response_body->banner_title : '';
$banner_description  = ! empty( $response_body->banner_description ) ? $response_body->banner_description : '';
$sale_button_text    = ! empty( $response_body->sale_button_text ) ? $response_body->sale_button_text : '';
$sale_button_link    = ! empty( $response_body->sale_button_link ) ? ATBDP_Upgrade::promo_link( $response_body->sale_button_link ) : '';
$offer_lists         = ! empty( $response_body->offer_lists ) ? $response_body->offer_lists : [];
$get_now_button_text = ! empty( $response_body->get_now_button_text ) ? $response_body->get_now_button_text : '';
$get_now_button_link = ! empty( $response_body->get_now_button_link ) ? ATBDP_Upgrade::promo_link( $response_body->get_now_button_link ) : '';

$url_args = [
	'close-directorist-promo-version' => $promo_version,
	'directorist_promo_nonce'         => wp_create_nonce( 'directorist_promo_nonce' )
];
?>
 <div class="directorist_membership-notice">
    <div class="directorist_membership-notice__content">
        <img src="<?php echo esc_url( DIRECTORIST_ASSETS . 'images/promo-logo.jpg' ); ?>" alt="Drectorist membership notice">
        <div class="directorist_membership-notice__text">
            <?php
            if( $banner_title ){ ?>
                <h4><?php echo esc_html( $banner_title ); ?></h4>
            <?php }
            if( $banner_description ){ ?>
                <p><?php echo esc_html( $banner_description ); ?></p>
            <?php }
            if( $sale_button_text ){ ?>
                <a class="directorist_membership-sale-badge" target="_blank" href="<?php echo esc_url( $sale_button_link ); ?>"><?php echo esc_html( $sale_button_text ); ?></a>
            <?php } ?>
        </div>
    </div>

    <?php
    if( $offer_lists ) { ?>
    <ul class="directorist_membership-notice__list">
        <?php
        foreach( $offer_lists as $offer ){ ?>
            <li>
                <span class="directorist_membership-notice__list--icon"><i class="fa fa-check"></i></span>
                <span class="directorist_membership-notice__list--text"><?php echo esc_html( $offer ); ?></span>
            </li>
        <?php } ?>
    </ul>
    <?php }
    if( $get_now_button_text ) { ?>
        <div class="directorist_membership-notice__action">
            <a href="<?php echo esc_url( $get_now_button_link ); ?>" target="_blank" class="directorist_membership-btn"><?php echo esc_html( $get_now_button_text ); ?></a>
        </div>
    <?php } ?>
    <a href="<?php echo esc_url( add_query_arg( $url_args, atbdp_get_current_url() ) );?>" class="directorist_membership-notice-close">
        <i class="fa fa-times"></i>
    </a>
</div>