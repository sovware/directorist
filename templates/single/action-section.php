<?php
/**
 * Action Section – renders phone, email and button widgets as linked action buttons.
 *
 * @author  wpWax
 * @since   8.7
 * @version 8.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$listing_id   = $listing->id;
$action_data  = [];

// Collect action data and check if any action has a value.
foreach ( $actions as $action ) {
    $widget = $action['widget_name'] ?? '';

    switch ( $widget ) {
        case 'phone':
            $phone = get_post_meta( $listing_id, '_phone', true );
            if ( $phone ) {
                $action_data[] = [
                    'type'   => 'phone',
                    'action' => $action,
                    'value'  => $phone,
                ];
            }
            break;

        case 'email':
            $email = get_post_meta( $listing_id, '_email', true );
            if ( $email ) {
                $action_data[] = [
                    'type'   => 'email',
                    'action' => $action,
                    'value'  => $email,
                ];
            }
            break;

        case 'button':
            $field_key = ! empty( $action['field_key'] ) ? sanitize_key( $action['field_key'] ) : 'custom-button';
            $btn_raw   = get_post_meta( $listing_id, '_' . $field_key, true );
            $btn_value = is_array( $btn_raw ) ? $btn_raw : maybe_unserialize( $btn_raw );
            $btn_text  = $btn_value['button_text'] ?? '';
            $btn_url   = $btn_value['button_url_label'] ?? '';

            if ( $btn_text && $btn_url ) {
                $action_data[] = [
                    'type'      => 'button',
                    'action'    => $action,
                    'text'      => $btn_text,
                    'url'       => $btn_url,
                    'field_key' => $field_key,
                ];
            }
            break;
    }
}

if ( empty( $action_data ) ) {
    return;
}
?>

<div class="directorist-listing-actions">
    <?php
    foreach ( $action_data as $data ) :
        $action = $data['action'];
        $widget = $action['widget_name'] ?? '';

        switch ( $widget ) :

            // ── Phone / WhatsApp ─────────────────────
            case 'phone':
                $phone      = $data['value'];
                $is_whatsapp = $listing->has_whatsapp( $action );
                $phone_args = [
                    'number'   => $phone,
                    'whatsapp' => $is_whatsapp,
                ];
                $phone_link = Helper::phone_link( $phone_args );
                $phone_icon = ! empty( $action['icon'] ) ? $action['icon'] : 'las la-phone';

                if ( $is_whatsapp ) :
                    $phone_label = ! empty( $action['form_data']['label'] )
                        ? $action['form_data']['label']
                        : ( ! empty( $action['label'] ) ? $action['label'] : '' );
                    ?>
                    <a class="directorist-btn directorist-btn-sm directorist-btn-default"
                       href="<?php echo esc_url( $phone_link ); ?>">
                        <?php directorist_icon( $phone_icon ); ?>
                        <?php echo esc_html( $phone_label ); ?>
                    </a>
                <?php else :
                    $phone_label = ! empty( $action['form_data']['label'] )
                        ? $action['form_data']['label']
                        : ( ! empty( $action['label'] ) ? $action['label'] : '' );
                    ?>
                    <a class="directorist-btn directorist-btn-sm directorist-btn-default"
                       href="<?php echo esc_url( $phone_link ); ?>">
                        <?php directorist_icon( $phone_icon ); ?>
                        <?php echo esc_html( $phone_label ); ?>
                    </a>
                <?php endif;
                break;

            // ── Email ────────────────────────────────
            case 'email':
                $email       = $data['value'];
                $email_icon  = ! empty( $action['icon'] ) ? $action['icon'] : 'las la-envelope';
                $email_label = ! empty( $action['form_data']['label'] ) ? $action['form_data']['label'] : __( 'Send Email', 'directorist' );
                ?>
                <a class="directorist-btn directorist-btn-sm directorist-btn-default"
                   href="mailto:<?php echo esc_attr( $email ); ?>">
                    <?php directorist_icon( $email_icon ); ?>
                    <?php echo esc_html( $email_label ); ?>
                </a>
                <?php
                break;

            // ── Custom Button ────────────────────────
            case 'button':
                $btn_text = $data['text'];
                $btn_url  = $data['url'];

                $button_style = ! empty( $action['form_data']['button_style'] ) ? $action['form_data']['button_style'] : 'default';
                $open_new_tab = ! empty( $action['form_data']['open_in_new_tab'] );

                $btn_class = 'directorist-btn directorist-btn-sm';
                if ( 'primary' === $button_style ) {
                    $btn_class .= ' directorist-btn-primary';
                } elseif ( 'secondary' === $button_style ) {
                    $btn_class .= ' directorist-btn-secondary';
                } else {
                    $btn_class .= ' directorist-btn-default';
                }

                $button_icon  = ! empty( $action['icon'] ) ? $action['icon'] : 'las la-link';
                $button_label = $btn_text;

                $target_attr = $open_new_tab ? ' target="_blank" rel="noopener"' : '';
                ?>
                <a class="<?php echo esc_attr( $btn_class ); ?>"
                   href="<?php echo esc_url( $btn_url ); ?>"<?php echo esc_attr( $target_attr ); ?>>
                    <?php directorist_icon( $button_icon ); ?>
                    <?php echo esc_html( $button_label ); ?>
                </a>
                <?php
                break;

        endswitch;
    endforeach;
    ?>
</div>