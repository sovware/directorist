<?php
if ( ! defined( 'ABSPATH' ) ) return;

/** Display verbose errors */
if (!defined('IMPORT_DEBUG')) define( 'IMPORT_DEBUG', false );

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
    $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
    if ( file_exists( $class_wp_importer ) )
        require $class_wp_importer;
}

// include WXR file parsers
require dirname( __FILE__ ) . '/parsers.php';

/**
 * WordPress Importer class for managing the import process of a WXR file
 *
 * @package WordPress
 * @subpackage Importer
 */
if ( class_exists( 'WP_Importer' ) ) {
    class Directorist_Import extends WP_Importer {
        var $max_wxr_version = 1.2; // max. supported WXR version

        var $id; // WXR attachment ID

        // information to import from WXR file
        var $version;
        var $authors = array();
        var $posts = array();
        var $terms = array();
        var $categories = array();
        var $tags = array();
        var $base_url = '';

        // Storing Parsed Data
        var $parsed_data = array();

        // mappings from old information to new
        var $processed_authors = array();
        var $author_mapping = array();
        var $processed_terms = array();
        var $processed_posts = array();
        var $post_orphans = array();
        var $processed_menu_items = array();
        var $menu_item_orphans = array();
        var $missing_menu_items = array();

        var $fetch_attachments = false;
        var $url_remap = array();
        var $featured_images = array();

        /**
         * Registered callback function for the WordPress Importer
         *
         * Manages the three separate stages of the WXR import process
         */
        function dispatch() {
            $this->header(); // show page title and importer update notice. there is nothing to check in it.

            $step = empty( $_GET['step'] ) ? 0 : (int) $_GET['step']; // set default step
            switch ( $step ) {
                case 0:
                    $this->greet();
                    break;
                case 1:
                    check_admin_referer( 'import-upload' ); // check security nonce
                    /**
                     * Handles the WXR upload and initial parsing of the file to prepare for
                     * displaying author import options
                     */
                    // Uploaded xml file succeeded, authors found? then show pre-import options
                    if ( $this->handle_upload() )
                        $this->import_options(); // it just displays a form Display pre-import options, author importing/mapping and option to fetch attachments
                    break;
                case 2:
                    check_admin_referer( 'import-wordpress' ); // check security nonce
                    $this->fetch_attachments = ( ! empty( $_POST['fetch_attachments'] ) && $this->allow_fetch_attachments() );
                    $this->id = (int) $_POST['import_id']; // it basically $this->id sent from the import form in the second step
                    $file = get_attached_file( $this->id ); // get the uploaded xml/csv etc file. in this case, an xml file
                    set_time_limit(600); // import process takes long time, so increase the time.
                    $this->import( $file );
                    break;
            }

            $this->footer();
        }

        /**
         * The main controller for the actual import stage.
         *
         * @param string $file Path to the WXR file for importing
         */
        function import( $file ) {
            add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
            add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );
            /*Parse the WXR file and prepare us for the task of processing parsed data*/
            $this->import_start( $file );
            /**
             * Map old author logins to local user IDs based on decisions made
             * in import options form. Can map to an existing user, create a new user
             * or falls back to the current user in case of error with either of the previous
             */
            $this->get_author_mapping();

            wp_suspend_cache_invalidation( true );
            /**
             * Create new posts based on import information
             *
             * Posts marked as having a parent which doesn't exist will become top level items.
             * Doesn't create a new post if: the post type doesn't exist, the given post ID
             * is already noted as imported or a post with the same title and date already exists.
             * Note that new/updated terms, comments and meta are imported for the last of the above.
             */
            $this->process_posts();
            wp_suspend_cache_invalidation( false );

            // update incorrect/missing information in the DB
            $this->backfill_parents();
            $this->backfill_attachment_urls();
            $this->remap_featured_images();

            $this->import_end();
        }

        /**
         * Parses the WXR file and prepares us for the task of processing parsed data
         *
         * @param string $file Path to the WXR file for importing
         */
        function import_start( $file ) {
            if ( ! is_file($file) ) {
                echo '<p><strong>' . __( 'Sorry, there has been an error.', ATBDP_TEXTDOMAIN ) . '</strong><br />';
                echo __( 'The file does not exist, please try again.', ATBDP_TEXTDOMAIN ) . '</p>';
                $this->footer();
                die();
            }

            $this->parsed_data = $this->parse( $file );

            if ( is_wp_error( $this->parsed_data ) ) {
                echo '<p><strong>' . __( 'Sorry, there has been an error.', ATBDP_TEXTDOMAIN ) . '</strong><br />';
                echo esc_html( $this->parsed_data->get_error_message() ) . '</p>';
                $this->footer();
                die();
            }
            // for debugging only @todo; remove debugging comments later
            //file_put_contents(__DIR__.'/imported_data.txt', json_encode($this->parsed_data));
            //file_put_contents(__DIR__.'/listing_img_url.txt', json_encode($this->parsed_data['posts'][0]['listing_img_url']));
            //die();
            $this->version = $this->parsed_data['version'];
            $this->get_authors_from_import( $this->parsed_data );
            $this->posts = $this->parsed_data['posts'];
            $this->terms = $this->parsed_data['terms'];
            $this->categories = $this->parsed_data['categories'];
            $this->tags = $this->parsed_data['tags'];
            $this->base_url = esc_url( $this->parsed_data['base_url'] );

            wp_defer_term_counting( true );
            wp_defer_comment_counting( true );

            do_action('import_start');
        }

        /**
         * Performs post-import cleanup of files and the cache
         */
        function import_end() {
            wp_import_cleanup( $this->id );

            wp_cache_flush();
            foreach ( get_taxonomies() as $tax ) {
                delete_option( "{$tax}_children" );
                _get_term_hierarchy( $tax );
            }

            wp_defer_term_counting( false );
            wp_defer_comment_counting( false );

            echo '<p>' . __( 'All done.', ATBDP_TEXTDOMAIN ) . ' <a href="' . admin_url() . '">' . __( 'Have fun!', ATBDP_TEXTDOMAIN ) . '</a>' . '</p>';
            echo '<p>' . __( 'Remember to update the passwords and roles of imported users.', ATBDP_TEXTDOMAIN ) . '</p>';

            do_action( 'import_end' );
        }

        /**
         * Handles the WXR upload and initial parsing of the file to prepare for
         * displaying author import options
         *
         * @return bool False if error uploading or invalid file, true otherwise
         */
        function handle_upload() {
            $file = wp_import_handle_upload(); // upload the xml file & get Uploaded file's details on success, error message on failure

            // error check
            if ( isset( $file['error'] ) ) {
                echo '<p><strong>' . __( 'Sorry, there has been an error.', ATBDP_TEXTDOMAIN ) . '</strong><br />';
                echo esc_html( $file['error'] ) . '</p>';
                return false;
            } else if ( ! file_exists( $file['file'] ) ) {
                echo '<p><strong>' . __( 'Sorry, there has been an error.', ATBDP_TEXTDOMAIN ) . '</strong><br />';
                printf( __( 'The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.', ATBDP_TEXTDOMAIN ), esc_html( $file['file'] ) );
                echo '</p>';
                return false;
            }

            $this->id = (int) $file['id'];
            $this->parsed_data = $this->parse( $file['file'] );
            // Check if we have error parsing imported file and extract data
            if ( is_wp_error( $this->parsed_data ) ) {
                echo '<p><strong>' . __( 'Sorry, there has been an error.', ATBDP_TEXTDOMAIN ) . '</strong><br />';
                echo esc_html( $this->parsed_data->get_error_message() ) . '</p>';
                return false;
            }

            $this->version = $this->parsed_data['version'];
            if ( $this->version > $this->max_wxr_version ) {
                echo '<div class="error"><p><strong>';
                printf( __( 'This WXR file (version %s) may not be supported by this version of the importer. Please consider updating.', ATBDP_TEXTDOMAIN ), esc_html($this->parsed_data['version']) );
                echo '</strong></p></div>';
            }

            $this->get_authors_from_import( $this->parsed_data ); //Retrieve authors from parsed WXR parsed import data. we could also use the following lines here instead of this function if we wanted simplicity.
            /*
             * if ( ! empty( $import_data['authors'] ) )
             *      $this->authors = $import_data['authors'];
            */

            return true;
        }

        /**
         * Retrieve authors from parsed WXR data
         *
         * Uses the provided author information from WXR 1.1 files
         * or extracts info from each post for WXR 1.0 files
         *
         * @param array $import_data Data returned by a WXR parser
         */
        function get_authors_from_import( $import_data ) {
            if ( ! empty( $import_data['authors'] ) ) {
                $this->authors = $import_data['authors'];
                // no author information, grab it from the posts
            } else {
                foreach ( $import_data['posts'] as $post ) {
                    $login = sanitize_user( $post['post_author'], true );
                    if ( empty( $login ) ) {
                        printf( __( 'Failed to import author %s. Their posts will be attributed to the current user.', ATBDP_TEXTDOMAIN ), esc_html( $post['post_author'] ) );
                        echo '<br />';
                        continue;
                    }

                    if ( ! isset($this->authors[$login]) )
                        $this->authors[$login] = array(
                            'author_login' => $login,
                            'author_display_name' => $post['post_author']
                        );
                }
            }
        }

        /**
         * Display a form with pre-import options, author importing/mapping and option to
         * fetch attachments
         */
        function import_options() {
            $j = 0;
            ?>
            <form action="<?php echo admin_url( 'admin.php?import=directorist&amp;step=2' ); ?>" method="post">
                <?php wp_nonce_field( 'import-wordpress' ); ?>
                <input type="hidden" name="import_id" value="<?php echo $this->id; ?>" />

                <?php if ( ! empty( $this->authors ) ) : ?>
                    <h3><?php _e( 'Assign Authors', ATBDP_TEXTDOMAIN ); ?></h3>
                    <p><?php _e( 'To make it easier for you to edit and save the imported content, you may want to reassign the author of the imported item to an existing user of this site. For example, you may want to import all the entries as <code>admin</code>s entries.', ATBDP_TEXTDOMAIN ); ?></p>
                    <?php if ( $this->allow_create_users() ) : ?>
                        <p><?php printf( __( 'If a new user is created by WordPress, a new password will be randomly generated and the new user&#8217;s role will be set as %s. Manually changing the new user&#8217;s details will be necessary.', ATBDP_TEXTDOMAIN ), esc_html( get_option('default_role') ) ); ?></p>
                    <?php endif; ?>

                
                    <!--list previously enqueued user names in unordered list-->
                    <ol id="authors">
                        <?php foreach ( $this->authors as $author ) : ?>
                            <li><?php $this->author_select( $j++, $author ); ?></li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>

                <?php if ( $this->allow_fetch_attachments() ) : ?>
                    <h3><?php _e( 'Import Attachments', ATBDP_TEXTDOMAIN ); ?></h3>
                    <p>
                        <input type="checkbox" value="1" name="fetch_attachments" id="import-attachments" />
                        <label for="import-attachments"><?php _e( 'Download and import file attachments', ATBDP_TEXTDOMAIN ); ?></label>
                    </p>
                <?php endif; ?>

                <p class="submit"><input type="submit" class="button" value="<?php esc_attr_e( 'Submit', ATBDP_TEXTDOMAIN ); ?>" /></p>
            </form>
            <?php
        }

        /**
         * Display import options for an individual author. That is, either create
         * a new user based on import info or map to an existing user
         *
         * @param int $n Index for each author in the form
         * @param array $author Author information, e.g. login, display name, email
         */
        function author_select( $n, $author ) {
            _e( 'Import author:', ATBDP_TEXTDOMAIN );
            echo ' <strong>' . esc_html( $author['author_display_name'] );
            if ( $this->version != '1.0' ) echo ' (' . esc_html( $author['author_login'] ) . ')';
            echo '</strong><br />';

            if ( $this->version != '1.0' )
                echo '<div style="margin-left:18px">';

            $create_users = $this->allow_create_users();
            if ( $create_users ) {
                if ( $this->version != '1.0' ) {
                    _e( 'or create new user with login name:', ATBDP_TEXTDOMAIN );
                    $value = '';
                } else {
                    _e( 'as a new user:', ATBDP_TEXTDOMAIN );
                    $value = esc_attr( sanitize_user( $author['author_login'], true ) );
                }

                echo ' <input type="text" name="user_new['.$n.']" value="'. $value .'" /><br />';
            }

            if ( ! $create_users && $this->version == '1.0' )
                _e( 'assign posts to an existing user:', ATBDP_TEXTDOMAIN );
            else
                _e( 'or assign posts to an existing user:', ATBDP_TEXTDOMAIN );
            wp_dropdown_users( array( 'name' => "user_map[$n]", 'multi' => true, 'show_option_all' => __( '- Select -', ATBDP_TEXTDOMAIN ) ) );
            echo '<input type="hidden" name="imported_authors['.$n.']" value="' . esc_attr( $author['author_login'] ) . '" />';

            if ( $this->version != '1.0' )
                echo '</div>';
        }

        /**
         * Map old author logins to local user IDs based on decisions made
         * in import options form. Can map to an existing user, create a new user
         * or falls back to the current user in case of error with either of the previous
         */
        function get_author_mapping() {
            if ( ! isset( $_POST['imported_authors'] ) )
                return;

            $create_users = $this->allow_create_users();

            foreach ( (array) $_POST['imported_authors'] as $i => $old_login ) {
                // Multisite adds strtolower to sanitize_user. Need to sanitize here to stop breakage in process_posts.
                $santized_old_login = sanitize_user( $old_login, true );
                $old_id = isset( $this->authors[$old_login]['author_id'] ) ? intval($this->authors[$old_login]['author_id']) : false;

                if ( ! empty( $_POST['user_map'][$i] ) ) {
                    $user = get_userdata( intval($_POST['user_map'][$i]) );
                    if ( isset( $user->ID ) ) {
                        if ( $old_id )
                            $this->processed_authors[$old_id] = $user->ID;
                        $this->author_mapping[$santized_old_login] = $user->ID;
                    }
                } else if ( $create_users ) {
                    if ( ! empty($_POST['user_new'][$i]) ) {
                        $user_id = wp_create_user( $_POST['user_new'][$i], wp_generate_password() );
                    } else if ( $this->version != '1.0' ) {
                        $user_data = array(
                            'user_login' => $old_login,
                            'user_pass' => wp_generate_password(),
                            'user_email' => isset( $this->authors[$old_login]['author_email'] ) ? $this->authors[$old_login]['author_email'] : '',
                            'display_name' => $this->authors[$old_login]['author_display_name'],
                            'first_name' => isset( $this->authors[$old_login]['author_first_name'] ) ? $this->authors[$old_login]['author_first_name'] : '',
                            'last_name' => isset( $this->authors[$old_login]['author_last_name'] ) ? $this->authors[$old_login]['author_last_name'] : '',
                        );
                        $user_id = wp_insert_user( $user_data );
                    }

                    if ( ! is_wp_error( $user_id ) ) {
                        if ( $old_id )
                            $this->processed_authors[$old_id] = $user_id;
                        $this->author_mapping[$santized_old_login] = $user_id;
                    } else {
                        printf( __( 'Failed to create new user for %s. Their posts will be attributed to the current user.', ATBDP_TEXTDOMAIN ), esc_html($this->authors[$old_login]['author_display_name']) );
                        if ( defined('IMPORT_DEBUG') && IMPORT_DEBUG )
                            echo ' ' . $user_id->get_error_message();
                        echo '<br />';
                    }
                }

                // failsafe: if the user_id was invalid, default to the current user
                if ( ! isset( $this->author_mapping[$santized_old_login] ) ) {
                    if ( $old_id )
                        $this->processed_authors[$old_id] = (int) get_current_user_id();
                    $this->author_mapping[$santized_old_login] = (int) get_current_user_id();
                }
            }
        }

        /**
         * Create new posts based on import information
         *
         * Posts marked as having a parent which doesn't exist will become top level items.
         * Doesn't create a new post if: the post type doesn't exist, the given post ID
         * is already noted as imported or a post with the same title and date already exists.
         * Note that new/updated terms, comments and meta are imported for the last of the above.
         */
        function process_posts() {
            $this->posts = apply_filters( 'wp_import_posts', $this->posts );

            foreach ( $this->posts as $post ) {
                $post = apply_filters( 'wp_import_post_data_raw', $post );
                /*skip to the next iteration if post type does not exist in the DB. eg. no post type registered = no import should*/
                if ( ! post_type_exists( $post['post_type'] ) ) {
                    printf( __( 'Failed to import &#8220;%s&#8221;: Invalid post type %s', ATBDP_TEXTDOMAIN ),
                        esc_html($post['post_title']), esc_html($post['post_type']) );
                    echo '<br />';
                    do_action( 'wp_import_post_exists', $post );
                    continue;
                }

                // skip to next if the processed posts id exist
                // or skip to next if post status is auto draft
                // or skip to next if post type is nav_menu_item
                if ( (isset( $this->processed_posts[$post['post_id']] ) && ! empty( $post['post_id'] ))
                    || ( $post['status'] == 'auto-draft' )
                    || ( 'nav_menu_item' == $post['post_type'] )
                ) continue;

                // get the post type object and check if a post exists with the current iterated post title and post date
                $post_type_object = get_post_type_object( $post['post_type'] );
                $post_exists = post_exists( $post['post_title'], '', $post['post_date'] );

                /**
                 * Filter ID of the existing post corresponding to post currently importing.
                 *
                 * Return 0 to force the post to be imported. Filter the ID to be something else
                 * to override which existing post is mapped to the imported post.
                 *
                 * @see post_exists()
                 * @since 0.6.2
                 *
                 * @param int   $post_exists  Post ID, or 0 if post did not exist.
                 * @param array $post         The post array to be inserted.
                 */
                $post_exists = apply_filters( 'wp_import_existing_post', $post_exists, $post );

                if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
                    printf( __('%s &#8220;%s&#8221; already exists.', ATBDP_TEXTDOMAIN), $post_type_object->labels->singular_name, esc_html($post['post_title']) );
                    echo '<br />';
                    $post_id = $post_exists;
                    $this->processed_posts[ intval( $post['post_id'] ) ] = intval( $post_exists );
                } else {
                    // do the current post has a parent? directorist listing does not have post parent. keeping the lines for now.
                    $post_parent = (int) $post['post_parent'];
                    if ( $post_parent ) {
                        // if we already know the parent, map it to the new local ID
                        if ( isset( $this->processed_posts[$post_parent] ) ) {
                            $post_parent = $this->processed_posts[$post_parent];
                            // otherwise record the parent for later
                        } else {
                            $this->post_orphans[intval($post['post_id'])] = $post_parent;
                            $post_parent = 0;
                        }
                    }

                    // map the post author and ge the author id
                    $author = sanitize_user( $post['post_author'], true );
                    if ( isset( $this->author_mapping[$author] ) )
                        $author = $this->author_mapping[$author];
                    else
                        $author = (int) get_current_user_id();
                    // prepare the post array to be inserted to the database.
                    $postdata = array(
                        'import_id' => $post['post_id'],
                        'post_author' => $author,
                        'post_date' => $post['post_date'],
                        'post_date_gmt' => $post['post_date_gmt'],
                        'post_content' => $post['post_content'],
                        'post_excerpt' => $post['post_excerpt'],
                        'post_title' => $post['post_title'],
                        'post_status' => $post['status'],
                        'post_name' => $post['post_name'],
                        'comment_status' => $post['comment_status'],
                        'ping_status' => $post['ping_status'],
                        'guid' => $post['guid'],
                        'post_parent' => $post_parent,
                        'menu_order' => $post['menu_order'],
                        'post_type' => $post['post_type'],
                        'post_password' => $post['post_password']
                    );

                    $original_post_ID = $post['post_id'];
                    $postdata = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

                    $postdata = wp_slash( $postdata );

                    if ( 'attachment' == $postdata['post_type'] ) {
                        $remote_url = ! empty($post['attachment_url']) ? $post['attachment_url'] : $post['guid'];

                        // try to use _wp_attached file for upload folder placement to ensure the same location as the export site
                        // e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
                        $postdata['upload_date'] = $post['post_date'];
                        if ( isset( $post['postmeta'] ) ) {
                            foreach( $post['postmeta'] as $meta ) {
                                if ( $meta['key'] == '_wp_attached_file' ) {
                                    if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches ) )
                                        $postdata['upload_date'] = $matches[0];
                                    break;
                                }
                            }
                        }

                        $post_id = $this->process_attachment( $postdata, $remote_url );
                    } else {

                        $post_id = wp_insert_post( $postdata, true );

                        // we have to process images here.
                        // get all images attached to this post.
                        $image_urls = (array) $post['listing_img_url'];

                        // fetch image from url, insert the attachment to db, store the attachment ids and url to the post meta
                        if ( !empty( $image_urls ) && is_array($image_urls) ) {
                            $attachment_ids = array();
                            foreach ( $image_urls as $image_url ) {
                                $attachment_ids[] = directorist_get_attachment_id_from_url( $image_url, $post_id );
                            }

                            // update the post meta with attachment ids
                            update_post_meta($post_id, '_listing_img', $attachment_ids);
                        }

                        do_action( 'wp_import_insert_post', $post_id, $original_post_ID, $postdata, $post );
                    }
                    // skip to the next post if the current post failed to be inserted
                    if ( is_wp_error( $post_id ) ) {
                        printf( __( 'Failed to import %s &#8220;%s&#8221;', ATBDP_TEXTDOMAIN ),
                            $post_type_object->labels->singular_name, esc_html($post['post_title']) );
                        if ( defined('IMPORT_DEBUG') && IMPORT_DEBUG )
                            echo ': ' . $post_id->get_error_message();
                        echo '<br />';
                        continue;
                    }

                }

                // map pre-import ID to local ID
                $this->processed_posts[intval($post['post_id'])] = (int) $post_id;

                // add categories, tags and other terms
                if ( ! isset( $post['terms'] ) )  $post['terms'] = array();
                $post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );
                if ( ! empty( $post['terms'] ) ) {
                    $terms_to_set = array();
                    foreach ( $post['terms'] as $term ) {
                        // back compat with WXR 1.0 map 'tag' to 'post_tag'
                        $taxonomy = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
                        $term_exists = term_exists( $term['slug'], $taxonomy );
                        $term_id = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
                        if ( ! $term_id ) {
                            $t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
                            if ( ! is_wp_error( $t ) ) {
                                $term_id = $t['term_id'];
                                do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
                            } else {
                                printf( __( 'Failed to import %s %s', ATBDP_TEXTDOMAIN ), esc_html($taxonomy), esc_html($term['name']) );
                                if ( defined('IMPORT_DEBUG') && IMPORT_DEBUG )
                                    echo ': ' . $t->get_error_message();
                                echo '<br />';
                                do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
                                continue;
                            }
                        }
                        $terms_to_set[$taxonomy][] = intval( $term_id );
                    }

                    foreach ( $terms_to_set as $tax => $ids ) {
                        $tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
                        do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
                    }
                    unset( $post['terms'], $terms_to_set );
                }

                // add/update post meta
                if ( ! isset( $post['postmeta'] ) ) $post['postmeta'] = array();
                $post['postmeta'] = apply_filters( 'wp_import_post_meta', $post['postmeta'], $post_id, $post );
                if ( ! empty( $post['postmeta'] ) ) {
                    foreach ( $post['postmeta'] as $meta ) {
                        $key = apply_filters( 'import_post_meta_key', $meta['key'], $post_id, $post );
                        $value = false;

                        if ( '_edit_last' == $key ) {
                            if ( isset( $this->processed_authors[intval($meta['value'])] ) )
                                $value = $this->processed_authors[intval($meta['value'])];
                            else
                                $key = false;
                        }

                        if ( $key ) {
                            //if there is _listing_img key then skip it. because we have already added new attachment ids as _listing_img above just after importing the post to the db. So, we do not want to use old _listing_img ids
                            if ('_listing_img' == $key) continue;

                            // export gets meta straight from the DB so could have a serialized string
                            if ( ! $value )
                                $value = maybe_unserialize( $meta['value'] );

                            add_post_meta( $post_id, $key, $value );
                            do_action( 'import_post_meta', $post_id, $key, $value );

                            // if the post has a featured image, take note of this in case of remap
                            if ( '_thumbnail_id' == $key )
                                $this->featured_images[$post_id] = (int) $value;
                        }
                    }
                }
            } // end foreach $posts loop

            unset( $this->posts );
        }


        /**
         * If fetching attachments is enabled then attempt to create a new attachment
         *
         * @param array $post Attachment post details from WXR
         * @param string $url URL to fetch attachment from
         * @return int|WP_Error Post ID on success, WP_Error otherwise
         */
        function process_attachment( $post, $url ) {
            if ( ! $this->fetch_attachments )
                return new WP_Error( 'attachment_processing_error',
                    __( 'Fetching attachments is not enabled', ATBDP_TEXTDOMAIN ) );

            // if the URL is absolute, but does not contain address, then upload it assuming base_site_url
            if ( preg_match( '|^/[\w\W]+$|', $url ) )
                $url = rtrim( $this->base_url, '/' ) . $url;

            $upload = $this->fetch_remote_file( $url, $post );
            if ( is_wp_error( $upload ) )
                return $upload;

            if ( $info = wp_check_filetype( $upload['file'] ) )
                $post['post_mime_type'] = $info['type'];
            else
                return new WP_Error( 'attachment_processing_error', __('Invalid file type', ATBDP_TEXTDOMAIN) );

            $post['guid'] = $upload['url'];

            // as per wp-admin/includes/upload.php
            $post_id = wp_insert_attachment( $post, $upload['file'] );
            wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

            // remap resized image URLs, works by stripping the extension and remapping the URL stub.
            if ( preg_match( '!^image/!', $info['type'] ) ) {
                $parts = pathinfo( $url );
                $name = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

                $parts_new = pathinfo( $upload['url'] );
                $name_new = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

                $this->url_remap[$parts['dirname'] . '/' . $name] = $parts_new['dirname'] . '/' . $name_new;
            }

            return $post_id;
        }

        /**
         * Attempt to download a remote file attachment
         *
         * @param string $url URL of item to fetch
         * @param array $post Attachment details
         * @return array|WP_Error Local file location details on success, WP_Error otherwise
         */
        function fetch_remote_file( $url, $post ) {
            // extract the file name and extension from the url
            $file_name = basename( $url );

            // get placeholder file in the upload dir with a unique, sanitized filename
            $upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
            if ( $upload['error'] )
                return new WP_Error( 'upload_dir_error', $upload['error'] );

            // fetch the remote url and write it to the placeholder file
            $remote_response = wp_safe_remote_get( $url, array(
                'timeout' => 300,
                'stream' => true,
                'filename' => $upload['file'],
            ) );

            $headers = wp_remote_retrieve_headers( $remote_response );

            // request failed
            if ( ! $headers ) {
                @unlink( $upload['file'] );
                return new WP_Error( 'import_file_error', __('Remote server did not respond', ATBDP_TEXTDOMAIN) );
            }

            $remote_response_code = wp_remote_retrieve_response_code( $remote_response );

            // make sure the fetch was successful
            if ( $remote_response_code != '200' ) {
                @unlink( $upload['file'] );
                return new WP_Error( 'import_file_error', sprintf( __('Remote server returned error response %1$d %2$s', ATBDP_TEXTDOMAIN), esc_html($remote_response_code), get_status_header_desc($remote_response_code) ) );
            }

            $filesize = filesize( $upload['file'] );

            if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
                @unlink( $upload['file'] );
                return new WP_Error( 'import_file_error', __('Remote file is incorrect size', ATBDP_TEXTDOMAIN) );
            }

            if ( 0 == $filesize ) {
                @unlink( $upload['file'] );
                return new WP_Error( 'import_file_error', __('Zero size file downloaded', ATBDP_TEXTDOMAIN) );
            }

            $max_size = (int) $this->max_attachment_size();
            if ( ! empty( $max_size ) && $filesize > $max_size ) {
                @unlink( $upload['file'] );
                return new WP_Error( 'import_file_error', sprintf(__('Remote file is too large, limit is %s', ATBDP_TEXTDOMAIN), size_format($max_size) ) );
            }

            // keep track of the old and new urls so we can substitute them later
            $this->url_remap[$url] = $upload['url'];
            $this->url_remap[$post['guid']] = $upload['url']; // r13735, really needed?
            // keep track of the destination if the remote url is redirected somewhere else
            if ( isset($headers['x-final-location']) && $headers['x-final-location'] != $url )
                $this->url_remap[$headers['x-final-location']] = $upload['url'];

            return $upload;
        }

        /**
         * Attempt to associate posts and menu items with previously missing parents
         *
         * An imported post's parent may not have been imported when it was first created
         * so try again. Similarly for child menu items and menu items which were missing
         * the object (e.g. post) they represent in the menu
         */
        function backfill_parents() {
            global $wpdb;

            // find parents for post orphans
            foreach ( $this->post_orphans as $child_id => $parent_id ) {
                $local_child_id = $local_parent_id = false;
                if ( isset( $this->processed_posts[$child_id] ) )
                    $local_child_id = $this->processed_posts[$child_id];
                if ( isset( $this->processed_posts[$parent_id] ) )
                    $local_parent_id = $this->processed_posts[$parent_id];

                if ( $local_child_id && $local_parent_id ) {
                    $wpdb->update( $wpdb->posts, array( 'post_parent' => $local_parent_id ), array( 'ID' => $local_child_id ), '%d', '%d' );
                    clean_post_cache( $local_child_id );
                }
            }

        }

        /**
         * Use stored mapping information to update old attachment URLs
         */
        function backfill_attachment_urls() {
            global $wpdb;
            // make sure we do the longest urls first, in case one is a substring of another
            uksort( $this->url_remap, array(&$this, 'cmpr_strlen') );

            foreach ( $this->url_remap as $from_url => $to_url ) {
                // remap urls in post_content
                $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url) );
                // remap enclosure urls
                $result = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'", $from_url, $to_url) );
            }
        }

        /**
         * Update _thumbnail_id meta to new, imported attachment IDs
         */
        function remap_featured_images() {
            // cycle through posts that have a featured image, here the $value is attachment_id
            foreach ( $this->featured_images as $post_id => $old_attachment_id ) {
                if ( isset( $this->processed_posts[$old_attachment_id] ) ) {
                    $new_id = $this->processed_posts[$old_attachment_id];
                    // only update if there's a difference
                    if ( $new_id != $old_attachment_id )
                        update_post_meta( $post_id, '_thumbnail_id', $new_id );
                }
            }
        }

        /**
         * Parse a WXR file
         *
         * @param string $file Path to WXR file for parsing
         * @return array Information gathered from the WXR file
         */
        function parse( $file ) {
            $parser = new Directorist_Parser();
            return $parser->parse( $file );
        }

        // Display import page title
        function header() {
            echo '<div class="wrap">';
            echo '<h2>' . __( 'Import Directorist Data', ATBDP_TEXTDOMAIN ) . '</h2>';

            $updates = get_plugin_updates();
            $basename = plugin_basename(__FILE__);
            if ( isset( $updates[$basename] ) ) {
                $update = $updates[$basename];
                echo '<div class="error"><p><strong>';
                printf( __( 'A new version of this importer is available. Please update to version %s to ensure compatibility with newer export files.', ATBDP_TEXTDOMAIN ), $update->update->new_version );
                echo '</strong></p></div>';
            }
        }

        // Close div.wrap
        function footer() {
            echo '</div>';
        }

        /**
         * Display introductory text and file upload form
         */
        function greet() {
            echo '<div class="narrow">';
            echo '<p>'.__( 'Howdy! Upload your Directorist Exported xml file to import the data into the site', ATBDP_TEXTDOMAIN ).'</p>';
            echo '<p>'.__( 'Choose a WXR (.xml) file to upload, then click Upload file and import.', ATBDP_TEXTDOMAIN ).'</p>';
            wp_import_upload_form( 'admin.php?import=directorist&amp;step=1' );
            echo '</div>';
        }

        /**
         * Decide if the given meta key maps to information we will want to import
         *
         * @param string $key The meta key to check
         * @return string|bool The key if we do want to import, false if not
         */
        function is_valid_meta_key( $key ) {
            // skip attachment metadata since we'll regenerate it from scratch
            // skip _edit_lock as not relevant for import
            if ( in_array( $key, array( '_wp_attached_file', '_wp_attachment_metadata', '_edit_lock' ) ) )
                return false;
            return $key;
        }

        /**
         * Decide whether or not the importer is allowed to create users.
         * Default is true, can be filtered via import_allow_create_users
         *
         * @return bool True if creating users is allowed
         */
        function allow_create_users() {
            return apply_filters( 'import_allow_create_users', true );
        }

        /**
         * Decide whether or not the importer should attempt to download attachment files.
         * Default is true, can be filtered via import_allow_fetch_attachments. The choice
         * made at the import options screen must also be true, false here hides that checkbox.
         *
         * @return bool True if downloading attachments is allowed
         */
        function allow_fetch_attachments() {
            return apply_filters( 'import_allow_fetch_attachments', true );
        }

        /**
         * Decide what the maximum file size for downloaded attachments is.
         * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
         *
         * @return int Maximum attachment file size to import
         */
        function max_attachment_size() {
            return apply_filters( 'import_attachment_size_limit', 0 );
        }

        /**
         * Added to http_request_timeout filter to force timeout at 120 seconds during import
         * @return int 600
         * @todo; $val is never used in the base  class as well as here. what is the use of it then? remove? or use it
         */
        function bump_request_timeout( $val ) {
            return 600;
        }

        // return the difference in length between two strings
        function cmpr_strlen( $a, $b ) {
            return strlen($b) - strlen($a);
        }
    }

} // class_exists( 'WP_Importer' )






//adjustments to wp-includes/http.php timeout values to workaround slow server responses
/*@todo; later give user option to increase this time as long as they want so that their import does not fail if the listing number is huge. It can be removed once batch uploading feature is implemented in future.*/
add_filter('http_request_args', 'directorist_http_request_args', 100, 1);
function directorist_http_request_args($r) //called on line 237
{
    $r['timeout'] = 600;
    return $r;
}
/*Increase the curl timeout value to prevent curl time out error when downloading many attachment during importing listings. */
add_action('http_api_curl', 'directorist_http_api_curl', 100, 1);
function directorist_http_api_curl($handle) //called on line 1315
{
    curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 600 );
    curl_setopt( $handle, CURLOPT_TIMEOUT, 600 );
}


function directorist_importer_init() {
    /**
     * WordPress Importer object for registering the import callback
     * @global WP_Import $wp_import
     */
    $GLOBALS['directorist_import'] = new Directorist_Import();
    /*@todo; wrong way to translate*/
    register_importer( 'directorist', 'Directorist', __('Import <strong>Directorist Listings</strong> from a Directorist export file.', ATBDP_TEXTDOMAIN), array( $GLOBALS['directorist_import'], 'dispatch' ) );
}
add_action( 'admin_init', 'directorist_importer_init' );
