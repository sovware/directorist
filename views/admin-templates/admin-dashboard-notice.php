<?php
/**
 * @package Directorist
 * @since 8.7.1
 * @version 8.7.1
 */

$data = ATBDP_Upgrade::dashboard_promo_remote_get();

if ( ! is_object( $data ) ) {
    $data = new stdClass();
}

$display_notice = isset( $data->display_notice ) ? (bool) $data->display_notice : true;

if ( ! $display_notice ) {
    return;
}

$promo_version   = ! empty( $data->promo_version ) ? sanitize_text_field( $data->promo_version ) : 'directorist-dashboard-promo-default';
$closed_version  = get_user_meta( get_current_user_id(), 'directorist_dashboard_promo_closed_version', true );
$banner_title    = ! empty( $data->banner_title ) ? sanitize_text_field( $data->banner_title ) : __( 'Directorist Pro', 'directorist' );
$notice_text     = ! empty( $data->notice_text ) ? wp_kses_post( $data->notice_text ) : wp_kses_post( __( '<span>Your directory is leaving money on the table,</span> charges for listings, takes bookings, accepts payments & more. <strong>Claim your 40% discount today.</strong>', 'directorist' ) );
$sale_text       = ! empty( $data->sale_text ) ? sanitize_text_field( $data->sale_text ) : __( 'Claim your 40% discount today.', 'directorist' );
$upgrade_text    = ! empty( $data->upgrade_now_text ) ? sanitize_text_field( $data->upgrade_now_text ) : __( 'Upgrade Now', 'directorist' );
$upgrade_url     = ! empty( $data->upgrade_now_text_link ) ? esc_url( $data->upgrade_now_text_link ) : 'https://directorist.com/pricing/';
$sale_button_url = ! empty( $data->sale_button_link ) ? esc_url( $data->sale_button_link ) : 'https://directorist.com/pricing/';

if ( $promo_version && $closed_version === $promo_version ) {
    return;
}

if ( empty( $upgrade_url ) ) {
    $upgrade_url = $sale_button_url;
}

if ( empty( $notice_text ) && ! empty( $sale_text ) ) {
    $notice_text = $sale_text;
}

if ( empty( $upgrade_url ) ) {
    return;
}

$dismiss_url = add_query_arg(
    array(
        'close-directorist-dashboard-promo-version' => $promo_version,
        'directorist_dashboard_promo_nonce'         => wp_create_nonce( 'directorist_dashboard_promo_nonce' ),
    ),
    admin_url( 'index.php' )
);
?>
<div class="notice notice-info is-dismissible directorist-dashboard-notice">
    <p>
        <strong><?php echo esc_html( $banner_title ); ?>:</strong>
        <?php echo wp_kses_post( $notice_text ); ?>
        <a
            href="<?php echo esc_url( $upgrade_url ); ?>"
            target="_blank"
            rel="noopener noreferrer"
        >
            <?php echo esc_html( $upgrade_text ); ?>
        </a>
    </p>

    <a
        class="notice-dismiss"
        href="<?php echo esc_url( $dismiss_url ); ?>"
        aria-label="<?php esc_attr_e( 'Dismiss this notice.', 'directorist' ); ?>"
    >
        <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'directorist' ); ?></span>
    </a>
</div>
