<?php
/**
 * @package Directorist
 * @since 8.7.1
 * @version 8.7.1
 */

$data = ATBDP_Upgrade::dashboard_promo_remote_get();
if ( empty( $data ) || empty( $data->display_notice ) ) {
    return;
}

$promo_version   = ! empty( $data->promo_version ) ? sanitize_text_field( $data->promo_version ) : '';
$closed_version  = get_user_meta( get_current_user_id(), 'directorist_dashboard_promo_closed_version', true );
$banner_title    = ! empty( $data->banner_title ) ? sanitize_text_field( $data->banner_title ) : __( 'Directorist Pro', 'directorist' );
$sale_text       = ! empty( $data->sale_text ) ? sanitize_text_field( $data->sale_text ) : '';
$upgrade_text    = ! empty( $data->upgrade_now_text ) ? sanitize_text_field( $data->upgrade_now_text ) : __( 'Upgrade Now', 'directorist' );
$upgrade_url     = ! empty( $data->upgrade_now_text_link ) ? esc_url( $data->upgrade_now_text_link ) : '';
$sale_button_url = ! empty( $data->sale_button_link ) ? esc_url( $data->sale_button_link ) : '';

if ( $promo_version && $closed_version === $promo_version ) {
    return;
}

if ( empty( $upgrade_url ) ) {
    $upgrade_url = $sale_button_url;
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
<div
    class="notice is-dismissible"
    style="display: flex; flex-wrap:wrap; justify-content:space-between"
>
    <div style="display: flex; flex-wrap:wrap; justify-content:space-between">
        <p style="">
            <span style="display: flex; flex-wrap:wrap; justify-content:space-between">
                <?php echo esc_html( $banner_title ); ?>
            </span>
        </p>

        <p style="display: flex; flex-wrap:wrap; justify-content:space-between">
            <strong style="font-weight:700;">
                <?php esc_html_e( 'Your directory is leaving money on the table,', 'directorist' ); ?>
            </strong>
            <?php esc_html_e( ' charges for listings, takes bookings, accepts payments & more.', 'directorist' ); ?>
            <?php if ( $sale_text ) : ?>
                <strong style="font-weight:700;"> <?php echo esc_html( $sale_text ); ?></strong>
            <?php endif; ?>
            <a
                href="<?php echo esc_url( $upgrade_url ); ?>"
                target="_blank"
                rel="noopener noreferrer"
                style="display:inline-block;margin-left:18px;color:#2563eb;font-weight:700;text-decoration:none;"
            >
                <?php echo esc_html( $upgrade_text ); ?> <span aria-hidden="true">&rarr;</span>
            </a>
        </p>
    </div>

    <a
        class="notice-dismiss"
        href="<?php echo esc_url( $dismiss_url ); ?>"
        style="top:50%;right:22px;transform:translateY(-50%);width:32px;height:32px;text-decoration:none;color:#98a2b3;"
    >
        <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'directorist' ); ?></span>
    </a>
</div>
