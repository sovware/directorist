<?php
/**
 * Account modal block renderer.
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

$directorist_account_block_instance = directorist_modal_block_instance( 'account-button' );
$account_modal_id                   = 'directorist-account-block-login-modal';
$account_navigation_id              = 'directorist-account-navigation-' . $directorist_account_block_instance;
$render_account_modal               = 1 === $directorist_account_block_instance;
$label                              = ! empty( $attributes['accessibleLabel'] ) ? $attributes['accessibleLabel'] : __( 'Open account', 'directorist' );

if ( is_user_logged_in() ) :
    $display       = isset( $attributes['loggedInDisplay'] ) ? $attributes['loggedInDisplay'] : 'avatar';
    $show_name     = in_array( $display, [ 'avatar_and_name', 'icon_and_name' ], true );
    $show_icon     = in_array( $display, [ 'icon', 'icon_and_name' ], true );
    $show_menu     = ! empty( $attributes['showDashboardMenu'] );
    $avatar_size   = isset( $attributes['avatarSize'] ) ? min( 120, max( 20, absint( $attributes['avatarSize'] ) ) ) : 40;
    $avatar_radius = isset( $attributes['avatarRadius'] ) ? min( 50, absint( $attributes['avatarRadius'] ) ) : 50;
    $trigger_attrs = directorist_modal_block_wrapper_attributes(
        $attributes,
        'directorist-account-block__trigger directorist-modal-trigger wp-block-button__link' . ( $show_name ? '' : ' directorist-modal-trigger--icon-only' ),
        $show_icon ? 'author' : ''
    );
    ?>
    <div class="directorist-account-block-logged-mode">
        <?php if ( $show_menu ) : ?>
            <button
                type="button"
                <?php echo wp_kses_data( $trigger_attrs ); ?>
                aria-label="<?php echo esc_attr( $label ); ?>"
                aria-controls="<?php echo esc_attr( $account_navigation_id ); ?>"
                aria-expanded="false"
            >
        <?php else : ?>
            <span <?php echo wp_kses_data( $trigger_attrs ); ?>>
        <?php endif; ?>

            <?php if ( $show_icon ) : ?>
                <?php echo wp_kses_post( directorist_modal_block_icon( $attributes, 'author', 'las la-user-circle' ) ); ?>
            <?php elseif ( 'image' === ( isset( $attributes['avatarSource'] ) ? $attributes['avatarSource'] : 'user' ) && ! empty( $attributes['avatarUrl'] ) ) : ?>
                <img
                    class="avatar directorist-account-block__custom-avatar"
                    width="<?php echo esc_attr( $avatar_size ); ?>"
                    height="<?php echo esc_attr( $avatar_size ); ?>"
                    src="<?php echo esc_url( $attributes['avatarUrl'] ); ?>"
                    alt="<?php echo esc_attr( $attributes['avatarAlt'] ); ?>"
                    style="border-radius:<?php echo esc_attr( $avatar_radius ); ?>%"
                />
            <?php else : ?>
                <?php directorist_account_block_avatar_image( $avatar_size, $avatar_radius ); ?>
            <?php endif; ?>

            <?php if ( $show_name ) : ?>
                <span class="directorist-account-block__display-name">
                    <?php echo esc_html( wp_get_current_user()->display_name ); ?>
                </span>
            <?php endif; ?>

        <?php if ( $show_menu ) : ?>
            </button>
            <?php include DIRECTORIST_BLOCK_TEMPLATE_PATH . '/navigation.php'; ?>
        <?php else : ?>
            </span>
        <?php endif; ?>
    </div>
<?php else :
    $text          = directorist_modal_block_trigger_text( $attributes, $content );
    $display       = ! empty( $attributes['styleDisplay'] ) ? $attributes['styleDisplay'] : ( '' !== trim( wp_strip_all_tags( $text ) ) ? 'text' : 'icon' );
    $show_icon     = 'text' !== $display;
    $show_text     = 'icon' !== $display;
    $trigger_attrs = directorist_modal_block_wrapper_attributes(
        $attributes,
        'directorist-account-block__trigger directorist-modal-trigger wp-block-button__link' . ( 'icon' === $display ? ' directorist-modal-trigger--icon-only' : '' )
    );
    ?>
    <div class="directorist-account-block-logout-mode<?php echo $show_icon ? ' directorist-account-block-logout-mode--has-native-icon' : ''; ?>">
        <button
            type="button"
            <?php echo wp_kses_data( $trigger_attrs ); ?>
            aria-label="<?php echo esc_attr( $label ); ?>"
            aria-controls="<?php echo esc_attr( $account_modal_id ); ?>"
            aria-expanded="false"
        >
            <?php if ( $show_icon ) : ?>
                <?php echo wp_kses_post( directorist_modal_block_icon( $attributes, '', 'las la-user' ) ); ?>
            <?php endif; ?>

            <?php if ( $show_text ) : ?>
                <span class="directorist-modal-trigger__text"><?php echo wp_kses_post( $text ); ?></span>
            <?php endif; ?>
        </button>

        <?php if ( $render_account_modal ) : ?>
            <?php include DIRECTORIST_BLOCK_TEMPLATE_PATH . '/account.php'; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
