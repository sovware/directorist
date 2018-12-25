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
                                                    printf( '<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg( 'view', $value ), $label );
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
                                                $options = atbdp_get_listings_orderby_options();

                                                foreach( $options as $value => $label ) {
                                                    $options = ( $value == $current_order ) ? ' active' : '';
                                                    printf( '<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg( 'sort', $value ), $label );
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
                        $atbd_listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                        $listing_img       = get_post_meta(get_the_ID(), '_listing_img', true);
                        $listing_prv_img   = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                        $excerpt           = get_post_meta(get_the_ID(), '_excerpt', true);
                        $tagline           = get_post_meta(get_the_ID(), '_tagline', true);
                        $address           = get_post_meta(get_the_ID(), '_address', true);
                        $phone_number      = get_post_meta(get_the_Id(), '_phone', true);
                        $category          = get_post_meta(get_the_Id(), '_admin_category_select', true);
                        $post_view         = get_post_meta(get_the_Id(),'_atbdp_post_views_count',true);
                        $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                        $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                        $display_title     = get_directorist_option('display_title',1);
                        $display_review     = get_directorist_option('display_review',1);
                        $display_price    = get_directorist_option('display_price',1);
                        $display_category    = get_directorist_option('display_category',1);
                        $display_view_count    = get_directorist_option('display_view_count',1);
                        $display_author_image    = get_directorist_option('display_author_image',1);
                        $display_publish_date    = get_directorist_option('display_publish_date',1);
                        /*Code for Business Hour Extensions*/
                        $bdbh                   = get_post_meta(get_the_ID(), '_bdbh', true);
                        $enable247hour               = get_post_meta(get_the_ID(), '_enable247hour', true);
                        $business_hours         = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                        $author_id = get_the_author_meta( 'ID' );
                        $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                        $avata_img = get_avatar($author_id, 32);
                        $thumbnail_cropping = get_directorist_option('thumbnail_cropping',1);
                        if(!empty($listing_prv_img)) {

                          if($thumbnail_cropping) {

                            $image_size = get_directorist_option('image_size','directory-image');
                            $prv_image   = wp_get_attachment_image_src($listing_prv_img, $image_size);

                            }else{
                              $prv_image   = wp_get_attachment_image_src($listing_prv_img, 'large');
                            }

                        }
                        if(!empty($listing_img[0])) {
                          if( $thumbnail_cropping ) {
                              $gallery_img_size = get_directorist_option('image_size','directory-image');
                              $gallery_img = wp_get_attachment_image_src($listing_img[0], $gallery_img_size);
                          }else{
                              $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium');
                          }

                        }

                        /*Code for Business Hour Extensions*/
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="atbd_single_listing atbd_listing_card">
                                <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure class="atbd_listing_thumbnail_area">
                                        <div class="atbd_listing_image">
                                            <?php if(!empty($listing_prv_img)){

                                                echo '<img src="'.esc_url($prv_image['0']).'" alt="listing image">';

                                            } if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                echo '<img src="' . esc_url($gallery_img['0']) . '" alt="listing image">';

                                            }if (empty($listing_img[0]) && empty($listing_prv_img)){

                                                echo '<img src="'.ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'.'" alt="listing image">';

                                            }
                                            ?>
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
                                                    $new = '<span class="atbd_badge atbd_badge_new">New</span>';
                                                if ($enable_new_listing){
                                                    switch ($is_day_or_days){
                                                        case ' day':
                                                            echo $new;
                                                            break;
                                                        case 'days':
                                                            //if it is more than 1 day let check the option value is grater than or equal
                                                            if (substr($is_old, 0, 1)<=$new_listing_day){
                                                                echo $new;
                                                            }
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

                                    <?php
                                    /*todo: Shahadat -> please implement the current markup*/?>
                                    <div class="atbd_listing_info">
                                        <div class="atbd_content_upper">
                                            <?php if(!empty($display_title)) {?>
                                            <h4 class="atbd_listing_title">
                                                <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                            </h4>
                                            <?php } if(!empty($tagline)) {?>
                                            <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                            <?php } ?>
                                            <?php if(!empty($display_review) && !empty($display_price)) {?>
                                            <div class="atbd_listing_meta">
                                                <?php
                                                /**
                                                 * Fires after the title and sub title of the listing is rendered
                                                 *
                                                 *
                                                 * @since 1.0.0
                                                 */
                                                if(!empty($display_review)) {
                                                do_action('atbdp_after_listing_tagline');
                                                }
                                                if(!empty($display_price)) {
                                                    if(!empty($price_range)) {
                                                     $output = atbdp_display_price_range($price_range);
                                                     echo $output;
                                                    }else{
                                                        atbdp_display_price($price, $is_disable_price);
                                                    }
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

                                            <?php } /* @todo: Shahadat -> please implement this */?>
                                            <?php if(!$disable_contact_info && !empty($display_publish_date)) {?>
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
                                                        } }

                                                      if(!empty($display_publish_date)) { ?>
                                                    <li><p><span class="fa fa-clock-o"></span><?php
                                                            printf( __( 'Posted %s ago', ATBDP_TEXTDOMAIN ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
                                                            ?></p></li>
                                                    <?php } ?>
                                                </ul>
                                            </div><!-- End atbd listing meta -->
                                            <?php
                                            }
                                            if( !empty($excerpt) ) {?>
                                            <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 30))); ?></p>
                                            <?php } ?>
                                        </div><!-- end ./atbd_content_upper -->

                                        <div class="atbd_listing_bottom_content">
                                            <?php
                                            if(!empty($display_category)) {
                                            if(!empty($cats) ) {?>
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

                                        <?php    } }

                                        ?>
                                            <ul class="atbd_content_right">
                                                <?php if(!empty($display_view_count)) {?>
                                                <li class="atbd_count"><span class="fa fa-eye"></span><?php echo !empty($post_view) ? $post_view : 0 ;?></li> <?php } ?>


                                                <li class="atbd_save">
                                                    <div id="atbdp-favourites-all-listing">
                                                        <input type="hidden" id="listing_ids" value="<?php echo get_the_ID(); ?>">
                                                        <?php
                                                       // do_action('wp_ajax_atbdp-favourites-all-listing', get_the_ID()); ?>
                                                    </div>
                                                </li>
                                                <?php if(!empty($display_author_image)) {?>
                                                <li class="atbd_author">
                                                    <a href="<?= ATBDP_Permalink::get_user_profile_page_link($author_id); ?>"><?php if (empty($u_pro_pic)) {echo $avata_img;} if (!empty($u_pro_pic)) { ?>
                                                            <img
                                                            src="<?php echo esc_url($u_pro_pic); ?>"
                                                            alt="Author Image"><?php } ?>
                                                    </a>
                                                </li>
                                                 <?php } ?>
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