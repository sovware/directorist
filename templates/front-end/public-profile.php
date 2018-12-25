<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
?>
<div id="directorist" class="atbd_wrapper atbd_author_profile">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                $author_id = !empty($author_id) ? $author_id : get_current_user_id();
                $author_id = rtrim($author_id, '/');
                $author_name = get_the_author_meta('display_name', $author_id);
                $user_registered = get_the_author_meta('user_registered', $author_id);
                $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                $u_pro_pic = wp_get_attachment_image_src($u_pro_pic, 'thumbnail');
                $bio = get_user_meta($author_id, 'bio', true);
                $avata_img = get_avatar($author_id, 32);
                $address = esc_attr(get_user_meta($author_id, 'address', true));
                $phone = esc_attr(get_user_meta($author_id, 'phone', true));
                $email = get_the_author_meta('user_email', $author_id);
                $website = get_the_author_meta('user_url', $author_id);;
                $facebook = get_user_meta($author_id, 'facebook', true);
                $twitter = get_user_meta($author_id, 'twitter', true);
                $google = get_user_meta($author_id, 'google', true);
                $linkedIn = get_user_meta($author_id, 'linkedIn', true);
                $youtube = get_user_meta($author_id, 'youtube', true);
                $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
                ?>
                <div class="atbd_auhor_profile_area">
                    <div class="atbd_author_avatar">
                        <?php if (empty($u_pro_pic)) {
                            echo $avata_img;
                        }
                        if (!empty($u_pro_pic)) { ?><img
                            src="<?php echo esc_url($u_pro_pic[0]); ?>"
                            alt="Author Image" ><?php } ?>
                        <div class="atbd_auth_nd">
                            <h2><?= esc_html($author_name); ?></h2>
                            <p><?php
                                printf(__('Member since %s ago', ATBDP_TEXTDOMAIN), human_time_diff(strtotime($user_registered), current_time('timestamp'))); ?></p>
                        </div>
                    </div>

                    <div class="atbd_author_meta">
                        <?php
                        $args = array(
                            'post_type' => ATBDP_POST_TYPE,
                            'post_status' => 'publish',
                            'author'        =>  $author_id,
                            'orderby'       =>  'post_date',
                            'order'         =>  'ASC',
                            'posts_per_page' => -1 // no limit
                        );
                        $current_user_posts = get_posts( $args );
                        $total_listing = count($current_user_posts);
                        $review_in_post = 0;
                        $all_reviews =0;
                        foreach ($current_user_posts as $post){
                            $average = ATBDP()->review->get_average($post->ID);
                            if (!empty($average)){
                                $averagee = array($average);
                                foreach ($averagee as $key){
                                    $all_reviews+= $key;
                                }
                                $review_in_post++;
                            }
                        }
                        $author_rating = (!empty($all_reviews) && !empty($review_in_post)) ? ($all_reviews / $review_in_post) : 0;
                        $author_rating = substr($author_rating, '0', '3');
                        ?>
                        <div class="atbd_listing_meta">
                            <span class="atbd_meta atbd_listing_rating">
                                <?php echo $author_rating;?><i class="fa fa-star"></i>
                            </span>
                        </div>
                        <p class="meta-info">
                            <span><?php echo !empty($review_in_post)?$review_in_post:'0'?></span>
                            <?php echo ($review_in_post>1)?'Reviews':'Review'?>
                        </p>
                        <p class="meta-info">
                            <span><?php echo !empty($total_listing)?$total_listing:'0'?></span>
                            <?php echo ($total_listing>1)?'Listings':'Listing'?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="atbd_author_module">
                    <div class="atbd_content_module">
                        <div class="atbd_content_module__tittle_area">
                            <div class="atbd_area_title">
                                <h4><span class="fa fa-user"></span>About Seller</h4>
                            </div>
                        </div>

                        <div class="atbdb_content_module_contents">
                            <p>
                                <?php !empty($bio)?_e(esc_html($bio), ATBDP_TEXTDOMAIN):_e('Nothing to show!', ATBDP_TEXTDOMAIN); ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget atbd_widget">
                    <div class="atbd_widget_title"><h4>Contact Info</h4></div>
                    <div class="atbdp atbd_author_info_widget">
                        <div class="atbd_widget_contact_info">
                            <ul>
                                <?php
                                if (!empty($address)){
                                  ?>
                                    <li>
                                        <span class="fa fa-map-marker"></span>
                                        <span class="atbd_info"><?= !empty($address)?esc_html($address):''; ?></span>
                                    </li>
                                <?php
                                }
                                if (!empty($phone)){
                                    ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <span class="fa fa-phone"></span>
                                        <span class="atbd_info"><?= !empty($phone)?esc_html($phone):''; ?></span>
                                    </li>
                                <?php
                                }
                                if (!empty($email)){
                                    ?>
                                    <li>
                                        <span class="fa fa-envelope"></span>
                                        <span class="atbd_info"><?= !empty($email)?esc_html($email):''; ?></span>
                                    </li>
                                <?php
                                }
                                if (!empty($website)){
                                    ?>
                                    <li>
                                        <span class="fa fa-globe"></span>
                                        <span class="atbd_info"><?= !empty($website)?esc_html($website):''; ?></span>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                        if (!empty($facebook || $twitter || $google || $linkedIn || $youtube)){
                            ?>
                            <div class="atbd_social_wrap">
                                <?php
                                if ($facebook) {
                                    printf('<p><a target="_blank" href="%s"><span class="fa fa-facebook"></span></a></p>', $facebook);
                                }
                                if ($twitter) {
                                    printf('<p><a target="_blank" href="%s"><span class="fa fa-twitter"></span></a></p>', $twitter);
                                }
                                if ($google) {
                                    printf('<p><a target="_blank" href="%s"><span class="fa fa-google-plus"></span></a></p>', $google);
                                }
                                if ($linkedIn) {
                                    printf('<p><a target="_blank" href="%s"><span class="fa fa-linkedin"></span></a></p>', $linkedIn);
                                }
                                if ($youtube) {
                                    printf('<p><a target="_blank" href="%s"><span class="fa fa-youtube"></span></a></p>', $youtube);
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                        <a href="<?= esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                           class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('+ Become an Author', ATBDP_TEXTDOMAIN); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="atbd_author_listings_area">
                    <h1><?php _e( "Author Listings", ATBDP_TEXTDOMAIN ); ?></h1>
                    <div class="atbd_author_filter_area">
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php _e( "Filter by category", ATBDP_TEXTDOMAIN ); ?> <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <?php
                                foreach( $categories as $category ) {
                                    printf( '<a class="dropdown-item" href="%s">%s</a>', add_query_arg( 'category', $category->slug ), $category->name );
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="atbd_authors_listing">
            <div class="row" data-uk-grid>


                <?php if ( $all_listings->have_posts() ) {
                    while ( $all_listings->have_posts() ) { $all_listings->the_post();
                        $cats              =  get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                        $locs              =  get_the_terms(get_the_ID(), ATBDP_LOCATION);
                        $featured          = get_post_meta(get_the_ID(), '_featured', true);
                        $price             = get_post_meta(get_the_ID(), '_price', true);
                        $price_range       = get_post_meta(get_the_ID(), '_price_range', true);
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
                        $is_disable_price = get_directorist_option('disable_list_price');
                        /*Code for Business Hour Extensions*/
                        $bdbh                   = get_post_meta(get_the_ID(), '_bdbh', true);
                        $enable247hour               = get_post_meta(get_the_ID(), '_enable247hour', true);
                        $business_hours         = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                        /*Code for Business Hour Extensions*/
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
                        ?>
                        <div class="col-md-4 col-sm-6">
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

                                    <?php /*todo: Shahadat -> please implement the current markup*/?>
                                    <div class="atbd_listing_info">
                                        <div class="atbd_content_upper">
                                            <h4 class="atbd_listing_title">
                                                <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                            </h4>
                                            <?php if(!empty($tagline)) {?>
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
                                                    if (empty($price) && !empty($price_range)) {
                                                        atbdp_display_price_range($price_range);
                                                    }
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
                                            <?php } ?>
                                            <?php /* @todo: Shahadat -> please implement this */?>
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
                                            } if( !empty($excerpt) ) {?>
                                                <p><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 30))); ?></p>
                                            <?php } ?>
                                        </div><!-- end ./atbd_content_upper -->

                                        <div class="atbd_listing_bottom_content">
                                            <?php if(!empty($cats)) {?>
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
                                            <!--    <li class="atbd_save"><span class="fa fa-heart"></span></li>-->
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
</div>