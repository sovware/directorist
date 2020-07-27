<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$paginate = !empty($paginate) ? $paginate : '';
$is_disable_price = get_directorist_option('disable_list_price');
$container_fluid = 'container-fluid';
?>
<div id="directorist" class="atbd_wrapper atbd_author_profile">
    <div class="<?php echo apply_filters('atbdp_public_profile_container_fluid', $container_fluid) ?>">
        <div class="row">
            <div class="col-md-12">
                <?php
                $author_id = !empty($author_id) ? $author_id : get_current_user_id();
                $author_id = rtrim($author_id, '/');
                $first_name = get_the_author_meta('first_name', $author_id);
                $last_name = get_the_author_meta('last_name', $author_id);
                $author_name =  $first_name . ( $last_name ? ' ' . $last_name : '' );
                $author_name =  $author_name ? $author_name : get_the_author_meta('display_name', $author_id);
                $user_registered = get_the_author_meta('user_registered', $author_id);
                $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                $u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
                $bio = get_user_meta($author_id, 'description', true);
                $avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 96));
                $address = esc_attr(get_user_meta($author_id, 'address', true));
                $phone = esc_attr(get_user_meta($author_id, 'atbdp_phone', true));
                $email = get_the_author_meta('user_email', $author_id);
                $website = get_the_author_meta('user_url', $author_id);;
                $facebook = get_user_meta($author_id, 'atbdp_facebook', true);
                $twitter = get_user_meta($author_id, 'atbdp_twitter', true);
                $linkedIn = get_user_meta($author_id, 'atbdp_linkedin', true);
                $youtube = get_user_meta($author_id, 'atbdp_youtube', true);
                $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
                ?>
                <div class="atbd_auhor_profile_area">
                    <div class="atbd_author_avatar">
                        <?php if (empty($u_pro_pic)) {
                            echo $avatar_img;
                        }
                        if (!empty($u_pro_pic)) { ?><img src="<?php echo esc_url($u_pro_pic[0]); ?>" alt="Author Image"><?php } ?>
                        <div class="atbd_auth_nd">
                            <h2><?php echo esc_html($author_name); ?></h2>
                            <p><?php
                                printf(__('Member since %s ago', 'directorist'), human_time_diff(strtotime($user_registered), current_time('timestamp'))); ?></p>
                        </div>
                    </div>

                    <div class="atbd_author_meta">
                        <?php
                        $args = array(
                            'post_type'      => ATBDP_POST_TYPE,
                            'post_status'    => 'publish',
                            'author'         => $author_id,
                            'orderby'        => 'post_date',
                            'order'          => 'ASC',
                            'posts_per_page' => -1, // no limit
                            'fields'         => 'ids'
                        );
                        $current_user_posts = get_posts($args);

                        $total_listing = apply_filters('atbdp_author_listing_count', count($current_user_posts));
                        $enable_review = get_directorist_option('enable_review', 1);
                        $review_in_post = 0;
                        $all_reviews = 0;

                        foreach ($current_user_posts as $post_id) {
                            $average = ATBDP()->review->get_average( $post_id );
                            if ( ! empty( $average ) ) {
                                $averagee = array($average);
                                foreach ($averagee as $key) {
                                    $all_reviews += $key;
                                }
                                $review_in_post++;
                            }
                        }

                        $author_rating = (!empty($all_reviews) && !empty($review_in_post)) ? ($all_reviews / $review_in_post) : 0;
                        $author_rating = substr($author_rating, '0', '3');
                        if ($enable_review) { ?>
                            <div class="atbd_listing_meta">
                                <span class="atbd_meta atbd_listing_rating">
                                    <?php echo $author_rating; ?><i class="<?php atbdp_icon_type(true); ?>-star"></i>
                                </span>
                            </div>
                            <p class="meta-info">
                                <span><?php echo !empty($review_in_post) ? $review_in_post : '0' ?></span>
                                <?php echo (($review_in_post > 1) || ($review_in_post === 0)) ? __('Reviews', 'directorist') : __('Review', 'directorist') ?>
                            </p>
                        <?php
                        }
                        ?>
                        <p class="meta-info">
                            <span><?php echo !empty($total_listing) ? $total_listing : '0' ?></span>
                            <?php echo (($total_listing > 1) || ($total_listing === 0)) ? __('Listings', 'directorist') : __('Listing', 'directorist') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="atbd_author_module">
                    <div class="atbd_content_module">
                        <div class="atbd_content_module_title_area">
                            <div class="atbd_area_title">
                                <h4>
                                    <span class="<?php atbdp_icon_type(true); ?>-user"></span><?php _e('About', 'directorist'); ?>
                                </h4>
                            </div>
                        </div>

                        <div class="atbdb_content_module_contents">
                            <p>
                                <?php
                                $content = apply_filters('the_content', $bio);
                                echo !empty($bio) ? $content : __('Nothing to show!', 'directorist');
                                ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget atbd_widget">
                    <div class="atbd_widget_title">
                        <h4><?php _e('Contact Info', 'directorist'); ?></h4>
                    </div>
                    <div class="atbdp atbd_author_info_widget">
                        <div class="atbd_widget_contact_info">
                            <ul>
                                <?php
                                if (!empty($address)) {
                                ?>
                                    <li>
                                        <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span>
                                        <span class="atbd_info"><?php echo !empty($address) ? esc_html($address) : ''; ?></span>
                                    </li>
                                <?php
                                }
                                if (!empty($phone)) {
                                ?>
                                    <!-- In Future, We will have to use a loop to print more than 1 number-->
                                    <li>
                                        <span class="<?php atbdp_icon_type(true); ?>-phone"></span>
                                        <span class="atbd_info"><a href="tel:<?php echo esc_html(stripslashes($phone)); ?>"><?php echo esc_html(stripslashes($phone)); ?></a></span>
                                    </li>
                                    <?php
                                }
                                $email_show = get_directorist_option('display_author_email', 'public');
                                if ('public' === $email_show) {
                                    if (!empty($email)) {
                                    ?>
                                        <li>
                                            <span class="<?php atbdp_icon_type(true); ?>-envelope"></span>
                                            <span class="atbd_info"><?php echo !empty($email) ? esc_html($email) : ''; ?></span>
                                        </li>
                                        <?php
                                    }
                                } elseif ('logged_in' === $email_show) {
                                    if (atbdp_logged_in_user()) {
                                        if (!empty($email)) {
                                        ?>
                                            <li>
                                                <span class="<?php atbdp_icon_type(true); ?>-envelope"></span>
                                                <span class="atbd_info"><?php echo !empty($email) ? esc_html($email) : ''; ?></span>
                                            </li>
                                    <?php
                                        }
                                    }
                                }

                                if (!empty($website)) {
                                    ?>
                                    <li>
                                        <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                                        <span class="atbd_info"><a target="_blank" href="<?php echo !empty($website) ? esc_html($website) : ''; ?>"><?php echo !empty($website) ? esc_html($website) : ''; ?></a></span>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <?php if (!empty($facebook || $twitter || $linkedIn || $youtube)) { ?>
                            <div class="atbd_social_wrap">
                                <div class="atbd_director_social_wrap">
                                    <?php
                                    if ($facebook) {
                                        printf('<a class="facebook" target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-facebook"></span></a>', $facebook);
                                    }
                                    if ($twitter) {
                                        printf('<a class="twitter" target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-twitter"></span></a>', $twitter);
                                    }
                                    if ($linkedIn) {
                                        printf('<a class="linkedin" target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-linkedin"></span></a>', $linkedIn);
                                    }
                                    if ($youtube) {
                                        printf('<a class="youtube" target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-youtube"></span></a>', $youtube);
                                    }
                                    ?>
                                </div>
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
                    <?php
                    $header_title = apply_filters('atbdp_author_listings_header_title', 1);
                    if ($header_title) {
                    ?>
                        <h1><?php _e("Author Listings", 'directorist'); ?></h1>
                    <?php }
                    $author_cat_filter = get_directorist_option('author_cat_filter', 1);
                    ?>
                    <?php if (!empty($author_cat_filter)) { ?>
                        <div class="atbd_author_filter_area">
                            <?php
                            /*
                             * @since 6.2.3
                             */
                            do_action('atbpd_before_author_listings_category_dropdown', $all_listings);
                            ?>
                            <div class="atbd_dropdown">
                                <a class="atbd_dropdown-toggle" href="#" id="dropdownMenuLink">
                                    <?php _e("Filter by category", 'directorist'); ?> <span class="atbd_drop-caret"></span>
                                </a>

                                <div class="atbd_dropdown-menu atbd_dropdown-menu--lg" aria-labelledby="dropdownMenuLink">
                                    <?php
                                    foreach ($categories as $category) {
                                        $active = (isset($_GET['category']) && ($category->slug == $_GET['category'])) ? 'active' : '';
                                        printf('<a class="atbd_dropdown-item %s" href="%s">%s</a>', $active, add_query_arg('category', $category->slug), $category->name);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row atbd_authors_listing">
            <?php
            $listings = apply_filters('atbdp_author_listings', true);
            if ( $listings ) {
                listing_view_by_grid($all_listings, $paginate, $is_disable_price);
            } else {
                // for dev
                do_action('atbdp_author_listings_html', $all_listings);
            }
            ?>
        </div>
    </div>
</div>