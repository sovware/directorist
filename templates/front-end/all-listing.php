<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$all_listing_title = !empty($all_listing_title) ? $all_listing_title : __('All Items', ATBDP_TEXTDOMAIN);
$is_disable_price = get_directorist_option('disable_list_price');

?>


    <div id="directorist" class="atbd_wrapper">
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
                    while ( $all_listings->have_posts() ) { $all_listings->the_post();
                        $cats       =  get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                        $locs       =  get_the_terms(get_the_ID(), ATBDP_LOCATION);
                        $featured   = get_post_meta(get_the_ID(), '_featured', true);
                        $price      = get_post_meta(get_the_ID(), '_price', true);
                        $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                        $excerpt    = get_post_meta(get_the_ID(), '_excerpt', true);
                        $tagline    = get_post_meta(get_the_ID(), '_tagline', true);

                        ?>

                        <div class="col-md-4 col-sm-6">
                            <div class="atbd_single_listing atbd_listing_card">
                                <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure class="atbd_listing_thumbnail_area">
                                        <div class="atbd_listing_image">
                                            <?= (!empty($attachment_id[0])) ? '<img src="'.esc_url(wp_get_attachment_image_url($attachment_id[0],  array(432,400))).'" alt="listing image">' : '' ?>
                                        </div>

                                        <figcaption class="atbd_thumbnail_overlay_content">
                                            <?php /*todo: Shahadat -> It needs dynamization */?>
                                            <div class="atbd_upper_badge">
                                                <span class="atbd_badge atbd_badge_open">Open Now</span>
                                            </div><!-- END /.atbd_upper_badge -->

                                            <div class="atbd_lower_badge">
                                                <?php
                                                    if ($featured){ printf(
                                                        '<span class="atbd_badge atbd_badge_featured">Featured</span>',
                                                        esc_html__('Featured', ATBDP_TEXTDOMAIN)
                                                    );}
                                                ?>
                                                <?php /*todo: Shahadat -> It needs dynamization */?>
                                                <span class="atbd_badge atbd_badge_popular">Popular</span>
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
                                                /*@todo: Shahadat -> added new markup, Average pricing */?>
                                                <span class="atbd_meta atbd_listing_average_pricing">
                                                    <span class="atbd_active">$</span>
                                                    <span class="atbd_active">$</span>
                                                    <span>$</span>
                                                    <span>$</span>
                                                </span>
                                                <?php
                                                atbdp_display_price($price, $is_disable_price);

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
                                                    <li><p><span class="fa fa-location-arrow"></span>House -24C, Road -113A, Gulshan -2, Dhaka</p></li>
                                                    <li><p><span class="fa fa-phone"></span>(415) 796-3633</p></li>
                                                    <li><p><span class="fa fa-clock-o"></span>Posted 2 months ago</p></li>
                                                </ul>
                                            </div><!-- End atbd listing meta -->
                                            <?php
                                            //show category and location info
                                            /* @todo: Shahadat -> Please fetch location, phone number and listing addition info here */
                                            /*ATBDP()->helper->output_listings_taxonomy_info($top_category, $deepest_location);*/?>
                                            <p><?= !empty($excerpt) ? esc_html(stripslashes($excerpt)) : ''; ?></p>

                                            <?php /* @todo: deleted the read more link */ ?>
                                        </div><!-- end ./atbd_content_upper -->

                                        <div class="atbd_listing_bottom_content">
                                            <div class="atbd_content_left">
                                                <div class="atbd_listting_category">
                                                    <a href="#"><span class="fa fa-glass"></span>Restaurant</a>
                                                </div>
                                            </div>

                                            <ul class="atbd_content_right">
                                                <li class="atbd_count"><span class="fa fa-eye"></span>900+</li>
                                                <li class="atbd_save"><span class="fa fa-heart"></span></li>
                                                <li class="atbd_author"><a href="#"><img src="<?php echo ATBDP_PUBLIC_ASSETS.'images/avtr.png'?>" alt=""></a></li>
                                            </ul>
                                        </div><!-- end ./atbd_listing_bottom_content -->
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

        <?php /* @todo: Shahadat -> atbd listing list style */?>
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

                        <div class="col-md-8">
                            <div class="atbd_single_listing atbd_listing_list">
                                <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure class="atbd_listing_thumbnail_area">
                                        <?= (!empty($attachment_id[0])) ? '<img src="'.esc_url(wp_get_attachment_image_url($attachment_id[0],  array(432,400))).'" alt="listing image">' : '' ?>


                                        <figcaption class="atbd_thumbnail_overlay_content">
                                            <?php /*todo: Shahadat -> It needs dynamization */?>

                                            <div class="atbd_lower_badge">
                                                <?php
                                                if ($featured){ printf(
                                                    '<span class="atbd_badge atbd_badge_featured">Featured</span>',
                                                    esc_html__('Featured', ATBDP_TEXTDOMAIN)
                                                );}
                                                ?>
                                                <?php /*todo: Shahadat -> It needs dynamization */?>
                                                <span class="atbd_badge atbd_badge_popular">Popular</span>
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
                                                /*@todo: Shahadat -> added new markup, Average pricing */?>
                                                <span class="atbd_meta atbd_listing_average_pricing">
                                                    <span class="atbd_active">$</span>
                                                    <span class="atbd_active">$</span>
                                                    <span>$</span>
                                                    <span>$</span>
                                                </span>
                                                <?php
                                                atbdp_display_price($price, $is_disable_price);

                                                /**
                                                 * Fires after the price of the listing is rendered
                                                 *
                                                 *
                                                 * @since 3.1.0
                                                 */
                                                do_action('atbdp_after_listing_price');
                                                ?>

                                                <?php /*@todo: Shahadat -> open close moved from thumbnail area to content area*/?>
                                                <span class="atbd_meta atbd_close_now">Close Now</span>
                                            </div><!-- End atbd listing meta -->

                                            <?php /* @todo: Shahadat -> please implement this */?>
                                            <div class="atbd_listing_data_list">
                                                <ul>
                                                    <li><p><span class="fa fa-location-arrow"></span>House -24C, Road -113A, Gulshan -2, Dhaka</p></li>
                                                    <li><p><span class="fa fa-phone"></span>(415) 796-3633</p></li>
                                                    <li><p><span class="fa fa-clock-o"></span>Posted 2 months ago</p></li>
                                                </ul>
                                            </div><!-- End atbd listing meta -->
                                            <?php
                                            //show category and location info
                                            /* @todo: Shahadat -> Please fetch location, phone number and listing addition info here */
                                            /*ATBDP()->helper->output_listings_taxonomy_info($top_category, $deepest_location);*/?>
                                            <p><?= !empty($excerpt) ? esc_html(stripslashes($excerpt)) : ''; ?></p>

                                            <?php /* @todo: deleted the read more link */ ?>
                                        </div><!-- end ./atbd_content_upper -->

                                        <div class="atbd_listing_bottom_content">
                                            <div class="atbd_content_left">
                                                <div class="atbd_listting_category">
                                                    <a href="#"><span class="fa fa-glass"></span>Restaurant</a>
                                                </div>
                                            </div>

                                            <ul class="atbd_content_right">
                                                <li class="atbd_count"><span class="fa fa-eye"></span>900+</li>
                                                <li class="atbd_save"><span class="fa fa-heart"></span></li>
                                                <li class="atbd_author"><a href="#"><img src="<?php echo ATBDP_PUBLIC_ASSETS.'images/avtr.png'?>" alt=""></a></li>
                                            </ul>
                                        </div><!-- end ./atbd_listing_bottom_content -->
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