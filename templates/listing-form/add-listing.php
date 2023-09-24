<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

$action_url = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ): '';
?>

<div class="directorist-add-listing-wrapper directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
        <form action="<?php echo esc_url( $action_url ); ?>" method="post" id="directorist-add-listing-form">
            <?php do_action('directorist_before_add_listing_from_frontend');?>
            <div class="directorist-add-listing-form">
                <input type="hidden" name="add_listing_form" value="1">
                <input type="hidden" name="listing_id" value="<?php echo !empty($p_id) ? esc_attr($p_id) : ''; ?>">
                <!-- MultiStep Wizard Start -->
                <div class="multistep-wizard default-add-listing"> 

                    <?php if( ! empty( $enable_sidebar ) ) : ?>

                        <div class="multistep-wizard__nav">
                            <?php 
                                $counter = 1;
                                foreach ( $form_data as $key => $section ) {   
                                    $listing_type = isset( $section['fields']['listing_type'] ) ? $section['fields']['listing_type']['widget_name'] : '';

                                    if ( empty( $listing_type ) ) {
                                        printf( '<div class="multistep-wizard__nav__item"><a href="#add-listing-content-%s" id="add-listing-nav-%s" class="multistep-wizard__nav__btn">%s %s</a></div>', esc_attr( $counter ), esc_attr( $counter ), ( isset( $section['icon'] ) ? directorist_icon( $section['icon'], false ) : directorist_icon( 'fas fa-circle', false ) ), $section['label'] );

                                        $counter++; 
                                    }
                                }
                            ?>
                            <div class="multistep-wizard__nav__item">
                                <a href="#" class="multistep-wizard__nav__btn multistep-wizard__nav__btn--finish  add-listing-nav-999"><?php directorist_icon( 'fas fa-check' ); ?><?php esc_html_e( 'Finish', 'directorist' ); ?></a>
                            </div>
                        </div>
                        
                    <?php endif; ?>

                    <div class="multistep-wizard__content">
                        <div class="multistep-wizard__wrapper">
                            <?php
                                ATBDP()->listing->add_listing->show_nonce_field();
                                if ( !empty( $is_edit_mode ) || !empty( $single_directory )) {
                                    $listing_form->type_hidden_field();
                                }
                                $counter = 1;
                                foreach ( $form_data as $section ) {
                                    echo '<div class="multistep-wizard__single" id="add-listing-content-'. esc_attr( $counter ) . '">';
                                    echo '<div class="multistep-wizard__single" id="add-listing-content-'. esc_attr( $counter ) . '">';
                                        $listing_form->section_template( $section );
                                    echo '</div>';
                                    $counter++; 
                                }
                            ?>
                            <div class="multistep-wizard__single add-listing-content-999">
                                <?php 
                                    $listing_form->submit_template();
                                ?>
                            </div>
                        </div>

                        <div class="multistep-wizard__progressbar">
                            <span class="multistep-wizard__progressbar__width"></span>
                        </div>

                        <div class="multistep-wizard__bottom">
                            <a class="directorist-btn multistep-wizard__btn multistep-wizard__btn--prev" disabled="true">
                                <?php 
                                    directorist_icon( 'fas fa-arrow-left' );
                                    esc_html_e( 'Back', 'directorist' );
                                ?>
                            </a>

                            <div class="multistep-wizard__count">
                                <span class="multistep-wizard__count__current"></span> / <span class="multistep-wizard__count__total"></span>
                            </div>

                            <a type="submit" class="directorist-btn multistep-wizard__btn multistep-wizard__btn--next">Save & Next<?php directorist_icon( 'fas fa-arrow-right' ); ?></a>

                            <button type="submit" class="directorist-btn directorist-form-submit__btn multistep-wizard__btn multistep-wizard__btn--save-preview">Save & Preview</button>

                            <button type="submit" class="directorist-btn directorist-btn-primary directorist-form-submit__btn multistep-wizard__btn multistep-wizard__btn--skip-preview">Skip preview and submit listing</button>
                        </div>
                        
                        <div class="default-add-listing-bottom">
                            <button type="submit" class="directorist-btn directorist-btn-primary directorist-form-submit__btn">Save & Preview</button>
                        </div>
                    </div>
                </div>
                <!-- MultiStep Wizard End -->
            </div>
        </form>
    </div>
</div>

<?php Helper::get_template( 'listing-form/quick-login' ); ?>