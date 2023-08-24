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

	protected $props = array();

	public function __construct( array $props = array() ) {
		$this->props = $props;
	}

	public function get_prop( string $prop_name, $default = '' ) {
		if ( isset( $this->props[ $prop_name ] ) ) {
			return $this->props[ $prop_name ];
		}

		return $default;
	}

	abstract static public function get_type() : string;

	public function get_key() : string {
		return ( string ) $this->get_prop( 'field_key' );
	}

	public function get_internal_key() : string {
		return ( string ) $this->get_prop( 'widget_key' );
	}

	abstract public function validate( $value );

	abstract public function sanitize( $value );

	// abstract public function get_placeholder() : string;

	// abstract public function get_builder_label() : string;

	// abstract public function get_builder_description() : string;

	abstract public function display( array $attributes = array() ) : void;

	public function is_admin_only() : bool {
		return ( bool ) $this->get_prop( 'only_for_admin', false );
	}

	public function is_required() : bool {
		return ( bool ) $this->get_prop( 'required', false );
	}

	public function is_preset() : bool {
		return ( bool ) ( $this->get_prop( 'widget_group', 'preset' ) === 'preset' );
	}

	// public function get_builder_config() {
	// 	return array(
	// 		'type'           => static::get_type(),
	// 		'label'          => $this->get_label(),
	// 		'widget_group'   => $this->is_preset() ? 'preset' : 'custom',
	// 		'widget_name'    => static::get_type(),
	// 		'widget_key'     => 'text',
	// 	);
	// }
}
