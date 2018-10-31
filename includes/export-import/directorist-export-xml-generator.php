<?php
/**
 * WordPress Export Administration API
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * Version number for the export format.
 *
 * Bump this when something changes that might affect compatibility.
 *
 * @since 2.5.0
 */
!defined('WXR_VERSION') ? define( 'WXR_VERSION', '1.2' ) : null;

/**
 * Generates the WXR export file for download.
 *
 * Default behavior is to export all content, however, note that post content will only
 * be exported for post types with the `can_export` argument enabled. Any posts with the
 * 'auto-draft' status will be skipped.
 *
 * @since 2.1.0
 *
 * @global wpdb    $wpdb WordPress database abstraction object.
 * @global WP_Post $post Global `$post`.
 *
 * @param array $args {
 *     Optional. Arguments for generating the WXR export file for download. Default empty array.
 *
 *     @type string $content        Type of content to export. If set, only the post content of this post type
 *                                  will be exported. Accepts 'all', 'post', 'page', 'attachment', or a defined
 *                                  custom post. If an invalid custom post type is supplied, every post type for
 *                                  which `can_export` is enabled will be exported instead. If a valid custom post
 *                                  type is supplied but `can_export` is disabled, then 'posts' will be exported
 *                                  instead. When 'all' is supplied, only post types with `can_export` enabled will
 *                                  be exported. Default 'all'.
 *     @type string $author         Author to export content for. Only used when `$content` is 'post', 'page', or
 *                                  'attachment'. Accepts false (all) or a specific author ID. Default false (all).
 *     @type string $category       Category (slug) to export content for. Used only when `$content` is 'post'. If
 *                                  set, only post content assigned to `$category` will be exported. Accepts false
 *                                  or a specific category slug. Default is false (all categories).
 *     @type string $start_date     Start date to export content from. Expected date format is 'Y-m-d'. Used only
 *                                  when `$content` is 'post', 'page' or 'attachment'. Default false (since the
 *                                  beginning of time).
 *     @type string $end_date       End date to export content to. Expected date format is 'Y-m-d'. Used only when
 *                                  `$content` is 'post', 'page' or 'attachment'. Default false (latest publish date).
 *     @type string $status         Post status to export posts for. Used only when `$content` is 'post' or 'page'.
 *                                  Accepts false (all statuses except 'auto-draft'), or a specific status, i.e.
 *                                  'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', or
 *                                  'trash'. Default false (all statuses except 'auto-draft').
 * }
 */
function export_directorist( $args = array() ) {
    global $wpdb, $post;

    $defaults = array(
        'content' => 'at_biz_dir',
        'author' => false,
        'category' => false,
        'start_date' => false,
        'end_date' => false,
        'status' => false,
    );
    $args = wp_parse_args( $args, $defaults );

    /**
     * Fires at the beginning of an export, before any headers are sent.
     *
     * @since 2.3.0
     *
     * @param array $args An array of export arguments.
     */
    do_action( 'directorist_export', $args );

    $sitename = sanitize_key( get_bloginfo( 'name' ) );
    if ( ! empty( $sitename ) ) {
        $sitename .= '.';
    }
    $date = date( 'Y-m-d' );
    $wp_filename = $sitename . 'directorist.' . $date . '.xml';
    /**
     * Filters the export filename.
     *
     * @since 3.2.5
     *
     * @param string $wp_filename The name of the file for download.
     * @param string $sitename    The site name.
     * @param string $date        Today's date, formatted.
     */
    $filename = apply_filters( 'export_directorist_filename', $wp_filename, $sitename, $date );

    header( 'Content-Description: File Transfer' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );
    // limit by at_biz_dir post_type
    $where = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", $args['content'] );

    // limit by post status
    if ( $args['status'] && ( 'post' == $args['content'] || 'page' == $args['content'] ) )
        $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_status = %s", $args['status'] );
    else
        $where .= " AND {$wpdb->posts}.post_status != 'auto-draft'";

    // define the joins later
    $join = '';
    // limit by author
    if ( $args['author'] )
        $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_author = %d", $args['author'] );
    // limit by start date
    if ( $args['start_date'] && 'all' !== $args['start_date'] )
        $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_date >= %s", date( 'Y-m-d', strtotime($args['start_date']) ) );
    // limit by end date
    if ( $args['end_date'] && 'all' !== $args['end_date'])
        $where .= $wpdb->prepare( " AND {$wpdb->posts}.post_date < %s", date( 'Y-m-d', strtotime('+1 month', strtotime($args['end_date'])) ) );


    // Grab a snapshot of post IDs, just in case it changes during the export.
    $post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} $join WHERE $where" );

    /**
     * Wrap given string in XML CDATA tag.
     *
     * @since 2.1.0
     *
     * @param string $str String to wrap in XML CDATA tag.
     * @return string
     */
    function wxr_cdata( $str ) {
        if ( ! seems_utf8( $str ) ) {
            $str = utf8_encode( $str );
        }
        // $str = ent2ncr(esc_html($str));
        $str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $str ) . ']]>';

        return $str;
    }

    /**
     * Return the URL of the site
     *
     * @since 2.5.0
     *
     * @return string Site URL.
     */
    function wxr_site_url() {
        // Multisite: the base URL.
        if ( is_multisite() )
            return network_home_url();
        // WordPress (single site): the blog URL.
        else
            return get_bloginfo_rss( 'url' );
    }

    /**
     * Output list of authors with posts
     *
     * @since 3.1.0
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param array $post_ids Array of post IDs to filter the query by. Optional.
     */
    function wxr_authors_list( array $post_ids = null ) {
        global $wpdb;

        if ( !empty( $post_ids ) ) {
            $post_ids = array_map( 'absint', $post_ids );
            $and = 'AND ID IN ( ' . implode( ', ', $post_ids ) . ')';
        } else {
            $and = '';
        }

        $authors = array();
        $results = $wpdb->get_results( "SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status != 'auto-draft' $and" );
        foreach ( (array) $results as $result )
            $authors[] = get_userdata( $result->post_author );

        $authors = array_filter( $authors );

        foreach ( $authors as $author ) {
            echo "\t<wp:author>";
            echo '<wp:author_id>' . intval( $author->ID ) . '</wp:author_id>';
            echo '<wp:author_login>' . wxr_cdata( $author->user_login ) . '</wp:author_login>';
            echo '<wp:author_email>' . wxr_cdata( $author->user_email ) . '</wp:author_email>';
            echo '<wp:author_display_name>' . wxr_cdata( $author->display_name ) . '</wp:author_display_name>';
            echo '<wp:author_first_name>' . wxr_cdata( $author->first_name ) . '</wp:author_first_name>';
            echo '<wp:author_last_name>' . wxr_cdata( $author->last_name ) . '</wp:author_last_name>';
            echo "</wp:author>\n";
        }
    }

    /**
     * Output list of taxonomy terms, in XML tag format, associated with a post
     *
     * @since 2.3.0
     */
    function wxr_post_taxonomy() {
        $post = get_post();

        $taxonomies = get_object_taxonomies( $post->post_type );
        if ( empty( $taxonomies ) )
            return;
        $terms = wp_get_object_terms( $post->ID, $taxonomies );

        foreach ( (array) $terms as $term ) {
            echo "\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . wxr_cdata( $term->name ) . "</category>\n";
        }
    }

    /**
     * It filters out _edit_lock post meta
     *
     * @param bool   $return_me
     * @param string $meta_key
     * @return bool
     */
    function wxr_filter_postmeta( $return_me, $meta_key ) {
        if ( '_edit_lock' == $meta_key )
            $return_me = true;
        return $return_me;
    }
    add_filter( 'wxr_export_skip_postmeta', 'wxr_filter_postmeta', 10, 2 );

echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";
    ?>
    <!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your site. -->
    <!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
    <!-- You may use this file to transfer that content from one site to another. -->
    <!-- This file is not intended to serve as a complete backup of your site. -->

    <!-- To import this information into a WordPress site follow these steps: -->
    <!-- 1. Log in to that site as an administrator. -->
    <!-- 2. Go to Tools: Import in the WordPress admin panel. -->
    <!-- 3. Install the "WordPress" importer from the list. -->
    <!-- 4. Activate & Run Importer. -->
    <!-- 5. Upload this file using the form provided on that page. -->
    <!-- 6. You will first be asked to map the authors in this export file to users -->
    <!--    on the site. For each author, you may choose to map to an -->
    <!--    existing user on the site or to create a new user. -->
    <!-- 7. WordPress will then import each of the posts, pages, comments, categories, etc. -->
    <!--    contained in this file into your site. -->

    <?php the_generator( 'export' ); ?>
<rss version="2.0"
     xmlns:excerpt="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:wp="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/"
>

    <channel>
        <title><?php bloginfo_rss( 'name' ); ?></title>
        <link><?php bloginfo_rss( 'url' ); ?></link>
        <description><?php bloginfo_rss( 'description' ); ?></description>
        <pubDate><?php echo date( 'D, d M Y H:i:s +0000' ); ?></pubDate>
        <language><?php bloginfo_rss( 'language' ); ?></language>
        <wp:wxr_version><?php echo WXR_VERSION; ?></wp:wxr_version>
        <wp:base_site_url><?php echo wxr_site_url(); ?></wp:base_site_url>
        <wp:base_blog_url><?php bloginfo_rss( 'url' ); ?></wp:base_blog_url>
        <?php wxr_authors_list( $post_ids ); ?>

<?php if ( $post_ids ) {
    /**
     * @global WP_Query $wp_query
     */
    global $wp_query;

    // Fake being in the loop.
    $wp_query->in_the_loop = true;

    // Fetch 20 posts at a time rather than loading the entire table into memory.
    while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
        $where = 'WHERE ID IN (' . join( ',', $next_posts ) . ')';
        $posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );

        // Begin Loop.
        foreach ( $posts as $post ) {
            setup_postdata( $post );
            $is_sticky = is_sticky( $post->ID ) ? 1 : 0;
            ?>
        <item>
            <title><?php echo apply_filters( 'the_title_rss', $post->post_title );?></title>
            <link><?php the_permalink_rss() ?></link>
            <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
            <dc:creator><?php echo wxr_cdata( get_the_author_meta( 'login' ) ); ?></dc:creator>
            <guid isPermaLink="false"><?php the_guid(); ?></guid>
            <description></description>
            <content:encoded><?php
                /**
                 * Filters the post content used for WXR exports.
                 *
                 * @since 2.5.0
                 *
                 * @param string $post_content Content of the current post.
                 */
                echo wxr_cdata( apply_filters( 'the_content_export', $post->post_content ) );
                ?></content:encoded>
            <excerpt:encoded><?php
                /**
                 * Filters the post excerpt used for WXR exports.
                 *
                 * @since 2.6.0
                 *
                 * @param string $post_excerpt Excerpt for the current post.
                 */
                echo wxr_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) );
                ?></excerpt:encoded>
            <wp:post_id><?php echo intval( $post->ID ); ?></wp:post_id>
            <wp:post_date><?php echo wxr_cdata( $post->post_date ); ?></wp:post_date>
            <wp:post_date_gmt><?php echo wxr_cdata( $post->post_date_gmt ); ?></wp:post_date_gmt>
            <wp:comment_status><?php echo wxr_cdata( $post->comment_status ); ?></wp:comment_status>
            <wp:ping_status><?php echo wxr_cdata( $post->ping_status ); ?></wp:ping_status>
            <wp:post_name><?php echo wxr_cdata( $post->post_name ); ?></wp:post_name>
            <wp:status><?php echo wxr_cdata( $post->post_status ); ?></wp:status>
            <wp:post_parent><?php echo intval( $post->post_parent ); ?></wp:post_parent>
            <wp:menu_order><?php echo intval( $post->menu_order ); ?></wp:menu_order>
            <wp:post_type><?php echo wxr_cdata( $post->post_type ); ?></wp:post_type>
            <wp:post_password><?php echo wxr_cdata( $post->post_password ); ?></wp:post_password>
            <wp:is_sticky><?php echo intval( $is_sticky ); ?></wp:is_sticky>
            <?php
            // get listing attachments
            $listing_img = get_post_meta($post->ID, '_listing_img', true);
            $listing_imgs = !empty($listing_img) ? $listing_img : array();
            if ( !empty($listing_imgs) ) {
                foreach ($listing_imgs as $attachment_img_id) { ?>
            <wp:listing_img_url><?php echo wxr_cdata( wp_get_attachment_url( $attachment_img_id ) ); ?></wp:listing_img_url>
            <?php }
            } ?>


            <?php wxr_post_taxonomy(); ?>
            <?php $postmeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID ) );
            foreach ( $postmeta as $meta ) {
                /**
                 * Filters whether to selectively skip post meta used for WXR exports.
                 *
                 * Returning a truthy value to the filter will skip the current meta
                 * object from being exported.
                 *
                 * @since 3.3.0
                 *
                 * @param bool   $skip     Whether to skip the current post meta. Default false.
                 * @param string $meta_key Current meta key.
                 * @param object $meta     Current meta object.
                 */
                if ( apply_filters( 'wxr_export_skip_postmeta', false, $meta->meta_key, $meta ) ){ continue; }

                if ('_listing_img' == $meta->meta_key){
                     continue; // go to next iteration without further processing. because we have already handled listing image metas
                }
                ?>
                <wp:postmeta>
                    <wp:meta_key><?php echo wxr_cdata( $meta->meta_key ); ?></wp:meta_key>
                    <wp:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></wp:meta_value>
                </wp:postmeta>
            <?php	} // ends $postmeta loop ?>
        </item>
                <?php
            }
        }
    } ?>
    </channel>
</rss>
    <?php
}
