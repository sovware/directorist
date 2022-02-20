<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-add-listing-wrapper directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">

		<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" id="directorist-add-listing-form">

        <?php do_action('directorist_before_add_listing_from_frontend');?>

            <div class="directorist-add-listing-form">

                <input type="hidden" name="add_listing_form" value="1">
                <input type="hidden" name="listing_id" value="<?php echo !empty($p_id) ? esc_attr($p_id) : ''; ?>">

                <?php
                ATBDP()->listing->add_listing->show_nonce_field();
                if ( !empty( $is_edit_mode ) || !empty( $single_directory )) {
                    $listing_form->type_hidden_field();
                }
                if(empty($listing_form->current_listing_type)) {
                    $value             		= get_post_meta( $listing_form->get_add_listing_id(), '_directory_type', true );
                    $current_directory_type = $listing_form->get_current_listing_type();
                    $default_directory 		= default_directory_type();
                    $directory_type         = ! empty( $current_directory_type ) ? $current_directory_type : $default_directory;
                    $current_type      		= ! empty( $value ) ? $value : $directory_type;
                    $listing_form->current_listing_type = $current_type;
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