<?php
if( !class_exists('ATBDP_GJSGeoQuery') ) {
    class ATBDP_GJSGeoQuery {
        public static function Instance() {
            static $instance = null;
            if ($instance === null) {
                $instance = new self();
            }
            return $instance;
        }

        private function __construct() {
            add_filter( 'posts_fields' , array( $this, 'posts_fields'  ), 10, 2 );
            add_filter( 'posts_join'   , array( $this, 'posts_join'    ), 10, 2 );
            add_filter( 'posts_where'  , array( $this, 'posts_where'   ), 10, 2 );
            add_filter( 'posts_orderby', array( $this, 'posts_orderby' ), 10, 2 );
        }

        // add a calculated "distance" parameter to the sql query, using a haversine formula
        public function posts_fields( $sql, $query ) {
            global $wpdb;
            $atbdp_geo_query = $query->get('atbdp_geo_query');
            if( $atbdp_geo_query ) {

                if( $sql ) {
                    $sql .= ', ';
                }
                $sql .= $this->haversine_term( $atbdp_geo_query ) . " AS atbdp_geo_query_distance";
            }
            return $sql;
        }

        public function posts_join( $sql, $query ) {
            global $wpdb;
            $atbdp_geo_query = $query->get('atbdp_geo_query');
            if( $atbdp_geo_query ) {

                if( $sql ) {
                    $sql .= ' ';
                }
                $sql .= "INNER JOIN " . $wpdb->prefix . "postmeta AS atbdp_geo_query_lat ON ( " . $wpdb->prefix . "posts.ID = atbdp_geo_query_lat.post_id ) ";
                $sql .= "INNER JOIN " . $wpdb->prefix . "postmeta AS atbdp_geo_query_lng ON ( " . $wpdb->prefix . "posts.ID = atbdp_geo_query_lng.post_id ) ";
            }
            return $sql;
        }

        // match on the right metafields, and filter by distance
        public function posts_where( $sql, $query ) {
            global $wpdb;
            $atbdp_geo_query = $query->get('atbdp_geo_query');
            if( $atbdp_geo_query ) {
                $lat_field = 'latitude';
                if( !empty( $atbdp_geo_query['lat_field'] ) ) {
                    $lat_field =  $atbdp_geo_query['lat_field'];
                }
                $lng_field = 'longitude';
                if( !empty( $atbdp_geo_query['lng_field'] ) ) {
                    $lng_field =  $atbdp_geo_query['lng_field'];
                }
                $distance = 20;
                if( isset( $atbdp_geo_query['distance'] ) ) {
                    $distance = $atbdp_geo_query['distance'];
                }
                if( $sql ) {
                    $sql .= " AND ";
                }
                $haversine = $this->haversine_term( $atbdp_geo_query );
                $new_sql = "( atbdp_geo_query_lat.meta_key = %s AND atbdp_geo_query_lng.meta_key = %s AND " . $haversine . " <= %f )";
                $sql .= $wpdb->prepare( $new_sql, $lat_field, $lng_field, $distance );
            }
            return $sql;
        }

        // handle ordering
        public function posts_orderby( $sql, $query ) {
            $atbdp_geo_query = $query->get('atbdp_geo_query');
            if( $atbdp_geo_query ) {
                $orderby = $query->get('orderby');
                $order   = $query->get('order');
                if( $orderby == 'distance' ) {
                    if( !$order ) {
                        $order = 'ASC';
                    }
                    $sql = 'atbdp_geo_query_distance ' . $order;
                }
            }
            return $sql;
        }

        public static function the_distance( $post_obj = null, $round = false ) {
            echo self::get_the_distance( $post_obj, $round );
        }

        public static function get_the_distance( $post_obj = null, $round = false ) {
            global $post;
            if( !$post_obj ) {
                $post_obj = $post;
            }
            if( property_exists( $post_obj, 'atbdp_geo_query_distance' ) ) {
                $distance = $post_obj->atbdp_geo_query_distance;
                if( $round !== false ) {
                    $distance = round( $distance, $round );
                }
                return $distance;
            }
            return false;
        }

        private function haversine_term( $atbdp_geo_query ) {
            global $wpdb;
            $units = "miles";
            if( !empty( $atbdp_geo_query['units'] ) ) {
                $units = strtolower( $atbdp_geo_query['units'] );
            }
            $radius = 3959;
            if( in_array( $units, array( 'km', 'kilometers' ) ) ) {
                $radius = 6371;
            }
            $lat_field = "atbdp_geo_query_lat.meta_value";
            $lng_field = "atbdp_geo_query_lng.meta_value";
            $lat = 0;
            $lng = 0;
            if( isset( $atbdp_geo_query['latitude'] ) ) {
                $lat = $atbdp_geo_query['latitude' ];
            }
            if(  isset( $atbdp_geo_query['longitude'] ) ) {
                $lng = $atbdp_geo_query['longitude'];
            }
            $haversine  = "( " . $radius . " * ";
            $haversine .=     "acos( cos( radians(%f) ) * cos( radians( " . $lat_field . " ) ) * ";
            $haversine .=     "cos( radians( " . $lng_field . " ) - radians(%f) ) + ";
            $haversine .=     "sin( radians(%f) ) * sin( radians( " . $lat_field . " ) ) ) ";
            $haversine .= ")";
            $haversine  = $wpdb->prepare( $haversine, array( $lat, $lng, $lat ) );
            return $haversine;
        }
    }
    ATBDP_GJSGeoQuery::Instance();
}

if( !function_exists( 'atbdp_the_distance' ) ) {
    function atbdp_the_distance( $post_obj = null, $round = false ) {
        ATBDP_GJSGeoQuery::the_distance( $post_obj, $round );
    }
}

if( !function_exists( 'atbdp_get_the_distance' ) ) {
    function atbdp_get_the_distance( $post_obj = null, $round = false ) {
        return ATBDP_GJSGeoQuery::get_the_distance( $post_obj, $round );
    }
}