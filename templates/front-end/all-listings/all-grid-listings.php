<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$all_listing_title = !empty($all_listing_title) ? $all_listing_title : __('All Items', ATBDP_TEXTDOMAIN);
$is_disable_price = get_directorist_option('disable_list_price');
$display_sortby_dropdown = get_directorist_option('display_sort_by',1);
$display_viewas_dropdown = get_directorist_option('display_view_as',1);
?>


    <div id="directorist" class="atbd_wrapper">
        <div class="header_bar">
            <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="atbd_generic_header">
                            <div class="atbd_generic_header_title">
                                <h3>
                                    <?php echo esc_html($all_listing_title); ?>
                                </h3>
                                <p>
                                    <?php
                                    _e('Total Listing Found: ', ATBDP_TEXTDOMAIN);
                                    if ($paginate){
                                        echo $all_listings->found_posts;
                                    }else{
                                        echo count($all_listings->posts);
                                    }
                                    ?>
                                </p>
                            </div>
                            <?php if($display_viewas_dropdown || $display_sortby_dropdown) {?>
                                <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                    <!-- Views dropdown -->
                                    <?php if($display_viewas_dropdown) { ?>
                                        <div class="dropdown">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php _e( "View as", ATBDP_TEXTDOMAIN ); ?> <span class="caret"></span>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <?php
                                                $views = atbdp_get_listings_view_options();

                                                foreach( $views as $value => $label ) {
                                                    $active_class = ( $view == $value ) ? ' active' : '';
                                                    printf( '<li class="dropdown-item%s"><a href="%s">%s</a></li>', $active_class, add_query_arg( 'view', $value ), $label );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- Orderby dropdown -->
                                    <?php
                                    if($display_sortby_dropdown) {
                                        ?>
                                        <div class="dropdown">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php _e( "Sort by", ATBDP_TEXTDOMAIN ); ?> <span class="caret"></span>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                <?php
                                                $views = atbdp_get_listings_view_options();

                                                foreach( $views as $value => $label ) {
                                                    $active_class = ( $view == $value ) ? ' active' : '';
                                                    printf( '<li class="dropdown-item%s"><a href="%s">%s</a></li>', $active_class, add_query_arg( 'view', $value ), $label );
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
            <div class="row" data-uk-grid>


                <?php if ( $all_listings->have_posts() ) {
                    while ( $all_listings->have_posts() ) { $all_listings->the_post();
                        $cats              =  get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                        $locs              =  get_the_terms(get_the_ID(), ATBDP_LOCATION);
                        $featured          = get_post_meta(get_the_ID(), '_featured', true);
                        $price             = get_post_meta(get_the_ID(), '_price', true);
                        $price_range       = get_post_meta(get_the_ID(), '_price_range', true);
                        $listing_img       = get_post_meta(get_the_ID(), '_listing_img', true);
                        $excerpt           = get_post_meta(get_the_ID(), '_excerpt', true);
                        $tagline           = get_post_meta(get_the_ID(), '_tagline', true);
                        $address           = get_post_meta(get_the_ID(), '_address', true);
                        $phone_number      = get_post_meta(get_the_Id(), '_phone', true);
                        $category          = get_post_meta(get_the_Id(), '_admin_category_select', true);
                        $post_view         = get_post_meta(get_the_Id(),'_atbdp_post_views_count',true);
                        $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                        $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                        /*Code for Business Hour Extensions*/
                        $bdbh                   = get_post_meta(get_the_ID(), '_bdbh', true);
                        $enable247hour               = get_post_meta(get_the_ID(), '_enable247hour', true);
                        $business_hours         = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                        /*Code for Business Hour Extensions*/
                        ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="atbd_single_listing atbd_listing_card">
                                <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure class="atbd_listing_thumbnail_area">
                                        <div class="atbd_listing_image">
                                            <?= (!empty($listing_img[0])) ? '<img src="'.esc_url(wp_get_attachment_image_url($listing_img[0],  array(432,400))).'" alt="listing image">' : '<img src="'.ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'.'" alt="listing image">' ?>
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
                                                $is_weeks = substr($is_old, -5);
                                                if ('weeks' != $is_weeks){
                                                    if (($is_old<=$new_listing_day) && ($enable_new_listing)){
                                                        echo '<span class="atbd_badge atbd_badge_new">New</span>';
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
                                            <?php if(!empty($tagline)) {?>
                                            <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                            <?php } ?>
                                            <div class="atbd_listing_meta">
                                                <?php
                                                /**
                                                 * Fires after the title and sub title of the listing is rendered
                                                 *
                                                 *
                                                 * @since 1.0.0
                                                 */

                                                do_action('atbdp_after_listing_tagline');

                                                if(empty($price) && !empty($price_range)) {
                                                 atbdp_display_price_range($price_range);
                                                }

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
                                                    <?php
                                                    if (!$disable_contact_info && !$hide_contact_info) {
                                                        if( !empty( $address )) { ?>
                                                        <li><p><span class="fa fa-location-arrow"></span><?php echo esc_html(stripslashes($address));?></p></li>
                                                        <?php } ?>
                                                        <?php if( !empty( $phone_number )) {?>
                                                        <li><p><span class="fa fa-phone"></span><?php echo esc_html(stripslashes($phone_number));?></p></li>
                                                    <?php
                                                        } }?>
                                                    <li><p><span class="fa fa-clock-o"></span><?php
                                                            printf( __( 'Posted %s ago', ATBDP_TEXTDOMAIN ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
                                                            ?></p></li>
                                                </ul>
                                            </div><!-- End atbd listing meta -->
                                            <?php if( !empty($excerpt) ) {?>
                                            <p><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 30))); ?></p>
                                            <?php } ?>
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
                                                <li class="atbd_author"><a href=""><?php echo get_avatar( get_the_author_meta( 'ID' ) , 32 ); ?></a></li>
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