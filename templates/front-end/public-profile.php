<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$pagenation = get_directorist_option('paginate_author_listings',1);
$is_disable_price = get_directorist_option('disable_list_price');
$container_fluid  = 'container-fluid';
?>
<div id="directorist" class="atbd_wrapper atbd_author_profile">
    <div class="<?php echo apply_filters('atbdp_public_profile_container_fluid',$container_fluid) ?>">
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
                                <h4><span class="fa fa-user"></span><?php _e('About', ATBDP_TEXTDOMAIN); ?></h4>
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
                    <div class="atbd_widget_title"><h4><?php _e('Contact Info', ATBDP_TEXTDOMAIN); ?></h4></div>
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
                                $email_show = get_directorist_option('display_author_email', 'public');
                                if ('public' === $email_show){
                                    if (!empty($email)){
                                        ?>
                                        <li>
                                            <span class="fa fa-envelope"></span>
                                            <span class="atbd_info"><?= !empty($email)?esc_html($email):''; ?></span>
                                        </li>
                                        <?php
                                    }
                                }elseif ('logged_in' === $email_show){
                                    if (is_user_logged_in()){
                                        if (!empty($email)){
                                            ?>
                                            <li>
                                                <span class="fa fa-envelope"></span>
                                                <span class="atbd_info"><?= !empty($email)?esc_html($email):''; ?></span>
                                            </li>
                                            <?php
                                        }
                                    }
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
                        if (!empty($facebook || $twitter || $linkedIn || $youtube)){
                            ?>
                            <div class="atbd_social_wrap">
                                <?php
                                if ($facebook) {
                                    printf('<p><a target="_blank" href="%s"><span class="fa fa-facebook"></span></a></p>', $facebook);
                                }
                                if ($twitter) {
                                    printf('<p><a target="_blank" href="%s"><span class="fa fa-twitter"></span></a></p>', $twitter);
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
                            <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        <div class="row atbd_authors_listing">

                <?php
                listing_view_by_grid($all_listings, $pagenation, $is_disable_price);
                ?>

        </div>
    </div>
</div>