<?php
/**
 * @author  wpWax
 * @since   8.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );

// wp_editor editor_id must be JS-safe (letters/numbers/underscores) for reliable init in edit mode.
$editor_id = strtolower( (string) $data['field_key'] );
$editor_id = preg_replace( '/[^a-z0-9_]/', '_', str_replace( '-', '_', $editor_id ) );
$editor_id = ! empty( $editor_id ) ? 'directorist_html_' . $editor_id : 'directorist_html_field';
?>

<div class="directorist-form-group directorist-custom-field-html directorist-form-description-field"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php
    $listing_form->field_label_template( $data );

    wp_editor(
        wp_kses_post( $data['value'] ),
        $editor_id,
        apply_filters(
            'atbdp_add_listing_wp_editor_settings',
            [
                'textarea_name' => $data['field_key'],
                'media_buttons' => false,
                'quicktags'     => true,
                'editor_height' => 200,
                'textarea_rows' => 8,
                'tinymce'       => [
                    'plugins' => 'lists,link,wordpress,paste,textcolor,fullscreen,hr',
                ],
            ]
        )
    );

    $listing_form->field_description_template( $data );
    ?>

</div>
