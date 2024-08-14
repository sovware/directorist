<?php
/**
 * ATBDP Permalink class
 *
 * This class is for interacting with Permalink, eg, receiving link to different page.
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Permalink
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

use Directorist\Helper;

// Exit if accessed directly
if ( ! defined('ABSPATH') ) { die( 'You are not allowed to access this file directly' ); }

if (!class_exists('ATBDP_Permalink')):
class ATBDP_Permalink {

    // atbdp_get_listing_permalink
    public static function get_listing_permalink( $post_id = 0, $permalink = '' ) {
        if ( ATBDP_POST_TYPE !== get_post_type( $post_id ) ) {
			return $permalink;
		}

        if ( empty( get_directorist_option( 'single_listing_slug_with_directory_type', false ) ) ) {
            return $permalink;
        }

		if ( empty( $permalink ) ) {
			$permalink = get_the_permalink( $post_id );
		}

		$directory_slug = '';
		$directory_id   = (int) get_post_meta( $post_id, '_directory_type', true );

        if ( $directory_id ) {
            $directory_term = get_term( $directory_id, ATBDP_DIRECTORY_TYPE );
            $directory_slug = $directory_term ? $directory_term->slug : '';
        }

		if ( empty( $directory_slug ) ) {
			$directory_terms = get_the_terms( $post_id, ATBDP_DIRECTORY_TYPE );

			if ( ! is_wp_error( $directory_terms ) && ! empty( $directory_terms ) ) {
				$directory_slug = $directory_terms[0]->slug;
			}
		}

        $permalink = str_replace( '%' . ATBDP_DIRECTORY_TYPE . '%', $directory_slug, $permalink );

        return $permalink;
    }

    public static function get_listing_slug() {
        $listing_slug = 'directory/%'. ATBDP_DIRECTORY_TYPE .'%';
        $custom_listing_slug = get_directorist_option('atbdp_listing_slug', 'directory');

        if ( ! empty( $custom_listing_slug ) ) {
            $listing_slug = $custom_listing_slug. '/%' .ATBDP_DIRECTORY_TYPE. '%';
        }

        return strtolower( $listing_slug );
    }

    /**
     * It returns the link to the custom search archive page of ATBDP
     * @return string
     */
    public static function get_search_result_page_link()
    {
        $link = home_url();
        $id = get_directorist_option('search_result_page'); // get the page id of the search page.

        if ( atbdp_required_polylang_url() && $id && pll_get_post( $id ) ) {
            $link = get_permalink( pll_get_post( $id ) );
            return apply_filters('atbdp_search_result_page_url', $link );
        }

        if ( $id ) {
            $link = get_permalink( $id );
        }

        return apply_filters('atbdp_search_result_page_url', $link );
    }

    /**
     * It returns the link to the custom search archive page of ATBDP
     * @return string
     */
    public static function get_user_profile_page_link( $author_id , $directory_type = '' )
    {
        $link = home_url();
        $page_id = get_directorist_option('author_profile_page');

        if ( $page_id ) {
            $link = get_permalink( $page_id );
            if( '' != get_option( 'permalink_structure' ) ) {
                $author = get_user_by( 'id', $author_id );
				$author_id = ( $author ) ? $author->user_login : $author_id;

                if( ! empty( $directory_type ) && directorist_is_multi_directory_enabled() ) {
                    $slug = $author_id . '/directory/' . $directory_type;
                    $link = Helper::join_slug_to_url( $link, $slug );
                } else {
                    $link = Helper::join_slug_to_url( $link, $author_id );
                }
            }
        }

        return apply_filters( 'atbdp_author_profile_page_url', $link, $page_id, $author_id, $directory_type );
    }

    /**
     * It returns the link to the custom search archive page of ATBDP
     * @return string
     */
    public static function get_login_redirection_page_link()
    {
        // Check if has temp redirect url
        $temp_login_redirect = get_transient( 'atbdp_redirect_after_login' );

        if ( ! empty( $temp_login_redirect ) ) {
            delete_transient( 'atbdp_redirect_after_login' );
            return $temp_login_redirect;
        }

        $page_id = get_directorist_option( 'redirection_after_login', 'previous_page' );

        if ( 'previous_page' == $page_id ) {
            $link = wp_get_referer();
        } else {
            $link = $page_id ? get_permalink( $page_id ) : '';
        }

        return apply_filters( 'atbdp_login_redirection_page_url', $link, $page_id );
    }

    public static function get_reg_redirection_page_link( $previous_page, $query_vars=array() )
    {
        $page_id = get_directorist_option( 'redirection_after_reg', 'previous_page' );

        if ( 'previous_page' == $page_id ) {
            $link = $previous_page;
        } else {
            $link = $page_id ? get_permalink( $page_id ) : '';
        }

        if ( ! empty( $query_vars ) && is_array( $query_vars ) ) {
            $link = add_query_arg( $query_vars, $link );
        }

        return apply_filters( 'atbdp_reg_redirection_page_url', $link, $page_id );
    }

    /**
     * It returns the link to the custom search archive page of ATBDP
     * @param array $query_vars [optional] Array of query vars to be added to the registration page url
     * @return string
     */
    public static function get_dashboard_page_link( $query_vars = array() )
    {
        $link    = home_url();
        $page_id = get_directorist_option( 'user_dashboard' );

        if ( $page_id )  {
            $link = get_permalink( $page_id );
        }

        if ( ! empty( $query_vars ) && is_array( $query_vars ) ){
            $link = add_query_arg( $query_vars, $link );
        }

        return apply_filters( 'atbdp_dashboard_page_url', $link, $page_id );
    }
    /**
     * It returns the link to the custom search archive page of ATBDP
     * @return string
     */
    public static function get_transaction_failure_page_link()
    {
        $link = home_url();
        $page_id = get_directorist_option('transaction_failure_page');

        if ( $page_id )  {
            $link = get_permalink( $page_id );
        }

        return apply_filters( 'atbdp_transaction_failure_page_url', $link, $page_id );
    }


    /**
     * It returns the link to the custom search archive page of ATBDP
     * @param array $query_vars [optional] Array of query vars to be added to the registration page url
     * @return string
     */
    public static function get_registration_page_link($query_vars=array())
    {
        $link = home_url();
        $page_id = get_directorist_option('custom_registration'); // get the page id of the custom registration page.

        if ( $page_id ) {
            $link = get_permalink( $page_id );
        }

        if ( ! empty( $query_vars ) && is_array( $query_vars ) ) {
            $link = add_query_arg( $query_vars, $link );
        }

        return apply_filters( 'atbdp_registration_page_url', $link, $page_id );
    }

    /**
     * It returns the link to the custom search archive page of ATBDP
     * @param array $query_vars [optional] Array of query vars to be added to the registration page url
     * @return string
     */
    public static function get_registration_page_url($query_vars=array())
    {
        $link = home_url().'/registration';
        /*$id = get_directorist_option('custom_registration'); // get the page id of the custom registration page.
        if( $id ) $link = get_permalink( $id );
        if (!empty($query_vars) && is_array($query_vars)){
            $link = add_query_arg( $query_vars, $link );
        }*/
        return apply_filters('atbdp_registration_page_url', $link);
    }


    /**
     * It returns the link to the all listing page of ATBDP
     * @param array $query_vars [optional] Array of query vars to be added to the all listing page url
     * @return string
     * @since 5.0
     */
    public static function get_directorist_listings_page_link($query_vars=array())
    {
        $link = home_url();
        $page_id = get_directorist_option( 'all_listing_page' );

        if ( $page_id ) {
            $link = get_permalink( $page_id );
        }

        if (!empty($query_vars) && is_array($query_vars)){
            $link = add_query_arg( $query_vars, $link );
        }

        return apply_filters( 'atbdp_listings_page_url', $link, $page_id );
    }

    /**
     * It returns the link to the custom search archive page of ATBDP
     * @param array $query_vars [optional] Array of query vars to be added to the registration page url
     * @return string
     */
    public static function get_login_page_link($query_vars=array())
    {
        $login_url = ATBDP_Permalink::get_login_page_url( $query_vars );
        return apply_filters('atbdp_user_login_page_url', $login_url);
    }

    /**
     * It returns the link to the custom search archive page of ATBDP
     * @param array $query_vars [optional] Array of query vars to be added to the registration page url
     * @return string
     */
    public static function get_login_page_url($query_vars=array())
    {
        $link = home_url() .'/login';
        $page_id = get_directorist_option( 'user_login' );

        if( $page_id ) {
            $link = get_permalink( $page_id );
        }

        if (!empty($query_vars) && is_array($query_vars)){
            $link = add_query_arg( $query_vars, $link );
        }

        return apply_filters( 'atbdp_user_login_page_url', $link, $page_id );
    }


    /**
     * It returns the link to the add listing page
     * @return string
     */
    public static function get_add_listing_page_link()
    {
        $link = home_url();
        $id = get_directorist_option('add_listing_page');
        if( $id ) $link = get_permalink( $id );
        return apply_filters('atbdp_add_listing_page_url', $link );
    }

    /**
     * It returns the link to the add listing page
     * @return string
     */
    public static function get_add_listing_page_link_with_plan($plan_id)
    {
        $link = home_url();
        $page_id = get_directorist_option( 'add_listing_page' );

        if ( $page_id ) {
            $args = [
                'listing_type' => isset( $_GET['directory_type'] ) ? sanitize_text_field( wp_unslash( $_GET['directory_type'] ) ) : '',
                'plan' => $plan_id,
            ];
            $link = get_permalink( $page_id );
            $link = add_query_arg( $args, $link);
        }

        return apply_filters( 'atbdp_add_listing_page_url', $link, $page_id, $plan_id );
    }

    /**
     * It returns the link to the dashbord manage fees
     * @return string
     */
    public static function get_fee_plan_page_link()
    {
        $link = home_url();
        $page_id = get_directorist_option( 'pricing_plans' );

        if ( $page_id )  {
            $link = get_permalink( $page_id );
        }

        return apply_filters( 'atbdp_fee_plan_page_url', $link, $page_id );
    }

    /**
     * It returns the link to the dashbord manage fees
     * @since 6.3
     * @return string
     */
    public static function get_privacy_policy_page_url()
    {
        $link = home_url();
        $page_id = get_directorist_option( 'privacy_policy' );

        if( $page_id )  {
            $link = get_permalink( $page_id );
        }

        return apply_filters( 'atbdp_privacy_policy_page_url', $link, $page_id );
    }

    /**
     * It returns the link to the dashbord manage fees
     * @since 6.3
     * @return string
     */
    public static function get_terms_and_conditions_page_url()
    {
        $link = home_url();
        $page_id = get_directorist_option( 'terms_conditions' );

        if ( $page_id ) {
            $link = get_permalink( $page_id );
        }

        return apply_filters( 'atbdp_terms_and_conditions_page_url', $link, $page_id );
    }


    /**
     * It returns the link to the custom edit listing page
     * @param int $listing_id Listing ID
     * @since 3.1.0
     * @return string
     */
    public static function get_edit_listing_page_link($listing_id)
    {
        $link = home_url();
        $page_id = get_directorist_option('add_listing_page');

        if ( $page_id ) {
            $link = get_permalink( $page_id );
            if ( '' != get_option( 'permalink_structure' ) ) {
                $link = user_trailingslashit( trailingslashit( $link )  . 'edit/' . $listing_id );
            } else {
                $link = add_query_arg( array( 'atbdp_action' => 'edit', 'atbdp_listing_id' => $listing_id ), $link );
            }
        }

        return apply_filters( 'atbdp_edit_listing_page_url', $link, $page_id, $listing_id );
    }


    /**
     * It returns the current page url of the wordpress and you can add any query string to the url
     * @param array $query_args The array of query arguments passed to the current url
     * @return mixed it returns the current url of WordPress
     */
    public static function get_current_page_url($query_args=array()){

        global $wp;
        $link = home_url($wp->request);
        if (!is_empty_v($query_args)){
            $link = home_url(add_query_arg($query_args, $wp->request));
        }

        return apply_filters('atbdp_current_page_url', $link );
    }


    /**
     * It returns the link to the custom category archive page of ATBDP
     * @param $cat
     * @param string $field
     * @return string
     */
    public static function get_category_archive($cat, $field='slug')
    {
        $link = add_query_arg(
            array(
                'q'=>'',
                'in_cat'=>$cat->{$field}
            ),
            self::get_search_result_page_link()
        );
        return apply_filters( 'atbdp_category_archive_url', $link, $cat, $field );
    }

    /**
     * Generate a permalink for single category page.
     *
     * @since    4.5.3
     *
     * @param    object    $term    The term object.
     * @return   string             Term link.
     */
    public static function atbdp_get_category_page( $term, $directory_type = '' ) {

        $page_id = get_directorist_option('single_category_page');
        $link = '/';

        if ( $page_id  ) {
            if ( atbdp_required_polylang_url() ) {
                $translation_page = pll_get_post( $page_id );

                if ( $translation_page ) {

                    $args = [
                        'category'       => $term->slug,
                        'directory_type' => $directory_type,
                    ];

                    $translatted_page_link = get_permalink( $translation_page );

                    $link = add_query_arg( $args, $translatted_page_link );

                    return apply_filters( 'atbdp_single_category', $link, $page_id, $term, $directory_type );

                }
            } else {
                $link = get_permalink( $page_id );

                if( '' != get_option( 'permalink_structure' ) ) {
                    $link = user_trailingslashit( trailingslashit( $link ) . $term->slug );

                } else {
                    $link = add_query_arg( 'atbdp_category', $term->slug, $link );
                }
            }
        }

        if ( ! empty( $directory_type ) && 'all' != $directory_type ) {
            $link = $link . '?directory_type=' . $directory_type;
        }

        return apply_filters( 'atbdp_single_category', $link, $page_id, $term, $directory_type );

    }

    /**
     * Generate a permalink for single location page.
     *
     * @since    4.5.3
     *
     * @param    object    $term    The term object.
     * @return   string             Term link.
     */
    public static function atbdp_get_location_page( $term, $directory_type = '' ) {

        $page_id = get_directorist_option('single_location_page');
        $link = '/';

        if ( $page_id  ) {
            if ( atbdp_required_polylang_url() ) {
                $translation_page = pll_get_post( $page_id );

                if ( $translation_page ) {

                    $args = [
                        'location'          => $term->slug,
                        'directory_type'    => $directory_type,
                    ];

                    $translatted_page_link = get_permalink( $translation_page );
                    $link = add_query_arg( $args, $translatted_page_link );

                    return apply_filters( 'atbdp_single_location', $link, $page_id, $term, $directory_type );

                }
            } else {
                $link = get_permalink( $page_id );

                if ( '' != get_option( 'permalink_structure' ) ) {
                    $link = user_trailingslashit( trailingslashit( $link ) . $term->slug );

                } else {
                    $link = add_query_arg( 'atbdp_location', $term->slug, $link );
                }
            }
        }

        if ( ! empty( $directory_type ) && 'all' != $directory_type ) {
            $link = $link . '?directory_type=' . $directory_type;
        }

        return apply_filters( 'atbdp_single_location', $link, $page_id, $term, $directory_type );

    }

    /**
     * Generate a permalink for single location page.
     *
     * @since    4.5.3
     *
     * @param    object    $term    The term object.
     * @return   string             Term link.
     */
    public static function atbdp_get_tag_page( $term, $directory_type = '' ) {

        $page_id = get_directorist_option('single_tag_page');
        $link = '/';

        if ( $page_id  ) {
            if ( atbdp_required_polylang_url() ) {
                $translation_page = pll_get_post( $page_id );

                if ( $translation_page ) {
                    $args = [
                        'location'          => $term->slug,
                        'directory_type'    => $directory_type,
                    ];

                    $translatted_page_link = get_permalink( $translation_page );
                    $link = add_query_arg( $args, $translatted_page_link );

                    return apply_filters( 'atbdp_single_tag', $link, $page_id, $term, $directory_type );
                }
            } else {
                $link = get_permalink( $page_id );

                if ( '' != get_option( 'permalink_structure' ) ) {
                    $link = user_trailingslashit( trailingslashit( $link ) . $term->slug );

                } else {
                    $link = add_query_arg( 'atbdp_single_tag', $term->slug, $link );
                }
            }
        }

        if ( ! empty( $directory_type ) && 'all' != $directory_type ) {
            $link = $link . '?directory_type=' . $directory_type;
        }

        return apply_filters( 'atbdp_single_tag', $link, $page_id, $term, $directory_type );

    }

    /**
     * It returns the link to the custom category archive page of ATBDP
     * @param $loc
     * @param string $field
     * @return string
     */
    public static function get_location_archive($loc, $field='slug')
    {
        $link = add_query_arg(
            array(
                'q'=>'',
                'in_loc'=>$loc->{$field}
            ),
            self::get_search_result_page_link()
        );
        return apply_filters( 'atbdp_location_archive_url', $link, $loc, $field );

    }

    /**
     * It returns the link to the custom tag archive page of ATBDP
     * @param WP_Term $tag
     * @param string $field
     * @return string
     */
    public static function get_tag_archive($tag, $field='slug')
    {
        $link = add_query_arg(
            array(
                'q'=>'',
                'in_tag'=>$tag->{$field}
            ),
            self::get_search_result_page_link()
        );
        return apply_filters( 'atbdp_tag_archive_url', $link, $tag, $field );
    }

    /**
     * Generate a permalink for Payment receipt page.
     *
     * @since    3.1.0
     *
     * @param    int       $order_id    Order ID.
     * @return   string                 Payment receipt page URL.
     */
    public static function get_payment_receipt_page_link($order_id) {
        $link = home_url(); // default url
        $page_id = get_directorist_option( 'payment_receipt_page' );

        if ( $page_id ) {
            $link = get_permalink( $page_id );

            if ( '' != get_option( 'permalink_structure' ) ) {
                $link = user_trailingslashit( trailingslashit( $link ) . 'order/' . $order_id );
            } else {
                $link = add_query_arg(
                    array(
                        'atbdp_action' => 'order',
                        'atbdp_order' => $order_id
                    ),
                    $link
                );
            }
        }

        return apply_filters( 'atbdp_payment_receipt_page_url', $link, $page_id, $order_id );
    }

    /**
     * Generate a permalink for Checkout page
     *
     * @since    3.1.0
     *
     * @param    int       $listing_id    Listing ID.
     * @return   string                   It returns Checkout page URL.
     */
    public static function get_checkout_page_link($listing_id) {
        $link = home_url(); // default url
        $page_id = get_directorist_option('checkout_page');

        if ( $page_id ) {
            $link = get_permalink( $page_id );

            if ( '' != get_option( 'permalink_structure' ) ) {
                $link = user_trailingslashit( trailingslashit( $link ) . 'submit/' . $listing_id );
            } else {
                $link = add_query_arg(
                    array(
                        'atbdp_action' => 'submission',
                        'atbdp_listing_id' => $listing_id
                    ),
                    $link
                );
            }
        }

        return apply_filters( 'atbdp_checkout_page_url', $link, $page_id, $listing_id );
    }

    /**
     * Generate a permalink for Checkout page
     *
     * @since    3.1.0
     *
     * @param    int       $listing_id    Listing ID.
     * @return   string                   It returns Checkout page URL.
     */
    public static function get_fee_renewal_checkout_page_link($listing_id) {
        $link = home_url(); // default url
        $page_id = get_directorist_option('checkout_page');

        if ( $page_id ) {
            $link = get_permalink( $page_id );

            if ( '' != get_option( 'permalink_structure' ) ) {
                $link = user_trailingslashit( trailingslashit( $link ) . 'submit/' . $listing_id );
            } else {
                $link = add_query_arg(
                    array(
                        'atbdp_action' => 'submission',
                        'atbdp_listing_id' => $listing_id
                    ),
                    $link
                );
            }
        }

        return apply_filters( 'atbdp_renewal_checkout_page_url', $link, $page_id, $listing_id );
    }

    public static function get_renewal_page_link($listing_id)
    {
        $link = home_url();
        $id = get_directorist_option('add_listing_page');

        if( $id ) {
            $link = get_permalink( $id );
            if( '' != get_option( 'permalink_structure' ) ) {
                $link = user_trailingslashit( trailingslashit( $link )  . 'renew/' . $listing_id );
            } else {
                $link = add_query_arg( array( 'atbdp_action' => 'renew', 'atbdp_listing_id ' => $listing_id ), $link );
            }
        }

        return $link;
    }

    /**
     * Generate a permalink for IPN Notify URL
     *
     * @since    3.1.2
     *
     * @param    int       $order_id    Order ID.
     * @return   string                   It returns IPN Notify URL
     */
    public static function get_ipn_notify_page_link($order_id) {
        $link = home_url(); // default url
        $page_id = get_directorist_option( 'checkout_page' );

        if ( $page_id ) {
            $link = get_permalink( $page_id );

            if ( '' != get_option( 'permalink_structure' ) ) {
                $link = user_trailingslashit( trailingslashit( $link ) . 'paypal-ipn/' . $order_id );
            } else {
                $link = add_query_arg(
                    array(
                        'atbdp_action' => 'paypal-ipn',
                        'atbdp_order_id' => $order_id
                    ),
                    $link
                );
            }
        }

        return apply_filters( 'atbdp_paypal_ipn_page_url', $link, $page_id, $order_id );
    }

} // end ATBDP_Permalink

endif;
