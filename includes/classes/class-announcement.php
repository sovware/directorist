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
            $announcements = new WP_Query([
                'post_type'      => 'listing-announcement',
                'posts_per_page' => -1,
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key'     => '_exp_date',
                        'value'   => date('Y-m-d'),
                        'compare' => '>'
                    ],
                    [
                        'key'     => '_closed',
                        'value'   => '1',
                        'compare' => '!='
                    ],
                    [
                        'key'     => '_seen',
                        'value'   => '1',
                        'compare' => '!='
                    ],
                ]
            ]);

            $new_announcements     = count( $announcements->posts );
            $has_new_announcements = ! empty( $new_announcements ) ? true : false;
            $nav_label             = ( $has_new_announcements ) ? "Announcements ({$new_announcements})" : 'Announcements';
            $nav_link_class        = ( $has_new_announcements ) ? " --has-new" : '';

            ob_start(); ?>
            <li class="atbdp_tab_nav--content-link<?php echo $nav_link_class; ?>">
                <a href="" class="atbd_tn_link" target="announcement">
                    <?php _e( $nav_label, 'directorist'); ?>
                </a>
            </li>
            <?php
            echo ob_get_clean();
        }

        public function add_dashboard_nav_content() {
            $announcements = new WP_Query([
                'post_type'      => 'listing-announcement',
                'posts_per_page' => 10,
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key'     => '_exp_date',
                        'value'   => date('Y-m-d'),
                        'compare' => '>'
                    ],
                    [
                        'key'     => '_closed',
                        'value'   => '1',
                        'compare' => '!='
                    ]
                ]
            ]);

            // _exp_date
            ob_start(); ?>
            <div class="atbd_tab_inner" id="announcement">
                <div class="atbd_announcement_wrapper">
                    <?php if ( $announcements->have_posts() ) : ?>
                    <div id="announcement-accordion" class="atbdp-accordion">
                        <?php while( $announcements->have_posts() ) : $announcements->the_post(); update_post_meta( get_the_ID(), '_seen', true ); ?>
                        <div class="atbdp-card">
                            <div class="atbdp-card-header">
                                <div class="atbdp-card-header-title-area">
                                    <h5 class="atbdp-card-header-title">
                                        <?php the_title(); ?>
                                    </h5>
                                </div>

                                <div class="atbdp-card-header-info-area">
                                    <div class="atbdp-date-card">
                                        <span class="atbdp-date-card-part-1"><?php echo get_the_date( 'd M' ) ?></span>
                                        <span class="atbdp-date-card-part-2"><?php echo get_the_date( 'Y' ) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="atbdp-card-body">
                                <?php the_content(); ?>
                            </div>

                            <div class="atbdp-card-footer">
                                <button class="button gray reject buttons-to-right__reject">
                                    <?php _e('Close', 'directorist'); ?>
                                </button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                        <p><?php _e( 'No announcement found', 'directorist' ) ?></p>
                    <?php endif; ?>
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
                $today = date("Y-m-d");
                $exp_date = date('Y-m-d', strtotime( $today. " + {$expiration} days" ) );

                update_post_meta( $announcement, '_exp_in_days', $expiration ); //
                update_post_meta( $announcement, '_exp_date', $exp_date );
                update_post_meta( $announcement, '_closed', false );
                update_post_meta( $announcement, '_seen', false );
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