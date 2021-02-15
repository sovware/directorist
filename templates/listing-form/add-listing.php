<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="directorist" class="directorist atbd_wrapper atbd_add_listing_wrapper">
	<div class="directorist-container-fluid">

		<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" id="add-listing-form">
            <div class="atbdp-form-fields">
                <input type="hidden" name="add_listing_form" value="1">
                <input type="hidden" name="listing_id" value="<?php echo !empty($p_id) ? esc_attr($p_id) : ''; ?>">
                <?php
                ATBDP()->listing->add_listing->show_nonce_field();
                if ( !empty( $is_edit_mode ) || !empty( $single_directory )) {
                    $listing_form->type_hidden_field();
                }

                foreach ( $form_data as $section ) {
                    $listing_form->section_template( $section );
                }

                $listing_form->submit_template();
                ?>
            </div>
        </form>
        
    </div>
</div>

<?php Helper::get_template( 'listing-form/quick-login' ); ?>