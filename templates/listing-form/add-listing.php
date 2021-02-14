<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

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

<div id="atbdp-quick-login" class="atbdp-modal-container">
    <div class="atbdp-modal-wrap">
        <div class="atbdp-modal">
            <div class="atbdp-modal-header">
                <div class="atbdp-modal-title-area">
                    <h3 class="atbdp-modal-title"><?php _e( 'Quick Login', 'directorist' ) ?></h3>
                </div>

                <div class="atbdp-modal-actions-area">
                    <a href="#" class="atbdp-toggle-modal" data-target="#atbdp-quick-login"><span class="fas fa-times"></span></a>
                </div>
            </div>

            <div class="atbdp-modal-body">
                <div class="atbdp-modal-alerts-area"></div>

                <p class="atbdp-form-label atbdp-email-label">user@email.com</p>

                <div class="atbdp-form-group">
                    <input type="password" name="password" placeholder="<?php echo 'Password'; ?>" class="atbdp-form-control">
                </div>

                <button type="button" name="login" class="atbdp-btn atbdp-btn-primary atbdp-btn-block">
                    <?php echo 'Login'; ?>
                </button>
            </div>
        </div>
    </div>
</div>