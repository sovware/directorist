<?php
/**
 * WP-CLI smoke coverage for the Search Modal and Account Button blocks.
 *
 * Run with:
 * wp eval-file wp-content/plugins/directorist/tests/wp-cli/modal-blocks-smoke.php
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fail the smoke test when a condition is not met.
 *
 * @param bool   $condition Condition to evaluate.
 * @param string $message   Failure message.
 *
 * @return void
 */
function directorist_modal_blocks_assert( $condition, $message ) {
    if ( ! $condition ) {
        WP_CLI::error( $message );
    }
}

$legacy_search  = '<!-- wp:directorist/search-modal --><div class="wp-block-directorist-search-modal"><button type="button" class="wp-block-button__link wp-element-button">Search modal</button></div><!-- /wp:directorist/search-modal -->';
$legacy_account = '<!-- wp:directorist/account-button --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button">Account button</button></div><!-- /wp:directorist/account-button -->';
$custom_search  = '<!-- wp:directorist/search-modal {"styleDisplay":"icon_and_text","iconClass":"las la-filter","iconSize":36,"iconColor":"#ff0000","iconPosition":"after","iconGap":12,"accessibleLabel":"Open filters"} --><div class="wp-block-directorist-search-modal"><button type="button" class="wp-block-button__link wp-element-button">Find providers</button></div><!-- /wp:directorist/search-modal -->';
$custom_account = '<!-- wp:directorist/account-button {"styleDisplay":"icon_and_text","iconClass":"las la-sign-in-alt","accessibleLabel":"Sign in now"} --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button">Sign in</button></div><!-- /wp:directorist/account-button -->';

$multi_search_output   = do_blocks( $legacy_search . $legacy_search );
$multi_account_output  = do_blocks( $legacy_account . $legacy_account );
$legacy_search_output  = do_blocks( $legacy_search );
$legacy_account_output = do_blocks( $legacy_account );
$custom_search_output  = do_blocks( $custom_search );
$custom_account_output = do_blocks( $custom_account );

directorist_modal_blocks_assert( false !== strpos( $legacy_search_output, 'aria-label="Search listings"' ), 'Legacy Search Modal did not receive its accessible default.' );
directorist_modal_blocks_assert( false !== strpos( $legacy_account_output, 'Account button' ), 'Legacy Account Button text was not migrated from saved markup.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, 'Find providers' ), 'Custom Search Modal text was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, 'directorist-modal-trigger--reverse' ), 'Search Modal icon position was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, '--directorist-modal-icon-size:36px' ), 'Search Modal icon size was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, '--directorist-modal-icon-color:#ff0000' ), 'Search Modal icon color was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, 'filter-solid.svg' ), 'Search Modal custom Directorist icon was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_account_output, 'Sign in now' ), 'Account Button accessible label was not rendered.' );
directorist_modal_blocks_assert( 1 === substr_count( $multi_search_output, 'id="directorist-search-popup"' ), 'Multiple Search Modal triggers rendered duplicate search forms.' );
directorist_modal_blocks_assert( 2 === substr_count( $multi_search_output, 'aria-controls="directorist-search-popup"' ), 'Multiple Search Modal triggers do not share the search dialog.' );
directorist_modal_blocks_assert( 1 === substr_count( $multi_account_output, 'id="directorist-account-block-login-modal"' ), 'Multiple Account Button triggers rendered duplicate authentication forms.' );
directorist_modal_blocks_assert( 2 === substr_count( $multi_account_output, 'aria-controls="directorist-account-block-login-modal"' ), 'Multiple Account Button triggers do not share the account dialog.' );

$administrator = get_users(
    [
        'role'   => 'administrator',
        'number' => 1,
        'fields' => 'ids',
    ]
);

if ( $administrator ) {
    wp_set_current_user( $administrator[0] );

    $logged_in_account = '<!-- wp:directorist/account-button {"loggedInDisplay":"icon_and_name","authorIconClass":"las la-user-circle","showDashboardMenu":true} --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button">Account</button></div><!-- /wp:directorist/account-button -->';
    $logged_in_output  = do_blocks( $logged_in_account );

    directorist_modal_blocks_assert( false !== strpos( $logged_in_output, wp_get_current_user()->display_name ), 'Logged-in display name was not rendered.' );
    directorist_modal_blocks_assert( false !== strpos( $logged_in_output, 'directorist-account-block-logged-mode__navigation' ), 'Logged-in dashboard menu was not rendered.' );
    directorist_modal_blocks_assert( false !== strpos( $logged_in_output, 'aria-expanded="false"' ), 'Logged-in trigger state was not rendered.' );

    wp_set_current_user( 0 );
}

WP_CLI::success( 'Directorist modal block smoke tests passed.' );
