<?php
/**
 * Directorist Builder Field Abstract class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Base_Field {

	public $type = 'base';

	protected $props = array();

	protected $errors = array();

	public function __construct( array $props = array() ) {
		$this->props = $props;
	}

	public function __get( $name ) {
		if ( isset( $this->props[ $name ] ) ) {
			return $this->props[ $name ];
		}
	}

	public function __set( $name, $value ) {
		return $this->props[ $name ] = $value;
	}

	public function __isset( $name ) {
		return isset( $this->props[ $name ] );
	}

	public function get_props() {
		return $this->props;
	}

	public function get_key() : string {
		return ( string ) $this->field_key;
	}

	public function get_internal_key() : string {
		return ( string ) $this->widget_key;
	}

	public function is_admin_only() : bool {
		return ( bool ) $this->only_for_admin;
	}

	public function is_required() : bool {
		return ( bool ) $this->required;
	}

	public function is_preset() : bool {
		return ( bool ) ( $this->widget_group === 'preset' );
	}

	public function is_category_only() {
		if ( $this->is_preset() || empty( $this->__get( 'assign_to' ) ) || $this->__get( 'assign_to' ) === 'form' ) {
			return false;
		}

		return true;
	}

	public function get_assigned_category() {
		if ( ! $this->is_category_only() || empty( $this->__get( 'category' ) ) ) {
			return 0;
		}

		return absint( $this->__get( 'category' ) );
	}

	public function add_error( $message = '' ) {
		if ( ! isset( $this->errors[ $this->get_internal_key() ] ) ) {
			$this->errors[ $this->get_internal_key() ] = array();
		}

		$this->errors[ $this->get_internal_key() ][] = $message;
	}

	public function get_error() {
		return isset( $this->errors[ $this->get_internal_key() ] ) ? implode( ' ', $this->errors[ $this->get_internal_key() ] ) : '';
	}

	public function has_error() {
		return ( ! empty( $this->get_error() ) );
	}

	public function get_value( $posted_data ) {
		return directorist_get_var( $posted_data[ $this->get_key() ] );
	}

	public function is_value_empty( $posted_data ) {
		$value = $this->get_value( $posted_data );
		return ( is_null( $value ) || ( is_string( $value ) && $value === '' ) || ( is_array( $value ) && empty( $value ) ) );
	}

	public function validate( $posted_data ) {
		return true;
	}

	public function sanitize( $posted_data ) {
		return directorist_clean( $this->get_value( $posted_data ) );
	}
}
