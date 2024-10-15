<?php
/**
 * Rest Listing actions Controller
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Server;

/**
 * Listings actions controller class.
 */
class Listings_Actions_Controller extends Abstract_Controller {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'listings/(?P<listing_id>[\d]+)/actions';

	/**
	 * Register the routes for listings actions.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => array_merge(
						$this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
						array(
							'id' => array(
								'type'        => 'string',
								'description' => __( 'Action identifier.', 'directorist' ),
								'enum'        => array( 'report', 'contact' ),
								'required'    => true,
							),
						)
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Check if a given request has access to create an action.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		$permissions = $this->check_permissions( $request, 'create' );
		if ( is_wp_error( $permissions ) ) {
			return $permissions;
		}

		if ( ! $permissions ) {
			return new WP_Error( 'directorist_rest_cannot_create', __( 'Sorry, you are not allowed to execute action.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Check permissions.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @param string          $context Request context.
	 * @return bool|WP_Error
	 */
	protected function check_permissions( $request, $context = 'read' ) {
		if ( $request['id'] === 'report' && ! current_user_can( 'read' ) ) {
			return new WP_Error( 'directorist_rest_listing_actions_unauthorized', __( 'Not authorized to execute this action.', 'directorist' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Create an action.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		$listing_id = (int) $request['listing_id'];

		do_action( 'directorist_rest_before_query', 'create_listing_action_item', $request, $listing_id );

		if ( get_post_type( $listing_id ) !== ATBDP_POST_TYPE ) {
			return new WP_Error( 'directorist_rest_invalid_listing_id', __( 'Invalid listing id.', 'directorist' ), 400 );
		}

		$action = $request['id'];

		if ( $action === 'report' ) {
			if ( empty( $request['message'] ) ) {
				return new WP_Error( 'directorist_rest_missing_message', __( 'Message is required to report.', 'directorist' ), 400 );
			}

			$this->send_listing_report( $listing_id, $request['message'] );
		}

		if ( $action === 'contact' ) {
			if ( empty( $request['name'] ) || empty( $request['email'] ) || empty( $request['message'] ) ) {
				return new WP_Error( 'directorist_rest_missing_params', __( 'name, email and message are required.', 'directorist' ), 400 );
			}

			$this->contact_listing_owner( $listing_id, $request['name'], $request['email'], $request['message'] );
		}

		/**
		 * Fires after an action is executed via the REST API.
		 *
		 * @param string          $action Action name.
		 * @param WP_REST_Request $request   Request object.
		 * @param boolean         $creating  True when creating user, false when updating user.
		 */
		do_action( 'directorist_rest_listings_execute_action', $action, $request, true );

		$request->set_param( 'context', 'edit' );
		$response = $this->prepare_item_for_response( $action, $request );
		$response = rest_ensure_response( $response );
		$response->set_status( 201 );

		do_action( 'directorist_rest_after_query', 'create_listing_action_item', $request, $listing_id );

		$response = apply_filters( 'directorist_rest_response', $response, 'create_listing_action_item', $request, $request['id'] );

		return $response;
	}

	/**
	 * Prepare a single listings output for response.
	 *
	 * @param array         $data  Listings data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $data, $request ) {
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$schema = $this->get_item_schema();

		$data = array_intersect_key( $request->get_params(), $schema['properties'] );
		$data = $this->add_additional_fields_to_object( $data, $request );
		$data = $this->filter_response_by_context( $data, $context );

		$response = rest_ensure_response( $data );

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->post_type,
		 * refers to object type being prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param array            $data     Data.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "directorist_rest_prepare_listings_actions", $response, $data, $request );
	}

	/**
	 * Get the Listings's actions schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'listings-actions',
			'type'       => 'object',
			'properties' => array(
				'id' => array(
					'description' => __( 'Action name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'name' => array(
					'description' => __( 'Action initiator name.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'email' => array(
					'description' => __( 'Action email.', 'directorist' ),
					'type'        => 'string',
					'format'      => 'email',
					'context'     => array( 'view', 'edit' ),
				),
				'message' => array(
					'description' => __( 'Action message.', 'directorist' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
			)
		);

		return $this->add_additional_fields_schema( $schema );
	}

	public function send_listing_report( $listing_id, $message ) {
		// sanitize form values
		$post_id = $listing_id;
		$message = esc_textarea( $message );

		// vars
		$user          = wp_get_current_user();
		$site_name     = get_bloginfo( 'name' );
		$site_url      = get_bloginfo( 'url' );
		$listing_title = get_the_title( $post_id );
		$listing_url   = get_permalink( $post_id );

		$placeholders = array(
			'{site_name}'     => $site_name,
			'{site_link}'     => sprintf( '<a href="%s">%s</a>', $site_url, $site_name ),
			'{site_url}'      => sprintf( '<a href="%s">%s</a>', $site_url, $site_url ),
			'{listing_title}' => $listing_title,
			'{listing_link}'  => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_title ),
			'{listing_url}'   => sprintf( '<a href="%s">%s</a>', $listing_url, $listing_url ),
			'{sender_name}'   => $user->display_name,
			'{sender_email}'  => $user->user_email,
			'{message}'       => $message
		);
		$send_email = get_directorist_option( 'admin_email_lists' );

		$to = !empty($send_email) ? $send_email : get_bloginfo('admin_email');

		$subject = __('{site_name} Report Abuse via "{listing_title}"', 'directorist');
		$subject = strtr($subject, $placeholders);

		$message = __("Dear Administrator,<br /><br />This is an email abuse report for a listing at {listing_url}.<br /><br />Name: {sender_name}<br />Email: {sender_email}<br />Message: {message}", 'directorist');
		$message = strtr($message, $placeholders);

		$message = atbdp_email_html($subject, $message);
		$headers = "From: {$user->display_name} <{$user->user_email}>\r\n";
		$headers .= "Reply-To: {$user->user_email}\r\n";

		// return true or false, based on the result
		return (bool) ATBDP()->email->send_mail( $to, $subject, $message, $headers );
	}

	public function contact_listing_owner( $listing_id, $name, $email, $message ) {
		// sanitize form values
		$post_id       = $listing_id;
		$name          = sanitize_text_field( $name );
		$email         = sanitize_email( $email );
		$listing_email = get_post_meta( $post_id, '_email', true );
		$message       = stripslashes( esc_textarea( $message ) );
		// vars
		$post_author_id        = get_post_field('post_author', $post_id);
		$user                  = get_userdata($post_author_id);
		$site_name             = get_bloginfo('name');
		$site_url              = get_bloginfo('url');
		$site_email            = get_bloginfo('admin_email');
		$listing_title         = get_the_title($post_id);
		$listing_url           = get_permalink($post_id);
		$date_format           = get_option('date_format');
		$time_format           = get_option('time_format');
		$current_time          = current_time('timestamp');
		$contact_email_subject = get_directorist_option('email_sub_listing_contact_email');
		$contact_email_body    = get_directorist_option('email_tmpl_listing_contact_email');
		$contact_recipient 	   = get_user_meta( $post_author_id, 'directorist_contact_owner_recipient', true );
		$user_email            = ! empty( $contact_recipient ) ? $contact_recipient : 'author';

		$placeholders = array(
			'==NAME=='          => $user->display_name,
			'==USERNAME=='      => $user->user_login,
			'==SITE_NAME=='     => $site_name,
			'==SITE_LINK=='     => sprintf('<a href="%s">%s</a>', $site_url, $site_name),
			'==SITE_URL=='      => sprintf('<a href="%s">%s</a>', $site_url, $site_url),
			'==LISTING_TITLE==' => $listing_title,
			'==LISTING_LINK=='  => sprintf('<a href="%s">%s</a>', $listing_url, $listing_title),
			'==LISTING_URL=='   => sprintf('<a href="%s">%s</a>', $listing_url, $listing_url),
			'==SENDER_NAME=='   => $name,
			'==SENDER_EMAIL=='  => $email,
			'==MESSAGE=='       => $message,
			'==TODAY=='         => date_i18n($date_format, $current_time),
			'==NOW=='           => date_i18n($date_format . ' ' . $time_format, $current_time)
		);
		if ('listing_email' == $user_email) {
			$to = $listing_email;
		} else {
			$to = $user->user_email;
		}
		$subject = strtr($contact_email_subject, $placeholders);
		$message = strtr($contact_email_body, $placeholders);
		$message = nl2br($message);
		$headers = "From: {$name} <{$site_email}>\r\n";
		$headers .= "Reply-To: {$email}\r\n";
		$message = atbdp_email_html( $subject, $message );

		// return true or false, based on the result
		return (bool) ATBDP()->email->send_mail( $to, $subject, $message, $headers );
	}
}
