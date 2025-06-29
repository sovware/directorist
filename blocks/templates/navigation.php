<?php
/**
 * @author  wpWax
 * @since   1.0
 * @version 1.0
 */

use Directorist\Directorist_Listing_Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( atbdp_is_page( 'dashboard' ) ) {
    return;
}

$dashboard     = Directorist_Listing_Dashboard::instance();
$dashboard_url = get_permalink( get_directorist_option( 'user_dashboard' ) );
?>

<div class="directorist-account-block-logged-mode__navigation">

    <ul>

        <?php foreach ( $dashboard->dashboard_tabs() as $key => $value ) : ?>

            <li>

                <a href="<?php echo esc_url( $dashboard_url ) . '#' . $key; ?>">

                    <span class="directorist_menuItem-icon">
                        <?php directorist_icon( $value['icon'] );?>
                    </span>

                    <?php echo wp_kses_post( $value['title'] ); ?>

                </a>

            </li>

        <?php endforeach;?>

        <?php if ( $dashboard->user_can_submit() ) : ?>

            <li>

                <a href="<?php echo esc_url( ATBDP_Permalink::get_add_listing_page_link() ); ?>">

                    <span class="directorist_menuItem-icon"><?php directorist_icon( 'las la-plus' );?> </span>

                    <?php esc_html_e( 'Add Listing', 'directorist' );?>

                </a>

            </li>

        <?php endif;?>

        <li>

            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">

                <span class="directorist_menuItem-icon"><?php directorist_icon( 'las la-sign-out-alt' );?>  </span>

                <?php esc_html_e( 'Log Out', 'directorist' );?>

            </a>

        </li>

    </ul>

</div>