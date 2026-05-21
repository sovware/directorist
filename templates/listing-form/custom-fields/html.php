<?php
/**
 * @author  wpWax
 * @since   8.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
$field_key = (string) $data['field_key'];
$editor_id = strtolower( preg_replace( '/[^a-z0-9_]/', '_', str_replace( '-', '_', $field_key ) ) );
$editor_id = ( $editor_id ? 'directorist_html_' . $editor_id : 'directorist_html_field' ) . '_' . substr( md5( $field_key ), 0, 8 );
$is_required = ! empty( $data['required'] );
$placeholder = isset( $data['placeholder'] ) ? (string) $data['placeholder'] : '';
$field_object = \Directorist\Fields\Fields::create( $data );
$allowed_html = \Directorist\Fields\HTML_Field::allowed_html( $field_object );
?>

<div class="directorist-form-group directorist-custom-field-html directorist-form-description-field"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php
    $listing_form->field_label_template( $data );

    wp_enqueue_media();

    $editor_settings = apply_filters(
        'atbdp_add_listing_wp_editor_settings',
        [
            'textarea_name' => $field_key,
            'media_buttons' => true,
            'quicktags'     => true,
            'editor_height' => 200,
            'textarea_rows' => 8,
            'tinymce'       => [
                'plugins' => 'lists,link,wordpress,paste,textcolor,fullscreen,hr',
            ],
        ]
    );
    $editor_settings['media_buttons'] = true;
    $editor_settings['tinymce'] = isset( $editor_settings['tinymce'] ) && is_array( $editor_settings['tinymce'] ) ? $editor_settings['tinymce'] : [];
    $editor_settings['tinymce']['content_style'] = trim( ( $editor_settings['tinymce']['content_style'] ?? '' ) . ' body { padding: 0 !important; box-sizing: border-box; }' );

    $editor_value = wp_kses( (string) $data['value'], $allowed_html );
    wp_editor( $editor_value, $editor_id, $editor_settings );

    if ( $placeholder || $is_required ) :
        ?>
        <script>
            (function() {
                var editorId = <?php echo wp_json_encode( $editor_id ); ?>;
                var editorTextarea = document.getElementById(editorId);
                if (!editorTextarea) {
                    return;
                }
                var fieldWrapper = editorTextarea.closest('.directorist-form-group');
                var placeholder = <?php echo wp_json_encode( $placeholder ); ?>;
                var isRequired = <?php echo wp_json_encode( $is_required ); ?>;

                if (placeholder) {
                    editorTextarea.setAttribute('placeholder', placeholder);
                }

                if (!isRequired) {
                    return;
                }

                var validateEditor = function() {
                    var editor = window.tinymce ? window.tinymce.get(editorId) : null;
                    var content = editor ? editor.getContent({ format: 'text' }) : editorTextarea.value;
                    var isHidden = fieldWrapper && window.getComputedStyle(fieldWrapper).display === 'none';

                    content = (content || '').replace(/\u00a0/g, ' ').trim();

                    editorTextarea.setCustomValidity('');

                    if (isHidden || content) {
                        return true;
                    }

                    editorTextarea.setCustomValidity('This field is required.');
                    return false;
                };

                editorTextarea.setAttribute('aria-required', 'true');

                if (editorTextarea.form && !editorTextarea.dataset.directoristHtmlValidationBound) {
                    editorTextarea.form.addEventListener('submit', function(event) {
                        if (validateEditor()) {
                            return;
                        }

                        event.preventDefault();

                        var editor = window.tinymce ? window.tinymce.get(editorId) : null;
                        if (editor) {
                            editor.focus();
                        } else {
                            editorTextarea.focus();
                        }

                        editorTextarea.reportValidity();
                    });

                    editorTextarea.dataset.directoristHtmlValidationBound = 'true';
                }
            })();
        </script>
        <?php
    endif;

    $listing_form->field_description_template( $data );
    ?>

</div>
