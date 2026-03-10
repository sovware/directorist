<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.6
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

// Get conditional logic attributes using centralized method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );
?>

<div class="directorist-form-group directorist-form-social-info-field"<?php echo $conditional_logic_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in get_conditional_logic_attributes() ?>>

    <?php $listing_form->field_label_template( $data );?>

    <div id="social_info_sortable_container">

        <input type="hidden" id="is_social_checked">

        <?php
        if ( ! empty( $data['value'] ) ) {
            foreach ( $data['value'] as $index => $social_info ) {
                $listing_form->social_item_template( $index, $social_info );
            }
        }
        ?>

    </div>

    <button type="button" class="directorist-btn directorist-btn-light" id="addNewSocial"><?php directorist_icon( 'las la-plus' ); ?><?php esc_html_e( 'Add Social', 'directorist' ); ?></button>

</div>