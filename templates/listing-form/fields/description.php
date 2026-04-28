<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$maxlength = $data['max'] ?? '';

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
?>

<div class="directorist-form-group directorist-form-description-field"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php
    $listing_form->field_label_template( $data );

    if ( 'textarea' === $data['type'] ) {
        ?>
        <textarea name="<?php echo esc_attr( $data['field_key'] ); ?>" id="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" rows="8" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" maxlength="<?php echo esc_attr( $maxlength ); ?>" <?php $listing_form->required( $data ); ?>><?php echo esc_html( $data['value'] ); ?></textarea>
        <?php
    } else {
        wp_editor(
            wp_kses_post( $data['value'] ),
            $data['field_key'],
            apply_filters(
                'atbdp_add_listing_wp_editor_settings',
                [
                    'media_buttons' => true,
                    'quicktags'     => true,
                    'editor_height' => 200,
                    'tinymce'       => array(
                        'plugins'    => 'lists,link,wordpress,paste,textcolor,fullscreen,hr',
                    ),
                ]
            )
        );
    }

    $listing_form->field_description_template( $data );
    ?>

    <div id="directorist_listing_description_indicator"></div>

</div>