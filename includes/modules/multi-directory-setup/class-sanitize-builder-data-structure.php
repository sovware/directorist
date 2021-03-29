<?php
namespace Directorist;

class Builder_Data_Sanitizer {

    // sanitize_builder_data_structure
    public function sanitize_builder_data_structure() {
        $directory_types = get_terms([
            'taxonomy'   => ATBDP_DIRECTORY_TYPE,
            'hide_empty' => false,
        ]);

        if ( empty( $directory_types ) ) { return; }

        foreach ( $directory_types as $directory_type ) {
            $this->sanitize_submission_form_fields_data_structure( $directory_type );
            $this->sanitize_single_listings_contents_data_structure( $directory_type );
            $this->sanitize_search_form_fields_data_structure( $directory_type );
            $this->sanitize_listings_card_grid_view_data_structure( $directory_type );
            $this->sanitize_listings_card_list_view_data_structure( $directory_type );
        }
    }

    // sanitize_submission_form_fields_data_structure
    public function sanitize_submission_form_fields_data_structure( $directory_type = null ) {
        if ( ! self::is_valid_term_object( $directory_type ) ) { return; }

        $submission_form_fields = get_term_meta( $directory_type->term_id, 'submission_form_fields', true );
        $submission_form_fields = self::get_sanitized_form_builder_data( $submission_form_fields );
        
        update_term_meta( $directory_type->term_id, 'submission_form_fields', $submission_form_fields);
    }

    // sanitize_single_listings_contents_data_structure
    public function sanitize_single_listings_contents_data_structure( $directory_type = null ) {
        if ( ! self::is_valid_term_object( $directory_type ) ) { return; }

        $referable_field_keys     = self::get_referable_field_keys( $directory_type->term_id, 'submission_form_fields' );
        $single_listings_contents = get_term_meta( $directory_type->term_id, 'single_listings_contents', true );
        $single_listings_contents = self::get_sanitized_form_builder_data( $single_listings_contents, $referable_field_keys );

        update_term_meta( $directory_type->term_id, 'single_listings_contents', $single_listings_contents);
    }

    // sanitize_search_form_fields_data_structure
    public function sanitize_search_form_fields_data_structure( $directory_type = null ) {
        if ( ! self::is_valid_term_object( $directory_type ) ) { return; }

        $referable_field_keys = self::get_referable_field_keys( $directory_type->term_id, 'submission_form_fields' );
        $search_form_fields   = get_term_meta( $directory_type->term_id, 'search_form_fields', true );
        $search_form_fields   = self::get_sanitized_form_builder_data( $search_form_fields, $referable_field_keys );

        update_term_meta( $directory_type->term_id, 'search_form_fields', $search_form_fields);
    }

    // get_sanitized_form_builder_data
    public static function get_sanitized_form_builder_data( $form_builder_data = [], $referable_field_keys = [] ) {
        if ( empty( $form_builder_data ) ) { return $form_builder_data; }
        if ( empty( $form_builder_data['fields'] ) ) { return $form_builder_data; }

        foreach ( $form_builder_data['fields'] as $field_key => $field_args ) {

            if ( ! is_array( $field_args ) ) {
                $form_builder_data['fields'][$field_key] = [];
            }

            $widget_name = ( isset( $field_args['widget_name'] ) ) ? $field_args['widget_name'] : '';
            $widget_name = self::sanitize_widget_name( $widget_name );

            if ( ! empty( $referable_field_keys ) && is_array( $referable_field_keys ) && in_array( $widget_name, $referable_field_keys ) ) {
                $form_builder_data['fields'][$field_key][ 'original_widget_key' ] = $field_key;
            }

            $form_builder_data['fields'][$field_key][ 'widget_name' ] = $widget_name;
            $form_builder_data['fields'][$field_key][ 'widget_key' ] = $field_key;
        }

        return $form_builder_data;
    }

    // sanitize_listings_card_grid_view_data_structure
    public function sanitize_listings_card_grid_view_data_structure( $directory_type = null ) {
        if ( ! self::is_valid_term_object( $directory_type ) ) { return; }

        $referable_field_keys    = self::get_referable_field_keys( $directory_type->term_id, 'submission_form_fields' );
        $listings_card_grid_view = get_term_meta( $directory_type->term_id, 'listings_card_grid_view', true );
        $listings_card_grid_view = self::get_sanitized_card_builder_data( $listings_card_grid_view, $referable_field_keys );

        update_term_meta( $directory_type->term_id, 'listings_card_grid_view', $listings_card_grid_view);
    }

    // sanitize_listings_card_list_view_data_structure
    public function sanitize_listings_card_list_view_data_structure( $directory_type = null ) {
        if ( ! self::is_valid_term_object( $directory_type ) ) { return; }

        $referable_field_keys    = self::get_referable_field_keys( $directory_type->term_id, 'submission_form_fields' );
        $listings_card_list_view = get_term_meta( $directory_type->term_id, 'listings_card_list_view', true );
        $listings_card_list_view = self::get_sanitized_card_builder_data( $listings_card_list_view, $referable_field_keys );

        update_term_meta( $directory_type->term_id, 'listings_card_list_view', $listings_card_list_view);
    }

    public static function get_sanitized_card_builder_data( $card_builder_data = [], $referable_field_keys = [] ) {
        if ( empty( $card_builder_data ) ) { return $card_builder_data; }
        if ( ! is_array( $card_builder_data ) ) { return $card_builder_data; }
        if ( ! isset( $card_builder_data['template_data'] ) ) { return $card_builder_data; }

        foreach ( $card_builder_data['template_data'] as $template_key => $template_args ) {
            if ( ! is_array( $template_args ) ) { continue; }
            $active_widget_keys = [];

            foreach ( $template_args as $layout_key => $layout_args ) {
                if ( ! is_array( $layout_args ) ) { continue; }
                foreach ( $layout_args as $area_key => $area_args ) {
                    if ( ! is_array( $area_args ) ) { continue; }
                    foreach ( $area_args as $_widget_key => $_widget_args ) {

                        if ( ! is_array( $_widget_args ) ) {
                            $card_builder_data['template_data'][ $template_key ][ $layout_key ][ $area_key ][ $_widget_key ] = [];
                        }

                        $widget_name = ( isset( $_widget_args[ 'widget_name' ] ) ) ? $_widget_args[ 'widget_name' ] : '';
                        $widget_name = self::sanitize_widget_name( $widget_name );
                        $widget_key  = ( isset( $_widget_args[ 'widget_key' ] ) ) ? $_widget_args[ 'widget_key' ] : '';
                        
                        if ( isset( $_widget_args[ 'original_widget_key' ] ) ) {
                            $widget_key = $_widget_args[ 'original_widget_key' ];
                            $card_builder_data['template_data'][ $template_key ][ $layout_key ][ $area_key ][ $_widget_key ][ 'widget_key' ] = $widget_key;
                        }

                        if ( in_array( $widget_key, $active_widget_keys ) ) {
                            unset( $card_builder_data['template_data'][ $template_key ][ $layout_key ][ $area_key ][ $_widget_key ] );
                        }

                        $active_widget_keys[] = $widget_key;

                        if ( ! empty( $referable_field_keys ) && is_array( $referable_field_keys ) && in_array( $widget_name, $referable_field_keys ) ) {
                            $card_builder_data['template_data'][ $template_key ][ $layout_key ][ $area_key ][ $_widget_key ][ 'original_widget_key' ] = $widget_key;
                        }
    
                        $card_builder_data['template_data'][ $template_key ][ $layout_key ][ $area_key ][ $_widget_key ][ 'widget_name' ] = $widget_name;
                    }
                }
            }
        }

        return $card_builder_data;
    }

    // get_referable_field_keys
    public static function get_referable_field_keys( $directory_type_id = 0, $referable_fields_metakey = '' ) {
        $referable_field_keys = [];
        $fields_data = get_term_meta( $directory_type_id, $referable_fields_metakey, true );

        if ( is_array( $fields_data ) && isset( $fields_data['fields'] ) ) {
            foreach ( $fields_data['fields'] as $field_key => $field_args ) {
                if ( ! isset( $field_args['widget_name'] ) ) { continue; }
                $referable_field_keys[] = $field_args['widget_name'];
                
            }
        }

        return $referable_field_keys;
    }

    // is_valid_term_object
    public static function is_valid_term_object( $term = [] ) {
        if ( empty( $term ) ) { return false; }
        if ( ! is_object( $term ) ) { return false; }
        if ( ! isset( $term->term_id ) ) { return false; }

        return true;
    }

    // sanitize_widget_name
    public static function sanitize_widget_name( $widget_name = '' ) {
        if ( ! is_string( $widget_name ) ) { return ''; }
        
        return preg_replace( '/_.*$/', '', $widget_name );
    }
}