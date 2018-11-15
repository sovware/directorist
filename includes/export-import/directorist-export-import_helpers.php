<?php 
function directorist_exim_get_categories_checkboxes( $taxonomy = 'category', $selected_cats = null ) {
	$args = array (
		'taxonomy' => $taxonomy
	);
	$all_categories = get_categories($args);
	
	$o = '<div class="checkbox_box checkbox_with_all"><ul><li class="e2t_all"><label><input class="e2t_all_input" type="checkbox" name="taxonomy['.$taxonomy.'][]" value="e2t_all" checked="checked" /> All</label></li>';
	foreach($all_categories as $key => $cat) {
		if($cat->parent == "0") $o .= directorist_exim_show_category($cat, $taxonomy, $selected_cats);
	}
	return $o . '</ul></div>';
}
function directorist_exim_show_category($cat_object, $taxonomy = 'category', $selected_cats = null) {
	$checked = "";
	if(!is_null($selected_cats) && is_array($selected_cats)) {
		$checked = (in_array($cat_object->cat_ID, $selected_cats)) ? 'checked="checked"' : "";
	}
	$ou = '<li><label><input class="e2t_input" ' . $checked .' type="checkbox" name="taxonomy['.$taxonomy.'][]" value="'. $cat_object->cat_ID .'" /> ' . $cat_object->cat_name . '</label>';
	$childs = get_categories('parent=' . $cat_object->cat_ID);
	foreach($childs as $key => $cat) {
		$ou .= '<ul class="children">' . directorist_exim_show_category($cat, $taxonomy, $selected_cats) . '</ul>';
	}
	$ou .= '</li>';
	return $ou;
}
// get taxonomies terms links
function directorist_exim_custom_taxonomies_terms_links() {
	global $post, $post_id;
	// get post type taxonomies
	$taxonomies = get_object_taxonomies(ATBDP_POST_TYPE);
	ob_start();

	$return = '';
	$taxonomies_count = count($taxonomies);
	foreach ($taxonomies as $taxonomy) {
		if( $taxonomy !=  'category' && $taxonomy != 'post_tag' ) {
            $taxonomies_count--;
			// get the terms related to post
			$terms = get_the_terms( $post->ID, $taxonomy );
			if ( !empty( $terms ) ) {
				$return .= $taxonomy.':';
				$count = count($terms);
				foreach ( $terms as $term ){

                    if($count > 1) {
                        $return .= $term->slug. ','; // include the comma if there is more than 1 items
                    } else {
                        $return .= $term->slug; // just add the number if there is only one item.
                    }
                    $count--; // reduce the counter
                }

				$return .= '&';
			}
		}
	}

    return ob_get_clean(); //  @todo; remove it later.

    //return $return;
}

// Code used to get start and end dates with posts
function directorist_exim_the_post_dates() {
	global $wpdb, $wp_locale;
	
	$dateoptions = '';
	$types = "'" . implode("', '", get_post_types( array( 'public' => true, 'can_export' => true ), 'names' )) . "'";
	if ( function_exists( 'get_post_stati' ) ) {
		$stati = "'" . implode("', '", get_post_stati( array( 'internal' => false ), 'names' )) . "'";
	}
	else {
		$stati = "'" . implode("', '", get_post_statuses()) . "'";
	}

    $monthyears = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, YEAR(DATE_ADD(post_date, INTERVAL 1 MONTH)) AS `eyear`, MONTH(DATE_ADD(post_date, INTERVAL 1 MONTH)) AS `emonth` FROM $wpdb->posts WHERE post_type IN ($types) AND post_status IN ($stati) ORDER BY post_date ASC ");

	if ( $monthyears ) {
		foreach ( $monthyears as $k => $monthyear ) $monthyears[$k]->lmonth = $wp_locale->get_month( $monthyear->month );

		for( $s = 0, $e = count( $monthyears ) - 1; $e >= 0; $s++, $e-- ) {
			$dateoptions .= "\t<option value=\"" . $monthyears[$e]->eyear . '-' . zeroise( $monthyears[$e]->emonth, 2 ) . '">' . $monthyears[$e]->lmonth . ' ' . $monthyears[$e]->year . "</option>\n";
		}
	}
	
	return $dateoptions;
}

function directorist_exim_implode_wrapped($before, $after, $array, $glue = '') {
    return $before . implode($after . $glue . $before, $array) . $after;
}


/**
 * Returns image mime types users are allowed to upload via the API.
 *
 * @since  3.3.5
 * @return array
 */
function directorist_allowed_image_mime_types() {
    return apply_filters(
        'directorist_allowed_image_mime_types', array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif'          => 'image/gif',
            'png'          => 'image/png',
            'bmp'          => 'image/bmp',
            'tiff|tif'     => 'image/tiff',
            'ico'          => 'image/x-icon',
        )
    );
}

/**
 * Get attachment ID.
 * @since 3.3.5
 * @param  string $url        Attachment URL.
 * @param  int    $listing_id Listing ID.
 * @return int
 * @throws Exception If attachment cannot be loaded.
 */
function directorist_get_attachment_id_from_url( $url, $listing_id ) {
    if ( empty( $url ) ) {
        return 0;
    }

    $id         = 0;
    $upload_dir = wp_upload_dir( null, false );
    $base_url   = $upload_dir['baseurl'] . '/';

    // Check first if attachment is inside the WordPress uploads directory, or we're given a filename only.
    if ( false !== strpos( $url, $base_url ) || false === strpos( $url, '://' ) ) {
        // Search for yyyy/mm/slug.extension or slug.extension - remove the base URL.
        $file = str_replace( $base_url, '', $url );
        $args = array(
            'post_type'   => 'attachment',
            'post_status' => 'any',
            'fields'      => 'ids',
            'meta_query'  => array( // @codingStandardsIgnoreLine.
                'relation' => 'OR',
                array(
                    'key'     => '_wp_attached_file',
                    'value'   => '^' . $file,
                    'compare' => 'REGEXP',
                ),
                array(
                    'key'     => '_wp_attached_file',
                    'value'   => '/' . $file,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => '_directorist_attachment_source',
                    'value'   => '/' . $file,
                    'compare' => 'LIKE',
                ),
            ),
        );
    } else {
        // This is an external URL, so compare to source.
        $args = array(
            'post_type'   => 'attachment',
            'post_status' => 'any',
            'fields'      => 'ids',
            'meta_query'  => array( // @codingStandardsIgnoreLine.
                array(
                    'value' => $url,
                    'key'   => '_directorist_attachment_source',
                ),
            ),
        );
    }

    $ids = get_posts( $args ); // @codingStandardsIgnoreLine.

    if ( $ids ) {
        $id = current( $ids );
    }

    // Upload if attachment does not exists.
    if ( ! $id && stristr( $url, '://' ) ) {
        $upload = directorist_upload_image_from_url( $url ); // get $upload Upload information from wp_upload_bits.

        if ( is_wp_error( $upload ) ) {
            throw new Exception( $upload->get_error_message(), 400 );
        }

        $id = directorist_insert_uploaded_image_as_attachment( $upload, $listing_id ); // insert uploaded image to the db as attachment

        if ( ! wp_attachment_is_image( $id ) ) {
            /* translators: %s: image URL */
            throw new Exception( sprintf( __( 'Not able to attach "%s".', ATBDP_TEXTDOMAIN ), $url ), 400 );
        }

        // Save attachment source for future reference.
        //@todo; for later improvement after import has been done. we can update our current directorist post meta to use this key to store attachment url when saving the post
        update_post_meta( $id, '_directorist_attachment_source', $url );
    }

    if ( ! $id ) {
        /* translators: %s: image URL */
        throw new Exception( sprintf( __( 'Unable to use image "%s".', ATBDP_TEXTDOMAIN ), $url ), 400 );
    }

    return $id;
}

/**
 * Upload image from URL.
 *
 * @since 3.2.5
 * @param string $image_url Image URL.
 * @return array|WP_Error Attachment data or error message.
 */
function directorist_upload_image_from_url( $image_url ) {
    $file_name  = basename( current( explode( '?', $image_url ) ) );
    $parsed_url = wp_parse_url( $image_url );

    // Check parsed URL.
    if ( ! $parsed_url || ! is_array( $parsed_url ) ) {
        /* translators: %s: image URL */
        return new WP_Error( 'directorist_import_invalid_image_url', sprintf( __( 'Invalid URL %s.', ATBDP_TEXTDOMAIN ), $image_url ), array( 'status' => 400 ) );
    }

    // Ensure url is valid.
    $image_url = esc_url_raw( $image_url );

    // Get the file.
    $response = wp_safe_remote_get(
        $image_url, array(
            'timeout' => 10,
        )
    );

    if ( is_wp_error( $response ) ) {
        return new WP_Error( 'directorist_import_invalid_remote_image_url',
            /* translators: %s: image URL */
            sprintf( __( 'Error getting remote image %s.', ATBDP_TEXTDOMAIN ), $image_url ) . ' '
            /* translators: %s: error message */
            . sprintf( __( 'Error: %s.', ATBDP_TEXTDOMAIN ), $response->get_error_message() ), array( 'status' => 400 )
        );
    } elseif ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
        /* translators: %s: image URL */
        return new WP_Error( 'directorist_import_invalid_remote_image_url', sprintf( __( 'Error getting remote image %s.', ATBDP_TEXTDOMAIN ), $image_url ), array( 'status' => 400 ) );
    }

    // Ensure we have a file name and type.
    $wp_filetype = wp_check_filetype( $file_name, directorist_allowed_image_mime_types() );

    if ( ! $wp_filetype['type'] ) {
        $headers = wp_remote_retrieve_headers( $response );
        if ( isset( $headers['content-disposition'] ) && strstr( $headers['content-disposition'], 'filename=' ) ) {
            $content = explode( 'filename=', $headers['content-disposition'] );
            $disposition = end( $content );
            $disposition = sanitize_file_name( $disposition );
            $file_name   = $disposition;
        } elseif ( isset( $headers['content-type'] ) && strstr( $headers['content-type'], 'image/' ) ) {
            $file_name = 'image.' . str_replace( 'image/', '', $headers['content-type'] );
        }
        unset( $headers );

        // Recheck filetype.
        $wp_filetype = wp_check_filetype( $file_name, directorist_allowed_image_mime_types() );

        if ( ! $wp_filetype['type'] ) {
            return new WP_Error( 'directorist_import_invalid_image_type', __( 'Invalid image type.', ATBDP_TEXTDOMAIN ), array( 'status' => 400 ) );
        }
    }

    // Upload the file.
    $upload = wp_upload_bits( $file_name, '', wp_remote_retrieve_body( $response ) );

    if ( $upload['error'] ) {
        return new WP_Error( 'directorist_import_image_upload_error', $upload['error'], array( 'status' => 400 ) );
    }

    // Get filesize.
    $filesize = filesize( $upload['file'] );

    if ( ! $filesize ) {
        @unlink( $upload['file'] ); // @codingStandardsIgnoreLine
        unset( $upload );

        return new WP_Error( 'directorist_import_image_upload_file_error', __( 'Zero size file downloaded.', ATBDP_TEXTDOMAIN ), array( 'status' => 400 ) );
    }

    do_action( 'directorist_uploaded_image_from_url', $upload, $image_url );

    return $upload;
}

/**
 * Insert uploaded image as attachment.
 *
 * @since 3.3.5
 * @param array $upload Upload information from wp_upload_bits.
 * @param int   $id [optional] Listing Post ID. Default to 0.
 * @return int Attachment ID
 */
function directorist_insert_uploaded_image_as_attachment( $upload, $id = 0 ) {
    $info    = wp_check_filetype( $upload['file'] );
    $title   = '';
    $content = '';

    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
        include_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $image_meta = wp_read_image_metadata( $upload['file'] );
    if ( $image_meta ) {
        if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) ) {
            $title = directorist_clean( $image_meta['title'] );
        }
        if ( trim( $image_meta['caption'] ) ) {
            $content = directorist_clean( $image_meta['caption'] );
        }
    }

    $attachment = array(
        'post_mime_type' => $info['type'],
        'guid'           => $upload['url'],
        'post_parent'    => $id,
        'post_title'     => $title ? $title : basename( $upload['file'] ),
        'post_content'   => $content,
    );

    $attachment_id = wp_insert_attachment( $attachment, $upload['file'], $id );
    if ( ! is_wp_error( $attachment_id ) ) {
        wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
    }

    return $attachment_id; // we could later return the attachment $id and url in an array here instead of just an id.
}
