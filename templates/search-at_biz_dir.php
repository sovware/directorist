<?php
global $wp_query;
!empty($args['data']) ? extract($args['data']) : array();
$listings = !empty($listings) ? $listings : array();
// Pagination fix
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $listings;
/*build appropriate search result text to display based on different situation*/
$in_s_string_text = !empty($s_string) ? sprintf(__('Search Result for: "%s"', ATBDP_TEXTDOMAIN), $s_string) : __('Search Result:', ATBDP_TEXTDOMAIN);
$in_tag_text = !empty($in_tag) ? sprintf(__(' from "%s" Tag', ATBDP_TEXTDOMAIN), $in_tag) : '';
$in_cat_text = !empty($in_cat) ? sprintf(__(' from "%s" Category', ATBDP_TEXTDOMAIN), $in_cat) : '';
$in_loc_text = !empty($in_loc) ? sprintf(__(' in "%s" Location', ATBDP_TEXTDOMAIN), $in_loc) : '';
$is_disable_price = get_directorist_option('disable_list_price');
?>
    <div id="directorist" class="directorist atbd_wrapper directory_wrapper search_area">
        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">

            <div class="header_bar">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="atbd_generic_header">
                                <div class="atbd_generic_header_title">
                                    <h3>
                                        <?php
                                        // show appropriate text for the search
                                        if (!empty($in_cat) || !empty($in_tag) || !empty($in_loc)){
                                            echo $in_s_string_text, $in_tag_text, $in_cat_text, $in_loc_text;
                                        }else{
                                            if (!empty($s_string)){
                                                printf(__('Search Result for: %s from All categories and locations', ATBDP_TEXTDOMAIN), '<span>'."'$s_string'".'</span>');
                                            }else{
                                                _e('Showing Result from all categories and locations.', ATBDP_TEXTDOMAIN);
                                            }
                                        }

                                        ?>
                                    </h3>
                                    <p>
                                        <?php
                                        _e('Total Listing Found: ', ATBDP_TEXTDOMAIN);
                                        if ($paginate){
                                            echo $listings->found_posts;
                                        }else{
                                            echo count($listings->posts);
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!--maybe we should removed the parent container so that it can match perfectly -->
                <div class="row" data-uk-grid>


                    <?php
                    if ( count($listings->posts) ) {
                        while ( $listings->have_posts() ) {
                            $listings->the_post();
                            $l_ID = get_the_ID(); // cache it, save several functions calls.
                            $cats =  get_the_terms($l_ID, ATBDP_CATEGORY);
                            $locs =  get_the_terms($l_ID, ATBDP_LOCATION);
                            $address              = get_post_meta(get_the_ID(), '_address', true);
                            $phone_number         = get_post_meta(get_the_Id(), '_phone', true);
                            $featured             = get_post_meta($l_ID, '_featured', true);
                            $price                = get_post_meta($l_ID, '_price', true);
                            $price_range          = get_post_meta($l_ID, '_price_range', true);

                            /*@todo; As listings on search page and the all listing page, and user dashboard is nearly the same, so try to refactor them to a function later using some condition to show some extra fields on the listing on user dashboard*/
                            $listing_img          = get_post_meta(get_the_ID(), '_listing_img', true);
                            $excerpt              = get_post_meta(get_the_ID(), '_excerpt', true);
                            $tagline              = get_post_meta(get_the_ID(), '_tagline', true);
                            $hide_contact_info    = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                            $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                            $category             = get_post_meta(get_the_Id(), '_admin_category_select', true);
                            /*Code for Business Hour Extensions*/
                            $bdbh                   = get_post_meta(get_the_ID(), '_bdbh', true);
                            $enable247hour               = get_post_meta(get_the_ID(), '_enable247hour', true);
                            $business_hours         = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                            $author_id = get_the_author_meta( 'ID' );
                            $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                            $avata_img = get_avatar($author_id, 32);
                            /*Code for Business Hour Extensions*/
                            ?>
                            <?php /*@todo shahadat - > updated search results page */?>
                            <div class="col-md-4 col-sm-6">
                                <div class="atbd_single_listing atbd_listing_card">
                                    <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                        <figure class="atbd_listing_thumbnail_area">
                                            <div class="atbd_listing_image">
                                                <?= (!empty($attachment_id[0])) ? '<img src="'.esc_url(wp_get_attachment_image_url($attachment_id[0],  array(432,400))).'" alt="listing image">' : '' ?>
                                            </div>

                                            <figcaption class="atbd_thumbnail_overlay_content">
                                                <?php if (class_exists('BD_Business_Hour')){
                                                    //lets check is it 24/7
                                                    if (!empty($enable247hour)) {
                                                        ?>
                                                        <div class="atbd_upper_badge">
                                                            <span class="atbd_badge atbd_badge_open">Open Now</span>
                                                        </div><!-- END /.atbd_upper_badge -->
                                                        <?php
                                                    }else {?>
                                                        <div class="atbd_upper_badge">
                                                            <?php BD_Business_Hour()->show_business_open_close($business_hours); // show the business hour in an unordered list ?>
                                                        </div>
                                                    <?php }
                                                }?>
                                                <div class="atbd_lower_badge">
                                                    <?php
                                                    if ($featured){ printf(
                                                        '<span class="atbd_badge atbd_badge_featured">Featured</span>',
                                                        esc_html__('Featured', ATBDP_TEXTDOMAIN)
                                                    );}
                                                    $count = !empty($count)?$count:5;
                                                    $popular_listings = ATBDP()->get_popular_listings($count = 5);

                                                    if ($popular_listings->have_posts()) {
                                                        foreach ($popular_listings->posts as $pop_post) {
                                                            if ($pop_post->ID == get_the_ID()){
                                                                echo ' <span class="atbd_badge atbd_badge_popular">Popular</span>';
                                                            }
                                                        }
                                                    }
                                                    $is_old = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
                                                    $enable_new_listing = get_directorist_option('enable_new_listing');
                                                    $new_listing_day = get_directorist_option('new_listing_day');
                                                    $is_day_or_days = substr($is_old, -4);
                                                    $is_other = substr($is_old, -5);
                                                    if (($is_old<=$new_listing_day) && ($enable_new_listing)){
                                                        $new = '<span class="atbd_badge atbd_badge_new">New</span>';
                                                        switch ($is_day_or_days){
                                                            case ' day':
                                                                echo $new;
                                                                break;
                                                            case 'days':
                                                                echo $new;
                                                                break;
                                                            case 'mins':
                                                                echo $new;
                                                                break;
                                                            case ' min':
                                                                echo $new;
                                                                break;
                                                            case 'hour':
                                                                echo $new;
                                                                break;
                                                        }
                                                        switch ($is_other){
                                                            case 'hours':
                                                                echo $new;
                                                                break;
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </figcaption>
                                        </figure>

                                        <?php /*todo: Shahadat -> please implement the current markup*/?>
                                        <div class="atbd_listing_info">
                                            <div class="atbd_content_upper">
                                                <h4 class="atbd_listing_title">
                                                    <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                                </h4>
                                                <p class="atbd_listing_tagline"><?= (!empty($tagline)) ? esc_html(stripslashes($tagline)) : ''; ?></p>
                                                <?php /* todo: Shahadat -> new markup implemented */?>
                                                <div class="atbd_listing_meta">
                                                    <?php
                                                    /**
                                                     * Fires after the title and sub title of the listing is rendered
                                                     *
                                                     *
                                                     * @since 1.0.0
                                                     */

                                                    do_action('atbdp_after_listing_tagline');
                                                    if(!empty($price_range)) {
                                                        $output = atbdp_display_price_range($price_range);
                                                        echo $output;
                                                    }else{
                                                        atbdp_display_price($price, $is_disable_price);
                                                    }

                                                    /**
                                                     * Fires after the price of the listing is rendered
                                                     *
                                                     *
                                                     * @since 3.1.0
                                                     */
                                                    do_action('atbdp_after_listing_price');
                                                    ?>
                                                </div><!-- End atbd listing meta -->

                                                <?php /* @todo: Shahadat -> please implement this */?>
                                                <div class="atbd_listing_data_list">
                                                    <ul>
                                                        <?php

                                                            if( !empty( $address )) { ?>
                                                                <li><p><span class="fa fa-location-arrow"></span><?php echo esc_html(stripslashes($address));?></p></li>
                                                            <?php } ?>
                                                            <?php if( !empty( $phone_number )) {?>
                                                                <li><p><span class="fa fa-phone"></span><?php echo esc_html(stripslashes($phone_number));?></p></li>
                                                                <?php
                                                            } ?>
                                                        <li><p><span class="fa fa-clock-o"></span><?php
                                                                printf( __( 'Posted %s ago', ATBDP_TEXTDOMAIN ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
                                                                ?></p></li>
                                                    </ul>
                                                </div><!-- End atbd listing meta -->
                                                <?php
                                                //show category and location info
                                                /* @todo: Shahadat -> Please fetch location, phone number and listing addition info here */
                                                /*ATBDP()->helper->output_listings_taxonomy_info($top_category, $deepest_location);*/?>
                                                <?php if(!empty($excerpt))  {?>
                                                <p><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 30))); ?></p>
                                                <?php } ?>
                                                <?php /* @todo: deleted the read more link */ ?>
                                            </div><!-- end ./atbd_content_upper -->

                                            <div class="atbd_listing_bottom_content">
                                                <?php if(!empty($category)) {?>
                                                    <div class="atbd_content_left">
                                                        <div class="atbd_listting_category">
                                                            <a href="<?php echo esc_url(ATBDP_Permalink::get_category_archive($cats[0]));;?>"><span class="fa <?php echo esc_attr(get_cat_icon($cats[0]->term_id)); ?>"></span><?php  echo $cats[0]->name;?></a>
                                                        </div>
                                                    </div>
                                                <?php }else{
                                                    ?>
                                                    <div class="atbd_content_left">
                                                        <div class="atbd_listting_category">
                                                            <a href=""><span class="fa fa fa-square-o"></span><?php  echo __('Uncategorized', ATBDP_TEXTDOMAIN);?></a>
                                                        </div>
                                                    </div>

                                                <?php    } ?>
                                                <ul class="atbd_content_right">
                                                    <li class="atbd_count"><span class="fa fa-eye"></span><?php echo !empty($post_view) ? $post_view : 0 ;?></li>
                                                    <li class="atbd_save"><span class="fa fa-heart"></span></li>
                                                    <li class="atbd_author">
                                                        <a href="<?= ATBDP_Permalink::get_user_profile_page_link($author_id); ?>"><?php if (empty($u_pro_pic)) {echo $avata_img;} if (!empty($u_pro_pic)) { ?>
                                                                <img
                                                                src="<?php echo esc_url($u_pro_pic); ?>"
                                                                alt="Author Image"><?php } ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div><!-- end ./atbd_listing_bottom_content -->
                                        </div>
                                    </article>
                                </div>
                            </div>
                        <?php }

                        } else {?>
                                <p><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
                    <?php } ?>



                </div> <!--ends .row -->

                <div class="row">
                    <div class="col-md-12">
                        <?php
                        the_posts_pagination(
                                array('mid_size'  => 2,
                                'prev_text' => '<span class="fa fa-chevron-left"></span>',
                                'next_text' => '<span class="fa fa-chevron-right"></span>',
                            ));
                        //atbdp_pagination($listings, $paged);
                        wp_reset_postdata();
                        $wp_query   = NULL;
                        $wp_query   = $temp_query;
                        ?>
                    </div>
                </div>
        </div>
    </div>
<?php
?>
<?php include __DIR__.'/style.php'; ?>
