<?php
/**
 * Orders
 *
 * @package       directorist
 * @subpackage    directorist/includes/orders
 * @copyright     Copyright 2018. AazzTech
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since         3.1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * ATBDP_Order Class
 *
 * @since    3.1.0
 * @access   public
 */
class ATBDP_Order {


    public function __construct()
    {
        add_action( 'init', array($this, 'register_custom_post_type') );

        add_action( 'admin_footer-edit.php', array($this, 'admin_footer_edit') );
        add_action( 'restrict_manage_posts', array($this, 'restrict_manage_posts') );
        add_action( 'load-edit.php', array($this, 'load_edit') );
        add_action( 'admin_notices', array($this, 'admin_notices') );

        add_filter( 'parse_query', array($this, 'parse_query') );
        add_filter( 'manage_atbdp_orders_posts_columns', array($this, 'add_new_order_columns') );
        add_action('manage_atbdp_orders_posts_custom_column', array($this, 'manage_order_columns'), 10, 2);

        add_filter( 'manage_edit-atbdp_orders_sortable_columns', array($this, 'get_sortable_columns') );

        add_filter( 'post_row_actions', array($this, 'set_payment_receipt_link'), 10, 2 );

    }

    /**
     * Register a custom post type "atbdp_orders".
     *
     * @since    3.1.0
     * @access   public
     */
    public function register_custom_post_type() {

        $labels = array(
            'name'                => _x( 'Order History', 'Post Type General Name', ATBDP_TEXTDOMAIN ),
            'singular_name'       => _x( 'Order', 'Post Type Singular Name', ATBDP_TEXTDOMAIN ),
            'menu_name'           => __( 'Order History', ATBDP_TEXTDOMAIN ),
            'name_admin_bar'      => __( 'Order', ATBDP_TEXTDOMAIN ),
            'all_items'           => __( 'Order History', ATBDP_TEXTDOMAIN ),
            'add_new_item'        => __( 'Add New Order', ATBDP_TEXTDOMAIN ),
            'add_new'             => __( 'Add New', ATBDP_TEXTDOMAIN ),
            'new_item'            => __( 'New Order', ATBDP_TEXTDOMAIN ),
            'edit_item'           => __( 'Edit Order', ATBDP_TEXTDOMAIN ),
            'update_item'         => __( 'Update Order', ATBDP_TEXTDOMAIN ),
            'view_item'           => __( 'View Order', ATBDP_TEXTDOMAIN ),
            'search_items'        => __( 'Search Order', ATBDP_TEXTDOMAIN ),
            'not_found'           => __( 'No orders found', ATBDP_TEXTDOMAIN ),
            'not_found_in_trash'  => __( 'No orders found in Trash', ATBDP_TEXTDOMAIN ),
        );

        $args = array(
            'labels'              => $labels,
            'description'         => __( 'This order post type will keep track of user\'s order and payment status', ATBDP_TEXTDOMAIN ),
            'supports'            => array( 'title', 'author', ),
            'taxonomies'          => array( '' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => current_user_can( 'manage_atbdp_options' ) ? true : false, // show the menu only to the admin
            'show_in_menu'        => current_user_can( 'manage_atbdp_options' ) ? 'edit.php?post_type='.ATBDP_POST_TYPE : false,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'atbdp_order',
            'map_meta_cap'        => true,
        );

        register_post_type( 'atbdp_orders', $args );

    }

    /**
     * Add/Remove custom bulk actions to the select menus.
     * @todo; In future we may use add_action('bulk_actions-{screen_id}', 'my_bulk_action') when we will stop supporting WP < 4.7.
     * @see http://wpengineer.com/2803/create-your-own-bulk-actions/
     * @since    3.1.0
     * @access   public
     */
    public function admin_footer_edit() {

        global $post_type;
        if( 'atbdp_orders' == $post_type ) {

            ?>
            <script type="text/javascript">
                var atbdp_bulk_actions = <?php echo json_encode( atbdp_get_payment_bulk_actions() ); ?>;
                /*$actions = array(
                 'set_to_created'   => __( "Set Status to Created", ATBDP_TEXTDOMAIN ),
                 'set_to_pending'   => __( "Set Status to Pending", ATBDP_TEXTDOMAIN ),
                 'set_to_completed' => __( "Set Status to Completed", ATBDP_TEXTDOMAIN ),
                 'set_to_failed'    => __( "Set Status to Failed", ATBDP_TEXTDOMAIN ),
                 'set_to_cancelled' => __( "Set Status to Cancelled", ATBDP_TEXTDOMAIN ),
                 'set_to_refunded'  => __( "Set Status to Refunded", ATBDP_TEXTDOMAIN )
                 );*/
                jQuery(document).ready(function() {
                    for( var key in atbdp_bulk_actions ) {
                        if( atbdp_bulk_actions.hasOwnProperty( key ) ) {
                            var $option = jQuery('<option>').val( key ).text( atbdp_bulk_actions[ key ] );
                            $option.appendTo('#bulk-action-selector-top','#bulk-action-selector-bottom');
                            //$option.appendTo('#bulk-action-selector-bottom');
                        }
                    }

                    jQuery('select[name="action"]').find('option[value="edit"]').remove();
                    jQuery('select[name="action2"]').find('option[value="edit"]').remove();
                });
            </script>
            <?php
        }
    }


    /**
     * Add custom filter options.
     *
     * @since    3.1.0
     * @access   public
     */
    public function restrict_manage_posts() {

        global $typenow, $wp_query;

        if( 'atbdp_orders' == $typenow ) {

            // Restrict by payment status
            $statuses = atbdp_get_payment_statuses();
            /*
             * @todo; remove all helper comments once work is done..
             * $statuses = array(
                    'created'   => __( "Created", ATBDP_TEXTDOMAIN ),
                    'pending'   => __( "Pending", ATBDP_TEXTDOMAIN ),
                    'completed' => __( "Completed", ATBDP_TEXTDOMAIN ),
                    'failed'    => __( "Failed", ATBDP_TEXTDOMAIN ),
                    'cancelled' => __( "Cancelled", ATBDP_TEXTDOMAIN ),
                    'refunded'  => __( "Refunded", ATBDP_TEXTDOMAIN )
                );
            */
            $current_status = isset( $_GET['payment_status'] ) ? $_GET['payment_status'] : '';

            echo '<select name="payment_status">';
            echo '<option value="all">'.__( "All orders", ATBDP_TEXTDOMAIN ).'</option>';
            foreach( $statuses as $value => $title ) {
                printf( '<option value="%s" %s>%s</option>', $value, selected( $value, $current_status), $title );
            }
            echo '</select>';


        }

    }

    /**
     * Parse a query string and enable filter by post meta "payment_status"
     *
     * @since    3.1.0
     * @access   public
     *
     * @param	 WP_Query    $query    WordPress Query object
     */
    public function parse_query( $query ) {

        global $pagenow, $post_type;

        if( 'edit.php' == $pagenow && 'atbdp_orders' == $post_type ) {
            $st= !empty($_GET['_payment_status']) ? $_GET['_payment_status']: '';
            // Filter by post meta "payment_status"
            if( '' != $st && 'all' != $st ) {
                $query->query_vars['meta_key'] = '_payment_status';
                $query->query_vars['meta_value'] = sanitize_key( $st );
            }

        }

    }

    /**
     * Retrieve the table columns.
     *
     * @since    3.1.0
     * @access   public
     * @param    array $columns
     *
     * @return   array    $columns    Array of all the list table columns.
     */
    public function add_new_order_columns($columns) {

        $columns = array(
            'cb'             => '<input type="checkbox" />', // Render a checkbox instead of text
            'ID'             => __( 'Order ID', ATBDP_TEXTDOMAIN ),
            'details'        => __( 'Details', ATBDP_TEXTDOMAIN ),
            'amount'         => __( 'Amount', ATBDP_TEXTDOMAIN ),
            'type'           => __( 'Payment Type', ATBDP_TEXTDOMAIN ),
            'transaction_id' => __( 'Transaction ID', ATBDP_TEXTDOMAIN ),
            'customer'       => __( 'Customer', ATBDP_TEXTDOMAIN ),
            'date'           => __( 'Date', ATBDP_TEXTDOMAIN ),
            'status'         => __( 'Status', ATBDP_TEXTDOMAIN ),
        );

        return $columns;

    }

    /**
     * This function renders the custom columns in the list table.
     *
     * @since    3.1.0
     * @access   public
     *
     * @param    string    $column    The name of the column.
     * @param    string    $post_id   Post ID.
     */
    public function manage_order_columns( $column, $post_id ) {

        global $post;
        switch ( $column ) {
            case 'ID' :
                printf( '<a href="%s" target="_blank">Order #%d</a>', ATBDP_Permalink::get_payment_receipt_page_link( $post_id ), $post_id );
                break;
            case 'details' :
                $listing_id = get_post_meta( $post_id, '_listing_id', true );
                printf( '<p><a href="%s"> %s: [Listing ID #%d]</a></p>', get_edit_post_link( $listing_id ), get_the_title( $listing_id ),  $listing_id );

                $order_details = apply_filters( 'atbdp_order_details', array(), $post_id, $listing_id);
                foreach( $order_details as $order_detail ) {
                    echo '<div>#Short Notes: '.$order_detail['label'].'</div>';
                }

                $featured = get_post_meta( $post_id, '_featured', true ); // is this listing featured ?
                if( $featured ) {
                    $f_title = get_directorist_option( 'featured_listing_title' );
                    echo "<div>({$f_title})</div>";
                }
                break;
            case 'amount' :
                $amount = get_post_meta( $post_id, '_amount', true );
                $amount = atbdp_format_payment_amount( $amount ); // get a formatted current amount

                $value = atbdp_payment_currency_filter( $amount ); // add a currency sign before the price
                echo $value;
                break;
            case 'type' :
                $gateway = get_post_meta( $post_id, '_payment_gateway', true );
                if( 'free' == $gateway ) {
                    _e( 'Free Submission', ATBDP_TEXTDOMAIN );
                } else {
                    $label = apply_filters('atbdp_'.$gateway.'gateway_label', '');
                    echo ! empty( $label ) ? $label : $gateway;
                }
                break;
            case 'transaction_id' :
                echo get_post_meta( $post_id, '_transaction_id', true );
                break;
            case 'customer' :
                $user_info = get_userdata( $post->post_author );

                printf( '<p><a href="%s">%s</a></p>', get_edit_user_link( $user_info->ID ), $user_info->display_name );
                echo $user_info->user_email;
                break;
            case 'date' :
                $date = strtotime( $post->post_date );
                $value = date_i18n( get_option( 'date_format' ), $date );

                echo $value;
                break;
            case 'status' :
                $value = get_post_meta( $post_id, '_payment_status', true );
                echo atbdp_get_payment_status_i18n( $value );
                break;
        }

    }

    /**
     * Retrieve the table's sortable columns.
     *
     * @since    3.1.0
     * @access   public
     *
     * @return   array    Array of all the sortable columns
     */
    public function get_sortable_columns() {

        $columns = array(
            'ID' 	    => 'ID',
            'amount'    => 'amount',
            'type'      => 'type',
            'customer'  => 'customer',
            'date' 	    => 'date',
            'status'    => 'status',
        );

        return $columns;

    }

    /**
     * Called only in /wp-admin/edit.php* pages.
     *
     * @since    3.1.0
     * @access   public
     */
    public function load_edit() {

        // Handle the custom bulk action
        global $typenow;
        $post_type = $typenow;

        if( 'atbdp_orders' == $typenow ) {

            // Get the action
            $wp_list_table = _get_list_table('WP_Posts_List_Table');
            $action = $wp_list_table->current_action();

            $allowed_actions = array_keys( atbdp_get_payment_bulk_actions() );
            if( ! in_array( $action, $allowed_actions ) ) return;

            // Security check
            check_admin_referer('bulk-posts');

            // Make sure ids are submitted
            if( isset( $_REQUEST['post'] ) ) {
                $post_ids = array_map( 'intval', $_REQUEST['post'] );
            }

            if( empty( $post_ids ) ) return;

            // This is based on wp-admin/edit.php
            $sendback = remove_query_arg( array_merge( $allowed_actions, array( 'untrashed', 'deleted', 'ids' ) ), wp_get_referer() );
            if( ! $sendback ) $sendback = admin_url( "edit.php?post_type=$post_type" );

            $pagenum = $wp_list_table->get_pagenum();
            $sendback = add_query_arg( 'paged', $pagenum, $sendback );

            $modified = 0;
            foreach( $post_ids as $post_id ) {
                if( ! $this->update_payment_status( $action, $post_id ) )	wp_die( __( 'Error updating post.', ATBDP_TEXTDOMAIN ) );
                $modified++;
            }

            $sendback = add_query_arg( array( $action => $modified, 'ids' => join( ',', $post_ids ) ), $sendback );
            $sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view' ), $sendback );

        }

        // Add filter to sort columns
        add_filter( 'request', array( $this, 'sort_columns' ) );

    }

    /**
     * Update payment status.
     *
     * @since    3.1.0
     * @access   public
     *
     * @param    string    $action    Action to be performed.
     * @param    int       $post_id   Post ID.
     * @return 	 boolean              If the save was successful or not.
     */
    public function update_payment_status( $action, $post_id ) {

        $old_status = get_post_meta( $post_id, '_payment_status', true );

        $new_status = str_replace( 'set_to_', '', $action );
        $new_status = sanitize_key( $new_status );

        if( $new_status == $old_status ) return true;

        do_action( 'atbdp_order_status_changed', $new_status, $old_status, $post_id );

        $non_complete_statuses = array( 'created', 'pending', 'failed', 'cancelled', 'refunded' );

        // If the order has featured
        $featured = get_post_meta( $post_id, '_featured', true );
        $listing_id = get_post_meta( $post_id, '_listing_id', true );

        if( ! empty( $featured ) ) {

            if( 'completed' == $old_status && in_array( $new_status, $non_complete_statuses ) ) {
                update_post_meta( $listing_id, '_featured', 0 );
            } else if( in_array( $old_status, $non_complete_statuses ) && 'completed' == $new_status ) {
                update_post_meta( $listing_id, '_featured', 1 );
            }
        }

        // Update new status
        update_post_meta( $post_id, '_payment_status', $new_status );

        // Email listing owner when his/her set to completed
        if( in_array( $old_status, $non_complete_statuses ) && 'completed' == $new_status ) {
            ATBDP()->email->notify_owner_order_completed($post_id, $listing_id);
            ATBDP()->email->notify_admin_order_completed($post_id, $listing_id);
        }

        return true;

    }

    /**
     * Display an admin notice on the payment history page after performing
     * a bulk action.
     *
     * @since    3.1.0
     * @access   public
     */
    public function admin_notices() {

        global $pagenow, $post_type;

        if( 'edit.php' == $pagenow && 'atbdp_orders' == $post_type ) {

            $message = '';
            $allowed_actions = array_keys( atbdp_get_payment_bulk_actions() );

            foreach( $allowed_actions as $action ) {
                $_action = str_replace( 'set_to_', '', $action );
                if( isset( $_REQUEST[ $action ] ) && (int) $_REQUEST[ $action ] ) {
                    $message = sprintf( _n( "Order set to $_action.", "%s orders set to $_action.", $_REQUEST[ $action ], ATBDP_TEXTDOMAIN ), number_format_i18n( $_REQUEST[ $action ] ) );
                    break;
                }
            }

            if( ! empty( $message ) ) echo "<div class='updated'> <p>{$message}</p></div>";

        }

    }

    /**
     * Sort custom columns.
     *
     * @since    3.1.0
     * @access   public
     *
     * @param    array    $vars    Array of query variables.
     * @return array
     */
    public function sort_columns( $vars ) {
        // Check if we're viewing the 'atbdp_orders' post type
        if( isset( $vars['post_type'] ) && 'atbdp_orders' == $vars['post_type'] ) {
            // Check if 'orderby' is set to 'amount'
            if ( isset( $vars['orderby'] ) && 'amount' == $vars['orderby'] ) {
                // Merge the query vars with our custom variables.
                $vars = array_merge(
                    $vars,
                    array(
                        'meta_key' => 'amount',
                        'orderby'  => 'meta_value_num'
                    )
                );
            }
        }
        return $vars;
    }

    /**
     * It returns order details in html format
     * @param int $order_id The order ID
     * @return string
     */
    public static function get_order_details($order_id)
    {
        if (empty($order_id)) return __('No Order ID Provided', ATBDP_TEXTDOMAIN);
        $c_position = get_directorist_option('payment_currency_position');
        $currency = atbdp_get_payment_currency();
        $symbol = atbdp_currency_symbol($currency);
        $order_items = apply_filters( 'atbdp_order_items', array(), $order_id ); // this is the hook that an extension can hook to, to add new items on checkout page.eg. plan

        $featured = get_post_meta( $order_id, '_featured', true );
        if( $featured ) {
            $order_items[] = atbdp_get_featured_settings_array();
        }

        // fix currency symbol position
        $before = ''; $after = '';
        ('after' == $c_position) ? $after = $symbol : $before = $symbol;

        ob_start();
        ?>
        <table border="0" cellspacing="0" cellpadding="7" style="border:1px solid #CCC;">
            <tr style="background-color:#F0F0F0;">
                <th style="border-right:1px solid #CCC; border-bottom:1px solid #CCC; text-align:left;"><?php _e( 'Item(s)', ATBDP_TEXTDOMAIN ); ?></th>
                <th style="border-bottom:1px solid #CCC;"><?php printf( __( 'Price [%s]', ATBDP_TEXTDOMAIN ), $currency ); ?></th>
            </tr>
            <?php foreach( $order_items as $order ) : ?>
                <tr>
                    <td style="border-right:1px solid #CCC; border-bottom:1px solid #CCC;">
                        <h3><?php echo $order['label']; ?></h3>
                        <?php if( isset( $order['desc'] ) ) echo $order['desc']; ?>
                    </td>
                    <td style="border-bottom:1px solid #CCC;">
                        <?php echo $before.esc_html(atbdp_format_payment_amount($order['price'])).$after;?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td style="border-right:1px solid #CCC; text-align:right; vertical-align:middle;">
                    <?php printf( __( 'Total amount [%s]', ATBDP_TEXTDOMAIN ), $currency ); ?>
                </td>
                <td>
                    <?php
                    $amount = get_post_meta( $order_id, '_amount', true );
                    echo $before.esc_html(atbdp_format_payment_amount($amount)).$after;
                    ?>
                </td>
            </tr>
        </table>
        <?php
        return ob_get_clean();
    }

    /**
     * It sets the view link of the order to the payment receipt page on the front end where the shortcode of payment receipt has been used.
     *
     * @param array     $actions        The array of post actions
     * @param WP_Post   $post           The current post post
     * @return array    $actions        It returns the array of post actions after modifying the order view link
     */
    public function set_payment_receipt_link($actions, WP_Post $post ) {
        if ( $post->post_type != 'atbdp_orders' ) return $actions;
        $actions['view'] = sprintf("<a href='%s'>%s</a>", ATBDP_Permalink::get_payment_receipt_page_link($post->ID), __('View', ATBDP_TEXTDOMAIN));
        return $actions;
    }

}