<?php
namespace Directorist\AddListingForm;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use WP_Error;
use Exception;
use ATBDP_Permalink;
use Directorist\Helper;
use Directorist\Fields\Fields;

class SubmissionController {
    protected static $selected_categories = null;

    /**
     * Request source origin.
     * Possible values are: web, api
     *
     * @var string
     */
    protected static $from = 'web';

    protected static function cache_selected_categories( $directory_id, &$posted_data ) {
        // Cache categories to check assigned categories in custom fields.
        $category_field = directorist_get_listing_form_category_field( $directory_id );

        if ( ! empty( $category_field ) ) {
            $selected_categories = Fields::create( $category_field )->get_value( $posted_data );

            if ( is_null( self::$selected_categories ) && ! empty( $selected_categories ) ) {
                self::$selected_categories = array_filter( wp_parse_id_list( $selected_categories ) );
            }
        }
    }

    protected static function is_admin_only_field( &$field ) {
        return $field->is_admin_only();
        // return ( $field->is_admin_only() && ! current_user_can( get_post_type_object( ATBDP_POST_TYPE )->cap->edit_others_posts ) );
    }

    protected static function should_ignore_category_custom_field( &$field ) {
        return ( $field->is_category_only() && ( is_null( self::$selected_categories ) || ! in_array( $field->get_assigned_category(), self::$selected_categories, true ) ) );
    }

    protected static function should_ignore_conditional_logic_field( &$field, &$posted_data ) {
        $conditional_logic = self::get_field_conditional_logic( $field->get_props() );

        if ( empty( $conditional_logic ) ) {
            return false;
        }

        return ! self::evaluate_conditional_logic( $conditional_logic, $posted_data );
    }

    protected static function get_field_conditional_logic( $field_props ) {
        if ( ! is_array( $field_props ) ) {
            return [];
        }

        $conditional_logic = [];

        if ( ! empty( $field_props['conditional_logic'] ) && is_array( $field_props['conditional_logic'] ) ) {
            $conditional_logic = $field_props['conditional_logic'];
        } elseif ( ! empty( $field_props['options']['conditional_logic']['value'] ) && is_array( $field_props['options']['conditional_logic']['value'] ) ) {
            $conditional_logic = $field_props['options']['conditional_logic']['value'];
        } elseif ( ! empty( $field_props['options']['conditional_logic'] ) && is_array( $field_props['options']['conditional_logic'] ) && ! isset( $field_props['options']['conditional_logic']['value'] ) ) {
            $conditional_logic = $field_props['options']['conditional_logic'];
        }

        if ( empty( $conditional_logic ) || empty( $conditional_logic['groups'] ) || ! is_array( $conditional_logic['groups'] ) ) {
            return [];
        }

        $enabled = isset( $conditional_logic['enabled'] ) ? filter_var( $conditional_logic['enabled'], FILTER_VALIDATE_BOOLEAN ) : false;

        if ( ! $enabled ) {
            return [];
        }

        $groups = [];
        foreach ( $conditional_logic['groups'] as $group ) {
            if ( empty( $group['conditions'] ) || ! is_array( $group['conditions'] ) ) {
                continue;
            }

            $conditions = [];
            foreach ( $group['conditions'] as $condition ) {
                if ( empty( $condition['field'] ) || empty( $condition['operator'] ) ) {
                    continue;
                }

                $conditions[] = $condition;
            }

            if ( empty( $conditions ) ) {
                continue;
            }

            $groups[] = [
                'operator'   => self::normalize_conditional_logic_group_operator( $group['operator'] ?? 'AND', 'AND' ),
                'conditions' => $conditions,
            ];
        }

        if ( empty( $groups ) ) {
            return [];
        }

        return [
            'enabled'        => true,
            'action'         => ! empty( $conditional_logic['action'] ) && 'hide' === strtolower( trim( $conditional_logic['action'] ) ) ? 'hide' : 'show',
            'globalOperator' => self::normalize_conditional_logic_group_operator( $conditional_logic['globalOperator'] ?? 'OR', 'OR' ),
            'groups'         => $groups,
        ];
    }

    protected static function normalize_conditional_logic_group_operator( $operator, $default = 'OR' ) {
        $operator = is_string( $operator ) ? strtoupper( trim( $operator ) ) : '';

        return in_array( $operator, [ 'AND', 'OR' ], true ) ? $operator : $default;
    }

    protected static function evaluate_conditional_logic( $conditional_logic, &$posted_data ) {
        $group_results = [];

        foreach ( $conditional_logic['groups'] as $group ) {
            $condition_results = [];

            foreach ( $group['conditions'] as $condition ) {
                $field_key = isset( $condition['field'] ) ? trim( (string) $condition['field'] ) : '';
                $operator  = isset( $condition['operator'] ) ? trim( (string) $condition['operator'] ) : '';

                if ( '' === $field_key || '' === $operator ) {
                    continue;
                }

                $field_value         = self::get_conditional_logic_field_value( $field_key, $posted_data );
                $condition_results[] = self::evaluate_conditional_logic_condition( $condition, $field_value );
            }

            if ( empty( $condition_results ) ) {
                continue;
            }

            $group_results[] = ( 'OR' === $group['operator'] )
                ? in_array( true, $condition_results, true )
                : ! in_array( false, $condition_results, true );
        }

        $result = true;

        if ( ! empty( $group_results ) ) {
            $result = ( 'AND' === $conditional_logic['globalOperator'] )
                ? ! in_array( false, $group_results, true )
                : in_array( true, $group_results, true );
        }

        return ( 'hide' === $conditional_logic['action'] ) ? ! $result : $result;
    }

    protected static function get_conditional_logic_field_value( $field_key, &$posted_data ) {
        $field_key  = self::normalize_conditional_logic_field_key( $field_key );
        $candidates = self::get_conditional_logic_field_key_candidates( $field_key );

        foreach ( $candidates as $candidate ) {
            $value = self::get_posted_data_value_by_key( $candidate, $posted_data, $exists );

            if ( $exists ) {
                return $value;
            }
        }

        return null;
    }

    protected static function normalize_conditional_logic_field_key( $field_key ) {
        $field_key = trim( (string) $field_key );

        $map = [
            'title'       => 'listing_title',
            'description' => 'listing_content',
            'content'     => 'listing_content',
        ];

        return isset( $map[ $field_key ] ) ? $map[ $field_key ] : $field_key;
    }

    protected static function get_conditional_logic_field_key_candidates( $field_key ) {
        $candidates = [ $field_key ];

        if ( substr( $field_key, -2 ) === '[]' ) {
            $candidates[] = substr( $field_key, 0, -2 );
        }

        switch ( $field_key ) {
            case 'category':
            case 'categories':
            case 'admin_category_select':
            case 'admin_category_select[]':
            case 'tax_input[' . ATBDP_CATEGORY . '][]':
            case 'in_cat':
                $candidates = array_merge(
                    $candidates,
                    [
                        'tax_input[' . ATBDP_CATEGORY . '][]',
                        'admin_category_select[]',
                        'admin_category_select',
                        'category',
                        'categories',
                        'in_cat',
                    ]
                );
                break;

            case 'tag':
            case 'tags':
            case 'in_tag':
            case 'in_tag[]':
            case 'tax_input[' . ATBDP_TAGS . '][]':
                $candidates = array_merge(
                    $candidates,
                    [
                        'tax_input[' . ATBDP_TAGS . '][]',
                        'tag',
                        'tags',
                        'in_tag[]',
                        'in_tag',
                    ]
                );
                break;

            case 'location':
            case 'locations':
            case 'in_loc':
            case 'tax_input[' . ATBDP_LOCATION . '][]':
                $candidates = array_merge(
                    $candidates,
                    [
                        'tax_input[' . ATBDP_LOCATION . '][]',
                        'location',
                        'locations',
                        'in_loc',
                    ]
                );
                break;

            case 'listing_title':
                $candidates[] = 'title';
                $candidates[] = 'post_title';
                break;

            case 'listing_content':
                $candidates[] = 'description';
                $candidates[] = 'content';
                break;
        }

        if ( $field_key && strpos( $field_key, 'custom-' ) !== 0 ) {
            $candidates[] = 'custom-' . $field_key;
            $candidates[] = 'custom-' . str_replace( '_', '-', $field_key );
        }

        return array_values( array_unique( array_filter( $candidates ) ) );
    }

    protected static function get_posted_data_value_by_key( $key, &$posted_data, &$exists ) {
        $exists = false;

        if ( array_key_exists( $key, $posted_data ) ) {
            $exists = true;
            return $posted_data[ $key ];
        }

        if ( substr( $key, -2 ) === '[]' ) {
            $key_without_array_suffix = substr( $key, 0, -2 );

            if ( array_key_exists( $key_without_array_suffix, $posted_data ) ) {
                $exists = true;
                return $posted_data[ $key_without_array_suffix ];
            }
        }

        if ( preg_match( '/^tax_input\[([^\]]+)\](?:\[\])?$/', $key, $matches ) && isset( $posted_data['tax_input'] ) && is_array( $posted_data['tax_input'] ) && array_key_exists( $matches[1], $posted_data['tax_input'] ) ) {
            $exists = true;
            return $posted_data['tax_input'][ $matches[1] ];
        }

        if ( preg_match( '/^custom_field\[([^\]]+)\](?:\[\])?$/', $key, $matches ) && isset( $posted_data['custom_field'] ) && is_array( $posted_data['custom_field'] ) && array_key_exists( $matches[1], $posted_data['custom_field'] ) ) {
            $exists = true;
            return $posted_data['custom_field'][ $matches[1] ];
        }

        return null;
    }

    protected static function evaluate_conditional_logic_condition( $condition, $field_value ) {
        $operator        = isset( $condition['operator'] ) ? strtolower( trim( (string) $condition['operator'] ) ) : '';
        $condition_value = isset( $condition['value'] ) ? $condition['value'] : '';

        if ( '' === $operator ) {
            return false;
        }

        if ( 'uploaded' === strtolower( trim( self::conditional_logic_value_to_string( $condition_value ) ) ) ) {
            if ( in_array( $operator, [ 'is', '==', '=' ], true ) ) {
                return ! self::conditional_logic_value_is_empty( $field_value );
            }

            if ( in_array( $operator, [ 'is not', '!=', 'not' ], true ) ) {
                return self::conditional_logic_value_is_empty( $field_value );
            }
        }

        if ( in_array( $operator, [ 'empty', 'is empty' ], true ) ) {
            return self::conditional_logic_value_is_empty( $field_value );
        }

        if ( in_array( $operator, [ 'not empty', 'is not empty' ], true ) ) {
            return ! self::conditional_logic_value_is_empty( $field_value );
        }

        if ( is_array( $field_value ) ) {
            return self::evaluate_conditional_logic_array_condition( $field_value, $condition_value, $operator );
        }

        $field_value       = strtolower( trim( self::conditional_logic_value_to_string( $field_value ) ) );
        $condition_value   = strtolower( trim( self::conditional_logic_value_to_string( $condition_value ) ) );
        $field_value_raw   = self::conditional_logic_value_to_string( $field_value );
        $condition_raw     = self::conditional_logic_value_to_string( $condition_value );

        switch ( $operator ) {
            case 'is':
            case '==':
            case '=':
                return $field_value_raw === $condition_raw;

            case 'is not':
            case '!=':
            case 'not':
                return $field_value_raw !== $condition_raw;

            case 'contains':
                return strpos( $field_value_raw, $condition_raw ) !== false;

            case 'does not contain':
                return strpos( $field_value_raw, $condition_raw ) === false;

            case 'greater than':
            case '>':
                return is_numeric( $field_value_raw ) && is_numeric( $condition_raw ) && (float) $field_value_raw > (float) $condition_raw;

            case 'less than':
            case '<':
                return is_numeric( $field_value_raw ) && is_numeric( $condition_raw ) && (float) $field_value_raw < (float) $condition_raw;

            case 'greater than or equal':
            case '>=':
                return is_numeric( $field_value_raw ) && is_numeric( $condition_raw ) && (float) $field_value_raw >= (float) $condition_raw;

            case 'less than or equal':
            case '<=':
                return is_numeric( $field_value_raw ) && is_numeric( $condition_raw ) && (float) $field_value_raw <= (float) $condition_raw;

            case 'starts with':
                return strpos( $field_value_raw, $condition_raw ) === 0;

            case 'ends with':
                if ( '' === $condition_raw ) {
                    return true;
                }

                return substr( $field_value_raw, -strlen( $condition_raw ) ) === $condition_raw;
        }

        return false;
    }

    protected static function evaluate_conditional_logic_array_condition( $field_value, $condition_value, $operator ) {
        $field_values     = array_map( [ __CLASS__, 'normalize_conditional_logic_array_value' ], $field_value );
        $field_values     = array_filter( $field_values, 'strlen' );
        $condition_value  = strtolower( trim( self::conditional_logic_value_to_string( $condition_value ) ) );
        $has_field_values = ! empty( $field_values );

        if ( ! $has_field_values ) {
            if ( in_array( $operator, [ 'empty', 'is empty' ], true ) ) {
                return true;
            }

            if ( in_array( $operator, [ 'not empty', 'is not empty', 'is', '==', '=', 'contains' ], true ) ) {
                return false;
            }

            if ( in_array( $operator, [ 'is not', '!=', 'not', 'does not contain' ], true ) ) {
                return true;
            }

            return false;
        }

        switch ( $operator ) {
            case 'is':
            case '==':
            case '=':
                return in_array( $condition_value, $field_values, true ) && 1 === count( array_unique( $field_values ) );

            case 'contains':
                foreach ( $field_values as $field_value ) {
                    if ( $field_value === $condition_value || strpos( $field_value, $condition_value ) !== false ) {
                        return true;
                    }
                }

                return false;

            case 'is not':
            case '!=':
            case 'not':
            case 'does not contain':
                foreach ( $field_values as $field_value ) {
                    if ( $field_value === $condition_value || strpos( $field_value, $condition_value ) !== false ) {
                        return false;
                    }
                }

                return true;
        }

        return false;
    }

    protected static function normalize_conditional_logic_array_value( $value ) {
        if ( is_array( $value ) ) {
            foreach ( [ 'name', 'label', 'value', 'id' ] as $key ) {
                if ( isset( $value[ $key ] ) ) {
                    return strtolower( trim( self::conditional_logic_value_to_string( $value[ $key ] ) ) );
                }
            }
        }

        return strtolower( trim( self::conditional_logic_value_to_string( $value ) ) );
    }

    protected static function conditional_logic_value_is_empty( $value ) {
        if ( is_null( $value ) ) {
            return true;
        }

        if ( is_string( $value ) ) {
            return trim( $value ) === '';
        }

        if ( is_array( $value ) ) {
            return empty( array_filter( $value, function( $item ) {
                return ! self::conditional_logic_value_is_empty( $item );
            } ) );
        }

        return false;
    }

    protected static function conditional_logic_value_to_string( $value ) {
        if ( is_bool( $value ) ) {
            return $value ? 'true' : 'false';
        }

        if ( is_array( $value ) ) {
            return implode( ',', array_map( [ __CLASS__, 'conditional_logic_value_to_string' ], $value ) );
        }

        if ( is_object( $value ) ) {
            return method_exists( $value, '__toString' ) ? (string) $value : '';
        }

        return (string) $value;
    }

    protected static function is_field_submission_empty( &$field, &$posted_data ) {
        return $field->is_value_empty( $posted_data );
    }

    protected static function validate_field( &$field, &$posted_data ) {
        $should_validate = (bool) apply_filters( 'atbdp_add_listing_form_validation_logic', true, $field->get_props(), $posted_data );

        if ( self::should_ignore_category_custom_field( $field ) || self::should_ignore_conditional_logic_field( $field, $posted_data ) ) {
            $should_validate = false;
        }

        if ( ! $should_validate ) {
            return array(
                'is_valid' => true,
                'message'  => ''
            );
        }

        if ( $field->is_required() && self::is_field_submission_empty( $field, $posted_data ) ) {
            $field->add_error( __( 'This field is required.', 'directorist' ) );
        } elseif ( ! self::is_field_submission_empty( $field, $posted_data ) ) {
            $field->validate( $posted_data );

            if ( self::$from === 'api' && $field->type === 'file' ) {
                self::validate_file_field( $field, $posted_data );
            }
        }

        return array(
            'is_valid' => ! $field->has_error(),
            'message'  => $field->get_error()
        );
    }

    protected static function validate_file_field( $field, &$posted_data ) {
        $value = $field->get_value( $posted_data );

        if ( ! is_string( $value ) ) {
            $field->add_error( __( 'Invalid data type, string allowed only.', 'directorist' ) );

            return;
        }

        // Ignore when stored value matched with given value.
        if ( ! empty( $posted_data['listing_id'] ) ) {
            $stored_value = get_post_meta( (int) $posted_data['listing_id'], '_' . $field->field_key, true );

            if ( $stored_value && ( $pos = strpos( $stored_value, '|' ) ) !== false ) {
                $stored_value = substr( $stored_value, 0, $pos );
            }

            if ( $stored_value && ( $value === $stored_value ) ) {
                return;
            }
        }

        try {
            $upload_dir = wp_get_upload_dir();
            $temp_dir   = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' ) );
            $filepath   = $temp_dir . $value;

            if ( is_dir( $filepath ) || ! file_exists( $filepath ) ) {
                $field->add_error( __( 'Invalid file or file does not exist.', 'directorist' ) );
                return;
            }

            $file_type = $field->get_file_types();

            if ( in_array( $file_type, array( '', 'all_types', 'all' ), true ) ) {
                $file_types = directorist_get_supported_file_types();
            } else {
                $groups = directorist_get_supported_file_types_groups();

                if ( isset( $groups[ $file_type ] ) ) {
                    $file_types = $groups[ $file_type ];
                } else {
                    $file_types = (array) $file_type;
                }
            }

            $supported_mimes = array();
            foreach ( get_allowed_mime_types() as $ext => $mime ) {
                $_exts = explode( '|', $ext );
                $match = array_intersect( $file_types, $_exts );
                if ( count( $match ) ) {
                    $supported_mimes[ $ext ] = $mime;
                }
            }

            $mimetype = mime_content_type( $filepath );
            if ( ! in_array( $mimetype, $supported_mimes, true ) ) {
                $field->add_error( __( 'Invalid file type.', 'directorist' ) );
            }

            $size = filesize( $filepath );
            if ( $size > $field->get_file_size() ) {
                $field->add_error(
                    sprintf(
                        __( 'Uploaded file (%s) is larger than supported size (%s).', 'directorist' ),
                        size_format( $size ),
                        size_format( $field->get_file_size() )
                    ) 
                );
            }

        } catch ( Exception $e ) {

            error_log( $e->getMessage() );

        }
    }

    protected static function get_file_value( $field, &$posted_data ) {
        $value = $field->get_value( $posted_data );

        if ( ! $value ) {
            return;
        }

        if ( ! empty( $posted_data['listing_id'] ) ) {
            $stored_value = get_post_meta( (int) $posted_data['listing_id'], '_' . $field->field_key, true );

            if ( $stored_value && ( $pos = strpos( $stored_value, '|' ) ) !== false ) {
                $stored_value = substr( $stored_value, 0, $pos );
            }

            if ( $stored_value && ( $value === $stored_value ) ) {
                return $stored_value;
            }
        }

        try {
            $upload_dir = wp_get_upload_dir();
            $temp_dir   = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' ) );
            $filepath   = $temp_dir . $value;
            $target_dir = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( 'atbdp_temp' );

            // Clean old file
            if ( ! empty( $stored_value ) ) {
                $old_file = basename( $stored_value );
                if ( file_exists( $target_dir . $old_file ) ) {
                    unlink( $target_dir . $old_file );
                }
            }

            if ( ! file_exists( $filepath ) ) {
                return;
            }

            if ( file_exists( $target_dir . $value ) ) {
                $value = wp_unique_filename( $target_dir, $value );
            }

            rename( $filepath, $target_dir . $value );

            return trailingslashit( $upload_dir['baseurl'] ) . trailingslashit( 'atbdp_temp' ) . $value;

        } catch ( Exception $e ) {

            error_log( $e->getMessage() );

        }

        return;
    }

    protected static function process_locations( &$field, &$posted_data, &$data, &$error ) {
        if ( $field->is_value_empty( $posted_data ) ) {
            $data[ ATBDP_LOCATION ] = array();

            return;
        }

        $locations    = $field->get_value( $posted_data );
        $location_ids = array();
        $max_allowed  = (int) $field->max_location_creation;

        foreach ( $locations as $location ) {

            $location_id = (int) $location;

            if ( $location_id && term_exists( $location_id, ATBDP_LOCATION ) ) {
                $location_ids[] = $location_id;

                if ( $field->user_can_select_multiple() && ( $max_allowed > 0 ) && ( count( $location_ids ) >= $max_allowed ) ) {
                    break;
                }

                if ( ! $field->user_can_select_multiple() && count( $location_ids ) === 1 ) {
                    break;
                }

                continue;
            }

            if ( $field->user_can_create() ) {
                $location_added = wp_insert_term( $location, ATBDP_LOCATION );

                if ( is_wp_error( $location_added ) ) {
                    if ( $location_added->get_error_code() === 'term_exists' ) {
                        $location_ids[] = $location_added->get_error_data();
                    } else {
                        continue;
                    }
                } else {
                    $location_ids[] = (int) $location_added['term_id'];

                    directorist_update_location_directory( $location_added['term_id'], array( $posted_data['directory_id'] ) );
                }
            }

            if ( $field->user_can_select_multiple() && ( $max_allowed > 0 ) && ( count( $location_ids ) >= $max_allowed ) ) {
                break;
            }

            if ( ! $field->user_can_select_multiple() && count( $location_ids ) === 1 ) {
                break;
            }
        }

        if ( ! $field->user_can_select_multiple() && ! empty( $location_ids ) ) {
            $data[ ATBDP_LOCATION ] = array( $location_ids[0] );
        } else {
            $data[ ATBDP_LOCATION ] = $location_ids;
        }
    }

    protected static function process_categories( &$field, &$posted_data, &$data, &$error ) {
        if ( $field->is_value_empty( $posted_data ) ) {
            $data[ ATBDP_CATEGORY ] = array();

            return;
        }

        $categories    = $field->get_value( $posted_data );
        $category_ids = array();

        foreach ( $categories as $category ) {

            $category_id = (int) $category;

            if ( $category_id && term_exists( $category_id, ATBDP_CATEGORY ) ) {
                $category_ids[] = $category_id;

                if ( ! $field->user_can_select_multiple() && count( $category_ids ) === 1 ) {
                    break;
                }

                continue;
            }

            if ( $field->user_can_create() ) {
                $category_added = wp_insert_term( $category, ATBDP_CATEGORY );

                if ( is_wp_error( $category_added ) ) {
                    if ( $category_added->get_error_code() === 'term_exists' ) {
                        $category_ids[] = $category_added->get_error_data();
                    } else {
                        continue;
                    }
                } else {
                    $category_ids[] = $category_added['term_id'];

                    directorist_update_category_directory( $category_added['term_id'], array( $posted_data['directory_id'] ) );
                }
            }

            if ( ! $field->user_can_select_multiple() && count( $category_ids ) === 1 ) {
                break;
            }
        }

        if ( ! $field->user_can_select_multiple() && ! empty( $category_ids ) ) {
            $data[ ATBDP_CATEGORY ] = array( $category_ids[0] );
        } else {
            $data[ ATBDP_CATEGORY ] = $category_ids;
        }
    }

    protected static function process_tags( &$field, &$posted_data, &$data, &$error ) {
        if ( $field->is_value_empty( $posted_data ) ) {
            $data[ ATBDP_TAGS ] = array();

            return;
        }

        $tags    = $field->get_value( $posted_data );
        $tag_ids = array();

        foreach ( $tags as $tag ) {

            if ( $tag && ( $_tag = term_exists( $tag, ATBDP_TAGS ) ) ) {
                $tag_ids[] = (int) $_tag['term_id'];

                if ( ! $field->user_can_select_multiple() && count( $tag_ids ) === 1 ) {
                    break;
                }

                continue;
            }

            if ( $field->user_can_create() ) {
                $tag_added = wp_insert_term( $tag, ATBDP_TAGS );

                if ( is_wp_error( $tag_added ) ) {
                    if ( $tag_added->get_error_code() === 'term_exists' ) {
                        $tag_ids[] = $tag_added->get_error_data();
                    } else {
                        continue;
                    }
                } else {
                    $tag_ids[] = (int) $tag_added['term_id'];
                }
            }

            if ( ! $field->user_can_select_multiple() && count( $tag_ids ) === 1 ) {
                break;
            }
        }

        if ( ! $field->user_can_select_multiple() && ! empty( $tag_ids ) ) {
            $data[ ATBDP_TAGS ] = array( $tag_ids[0] );
        } else {
            $data[ ATBDP_TAGS ] = $tag_ids;
        }
    }

    protected static function process_pricing( &$field, &$posted_data, &$data, &$error ) {
        if ( $field->is_value_empty( $posted_data ) ) {
            $data['_atbd_listing_pricing'] = '';
            $data['_price']                = '';
            $data['_price_range']          = '';

            return;
        }

        $value = $field->get_value( $posted_data );

        if ( empty( $value['price_type'] ) || ( empty( $value['price'] ) && empty( $value['price_range'] ) ) ) {
            $data['_atbd_listing_pricing'] = '';
            $data['_price']                = '';
            $data['_price_range']          = '';

            return;
        }

        $data['_atbd_listing_pricing'] = $value['price_type'];

        if ( $value['price_type'] === 'range' ) {
            $data['_price_range'] = $value['price_range'];
            $data['_price']       = '';
        } else {
            $data['_price']       = $value['price'];
            $data['_price_range'] = '';
        }
    }

    protected static function process_map( &$field, &$posted_data, &$data, &$error ) {
        if ( $field->is_value_empty( $posted_data ) ) {
            $data['_hide_map']   = '';
            $data['_manual_lat'] = '';
            $data['_manual_lng'] = '';

            return;
        }

        $value = $field->get_value( $posted_data );

        if ( $value['hide_map'] ) {
            $data['_hide_map']   = $value['hide_map'];
            $data['_manual_lat'] = '';
            $data['_manual_lng'] = '';

            return;
        }

        $data['_hide_map']   = $value['hide_map'];
        $data['_manual_lat'] = $value['manual_lat'];
        $data['_manual_lng'] = $value['manual_lng'];
    }

    protected static function filter_empty_meta_data( $meta_data ) {
        return array_filter(
            $meta_data, static function( $value, $key ) {
                if ( $key === '_hide_contact_owner' && ! $value ) {
                    return false;
                }

                if ( is_array( $value ) ) {
                    return ! empty( $value );
                }

                if ( is_null( $value ) ) {
                    return false;
                }

                if ( is_string( $value ) && $value === '' ) {
                    return false;
                }

                if ( is_numeric( $value ) && $value == 0 ) {
                    return false;
                }

                return true;
            }, ARRAY_FILTER_USE_BOTH 
        );
    }

    protected static function reset_listing_taxonomy( $listing_id, $taxonomy_data = array() ) {
        $taxonomies = array( ATBDP_LOCATION, ATBDP_CATEGORY, ATBDP_TAGS );

        foreach ( $taxonomies as $taxonomy ) {
            if ( isset( $taxonomy_data[ $taxonomy ] ) && empty( $taxonomy_data[ $taxonomy ] ) ) {
                wp_set_object_terms( $listing_id, '', $taxonomy );
            }
        }
    }

    protected static function clean_empty_metadata( $listing_id, $meta_data, $meta_input ) {
        $deletable_meta_fields = array_keys( array_diff_key( $meta_data, $meta_input ) );

        foreach ( $deletable_meta_fields as $deletable_meta_field ) {
            delete_post_meta( $listing_id, $deletable_meta_field );
        }
    }

    protected static function maybe_get_listing_id( &$posted_data ) {
        $listing_id = absint( directorist_get_var( $posted_data['listing_id'], 0 ) );

        if ( ! $listing_id ) {
            return 0;
        }

        if ( $listing_id && ! directorist_is_listing_post_type( $listing_id ) ) {
            return new WP_Error(
                'directorist_invalid_listing',
                __( 'Invalid listing!', 'directorist' ),
                array( 'status' => 400 )
            );
        }

        if ( $listing_id && ! current_user_can( get_post_type_object( ATBDP_POST_TYPE )->cap->edit_post, $listing_id ) ) {
            return new WP_Error(
                'directorist_invalid_permission',
                __( 'You are not allowed to edit this listing.', 'directorist' ),
                array( 'status' => 403 )
            );
        }

        return $listing_id;
    }

    protected static function maybe_get_directory_id( &$posted_data ) {
        $maybe_directory_id = sanitize_text_field( directorist_get_var( $posted_data['directory_type'], '' ) );
        $directory          = get_term_by( ( is_numeric( $maybe_directory_id ) ? 'id' : 'slug' ), $maybe_directory_id, ATBDP_DIRECTORY_TYPE );

        if ( directorist_is_multi_directory_enabled() && ! $directory ) {
            return new WP_Error(
                'directorist_invalid_directory',
                __( 'Invalid directory id.', 'directorist' ),
                array( 'status' => 400 )
            );
        }

        if ( ! $directory ) {
            return directorist_get_default_directory();
        }

        return ( (int) $directory->term_id );
    }

    protected static function validate_terms_and_conditions( $directory_id, &$posted_data, $form_fields = [] ) {
        $error = new WP_Error();

        if ( ! empty( $form_fields['terms_privacy'] ) ) {
            $terms_privacy_field = Fields::create( $form_fields['terms_privacy'] );

            if ( self::should_ignore_conditional_logic_field( $terms_privacy_field, $posted_data ) ) {
                return true;
            }
        }

        if ( directorist_should_check_privacy_policy( $directory_id ) && empty( $posted_data['privacy_policy'] ) ) {
            $error->add(
                'directorist_invalid_field',
                __( 'Privacy policy is required.', 'directorist' ),
                array(
                    'status' => 400
                )
            );
        }

        if ( directorist_should_check_terms_and_condition( $directory_id ) && empty( $posted_data['t_c_check'] ) ) {
            $error->add(
                'directorist_invalid_field',
                __( 'Terms and condition is required.', 'directorist' ),
                array(
                    'status' => 400
                )
            );
        }

        if ( $error->has_errors() ) {
            return $error;
        }

        return true;
    }

    /**
     * Process form submission.
     *
     * @param  array $posted_data
     * @param  string $from Request coming form web or api.
     *
     * @return WP_Error|array
     */
    public static function submit( $posted_data, $from = 'web' ) {
        self::$from = $from;

        $listing_id = static::maybe_get_listing_id( $posted_data );
        if ( is_wp_error( $listing_id ) ) {
            return $listing_id;
        }

        $directory_id = static::maybe_get_directory_id( $posted_data );
        if ( is_wp_error( $directory_id ) ) {
            return $directory_id;
        }
        $posted_data['directory_id'] = $directory_id;

        /**
         * Process form fields.
         */
        $form_fields = directorist_get_listing_form_fields( $directory_id );

        $terms_conditions_check = static::validate_terms_and_conditions( $directory_id, $posted_data, $form_fields );
        if ( is_wp_error( $terms_conditions_check ) ) {
            return $terms_conditions_check;
        }

        static::cache_selected_categories( $directory_id, $posted_data );

        $error        = new WP_Error();
        $tax_data     = array();
        $meta_data    = array();
        $listing_data = array(
            'post_type' => ATBDP_POST_TYPE
        );

        foreach ( $form_fields as $form_field ) {
            $field = Fields::create( $form_field );

            // Ignore admin only fields when current user do not have that capability.
            if ( self::is_admin_only_field( $field ) ) {
                continue;
            }

            $result = self::validate_field( $field, $posted_data );

            if ( ! $result['is_valid'] ) {
                $error->add(
                    'directorist_invalid_field',
                    $result['message'],
                    array(
                        'field'  => $field->get_key(),
                        'label'  => $field->label,
                        'status' => 400
                    )
                );

                continue;
            }

            if ( self::should_ignore_category_custom_field( $field ) || self::should_ignore_conditional_logic_field( $field, $posted_data ) ) {
                continue;
            }

            switch ( $field->get_internal_key() ) {
                case 'title':
                    $listing_data['post_title'] = $field->sanitize( $posted_data );
                    break;

                case 'excerpt':
                    $listing_data['post_excerpt'] = $field->sanitize( $posted_data );
                    $meta_data['_excerpt']        = $field->sanitize( $posted_data );
                    break;

                case 'description':
                    $listing_data['post_content'] = $field->sanitize( $posted_data );
                    break;

                case 'location':
                    self::process_locations( $field, $posted_data, $tax_data, $error );
                    break;

                case 'category':
                    self::process_categories( $field, $posted_data, $tax_data, $error );
                    break;

                case 'tag':
                    self::process_tags( $field, $posted_data, $tax_data, $error );
                    break;

                case 'pricing':
                    self::process_pricing( $field, $posted_data, $meta_data, $error );
                    break;

                case 'map':
                    self::process_map( $field, $posted_data, $meta_data, $error );
                    break;

                case 'image_upload':
                    break;

                default:
                    $meta_data[ '_' . $field->get_key() ] = $field->sanitize( $posted_data );
            }

            // Exception from the web version.
            if ( self::$from === 'api' && $field->type === 'file' ) {
                $meta_data[ '_' . $field->get_key() ] = self::get_file_value( $field, $posted_data );
            }
        }

        if ( $error->has_errors() ) {
            return $error;
        }

        // Terms & conditions and privacy policy have been merged in v8.
        if ( ! empty( $posted_data['t_c_check'] ) || ! empty( $posted_data['privacy_policy'] ) ) {
            $meta_data['_t_c_check'] = true;
            $meta_data['_privacy_policy'] = true;
        }

        $listing_create_status = directorist_get_listing_create_status( $directory_id );
        $default_expiration    = directorist_get_default_expiration( $directory_id );
        $preview_enable        = directorist_is_preview_enabled( $directory_id );

        /**
         * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
         *
         * @param array $meta_data the array of meta keys and meta values
         */
        $meta_data = apply_filters( 'atbdp_listing_meta_user_submission', $meta_data );
        $meta_data = apply_filters( 'atbdp_ultimate_listing_meta_user_submission', $meta_data, $posted_data );

        $meta_input = self::filter_empty_meta_data( $meta_data );

        $listing_data['meta_input'] = $meta_input;
        $listing_data['tax_input']  = $tax_data;

        // Handle edit
        if ( $listing_id ) {
            /**
             * @since 5.4.0
             */
            do_action( 'atbdp_before_processing_to_update_listing' );

            $listing_data['ID']          = $listing_id;
            $listing_data['post_status'] = directorist_get_listing_edit_status( $directory_id, $listing_id );

            $listing_id = wp_update_post( $listing_data );

            if ( is_wp_error( $listing_id ) ) {
                return $listing_id;
            }

            self::reset_listing_taxonomy( $listing_id, $tax_data );
            directorist_set_listing_directory( $listing_id, $directory_id );

            // Clean empty meta data.
            self::clean_empty_metadata( $listing_id, $meta_data, $meta_input );

            do_action( 'atbdp_listing_updated', $listing_id );
        } else {
            $listing_data['post_status'] = $listing_create_status;

            $listing_id = wp_insert_post( $listing_data );

            if ( is_wp_error( $listing_id ) ) {
                return $listing_id;
            }

            directorist_set_listing_directory( $listing_id, $directory_id );

            do_action( 'atbdp_listing_inserted', $listing_id ); // for sending email notification

            // Every post with the published status should contain all the post meta keys so that we can include them in query.
            if ( 'publish' === $listing_create_status || 'pending' === $listing_create_status ) {
                if ( ! $default_expiration ) {
                    update_post_meta( $listing_id, '_never_expire', 1 );
                } else {
                    $expiration_date = calc_listing_expiry_date( '', $default_expiration );
                    update_post_meta( $listing_id, '_expiry_date', $expiration_date );
                }

                update_post_meta( $listing_id, '_featured', 0 );
                // TODO: Status has been migrated, remove related code.
                update_post_meta( $listing_id, '_listing_status', 'post_status' );

                /*
                 * It fires before processing a listing from the front end
                 * @param array $_POST the array containing the submitted fee data.
                 * */
                do_action( 'atbdp_before_processing_listing_frontend', $listing_id );
            }

            if ( 'publish' === $listing_create_status ) {
                do_action( 'atbdp_listing_published', $listing_id );// for sending email notification
            }
        }

        do_action( 'atbdp_after_created_listing', $listing_id );

        // handling media files
        self::upload_images( $listing_id, $posted_data );

        $response = array(
            'id' => $listing_id
        );

        $permalink = get_permalink( $listing_id );

        $response['redirect_url'] = $permalink;

        if ( (bool) get_directorist_option( 'submission_confirmation', 1 ) ) {
            $data['redirect_url'] = add_query_arg( 'notice', true, $response['redirect_url'] );
        }

        $is_listing_featured = ( ! empty( $posted_data['listing_type'] ) && ( 'featured' === $posted_data['listing_type'] ) );
        $should_monetize     = ( directorist_is_monetization_enabled() && directorist_is_featured_listing_enabled() && $is_listing_featured );

        if ( $should_monetize && ! is_fee_manager_active() ) {
            $payment_status            = Helper::get_listing_payment_status( $listing_id );
            $rejectable_payment_status = array( 'failed', 'cancelled', 'refunded' );

            if ( empty( $payment_status ) || in_array( $payment_status, $rejectable_payment_status, true ) ) {
                $response['redirect_url'] = ATBDP_Permalink::get_checkout_page_link( $listing_id );
                $response['need_payment'] = true;

                wp_update_post(
                    array(
                        'ID'          => $listing_id,
                        'post_status' => 'pending',
                    ) 
                );
            }
        }

        $response['success'] = true;
        $response['success_msg'] = __( 'Your listing submission is completed! Redirecting...', 'directorist' );
        $response['preview_url'] = $permalink;

        if ( ! empty( $response['need_payment'] ) && $response['need_payment'] === true ) {
            $response['success_msg'] = __( 'Payment required! Redirecting to checkout...', 'directorist' );
        }

        $data['preview_mode'] = $preview_enable;

        if ( ! empty( $posted_data['listing_id'] ) ) {
            $response['edited_listing'] = true;
        }

        if ( ! empty( $posted_data['preview_url'] ) ) {
            $response['preview_url'] = Helper::escape_query_strings_from_url( $posted_data['preview_url'] );
        }

        if ( ! empty( $posted_data['redirect_url'] ) ) {
            $response['redirect_url'] = Helper::escape_query_strings_from_url( $posted_data['redirect_url'] );
        }

        return apply_filters( 'atbdp_listing_form_submission_info', $response );
    }

    public static function upload_images( $listing_id, $posted_data ) {
        $image_upload_field = directorist_get_listing_form_field( $posted_data['directory_id'], 'image_upload' );

        if ( empty( $image_upload_field ) ) {
            return;
        }

        $selected_images = Fields::create( $image_upload_field )->get_value( $posted_data );

        if ( is_null( $selected_images ) ) {
            // Cleanup listing meta when images field is empty.
            delete_post_thumbnail( $listing_id );
            delete_post_meta( $listing_id, '_listing_img' );
            delete_post_meta( $listing_id, '_listing_prv_img' );

            return;
        }

        $old_images = $selected_images['old'];
        $new_images = $selected_images['new'];

        self::clean_unselected_images( $listing_id, $old_images );

        if ( empty( $old_images ) && empty( $new_images ) ) {
            return;
        }

        try {
            $upload_dir                    = wp_get_upload_dir();
            $temp_dir                      = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' ) );
            $target_dir                    = trailingslashit( $upload_dir['path'] );
            $uploaded_images               = $old_images;
            $background_processable_images = array();

            foreach ( $new_images as $image ) {
                if ( empty( $image ) ) {
                    continue;
                }

                $filepath = $temp_dir . $image;

                if ( is_dir( $filepath ) || ! file_exists( $filepath ) ) {
                    continue;
                }

                if ( file_exists( $target_dir . $image ) ) {
                    $image = wp_unique_filename( $target_dir, $image );
                }

                rename( $filepath, $target_dir . $image );

                $mime = wp_check_filetype( $image );
                $name = wp_basename( $image, ".{$mime['ext']}" );

                // Construct the attachment array.
                $attachment = array(
                    'post_mime_type' => $mime['type'],
                    'guid'           => trailingslashit( $upload_dir['url'] ) . $image,
                    'post_parent'    => $listing_id,
                    'post_title'     => sanitize_text_field( $name ),
                );

                $attachment_id = wp_insert_attachment( $attachment, $target_dir . $image, $listing_id, false );

                if ( is_wp_error( $attachment_id ) ) {
                    throw new Exception( $attachment_id->get_error_message() );

                    continue;
                }

                $background_processable_images[ $attachment_id ] = $target_dir . $image;

                $uploaded_images[] = $attachment_id;
            }

            if ( ! empty( $uploaded_images ) ) {
                update_post_meta( $listing_id, '_listing_prv_img', $uploaded_images[0] );
                set_post_thumbnail( $listing_id, $uploaded_images[0] );

                unset( $uploaded_images[0] );

                if ( count( $uploaded_images ) ) {
                    update_post_meta( $listing_id, '_listing_img', $uploaded_images );
                }

                directorist_background_image_process( $background_processable_images );
            }

        } catch ( Exception $e ) {

            error_log( $e->getMessage() );

        }
    }

    protected static function clean_unselected_images( $listing_id, $selected_images ) {
        $saved_images = atbdp_get_listing_attachment_ids( $listing_id );
        if ( empty( $saved_images ) ) {
            return;
        }

        $unselected_images = array_diff( $saved_images, $selected_images );
        if ( empty( $unselected_images ) ) {
            return;
        }

        foreach ( $unselected_images as $unselected_image ) {
            wp_delete_attachment( $unselected_image, true );
        }
    }
}
