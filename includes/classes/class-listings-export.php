<?php
namespace Directorist;
class Listings_Exporter {

    // get_prepared_listings_export_file
    public static function get_prepared_listings_export_file() {
        $filename      = "listings-export-data";
        $file_name     = "{$filename}.csv";
        $file_contents = self::get_listings_data_as_csv_content();

        $old_file_id = get_directorist_option( 'directorist_export_attachent_id', '', true );
        if ( ! empty( $old_file_id ) ) {
            wp_delete_attachment( $old_file_id, true );
        }

        $upload_dir = wp_upload_dir();

        if ( wp_mkdir_p( $upload_dir['path'] ) ) {
            $file = $upload_dir['path'] . '/' . $file_name;
        } else {
            $file = $upload_dir['basedir'] . '/' . $file_name;
        }

        file_put_contents( $file, $file_contents );

        $wp_filetype = wp_check_filetype( $file_name, null );
        $attachment  = [
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];

        $attach_id  = wp_insert_attachment( $attachment, $file );
        $attach_url = wp_get_attachment_url( $attach_id );

        update_directorist_option( 'directorist_export_attachent_id', $attach_id );

        return [ 'success' => true, 'file_url' => $attach_url];
    }

    // get_listings_data_as_csv_content
    public static function get_listings_data_as_csv_content() {
        $contents = '';

        $listings_data = self::get_listings_data();

        if ( empty( $listings_data ) ) {
            return $contents;
        }

        $handle = fopen( 'php://temp', 'r+' );

        if ( false === $handle ) {
            return $contents;
        }

        foreach ( $listings_data as $index => $row ) {
            if ( $index === 0 ) {
                fputcsv( $handle, array_keys( $row ), ',', '"', '\\' );
            }

            fputcsv( $handle, array_map( [ __CLASS__, 'prepareCsvCellValue' ], $row ), ',', '"', '\\' );
        }

        rewind( $handle );
        $contents = stream_get_contents( $handle );
        fclose( $handle );

        return false === $contents ? '' : $contents;
    }

    // get_listings_data
    public static function get_listings_data() {
        $listings_data = [];

        $listings = new \WP_Query(
            apply_filters(
                'directorist_listings_export_query' , [
                    'post_type'      => ATBDP_POST_TYPE,
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                ]
            )
        );

        $field_map = [
            'native_field'               => [
                'verify'      => 'verifyNativeField',
                'update_data' => 'updateNativeFieldData',
            ],
            'taxonomy_field'             => [
                'verify'      => 'verifyTaxonomyField',
                'update_data' => 'updateTaxonomyFieldData',
            ],
            'listing_image_module_field' => [
                'verify'      => 'verifyListingImageModuleField',
                'update_data' => 'updateListingImageModuleFieldsData',
            ],
            'price_module_field'         => [
                'verify'      => 'verifyPriceModuleField',
                'update_data' => 'updatePriceModuleFieldData',
            ],
            'map_module_field'           => [
                'verify'      => 'verifyMapModuleField',
                'update_data' => 'updateMapModuleFieldData',
            ],
            'meta_key_field'             => [
                'verify'      => 'verifyMetaKeyField',
                'update_data' => 'updateMetaKeyFieldData',
            ],
        ];

        if ( $listings->have_posts() ) {
            while ( $listings->have_posts() ) {
                $listings->the_post();

                $row                 = [];
                $row['id']           = get_the_ID();
                $row['directory']    = self::get_directory_slug_by_id( get_the_id() );
                $row['publish_date'] = get_the_date( 'Y-m-d H:i:s', get_the_ID() );

                $directory_type_id = get_post_meta( get_the_ID(), '_directory_type', true );
                $submission_form   = get_term_meta( $directory_type_id, 'submission_form_fields', true );

                if ( is_array( $submission_form ) && ! empty( $submission_form['fields'] ) ) {
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

                $row             = self::updateListingReviewsData( $row, get_the_ID() );
                $row             = apply_filters( 'directorist_listings_export_row', $row );
                $listings_data[] = $row;
            }
            wp_reset_postdata();
        }

        $listings_data = self::justifyDataTableRow( $listings_data );

        return $listings_data;
    }

    // justifyDataRow
    public static function justifyDataTableRow( $data_table = [], $tr_lengths = [] ) {
        if ( empty( $data_table ) ) {
            return $data_table; }
        if ( ! is_array( $data_table ) ) {
            return $data_table; }

        $header_keys = [];

        foreach ( $data_table as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }

            foreach ( array_keys( $row ) as $row_key ) {
                $header_keys[ $row_key ] = true;
            }
        }

        $justify_table = [];
        foreach ( $data_table as $row ) {
            $tr = [];

            foreach ( array_keys( $header_keys ) as $row_key ) {
                $tr[ $row_key ] = array_key_exists( $row_key, $row ) ? $row[ $row_key ] : '';
            }

            $justify_table[] = $tr;
        }

        return $justify_table;
    }

    // ================[ Submission Form Fields Helper ]================
    // verifyNativeField
    public static function verifyNativeField( $args = [] ) {
        if ( ! is_array( $args ) ) {
            return false; }
        if ( empty( $args['widget_group'] ) ) {
            return false; }
        if ( empty( $args['widget_name'] ) ) {
            return false; }
        if ( empty( $args['field_key'] ) ) {
            return false; }
        if ( 'preset' !== $args['widget_group'] ) {
            return false; }

        $native_fields = [ 'listing_title', 'listing_content' ];

        if ( ! in_array( $args['field_key'], $native_fields ) ) {
            return false; }

        return true;
    }

    // updateNativeFieldData
    public static function updateNativeFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $field_data_map = [
            'listing_title'   => 'get_the_title',
            'listing_content' => 'get_the_content',
        ];

        $field_key = $field_args['field_key'];
        $content   = call_user_func( $field_data_map[ $field_key ] );
        // $content = str_replace( '"', '""', $content );

        $row[ $field_key ] = self::escape_data( $content );

        return $row;
    }

    // verifyTaxonomyField
    public static function verifyTaxonomyField( $args = [] ) {
        if ( ! is_array( $args ) ) {
            return false; }
        if ( empty( $args['widget_group'] ) ) {
            return false; }
        if ( empty( $args['widget_name'] ) ) {
            return false; }
        if ( empty( $args['field_key'] ) ) {
            return false; }
        if ( 'preset' !== $args['widget_group'] ) {
            return false; }

        $taxonomy = [ 'category', 'location', 'tag' ];

        if ( ! in_array( $args['widget_name'], $taxonomy ) ) {
            return false; }

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
    public static function verifyListingImageModuleField( $args = [] ) {
        if ( ! is_array( $args ) ) {
            return false; }
        if ( empty( $args['widget_group'] ) ) {
            return false; }
        if ( empty( $args['widget_name'] ) ) {
            return false; }
        if ( empty( $args['field_key'] ) ) {
            return false; }
        if ( 'preset' !== $args['widget_group'] ) {
            return false; }
        if ( 'listing_img' !== $args['field_key'] ) {
            return false; }

        return true;
    }

    // updateListingImageModuleFieldsData
    public static function updateListingImageModuleFieldsData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $preview_image  = directorist_get_listing_preview_image( get_the_ID() );
        $gallery_images = directorist_get_listing_gallery_images( get_the_ID() );

        if ( empty( $preview_image ) && empty( $gallery_images ) ) {
            return $row;
        }

        $image_urls = [];
        $image_url  = wp_get_attachment_image_url( $preview_image, 'full' );

        if ( $image_url ) {
            $image_urls[] = $image_url;
        }

        foreach ( $gallery_images as $image ) {
            if ( $image === $preview_image ) {
                continue;
            }
            
            $image_url = wp_get_attachment_image_url( $image, 'full' );
            if ( $image_url ) {
                $image_urls[] = $image_url;
            }
        }

        $row[ $field_args['field_key'] ] = implode( ',', $image_urls );

        return $row;
    }

    // verifyMetaKeyField
    public static function verifyMetaKeyField( $args = [] ) {
        if ( ! is_array( $args ) ) {
            return false; }
        if ( empty( $args['widget_group'] ) ) {
            return false; }
        if ( empty( $args['widget_name'] ) ) {
            return false; }
        if ( empty( $args['field_key'] ) ) {
            return false; }

        return true;
    }

    // updateMetaKeyFieldData
    public static function updateMetaKeyFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $listing_id = get_the_ID();
        $value      = get_post_meta( get_the_id(), '_' . $field_args['field_key'], true );

        $row[ $field_args['field_key'] ] = self::prepareExportFieldValue( $value, $field_args, $listing_id );

        return $row;
    }

    public static function updateListingReviewsData( array $row = [], $listing_id = 0 ) {
        $reviews        = self::get_listing_reviews_data( $listing_id );
        $row['reviews'] = '';

        if ( empty( $reviews ) ) {
            return $row;
        }

        $row['reviews'] = wp_json_encode( $reviews, JSON_HEX_APOS );

        return $row;
    }

    // verifyPriceModuleField
    public static function verifyPriceModuleField( $args = [] ) {
        if ( ! is_array( $args ) ) {
            return false; }
        if ( empty( $args['widget_group'] ) ) {
            return false; }
        if ( empty( $args['widget_name'] ) ) {
            return false; }
        if ( 'pricing' !== $args['widget_name'] ) {
            return false; }

        return true;
    }

    // updatePriceModuleFieldData
    public static function updatePriceModuleFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $row[ 'price' ]                = self::escape_data( get_post_meta( get_the_id(), '_price', true ) );
        $row[ 'price_range' ]          = self::escape_data( get_post_meta( get_the_id(), '_price_range', true ) );
        $row[ 'atbd_listing_pricing' ] = self::escape_data( get_post_meta( get_the_id(), '_atbd_listing_pricing', true ) );

        return $row;
    }

    // verifyMapModuleField
    public static function verifyMapModuleField( $args = [] ) {
        if ( ! is_array( $args ) ) {
            return false; }
        if ( empty( $args['widget_group'] ) ) {
            return false; }
        if ( empty( $args['widget_name'] ) ) {
            return false; }
        if ( 'map' !== $args['widget_name'] ) {
            return false; }

        return true;
    }

    // updateMapModuleFieldData
    public static function updateMapModuleFieldData( array $row = [], string $field_key = '', array $field_args = [] ) {
        $row[ 'hide_map' ]   = get_post_meta( get_the_id(), '_hide_map', true );
        $row[ 'manual_lat' ] = self::escape_data( get_post_meta( get_the_id(), '_manual_lat', true ) );
        $row[ 'manual_lng' ] = self::escape_data( get_post_meta( get_the_id(), '_manual_lng', true ) );

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
        $terms = get_the_terms( $post_id, $taxonomy );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return '';
        }

        return join( ',', wp_list_pluck( $terms, 'name' ) );
    }

    public static function get_listing_reviews_data( $listing_id = 0 ) {
        $comments = get_comments(
            [
                'post_id' => absint( $listing_id ),
                'type'    => 'review',
                'status'  => 'all',
                'orderby' => 'comment_date_gmt',
                'order'   => 'ASC',
            ]
        );

        if ( empty( $comments ) ) {
            return [];
        }

        $reviews = [];
        foreach ( $comments as $comment ) {
            $reviews[] = [
                'review_id'        => (int) $comment->comment_ID,
                'parent_review_id' => (int) $comment->comment_parent,
                'user_id'          => (int) $comment->user_id,
                'author'           => self::escape_data( $comment->comment_author ),
                'email'            => self::escape_data( $comment->comment_author_email ),
                'url'              => esc_url_raw( $comment->comment_author_url ),
                'content'          => self::escape_data( $comment->comment_content ),
                'rating'           => (float) get_comment_meta( $comment->comment_ID, 'rating', true ),
                'status'           => self::prepare_review_status_for_export( $comment->comment_approved ),
                'date'             => $comment->comment_date,
                'date_gmt'         => $comment->comment_date_gmt,
                'meta'             => self::get_listing_review_meta_data( $comment, $listing_id ),
                'advanced_review'  => self::get_listing_advanced_review_data( $comment, $listing_id ),
            ];
        }

        return apply_filters( 'directorist_listings_export_reviews_data', $reviews, $listing_id );
    }

    public static function get_listing_review_meta_data( $comment, $listing_id = 0 ) {
        $meta = get_comment_meta( $comment->comment_ID );

        foreach ( $meta as $key => $values ) {
            $meta[ $key ] = array_map( 'maybe_unserialize', $values );
        }

        return apply_filters( 'directorist_listings_export_review_meta', $meta, $comment, $listing_id );
    }

    public static function get_listing_advanced_review_data( $comment, $listing_id = 0 ) {
        global $wpdb;

        $table = self::get_advanced_review_table_name();

        if ( ! self::advanced_review_table_exists( $table ) ) {
            return [];
        }

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT criteria_key, rating FROM {$table} WHERE comment_ID = %d AND listing_id = %d ORDER BY id ASC",
                $comment->comment_ID,
                absint( $listing_id )
            ),
            ARRAY_A
        );

        if ( empty( $rows ) ) {
            return [];
        }

        $advanced_review = [];
        $criteria_labels = self::get_advanced_review_criteria_labels( $listing_id );

        foreach ( $rows as $row ) {
            $criteria_key      = (string) $row['criteria_key'];
            $advanced_review[] = [
                'criteria_key'   => self::escape_data( $criteria_key ),
                'criteria_label' => ! empty( $criteria_labels[ $criteria_key ] ) ? self::escape_data( $criteria_labels[ $criteria_key ] ) : '',
                'rating'         => (float) $row['rating'],
            ];
        }

        return apply_filters( 'directorist_listings_export_advanced_review_data', $advanced_review, $comment, $listing_id );
    }

    protected static function get_advanced_review_table_name() {
        global $wpdb;

        return $wpdb->prefix . 'directorist_advanced_reviews';
    }

    protected static function advanced_review_table_exists( $table ) {
        global $wpdb;

        return $table === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
    }

    protected static function get_advanced_review_criteria_labels( $listing_id = 0 ) {
        if ( ! function_exists( 'directorist_get_directory_meta' ) || ! function_exists( 'directorist_get_listings_directory_type' ) ) {
            return [];
        }

        $contents = directorist_get_directory_meta( directorist_get_listings_directory_type( $listing_id ), 'single_listings_contents' );

        if ( empty( $contents['fields']['review_criteria']['criterias'] ) || ! is_array( $contents['fields']['review_criteria']['criterias'] ) ) {
            return [];
        }

        $labels = [];
        foreach ( $contents['fields']['review_criteria']['criterias'] as $criteria ) {
            if ( ! isset( $criteria['id'], $criteria['value'] ) ) {
                continue;
            }

            $labels[ (string) $criteria['id'] ] = $criteria['value'];
        }

        return $labels;
    }

    protected static function prepare_review_status_for_export( $status ) {
        if ( '1' === (string) $status ) {
            return 'approve';
        }

        if ( '0' === (string) $status ) {
            return 'hold';
        }

        return (string) $status;
    }

    /**
     * Prepare field values for export before the CSV row is generated.
     *
     * @param mixed $value Field value.
     * @param array $field_args Field arguments.
     * @param int   $listing_id Listing ID.
     * @return mixed
     */
    public static function prepareExportFieldValue( $value, array $field_args = [], $listing_id = 0 ) {
        $value = self::maybeUnserializeValue( $value );

        return apply_filters( 'directorist_listings_export_field_value', $value, $field_args, $listing_id );
    }

    /**
     * Prepare a single CSV cell value.
     *
     * @param mixed $value CSV cell value.
     * @return string|int|float
     */
    public static function prepareCsvCellValue( $value ) {
        $value = self::maybeUnserializeValue( $value );

        if ( is_bool( $value ) ) {
            return $value ? '1' : '0';
        }

        if ( null === $value ) {
            return '';
        }

        if ( is_array( $value ) ) {
            if ( self::isSequentialScalarArray( $value ) ) {
                return self::escape_data( implode( ', ', array_map( [ __CLASS__, 'stringifyScalarValue' ], $value ) ) );
            }

            $json_value = wp_json_encode( $value, JSON_HEX_APOS );
            return self::escape_data( false === $json_value ? '' : $json_value );
        }

        if ( is_object( $value ) ) {
            $json_value = wp_json_encode( $value, JSON_HEX_APOS );
            return self::escape_data( false === $json_value ? '' : $json_value );
        }

        return self::escape_data( (string) $value );
    }

    /**
     * Unserialize values saved by field controls.
     *
     * @param mixed $value Field value.
     * @return mixed
     */
    protected static function maybeUnserializeValue( $value ) {
        while ( is_string( $value ) && is_serialized( $value ) ) {
            $value = maybe_unserialize( $value );
        }

        return $value;
    }

    /**
     * Check whether an array can be exported as a readable value list.
     *
     * @param array $value Field value.
     * @return bool
     */
    protected static function isSequentialScalarArray( array $value ) {
        if ( [] === $value ) {
            return true;
        }

        $index = 0;
        foreach ( $value as $key => $item ) {
            if ( $key !== $index ) {
                return false;
            }

            if ( is_array( $item ) || is_object( $item ) ) {
                return false;
            }

            $index++;
        }

        return true;
    }

    /**
     * Convert scalar values to a readable string.
     *
     * @param mixed $value Field value.
     * @return string
     */
    protected static function stringifyScalarValue( $value ) {
        if ( is_bool( $value ) ) {
            return $value ? '1' : '0';
        }

        if ( null === $value ) {
            return '';
        }

        return (string) $value;
    }

    /**
     * Escape a string to be used in a CSV context
     *
     * Malicious input can inject formulas into CSV files, opening up the possibility
     * for phishing attacks and disclosure of sensitive information.
     *
     * Additionally, Excel exposes the ability to launch arbitrary commands through
     * the DDE protocol.
     *
     * @see http://www.contextis.com/resources/blog/comma-separated-vulnerabilities/
     * @see https://hackerone.com/reports/72785
     *
     * @since 7.7.1
     * @param string $data CSV field to escape.
     * @return string
     */
    public static function escape_data( $data ) {

        if ( ! is_string( $data ) ) {
            return $data;
        }

        $active_content_triggers = [ '=', '+', '-', '@' ];

        if ( in_array( mb_substr( $data, 0, 1 ), $active_content_triggers, true ) ) {
            $data = "'" . $data;
        }

        return $data;
    }
}
