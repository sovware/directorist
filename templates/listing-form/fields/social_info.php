<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="directorist-form-group directorist-form-social-info-field">

    <?php $listing_form->field_label_template( $data );?>

    <div id="social_info_sortable_container">
        <?php $listing_form->render_social_items( $data ); ?>
    </div>

    <button type="button" class="directorist-btn directorist-btn-light directorist-btn-sm" id="addNewSocial"><?php directorist_icon( 'las la-plus' ); ?><?php esc_html_e( 'Add New', 'directorist' ); ?></button>
</div>
