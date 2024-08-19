<?php

/**
 * Cron class of Directorist
 *
 * This class is for running scheduled work and interacting with cronjobs
 *
 * @package     Directorist
 * @copyright   Copyright (c) 2018, AazzTech
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die( 'What the hell are you doing here accessing this file directly' );
}
if ( ! class_exists( 'ATBDP_Cron' ) ) :
	class ATBDP_Cron {


		public function __construct() {
			// init wp schedule
			add_action( 'wp', array( $this, 'atbdp_custom_schedule_cron' ) );
			add_action( 'directorist_hourly_scheduled_events', array( $this, 'atbdp_schedule_tasks' ) );
			// schedule task run after every 5 minutes || use bellow line for debug
			// add_action('init', array($this, 'atbdp_schedule_tasks'));
			add_filter( 'cron_schedules', array( $this, 'atbdp_cron_init' ) );

			add_action( 'edit_post', array( $this, 'update_atbdp_schedule_tasks' ), 10, 2 );

			add_action( 'directorist_cleanup_temporary_uploads', array( $this, 'cleanup_temporary_uploads' ) );
		}

		// update_atbdp_schedule_tasks
		function update_atbdp_schedule_tasks( $post_id, $post ) {

			if ( ! is_admin() || ATBDP_POST_TYPE !== get_post_type( $post_id ) ) {
				return;
			}

			$this->atbdp_schedule_tasks();
		}


		/**
		 * @since 5.0.1
		 */

		public function atbdp_cron_init( $schedules ) {
			$schedules['atbdp_listing_manage'] = apply_filters(
				'atbdp_cron_setup_args',
				array(
					'interval' => 1800,
					'display'  => __( 'Every 30 minutes', 'directorist' ),
				)
			);

			return $schedules;
		}

		/**
		 * @since 5.0.1
		 */
		// the actual function
		public function atbdp_schedule_tasks() {
			// see if fires via email notification
			$this->update_renewal_status(); // we will send about to expire notification here
			$this->update_expired_status();  // we will send expired notification here
			$this->update_expired_listing_status();  // we will send expired notification here
			$this->send_renewal_reminders(); // we will send renewal notification after expiration here
			$this->delete_expired_listings(); // we will delete listings here certain days after expiration here.
			$this->featured_listing_followup();
			// for additional development

			do_action( 'atbdp_schedule_check' );

			/**
			 * @since 5.5.6
			 */
			do_action( 'atbdp_schedule_task' );
		}
		/**
		 * @since 5.0.1
		 */
		public function atbdp_custom_schedule_cron() {
			if ( ! wp_next_scheduled( 'directorist_hourly_scheduled_events' ) ) {
				wp_schedule_event( time(), 'atbdp_listing_manage', 'directorist_hourly_scheduled_events' );
			}

			if ( ! wp_next_scheduled( 'directorist_cleanup_temporary_uploads' ) ) {
				wp_schedule_event( time(), 'daily', 'directorist_cleanup_temporary_uploads' );
			}
		}

		 /**
		  * Move featured listing to general
		  *
		  * @since 6.6.6
		  */

		private function featured_listing_followup() {
			if ( directorist_is_monetization_enabled() && directorist_is_featured_listing_enabled() ) {
				$featured_days = get_directorist_option( 'featured_listing_time', 30 );
				// Define the query
				$args = array(
					'post_type'      => ATBDP_POST_TYPE,
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'cache_results'  => false,
					'nopaging'       => true,
					'meta_query'     => array(
						array(
							'key'   => '_featured',
							'value' => 1,
						),
					),
				);

				$listings = new WP_Query( $args );

				// Start the Loop
				if ( $listings->found_posts ) {
					foreach ( $listings->posts as $listing ) {
						$order = $this->get_order_by_listing( $listing->ID );
						if ( $order ) {
							$days = round( abs( strtotime( current_time( 'mysql' ) ) - strtotime( $order[0]->post_date ) ) / 86400 );
							if ( $days > $featured_days ) {
								do_action( 'atbdp_listing_featured_to_general', $listing->ID );
								update_post_meta( $listing->ID, '_featured', '' );
							}
						}
					}
				}
			}
		}

		private function get_order_by_listing( $listing_id ) {
			$args = array(
				'post_type'      => ATBDP_ORDER_POST_TYPE,
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => '_listing_id',
						'value' => $listing_id,
					),
					array(
						'key'   => '_payment_status',
						'value' => 'completed',
					),
				),
			);

			$listings = new WP_Query( $args );

			// Start the Loop
			if ( $listings->found_posts ) {
				return $listings->posts;
			}
			return '';
		}

		/**
		 * Move listings to renewal status (only if applicable).
		 *
		 * @since    3.1.0
		 * @access   private
		 */
		private function update_renewal_status() {
			$renew_email_threshold = (int) get_directorist_option( 'email_to_expire_day' ); // before how many days of expiration, a renewal message should be sent

			if ( directorist_can_user_renew_listings() && $renew_email_threshold > 0 ) {
				$renew_email_threshold_date = date( 'Y-m-d H:i:s', strtotime( "+{$renew_email_threshold} days" ) );

				// Define the query
				$args = array(
					'post_type'      => ATBDP_POST_TYPE,
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'cache_results'  => false,
					'nopaging'       => true,
					'meta_query'     => array(
						'relation'   => 'AND',
						'renewal_status' => array(
							'key' => '_listing_status',
							'value' => 'renewal',
							'compare' => '!=',
						),
						'never_expire' => array(
							'key'     => '_never_expire',
							'compare' => 'NOT EXISTS',
						),
						'expiry_date' => array(
							'key'     => '_expiry_date',
							'value'   => $renew_email_threshold_date,
							'compare' => '<=',
							// _expiry_date > $renew_email_threshold_date,     '2018-04-15 09:24:00' < '2018-04-09 12:57:27'. eg. expiry date can not be greater than renewal threshold because threshold is the future date. expiration date should be equal to future date or less.
							'type'    => 'DATETIME',
						),
					),
				);

				$listings = new WP_Query( $args ); // get all the post that has post_status only and update their status and fire an email

				if ( $listings->found_posts ) {
					foreach ( $listings->posts as $listing ) {
						update_post_meta( $listing->ID, '_listing_status', 'renewal' );

						// hook for dev.
						do_action( 'atbdp_status_updated_to_renewal', $listing->ID );
					}
				}
			}
		}

		/**
		 * Move listings to expired status (only if applicable).
		 *
		 * @since    3.1.0
		 * @access   private
		 */
		private function update_expired_status() {
			$email_renewal_day = (int) get_directorist_option( 'email_renewal_day' );
			$delete_in_days    = (int) get_directorist_option( 'delete_expired_listings_after' );
			$del_exp_l         = get_directorist_option( 'delete_expired_listing' );
			// add renewal reminder days to deletion thresholds
			$delete_threshold = directorist_can_user_renew_listings() ? ( $email_renewal_day +  $delete_in_days ) : $delete_in_days;

			// Define the query
			$args = array(
				'post_type'      => ATBDP_POST_TYPE,
				'posts_per_page' => -1,
				'cache_results'  => false,
				'nopaging'       => true,
				'post_status'    => 'publish', // get expired post with published status
				'meta_query'     => array(
					'relation'   => 'AND',
					'never_expire' => array(
						'key'     => '_never_expire',
						'compare' => 'NOT EXISTS',
					),
					'expiry_date' => array(
						'key'     => '_expiry_date',
						'value'   => current_time( 'mysql' ),
						'compare' => '<=',                    // eg. expire date 6 <= current date 7 will return the post
						'type'    => 'DATETIME',
					),
				),
			);

			$listings = new WP_Query( apply_filters( 'directorist_update_listings_expired_status_query_arguments ', $args ) );

			if ( $listings->have_posts() ) {
				foreach ( $listings->posts as $listing ) {
					// prepare the post meta data
					$meta_input = array(
						// TODO: Status has been migrated, remove related code.
						'_listing_status'        => 'expired',
						'_featured'              => 0,
						'_renewal_reminder_sent' => 0,
					);

					// delete expired listings?
					if ( $del_exp_l ) {
						// if deletion threshold is set then add deletion date
						if ( $delete_threshold > 0 ) {
							$meta_input['_deletion_date'] = date( 'Y-m-d H:i:s', strtotime( '+' . $delete_threshold . ' days' ) );
						} else {
							$meta_input['_deletion_date'] = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) );
						}
					}

					$expired_listing_id = wp_update_post( array(
						'ID'          => $listing->ID,
						'post_status' => 'expired',
						'meta_input'  => $meta_input,
					) );

					if ( is_wp_error( $expired_listing_id ) ) {
						continue;
					}

					do_action( 'atbdp_listing_expired', $expired_listing_id );
				}
			}
		}

		/**
		 * Move listings to expired status (only if applicable).
		 *
		 * @since    3.1.0
		 * @access   private
		 */
		private function update_expired_listing_status() {
			// TODO: Status has been migrated, remove related code.
			// // Define the query
			// $args                         = array(
			// 	'post_type'      => ATBDP_POST_TYPE,
			// 	'posts_per_page' => -1,
			// 	'post_status'    => 'publish', // get expired post with published status
			// );
			// 	$meta                     = array();
			// 	$meta['renewed_by_admin'] = array(
			// 		'relation' => 'OR',
			// 		array(
			// 			'key'     => '_expiry_date',
			// 			'value'   => current_time( 'mysql' ),
			// 			'compare' => '>',                     // eg. expire date 6 <= current date 7 will return the post
			// 			'type'    => 'DATETIME',
			// 		),
			// 		array(
			// 			'key'   => '_never_expire',
			// 			'value' => 1,
			// 		),
			// 	);
			// 		$meta['get_expired']  = array(
			// 			'key'     => '_listing_status',
			// 			'value'   => 'expired',
			// 			'compare' => '=',
			// 		);

			// 		$args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta );
			// 		$listings           = new WP_Query( $args );
			// 		if ( $listings->found_posts ) {
			// 			foreach ( $listings->posts as $listing ) {
			// 				// prepare the post meta data
			// 				$metas = array(
			// 					'_listing_status'        => 'post_status',
			// 					'_renewal_reminder_sent' => 0,
			// 				);
			// 				wp_update_post(
			// 					array(
			// 						'ID'          => $listing->ID,
			// 						'post_status' => 'publish',      // update the status to private so that we do not run this func a second time
			// 						'meta_input'  => $metas,         // insert all meta data once to reduce update meta query
			// 					)
			// 				);
			// 			}
			// 		}

			$args = array(
				'post_type'      => ATBDP_POST_TYPE,
				'posts_per_page' => -1,
				'post_status'    => 'expired',
				'cache_results'  => false,
				'nopaging'       => true,
				'meta_query' => array(
					'relation' => 'OR',
					'never_expire' => array(
						'key'     => '_never_expire',
						'compare' => 'EXISTS',
					),
					'expiry_date' => array(
						'key'     => '_expiry_date',
						'value'   => current_time( 'mysql' ),
						'compare' => '>',                     // eg. expire date 6 <= current date 7 will return the post
						'type'    => 'DATETIME',
					),
				)
			);

			$listings = new WP_Query( $args );

			if ( $listings->have_posts() ) {
				foreach ( $listings->posts as $listing ) {
					wp_update_post( array(
						'ID'          => $listing->ID,
						'post_status' => 'publish',
						'meta_input'  => array(
							'_listing_status'        => 'post_status',
							'_renewal_reminder_sent' => 0,
						),
					) );
					do_action( 'atbdp_after_renewal', $listing->ID );
				}
			}
		}

		/**
		 * Send renewal reminders to expired listings (only if applicable)
		 *
		 * @since    3.1.0
		 * @access   private
		 */
		private function send_renewal_reminders() {
			$email_renewal_day = (int) get_directorist_option( 'email_renewal_day' );

			if ( directorist_can_user_renew_listings() && $email_renewal_day > 0 ) {
				// Define the query
				$args = array(
					'post_type'      => ATBDP_POST_TYPE,
					'posts_per_page' => -1,
					'cache_results'  => false,
					'nopaging'       => true,
					'post_status'    => 'expired',
					'meta_query'     => array(
						'relation'   => 'AND',
						// TODO: Status has been migrated, remove related code.
						// array(
						// 	'key'   => '_listing_status',
						// 	'value' => 'expired',
						// ),
						'never_expire' => array(
							'key'     => '_never_expire',
							'compare' => 'NOT EXISTS',
						),
						'renewal_reminder_sent' => array(
							'key'   => '_renewal_reminder_sent',
							'value' => 0,
						),
					),
				);

				$listings = new WP_Query( $args );

				// Start the Loop
				if ( $listings->have_posts() ) {
					foreach ( $listings->posts as $listing ) {
						// Send emails
						$expiration_date      = get_post_meta( $listing->ID, '_expiry_date', true );
						$expiration_date_time = strtotime( $expiration_date );
						$reminder_date_time   = strtotime( "+{$email_renewal_day} days", strtotime( $expiration_date_time ) );

						if ( current_time( 'timestamp' ) > $reminder_date_time ) {
							do_action( 'atbdp_send_renewal_reminder', $listing->ID );
							// once we notify the user, lets update the reminder status so that we do not run this func a second time
							update_post_meta( $listing->ID, '_renewal_reminder_sent', 1 );
						}
					}
				}
			}
		}

		/**
		 * Delete expired listings (only if applicable)
		 *
		 * @since    3.1.0
		 * @access   private
		 */
		private function delete_expired_listings() {

			$del_exp_l = get_directorist_option( 'delete_expired_listing' );
			if ( ! $del_exp_l ) {
				return; // vail if admin does not want to delete expired listing
			}
			$del_mode = get_directorist_option( 'deletion_mode', 'trash' ); // force_delete | trash
			$force    = 'force_delete' == $del_mode ? true : false; // for now we are just focusing on Force Delete or Not. later we may consider more
			// Define the query
			$args = array(
				'post_type'      => ATBDP_POST_TYPE,
				'posts_per_page' => -1,
				'cache_results'  => false,
				'nopaging'       => true,
				'post_status'    => 'expired',
				'meta_query'     => array(
					'relation'   => 'AND',
					// TODO: Status has been migrated, remove related code.
					// array(
					// 	'key'   => '_listing_status',
					// 	'value' => 'expired',
					// ),
					'never_expire' => array(
						'key'     => '_never_expire',
						'compare' => 'NOT EXISTS',
					),
					'deletion_date' => array(
						'key'     => '_deletion_date',
						'value'   => current_time( 'mysql' ),
						'compare' => '<',
						'type'    => 'DATETIME',
					),
				),
			);

			$listings = new WP_Query( $args );

			if ( $listings->found_posts ) {
				// should we delete or trash? @todo; later think about adding option to change post status (eg to hidden) instead of deleting them.
				foreach ( $listings->posts as $listing ) {
					if ( $force ) {
						wp_delete_post( $listing->ID, $force );
					} else {
						wp_trash_post( $listing->ID );
					}
					do_action( 'atbdp_deleted_expired_listings', $listing->ID );
				}
			}
		}

		public function cleanup_temporary_uploads() {
			directorist_delete_temporary_upload_dirs();

			if ( ! wp_next_scheduled( 'directorist_cleanup_temporary_uploads' ) ) {
				wp_schedule_event( time(), 'daily', 'directorist_cleanup_temporary_uploads' );
			}
		}
	}

endif;
