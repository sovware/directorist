<?php
/**
 * Directorist Fields manager class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;

class Fields {

	private static $fields = array();

	public static function register( $field ) {
		try {
			if ( ! is_subclass_of( $field, Base_Field::class ) ) {
				throw new Exception( 'Must be a subclass of <code>' . Base_Field::class . '</code>' );
			}

			if ( empty( $field->type ) ) {
				throw new Exception( 'The type must be set' );
			}

			if ( isset( self::$fields[ $field->type ] ) ) {
				throw new Exception( 'Field type already registered: ' . $field->type );
			}

			self::$fields[ $field->type ] = $field;
		} catch ( Exception $e ) {
			wp_die( $e->getMessage() );
		}
	}

	public static function exists( $field_type ) {
		return isset( self::$fields[ $field_type ] );
	}

	/**
	 * @param $field_type
	 *
	 * @return Base_Field
	 */
	public static function get( $field_type ) {
		return isset( self::$fields[ $field_type ] ) ? self::$fields[ $field_type ] : false;
	}

	/**
	 * Return all the registered field types.
	 *
	 * @return Base_Field[]
	 */
	public static function get_all() {
		return self::$fields;
	}

	/**
	 * Creates a Field object from an array of field properties.
	 *
	 * @param array|Base_Field $properties
	 *
	 * @return Base_Field | bool
	 */
	public static function create( $properties ) {
		if ( $properties instanceof Base_Field ) {
			$type = $properties->type;
		} else {
			$type = isset( $properties['widget_name'] ) ? $properties['widget_name'] : '';
		}

		$type = self::translate_key_to_field( $type );

		if ( empty( $type ) || ! isset( self::$fields[ $type ] ) ) {
			return new Base_Field( $properties );
		}

		$class      = self::$fields[ $type ];
		$class_name = get_class( $class );
		$field      = new $class_name( $properties );

		return $field;
	}

	public static function translate_key_to_field( $type ) {
		$map = array(
			'address'            => 'text',
			'category'           => 'categories',
			'color'              => 'color_picker',
			'excerpt'            => 'textarea',
			'fax'                => 'text',
			'hide_contact_owner' => 'switch',
			'title'              => 'text',
			'location'           => 'locations',
			'phone'              => 'text',
			'phone2'             => 'text',
			'tag'                => 'tags',
			'tagline'            => 'text',
			'website'            => 'url',
			'zip'                => 'text',
		);

		$map = apply_filters( 'directorist_listing_form_fields_class_map', $map );

		return isset( $map[ $type ] ) ? $map[ $type ] : $type;
	}
}
