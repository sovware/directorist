<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03-Dec-18
 * Time: 1:02 PM
 */ ?>
<div id="directorist" class="atbd_wrapper atbd_public_profile">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                $listing_id = get_the_ID();
                $author_id = get_post_field('post_author', $listing_id);
                $author_name = get_the_author_meta('display_name', $author_id);
                $user_registered = get_the_author_meta('user_registered', $author_id);
                $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
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
                ?>
                <div class="atbd_auhor_profile_area">
                    <div class="atbd_author_avatar">
                        <?php if (empty($u_pro_pic)) {
                            echo $avata_img;
                        }
                        if (!empty($u_pro_pic)) { ?><img
                            src="<?php echo esc_url($u_pro_pic); ?>"
                            alt="Author Image"><?php } ?>
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
                                <?php echo esc_html($bio); ?>
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
                                <li>
                                    <span class="fa fa-map-marker"></span>
                                    <span class="atbd_info"><?= !empty($address)?esc_html($address):''; ?></span>
                                </li>

                                <!-- In Future, We will have to use a loop to print more than 1 number-->
                                <li>
                                    <span class="fa fa-phone"></span>
                                    <span class="atbd_info"><?= !empty($phone)?esc_html($phone):''; ?></span>
                                </li>

                                <li>
                                    <span class="fa fa-envelope"></span>
                                    <span class="atbd_info"><?= !empty($email)?esc_html($email):''; ?></span>
                                </li>

                                <li>
                                    <span class="fa fa-globe"></span>
                                    <span class="atbd_info"><?= !empty($website)?esc_html($website):''; ?></span>
                                </li>

                            </ul>
                        </div>
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
                        <a href="<?= esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
                           class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Send Massage', ATBDP_TEXTDOMAIN); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="atbd_author_listings_area">
                    <h1>68 Author Listings</h1>
                    <div class="atbd_author_filter_area">
                        <select class="form-control" name="category" id="selectCategory">
                            <option value="">Category</option>
                            <option value="">Option one</option>
                            <option value="">Option two</option>
                            <option value="">Option Three</option>
                            <option value="">Option Four</option>
                            <option value="">Option Five</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="atbd_authors_listing">
            <div class="row">
                <div class="col-md-4">
                    <div class="atbd_single_listing atbd_listing_card">
                        <article class="atbd_single_listing_wrapper directorist-featured-listings">
                            <figure class="atbd_listing_thumbnail_area">
                                <div class="atbd_listing_image">
                                    <img src="http://localhost/martplace/wp-content/uploads/2017/08/steak-1.jpg"
                                         alt="listing image"></div>

                                <figcaption class="atbd_thumbnail_overlay_content">
                                    <div class="atbd_upper_badge">
                                        <span class="atbd_badge atbd_badge_close">Close Now</span></div>

                                    <div class="atbd_lower_badge">
                                        <span class="atbd_badge atbd_badge_featured">Featured</span> <span
                                                class="atbd_badge atbd_badge_popular">Popular</span> <span
                                                class="atbd_badge atbd_badge_new">New</span>
                                    </div>
                                </figcaption>
                            </figure>

                            <div class="atbd_listing_info">
                                <div class="atbd_content_upper">
                                    <h4 class="atbd_listing_title">
                                        <a href="http://localhost/martplace/at_biz_dir/gillrays-steakhouse-bar/">Gillray’s
                                            Steakhouse</a>
                                    </h4>
                                    <p class="atbd_listing_tagline">Steakhouse and Bar</p>
                                    <div class="atbd_listing_meta">
                                                        <span class="atbd_meta atbd_listing_rating">
            4<i class="fa fa-star"></i>
        </span>
                                    </div><!-- End atbd listing meta -->

                                    <div class="atbd_listing_data_list">
                                        <ul>
                                            <li><p><span class="fa fa-location-arrow"></span>Waterloo, London, United
                                                    Kingdom</p></li>
                                            <li><p><span class="fa fa-phone"></span>+44 20 7928 5200</p></li>
                                            <li><p><span class="fa fa-clock-o"></span>Posted 1 year ago</p></li>
                                        </ul>
                                    </div><!-- End atbd listing meta -->
                                    <p>We serve the best Aberdeen Angus steak sourced from English farms accompanied by the
                                        highest quality service.</p>
                                </div><!-- end ./atbd_content_upper -->

                                <div class="atbd_listing_bottom_content">
                                    <div class="atbd_content_left">
                                        <div class="atbd_listting_category">
                                            <a href=""><span class="fa fa fa-square-o"></span>Uncategorized</a>
                                        </div>
                                    </div>

                                    <ul class="atbd_content_right">
                                        <li class="atbd_count"><span class="fa fa-eye"></span>479</li>
                                        <li class="atbd_save"><span class="fa fa-heart"></span></li>
                                        <li class="atbd_author"><a href=""><img alt=""
                                                                                src="http://2.gravatar.com/avatar/27328709cfb84e33d3ccfdf1463f6ca1?s=32&amp;d=mm&amp;r=g"
                                                                                srcset="http://2.gravatar.com/avatar/27328709cfb84e33d3ccfdf1463f6ca1?s=64&amp;d=mm&amp;r=g 2x"
                                                                                class="avatar avatar-32 photo" height="32"
                                                                                width="32"></a></li>
                                    </ul>
                                </div><!-- end ./atbd_listing_bottom_content -->
                            </div>
                        </article>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="atbd_single_listing atbd_listing_card">
                        <article class="atbd_single_listing_wrapper directorist-featured-listings">
                            <figure class="atbd_listing_thumbnail_area">
                                <div class="atbd_listing_image">
                                    <img src="http://localhost/martplace/wp-content/uploads/2017/08/steak-1.jpg"
                                         alt="listing image"></div>

                                <figcaption class="atbd_thumbnail_overlay_content">
                                    <div class="atbd_upper_badge">
                                        <span class="atbd_badge atbd_badge_close">Close Now</span></div>

                                    <div class="atbd_lower_badge">
                                        <span class="atbd_badge atbd_badge_featured">Featured</span> <span
                                                class="atbd_badge atbd_badge_popular">Popular</span> <span
                                                class="atbd_badge atbd_badge_new">New</span>
                                    </div>
                                </figcaption>
                            </figure>

                            <div class="atbd_listing_info">
                                <div class="atbd_content_upper">
                                    <h4 class="atbd_listing_title">
                                        <a href="http://localhost/martplace/at_biz_dir/gillrays-steakhouse-bar/">Gillray’s
                                            Steakhouse</a>
                                    </h4>
                                    <p class="atbd_listing_tagline">Steakhouse and Bar</p>
                                    <div class="atbd_listing_meta">
                                                        <span class="atbd_meta atbd_listing_rating">
            4<i class="fa fa-star"></i>
        </span>
                                    </div><!-- End atbd listing meta -->

                                    <div class="atbd_listing_data_list">
                                        <ul>
                                            <li><p><span class="fa fa-location-arrow"></span>Waterloo, London, United
                                                    Kingdom</p></li>
                                            <li><p><span class="fa fa-phone"></span>+44 20 7928 5200</p></li>
                                            <li><p><span class="fa fa-clock-o"></span>Posted 1 year ago</p></li>
                                        </ul>
                                    </div><!-- End atbd listing meta -->
                                    <p>We serve the best Aberdeen Angus steak sourced from English farms accompanied by the
                                        highest quality service.</p>
                                </div><!-- end ./atbd_content_upper -->

                                <div class="atbd_listing_bottom_content">
                                    <div class="atbd_content_left">
                                        <div class="atbd_listting_category">
                                            <a href=""><span class="fa fa fa-square-o"></span>Uncategorized</a>
                                        </div>
                                    </div>

                                    <ul class="atbd_content_right">
                                        <li class="atbd_count"><span class="fa fa-eye"></span>479</li>
                                        <li class="atbd_save"><span class="fa fa-heart"></span></li>
                                        <li class="atbd_author"><a href=""><img alt=""
                                                                                src="http://2.gravatar.com/avatar/27328709cfb84e33d3ccfdf1463f6ca1?s=32&amp;d=mm&amp;r=g"
                                                                                srcset="http://2.gravatar.com/avatar/27328709cfb84e33d3ccfdf1463f6ca1?s=64&amp;d=mm&amp;r=g 2x"
                                                                                class="avatar avatar-32 photo" height="32"
                                                                                width="32"></a></li>
                                    </ul>
                                </div><!-- end ./atbd_listing_bottom_content -->
                            </div>
                        </article>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="atbd_single_listing atbd_listing_card">
                        <article class="atbd_single_listing_wrapper directorist-featured-listings">
                            <figure class="atbd_listing_thumbnail_area">
                                <div class="atbd_listing_image">
                                    <img src="http://localhost/martplace/wp-content/uploads/2017/08/steak-1.jpg"
                                         alt="listing image"></div>

                                <figcaption class="atbd_thumbnail_overlay_content">
                                    <div class="atbd_upper_badge">
                                        <span class="atbd_badge atbd_badge_close">Close Now</span></div>

                                    <div class="atbd_lower_badge">
                                        <span class="atbd_badge atbd_badge_featured">Featured</span> <span
                                                class="atbd_badge atbd_badge_popular">Popular</span> <span
                                                class="atbd_badge atbd_badge_new">New</span>
                                    </div>
                                </figcaption>
                            </figure>

                            <div class="atbd_listing_info">
                                <div class="atbd_content_upper">
                                    <h4 class="atbd_listing_title">
                                        <a href="http://localhost/martplace/at_biz_dir/gillrays-steakhouse-bar/">Gillray’s
                                            Steakhouse</a>
                                    </h4>
                                    <p class="atbd_listing_tagline">Steakhouse and Bar</p>
                                    <div class="atbd_listing_meta">
                                                        <span class="atbd_meta atbd_listing_rating">
            4<i class="fa fa-star"></i>
        </span>
                                    </div><!-- End atbd listing meta -->

                                    <div class="atbd_listing_data_list">
                                        <ul>
                                            <li><p><span class="fa fa-location-arrow"></span>Waterloo, London, United
                                                    Kingdom</p></li>
                                            <li><p><span class="fa fa-phone"></span>+44 20 7928 5200</p></li>
                                            <li><p><span class="fa fa-clock-o"></span>Posted 1 year ago</p></li>
                                        </ul>
                                    </div><!-- End atbd listing meta -->
                                    <p>We serve the best Aberdeen Angus steak sourced from English farms accompanied by the
                                        highest quality service.</p>
                                </div><!-- end ./atbd_content_upper -->

                                <div class="atbd_listing_bottom_content">
                                    <div class="atbd_content_left">
                                        <div class="atbd_listting_category">
                                            <a href=""><span class="fa fa fa-square-o"></span>Uncategorized</a>
                                        </div>
                                    </div>

                                    <ul class="atbd_content_right">
                                        <li class="atbd_count"><span class="fa fa-eye"></span>479</li>
                                        <li class="atbd_save"><span class="fa fa-heart"></span></li>
                                        <li class="atbd_author"><a href=""><img alt=""
                                                                                src="http://2.gravatar.com/avatar/27328709cfb84e33d3ccfdf1463f6ca1?s=32&amp;d=mm&amp;r=g"
                                                                                srcset="http://2.gravatar.com/avatar/27328709cfb84e33d3ccfdf1463f6ca1?s=64&amp;d=mm&amp;r=g 2x"
                                                                                class="avatar avatar-32 photo" height="32"
                                                                                width="32"></a></li>
                                    </ul>
                                </div><!-- end ./atbd_listing_bottom_content -->
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>