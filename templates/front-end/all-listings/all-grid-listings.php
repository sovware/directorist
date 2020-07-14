<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$is_disable_price = get_directorist_option('disable_list_price');
$display_sortby_dropdown = get_directorist_option('display_sort_by', 1);
$display_viewas_dropdown = get_directorist_option('display_view_as', 1);
$view_as = get_directorist_option('grid_view_as', 'normal_grid');
if (is_rtl()) {
    wp_enqueue_style('atbdp-search-style-rtl', ATBDP_PUBLIC_ASSETS . 'css/search-style-rtl.css');
} else {
    wp_enqueue_style('atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
}
$column_width = 100 / (int)$columns . '%';
/**
 * @param WP_Query $all_listings It contains all the queried listings by a user
 * @since 5.5.1
 * @package Directorist
 */
do_action('atbdp_before_all_listings_grid', $all_listings);
?>

<div id="directorist" class="atbd_wrapper ads-advaced--wrapper">
    <?php 
    ob_start();
    include ATBDP_TEMPLATES_DIR . "front-end/all-listings/listings-header.php";
    $header_output = ob_get_clean();
    /**
     * @since 6.3.5
     * 
     */
    $header_output = apply_filters( 'atbdp_listing_header_html', $header_output, compact( 'display_header','header_container_fluid','search_more_filters_fields', 'listing_filters_button', 'header_title','listing_filters_icon','display_viewas_dropdown','display_sortby_dropdown', 'filters', 'view_as_text', 'view_as_items', 'sort_by_text', 'sort_by_items', 'listing_location_address', 'filters_button' ) );   
    echo $header_output;
    ?>
    <div class="<?php echo !empty($grid_container_fluid) ? $grid_container_fluid : ''; ?>">
        <?php
        /**
         * @since 5.0
         * It fires before the listings columns
         * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
         */
        $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
        if ('yes' === $action_before_after_loop) {
            do_action('atbdp_before_grid_listings_loop');
        }
        ?>
        <div class="row" <?php echo ($view_as !== 'masonry_grid') ? '' : 'data-uk-grid'; ?>>
            <?php
            // Prime caches to reduce future queries.
            if ( ! empty( $all_listings->ids ) && is_callable( '_prime_post_caches' ) ) {
                _prime_post_caches( $all_listings->ids );
            }

            $original_post = $GLOBALS['post'];

            if ( ! empty( $all_listings->ids ) ) :
                foreach ( $all_listings->ids as $listings_id ) :
                    $GLOBALS['post'] = get_post( $listings_id );
                    setup_postdata( $GLOBALS['post'] );
                    
                    $listing_id = get_the_ID();
                    $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                    $locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                    $featured = get_post_meta(get_the_ID(), '_featured', true);
                    $price = get_post_meta(get_the_ID(), '_price', true);
                    $price = '';
                    $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                    $atbd_listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                    $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                    $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                    $excerpt = get_post_meta(get_the_ID(), '_excerpt', true);
                    $tagline = get_post_meta(get_the_ID(), '_tagline', true);
                    $address = get_post_meta(get_the_ID(), '_address', true);
                    $email = get_post_meta(get_the_ID(), '_email', true);
                    $web = get_post_meta(get_the_ID(), '_website', true);
                    $phone_number = get_post_meta(get_the_Id(), '_phone', true);
                    $category = get_post_meta(get_the_Id(), '_admin_category_select', true);
                    $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
                    $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                    $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                    $display_title = get_directorist_option('display_title', 1);
                    $display_review = get_directorist_option('enable_review', 1);
                    $display_price = get_directorist_option('display_price', 1);
                    $display_email = get_directorist_option('display_email', 0);
                    $display_web_link = get_directorist_option('display_web_link', 0);
                    $display_category = get_directorist_option('display_category', 1);
                    $display_view_count = get_directorist_option('display_view_count', 1);
                    $display_mark_as_fav = get_directorist_option('display_mark_as_fav', 1);
                    $display_publish_date = get_directorist_option('display_publish_date', 1);
                    $display_contact_info = get_directorist_option('display_contact_info', 1);
                    $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
                    $display_popular_badge_cart = get_directorist_option('display_popular_badge_cart', 1);
                    $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
                    $feature_badge_text = apply_filters('atbdp_featured_badge_text', get_directorist_option('feature_badge_text', 'Featured'));
                    $enable_tagline = get_directorist_option('enable_tagline');
                    $enable_excerpt = get_directorist_option('enable_excerpt');
                    $address_location = get_directorist_option('address_location', 'location');
                    /*Code for Business Hour Extensions*/
                    $bdbh = get_post_meta(get_the_ID(), '_bdbh', true);
                    $enable247hour = get_post_meta(get_the_ID(), '_enable247hour', true);
                    $disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
                    $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                    $author_id = get_the_author_meta('ID');
                    $display_author_image = get_directorist_option('display_author_image', 1);
                    $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                    $u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
                    $avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
                    $display_tagline_field = get_directorist_option('display_tagline_field', 0);
                    $display_pricing_field = get_directorist_option('display_pricing_field', 1);
                    $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
                    $display_address_field = get_directorist_option('display_address_field', 1);
                    $display_phone_field = get_directorist_option('display_phone_field', 1);
                    $display_image = !empty($display_image) ? $display_image : '';
                    $listing_preview_img = empty(get_directorist_option('display_preview_image', 1)) || 'no' == $display_image ? 'no' : 'yes';
                    $prv_image = '';
                    if (!empty($listing_prv_img)) {
                        $prv_image_full = atbdp_get_image_source($listing_prv_img, 'full');
                    }
                    if (!empty($listing_img[0])) {
                        $gallery_img_full = atbdp_get_image_source($listing_img[0], 'full');
                    }
                    $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');

                    $listing_preview_img_class = 'no' == $listing_preview_img || (empty($prv_image) && empty($default_image) && empty($gallery_img)) ? ' listing_preview_img_none' : '';
                    /*Code for Business Hour Extensions*/
                    $atbdp_column = apply_filters('atbdp_column_class','atbdp_column');
                    ?>
                    <div class="<?php echo !empty($atbdp_column) ? $atbdp_column : 'atbdp_column'; ?>">
                        <div class="atbd_single_listing atbd_listing_card <?php echo get_directorist_option('info_display_in_single_line', 0) ? 'atbd_single_line_card_info' : '';
                        echo esc_html($listing_preview_img_class); ?>">
                            <article
                                    class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                <figure class="atbd_listing_thumbnail_area">
                                    <div class="atbd_listing_image">
                                        <?php
                                        $thumbnail_link_attr = " " . apply_filters( 'grid_view_thumbnail_link_add_attr', '' );
                                        $thumbnail_link_attr = trim( $thumbnail_link_attr );

                                        $title_link_attr = " " . apply_filters( 'grid_view_title_link_add_attr', '' );
                                        $title_link_attr = trim( $title_link_attr );

                                        if ('yes' == $listing_preview_img) {

                                        $disable_single_listing = get_directorist_option('disable_single_listing');
                                        if (empty($disable_single_listing)) {
                                            ?>
                                        <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>"<?php echo $thumbnail_link_attr;?>>
                                            <?php
                                            }
                                            the_thumbnail_card();

                                            if (empty($disable_single_listing)) {
                                                echo '</a>';
                                            }
                                            }

                                            if (!empty($display_author_image)) {
                                                $author = get_userdata($author_id);
                                                $class = !empty($author->first_name && $author->last_name) ? 'atbd_tooltip' : '';
                                                ?>
                                                <div class="atbd_author">
                                                    <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link($author_id); ?>"
                                                       aria-label="<?php echo $author->first_name . ' ' . $author->last_name; ?>"
                                                       class="<?php echo $class; ?>">
                                                        <?php if (empty($u_pro_pic)) {
                                                            echo $avatar_img;
                                                        }
                                                        if (!empty($u_pro_pic)) { ?>
                                                            <img src="<?php echo esc_url($u_pro_pic[0]); ?>"
                                                                 alt="Author Image"><?php } ?>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                    </div>

                                    <?php
                                    $plan_hours = true;
                                    $u_badge_html = '<span class="atbd_upper_badge bh_only">';
                                    if (is_fee_manager_active()) {
                                        $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
                                    }
                                    if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
                                        //lets check is it 24/7

                                        if ('2.2.6' > BDBH_VERSION) {
                                            ?>
                                            <style>
                                                .atbd_badge_close,
                                                .atbd_badge_open {
                                                    position: absolute;
                                                    left: 15px;
                                                    top: 15px;
                                                }
                                            </style>
                                            <?php
                                        }
                                        $open = get_directorist_option('open_badge_text', __('Open Now', 'directorist'));
                                        if (!empty($enable247hour)) {
                                            $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">' . $open . '</span>';
                                        } else {
                                            $bh_statement = BD_Business_Hour()->show_business_open_close($business_hours);

                                            $u_badge_html .= $bh_statement;
                                        }
                                    }
                                    $u_badge_html .= '</span>';

                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_upper_badges', $u_badge_html);


                                    //Start lower badge
                                    $l_badge_html = '<span class="atbd_lower_badge">';

                                    if ($featured && !empty($display_feature_badge_cart)) {
                                        $l_badge_html .= apply_filters( 'atbdp_featured_badge', '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>' );
                                    }

                                    $popular_listing_id = atbdp_popular_listings(get_the_ID());
                                    $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                                    if ($popular_listing_id === get_the_ID() && !empty($display_popular_badge_cart)) {
                                        $l_badge_html .= $badge;
                                    }
                                    //print the new badge
                                    $l_badge_html .= new_badge();
                                    $l_badge_html .= '</span>';

                                    /**
                                     * @since 6.4.4
                                     */
                                    do_action( 'atbdp_all_grid_listings_before_favourite_icon' );
                                    
                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_grid_lower_badges', $l_badge_html);
                                    if (!empty($display_mark_as_fav)) {
                                        echo atbdp_listings_mark_as_favourite($listing_id);
                                    }
                                    ?>
                                </figure>
                                <div class="atbd_listing_info">
                                    <?php if (!empty($display_title) || !empty($enable_tagline) || !empty($display_review) || !empty($display_price)) { ?>

                                        <div class="atbd_content_upper">
                                            <?php if (!empty($display_title)) { ?>
                                                <h4 class="atbd_listing_title">
                                                    <?php
                                                    if (empty($disable_single_listing)) {
                                                        echo '<a href="'. esc_url(get_post_permalink(get_the_ID())) .'"'. $title_link_attr .'>'. esc_html(stripslashes(get_the_title())) .'</a>';
                                                    } else {
                                                        echo esc_html(stripslashes(get_the_title()));
                                                    } ?>

                                                </h4>
                                            <?php }
                                            if (!empty($tagline) && !empty($enable_tagline) && !empty($display_tagline_field)) {
                                                ?>
                                                <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                            <?php }

                                            /**
                                             * Fires after the title and sub title of the listing is rendered
                                             *
                                             *
                                             * @since 1.0.0
                                             */

                                            do_action('atbdp_after_listing_tagline');
                                            ?>
                                            <?php
                                            $meta_html = '';
                                            if (!empty($display_review) || (!empty($display_price) && (!empty($price) || !empty($price_range)))) { ?>
                                                <?php
                                                $meta_html .= '<div class="atbd_listing_meta">';
                                                $average = ATBDP()->review->get_average(get_the_ID());
                                                if (!empty($display_review)) {
                                                    $meta_html .= '<span class="atbd_meta atbd_listing_rating">' . $average . '<i class="' . atbdp_icon_type() . '-star"></i></span>';
                                                }
                                                $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
                                                if (!empty($display_price) && !empty($display_pricing_field)) {
                                                    if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                                                        $output = atbdp_display_price_range($price_range);
                                                        $meta_html .= $output;
                                                    } else {
                                                        $meta_html .= apply_filters('atbdp_listing_card_price', atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false));
                                                    }
                                                }
                                                /**
                                                 * Fires after the price of the listing is rendered
                                                 *
                                                 *
                                                 * @since 3.1.0
                                                 */
                                                do_action('atbdp_after_listing_price');
                                                $meta_html .= '</div>';
                                            }
                                            /**
                                             * @since 5.0
                                             * universal action to fire after the price
                                             */
                                            echo apply_filters('atbdp_listings_review_price', $meta_html);
                                            ?>

                                            <?php if (!empty($display_contact_info || $display_publish_date || $display_email || $display_web_link)) { ?>
                                                <div class="atbd_listing_data_list">
                                                    <ul>
                                                        <?php
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_before_location');

                                                        if (!empty($display_contact_info)) {
                                                            if (!empty($address) && 'contact' == $address_location && !empty($display_address_field)) { ?>
                                                                <li>
                                                                    <p>
                                                                        <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span><?php echo esc_html(stripslashes($address)); ?>
                                                                    </p>
                                                                </li>
                                                            <?php } elseif (!empty($locs) && 'location' == $address_location) {
                                                                $local_names = array();
                                                                foreach ($locs as $term) {
                                                                    $local_names[$term->term_id] = $term->parent == 0 ? $term->slug : $term->slug;
                                                                    ksort($local_names);
                                                                    $locals = array_reverse($local_names);
                                                                }
                                                                $output = array();
                                                                $link = array();
                                                                foreach ($locals as $location) {
                                                                    $term = get_term_by('slug', $location, ATBDP_LOCATION);
                                                                    $link = ATBDP_Permalink::atbdp_get_location_page($term);
                                                                    $space = str_repeat(' ', 1);
                                                                    $output[] = "{$space}<a href='{$link}'>{$term->name}</a>";
                                                                }
                                                                ?>
                                                                <li>
                                                                    <p>
                                                                        <span>
                                                                            <?php
                                                                            echo "<span class='" . atbdp_icon_type() . "-map-marker'></span>" . join(',', $output); ?>
                                                                        </span>
                                                                    </p>
                                                                </li>
                                                            <?php }
                                                            /**
                                                             * @since 4.7.6
                                                             */
                                                            do_action('atbdp_listings_before_phone');
                                                            ?>
                                                            <?php if (!empty($phone_number) && !empty($display_phone_field)) { ?>
                                                                <li>
                                                                    <p>
                                                                        <span class="<?php atbdp_icon_type(true); ?>-phone"></span><a
                                                                                href="tel:<?php echo esc_html(stripslashes($phone_number)); ?>"><?php echo esc_html(stripslashes($phone_number)); ?></a>

                                                                    </p>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_before_post_date');

                                                        if (!empty($display_publish_date)) { ?>
                                                            <li>
                                                                <p>
                                                                    <span class="<?php atbdp_icon_type(true); ?>-clock-o"></span><?php
                                                                    $publish_date_format = get_directorist_option('publish_date_format', 'time_ago');
                                                                    if ('time_ago' === $publish_date_format) {
                                                                        printf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp')));
                                                                    } else {
                                                                        echo get_the_date();
                                                                    }
                                                                    ?></p>
                                                            </li>
                                                        <?php }
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_after_post_date');

                                                        if (!empty($email && $display_email)) :
                                                            echo '<li><p><span class="' . atbdp_icon_type() . '-envelope"></span><a target="_top" href="mailto:' . $email . '">' . $email . '</a></p></li>';
                                                        endif;
                                                        if (!empty($web && $display_web_link)) :
                                                            ?>
                                                            <li>
                                                                <p>
                                                                    <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                                                                    <a target="_blank"
                                                                       href="<?php echo esc_url($web); ?>" <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($web); ?></a>
                                                                </p>
                                                            </li>
                                                        <?php
                                                        endif;
                                                        ?>
                                                    </ul>
                                                </div><!-- End atbd listing meta -->
                                                <?php
                                            }
                                            if (!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) {
                                                $excerpt_limit = get_directorist_option('excerpt_limit', 20);
                                                $display_readmore = get_directorist_option('display_readmore', 0);
                                                $readmore_text = get_directorist_option('readmore_text', __('Read More', 'directorist'));
                                                ?>
                                                <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, $excerpt_limit)));
                                                /**
                                                 * @since 5.0.9
                                                 */
                                                do_action('atbdp_listings_after_exerpt');
                                                if (!empty($display_readmore)) {
                                                    ?><a
                                                    href="<?php the_permalink(); ?>"><?php printf(__(' %s', 'directorist'), $readmore_text); ?></a></p>
                                                <?php }
                                            } ?>
                                        </div><!-- end ./atbd_content_upper -->
                                    <?php }
                                    $catViewCount = '';
                                    if (!empty($display_category) || !empty($display_view_count)) {
                                        $catViewCount .= '<div class="atbd_listing_bottom_content">';
                                        if (!empty($display_category)) {
                                            if (!empty($cats)) {
                                                $totalTerm = count($cats);
                                                $catViewCount .= '<div class="atbd_content_left">';
                                                $catViewCount .= '<div class="atbd_listing_category">';
                                                $catViewCount .= '<a href="' . ATBDP_Permalink::atbdp_get_category_page($cats[0]) . '">';
                                                $catViewCount .= '<span class="' . atbdp_icon_type() . '-folder-open"></span>';
                                                $catViewCount .= $cats[0]->name;
                                                $catViewCount .= '</a>';
                                                if ($totalTerm > 1) {
                                                    $totalTerm = $totalTerm - 1;
                                                    $catViewCount .= '<div class="atbd_cat_popup">';
                                                    $catViewCount .= '<span>+' . $totalTerm . '</span>';
                                                    $catViewCount .= '<div class="atbd_cat_popup_wrapper">';
                                                    $output = array();
                                                    foreach (array_slice($cats, 1) as $cat) {
                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                        $space = str_repeat(' ', 1);
                                                        $output[] = "{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>";
                                                    }
                                                    $catViewCount .= '<span>' . join($output) . '</span>';
                                                    $catViewCount .= '</div>';
                                                    $catViewCount .= '</div>';
                                                }
                                                $catViewCount .= '</div>';
                                                $catViewCount .= '</div>';
                                            } else {
                                                $catViewCount .= '<div class="atbd_content_left">';
                                                $catViewCount .= '<div class="atbd_listing_category">';
                                                $catViewCount .= '<a href="">';
                                                $catViewCount .= '<span class="' . atbdp_icon_type() . '-folder-open"></span>';
                                                $catViewCount .= __('Uncategorized', 'directorist');
                                                $catViewCount .= '</a>';
                                                $catViewCount .= '</div>';
                                                $catViewCount .= '</div>';
                                            }
                                        }
                                        if (!empty($display_view_count)) {
                                            $fotter_right = '<ul class="atbd_content_right">';
                                            $fotter_right .= '<li class="atbd_count">';
                                            $fotter_right .= '<span class="' . atbdp_icon_type() . '-eye"></span>';
                                            $fotter_right .= !empty($post_view) ? $post_view : 0;
                                            $fotter_right .= '</li>';
                                            $fotter_right .= '</ul>';
                                            $catViewCount .= apply_filters('atbdp_grid_footer_right_html', $fotter_right);
                                        }

                                        $catViewCount .= '</div>'; //end ./atbd_listing_bottom_content

                                    }
                                    echo apply_filters('atbdp_listings_grid_cat_view_count', $catViewCount);

                                    /**
                                     * @param mixed $footer_html
                                     * @since
                                     * @package Directorist
                                     */
                                    //apply_filters('atbdp_listings_footer_content')
                                    ?>
                                </div>
                            </article>
                            <?php do_action('atbdp_after_single_listing_wrapper_class',$listing_id); ?>
                        </div>
                    </div>
                <?php
                endforeach;
                else: ?>
                    <p class="atbdp_nlf"><?php _e('No listing found.', 'directorist'); ?></p>
                <?php
            endif;

            $GLOBALS['post'] = $original_post;
		    wp_reset_postdata();
            ?>

        </div>
        <!--end row-->

        <div class="row">
            <div class="col-lg-12">
                <?php
                /**
                 * @since 5.0
                 */
                do_action('atbdp_before_listings_pagination');
                $show_pagination = !empty($show_pagination) ? $show_pagination : '';
                if ('yes' == $show_pagination) {
                    $paged = !empty($paged) ? $paged : '';
                    echo atbdp_pagination( $all_listings );
                } ?>
            </div>
        </div>

        <?php
        /**
         * @since 5.0
         * to add custom html
         * It only fires if the parameter [directorist_all_listing action_before_after_loop="yes"]
         */
        $action_before_after_loop = !empty($action_before_after_loop) ? $action_before_after_loop : '';
        if ('yes' === $action_before_after_loop) {
            do_action('atbdp_after_grid_listings_loop');
        }
        ?>
    </div>
</div>
<style>
    .atbd_content_active #directorist.atbd_wrapper .atbdp_column {
        width: <?php echo $column_width; ?>;
    }
</style>