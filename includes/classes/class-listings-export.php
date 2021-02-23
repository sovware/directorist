<?php
namespace Directorist;
class Listings_Export {
    
    public function __construct() {
        # code...`
    }

    public static function get_listings_data() {
        $listings_data = [];

        $listings = new \WP_Query([
            'post_type'      => ATBDP_POST_TYPE,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        $field_map = [
            'native_field' => [
                'verify'      => 'verifyNativeField',
                'update_data' => 'updateNativeFieldData',
            ],
            'taxonomy_field' => [
                'verify'      => 'verifyTaxonomyField',
                'update_data' => 'updateTaxonomyFieldData',
            ],
            'listing_image_module_field' => [
                'verify'      => 'verifyListingImageModuleField',
                'update_data' => 'updateListingImageModuleFieldsData',
            ],
            'price_module_field' => [
                'verify'      => 'verifyPriceModuleField',
                'update_data' => 'updatePriceModuleFieldData',
            ],
            'map_module_field' => [
                'verify'      => 'verifyMapModuleField',
                'update_data' => 'updateMapModuleFieldData',
            ],
            'meta_key_field' => [
                'verify'      => 'verifyMetaKeyField',
                'update_data' => 'updateMetaKeyFieldData',
            ],
        ];

        if ( $listings->have_posts() ) {
            while ( $listings->have_posts() ) {
                $listings->the_post();
                
                $row = [];
                $row['id'] = get_the_ID();
                $row['directory_type'] = self::get_directory_slug_by_id( get_the_id() );

                $directory_type_id = get_post_meta( get_the_ID(), '_directory_type', true );
                $submission_form   = get_term_meta( $directory_type_id, 'submission_form_fields', true );

                if ( 'array' === gettype( $submission_form ) && ! empty( $submission_form['fields'] ) ) {
                    foreach ( $submission_form['fields'] as $field_key => $field_args ) {
                        foreach ( $field_map as $field_map_key => $field_map_args ) {
                            $verify      = $field_map_args[ 'verify' ];
                            $update_data = $field_map_args[ 'update_data' ];
    
                            if ( self::$verify( $field_args ) ) {
                                $row = self::$update_data( $row, $field_key, $field_args );
                                $row = apply_filters( 'directorist_listings_export_submission_form_fields_row', $row, $field_key, $field_args, $field_map_key );
                                break;
                            }
                        }
                    }
                }
                

                $row = apply_filters( 'directorist_listings_export_row', $row );
                array_push( $listings_data, $row );
            }

            // die;
        }

        return $listings_data;
    }


    // ================[ Submission Form Fields Helper ]================
    // verifyNativeField
    public static function verifyNativeField( array $args = [] ) {
        if ( empty( $args['widget_group'] ) ) { return false; }
        if ( empty( $args['widget_name'] ) ) { return false; }
        if ( empty( $args['field_key'] ) ) { return false; }
        if ( 'preset' !== $args['widget_group'] ) { return false; }

        $native_fields = [ 'listing_title', 'listing_content' ];

        if ( ! in_array( $args['field_key'], $native_fields ) ) { return false; }

        return true;
    }

    // updateNativeFieldData
    public static function updateNativeFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $field_data_map = [
            'listing_title'   => 'get_the_title',
            'listing_content' => 'get_the_content',
        ];

        $field_key = $field_args['field_key'];
        $row[ $field_key ] = call_user_func( $field_data_map[ $field_key ] );

        return $row;
    }

    // verifyTaxonomyField
    public static function verifyTaxonomyField( array $args = [] ) {
        if ( empty( $args['widget_group'] ) ) { return false; }
        if ( empty( $args['widget_name'] ) ) { return false; }
        if ( empty( $args['field_key'] ) ) { return false; }
        if ( 'preset' !== $args['widget_group'] ) { return false; }

        $taxonomy = [ 'category', 'location', 'tag' ];

        if ( ! in_array( $args['widget_name'], $taxonomy ) ) { return false; }
        
        return true;
    }

    // updateTaxonomyFieldData
    public static function updateTaxonomyFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $term_map = [
            'category' => ATBDP_CATEGORY,
            'location' => ATBDP_LOCATION,
            'tag'      => ATBDP_TAGS,
        ];
        
        $row[ $field_key ] = self::get_term_names( get_the_ID(), $term_map[ $field_args['widget_name'] ] );

        return $row;
    }

    // verifyListingImageModuleField
    public static function verifyListingImageModuleField( array $args = [] ) {
        if ( empty( $args['widget_group'] ) ) { return false; }
        if ( empty( $args['widget_name'] ) ) { return false; }
        if ( empty( $args['field_key'] ) ) { return false; }
        if ( 'preset' !== $args['widget_group'] ) { return false; }
        if ( 'listing_img' !== $args['field_key'] ) { return false; }
        
        return true;
    }

    // updateListingImageModuleFieldsData
    public static function updateListingImageModuleFieldsData( array $row = [], string $field_key = '', array $field_args = [] ) {
        
        $image_urls          = [];
        $_listing_prv_img_id = get_post_meta( get_the_ID(), '_listing_prv_img', true );
        $_listing_img_id     = get_post_meta( get_the_ID(), '_listing_img', true );

        if ( empty( $_listing_prv_img_id ) && empty( $_listing_img_id ) ) {
            return $row;
        }

        if ( ! empty( $_listing_prv_img_id ) ) {
            $preview_image_url = wp_get_attachment_image_url( $_listing_prv_img_id, 'full' );
            $image_urls[] = $preview_image_url;
        }

        if ( ! empty( $_listing_img_id ) && is_array( $_listing_img_id ) ) {
            foreach ( $_listing_img_id as $_img_id ) {

                if ( $_img_id === $_listing_prv_img_id ) { continue; }

                $image_url = wp_get_attachment_image_url( $_img_id, 'full' );
                $image_urls[] = $image_url;
            }
        }

        $image_urls = implode( ',', $image_urls );
        $row[ $field_args['field_key'] ] = $image_urls;

        return $row;
    }

    // verifyMetaKeyField
    public static function verifyMetaKeyField( array $args = [] ) {
        if ( empty( $args['widget_group'] ) ) { return false; }
        if ( empty( $args['widget_name'] ) ) { return false; }
        if ( empty( $args['field_key'] ) ) { return false; }
        
        return true;
    }

    // updateMetaKeyFieldData
    public static function updateMetaKeyFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $value = get_post_meta( get_the_id(), '_' . $field_args['field_key'], true );
        $row[ $field_args['field_key'] ] = $value;

        return $row;
    }

    // verifyPriceModuleField
    public static function verifyPriceModuleField( array $args = [] ) {
        if ( empty( $args['widget_group'] ) ) { return false; }
        if ( empty( $args['widget_name'] ) ) { return false; }
        if ( 'pricing' !== $args['widget_name'] ) { return false; }
        
        return true;
    }

    // updatePriceModuleFieldData
    public static function updatePriceModuleFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $row[ 'price' ] = get_post_meta( get_the_id(), '_price', true );
        $row[ 'price_range' ] = get_post_meta( get_the_id(), '_price_range', true );
        $row[ 'atbd_listing_pricing' ] = get_post_meta( get_the_id(), '_atbd_listing_pricing', true );

        return $row;
    }


    // verifyMapModuleField
    public static function verifyMapModuleField( array $args = [] ) {
        if ( empty( $args['widget_group'] ) ) { return false; }
        if ( empty( $args['widget_name'] ) ) { return false; }
        if ( 'map' !== $args['widget_name'] ) { return false; }
        
        return true;
    }

    // updateMapModuleFieldData
    public static function updateMapModuleFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $row[ 'hide_map' ] = get_post_meta( get_the_id(), '_hide_map', true );
        $row[ 'manual_lat' ] = get_post_meta( get_the_id(), '_manual_lat', true );
        $row[ 'manual_lng' ] = get_post_meta( get_the_id(), '_manual_lng', true );

        return $row;
    }

    // ================[ Submission Form Fields Helper : End ]================

    // get_directory_slug_by_id
    public static function get_directory_slug_by_id( $id = 0 ) {
        $directory_type_id   = get_post_meta( $id, '_directory_type', true );
        $directory_type      = ( ! empty( $directory_type_id ) ) ? get_term_by( 'id', $directory_type_id, ATBDP_DIRECTORY_TYPE ) : '';
        $directory_type_slug = ( ! empty( $directory_type ) && is_object( $directory_type ) ) ? $directory_type->slug : '';

        return $directory_type_slug;
    }

    // get_term_names
    public static function get_term_names( $post_id = 0, $taxonomy = '' ) {
        // $term_names = [];
        $term_name = '';
        $terms = get_the_terms( $post_id, $taxonomy );

        if ( ! empty( $terms ) ) {
            $term_name = $terms[0]->name;
            // foreach ( $terms as $term ) {
            //     array_push( $term_names, $term->name );
            // }
        }

        // $term_names = ( ! empty( $term_names ) ) ? join( ',', $term_names ) : '';

        return $term_name;
    }

}