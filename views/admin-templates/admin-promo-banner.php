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

if ( ! $display_promo || ( $directorist_promo_closed && ( $directorist_promo_closed == $promo_version ) ) ) {
    return;
}

$banner_title        = ! empty( $response_body->banner_title ) ? $response_body->banner_title : '';
$banner_description  = ! empty( $response_body->banner_description ) ? $response_body->banner_description : '';
$banner_badge_text   = ! empty( $response_body->banner_badge_text ) ? $response_body->banner_badge_text : '';
$feature_items       = ! empty( $response_body->feature_items ) && is_array( $response_body->feature_items ) ? $response_body->feature_items : [];
$offer_box_discount_text = ! empty( $response_body->offer_box_discount_text ) ? $response_body->offer_box_discount_text : '';
$offer_box_label     = ! empty( $response_body->offer_box_label ) ? $response_body->offer_box_label : '';
$offer_box_button_text = ! empty( $response_body->offer_box_button_text ) ? $response_body->offer_box_button_text : '';
$offer_box_button_link = ! empty( $response_body->offer_box_button_link ) ? ATBDP_Upgrade::promo_link( $response_body->offer_box_button_link ) : '';
$allowed_title_html  = [
    'span' => [
        'class' => [],
    ],
];
$allowed_discount_html = [
    'span' => [
        'class' => [],
    ],
];

$get_feature_item_value = static function( $feature_item, $key ) {
    if ( is_array( $feature_item ) && isset( $feature_item[ $key ] ) ) {
        return $feature_item[ $key ];
    }

    if ( is_object( $feature_item ) && isset( $feature_item->$key ) ) {
        return $feature_item->$key;
    }

    return '';
};

$get_feature_icon_class = static function( $icon_key ) {
    $icon_map = [
        'star'     => 'dashicons dashicons-star-filled',
        'calendar' => 'dashicons dashicons-calendar-alt',
        'puzzle'   => 'dashicons dashicons-admin-plugins',
        'support'  => 'dashicons dashicons-format-chat',
    ];

    return isset( $icon_map[ $icon_key ] ) ? $icon_map[ $icon_key ] : 'dashicons dashicons-yes-alt';
};

$promo_visual = DIRECTORIST_ASSETS . 'images/directorist-logo-solid.svg';

$url_args = [
    'close-directorist-promo-version' => $promo_version,
    'directorist_promo_nonce'         => wp_create_nonce( 'directorist_promo_nonce' )
];
?>
<div class="directorist_membership-notice">
    <div class="directorist_membership-notice__visual">
        <div class="directorist_membership-notice__visual-card">
            <img src="<?php echo esc_url( $promo_visual ); ?>" alt="<?php esc_attr_e( 'Directorist membership notice', 'directorist' ); ?>">
        </div>
    </div>

    <div class="directorist_membership-notice__body">
        <div class="directorist_membership-notice__body-top">
            <div class="directorist_membership-notice__heading">
                <?php if ( $banner_title ) { ?>
                    <h4><?php echo wp_kses( $banner_title, $allowed_title_html ); ?></h4>
                <?php } ?>

                <?php if ( $banner_description ) { ?>
                    <p><?php echo esc_html( $banner_description ); ?></p>
                <?php } ?>
            </div>

            <?php if ( $banner_badge_text ) { ?>
                <span class="directorist_membership-notice__badge">
                    <span class="dashicons dashicons-awards" aria-hidden="true"></span>
                    <?php echo esc_html( $banner_badge_text ); ?>
                </span>
            <?php } ?>
        </div>

        <?php if ( ! empty( $feature_items ) ) { ?>
            <ul class="directorist_membership-notice__features">
                <?php foreach ( $feature_items as $feature_item ) :
                    $feature_text = $get_feature_item_value( $feature_item, 'text' );
                    $feature_icon = $get_feature_item_value( $feature_item, 'icon' );

                    if ( empty( $feature_text ) ) {
                        continue;
                    }
                    ?>
                    <li class="directorist_membership-notice__feature">
                        <span class="directorist_membership-notice__feature-icon directorist_membership-notice__feature-icon--<?php echo esc_attr( sanitize_html_class( $feature_icon ? $feature_icon : 'default' ) ); ?>" aria-hidden="true">
                            <span class="<?php echo esc_attr( $get_feature_icon_class( $feature_icon ) ); ?>"></span>
                        </span>
                        <span class="directorist_membership-notice__feature-text"><?php echo esc_html( $feature_text ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php } ?>
    </div>

    <?php if ( $offer_box_discount_text || $offer_box_button_text ) { ?>
        <div class="directorist_membership-notice__offer">
            <div class="directorist_membership-notice__offer-card">
                <?php if ( $offer_box_discount_text ) { ?>
                    <div class="directorist_membership-notice__offer-discount"><?php echo wp_kses( $offer_box_discount_text, $allowed_discount_html ); ?></div>
                <?php } ?>

                <?php if ( $offer_box_label ) { ?>
                    <div class="directorist_membership-notice__offer-label"><?php echo esc_html( $offer_box_label ); ?></div>
                <?php } ?>

                <?php if ( $offer_box_button_text && $offer_box_button_link ) { ?>
                    <a href="<?php echo esc_url( $offer_box_button_link ); ?>" target="_blank" rel="noopener noreferrer" class="directorist_membership-notice__offer-button">
                        <?php echo esc_html( $offer_box_button_text ); ?>
                        <span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
                    </a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <a href="<?php echo esc_url( add_query_arg( $url_args, atbdp_get_current_url() ) );?>" class="directorist_membership-notice-close">
        <i class="fa fa-times"></i>
    </a>
</div>
