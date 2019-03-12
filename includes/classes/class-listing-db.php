<?php
/**
 * Listing DB class
 *
 * This class is for interacting with listing post type
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes/class-listing-db
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'Sorry, it is not your place to have fun..' ); }

if (!class_exists('ATBDP_Listing_DB')):
class ATBDP_Listing_DB {


    /**
     *It returns all the listing
     * @return bool|WP_Query it returns an object of WP_Query with all listings on success and false on failure
     */
    public function all_listing()
    {
        $listings = new WP_Query(array(
            'post_type' => ATBDP_POST_TYPE,
            'post_per_page'=>-1
        ));
        if ($listings->found_posts) return $listings;
        return false;
    }

    public function get_listing_order_by_featured()
    {
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'post_per_page'=>-1
        );
    }

    /**
     * It returns all the listing of the given user or the current user if no user id is passed.
     * @param int $user_id [optional] The id of the user. Default is current user id.
     * @return WP_Query   it returns an object of the WP_Query class with the items/listings on success and false on failure.
     */
    public function get_listing_by_user($user_id=0)
    {
        /*@Todo; Add pagination later, better use ajax pagination*/
        $args = array(
            'author'=> !empty($user_id) ? absint($user_id) :  get_current_user_id(),
            'post_type'=> ATBDP_POST_TYPE,
            'posts_per_page' => -1, //@todo; Add pagination in future.
            'order'=> 'DESC',
            'orderby' => 'date'
        );
        return new WP_Query($args);
    }

    /**
     * It deletes a specific listing along with its meta and reviews by listing id
     * @param int $id The ID of the listing that should be deleted
     * @return bool It returns true on success and false on failure
     */
    public function delete_listing_by_id($id)
    {
        $deleted = wp_delete_post(absint($id), true); // i
        if ( false !== $deleted ) {
            // as post has been deleted, now delete the review if there is any associated with the post
            $review_delete = ATBDP()->review->db->delete_reviews_by('post_id', absint($id));
            return true;

        }
        return false;

    }
    //@TODO; methods to add: delete all listing by user

    public function get_favourites($user_id=0)
    {
        $user_id = !empty($user_id) ? absint($user_id) :  get_current_user_id();
        $fav_listing = get_user_meta($user_id, 'atbdp_favourites', true);
        $fav_listing = !empty($fav_listing)?$fav_listing:'';
        /*@Todo; Add pagination later, better use ajax pagination*/
        if (!empty($_GET['atbdp_action'])?$_GET['atbdp_action']:''){

            $remove_id = !empty($_GET['atbdp_listing'])?$_GET['atbdp_listing']:array();
            $fav_listing = get_user_meta($user_id, 'atbdp_favourites', true);
            if( in_array( $remove_id, $fav_listing ) ) {
                if( ( $key = array_search( $remove_id, $fav_listing ) ) !== false ) {
                    unset( $fav_listing[ $key ] );
                }
            } else {
                $fav_listing[] = $remove_id;
            }

            $fav_listing = array_filter( $fav_listing );
            $fav_listing = array_values( $fav_listing );

            delete_user_meta( get_current_user_id(), 'atbdp_favourites' );
            update_user_meta( get_current_user_id(), 'atbdp_favourites', $fav_listing );
        }
        if (!empty($fav_listing)){
            $args = array(
               // 'author'=> $user_id,
                'post_type'=> ATBDP_POST_TYPE,
                'posts_per_page' => -1, //@todo; Add pagination in future.
                'order'=> 'DESC',
                'post__in' => $fav_listing,
                'orderby' => 'date'

            );
        }else{
            $args = array();
        }


        return new WP_Query($args);
    }

} // ends class ATBDP_Listing_DB

endif;