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
<style>
    .directorist-dashboard-notice.notice {
        position: relative;
        display: block;
        padding: 0;
        border: none;
        border-left: 5px solid #00a32a;
        border-radius: 0;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(16, 24, 40, 0.08);
        border-radius: 0 4px 4px 0;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__inner {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 15px 64px 15px 15px;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__eyebrow {
        flex: 0 0 auto;
        margin: 0;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__eyebrow-text {
        display: inline-flex;
        align-items: center;
        min-height: 30px;
        padding: 0 18px;
        background: #f3f4f6;
        color: #5b6474;
        font-size: 14px;
        font-weight: 700;
        line-height: 1;
        letter-spacing: 0.06em;
        border-radius: 3px;
        text-transform: uppercase;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__body {
        flex: 1 1 auto;
        margin: 0;
        font-size: 16px;
        font-weight: 400;
        line-height: 1.45;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__body span{
        font-weight: 500;
    }
    .directorist-dashboard-notice.notice .directorist-dashboard-notice__body strong {
        font-weight: 700;
        color: #111827;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__link {
        display: inline-flex;
        align-items: center;
        margin-left: 10px;
        color: #2563eb;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.3;
        text-decoration: none;
        white-space: nowrap;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__link:hover,
    .directorist-dashboard-notice.notice .directorist-dashboard-notice__link:focus {
        color: #1d4ed8;
        text-decoration: none;
        box-shadow: none;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__dismiss.notice-dismiss {
        top: 50%;
        right: 24px;
        width: 28px;
        height: 28px;
        padding: 0;
        transform: translateY(-50%);
        color: #b6beca;
        text-decoration: none;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__dismiss.notice-dismiss:before {
        font-size: 24px;
        width: 28px;
        height: 28px;
        line-height: 28px;
    }

    .directorist-dashboard-notice.notice .directorist-dashboard-notice__dismiss.notice-dismiss:hover,
    .directorist-dashboard-notice.notice .directorist-dashboard-notice__dismiss.notice-dismiss:focus {
        color: #8c95a3;
        box-shadow: none;
    }

    @media only screen and (max-width: 991px) {
        .directorist-dashboard-notice.notice .directorist-dashboard-notice__inner {
            align-items: flex-start;
            flex-direction: column;
            gap: 16px;
            padding: 22px 64px 22px 24px;
        }

        .directorist-dashboard-notice.notice .directorist-dashboard-notice__dismiss.notice-dismiss {
            top: 18px;
            right: 18px;
            transform: none;
        }
    }

    @media only screen and (max-width: 767px) {
        .directorist-dashboard-notice.notice .directorist-dashboard-notice__body,
        .directorist-dashboard-notice.notice .directorist-dashboard-notice__link {
            font-size: 16px;
        }

        .directorist-dashboard-notice.notice .directorist-dashboard-notice__link {
            margin-left: 14px;
        }
    }

    @media only screen and (max-width: 575px) {
        .directorist-dashboard-notice.notice .directorist-dashboard-notice__link {
            display: inline;
            margin-left: 10px;
            white-space: normal;
        }
    }
</style>
<div class="notice is-dismissible directorist-dashboard-notice">
    <div class="directorist-dashboard-notice__inner">
        <p class="directorist-dashboard-notice__eyebrow">
            <span class="directorist-dashboard-notice__eyebrow-text">
                <?php echo esc_html( $banner_title ); ?>
            </span>
        </p>

        <p class="directorist-dashboard-notice__body">
            <?php echo wp_kses_post( $notice_text ); ?>
            <a
                class="directorist-dashboard-notice__link"
                href="<?php echo esc_url( $upgrade_url ); ?>"
                target="_blank"
                rel="noopener noreferrer"
            >
                <?php echo esc_html( $upgrade_text ); ?> <span aria-hidden="true">&rarr;</span>
            </a>
        </p>
    </div>

    <a
        class="notice-dismiss directorist-dashboard-notice__dismiss"
        href="<?php echo esc_url( $dismiss_url ); ?>"
        aria-label="<?php esc_attr_e( 'Dismiss this notice.', 'directorist' ); ?>"
    >
        <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'directorist' ); ?></span>
    </a>
</div>
