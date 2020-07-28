<?php
/**
 * Rating Review DB class
 *
 * This class is for interacting with the star and review database table
 *
 * @package     ATBDP
 * @subpackage  inlcudes/review-rating Rating Review
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'You should not access this file directly'  ); }

if (!class_exists('ATBDP_Review_Rating_DB')):

    class ATBDP_Review_Rating_DB extends ATBDP_Database {
        public $charset = 'utf8mb4';
        public $collate = 'utf8mb4_bin';

        /**
         * Get things started
         *
         * @access  public
         * @since   1.0
         */
        public function __construct() {
            global $wpdb;
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $this->table_name  = $wpdb->prefix . 'atbdp_review';
            $this->primary_key = 'id';
            $this->version     = '1.0';
        }

        /**
         * Get Charset Collate
         *
         * @access public
         * @since 6.4.4
         */
        public function get_charset_collate()
        {
            return $this->charset . "::" . $this->collate;
        }

        /**
         * Get columns and formats
         *
         * @access  public
         * @since   1.0
         */
        public function get_columns() {
            return array(
                'id'             => '%d',
                'post_id'             => '%d',
                'name'           => '%s',
                'email'          => '%s',
                'content'    => '%s',
                'rating'    => '%d',
                'by_guest'        => '%d',
                'by_user_id'        => '%d',
                'date_created'   => '%s',
            );
        }


        /**
         * Get default column values
         *
         * @access  public
         * @since   1.0
         */
        public function get_column_defaults() {
            return array(
                'post_id'             => 0,
                'name'           => '',
                'email'          => '',
                'content'    => '',
                'rating'    => 1,
                'by_guest'        => 0,
                'by_user_id'        => 0,
                'date_created'   => date( 'Y-m-d H:i:s' ),
            );
        }


        /**
         * Add a review/ update a review if it exists
         * @param array $data The data to be saved eg. $args = array(
                                            'post_id'          => $post_id,
                                            'name'           => $user ? $user->display_name : '',
                                            'email'          => $email,
                                            'by_user_id'        => $user ? $user->ID : 0,
                                            );
         * @access  public
         * @since   1.0
         * @return int It returns the inserted row id or the updated row id
         */
        public function add( $data = array() ) {
        global $wpdb;
            $defaults = array(
                'id' => 0,
                'post_id' => 0,
                'by_user_id' => 0,
            );

            $args = wp_parse_args( $data, $defaults );

            if( empty( $args['post_id'] ) ) {
                return false; // no post id given, so where will you attach the review? so return false
            }

            /*if the post has an id, then the review needs update if it exists in the database */
            if (!empty($args['id'])){
                $review = $this->get_review_by( 'id', intval($args['id']) ); // get a review by a review id

                if( $review ) {
                    // update an existing review
                    $this->update( $review->id, $args );
                    return $review->id; // return the old id

                }
            }

            /* If a post's review has a user email and it is equal to the current/posted user email then update it.
            IN other word, a user can not add more than one review for a post */
            if (!empty($args['email']) && !empty($args['post_id'])) {
                $review_exist = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE post_id=%s AND email = %s LIMIT 1", array( absint($args['post_id']), sanitize_email($args['email']))));

                if( $review_exist ) {
                    $args['id'] = $review_exist->id;  // set the review id of old review that we are going to replace as $args may not have id set at this point
                    // update an existing review
                    $this->update( $review_exist->id, $args );
                    return $review_exist->id; // return the old id


                }
            }


            /**
             * @since 5.4.2
             */
            do_action('atbdp_before_insert_review', $args);
            // finally insert new data if update was not done.
            return $this->insert( $args, 'review' ); // return the inserted id



        }


        /**
         * Retrieves a single review from the database
         *
         * @access public
         * @since  2.3
         * @param  string $field id or post_id, by_user_id, email
         * @param  mixed  $value  The Review ID or post_id to search
         * @return mixed          Upon success, an object of the review. Upon failure, NULL
         */
        public function get_review_by( $field = 'id', $value = 0 ) {
            global $wpdb;

            if ( empty( $field ) || empty( $value ) ) {
                return NULL;
            }

            if ( 'id' == $field || 'post_id' == $field ) {
                // Make sure the value is numeric to avoid casting objects, for example,
                // to int 1.
                if ( ! is_numeric( $value ) ) {
                    return false;
                }

                $value = intval( $value );

                if ( $value < 1 ) {
                    return false;
                }

            } elseif ( 'email' === $field ) {

                if ( ! is_email( $value ) ) {
                    return false;
                }

                $value = sanitize_email( $value );
            }

            if ( ! $value ) {
                return false;
            }

            switch ( $field ) {
                case 'id':
                    $db_field = 'id';
                    break;
                case 'post_id':
                    $db_field = 'post_id';
                    break;
                case 'email':
                    $value    = sanitize_email( $value );
                    $db_field = 'email';
                    break;
                case 'by_user_id':
                    $db_field = 'by_user_id';
                    break;
                default:
                    return false;
            }

            if ( ! $review = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $db_field = %s LIMIT 1", $value ) ) ) { return false; }

            return $review;
        }


        /**
         * Retrieves all reviews from the database based on the field given in the argument
         *
         * @access public
         * @since  2.3
         * @param  string $field [Optional] id or post_id, by_user_id, email
         * @param  mixed  $value  [Optional] The Review ID or post_id to search
         * @param int $limit       [Optional]. Limit the number of the post we get from the database.
         * @param int $offset       [Optional]. Offset the record for pagination purpose or when using with ajax
         * @param string $order_by [Optional] Name of the column to order by
         * @param string $order     Order name Ascending or descending Accepted Value ASE, or DSEC. Default is DESC.
         * @param string $output [Optional]. Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants. Default is OBJECT.
         *                       With one of the first three, return an array of rows indexed from 0 by SQL result row number.
         *                       Each row is an associative array (column => value, ...), a numerically indexed array (0 => value, ...), or an object. ( ->column = value ), respectively.
         *                       With OBJECT_K, return an associative array of row objects keyed by the value of each row's first column's value.
         *                       Duplicate keys are discarded.
         * @return mixed          Upon success, an object of the review. Upon failure, NULL
         */
        public function get_reviews_by( $field = 'id', $value = 0,  $offset = 0 , $limit=PHP_INT_MAX, $order_by='date_created', $order='DESC', $output = 'OBJECT') {
            global $wpdb;

            if ( empty( $field ) || empty( $value ) ) {
                return NULL;
            }

            if ( 'id' == $field || 'post_id' == $field ) {
                // Make sure the value is numeric to avoid casting objects, for example,
                // to int 1.
                if ( ! is_numeric( $value ) ) {
                    return false;
                }

                $value = intval( $value );

                if ( $value < 1 ) {
                    return false;
                }

            } elseif ( 'email' === $field ) {

                if ( ! is_email( $value ) ) {
                    return false;
                }

                $value = sanitize_email( $value );
            }

            if ( ! $value ) {
                return false;
            }

            switch ( $field ) {
                case 'id':
                    $db_field = 'id';
                    break;
                case 'post_id':
                    $db_field = 'post_id';
                    break;
                case 'email':
                    $value    = sanitize_email( $value );
                    $db_field = 'email';
                    break;
                case 'by_user_id':
                    $db_field = 'by_user_id';
                    break;
                default:
                    return false;
            }

            if(!empty($order_by) && $order){
                $orderby = sprintf("ORDER BY %s %s", $order_by, $order);
            }else{
                $orderby='ORDER BY id ASC';
            }

            if ( ! $reviews = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $db_field = %s $orderby LIMIT %d, %d", $value, $offset, $limit ), $output ) ) { return false; }

            return $reviews;
        }

        /**
         * Retrieve all rows by the primary key
         *
         * @param int $limit Number of rows to be selected from the database
         * @param string $order_by [optional] Name of the the column to order by. Default date_created
         * @param string $order    [optional] Arrangement type of the result eg. ASC or DESC. Default DESC.
         * @access  public
         * @since   1.0
         * @return  object It returns all the column from the database limited by the by the number given as the argument.
         */
        public function get_all( $limit=-1, $order_by='date_created', $order='DESC' ) {
            global $wpdb;
            if(!empty($order_by) && $order){
                $orderby = sprintf("ORDER BY %s %s", $order_by, $order);
            }else{
                $orderby='ORDER BY id ASC';
            }
            return $wpdb->get_results($wpdb->prepare( "SELECT * FROM $this->table_name WHERE 1=1 $orderby LIMIT %d;", intval($limit) ));

        }




        /**
         * Retrieve one review from the database for a post by a user
         *
         * @access  public
         * @since   1.0
         * @param   int $user_id The id of the user to get review for
         * @param   int $post_id  The id of the post whose review we want to get
         * @return  object|array It returns a single review of a user for a post
         */
        public function get_user_review_for_post( $user_id, $post_id ) {
            global $wpdb;
            return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE by_user_id = %d AND post_id = %d;", absint( $user_id ), absint( $post_id )) );
        }


        /**
         * Retrieves all reviews or number of the reviews of specific post based on rating
         * from the database based on the field given in the argument
         *
         * @access public
         * @since  2.3
         * @param  string $field id or post_id, by_user_id, email
         * @param  mixed  $value  The Review ID or post_id to search
         * @param  int  $rating  Rating value eg. 5
         * @param  bool  $count  Whether to return count value or the full result
         * @param int $limit       Optional. Limit the number of the post we get from the database.
         * @return mixed          Upon success, an object of the review. Upon failure, NULL
         */
        public function get_reviews_by_rating( $field = 'id', $value = 0, $rating=5, $count=false, $limit=PHP_INT_MAX ) {
            global $wpdb;

            if ( empty( $field ) || empty( $value ) ) {
                return NULL;
            }

            if ( 'id' == $field || 'post_id' == $field ) {
                // Make sure the value is numeric to avoid casting objects, for example,
                // to int 1.
                if ( ! is_numeric( $value ) ) {
                    return false;
                }

                $value = intval( $value );

                if ( $value < 1 ) {
                    return false;
                }

            } elseif ( 'email' === $field ) {

                if ( ! is_email( $value ) ) {
                    return false;
                }

                $value = trim( $value );
            }

            if ( ! $value ) {
                return false;
            }

            switch ( $field ) {
                case 'id':
                    $db_field = 'id';
                    break;
                case 'post_id':
                    $db_field = 'post_id';
                    break;
                case 'email':
                    $value    = sanitize_email( $value );
                    $db_field = 'email';
                    break;
                case 'by_user_id':
                    $db_field = 'by_user_id';
                    break;
                default:
                    return false;
            }

            // check if we need to return the count or the result
            if ($count){
                $arg = "SELECT COUNT($this->primary_key) FROM $this->table_name WHERE $db_field = %s AND 'rating' = %d LIMIT %d";
                if ( ! $reviews = $wpdb->get_results( $wpdb->prepare( $arg, $value, $rating, $limit ) ) ) { return false; }
            }else{
                if ( ! $reviews = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $db_field = %s AND 'rating' = %d LIMIT %d", $value, $rating, $limit ) ) ) { return false; }
            }



            return $reviews;
        }


        /**
         * Retrieves all reviews from the database based on the field given in the argument
         *
         * @access public
         * @since  2.3
         * @param  string $field id or post_id, by_user_id, email
         * @param  mixed  $value  The Review ID or post_id to search
         * @param int $limit       Optional. Limit the number of the post we get from the database.
         * @return mixed          Upon success, an object of the review. Upon failure, NULL
         */
        public function get_ratings_by($field = 'id', $value = 0, $limit=PHP_INT_MAX)
        {
            global $wpdb;

            if ( empty( $field ) || empty( $value ) ) {
                return NULL;
            }

            if ( 'id' == $field || 'post_id' == $field ) {
                // Make sure the value is numeric to avoid casting objects, for example,
                // to int 1.
                if ( ! is_numeric( $value ) ) {
                    return false;
                }

                $value = intval( $value );

                if ( $value < 1 ) {
                    return false;
                }

            } elseif ( 'email' === $field ) {

                if ( ! is_email( $value ) ) {
                    return false;
                }

                $value = sanitize_email( $value );
            }

            if ( ! $value ) {
                return false;
            }

            switch ( $field ) {
                case 'id':
                    $db_field = 'id';
                    break;
                case 'post_id':
                    $db_field = 'post_id';
                    break;
                case 'email':
                    if (is_email($value)){
                        $value    = sanitize_email( $value );
                        $db_field = 'email';
                    }

                    break;
                case 'by_user_id':
                    $db_field = 'by_user_id';
                    break;
                default:
                    return false;
            }

            $cache_key = md5( 'atbdp_ratings' . serialize( $value ) );
            $ratings = wp_cache_get( $cache_key, 'ratings' );

            if ( $ratings === false ) {
                $args = $wpdb->prepare( "SELECT rating FROM $this->table_name WHERE $db_field = %s LIMIT %d", $value, $limit );
                if ( ! $ratings = $wpdb->get_results( $args ) ) { return false; }
                wp_cache_set( $cache_key, $ratings, 'ratings', 5 ); // cache it for 1 minutes now then increase  it to 1 hour
            }
            return $ratings;
        }


        /**
         * It deletes all the reviews based on a column and given value
         * @param string $field  Name of the field/column in the table eg. id
         * @param string $value  the  value of the field in the table
         * @return bool It returns true on success and false on failure
         */
        public function delete_reviews_by( $field='id', $value='' )
        {

            global $wpdb;

            if ( empty( $field ) || empty( $value ) ) {
                return NULL;
            }

            if ( 'id' == $field || 'post_id' == $field ) {
                // Make sure the value is numeric to avoid casting objects, for example,
                // to int 1.
                if ( ! is_numeric( $value ) ) {
                    return false;
                }

                $value = intval( $value );

                if ( $value < 1 ) {
                    return false;
                }

            } elseif ( 'email' === $field ) {

                if ( ! is_email( $value ) ) {
                    return false;
                }

                $value = sanitize_email( $value );
            }

            if ( ! $value ) {
                return false;
            }

            switch ( $field ) {
                case 'id':
                    $where = sprintf("$this->primary_key = %d", absint($value));
                    break;
                case 'post_id':
                    $where = sprintf("post_id = %d", absint($value));
                    break;
                case 'email':
                    $where = sprintf("email = %ds", sanitize_email( $value ));
                    break;
                case 'by_user_id':
                    $where = sprintf("by_user_id = %d", absint($value));
                    break;
                default:
                    return false;
            }

            if ( false === $wpdb->query( "DELETE FROM $this->table_name WHERE $where" ))  {
                return false;
            }


            return true;
        }



        /**
         * Count the total number of reviews in the database
         *
         * @param array $args argument needed to count reviews based on. eg. ['id'=>3]
         * @access  public
         * @since   1.0
         * @return int It returns the number of reviews based on the passed argument
         */
        public function count( $args = array() ) {

            global $wpdb;


            $where = ' WHERE 1=1 ';

            // specific reviews
            if( ! empty( $args['id'] ) ) {

                if( is_array( $args['id'] ) ) {
                    $ids = implode( ',', array_map('intval', $args['id'] ) );
                } else {
                    $ids = intval( $args['id'] );
                }

                $where .= " AND `id` IN( {$ids} ) ";

            }

            // reviews for specific user accounts
            if( ! empty( $args['by_user_id'] ) ) {

                if( is_array( $args['by_user_id'] ) ) {
                    $by_user_ids = implode( ',', array_map('intval', $args['by_user_id'] ) );
                } else {
                    $by_user_ids = intval( $args['by_user_id'] );
                }

                $where .= " AND `by_user_id` IN( {$by_user_ids} ) ";

            }


            // reviews for specific post_id
            if( ! empty( $args['post_id'] ) ) {

                if( is_array( $args['post_id'] ) ) {
                    $post_ids = implode( ',', array_map('intval', $args['post_id'] ) );
                } else {
                    $post_ids = intval( $args['post_id'] );
                }

                $where .= " AND `post_id` IN( {$post_ids} ) ";

            }

            //specific reviews by email
            if( ! empty( $args['email'] ) ) {

                if( is_array( $args['email'] ) ) {

                    $emails_count       = count( $args['email'] );
                    $emails_placeholder = array_fill( 0, $emails_count, '%s' );
                    $emails             = implode( ', ', $emails_placeholder );

                }else{
                    $email            = sanitize_email($args['email']);
                    $where .= $wpdb->prepare( " AND `email` = '$email'  ", $args['email'] );

                }


            }

            // specific reviews by name
            if( ! empty( $args['name'] ) ) {
                $where .= $wpdb->prepare( " AND `name` LIKE '%%%%" . '%s' . "%%%%' ", $args['name'] );
            }

            // Reviews created for a specific date or in a date range
            if( ! empty( $args['date'] ) ) {

                if( is_array( $args['date'] ) ) {

                    if( ! empty( $args['date']['start'] ) ) {

                        $start = date( 'Y-m-d 00:00:00', strtotime( $args['date']['start'] ) );
                        $where .= " AND `date_created` >= '{$start}'";

                    }

                    if( ! empty( $args['date']['end'] ) ) {

                        $end = date( 'Y-m-d 23:59:59', strtotime( $args['date']['end'] ) );
                        $where .= " AND `date_created` <= '{$end}'";

                    }

                } else {

                    $year  = date( 'Y', strtotime( $args['date'] ) );
                    $month = date( 'm', strtotime( $args['date'] ) );
                    $day   = date( 'd', strtotime( $args['date'] ) );

                    $where .= " AND $year = YEAR ( date_created ) AND $month = MONTH ( date_created ) AND $day = DAY ( date_created )";
                }

            }

            $cache_key = md5( 'atbdp_reviews_count' . serialize( $args ) );

            $count = wp_cache_get( $cache_key, 'reviews' );

            if( $count === false ) {
                $query = "SELECT COUNT($this->primary_key) FROM " . $this->table_name . "{$where};";
                $count = $wpdb->get_var( $query );
                wp_cache_set( $cache_key, $count, 'reviews', 3600 );
            }

            return absint( $count );

        }


        /**
         * Checks if a review exists
         *
         * @access  public
         * @since   1.0
         * @param string $value
         * @param string $field
         * @return bool It returns true if a review exists, otherwise false
         */
        public function exists( $value = '', $field = 'id' ) {

            $columns = $this->get_columns();
            if ( ! array_key_exists( $field, $columns ) ) {
                return false;
            }

            return (bool) $this->get_column_by( 'id', $field, $value );

        }

        /**
         * Create review table
         *
         * @access public
         * @since 1.0
         */
        public function create_table()
        {
            global $wpdb;
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			post_id bigint(20) unsigned NOT NULL,
			name varchar(50) NOT NULL,
			email varchar(50) NOT NULL,
			content longtext NOT NULL,
			rating float(10) NOT NULL,
			by_guest tinyint(1),
			by_user_id bigint(20) unsigned,
			date_created datetime NOT NULL,
			PRIMARY KEY  (id),
		    KEY user (post_id)
            ) CHARACTER SET {$this->charset} COLLATE {$this->collate};";
            //  if we have already created a table then the _db_version should be the same next time if this function runs, and the check below will prevent the plugin to use dbDelta twice unnecessarily. During testing/development process, remove the condition, or delete the option or increase $this->version
            if (get_option($this->table_name . '_db_version') < $this->version){
                dbDelta( $sql );
            }

            update_option( $this->table_name . '_db_version', $this->version );
        }

        
        /**
         * Update Table Collation
         *
         * @access public
         * @since 6.4.4
         */
        public function update_table_collation() {
            global $wpdb;
            $table = 'wp_atbdp_review';

            $charset = $this->charset;
            $collate = $this->collate;

            $wpdb->query($wpdb->prepare("ALTER TABLE $table CONVERT TO CHARACTER SET $charset COLLATE $collate;"));
        }
    }

endif;
