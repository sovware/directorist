<?php
if ( ! class_exists( 'ATBDP_Announcement' ) ) :
    class ATBDP_Announcement {

        public function __construct()  {
            add_action( 'wp_ajax_atbdp_send_announcement', [ $this, 'send_announcement' ] );
            add_action( 'init', [ $this, 'create_announcement_post_type' ] );
            add_action( 'atbdp_tab_after_favorite_listings', [ $this, 'add_dashboard_nav_link' ] );
            add_action( 'atbdp_tab_content_after_favorite', [ $this, 'add_dashboard_nav_content' ] );
        }

        // add_dashboard_nav_link
        public function add_dashboard_nav_link() {
            ob_start(); ?>
            <li class="atbdp_tab_nav--content-link">
                <a href="" class="atbd_tn_link" target="announcement"><?php _e('Announcements', 'directorist'); ?></a>
            </li>
            <?php
            echo ob_get_clean();
        }

        public function add_dashboard_nav_content() {
            ob_start(); ?>
            <div class="atbd_tab_inner" id="announcement">
                <div class="atbd_announcement_wrapper">
                    <div id="announcement-accordion" class="atbdp-accordion">
                        <div class="atbdp-card">
                            <div class="atbdp-card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link atbdp-toggle-tab" data-target="#collapseOne">
                                        Collapsible Group Item #1
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="atbdp-collapse">
                                <div class="atbdp-card-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }

        // send_announcement
        public function send_announcement() {
            $to            = ( isset( $_POST['to'] ) ) ? $_POST['to'] : '';
            $recepents     = ( isset( $_POST['recepents'] ) ) ? $_POST['recepents'] : '';
            $subject       = ( isset( $_POST['subject'] ) ) ? $_POST['subject'] : '';
            $message       = ( isset( $_POST['message'] ) ) ? $_POST['message'] : '';
            $expiration    = ( isset( $_POST['expiration'] ) ) ? $_POST['expiration'] : '';
            $send_to_email = ( isset( $_POST['send_to_email'] ) ) ? $_POST['send_to_email'] : '';

            $status = [ 
                'success' => false, 
                'message' => __( 'Sorry, something went wrong, please try again' )
            ];

            // Get Recepents
            if ( 'selected_user' === $to ) {
                $recepents = ( 'string' === gettype( $recepents ) ) ? explode(',', $recepents ) : null;
            }

            if ( 'all_user' === $to ) {
                $users = get_users([ 'role__not_in' => 'Administrator' ]); // Administrator | Subscriber
                $recepents = [];

                if ( ! empty( $users ) ) {
                    foreach ( $users as $user ) {
                        $recepents[] = $user->user_email;
                    }
                }
            }

            // Validate recepents
            if ( empty( $recepents ) ) {
                $status['message'] = __( 'No recepents found' );
                wp_send_json( $status );
            }

            // Validate Subject
            if ( empty( $subject ) ) {
                $status['message'] = __( 'The subject cant be empty' );
                wp_send_json( $status );
            }

            // Save the post
            $announcement = wp_insert_post([
                'post_type'    => 'listing-announcement',
                'post_title'   => $subject,
                'post_content' => $message,
                'post_status'  => 'publish',
            ]);

            if ( is_wp_error( $announcement ) ) {
                $status['message'] = __( 'Sorry, something went wrong, please try again' );
                wp_send_json( $status );
            }

            // Update the post meta
            if ( is_numeric( $expiration  ) ) {
                update_post_meta( $announcement, '_expiration_in_days', $expiration );
            }
            
            // Send email if enabled
            if ( '1' == $send_to_email ) {
                wp_mail( $recepents, $subject, $message );
            }

            $status['status']  = true;
            $status['message'] = __( 'The announcement has been sent successfully' );

            wp_send_json( $status );
        }

        // create_announcement_post_type
        public function create_announcement_post_type() {
            register_post_type( 'listing-announcement', [
                'label'  => 'Announcement',
                'labels' => 'Announcements',
                'public' => false,
            ]);
        }
    }
endif;