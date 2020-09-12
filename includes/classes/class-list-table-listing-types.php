<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if ( class_exists( 'WP_List_Table' ) && ! class_exists( 'Listing_Types_List_Table' ) ) :

    class Listing_Types_List_Table extends WP_List_Table {

        // get_archive
        public function get_archive( $offset = 0, $limit = 1 ) {
            $listing_types = get_terms([
                'taxonomy'   => 'atbdp_listing_types',
                'hide_empty' => false,
                'orderby'    => 'date',
                'order'      => 'DSCE',
                'offset'     => $offset,
                'number'     => $limit,
            ]);
            
            $archive = [];
            foreach ( $listing_types as $listing_type ) {
                $archive[] = [
                    'ID' => $listing_type->term_id,
                    'name' => $listing_type->name,
                    'slug' => $listing_type->slug,
                ];
            }

            return $archive;
        }

        // get_columns
        function get_columns(){
            $columns = array(
              'cb' => '<input type="checkbox" />',
              'name' => 'Listing Type',
              'slug' => 'Slug',
            );

            return $columns;
        }
          
        // prepare_items
        function prepare_items() {
            $columns               = $this->get_columns();
            $hidden                = array();
            $sortable              = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            $per_page = 10;
            $current_page = $this->get_pagenum();
            $total_items = wp_count_terms( 'atbdp_listing_types' );

            $offset =  ( $current_page - 1 ) * $per_page;
            $found_data = $this->get_archive( $offset, $per_page );

            $this->set_pagination_args( array(
                'total_items' => $total_items,
                'per_page'    => $per_page
            ));

            $this->items = $found_data;
        }

        // column_default
        function column_default( $item, $column_name ) {
            switch( $column_name ) { 
              case 'slug':
                return $item[ $column_name ];
              case 'name':
                return '<a href="#">'. $item[ $column_name ] .'</a>';
              default:
                return $item[ $column_name ];
            }
        }

        // column_name
        function column_name( $item ) {
            // create a nonce
            $edit_nonce = wp_create_nonce( 'sp_edit_listing_type' );
            $delete_nonce = wp_create_nonce( 'sp_delete_listing_type' );
            $title = '<strong><a href="?post_type=at_biz_dir&page='. esc_attr( $_REQUEST['page'] ) .'&listing_type_id='. absint( $item['ID'] ) .'&action=edit">' . $item['name'] . '</a></strong>';
          
            $actions = [
              'edit' => sprintf( '<a href="?post_type=at_biz_dir&page=%s&action=%s&listing_type_id=%s&_wpnonce=%s">Edit</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['ID'] ), $edit_nonce ),
              'delete' => sprintf( '<a href="?post_type=at_biz_dir&page=%s&action=%s&listing_type_id=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
            ];
          
            return $title . $this->row_actions( $actions );
          }

        // get_sortable_columns
        function get_sortable_columns() {
            $sortable_columns = array(
              'name' => array('name',false),
            );
            
            return $sortable_columns;
        }

        // get_bulk_actions
        function get_bulk_actions() {
            $actions = array(
              'delete' => 'Delete'
            );

            return $actions;
        }

        // column_cb
        function column_cb( $item ) {
            return sprintf(
                '<input type="checkbox" name="listing_types[]" value="%s" />', $item['ID']
            );    
        }

        // no_items
        function no_items() {
            _e( 'No listing type found.' );
        }
    }

endif;