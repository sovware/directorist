<?php
/**
 * @package Directorist
 * @since 7.2.0
 * @version 1.0
 */

$url     = 'https://app.directorist.com/wp-json/directorist/v1/get-promo';
$headers = [
    'user-agent' => 'Directorist/' . md5( esc_url( home_url() ) ) . ';',
    'Accept'     => 'application/json',
];

$config = [
    'method'      => 'GET',
    'timeout'     => 30,
    'redirection' => 5,
    'httpversion' => '1.0',
    'headers'     => $headers,
    'cookies'     => [],
];

$response_body = [];

try {
    $response = wp_remote_get( $url, $config );

    if ( ! is_wp_error( $response ) ) {
        $response_body = ( 'string' === gettype( $response['body'] ) ) ? json_decode( $response['body'], true ) : $response['body'];
        
        $display_promo        = ! empty( $response_body['display_promo'] ) ? $response_body['display_promo'] : '';
        if( ! $display_promo ) {
            return;
        }
        
        $banner_title        = ! empty( $response_body['banner_title'] ) ? $response_body['banner_title'] : '';
        $banner_description  = ! empty( $response_body['banner_description'] ) ? $response_body['banner_description'] : '';
        $sale_button_text    = ! empty( $response_body['sale_button_text'] ) ? $response_body['sale_button_text'] : '';
        $sale_button_link    = ! empty( $response_body['sale_button_link'] ) ? $response_body['sale_button_link'] : '';
        $offer_lists         = ! empty( $response_body['offer_lists'] ) ? $response_body['offer_lists'] : [];
        $get_now_button_text = ! empty( $response_body['get_now_button_text'] ) ? $response_body['get_now_button_text'] : '';
        $get_now_button_link = ! empty( $response_body['get_now_button_link'] ) ? $response_body['get_now_button_link'] : '';
    }
    
} catch ( Exception $e ) {
    return;
}
?>
 <div class="directorist_membership-notice">
    <div class="directorist_membership-notice__content">
        <img src="https://ps.w.org/directorist/assets/icon-256x256.gif?rev=2642947" alt="Drectorist membership notice">
        <div class="directorist_membership-notice__text">
            <?php 
            if( $banner_title ){ ?>
                <h4><?php echo esc_attr( $banner_title ); ?></h4>
            <?php } 
            if( $banner_description ){ ?>
                <p><?php echo esc_attr( $banner_description ); ?></p>
            <?php }  
            if( $sale_button_text ){ ?>
                <a class="directorist_membership-sale-badge" target="_blank" href="<?php echo esc_attr( $sale_button_link ); ?>"><?php echo esc_attr( $sale_button_text ); ?></a>
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
                <span class="directorist_membership-notice__list--text"><?php echo esc_attr( $offer ); ?></span>
            </li>
        <?php } ?>
    </ul>
    <?php } 
    if( $get_now_button_text ) { ?>
        <div class="directorist_membership-notice__action">
            <a href="<?php echo esc_url( $get_now_button_link ); ?>" target="_blank" class="directorist_membership-btn"><?php echo esc_attr( $get_now_button_text ); ?></a>
        </div>
    <?php } ?>
    <a href="#" class="directorist_membership-notice-close">
        <i class="fa fa-times"></i>
    </a>
</div>