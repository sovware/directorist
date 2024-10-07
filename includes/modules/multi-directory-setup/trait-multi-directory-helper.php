<?php

namespace Directorist\Multi_Directory;

use Directorist\Helper;

trait Multi_Directory_Helper {

    public static function add_directory( array $args = [] ) {
        $default = [
            'term_id'        => 0,
            'directory_name' => '',
            'fields_value'   => [],
            'is_json'        => false
        ];
        $args = array_merge( $default, $args );

        $has_term_id = false;
        if ( ! empty( $args['term_id'] ) ) {
            $has_term_id = true;
        }

        if ( $has_term_id && ! is_numeric( $args['term_id'] ) ) {
            $has_term_id = false;
        }

        if ( $has_term_id && $args['term_id'] < 1 ) {
            $has_term_id = false;
        }

        $create_directory = [ 'term_id' => 0 ];

        if ( ! $has_term_id ) {
            $create_directory = self::create_directory([
                'directory_name' => $args['directory_name']
            ]);

            if ( ! $create_directory['status']['success'] ) {
                return $create_directory;
            }
        }

        $update_directory = self::update_directory([
            'term_id'        => ( ! $has_term_id ) ? ( int ) $create_directory['term_id'] : ( int ) $args['term_id'],
            'directory_name' => $args['directory_name'],
            'fields_value'   => $args['fields_value'],
            'is_json'        => $args['is_json'],
        ]);


        if ( ! empty( $update_directory['status']['status_log']['term_updated'] ) && ! empty( $create_directory['status']['status_log']['term_created'] ) ) {
            $update_directory['status']['status_log']['term_created'] = $create_directory['status']['status_log']['term_created'];

            unset( $update_directory['status']['status_log']['term_updated'] );
        }

        return $update_directory;
    }

    // create_directory
    public static function create_directory( array $args = [] ) {
        $default = [ 'directory_name' => '' ];
        $args    = array_merge( $default, $args );

        $response = [
            'status' => [
                'success'     => true,
                'status_log'  => [],
                'error_count' => 0,
            ]
        ];

        $directory_name = esc_attr( $args['directory_name'] );

        // Validate name
        if ( empty( $directory_name ) ) {
            $response['status']['status_log']['name_is_missing'] = [
                'type'    => 'error',
                'message' => __( 'Name is missing', 'directorist' ),
            ];

            $response['status']['error_count']++;
        }

        // Validate term name
        if ( ! empty( $directory_name ) && term_exists( $directory_name, 'atbdp_listing_types' ) ) {
            $response['status']['status_log']['term_exists'] = [
                'type'    => 'error',
                'message' => __( 'The name already exists', 'directorist' ),
            ];

            $response['status']['error_count']++;
        }

        // Return status
        if ( $response['status']['error_count'] ) {
            $response['status']['success'] = false;
            return $response;
        }

        do_action( 'directorist_before_create_directory_type', $directory_name );

        // Create the directory
        $term = wp_insert_term( $directory_name, 'atbdp_listing_types');

        if ( is_wp_error( $term ) ) {
            $response['status']['status_log']['term_exists'] = [
                'type'    => 'error',
                'message' => __( 'The name already exists', 'directorist' ),
            ];

            $response['status']['error_count']++;
        }


        if ( $response['status']['error_count'] ) {
            $response['status']['success'] = false;
            return $response;
        }

        do_action( 'directorist_after_create_directory_type', $term );

        $response['term_id'] = ( int ) $term['term_id'];

        $response['status']['status_log']['term_created'] = [
            'type'    => 'success',
            'message' => __( 'The directory has been created successfully', 'directorist' ),
        ];
        update_term_meta( $response['term_id'], '_created_date', time() );
        return $response;
    }

    // update_directory
    public static function update_directory( array $args = [] ) {
        $default = [
            'directory_name' => '',
            'term_id'        => 0,
            'fields_value'   => [],
            'is_json'        => false
        ];
        $args = array_merge( $default, $args );

        $response = [
            'status' => [
                'success'     => true,
                'status_log'  => [],
                'error_count' => 0,
            ]
        ];

        $response['term_id'] = $args['term_id'];

        // Validation
        if ( $args['is_json'] ) {
            $args['fields_value'] = json_decode( $args['fields_value'], true );
        }

        // Validate data
        $has_invalid_data = false;

        if ( is_null( $args['fields_value'] ) ) {
            $has_invalid_data = true;
        }

        if ( ! is_array( $args['fields_value'] ) ) {
            $has_invalid_data = true;
        }

        if ( $has_invalid_data ) {
            $response['status']['status_log']['invalid_data'] = [
                'type' => 'error',
                'message' => __( 'The data is invalid', 'directorist' ),
            ];

            $response['status']['error_count']++;
        }

        // Validate term id
        $has_invalid_term_id = false;

        if ( empty( $args['term_id'] ) ) {
            $has_invalid_term_id = true;
        }

        if ( ! is_numeric( $args['term_id'] ) ) {
            $has_invalid_term_id = true;
        }

        $term_id = $args['term_id'];
        if ( is_numeric( $term_id ) ) {
            $args['term_id'] = ( int ) $term_id;
        }

        // Validate term id
        if ( ! term_exists( $args['term_id'], 'atbdp_listing_types' ) ) {
            $has_invalid_term_id = true;
        }

        if ( $has_invalid_term_id ) {
            $response['status']['status_log']['invalid_term_id'] = [
                'type'    => 'error',
                'message' => __( 'Invalid term ID', 'directorist' ),
            ];

            $response['status']['error_count']++;
            $response['status']['success'] = false;

            return $response;
        }

        do_action( 'directorist_before_update_directory_type', (int) $term_id );

        $fields = $args['fields_value'];

        if ( is_array( $fields ) ) {
            foreach ( $fields as $_field_key => $_field_value ) {
                $fields[ $_field_key ] = Helper::maybe_json( $_field_value );
            }
        }

        $fields = apply_filters( 'cptm_fields_before_update', $fields );

        $directory_name = ( ! empty( $fields['name'] ) ) ? $fields['name'] : '';
        $directory_name = ( ! empty( $args['directory_name'] ) ) ? $args['directory_name'] : $directory_name;
        $directory_name = esc_attr( $directory_name );

        $response['fields_value']   = $fields;
        $response['directory_name'] = $args['directory_name'];

        unset( $fields['name'] );

        $term = get_term( $args['term_id'], ATBDP_DIRECTORY_TYPE );
        $old_name = $term->name;

        $has_diffrent_name = $old_name !== $directory_name;

        if ( $has_diffrent_name && term_exists( $directory_name, 'atbdp_listing_types' ) ) {
            $response['status']['status_log']['name_exists'] = [
                'type'    => 'error',
                'message' => __( 'The name already exists', 'directorist' ),
            ];

            $response['status']['error_count']++;
        }

        // Return status
        if ( $response['status']['error_count'] ) {
            $response['status']['success'] = false;
            return $response;
        }

        // Update name if exist
        if ( ! empty( $directory_name ) ) {
            wp_update_term( $args['term_id'], 'atbdp_listing_types', ['name' => $directory_name] );
        }

        // Update the value
        foreach ( $fields as $key => $value ) {
            self::update_validated_term_meta( $args['term_id'], $key, $value );
        }

        $response['status']['status_log']['term_updated'] = [
            'type'    => 'success',
            'message' => __( 'The directory has been updated successfully', 'directorist' ),
        ];

        do_action( 'directorist_after_update_directory_type', (int) $term_id );

        return $response;
    }

    // maybe_serialize
    public function maybe_serialize( $value = '' ) {
        return maybe_serialize( Helper::maybe_json( $value ) );
    }
}