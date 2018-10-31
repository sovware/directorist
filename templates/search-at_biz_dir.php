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
    <div class="directorist directory_wrapper single_area">
        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">

            <div class="header_bar">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_form_wrapper">
                                <div class="directory_title">
                                    <h3>
                                        <?php
                                        // show appropriate text for the search
                                        if (!empty($in_cat) || !empty($in_tag) || !empty($in_loc)){
                                            echo $in_s_string_text, $in_tag_text, $in_cat_text, $in_loc_text;
                                        }else{
                                            if (!empty($s_string)){
                                                printf(__('Search Result for: "%s" from All categories and locations', ATBDP_TEXTDOMAIN), $s_string);
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

                            $featured = get_post_meta($l_ID, '_featured', true);
                            $price = get_post_meta($l_ID, '_price', true);
                            /*@todo; As listings on search page and the all listing page, and user dashboard is nearly the same, so try to refactor them to a function later using some condition to show some extra fields on the listing on user dashboard*/
                            $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                            $excerpt = get_post_meta(get_the_ID(), '_excerpt', true);
                            $tagline = get_post_meta(get_the_ID(), '_tagline', true);
                            ?>

                            <div class="col-md-4 col-sm-6">
                                <div class="single_directory_post">
                                    <article class="<?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                        <figure>
                                            <div class="post_img_wrapper">
                                                <?= (!empty($listing_img[0])) ? '<img src="'.esc_url(wp_get_attachment_image_url($listing_img[0],  array(432,400))).'" alt="listing image">' : '' ?>
                                            </div>

                                            <figcaption>
                                                <p><?= !empty($excerpt) ? esc_html(stripslashes($excerpt)) : ''; ?></p>
                                            </figcaption>
                                        </figure>

                                        <div class="article_content">
                                            <div class="content_upper">
                                                <h4 class="post_title">
                                                    <a href="<?= esc_url(get_post_permalink($l_ID)); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                                    <?php
                                                    if ($featured){ printf(
                                                        ' <span class="directorist-ribbon featured-ribbon">%s</span>',
                                                        esc_html__('Featured', ATBDP_TEXTDOMAIN)
                                                    );}
                                                    ?>
                                                </h4>
                                                <p><?= (!empty($tagline)) ? esc_html(stripslashes($tagline)) : ''; ?></p>
                                                <?php
                                                atbdp_display_price($price, $is_disable_price);
                                                /**
                                                 * Fires after the price of the listing is rendered
                                                 *
                                                 *
                                                 * @since 3.1.0
                                                 */
                                                do_action('atbdp_after_listing_price');
                                                /**
                                                 * Fires after the title and sub title of the listing is rendered
                                                 *
                                                 *
                                                 * @since 1.0.0
                                                 */
                                                /*@todo; later refactor the hook name and now it is kept for backward compatibility to show ratings here in this action*/
                                                do_action('atbdp_after_listing_tagline');

                                                ?>

                                            </div>
                                            <?php
                                            //show category and location info
                                            ATBDP()->helper->output_listings_all_taxonomy_info($cats, $locs);
                                            // show read more link/btn
                                            ATBDP()->helper->listing_read_more_link();
                                            ?>
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
