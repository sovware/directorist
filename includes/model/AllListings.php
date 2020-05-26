<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Directorist_All_Listings' ) ):
    class Directorist_All_Listings {
        public $address_label;
        public $all_listing_title;
        public $all_listings;
        public $apply_filters_text;
        public $args;
        public $arguments;
        public $atts;
        public $average_review_for_popular;
        public $c_symbol;
        public $categories;
        public $categories_fields;
        public $category_placeholder;
        public $column_width;
        public $grid_col_class;
        public $columns;
        public $count_meta_queries;
        public $currency;
        public $current_order;
        public $data_for_template;
        public $default_radius_distance;
        public $display_header;
        public $display_image;
        public $display_listings_header;
        public $display_viewas_dropdown;
        public $email_label;
        public $fax_label;
        public $feature_only;
        public $filters;
        public $filters_button;
        public $filters_display;
        public $geo_loc;
        public $grid_container_fluid;
        public $has_featured;
        public $header_container_fluid;
        public $header_title;
        public $header_title_for_search;
        public $include;
        public $is_disable_price;
        public $listing_count;
        public $listing_filters_button;
        public $listing_filters_icon;
        public $listing_grid_columns;
        public $listing_grid_container_fluid;
        public $listing_header_container_fluid;
        public $listing_id;
        public $listing_location_address;
        public $listing_order;
        public $listing_orderby;
        public $listing_popular_by;
        public $listing_type;
        public $listing_view;
        public $listingbyid_arg;
        public $listings;
        public $listings_header_title;
        public $listings_map_height;
        public $location_placeholder;
        public $locations;
        public $locations_fields;
        public $map_height;
        public $meta_queries;
        public $miles;
        public $paged;
        public $paginate;
        public $pagination;
        public $parameters;
        public $params;
        public $popular_only;
        public $query_args;
        public $radius_search_unit;
        public $rated;
        public $redirect_page_url;
        public $reset_filters_text;
        public $search_more_filters_fields;
        public $select_listing_map;
        public $show_pagination;
        public $sort_by_items;
        public $sort_by_text;
        public $tag_label;
        public $tags;
        public $tax_queries;
        public $text_placeholder;
        public $view;
        public $view_as;
        public $view_as_items;
        public $view_as_text;
        public $view_to_popular;
        public $views;
        public $website_label;
        public $zip_label;
        public $use_nofollow;
        

        public function __construct( $args = array() ) {
            if ( ! empty( $args['atts'] ) ) {
                $this->atts = $args['atts'];
            }

            $this->prepare_data();
        }

        // render_shortcode
        public function render_shortcode( ) {
            wp_enqueue_script('adminmainassets');
            wp_enqueue_script('atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js');
            wp_localize_script('atbdp-search-listing', 'atbdp_search', array(
                'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'added_favourite' => __('Added to favorite', 'directorist'),
                'please_login' => __('Please login first', 'directorist')
            ));
            wp_enqueue_script('atbdp-range-slider');

            wp_localize_script( 'atbdp-range-slider', 'atbdp_range_slider', array(
                'Miles'     =>  $this->miles,
                'default_val'   =>  $this->default_radius_distance
            ));

            
            ob_start();

            // Add Inline Style
            $style = '.atbd_content_active #directorist.atbd_wrapper .atbdp_column {';
            $style .= "width: " . $this->column_width . "; } \n";

            $listing_map_type = $this->select_listing_map;
            
            if ( $listing_map_type === 'openstreet' ) {
                $style .= '.myDivIcon {';
                $style .= 'text-align: center !important;';
                $style .= 'line-height: 20px !important;';
                $style .= "position: relative; }\n";

                $style .= '.myDivIcon div.atbd_map_shape {';
                $style .= 'position: absolute;';
                $style .= 'top: -38px;';
                $style .= "left: -15px; }\n";
            }

            wp_add_inline_style( 'atbdp-inline-style', $style );
            wp_enqueue_style('atbdp-inline-style');
            
            if (!empty($this->redirect_page_url)) {
                $redirect = '<script>window.location="' . esc_url($this->redirect_page_url) . '"</script>';
                return $redirect;
            }

            if ( is_rtl() ){
                wp_enqueue_style('atbdp-search-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/search-style-rtl.css');
            } else{
                wp_enqueue_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
            }

            if ( 'listings_with_map' == $this->view ) {
                $template_file = "listing-with-map/map-view";
                $extension_file = BDM_TEMPLATES_DIR . '/map-view';

                atbdp_get_shortcode_ext_template( $template_file, $extension_file, null, $this, true );
                return ob_get_clean();
            }
            
            $template_file = "listings-archive/listings-{$this->view}";
            atbdp_get_shortcode_template( $template_file, null, $this, true );

            return ob_get_clean();
        }

        // category_field_data
        public function category_field_data() {
            $slug             = ! empty( $this->term_slug ) ? $this->term_slug : '';
            $taxonomy_by_slug = get_term_by( 'slug', $slug, ATBDP_CATEGORY );
            $taxonomy_id      = '';

            if ( ! empty( $taxonomy_by_slug ) ) {
                $taxonomy_id = $taxonomy_by_slug->term_taxonomy_id;
            }
            $selected = isset( $_GET['in_cat'] ) ? $_GET['in_cat'] : -1;

            $data = array(
                'taxonomy_id' => $taxonomy_id,
                'selected'    => $selected,
            );

            return $data;
        }

        // filter_container_class
        public function filter_container_class() {
            echo ( 'overlapping' === $this->filters_display ) ? 'ads_float' : 'ads_slide';
        }

        // geolocation_field_data
        public function geolocation_field_data() {
            $select_listing_map = get_directorist_option( 'select_listing_map', 'google' );
            $geo_loc            = ( 'google' == $select_listing_map ) ? '<span class="atbd_get_loc la la-crosshairs"></span>' : '<span class="atbd_get_loc la la-crosshairs"></span>';

            $value       = ! empty( $_GET['address'] ) ? $_GET['address'] : '';
            $placeholder = ! empty( $this->location_placeholder ) ? sanitize_text_field( $this->location_placeholder ) : __( 'location', 'directorist' );
            $cityLat     = ( isset( $_GET['cityLat'] ) ) ? esc_attr( $_GET['cityLat'] ) : '';
            $cityLng     = ( isset( $_GET['cityLng'] ) ) ? esc_attr( $_GET['cityLng'] ) : '';

            wp_localize_script( 'atbdp-geolocation', 'adbdp_geolocation', array( 'select_listing_map' => $select_listing_map ) );
            wp_enqueue_script( 'atbdp-geolocation' );

            $data = array(
                'select_listing_map' => $select_listing_map,
                'geo_loc'            => $geo_loc,
                'value'              => $value,
                'placeholder'        => $placeholder,
                'cityLat'            => $cityLat,
                'cityLng'            => $cityLng,
            );

            return $data;
        }

        // get_sort_by_link_list
        public function get_sort_by_link_list() {
            $link_list = array();

            $options       = atbdp_get_listings_orderby_options( $this->sort_by_items );
            $current_order = ! empty( $this->current_order ) ? $this->current_order : '';

            foreach ( $options as $value => $label ) {
                $active_class = ( $value == $current_order ) ? ' active' : '';
                $link         = add_query_arg( 'sort', $value );

                $link_item['active_class'] = $active_class;
                $link_item['link']         = $link;
                $link_item['label']        = $label;

                array_push( $link_list, $link_item );
            }

            return $link_list;
        }

        // get_view_as_link_list
        public function get_view_as_link_list() {
            $link_list = array();
            $view      = ! empty( $this->view ) ? $this->view : '';

            foreach ( $this->views as $value => $label ) {
                $active_class = ( $view === $value ) ? ' active' : '';
                $link         = add_query_arg( 'view', $value );
                $link_item    = array();

                $link_item['active_class'] = $active_class;
                $link_item['link']         = $link;
                $link_item['label']        = $label;

                array_push( $link_list, $link_item );
            }

            return $link_list;
        }

        // has_any_price_field
        public function has_any_price_field() {
            if (
            in_array( 'search_price', $this->search_more_filters_fields ) ||
            in_array( 'search_price_range', $this->search_more_filters_fields )
        ) {
                return true;
            }

            return false;
        }

        // has_category_field
        public function has_category_field() {
            return in_array( 'search_category', $this->search_more_filters_fields );
        }

        // has_filter_button
        public function has_filter_button() {
            return ! empty( $this->listing_filters_button );
        }

        // has_filter_icon
        public function has_filter_icon() {
            return ! empty( $this->listing_filters_icon );
        }

        // has_header_title
        public function has_header_title() {
            return ! empty( $this->header_title );
        }

        // has_listings_header
        public function has_listings_header() {
            $has_filter_button = ( ! empty( $this->listing_filters_button ) && ! empty( $this->search_more_filters_fields ) );

            return ( $has_filter_button || ! empty( $this->header_title ) ) ? true : false;
        }

        // has_listings_header_toolbar
        public function has_listings_header_toolbar() {
            return ( $this->display_viewas_dropdown || $this->display_sortby_dropdown ) ? true : false;
        }

        // has_location_field
        public function has_location_field() {
            return in_array( 'search_location', $this->search_more_filters_fields );
        }

        // has_radius_search_field
        public function has_open_now_field() {
            $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
            $plugin_path    = 'directorist-business-hours/bd-business-hour.php';

            return in_array( 'search_open_now', $this->search_more_filters_fields ) && in_array( $plugin_path, $active_plugins );
        }

        // has_price_field
        public function has_price_field() {
            return in_array( 'search_price', $this->search_more_filters_fields );
        }

        // has_price_range_field
        public function has_price_range_field() {
            return in_array( 'search_price_range', $this->search_more_filters_fields );
        }

        // has_radius_search_field
        public function has_radius_search_field() {
            return ( 'map_api' == $this->listing_location_address && in_array( 'radius_search', $this->search_more_filters_fields ) );
        }

        // has_rating_field
        public function has_rating_field() {
            return in_array( 'search_rating', $this->search_more_filters_fields );
        }

        // has_search_field
        public function has_search_field() {
            return in_array( 'search_text', $this->search_more_filters_fields );
        }

        // has_tag_field
        public function has_tag_field() {
            return in_array( 'search_tag', $this->search_more_filters_fields );
        }

        // header_container_class
        public function header_container_class() {
            echo ( ! empty( $this->header_container_fluid ) ) ? $this->header_container_fluid : '';
        }

        // listings_loop_data
        public function listings_loop_data() {
            $data = array(
                'listings_id'                => get_the_ID(),
                'cats'                       => get_the_terms( get_the_ID(), ATBDP_CATEGORY ),
                'locs'                       => get_the_terms( get_the_ID(), ATBDP_LOCATION ),
                'featured'                   => get_post_meta( get_the_ID(), '_featured', true ),
                'price'                      => get_post_meta( get_the_ID(), '_price', true ),
                'price_range'                => get_post_meta( get_the_ID(), '_price_range', true ),
                'atbd_listing_pricing'       => get_post_meta( get_the_ID(), '_atbd_listing_pricing', true ),
                'listing_img'                => get_post_meta( get_the_ID(), '_listing_img', true ),
                'listing_prv_img'            => get_post_meta( get_the_ID(), '_listing_prv_img', true ),
                'excerpt'                    => get_post_meta( get_the_ID(), '_excerpt', true ),
                'tagline'                    => get_post_meta( get_the_ID(), '_tagline', true ),
                'address'                    => get_post_meta( get_the_ID(), '_address', true ),
                'email'                      => get_post_meta( get_the_ID(), '_email', true ),
                'web'                        => get_post_meta( get_the_ID(), '_website', true ),
                'phone_number'               => get_post_meta( get_the_Id(), '_phone', true ),
                'category'                   => get_post_meta( get_the_Id(), '_admin_category_select', true ),
                'post_view'                  => get_post_meta( get_the_Id(), '_atbdp_post_views_count', true ),
                'hide_contact_info'          => get_post_meta( get_the_ID(), '_hide_contact_info', true ),
                'disable_contact_info'       => get_directorist_option( 'disable_contact_info', 0 ),
                'display_title'              => get_directorist_option( 'display_title', 1 ),
                'display_review'             => get_directorist_option( 'enable_review', 1 ),
                'display_price'              => get_directorist_option( 'display_price', 1 ),
                'display_email'              => get_directorist_option( 'display_email', 0 ),
                'display_web_link'           => get_directorist_option( 'display_web_link', 0 ),
                'display_category'           => get_directorist_option( 'display_category', 1 ),
                'display_view_count'         => get_directorist_option( 'display_view_count', 1 ),
                'display_mark_as_fav'        => get_directorist_option( 'display_mark_as_fav', 1 ),
                'display_publish_date'       => get_directorist_option( 'display_publish_date', 1 ),
                'display_contact_info'       => get_directorist_option( 'display_contact_info', 1 ),
                'display_feature_badge_cart' => get_directorist_option( 'display_feature_badge_cart', 1 ),
                'display_popular_badge_cart' => get_directorist_option( 'display_popular_badge_cart', 1 ),
                'popular_badge_text'         => get_directorist_option( 'popular_badge_text', 'Popular' ),
                'feature_badge_text'         => get_directorist_option( 'feature_badge_text', 'Featured' ),
                'enable_tagline'             => get_directorist_option( 'enable_tagline' ),
                'enable_excerpt'             => get_directorist_option( 'enable_excerpt' ),
                'address_location'           => get_directorist_option( 'address_location', 'location' ),
                'bdbh'                       => get_post_meta( get_the_ID(), '_bdbh', true ),
                'enable247hour'              => get_post_meta( get_the_ID(), '_enable247hour', true ),
                'disable_bz_hour_listing'    => get_post_meta( get_the_ID(), '_disable_bz_hour_listing', true ),
                'author_id'                  => get_the_author_meta( 'ID' ),
                'display_author_image'       => get_directorist_option( 'display_author_image', 1 ),

                'display_tagline_field'      => get_directorist_option( 'display_tagline_field', 0 ),
                'display_pricing_field'      => get_directorist_option( 'display_pricing_field', 1 ),
                'display_excerpt_field'      => get_directorist_option( 'display_excerpt_field', 0 ),
                'display_address_field'      => get_directorist_option( 'display_address_field', 1 ),
                'display_phone_field'        => get_directorist_option( 'display_phone_field', 1 ),
                'prv_image'                  => '',
                'default_image'              => get_directorist_option( 'default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg' ),
                'thumbnail_link_attr'        => trim( ' ' . apply_filters( 'grid_view_thumbnail_link_add_attr', '' ) ),
                'title_link_attr'            => trim( ' ' . apply_filters( 'grid_view_title_link_add_attr', '' ) ),
            );

            $data['display_image']             = ( ! empty( $this->display_image ) ) ? $this->display_image : '';
            $data['listing_preview_img']       = ( empty( get_directorist_option( 'display_preview_image', 1 ) ) || 'no' == $data['display_image'] ) ? 'no' : 'yes';
            $data['listing_preview_img_class'] = ( 'no' == $data['listing_preview_img'] || ( empty( $data['prv_image'] ) && empty( $data['default_image'] ) && empty( $data['gallery_img'] ) ) ) ? ' listing_preview_img_none' : '';

            if ( ! empty( $data['listing_preview_img'] ) ) {
                $data['prv_image_full'] = atbdp_get_image_source( $data['listing_preview_img'], 'full' );
            }

            if ( ! empty( $data['listing_img'][0] ) ) {
                $data['gallery_img_full'] = atbdp_get_image_source( $data['listing_img'][0], 'full' );
            }

            $data['business_hours'] = ! empty( $this->bdbh ) ? atbdp_sanitize_array( $this->bdbh ) : array();

            $data['u_pro_pic']  = get_user_meta( $data['author_id'], 'pro_pic', true );
            $data['u_pro_pic']  = ! empty( $data['u_pro_pic'] ) ? wp_get_attachment_image_src( $data['u_pro_pic'], 'thumbnail' ) : '';
            $data['avatar_img'] = get_avatar( $data['author_id'], apply_filters( 'atbdp_avatar_size', 32 ) );

            $thumbnail_link_attr = " " . apply_filters('grid_view_thumbnail_link_add_attr', '');
            $thumbnail_link_attr = trim($thumbnail_link_attr);

            $title_link_attr = " " . apply_filters('grid_view_title_link_add_attr', '');
            $title_link_attr = trim($title_link_attr);

            $data['listings_link_attr'] = $title_link_attr;
            $data['listings_link'] = esc_url(get_post_permalink(get_the_ID()));

            $data['show_preview_image'] = ( 'yes' === $data['listing_preview_img'] ) ? true : false;

            $is_disabled = get_directorist_option('disable_single_listing');
            $data['single_listing_is_disabled'] = ( empty( $is_disabled ) ) ? false : true;
            $data['listings_title'] = esc_html( stripslashes(get_the_title()) );

            $data['author'] = get_userdata( $data['author_id'] );

            $author = $data['author'];
            $data['author_full_name'] = $author->first_name . ' ' . $author->last_name;
            $data['author_link'] = ATBDP_Permalink::get_user_profile_page_link( $data['author_id'] );
            $data['author_link_class'] = ! empty( $author->first_name && $author->last_name ) ? 'atbd_tooltip' : '';

            $plan_hours = true;
            if (is_fee_manager_active()) {
                $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
            }

            $data['plan_hours'] = $plan_hours;

            $data['excerpt_limit'] = get_directorist_option('excerpt_limit', 20);
            $data['display_readmore'] = get_directorist_option('display_readmore', 0);
            $data['readmore_text'] = get_directorist_option('readmore_text', __('Read More', 'directorist'));

            return $data;
        }

        // location_field_data
        public function location_field_data() {
            $slug             = ! empty( $this->term_slug ) ? $this->term_slug : '';
            $location_by_slug = get_term_by( 'slug', $slug, ATBDP_LOCATION );
            if ( ! empty( $location_by_slug ) ) {
                $location_id = $location_by_slug->term_taxonomy_id;
            }
            $loc_selected = isset( $_GET['in_loc'] ) ? $_GET['in_loc'] : -1;

            $data = array(
                'location_id'  => $location_id,
                'loc_selected' => $loc_selected,
            );

            return $data;
        }

        // location_field_type
        public function location_field_type( $type ) {
            if ( ! $this->has_location_field() ) {
                return false;
            }

            if ( $type !== $this->listing_location_address ) {
                return false;
            }

            return true;
        }

        // open_now_field_data
        public function open_now_field_data() {
            $checked = ! empty( $_GET['open_now'] ) && 'open_now' == $_GET['open_now'] ? " checked='checked'" : '';

            return $checked;
        }

        public function prepare_data() {
            $this->radius_search_unit = get_directorist_option( 'radius_search_unit', 'miles' );
            if ( ! empty( $this->radius_search_unit ) && 'kilometers' == $this->radius_search_unit ) {
                $this->miles = __( ' Kilometers', 'directorist' );
            } else {
                $this->miles = __( ' Miles', 'directorist' );
            }
            $this->default_radius_distance = get_directorist_option( 'listing_default_radius_distance', 0 );
            $this->listing_orderby         = get_directorist_option( 'order_listing_by' );
            $this->listing_view            = get_directorist_option( 'default_listing_view' );
            $this->filters_display         = get_directorist_option( 'listings_display_filter', 'sliding' );
            $this->listing_filters_button  = get_directorist_option( 'listing_filters_button' );
            $this->listing_order           = get_directorist_option( 'sort_listing_by' );
            $this->listing_grid_columns    = get_directorist_option( 'all_listing_columns', 3 );
            $this->display_listings_header = get_directorist_option( 'display_listings_header', 1 );
            $this->listings_header_title   = get_directorist_option( 'all_listing_header_title', __( 'Items Found', 'directorist' ) );
            $this->pagination              = get_directorist_option( 'paginate_all_listings' );
            $this->listings_map_height     = get_directorist_option( 'listings_map_height', 350 );
            $this->parameters              = array(
                'view'                     => ! empty( $this->listing_view ) ? $this->listing_view : 'grid',
                '_featured'                => 1,
                'filterby'                 => '',
                'orderby'                  => ! empty( $this->listing_orderby ) ? $this->listing_orderby : 'date',
                'order'                    => ! empty( $this->listing_order ) ? $this->listing_order : 'asc',
                'listings_per_page'        => (int)get_directorist_option( 'all_listing_page_items', 6 ),
                'show_pagination'          => ! empty( $this->pagination ) ? 'yes' : '',
                'header'                   => ! empty( $this->display_listings_header ) ? 'yes' : '',
                'header_title'             => ! empty( $this->listings_header_title ) ? $this->listings_header_title : '',
                'category'                 => '',
                'location'                 => '',
                'tag'                      => '',
                'ids'                      => '',
                'columns'                  => ! empty( $this->listing_grid_columns ) ? $this->listing_grid_columns : 3,
                'featured_only'            => '',
                'popular_only'             => '',
                'advanced_filter'          => '',
                'display_preview_image'    => 'yes',
                'action_before_after_loop' => 'yes',
                'logged_in_user_only'      => '',
                'redirect_page_url'        => '',
                'map_height'               => ! empty( $this->listings_map_height ) ? $this->listings_map_height : 350,
            );
            $this->params                   = apply_filters( 'atbdp_all_listings_params', $this->parameters );
            $this->atts                     = shortcode_atts( $this->params, $this->atts );
            $this->categories               = ! empty( $this->atts['category'] ) ? explode( ',', $this->atts['category'] ) : '';
            $this->tags                     = ! empty( $this->atts['tag'] ) ? explode( ',', $this->atts['tag'] ) : '';
            $this->locations                = ! empty( $this->atts['location'] ) ? explode( ',', $this->atts['location'] ) : '';
            $this->listing_id               = ! empty( $this->atts['ids'] ) ? explode( ',', $this->atts['ids'] ) : '';
            $this->columns                  = ! empty( $this->atts['columns'] ) ? $this->atts['columns'] : 3;
            $this->display_header           = ! empty( $this->atts['header'] ) ? $this->atts['header'] : '';
            $this->header_title             = ! empty( $this->atts['header_title'] ) ? $this->atts['header_title'] : '';
            $this->feature_only             = ! empty( $this->atts['featured_only'] ) ? $this->atts['featured_only'] : '';
            $this->popular_only             = ! empty( $this->atts['popular_only'] ) ? $this->atts['popular_only'] : '';
            $this->action_before_after_loop = ! empty( $this->atts['action_before_after_loop'] ) ? $this->atts['action_before_after_loop'] : '';
            $this->show_pagination          = ! empty( $this->atts['show_pagination'] ) ? $this->atts['show_pagination'] : '';
            $this->display_image            = ! empty( $this->atts['display_preview_image'] ) ? $this->atts['display_preview_image'] : '';
            $this->redirect_page_url        = ! empty( $this->atts['redirect_page_url'] ) ? $this->atts['redirect_page_url'] : '';
            $this->map_height               = ! empty( $this->atts['map_height'] ) ? $this->atts['map_height'] : '';
            $this->listing_type             = ! empty( $this->atts['listing_type'] ) ? $this->atts['listing_type'] : '';
            $this->view                     = ! empty( $this->atts['view'] ) ? $this->atts['view'] : 'grid';
            //for pagination
            $this->paged = atbdp_get_paged_num();

            $this->has_featured = get_directorist_option( 'enable_featured_listing' );
            if ( $this->has_featured || is_fee_manager_active() ) {
                $this->has_featured = $this->atts['_featured'];
            }
            if ( 'rand' == $this->atts['orderby'] ) {
                $this->current_order = atbdp_get_listings_current_order( $this->atts['orderby'] );
            } else {
                $this->current_order = atbdp_get_listings_current_order( $this->atts['orderby'] . '-' . $this->atts['order'] );
            }

            $this->view = atbdp_get_listings_current_view_name( $this->atts['view'] );

            $this->args = array(
                'post_type'      => ATBDP_POST_TYPE,
                'post_status'    => 'publish',
                'posts_per_page' => (int)$this->atts['listings_per_page'],
            );
            if ( 'yes' == $this->show_pagination ) {
                $this->args['paged'] = $this->paged;
            } else {
                $this->args['no_found_rows'] = true;
            }

            $this->listingbyid_arg = array();

            if ( ! empty( $this->listing_id ) ) {
                $this->listingbyid_arg  = $this->listing_id;
                $this->args['post__in'] = $this->listingbyid_arg;
            }

            $this->tax_queries = array(); // initiate the tax query var to append to it different tax query

            if ( ! empty( $this->categories ) && ! empty( $this->locations ) && ! empty( $this->tags ) ) {

                $this->tax_queries['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy'         => ATBDP_CATEGORY,
                        'field'            => 'slug',
                        'terms'            => ! empty( $categories ) ? $this->categories : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                    array(
                        'taxonomy'         => ATBDP_LOCATION,
                        'field'            => 'slug',
                        'terms'            => ! empty( $locations ) ? $this->locations : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                    array(
                        'taxonomy'         => ATBDP_TAGS,
                        'field'            => 'slug',
                        'terms'            => ! empty( $tags ) ? $this->tags : array(),
                        'include_children' => true, /*@todo; Add option to include children or exclude it*/
                    ),
                );
            } elseif ( ! empty( $this->categories ) && ! empty( $this->tags ) ) {
            $this->tax_queries['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy'         => ATBDP_CATEGORY,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),
                array(
                    'taxonomy'         => ATBDP_TAGS,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->tags ) ? $this->tags : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),
            );
        } elseif ( ! empty( $this->categories ) && ! empty( $this->locations ) ) {
            $this->tax_queries['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy'         => ATBDP_CATEGORY,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),
                array(
                    'taxonomy'         => ATBDP_LOCATION,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),

            );
        } elseif ( ! empty( $this->tags ) && ! empty( $this->locations ) ) {
            $this->tax_queries['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy'         => ATBDP_TAGS,
                    'field'            => 'slug',
                    'terms'            => ! empty( $tags ) ? $this->tags : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),
                array(
                    'taxonomy'         => ATBDP_LOCATION,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),

            );
        } elseif ( ! empty( $this->categories ) ) {
            $this->tax_queries['tax_query'] = array(
                array(
                    'taxonomy'         => ATBDP_CATEGORY,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),
            );
        } elseif ( ! empty( $this->tags ) ) {
            $tax_queries['tax_query'] = array(
                array(
                    'taxonomy'         => ATBDP_TAGS,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->tags ) ? $this->tags : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),
            );
        } elseif ( ! empty( $this->locations ) ) {
            $this->tax_queries['tax_query'] = array(
                array(
                    'taxonomy'         => ATBDP_LOCATION,
                    'field'            => 'slug',
                    'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                ),
            );
        }
        $this->args['tax_query'] = $this->tax_queries;

        $this->meta_queries = array();

        $this->meta_queries['expired'] = array(
            'relation' => 'OR',
            array(
                'key'     => '_expiry_date',
                'value'   => current_time( 'mysql' ),
                'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                 'type'    => 'DATETIME',
            ),
            array(
                'key'   => '_never_expire',
                'value' => 1,
            ),
        );
        $this->args['expired'] = $this->meta_queries;

        if ( $this->has_featured ) {

            if ( '_featured' == $this->atts['filterby'] ) {
                $this->meta_queries['_featured'] = array(
                    'key'     => '_featured',
                    'value'   => 1,
                    'compare' => '=',
                );
            } else {
                $this->meta_queries['_featured'] = array(
                    'key'     => '_featured',
                    'type'    => 'NUMERIC',
                    'compare' => 'EXISTS',
                );
            }
        }
        if ( 'yes' == $this->feature_only ) {
            $this->meta_queries['_featured'] = array(
                'key'     => '_featured',
                'value'   => 1,
                'compare' => '=',
            );
        }

        $this->listings                   = get_atbdp_listings_ids();
        $this->rated                      = array();
        $this->listing_popular_by         = get_directorist_option( 'listing_popular_by' );
        $this->average_review_for_popular = get_directorist_option( 'average_review_for_popular', 4 );
        $this->view_to_popular            = get_directorist_option( 'views_for_popular' );

        if (  ( 'yes' == $this->popular_only ) || ( 'views-desc' === $this->current_order ) ) {
            if ( $this->has_featured ) {
                if ( 'average_rating' === $this->listing_popular_by ) {
                    if ( $this->listings->have_posts() ) {
                        while ( $this->listings->have_posts() ) {
                            $this->listings->the_post();
                            $this->listing_id = get_the_ID();
                            $this->average    = ATBDP()->review->get_average( $this->listing_id );
                            if ( $this->average_review_for_popular <= $this->average ) {
                                $this->rated[] = get_the_ID();
                            }
                        }
                        $rating_id = array(
                            'post__in' => ! empty( $this->rated ) ? $this->rated : array(),
                        );
                        $this->args = array_merge( $this->args, $rating_id );
                    }
                } elseif ( 'view_count' === $this->listing_popular_by ) {
                    $this->meta_queries['views'] = array(
                        'key'     => '_atbdp_post_views_count',
                        'value'   => $this->view_to_popular,
                        'type'    => 'NUMERIC',
                        'compare' => '>=',
                    );

                    $this->args['orderby'] = array(
                        '_featured' => 'DESC',
                        'views'     => 'DESC',
                    );
                } else {
                    $this->meta_queries['views'] = array(
                        'key'     => '_atbdp_post_views_count',
                        'value'   => $this->view_to_popular,
                        'type'    => 'NUMERIC',
                        'compare' => '>=',
                    );
                    $args['orderby'] = array(
                        '_featured' => 'DESC',
                        'views'     => 'DESC',
                    );
                    if ( $this->listings->have_posts() ) {
                        while ( $this->listings->have_posts() ) {
                            $this->listings->the_post();
                            $this->listing_id = get_the_ID();
                            $average          = ATBDP()->review->get_average( $this->listing_id );
                            if ( $this->average_review_for_popular <= $average ) {
                                $this->rated[] = get_the_ID();
                            }
                        }
                        $rating_id = array(
                            'post__in' => ! empty( $this->rated ) ? $this->rated : array(),
                        );
                        $this->args = array_merge( $this->args, $rating_id );
                    }
                }
            } else {
                if ( 'average_rating' === $this->listing_popular_by ) {
                    if ( $this->listings->have_posts() ) {
                        while ( $this->listings->have_posts() ) {
                            $this->listings->the_post();
                            $listing_id = get_the_ID();
                            $average    = ATBDP()->review->get_average( $listing_id );
                            if ( $this->average_review_for_popular <= $average ) {
                                $this->rated[] = get_the_ID();
                            }
                        }
                        $rating_id = array(
                            'post__in' => ! empty( $this->rated ) ? $this->rated : array(),
                        );
                        $this->args = array_merge( $args, $rating_id );
                    }
                } elseif ( 'view_count' === $this->listing_popular_by ) {
                    $this->meta_queries['views'] = array(
                        'key'     => '_atbdp_post_views_count',
                        'value'   => $this->view_to_popular,
                        'type'    => 'NUMERIC',
                        'compare' => '>=',
                    );
                    $this->args['orderby'] = array(
                        'views' => 'DESC',
                    );
                } else {
                    $this->meta_queries['views'] = array(
                        'key'     => '_atbdp_post_views_count',
                        'value'   => (int)$this->view_to_popular,
                        'type'    => 'NUMERIC',
                        'compare' => '>=',
                    );
                    $this->args['orderby'] = array(
                        'views' => 'DESC',
                    );
                    if ( $this->listings->have_posts() ) {
                        while ( $this->listings->have_posts() ) {
                            $this->listings->the_post();
                            $listing_id = get_the_ID();
                            $average    = ATBDP()->review->get_average( $listing_id );
                            if ( $average_review_for_popular <= $average ) {
                                $this->rated[] = get_the_ID();
                            }
                        }
                        $rating_id = array(
                            'post__in' => ! empty( $this->rated ) ? $this->rated : array(),
                        );
                        $this->args = array_merge( $args, $rating_id );
                    }
                }
            }
        }

        switch ( $this->current_order ) {
        case 'title-asc':
            if ( $this->has_featured ) {
                $this->args['meta_key'] = '_featured';
                $this->args['orderby']  = array(
                    'meta_value_num' => 'DESC',
                    'title'          => 'ASC',
                );
            } else {
                $this->args['orderby'] = 'title';
                $this->args['order']   = 'ASC';
            };
            break;
        case 'title-desc':
            if ( $this->has_featured ) {
                $this->args['meta_key'] = '_featured';
                $this->args['orderby']  = array(
                    'meta_value_num' => 'DESC',
                    'title'          => 'DESC',
                );
            } else {
                $this->args['orderby'] = 'title';
                $this->args['order']   = 'DESC';
            };
            break;
        case 'date-asc':
            if ( $this->has_featured ) {
                $this->args['meta_key'] = '_featured';
                $this->args['orderby']  = array(
                    'meta_value_num' => 'DESC',
                    'date'           => 'ASC',
                );
            } else {
                $this->args['orderby'] = 'date';
                $this->args['order']   = 'ASC';
            };
            break;
        case 'date-desc':
            if ( $this->has_featured ) {
                $this->args['meta_key'] = '_featured';
                $this->args['orderby']  = array(
                    'meta_value_num' => 'DESC',
                    'date'           => 'DESC',
                );
            } else {
                $this->args['orderby'] = 'date';
                $this->args['order']   = 'DESC';
            };
            break;
        case 'price-asc':
            if ( $this->has_featured ) {
                $this->meta_queries['price'] = array(
                    'key'     => '_price',
                    'type'    => 'NUMERIC',
                    'compare' => 'EXISTS',
                );

                $this->args['orderby'] = array(
                    '_featured' => 'DESC',
                    'price'     => 'ASC',
                );
            } else {
                $this->args['meta_key'] = '_price';
                $this->args['orderby']  = 'meta_value_num';
                $this->args['order']    = 'ASC';
            };
            break;
        case 'price-desc':
            if ( $this->has_featured ) {
                $this->meta_queries['price'] = array(
                    'key'     => '_price',
                    'type'    => 'NUMERIC',
                    'compare' => 'EXISTS',
                );

                $this->args['orderby'] = array(
                    '_featured' => 'DESC',
                    'price'     => 'DESC',
                );
            } else {
                $this->args['meta_key'] = '_price';
                $this->args['orderby']  = 'meta_value_num';
                $this->args['order']    = 'DESC';
            };
            break;
        case 'rand':
            if ( $this->has_featured ) {
                $this->args['meta_key'] = '_featured';
                $this->args['orderby']  = 'meta_value_num rand';
            } else {
                $this->args['orderby'] = 'rand';
            };
            break;
        }
        $this->meta_queries       = apply_filters( 'atbdp_all_listings_meta_queries', $this->meta_queries );
        $this->count_meta_queries = count( $this->meta_queries );

        if ( $this->count_meta_queries ) {
            $this->args['meta_query'] = ( $this->count_meta_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $this->meta_queries ) : $this->meta_queries;
        }

        $this->arguments    = apply_filters( 'atbdp_all_listings_query_arguments', $this->args );
        $this->all_listings = new WP_Query( $this->arguments );
        $this->paginate     = get_directorist_option( 'paginate_all_listings' );

        $this->listing_count = '<span>' . count( $this->all_listings->posts ) . '</span>';

        if ( 'yes' == $this->show_pagination ) {
            $this->listing_count = '<span>' . $this->all_listings->found_posts . '</span>';
        }

        $this->display_header          = ! empty( $this->display_header ) ? $this->display_header : '';
        $this->header_title_for_search = ! empty( $this->header_title ) ? $this->header_title : '';
        $this->header_title            = ! empty( $this->header_title ) ? $this->listing_count . ' ' . $this->header_title : '';
        $this->listing_filters_button  = ! empty( $this->atts['advanced_filter'] ) ? (  ( 'yes' === $this->atts['advanced_filter'] ) ? 1 : (  ( 'no' === $this->atts['advanced_filter'] ) ? 0 : $this->listing_filters_button ) ) : $this->listing_filters_button;
        $this->filters                 = get_directorist_option( 'listings_filter_button_text', __( 'Filters', 'directorist' ) );
        $this->text_placeholder        = get_directorist_option( 'listings_search_text_placeholder', __( 'What are you looking for?', 'directorist' ) );
        $this->category_placeholder    = get_directorist_option( 'listings_category_placeholder', __( 'Select a category', 'directorist' ) );
        $this->location_placeholder    = get_directorist_option( 'listings_location_placeholder', __( 'Select a location', 'directorist' ) );
        $this->all_listing_title       = ! empty( $this->all_listing_title ) ? $this->all_listing_title : '';
        // $this->data_for_template              = compact('all_listings', 'all_listing_title', 'paged', 'paginate');
        $this->search_more_filters_fields     = get_directorist_option( 'listing_filters_fields', array( 'search_text', 'search_category', 'search_location', 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search' ) );
        $this->filters_button                 = get_directorist_option( 'listings_filters_button', array( 'reset_button', 'apply_button' ) );
        $this->reset_filters_text             = get_directorist_option( 'listings_reset_text', __( 'Reset Filters', 'directorist' ) );
        $this->apply_filters_text             = get_directorist_option( 'listings_apply_text', __( 'Apply Filters', 'directorist' ) );
        $this->sort_by_text                   = get_directorist_option( 'sort_by_text', __( 'Sort By', 'directorist' ) );
        $this->view_as_text                   = get_directorist_option( 'view_as_text', __( 'View As', 'directorist' ) );
        $this->view_as_items                  = get_directorist_option( 'listings_view_as_items', array( 'listings_grid', 'listings_list', 'listings_map' ) );
        $this->views                          = atbdp_get_listings_view_options( $this->view_as_items );
        $this->sort_by_items                  = get_directorist_option( 'listings_sort_by_items', array( 'a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random' ) );
        $this->listing_header_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
        $this->header_container_fluid         = apply_filters( 'atbdp_listings_header_container_fluid', $this->listing_header_container_fluid );
        $this->listing_grid_container_fluid   = is_directoria_active() ? 'container' : 'container-fluid';
        $this->grid_container_fluid           = apply_filters( 'atbdp_listings_grid_container_fluid', $this->listing_grid_container_fluid );
        $this->listing_location_address       = get_directorist_option( 'listing_location_address', 'map_api' );
        $this->include                        = apply_filters( 'include_style_settings', true );

        $this->all_listings            = ! empty( $this->all_listings ) ? $this->all_listings : new WP_Query();
        $this->display_sortby_dropdown = get_directorist_option( 'display_sort_by', 1 );
        $this->display_viewas_dropdown = get_directorist_option( 'display_view_as', 1 );
        $this->display_image           = ! empty( $this->display_image ) ? $this->display_image : '';
        $this->show_pagination         = ! empty( $this->show_pagination ) ? $this->show_pagination : '';
        $this->paged                   = ! empty( $this->paged ) ? $this->paged : '';

        $this->is_disable_price = get_directorist_option( 'disable_list_price' );
        $this->view_as          = get_directorist_option( 'grid_view_as', 'normal_grid' );
        $this->column_width     = 100 / (int)$this->columns . '%';
        $this->grid_col_class     = 100 / (int)$this->columns . '%';
        $this->grid_col_class   = "atbdp-col-$this->columns";

        $this->address_label        = get_directorist_option( 'address_label', __( 'Address', 'directorist' ) );
        $this->fax_label            = get_directorist_option( 'fax_label', __( 'Fax', 'directorist' ) );
        $this->email_label          = get_directorist_option( 'email_label', __( 'Email', 'directorist' ) );
        $this->website_label        = get_directorist_option( 'website_label', __( 'Website', 'directorist' ) );
        $this->tag_label            = get_directorist_option( 'tag_label', __( 'Tag', 'directorist' ) );
        $this->zip_label            = get_directorist_option( 'zip_label', __( 'Zip', 'directorist' ) );
        $this->listing_filters_icon = get_directorist_option( 'listing_filters_icon', 1 );
        $this->query_args           = array(
            'parent'             => 0,
            'term_id'            => 0,
            'hide_empty'         => 0,
            'orderby'            => 'name',
            'order'              => 'asc',
            'show_count'         => 0,
            'single_only'        => 0,
            'pad_counts'         => true,
            'immediate_category' => 0,
            'active_term_id'     => 0,
            'ancestors'          => array(),
        );
        $this->categories_fields = search_category_location_filter( $this->query_args, ATBDP_CATEGORY );
        $this->locations_fields  = search_category_location_filter( $this->query_args, ATBDP_LOCATION );
        $this->currency          = get_directorist_option( 'g_currency', 'USD' );
        $this->c_symbol          = atbdp_currency_symbol( $this->currency );

        $this->select_listing_map = get_directorist_option( 'select_listing_map', 'google' );
        $this->geo_loc            = ( 'google' == $this->select_listing_map ) ? '<span class="atbd_get_loc la la-crosshairs"></span>' : '<span class="atbd_get_loc la la-crosshairs"></span>';
    }

    public function price_field_data() {
        $min_price_value = ( isset( $_GET['price'] ) ) ? esc_attr( $_GET['price'][0] ) : '';
        $max_price_value = ( isset( $_GET['price'] ) ) ? esc_attr( $_GET['price'][1] ) : '';

        return compact( 'min_price_value', 'max_price_value' );
    }

    // price_range_field_data
    public function price_range_field_data() {
        $bellow_economy_value = '';
        $economy_value        = '';
        $moderate_value       = '';
        $skimming_value       = '';

        if ( ! empty( $_GET['price_range'] ) ) {
            $bellow_economy_value = ( 'bellow_economy' == $_GET['price_range'] ) ? "checked='checked'" : '';
            $economy_value        = ( 'economy' == $_GET['price_range'] ) ? "checked='checked'" : '';
            $moderate_value       = ( 'moderate' == $_GET['price_range'] ) ? "checked='checked'" : '';
            $skimming_value       = ( 'skimming' == $_GET['price_range'] ) ? "checked='checked'" : '';
        }

        $data = compact(
            'bellow_economy_value',
            'economy_value',
            'moderate_value',
            'skimming_value'
        );

        return $data;
    }

    // rating_field_data
    public function rating_field_data() {
        $rating_options = array(
            array(
                'selected' => '',
                'value'    => '',
                'label'    => __( 'Select Ratings', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '5' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '5',
                'label'    => __( '5 Star', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '4' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '4',
                'label'    => __( '4 Star & Up', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '3' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '3',
                'label'    => __( '3 Star & Up', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '2' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '2',
                'label'    => __( '2 Star & Up', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '1' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '1',
                'label'    => __( '1 Star & Up', 'directorist' ),
            ),
        );

        return $rating_options;
    }

    // atbdp_search_fields_wrapper_style
    public function search_fields_wrapper_style() {
        echo empty( $this->search_border ) ? ' style="border: none;"' : '';
    }

    // tag_field_data
    public function tag_field_data() {
        $listing_tags_field = get_directorist_option( 'listing_tags_field', 'all_tags' );
        $category_slug      = get_query_var( 'atbdp_category' );
        $category           = get_term_by( 'slug', $category_slug, ATBDP_CATEGORY );
        $category_id        = ! empty( $category_slug ) ? $category->term_id : '';
        $tag_args           = array(
            'post_type' => ATBDP_POST_TYPE,
            'tax_query' => array(
                array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'terms'    => ! empty( $_GET['in_cat'] ) ? $_GET['in_cat'] : $category_id,
                ),
            ),
        );
        $category_select = ! empty( $_GET['in_cat'] ) ? $_GET['in_cat'] : $category_id;
        $tag_posts       = get_posts( $tag_args );
        if ( ! empty( $tag_posts ) ) {
            foreach ( $tag_posts as $tag_post ) {
                $tag_id[] = $tag_post->ID;
            }
        }
        $tag_id = ! empty( $tag_id ) ? $tag_id : '';
        $terms  = wp_get_object_terms( $tag_id, ATBDP_TAGS );

        if ( 'all_tags' == $listing_tags_field || empty( $category_select ) ) {
            $terms = get_terms( ATBDP_TAGS );
        }

        if ( ! empty( $terms ) ) {
            return $terms;
        }

        return null;
    }


    // listing_card_class
    public function listing_card_class() {
        $loop_data = $this->listings_loop_data();
        extract( $loop_data );

        $atbd_listing_card_class = get_directorist_option('info_display_in_single_line', 0) ? 'atbd_single_line_card_info' : '';
        $atbd_listing_card_class = get_directorist_option(esc_html($listing_preview_img_class));

        echo $atbd_listing_card_class;
    }

    // listing_wrapper_class
    public function listing_wrapper_class() {
        $featured = get_post_meta( get_the_ID(), '_featured', true );
        echo ($featured) ? 'directorist-featured-listings' : '';
    }
}

endif;
