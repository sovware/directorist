<?php
/**
 * Rating Review class
 *
 * This class is for interacting with the star and review database table
 *
 * @package     ATBDP
 * @subpackage  inlcudes/review-rating Rating Review
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'You should not access this file directly' ); }

if (!class_exists('ATBDP_Review_Rating')):

class ATBDP_Review_Rating{

    /**
     * ATBDP_Review_Rating_DB Object.
     *
     * @var object|ATBDP_Review_Rating_DB
     * @since 1.0
     */
    public $db;


    public function __construct()
    {
        $this->includes();
        $this->db = new ATBDP_Review_Rating_DB;
    }



    /**
     *It includes required files for the review rating object to work.
     */
    public function includes()
    {
        require_once ATBDP_INC_DIR . 'review-rating/class-review-rating-database.php';
    }

    /**
     * Print the an html list of rating
     * @param int $star_number the number of start that should be colored
     * @return string
     */
    public function print_static_rating($star_number=1)
    {
        $v ='<ul>';
            for ($i=1; $i<=5; $i++){
                $v .= ($i <= $star_number)
                    ? "<li><span class='directorist-rate-active'></span></li>"
                    : "<li><span class='directorist-rate-disable'></span></li>";
                /*Less readable
                 * $c = ($i <= $review->rating) ? 'rate_active' : 'rate_disable';
                echo "<li><span class='{$c}'></span></li>";*/
            }
        $v .= '</ul>';
        return $v;
    }




    /**
     * It returns the average of stars of a post.
     * @param int|object $post_id_or_object The ID of the post to get all ratings or the reviews/rating objects
     * @return float|int
     */
    public function get_average($post_id_or_object)
    {
        /*RATING RELATED STUFF STARTS*/
        if (is_array($post_id_or_object)){
            $ratings = $post_id_or_object; // if it is an array of ratings than
        }elseif (is_numeric($post_id_or_object)){
            $ratings = $this->db->get_ratings_by('post_id', absint($post_id_or_object)); // get only 3
        }else{
            $ratings = array();
        }

// set placeholder or default counter of individual stars
        $s_5 =0;
        $s_4 =0;
        $s_3 =0;
        $s_2 =0;
        $s_1 =0;

        // find the count of the stars for this post's review
        if (!empty($ratings)) {
            foreach ($ratings as $rating) {
                if (is_object($rating)) {
                    switch ($rating->rating) {
                        case (string)5:
                            $s_5++; // how many 5 stars we have got?
                            break;
                        case (string)4:
                            $s_4++; // how many 4 stars we have got?
                            break;
                        case (string)3:
                            $s_3++; // how many 3 stars we have got?
                            break;
                        case (string)2:
                            $s_2++; // how many 1 stars we have got?
                            break;
                        case (string)1:
                            $s_1++; // how many 1 star we have got?
                            break;
                    }
                }
            }
        }

        $star_dividend = (5*$s_5 + 4*$s_4 + 3*$s_3 + 2*$s_2 + 1*$s_1); // add up all value of all starts together
        $star_divisor= ($s_5 + $s_4 + $s_3 + $s_2 + $s_1); // add up the count of of all stars
        $average = (!empty($star_dividend) && !empty($star_divisor)) ? ($star_dividend / $star_divisor) : 0;
        $average = substr($average, '0', '3');
        return $average;
    }




}



endif;
