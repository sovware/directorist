<?php

if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

if (class_exists('WP_List_Table') && !class_exists('Listing_Types_List_Table')) :

  class Listing_Types_List_Table extends WP_List_Table {
    public $listing_type_manager = null;

    
    public function __construct( ATBDP_Listing_Type_Manager $listing_type_manager )
    {
      parent::__construct([
        'singular' => 'bulk-delete',
        'plural'   => 'bulk-deletes',
        'ajax'     => false
      ]);

      $this->listing_type_manager = $listing_type_manager;
    }

    // get_archive
    public function get_archive($offset = 0, $limit = 1)
    {
      $listing_types = get_terms([
        'taxonomy'   => 'atbdp_listing_types',
        'hide_empty' => false,
        'orderby'    => 'date',
        'order'      => 'DSCE',
        'offset'     => $offset,
        'number'     => $limit,
      ]);

      $archive = [];
      foreach ($listing_types as $listing_type) {
        $default = get_term_meta( $listing_type->term_id, '_default', true );
        $default_type = $default ? '<span class="page-title-action">Default</span>' : '';
        $archive[] = [
          'ID' => $listing_type->term_id,
          'name' => $listing_type->name . $default_type,
          'slug' => $listing_type->slug,
        ];
      }

      return $archive;
    }

    // get_columns
    function get_columns()
    {
      $columns = array(
        // 'cb' => '<input type="checkbox" />',
        'name' => 'Listing Type',
        'slug' => 'Slug',
      );

      return $columns;
    }

    // prepare_items
    function prepare_items()
    {
      $columns               = $this->get_columns();
      $hidden                = [];
      $sortable              = $this->get_sortable_columns();
      $this->_column_headers = array($columns, $hidden, $sortable);

      $this->process_bulk_action();

      $per_page     = 10;
      $current_page = $this->get_pagenum();
      $total_items  = wp_count_terms('atbdp_listing_types');

      $offset     = ($current_page - 1) * $per_page;
      $found_data = $this->get_archive($offset, $per_page);

      $this->set_pagination_args(array(
        'total_items' => $total_items,
        'per_page'    => $per_page
      ));

      $this->items = $found_data;
    }

    public function process_bulk_action()
    {

      /*  //Detect when a bulk action is being triggered...
            if ( 'delete' === $this->current_action() ) {
          
              // In our file that handles the request, verify the nonce.
              $nonce = esc_attr( $_REQUEST['_wpnonce'] );
          
              if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
                die( 'Go get a life script kiddies' );
              }
              else {
                self::delete_customer( absint( $_GET['customer'] ) );
          
                wp_redirect( esc_url( add_query_arg() ) );
                exit;
              }
          
            } */

        // If the delete bulk action is triggered
        if ( (isset($_REQUEST['action']) && $_REQUEST['action'] == 'bulk-delete')
        || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == 'bulk-delete')
        ) {
              
              var_dump( $this->current_action() );
        $delete_ids = esc_sql($_REQUEST['listing_types']);

        // loop over the array of record IDs and delete them
        foreach ($delete_ids as $id) {
          $this->listing_type_manager->delete_listing_type( $id );
        }

        wp_redirect(esc_url(add_query_arg()));
        exit;
      }
    }

    // column_default
    function column_default($item, $column_name)
    {
      switch ($column_name) {
        case 'slug':
          return $item[$column_name];
        case 'name':
          return '<a href="#">' . $item[$column_name] . '</a>';
        default:
          return $item[$column_name];
      }
    }

    // column_name
    function column_name($item)
    {
      $edit_link   = admin_url('edit.php' . '?post_type=at_biz_dir&page=atbdp-listing-types&listing_type_id=' . absint($item['ID']) . '&action=edit');
      $delete_link = admin_url('admin-post.php' . '?listing_type_id=' . absint($item['ID']) . '&action=delete_listing_type');
      $delete_link = wp_nonce_url($delete_link, 'delete_listing_type');
      $title       = sprintf('<strong><a href="%s">%s</a></strong>', $edit_link, $item['name']);
      $default = get_term_meta( absint($item['ID']), '_default', true );

      $actions = [
        'edit' => sprintf('<a href="%s">Edit</a>', $edit_link),
        'delete' => sprintf('<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\')">Delete</a>', $delete_link),
        //'default' => '<a href="" data-type-id="'. absint($item['ID']) .'" class="submitdefault">Make Default</a>',
      ];
      if( !$default ){
        $actions['default'] = '<a href="" data-type-id="'. absint($item['ID']) .'" class="submitdefault">Make Default</a>';
      }

      return $title . $this->row_actions($actions);
    }

    // get_sortable_columns
    function get_sortable_columns()
    {
      $sortable_columns = array(
        'name' => array('name', false),
      );

      return $sortable_columns;
    }

    // // get_bulk_actions
    // function get_bulk_actions()
    // {
    //   $actions = array(
    //     'bulk-delete' => 'Delete'
    //   );

    //   return $actions;
    // }

    // column_cb
    function column_cb($item)
    {
      return sprintf(
        '<input type="checkbox" name="listing_types[]" value="%s" />',
        $item['ID']
      );
    }

    // no_items
    function no_items()
    {
      _e('No listing type found.');
    }
  }

endif;
