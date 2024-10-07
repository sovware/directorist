<?php

if ( ! class_exists( 'ATBDP_Announcement' ) ) :
	class ATBDP_Announcement {

		public function __construct() {
			// Cteate announcement post type
			add_action( 'init', array( $this, 'create_announcement_post_type' ) );

			// Legacy template
			// add_action( 'atbdp_tab_after_favorite_listings', [ $this, 'add_dashboard_nav_link' ] );
			// add_action( 'atbdp_tab_content_after_favorite', [ $this, 'add_dashboard_nav_content' ] );

			// Non legacy template
			// add_action( 'directorist_tab_after_favorite_listings', [ $this, 'non_legacy_add_dashboard_nav_link' ] );
			// add_action( 'directorist_tab_content_after_favorite', [ $this, 'non_legacy_add_dashboard_nav_content' ] );

			add_action( 'atbdp_schedule_task', array( $this, 'delete_expaired_announcements' ) );

			// Handle ajax
			add_action( 'wp_ajax_atbdp_send_announcement', array( $this, 'send_announcement' ) );
			add_action( 'wp_ajax_atbdp_close_announcement', array( $this, 'close_announcement' ) );
			add_action( 'wp_ajax_atbdp_get_new_announcement_count', array( $this, 'response_new_announcement_count' ) );
			add_action( 'wp_ajax_atbdp_clear_seen_announcements', array( $this, 'clear_seen_announcements' ) );

		}

		public function non_legacy_add_dashboard_nav_link() {
			$announcement_tab      = get_directorist_option( 'announcement_tab', 'directorist' );
			$announcement_tab_text = get_directorist_option( 'announcement_tab_text', __( 'Announcements', 'directorist' ) );
			if ( empty( $announcement_tab ) ) {
				return;
			}
			$nav_label         = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count'></span>";
			$new_announcements = $this->get_new_announcement_count();

			if ( $new_announcements > 0 ) {
				$nav_label = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count show'>{$new_announcements}</span>";
			}

			?>
			<li class="directorist-tab__nav__item">
				<a href="#" class="directorist-booking-nav-link directorist-tab__nav__link" target="announcement">
					<span class="directorist_menuItem-text">
						<span class="directorist_menuItem-icon"><?php directorist_icon( 'las la-bullhorn' ); ?></span><?php echo wp_kses( $nav_label, array( 'span' => array( 'class' => array() ) ) ); ?>
					</span>
				</a>
			</li>
			<?php
		}

		// get_announcement_querys
		public static function get_announcement_query_data() {
			$announcements = new WP_Query(
				array(
					'post_type'      => 'listing-announcement',
					'posts_per_page' => 20,
					'meta_query'     => array(
						'relation' => 'AND',
						array(
							'key'     => '_exp_date',
							'value'   => date( 'Y-m-d' ),
							'compare' => '>',
						),
						array(
							'key'     => '_closed',
							'value'   => '1',
							'compare' => '!=',
						),
					),
				)
			);

			return $announcements;
		}

		public function non_legacy_add_dashboard_nav_content() {
			$announcements = self::get_announcement_query_data();

			// directorist_console_log([
			// 'announcements' => $announcements->posts,
			// 'post_type_exists' => post_type_exists( 'listing-announcement' ),
			// ]);

			$total_posts        = count( $announcements->posts );
			$skipped_post_count = 0;
			$current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

			?>
			<div class="directorist-tab__pane" id="announcement">
				<div class="atbd_announcement_wrapper">
					<?php if ( $announcements->have_posts() ) : ?>
					<div class="atbdp-accordion">
						<?php
						while ( $announcements->have_posts() ) :
							$announcements->the_post();

							// Check recepent restriction
							$recipient = get_post_meta( get_the_ID(), '_recepents', true );
							if ( ! empty( $recipient ) && is_array( $recipient ) ) {
								if ( ! in_array( $current_user_email, $recipient ) ) {
									$skipped_post_count++;
									continue;
								}
							}
							?>
						<div class="atbdp-announcement <?php echo 'update-announcement-status announcement-item announcement-id-' . get_the_ID(); ?>" data-post-id="<?php the_id(); ?>">
							<div class="atbdp-announcement__date">
								<span class="atbdp-date-card-part-1"><?php echo get_the_date( 'd' ); ?></span>
								<span class="atbdp-date-card-part-2"><?php echo get_the_date( 'M' ); ?></span>
								<span class="atbdp-date-card-part-3"><?php echo get_the_date( 'Y' ); ?></span>
							</div>
							<div class="atbdp-announcement__content">
								<h3 class="atbdp-announcement__title">
									<?php the_title(); ?>
								</h3>
								<p><?php the_content(); ?></p>
							</div>
							<div class="atbdp-announcement__close">
								<button class="close-announcement" data-post-id="<?php the_id(); ?>"><i class="la la-times"></i></button>
							</div>
						</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php else : ?>
						<div class="directorist_not-found"><p><?php esc_html_e( 'No announcement found', 'directorist' ); ?></p></div>
						<?php
					endif;

					if ( $total_posts && $skipped_post_count == $total_posts ) {
						esc_html_e( 'No announcement found', 'directorist' );
					}
					?>
				</div>
			</div>
			<?php
		}

		// response_new_announcement_count
		public function response_new_announcement_count() {
			$new_announcements = $this->get_new_announcement_count();
			wp_send_json(
				array(
					'success'                => true,
					'total_new_announcement' => $new_announcements,
				)
			);
		}

		// clear_seen_announcements
		public function clear_seen_announcements() {
			$new_announcements = new WP_Query(
				array(
					'post_type'      => 'listing-announcement',
					'posts_per_page' => -1,
					'meta_query'     => array(
						array(
							'key'     => '_exp_date',
							'value'   => date( 'Y-m-d' ),
							'compare' => '>',
						),
						array(
							'key'     => '_closed',
							'value'   => '1',
							'compare' => '!=',
						),
						array(
							'key'     => '_seen',
							'value'   => '1',
							'compare' => '!=',
						),
					),
				)
			);

			$current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

			if ( $new_announcements->have_posts() ) {
				while ( $new_announcements->have_posts() ) {
					$new_announcements->the_post();
					// Check recepent restriction
					$recipient = get_post_meta( get_the_ID(), '_recepents', true );
					if ( ! empty( $recipient ) && is_array( $recipient ) ) {
						if ( ! in_array( $current_user_email, $recipient ) ) {
							continue;
						}
					}

					update_post_meta( get_the_ID(), '_seen', true );
				}
				wp_reset_postdata();
			}

			wp_send_json( array( 'success' => true ) );
		}

		// get_new_announcement_count
		public function get_new_announcement_count() {
			$new_announcements = new WP_Query(
				array(
					'post_type'      => 'listing-announcement',
					'posts_per_page' => -1,
					'meta_query'     => array(
						array(
							'key'     => '_exp_date',
							'value'   => date( 'Y-m-d' ),
							'compare' => '>',
						),
						array(
							'key'     => '_closed',
							'value'   => '1',
							'compare' => '!=',
						),
						array(
							'key'     => '_seen',
							'value'   => '1',
							'compare' => '!=',
						),
					),
				)
			);

			$total_posts        = count( $new_announcements->posts );
			$skipped_post_count = 0;
			$current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

			if ( $new_announcements->have_posts() ) {
				while ( $new_announcements->have_posts() ) {
					$new_announcements->the_post();
					// Check recepent restriction
					$recipient = get_post_meta( get_the_ID(), '_recepents', true );
					if ( ! empty( $recipient ) && is_array( $recipient ) ) {
						if ( ! in_array( $current_user_email, $recipient ) ) {
							$skipped_post_count++;
							continue;
						}
					}
				}
				wp_reset_postdata();
			}

			$new_posts = $total_posts - $skipped_post_count;

			return $new_posts;
		}

		// delete_expaired_announcements
		public function delete_expaired_announcements() {
			$expaired_announcements = new WP_Query(
				array(
					'post_type'      => 'listing-announcement',
					'posts_per_page' => -1,
					'meta_query'     => array(
						array(
							'key'     => '_exp_date',
							'value'   => date( 'Y-m-d' ),
							'compare' => '<=',
						),
					),
				)
			);

			if ( ! $expaired_announcements->have_posts() ) {
				return; }
			while ( $expaired_announcements->have_posts() ) {
				$expaired_announcements->the_post();
				wp_delete_post( get_the_ID(), true );
			}
			wp_reset_postdata();
		}

		// add_dashboard_nav_link
		public function add_dashboard_nav_link() {
			$announcement_tab      = get_directorist_option( 'announcement_tab', 'directorist' );
			$announcement_tab_text = get_directorist_option( 'announcement_tab_text', __( 'Announcements', 'directorist' ) );
			if ( empty( $announcement_tab ) ) {
				return;
			}
			$nav_label         = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count'></span>";
			$new_announcements = $this->get_new_announcement_count();

			if ( $new_announcements > 0 ) {
				$nav_label = $announcement_tab_text . " <span class='atbdp-nav-badge new-announcement-count show'>{$new_announcements}</span>";
			}

			?>
			<li class="atbdp_tab_nav--content-link">
				<a href="" class="atbdp_all_booking_nav-link atbd-dash-nav-dropdown atbd_tn_link" target="announcement">
					<span class="directorist_menuItem-text">
						<span class="directorist_menuItem-icon"><?php directorist_icon( 'las la-bullhorn' ); ?></span><?php echo wp_kses( $nav_label, array( 'span' => array( 'class' => array() ) ) ); ?>
					</span>
				</a>
			</li>
			<?php
		}

		public function add_dashboard_nav_content() {
			$announcements      = self::get_announcement_query_data();
			$total_posts        = count( $announcements->posts );
			$skipped_post_count = 0;
			$current_user_email = get_the_author_meta( 'user_email', get_current_user_id() );

			?>
			<div class="atbd_tab_inner" id="announcement">
				<div class="atbd_announcement_wrapper">
					<?php if ( $announcements->have_posts() ) : ?>
					<div class="atbdp-accordion">
						<?php
						while ( $announcements->have_posts() ) :
							$announcements->the_post();

							// Check recepent restriction
							$recipient = get_post_meta( get_the_ID(), '_recepents', true );
							if ( ! empty( $recipient ) && is_array( $recipient ) ) {
								if ( ! in_array( $current_user_email, $recipient ) ) {
									$skipped_post_count++;
									continue;
								}
							}
							?>
						<div class="atbdp-announcement <?php echo 'update-announcement-status announcement-item announcement-id-' . get_the_ID(); ?>" data-post-id="<?php the_id(); ?>">
							<div class="atbdp-announcement__date">
								<span class="atbdp-date-card-part-1"><?php echo get_the_date( 'd' ); ?></span>
								<span class="atbdp-date-card-part-2"><?php echo get_the_date( 'M' ); ?></span>
								<span class="atbdp-date-card-part-3"><?php echo get_the_date( 'Y' ); ?></span>
							</div>
							<div class="atbdp-announcement__content">
								<h3 class="atbdp-announcement__title">
									<?php the_title(); ?>
								</h3>
								<p><?php the_content(); ?></p>
							</div>
							<div class="atbdp-announcement__close">
								<button class="close-announcement" data-post-id="<?php the_id(); ?>"><i class="la la-times"></i></button>
							</div>
						</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php else : ?>
						<div class="directorist_not-found"><p><?php esc_html_e( 'No announcement found', 'directorist' ); ?></p></div>
						<?php
					endif;

					if ( $total_posts && $skipped_post_count == $total_posts ) {
						esc_html_e( 'No announcement found', 'directorist' );
					}
					?>
				</div>
			</div>
			<?php
		}

		private function get_all_user_emails() {
			$result = array();
			$number = 300;

			// Initiate first query
			$args = array(
				'role__not_in' => 'Administrator',
				'fields'       => 'user_email',
				'paged'        => 1,
				'number'       => $number,
			);

			$query  = new WP_User_Query( $args );
			$users  = (array) $query->get_results();
			$result = array_merge( $users, $result );

			$total = $query->get_total();

			if ( $total <= $number ) {
				return array_filter( $result );
			}

			$number_of_loops = ceil( $total/$number );

			// Run subsequent queries
			for ( $i = 2; $i <= $number_of_loops ; $i++ ) {
				$args = array(
					'role__not_in' => 'Administrator',
					'fields'       => 'user_email',
					'paged'        => $i,
					'number'       => $number,
				);
				$query  = new WP_User_Query( $args );
				$users  = (array) $query->get_results();
				$result = array_merge( $users, $result );
			}

			return array_filter( $result );
		}

		// send_announcement
		public function send_announcement() {
			$nonce         = isset( $_POST['nonce'] ) ? wp_unslash( $_POST['nonce'] ) : ''; // @codingStandardsIgnoreLine.WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$to            = isset( $_POST['to'] ) ? sanitize_text_field( wp_unslash( $_POST['to'] ) ) : 'all_user';
			$recipient     = isset( $_POST['recipient'] ) ? sanitize_text_field( wp_unslash( $_POST['recipient'] ) ) : '';
			$subject       = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
			$message       = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
			$expiration    = isset( $_POST['expiration'] ) ? intval( $_POST['expiration'] ) : 0;
			$send_to_email = isset( $_POST['send_to_email'] ) ? boolval( $_POST['send_to_email'] ) : true;

			$status = array(
				'success' => false,
				'message' => __( 'Sorry, something went wrong, please try again', 'directorist' ),
			);

			if ( ! wp_verify_nonce( $nonce, directorist_get_nonce_key() ) ) {
				$status['message'] = __( 'Invalid request', 'directorist' );
				wp_send_json( $status );
			}

			// Only admin can send announcements
			if ( ! current_user_can( 'manage_options' ) ) {
				$status['message'] = __( 'You are not allowed to send announcement', 'directorist' );
				wp_send_json( $status );
			}

			// Validate Subject
			if ( empty( $subject ) ) {
				$status['message'] = __( 'The subject cannot be empty', 'directorist' );
				wp_send_json( $status );
			}

			// Validate Message
			if ( empty( $message ) ) {
				$status['message'] = __( 'The message cannot be empty', 'directorist' );
				wp_send_json( $status );
			}

			if ( strlen( $message ) > 400 ) {
				$status['message'] = __( 'Maximum 400 characters are allowed for the message', 'directorist' );
				wp_send_json( $status );
			}

			// Save the post
			$announcement = wp_insert_post(
				array(
					'post_type'    => 'listing-announcement',
					'post_title'   => $subject,
					'post_content' => $message,
					'post_status'  => 'publish',
				)
			);

			if ( is_wp_error( $announcement ) ) {
				$status['message'] = __( 'Sorry, something went wrong, please try again', 'directorist' );
				wp_send_json( $status );
			}

			$status['announcement'] = $announcement;

			$recipients = array();

			// Get Recipient
			if ( 'selected_user' === $to ) {
				$recipients = explode( ',', $recipient );
				$recipients = array_map( 'trim', $recipients );
				$recipients = array_filter( $recipients, 'is_email' );
				$recipients = array_unique( $recipients );

				// Validate recipient
				if ( empty( $recipients ) ) {
					$status['message'] = __( 'No recipient found', 'directorist' );
					wp_send_json( $status );
				}
			}

			if ( 'all_user' === $to ) {
				$users = $this->get_all_user_emails();

				if ( ! empty( $users ) ) {
					$recipients = $users;
				}

				// Validate recipient
				if ( empty( $recipients ) ) {
					$status['message'] = __( 'No recipient found', 'directorist' );
					wp_send_json( $status );
				}
			}

			if ( 'all_user' !== $to ) {
				update_post_meta( $announcement, '_recepents', $recipient );
			} else {
				update_post_meta( $announcement, '_recepents', '' );
			}

			// Update the post meta
			update_post_meta( $announcement, '_to', $to );
			update_post_meta( $announcement, '_closed', false );
			update_post_meta( $announcement, '_seen', false );

			if ( empty( $expiration ) ) {
				$expiration = 365;
			}

			$today    = date( 'Y-m-d' );
			$exp_date = date( 'Y-m-d', strtotime( $today . " + {$expiration} days" ) );

			update_post_meta( $announcement, '_exp_in_days', $expiration );
			update_post_meta( $announcement, '_exp_date', $exp_date );

			// Send email if enabled
			if ( $send_to_email ) {
				$message = atbdp_email_html( $subject, $message );
				$headers = ATBDP()->email->get_email_headers();

				/**
				 * Filter the email headers for Directorist announcement emails.
				 *
				 * Allows developers to modify the email headers before sending Directorist announcement emails.
				 *
				 * @since 7.8.3
				 *
				 * @param string $headers   The email headers.
				 * @param string[] $recipients The email recipients.
				 *
				 * @return string The filtered email headers.
				 */
				$headers = apply_filters( 'directorist_announcement_email_headers', $headers, $recipients );

				/**
				 * Filter the email recipients for Directorist announcement emails.
				 *
				 * Allows developers to modify the list of email recipients for Directorist announcement emails.
				 *
				 * @since 7.8.3
				 *
				 * @param string[] $recipients The email recipients.
				 *
				 * @return string[] The filtered email recipients.
				 */
				$recipients = apply_filters( 'directorist_announcement_email_recipients', $recipients );

				ATBDP()->email->send_mail( $recipients, $subject, $message, $headers );
			}

			$status['success'] = true;
			$status['message'] = __( 'The announcement has been sent successfully', 'directorist' );

			wp_send_json( $status );
		}

		// close_announcement
		public function close_announcement() {
			$status = array( 'success' => false );

			if ( ! directorist_verify_nonce( 'nonce' ) ) {
				$status['message'] = __( 'Sorry, something went wrong, please try again', 'directorist' );
				wp_send_json( $status );
			}

			$post_id = ( isset( $_POST['post_id'] ) ) ? absint( $_POST['post_id'] ) : 0;

			// Validate post id
			if ( empty( $post_id ) ) {
				$status['message'] = __( 'Sorry, something went wrong, please try again', 'directorist' );
				wp_send_json( $status );
			}

			update_post_meta( $post_id, '_closed', true );

			$status['success'] = true;
			$status['message'] = __( 'The announcement has been closed successfully', 'directorist' );

			wp_send_json( $status );
		}

		// create_announcement_post_type
		public function create_announcement_post_type() {
			register_post_type(
				'listing-announcement',
				array(
					'label'  => 'Announcement',
					'labels' => 'Announcements',
					'public' => false,
				)
			);
		}
	}
endif;
