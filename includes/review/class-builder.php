<?php
/**
 * Review form builder data class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Builder {

	protected $fields = array();

	private static $instance = null;

	public static function get( $post_id ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $post_id );
		}

		return self::$instance;
	}

	private function __construct( $post_id ) {
		$this->load_data( $post_id );
	}

	public function load_data( $post_id )  {
		$type = get_post_meta( $post_id, '_directory_type', true );
		$this->fields = get_term_meta( $type, 'review_config', true );
	}

	/**
	 * Get rating type.
	 *
	 * @return string
	 */
	public function get_rating_type() {
		return $this->get_field( 'rating_type', 'single' );
	}

	public function is_rating_type_single() {
		return $this->get_field( 'rating_type', 'single' ) === 'single';
	}

	public function get_name_label( $default = '' ) {
		return $this->get_field( 'name_label', $default );
	}

	public function get_name_placeholder( $default = '' ) {
		return $this->get_field( 'name_placeholder', $default );
	}

	public function get_email_label( $default = '' ) {
		return $this->get_field( 'email_label', $default );
	}

	public function get_email_placeholder( $default = '' ) {
		return $this->get_field( 'email_placeholder', $default );
	}

	public function get_website_label( $default = '' ) {
		return $this->get_field( 'website_label', $default );
	}

	public function get_website_placeholder( $default = '' ) {
		return $this->get_field( 'website_placeholder', $default );
	}

	public function get_comment_label( $default = '' ) {
		return $this->get_field( 'comment_label', $default );
	}

	public function get_comment_placeholder( $default = '' ) {
		return $this->get_field( 'comment_placeholder', $default );
	}

	public function is_cookies_consent_active() {
		return (bool) $this->get_field( 'cookies_consent', false );
	}

	public function is_website_field_active() {
		return (bool) $this->get_field( 'show_website_field', false );
	}

	protected function get_field( $field_key, $default = false ) {
		$field_key = "review_{$field_key}";
		return ( ( isset( $this->fields[ $field_key ] ) && $this->fields[ $field_key ] !== '' ) ? $this->fields[ $field_key ] : $default );
	}
}
