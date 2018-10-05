<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$all_listing_title = !empty($all_listing_title) ? $all_listing_title : __('All Items', ATBDP_TEXTDOMAIN);
$is_disable_price = get_directorist_option('disable_list_price');

?>


    <div class="directorist directory_wrapper single_area">
        <div class="header_bar">
            <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="header_form_wrapper">
                            <div class="directory_title">
                                <h3>
                                    <?php echo esc_html($all_listing_title); ?>
                                </h3>
                                <?php
                                    _e('Total Listing Found: ', ATBDP_TEXTDOMAIN);
                                    if ($paginate){
                                        echo $all_listings->found_posts;
                                    }else{
                                        echo count($all_listings->posts);
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
            <div class="row" data-uk-grid>


                <?php if ( $all_listings->have_posts() ) {
                    while ( $all_listings->have_posts() ) { $all_listings->the_post(); ?>
                        <?php
                        $info = ATBDP()->metabox->get_listing_info(get_the_ID()); // get all post meta and extract it.
                        extract($info);
                        // get only one parent or high level term object
                        $top_category = ATBDP()->taxonomy->get_one_high_level_term(get_the_ID(), ATBDP_CATEGORY);
                        $deepest_location = ATBDP()->taxonomy->get_one_deepest_level_term(get_the_ID(), ATBDP_LOCATION);
                        $featured = get_post_meta(get_the_ID(), '_featured', true);
                        $price = get_post_meta(get_the_ID(), '_price', true);

                        ?>

                        <div class="col-md-4 col-sm-6">
                            <div class="single_directory_post">
                                <article class="<?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure>
                                        <div class="post_img_wrapper">
                                            <?= (!empty($attachment_id[0])) ? '<img src="'.esc_url(wp_get_attachment_image_url($attachment_id[0],  array(432,400))).'" alt="listing image">' : '' ?>
                                        </div>

                                        <figcaption>
                                            <p><?= !empty($excerpt) ? esc_html(stripslashes($excerpt)) : ''; ?></p>
                                        </figcaption>
                                    </figure>

                                    <div class="article_content">
                                        <div class="content_upper">
                                            <h4 class="post_title">
                                                <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
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

                                            do_action('atbdp_after_listing_tagline');


                                            ?>
                                          
                                        </div>
                                        <?php
                                        //show category and location info
                                        ATBDP()->helper->output_listings_taxonomy_info($top_category, $deepest_location);
                                        // show read more link/btn
                                        ATBDP()->helper->listing_read_more_link();
                                        ?>


                                    </div>
                                </article>
                            </div>
                        </div>

                    <?php }
                    wp_reset_postdata();
                    } else {?>
                            <p><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
                <?php } ?>



            </div> <!--ends .row -->

            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
?>
<?php //get_footer(); ?>