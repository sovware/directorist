<div id="directorist" class="atbd_wrapper atbd_author_profile">
    <div class="<?php echo $container_fluid; ?>">
        <?php
        /**
         * @hooked Directorist_Template_Hooks::author_profile_header - 10
         * @hooked Directorist_Template_Hooks::author_profile_about - 15
         * @hooked Directorist_Template_Hooks::author_profile_listings - 20
         */
        do_action( 'directorist_author_profile_content' );
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="atbd_auhor_profile_area">
                    <div class="atbd_author_avatar">
                        <?php if (empty($u_pro_pic)) {
                            echo $avatar_img;
                        }
                        if (!empty($u_pro_pic)) { ?><img
                            src="<?php echo esc_url($u_pro_pic[0]); ?>"
                            alt="Author Image" ><?php } ?>
                        <div class="atbd_auth_nd">
                            <h2><?php echo esc_html($author_name); ?></h2>
                            <p><?php
                                printf(__('Member since %s ago', 'directorist'), human_time_diff(strtotime($user_registered), current_time('timestamp'))); ?></p>
                        </div>
                    </div>

                    <div class="atbd_author_meta">
                        <?php
                        if ($enable_review) {
                            ?>
                            <div class="atbd_listing_meta">
                            <span class="atbd_meta atbd_listing_rating">
                                <?php echo $author_rating; ?><i class="<?php atbdp_icon_type(true); ?>-star"></i>
                            </span>
                            </div>
                            <p class="meta-info">
                                <span><?php echo !empty($author_review_count) ? $author_review_count : '0' ?></span>
                                <?php echo (($author_review_count > 1) || ($author_review_count === 0)) ? __('Reviews', 'directorist') : __('Review', 'directorist') ?>
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
                                echo !empty($bio) ? $content : __('Nothing to show!', 'directorist');
                                ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget atbd_widget">
                    <div class="atbd_widget_title"><h4><?php _e('Contact Info', 'directorist'); ?></h4></div>
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
                                        <span class="atbd_info"><a
                                                    href="tel:<?php echo esc_html(stripslashes($phone)); ?>"><?php echo esc_html(stripslashes($phone)); ?></a></span>
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
                        <?php
                        if (!empty($facebook || $twitter || $linkedIn || $youtube)) {
                            ?>
                            <div class="atbd_social_wrap">
                                <?php
                                if ($facebook) {
                                    printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-facebook"></span></a></p>', $facebook);
                                }
                                if ($twitter) {
                                    printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-twitter"></span></a></p>', $twitter);
                                }
                                if ($linkedIn) {
                                    printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-linkedin"></span></a></p>', $linkedIn);
                                }
                                if ($youtube) {
                                    printf('<p><a target="_blank" href="%s"><span class="' . atbdp_icon_type() . '-youtube"></span></a></p>', $youtube);
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
                    <?php
                    if ($header_title){
                    ?>
                    <h1><?php _e("Author Listings", 'directorist'); ?></h1>
                    <?php }
                    ?>
                    <?php if(!empty($author_cat_filter)) {?>
                    <div class="atbd_author_filter_area">
                        <?php
                        /*
                         * @since 6.2.3
                         */
                        do_action('atbpd_before_author_listings_category_dropdown', $all_listings);
                        ?>
                        <div class="atbd_dropdown">
                            <a class="atbd_dropdown-toggle" href="#"
                               id="dropdownMenuLink">
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
            
            if ($listings){
                listing_view_by_grid($all_listings, $paginate, $is_disable_price);
            }else{
                // for dev
                do_action('atbdp_author_listings_html', $all_listings);
            }
            ?>

        </div>
    </div>
</div>
