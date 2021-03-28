<?php

if ( ! class_exists( 'ATBDP_Announcement' ) ) :
    class ATBDP_Announcement {

        public function __construct()  {
            // Cteate announcement post type
            add_action( 'init', [ $this, 'create_announcement_post_type' ] );

            // Legacy template
           // add_action( 'atbdp_tab_after_favorite_listings', [ $this, 'add_dashboard_nav_link' ] );
           // add_action( 'atbdp_tab_content_after_favorite', [ $this, 'add_dashboard_nav_content' ] );

            // Non legacy template
           // add_action( 'directorist_tab_after_favorite_listings', [ $this, 'non_legacy_add_dashboard_nav_link' ] );
            //add_action( 'directorist_tab_content_after_favorite', [ $this, 'non_legacy_add_dashboard_nav_content' ] );

            add_action( 'atbdp_schedule_task', [ $this, 'delete_expaired_announcements' ] );

            // Handle ajax
            add_action( 'wp_ajax_atbdp_send_announcement', [ $this, 'send_announcement' ] );
            add_action( 'wp_ajax_atbdp_close_announcement', [ $this, 'close_announcement' ] );
            add_action( 'wp_ajax_atbdp_get_new_announcement_count', [ $this, 'response_new_announcement_count' ] );
            add_action( 'wp_ajax_atbdp_clear_seen_announcements', [ $this, 'clear_seen_announcements' ] );


        }

        public function non_legacy_add_dashboard_nav_link() {
            $announcement_tab       = get_directorist_option( 'announcement_tab', 'directorist' );
            $announcement_tab_text  = get_directorist_option( 'announcement_tab_text', __( 'Announcements', 'directorist' ) );
            if( empty( $announcement_tab ) ) return;
            $nav_label = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count'></span>";
            $new_announcements = $this->get_new_announcement_count();

            if ( $new_announcements > 0 ) {
                $nav_label = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count show'>{$new_announcements}</span>";
            }

            ob_start(); ?>
            <li class="directorist-tab__nav__item">
                <a href="#" class="directorist-booking-nav-link directorist-tab__nav__link" target="announcement">
                    <span class="directorist_menuItem-text">
						<span class="directorist_menuItem-icon"><i class="la la-bullhorn"></i></span><?php _e( $nav_label, 'directorist' ); ?>
					</span>
                </a>
            </li>
            <?php
            echo ob_get_clean();
        }

        // get_announcement_querys
        public static function get_announcement_query_data() {
            $announcements = new WP_Query([
                'post_type'      => 'listing-announcement',
                'posts_per_page' => 20,
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


            return $announcements;
        }

        public function non_legacy_add_dashboard_nav_content() {
            $announcements = self::get_announcement_query_data();

            // directorist_console_log([
            //     'announcements' => $announcements->posts,
            //     'post_type_exists' => post_type_exists( 'listing-announcement' ),
            // ]);

            $total_posts = count( $announcements->posts );
            $skipped_post_count = 0;
            $current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

            ob_start(); ?>
            <div class="directorist-tab__pane" id="announcement">
                <div class="atbd_announcement_wrapper">
                    <?php if ( $announcements->have_posts() ) : ?>
                    <div class="atbdp-accordion">
                        <?php while( $announcements->have_posts() ) :
                            $announcements->the_post();

                            // Check recepent restriction
                            $recepents = get_post_meta( get_the_ID(), '_recepents', true );
                            if ( ! empty( $recepents ) && is_array( $recepents )  ) {
                                if ( ! in_array( $current_user_email, $recepents ) ) {
                                    $skipped_post_count++;
                                    continue;
                                }
                            }
                        ?>
                        <div class="atbdp-announcement <?php echo 'update-announcement-status announcement-item announcement-id-' . get_the_ID() ?>" data-post-id="<?php the_id() ?>">
                            <div class="atbdp-announcement__date">
                                <span class="atbdp-date-card-part-1"><?php echo get_the_date( 'd' ) ?></span>
                                <span class="atbdp-date-card-part-2"><?php echo get_the_date( 'M' ) ?></span>
                                <span class="atbdp-date-card-part-3"><?php echo get_the_date( 'Y' ) ?></span>
                            </div>
                            <div class="atbdp-announcement__content">
                                <h3 class="atbdp-announcement__title">
                                    <?php the_title(); ?>
                                </h3>
                                <p><?php the_content(); ?></p>
                            </div>
                            <div class="atbdp-announcement__close">
                                <button class="close-announcement" data-post-id="<?php the_id() ?>">
                                    <?php _e('<i class="la la-times"></i>', 'directorist'); ?>
                                </button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                        <div class="directorist_not-found"><p><?php _e( 'No announcement found', 'directorist' ) ?></p></div>
                    <?php endif;

                    if ( $total_posts && $skipped_post_count == $total_posts ) {
                        _e( 'No announcement found', 'directorist' );
                    }
                    ?>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }

        // response_new_announcement_count
        public function response_new_announcement_count() {
            $new_announcements = $this->get_new_announcement_count();
            wp_send_json( [ 'success' => true, 'total_new_announcement' => $new_announcements ] );
        }

        // clear_seen_announcements
        public function clear_seen_announcements() {
            $new_announcements = new WP_Query([
                'post_type'      => 'listing-announcement',
                'posts_per_page' => -1,
                'meta_query' => [
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

            $current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

            if ( $new_announcements->have_posts() ) {
                while( $new_announcements->have_posts() ) {
                    $new_announcements->the_post();
                    // Check recepent restriction
                    $recepents = get_post_meta( get_the_ID(), '_recepents', true );
                    if ( ! empty( $recepents ) && is_array( $recepents )  ) {
                        if ( ! in_array( $current_user_email, $recepents ) ) {
                            continue;
                        }
                    }

                    update_post_meta( get_the_ID(), '_seen', true );
                }
            }

            wp_send_json([ 'success' => true ]);
        }

        // get_new_announcement_count
        public function get_new_announcement_count() {
            $new_announcements = new WP_Query([
                'post_type'      => 'listing-announcement',
                'posts_per_page' => -1,
                'meta_query' => [
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

            $total_posts        = count( $new_announcements->posts );
            $skipped_post_count = 0;
            $current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

            if ( $new_announcements->have_posts() ) {
                while( $new_announcements->have_posts() ) {
                    $new_announcements->the_post();
                    // Check recepent restriction
                    $recepents = get_post_meta( get_the_ID(), '_recepents', true );
                    if ( ! empty( $recepents ) && is_array( $recepents )  ) {
                        if ( ! in_array( $current_user_email, $recepents ) ) {
                            $skipped_post_count++;
                            continue;
                        }
                    }
                }
            }

            $new_posts = $total_posts - $skipped_post_count;

            return $new_posts;
        }

        // delete_expaired_announcements
        public function delete_expaired_announcements() {
            $expaired_announcements = new WP_Query([
                'post_type'      => 'listing-announcement',
                'posts_per_page' => -1,
                'meta_query' => [
                    [
                        'key'     => '_exp_date',
                        'value'   => date('Y-m-d'),
                        'compare' => '<='
                    ]
                ]
            ]);

            if ( ! $expaired_announcements->have_posts() ) { return; }
            while ( $expaired_announcements->have_posts() ) {
                $expaired_announcements->the_post();
                wp_delete_post( get_the_ID(), true );
            }
        }

        // add_dashboard_nav_link
        public function add_dashboard_nav_link() {
            $announcement_tab       = get_directorist_option( 'announcement_tab', 'directorist' );
            $announcement_tab_text  = get_directorist_option( 'announcement_tab_text', __( 'Announcements', 'directorist' ) );
            if( empty( $announcement_tab ) ) return;
            $nav_label = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count'></span>";
            $new_announcements = $this->get_new_announcement_count();

            if ( $new_announcements > 0 ) {
                $nav_label = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count show'>{$new_announcements}</span>";
            }

            ob_start(); ?>
            <li class="atbdp_tab_nav--content-link">
                <a href="" class="atbdp_all_booking_nav-link atbd-dash-nav-dropdown atbd_tn_link" target="announcement">
                    <span class="directorist_menuItem-text">
						<span class="directorist_menuItem-icon"><i class="la la-bullhorn"></i></span><?php _e( $nav_label, 'directorist' ); ?>
					</span>
                </a>
            </li>
            <?php
            echo ob_get_clean();
        }

        public function add_dashboard_nav_content() {
            $announcements = $announcements = self::get_announcement_query_data();

            $total_posts = count( $announcements->posts );
            $skipped_post_count = 0;
            $current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

            ob_start(); ?>
            <div class="atbd_tab_inner" id="announcement">
                <div class="atbd_announcement_wrapper">
                    <?php if ( $announcements->have_posts() ) : ?>
                    <div class="atbdp-accordion">
                        <?php while( $announcements->have_posts() ) :
                            $announcements->the_post();

                            // Check recepent restriction
                            $recepents = get_post_meta( get_the_ID(), '_recepents', true );
                            if ( ! empty( $recepents ) && is_array( $recepents )  ) {
                                if ( ! in_array( $current_user_email, $recepents ) ) {
                                    $skipped_post_count++;
                                    continue;
                                }
                            }
                        ?>
                        <div class="atbdp-announcement <?php echo 'update-announcement-status announcement-item announcement-id-' . get_the_ID() ?>" data-post-id="<?php the_id() ?>">
                            <div class="atbdp-announcement__date">
                                <span class="atbdp-date-card-part-1"><?php echo get_the_date( 'd' ) ?></span>
                                <span class="atbdp-date-card-part-2"><?php echo get_the_date( 'M' ) ?></span>
                                <span class="atbdp-date-card-part-3"><?php echo get_the_date( 'Y' ) ?></span>
                            </div>
                            <div class="atbdp-announcement__content">
                                <h3 class="atbdp-announcement__title">
                                    <?php the_title(); ?>
                                </h3>
                                <p><?php the_content(); ?></p>
                            </div>
                            <div class="atbdp-announcement__close">
                                <button class="close-announcement" data-post-id="<?php the_id() ?>">
                                    <?php _e('<i class="la la-times"></i>', 'directorist'); ?>
                                </button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                        <div class="directorist_not-found"><p><?php _e( 'No announcement found', 'directorist' ) ?></p></div>
                    <?php endif;

                    if ( $total_posts && $skipped_post_count == $total_posts ) {
                        _e( 'No announcement found', 'directorist' );
                    }
                    ?>
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

            if ( empty( $expiration ) ) {
                $expiration = 365;
            }

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

            // Validate Message
            if ( strlen( $message ) > 400 ) {
                $status['message'] = __( 'Maximum 400 characters are allowed for the message' );
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

            $status['announcement'] = $announcement;

            if ( 'all_user' !== $to ) {
                update_post_meta( $announcement, '_recepents', $recepents );
            } else {
                update_post_meta( $announcement, '_recepents', '' );
            }

            // Update the post meta
            update_post_meta( $announcement, '_to', $to );
            update_post_meta( $announcement, '_closed', false );
            update_post_meta( $announcement, '_seen', false );

            if ( is_numeric( $expiration ) ) {
                $today = date("Y-m-d");
                $exp_date = date('Y-m-d', strtotime( $today. " + {$expiration} days" ) );

                update_post_meta( $announcement, '_exp_in_days', $expiration );
                update_post_meta( $announcement, '_exp_date', $exp_date );
            }

            // Send email if enabled
            if ( '1' == $send_to_email ) {
                wp_mail( $recepents, $subject, $message );
            }

            $status['success']  = true;
            $status['message'] = __( 'The announcement has been sent successfully' );

            wp_send_json( $status );
        }

        // close_announcement
        public function close_announcement() {
            $post_id = ( isset( $_POST['post_id'] ) ) ? $_POST['post_id'] : 0;

            $status = [ 'success' => false ];
            $status['message'] = __( 'Sorry, something went wrong, please try again' );

            // Validate post id
            if ( empty( $post_id ) ) {
                wp_send_json( $status );
            }

            update_post_meta( $post_id, '_closed', true );

            $status['success'] = true;
            $status['message'] = __( 'The announcement has been closed successfully' );

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