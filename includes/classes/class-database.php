<?php
/**
 * DB class of Directorist
 *
 * This class is for interacting database table
 *
 * @package     Directorist
 * @subpackage  Classes/ATBDP_Database
 * @copyright   Copyright (c) 2018, AazzTech
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
defined('ABSPATH') || die( 'Direct access is not allowed.' );
if (!class_exists('ATBDP_Database')):

abstract class ATBDP_Database {


    /**
     * The name of our database table
     *
     * @access  public
     * @since   1.0
     */
    public $table_name;

    /**
     * The version of our database table
     *
     * @access  public
     * @since   1.0
     */
    public $version;

    /**
     * The name of the primary column
     *
     * @access  public
     * @since   1.0
     */
    public $primary_key;

    /**
     * Get things started
     *
     * @access  public
     * @since   1.0
     */
    public function __construct() {}

    /**
     * Whitelist of columns
     *
     * @access  public
     * @since   1.0
     * @return  array
     */
    public function get_columns() {
        return array();
    }

    /**
     * Default column values
     *
     * @access  public
     * @since   1.0
     * @return  array
     */
    public function get_column_defaults() {
        return array();
    }

    /**
     * Retrieve a row by the primary key
     *
     * @access  public
     * @since   1.0
     * @return  object
     */
    public function get( $row_id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
    }

    /**
     * Retrieve all rows of $this->table_name
     *
     * @param int $limit Number of rows to be selected from the database
     * @access  public
     * @since   1.0
     * @return  object It returns all the column from the database limited by the by the number given as the argument.
     */
    public function get_all( $limit=-1 ) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare( "SELECT * FROM $this->table_name WHERE 1=1 LIMIT %d;", $limit ));

    }



    /**
     * Retrieve a row by a specific column / value
     *
     * @param string $column Name of the column to use in WHERE clause
     * @param mixed $column_value The value of the column to be used in the WHERE clause
     * @access  public
     * @since   1.0
     * @return  object
     */
    public function get_by( $column, $column_value ) {
        global $wpdb;
        $column = esc_sql( $column );
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $column_value ) );
    }

    /**
     * Retrieve a specific column's value by the primary key
     *
     * @param string $column Name of the column to SELECT
     * @param int     $row_id The primary id of the column
     * @access  public
     * @since   1.0
     * @return  string
     */
    public function get_column( $column, $row_id ) {
        global $wpdb;
        $column = esc_sql( $column );
        return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
    }

    /**
     * Retrieve a specific column's value by the the specified column / value
     *
     * @access  public
     * @since   1.0
     * @return  string
     */
    public function get_column_by( $column, $column_where, $column_value ) {
        global $wpdb;
        $column_where = esc_sql( $column_where );
        $column       = esc_sql( $column );
        return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value ) );
    }




    /**
     * Insert a new row
     *
     * @access  public
     * @since   1.0
     * @return  int
     */
    public function insert( $data, $type = '' ) {
        global $wpdb;

        // Set default values
        $data = wp_parse_args( $data, $this->get_column_defaults() );

        do_action( 'atbdp_pre_insert_' . $type, $data );

        // Initialise column format array
        $column_formats = $this->get_columns();

        // Force fields to lower case
        $data = array_change_key_case( $data );

        // White list columns
        $data = array_intersect_key( $data, $column_formats );

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys( $data );
        $column_formats = array_merge( array_flip( $data_keys ), $column_formats );

        $wpdb->insert( $this->table_name, $data, $column_formats );
        $wpdb_insert_id = $wpdb->insert_id;

        do_action( 'atbdp_post_insert_' . $type, $wpdb_insert_id, $data );

        return $wpdb_insert_id;
    }

    /**
     * Update a row
     *
     * @access  public
     * @since   1.0
     * @return  bool
     */
    public function update( $row_id, $data = array(), $where = '' ) {

        global $wpdb;

        // Row ID must be positive integer
        $row_id = absint( $row_id );

        if( empty( $row_id ) ) {
            return false;
        }

        if( empty( $where ) ) {
            $where = $this->primary_key;
        }

        // Initialise column format array
        $column_formats = $this->get_columns();

        // Force fields to lower case
        $data = array_change_key_case( $data );

        // White list columns
        $data = array_intersect_key( $data, $column_formats );

        // Reorder $column_formats to match the order of columns given in $data
        $data_keys = array_keys( $data );
        $column_formats = array_merge( array_flip( $data_keys ), $column_formats );

        if ( false === $wpdb->update( $this->table_name, $data, array( $where => $row_id ), $column_formats ) ) {
            return false;
        }

        return true;
    }

    /**
     * Delete a row identified by the primary key
     *
     * @access  public
     * @since   1.0
     * @return  bool It returns true on success and false on failure
     */
    public function delete( $row_id = 0 ) {

        global $wpdb;

        // Row ID must be positive integer
        $row_id = absint( $row_id );

        if( empty( $row_id ) ) {
            return false;
        }

        if ( false === $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id ) ) ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the given table exists
     *
     * @since  1.0
     * @param  string $table The table name
     * @return bool          If the table name exists
     */
    public function table_exists( $table ) {
        global $wpdb;
        $table = sanitize_text_field( $table );

        return $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $table ) ) === $table;
    }

    /**
     * Check if the table was ever installed
     *
     * @since  1.0
     * @return bool Returns if the customers table was installed and upgrade routine run
     */
    public function installed() {
        return $this->table_exists( $this->table_name );
    }
}
endif;