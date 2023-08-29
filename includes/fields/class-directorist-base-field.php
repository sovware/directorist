<?php
/**
 * Directorist Builder Field Abstract class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Base_Field {

	public $type = 'base';

	protected $props = array();

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
		return ( bool ) $this->widget_group === 'preset';
	}


	abstract public function validate( $value );

	abstract public function sanitize( $value );

	abstract public function get_builder_label() : string;

	abstract public function get_builder_icon() : string;

	abstract public function get_builder_fields( $directory_manager ) : array;

	abstract public function display( array $attributes = array() ) : void;

	public function to_builder_array( $directory_manager ) {
		return array(
			'label'   => $this->get_builder_label(),
			'icon'    => $this->get_builder_icon(),
			'options' => $this->get_builder_fields( $directory_manager ),
		);
	}
}
