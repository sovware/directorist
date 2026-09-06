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

$legacy_search   = '<!-- wp:directorist/search-modal --><div class="wp-block-directorist-search-modal"><button type="button" class="wp-block-button__link wp-element-button">Search modal</button></div><!-- /wp:directorist/search-modal -->';
$legacy_account  = '<!-- wp:directorist/account-button --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button">Account button</button></div><!-- /wp:directorist/account-button -->';
$empty_account   = '<!-- wp:directorist/account-button --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button"></button></div><!-- /wp:directorist/account-button -->';
$empty_search    = '<!-- wp:directorist/search-modal --><div class="wp-block-directorist-search-modal"><button type="button" class="wp-block-button__link wp-element-button"></button></div><!-- /wp:directorist/search-modal -->';
$custom_search   = '<!-- wp:directorist/search-modal {"styleDisplay":"icon_and_text","iconClass":"las la-filter","iconSize":36,"iconColor":"#ff0000","iconPosition":"after","iconGap":12,"accessibleLabel":"Open filters"} --><div class="wp-block-directorist-search-modal"><button type="button" class="wp-block-button__link wp-element-button">Find providers</button></div><!-- /wp:directorist/search-modal -->';
$custom_account  = '<!-- wp:directorist/account-button {"styleDisplay":"icon_and_text","iconClass":"las la-sign-in-alt","iconSize":34,"accessibleLabel":"Sign in now"} --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button">Sign in</button></div><!-- /wp:directorist/account-button -->';
$invalid_search  = '<!-- wp:directorist/search-modal {"iconClass":"las la-not-a-real-directorist-icon"} --><div class="wp-block-directorist-search-modal"><button type="button" class="wp-block-button__link wp-element-button"></button></div><!-- /wp:directorist/search-modal -->';
$invalid_account = '<!-- wp:directorist/account-button {"styleDisplay":"icon","iconClass":"las la-not-a-real-directorist-icon"} --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button"></button></div><!-- /wp:directorist/account-button -->';

$multi_search_output    = do_blocks( $legacy_search . $legacy_search );
$multi_account_output   = do_blocks( $legacy_account . $legacy_account );
$legacy_search_output   = do_blocks( $legacy_search );
$legacy_account_output  = do_blocks( $legacy_account );
$empty_account_output   = do_blocks( $empty_account );
$empty_search_output    = do_blocks( $empty_search );
$custom_search_output   = do_blocks( $custom_search );
$custom_account_output  = do_blocks( $custom_account );
$invalid_search_output  = do_blocks( $invalid_search );
$invalid_account_output = do_blocks( $invalid_account );

directorist_modal_blocks_assert( false !== strpos( $legacy_search_output, 'aria-label="Search listings"' ), 'Legacy Search Modal did not receive its accessible default.' );
directorist_modal_blocks_assert( false !== strpos( $legacy_account_output, 'Account button' ), 'Legacy Account Button text was not migrated from saved markup.' );
directorist_modal_blocks_assert( false !== strpos( $empty_account_output, 'user-solid.svg' ), 'An empty legacy Account Button did not receive a visible native icon.' );
directorist_modal_blocks_assert( false !== strpos( $empty_account_output, 'directorist-modal-trigger--icon-only' ), 'An empty legacy Account Button did not migrate to the icon-only layout.' );
directorist_modal_blocks_assert( false !== strpos( $empty_search_output, 'directorist-modal-trigger--icon-only' ), 'A default Search Modal did not use the icon-only layout.' );
directorist_modal_blocks_assert( false === strpos( $empty_search_output, 'directorist-modal-trigger__text' ), 'A default Search Modal rendered extra trigger text.' );
directorist_modal_blocks_assert( false === strpos( $empty_account_output, 'directorist-modal-trigger__text' ), 'A default Account Button rendered extra trigger text.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, 'Find providers' ), 'Custom Search Modal text was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, 'directorist-modal-trigger--reverse' ), 'Search Modal icon position was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, '--directorist-modal-icon-size:36px' ), 'Search Modal icon size was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, '--directorist-modal-icon-color:#ff0000' ), 'Search Modal icon color was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_search_output, 'filter-solid.svg' ), 'Search Modal custom Directorist icon was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_account_output, 'Sign in now' ), 'Account Button accessible label was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $custom_account_output, '--directorist-modal-icon-size:34px' ), 'Logged-out Account Button icon size was not rendered.' );
directorist_modal_blocks_assert( false !== strpos( $invalid_search_output, 'font-awesome/svgs/solid/search.svg' ), 'An unsupported Search Modal icon did not fall back to the default icon.' );
directorist_modal_blocks_assert( false !== strpos( $invalid_account_output, 'line-awesome/svgs/user-solid.svg' ), 'An unsupported Account Button icon did not fall back to the default icon.' );
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

    $logged_in_avatar  = '<!-- wp:directorist/account-button {"avatarSize":58} --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button"></button></div><!-- /wp:directorist/account-button -->';
    $logged_in_account = '<!-- wp:directorist/account-button {"loggedInDisplay":"icon_and_name","authorIconClass":"las la-user-circle","authorIconSize":52,"showDashboardMenu":true} --><div class="wp-block-directorist-account-button"><button type="button" class="wp-block-button__link wp-element-button">Account</button></div><!-- /wp:directorist/account-button -->';
    $avatar_output     = do_blocks( $logged_in_avatar );
    $logged_in_output  = do_blocks( $logged_in_account );

    directorist_modal_blocks_assert( false !== strpos( $avatar_output, 'directorist-modal-trigger--icon-only' ), 'Logged-in avatar-only trigger did not receive its compact layout.' );
    directorist_modal_blocks_assert( false !== strpos( $avatar_output, 'width="58"' ), 'Logged-in avatar size was not rendered.' );
    directorist_modal_blocks_assert( false !== strpos( $logged_in_output, wp_get_current_user()->display_name ), 'Logged-in display name was not rendered.' );
    directorist_modal_blocks_assert( false !== strpos( $logged_in_output, '--directorist-modal-icon-size:52px' ), 'Logged-in author icon size was not rendered.' );
    directorist_modal_blocks_assert( false !== strpos( $logged_in_output, 'directorist-account-block-logged-mode__navigation' ), 'Logged-in dashboard menu was not rendered.' );
    directorist_modal_blocks_assert( false !== strpos( $logged_in_output, 'aria-expanded="false"' ), 'Logged-in trigger state was not rendered.' );

    wp_set_current_user( 0 );
}

WP_CLI::success( 'Directorist modal block smoke tests passed.' );
