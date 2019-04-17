<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
$is_disable_price = get_directorist_option('disable_list_price');
$display_sortby_dropdown = get_directorist_option('display_sort_by', 1);
$display_viewas_dropdown = get_directorist_option('display_view_as', 1);
$pagenation = get_directorist_option('paginate_all_listings', 1);
wp_enqueue_style( 'atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
$column_width = 100 / $columns . '%';
?>

<div id="directorist" class="atbd_wrapper ads-advaced--wrapper">
    <?php if( $display_header == 'yes'  ) { ?>
    <div class="header_bar">
        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
            <div class="row">
                <div class="col-md-12">
                    <?php if(!empty($header_title)) {?>
                        <h3>
                            <?php echo esc_html($header_title); ?>
                        </h3>
                    <?php } ?>
                    <div class="atbd_generic_header">
                        <div class="atbd_generic_header_title">
                            <button class="more-filter btn btn-outline btn-outline-primary"><span class="fa fa-filter"></span> <?php _e('More Filters', ATBDP_TEXTDOMAIN);?></button>
                        </div>
                        <?php if ($display_viewas_dropdown || $display_sortby_dropdown) { ?>
                            <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                <!-- Views dropdown -->
                                <?php if ($display_viewas_dropdown) {
                                    $html = '<div class="dropdown">';
                                    $html .= '<a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . __("View as", ATBDP_TEXTDOMAIN) . '<span class="caret"></span>
                                    </a>';
                                    $html .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                                    $views = atbdp_get_listings_view_options();
                                    foreach ($views as $value => $label) {
                                        $active_class = ($view == $value) ? ' active' : '';
                                        $html .= sprintf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('view', $value), $label);

                                    }
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    /**
                                     * @since 5.0.0
                                     * @package Directorist
                                     * @param htmlUms $html it return the markup for list and grid
                                     * @param string $view the shortcode attr view_as value
                                     * @param array $views it return the views type array
                                     *
                                     */
                                    echo apply_filters('atbdp_listings_view_as', $html, $view, $views);
                                    ?>
                                <?php } ?>
                                <!-- Orderby dropdown -->
                                <?php
                                if ($display_sortby_dropdown) {
                                    ?>
                                    <div class="dropdown">
                                        <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <?php _e("Sort by", ATBDP_TEXTDOMAIN); ?> <span class="caret"></span>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                            <?php
                                            $options = atbdp_get_listings_orderby_options();

                                            foreach ($options as $value => $label) {
                                                $active_class = ($value == $current_order) ? ' active' : '';
                                                printf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('sort', $value), $label);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <!--ads advance search-->
                    <div class="">
                        <div class="ads-advanced">
                            <form action="/">
                                <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border)?'style="border: none;"':'';?>>
                                    <div class="row atbdp-search-form">

                                        <div class="col-md-6 col-sm-12 col-lg-4">
                                            <div class="single_search_field search_query">
                                                <input class="form-control search_fields" type="text" name="q"
                                                       placeholder="<?php echo esc_html($search_placeholder); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 col-lg-4">
                                            <div class="single_search_field search_category">
                                                <?php
                                                $args = array(
                                                    'show_option_none' =>  __('Select a category', ATBDP_TEXTDOMAIN),
                                                    'taxonomy' => ATBDP_CATEGORY,
                                                    'id' => 'cat-type',
                                                    'option_none_value'  => '',
                                                    'class' => 'form-control directory_field bdas-category-search',
                                                    'name' => 'in_cat',
                                                    'orderby' => 'name',
                                                    'selected' => '',
                                                    'hierarchical' => true,
                                                    'value_field'  => 'id',
                                                    'depth' => 10,
                                                    'show_count' => false,
                                                    'hide_empty' => false,
                                                );

                                                wp_dropdown_categories($args);
                                                ?>
                                            </div>

                                        </div>

                                        <div class="col-md-12 col-sm-12 col-lg-4">
                                            <div class="single_search_field search_location">
                                                <?php
                                                $args = array(
                                                    'show_option_none' =>  __('Select a location', ATBDP_TEXTDOMAIN),
                                                    'taxonomy' => ATBDP_LOCATION,
                                                    'id' => 'cat-type',
                                                    'option_none_value'  => '',
                                                    'class' => 'form-control directory_field',
                                                    'name' => 'in_loc',
                                                    'orderby' => 'name',
                                                    'selected' => '',
                                                    'hierarchical' => true,
                                                    'value_field'  => 'id',
                                                    'depth' => 10,
                                                    'show_count' => false,
                                                    'hide_empty' => false,
                                                );

                                                wp_dropdown_categories($args);
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        /**
                                         * @since 5.0
                                         */
                                        do_action('atbdp_search_field_after_location');

                                        ?>
                                    </div>
                                </div>
                                <div class="form-group ">

                                    <label class=""><?php _e('Price Range', ATBDP_TEXTDOMAIN);?></label>
                                    <div class="price_ranges">
                                        <div class="range_single">
                                            <input type="text" name="price[0]" class="form-control" placeholder="Min Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
                                        </div>
                                        <div class="range_single">
                                            <input type="text" name="price[1]" class="form-control" placeholder="Max Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                                        </div>

                                        <div class="price-frequency">
                                            <label class="pf-btn"><input type="radio" name="price_range" value="bellow_economy"<?php if(!empty($_GET['price_range']) && 'bellow_economy' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$</span></label>
                                            <label class="pf-btn"><input type="radio" name="price_range" value="economy" <?php if(!empty($_GET['price_range']) && 'economy' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$</span></label>
                                            <label class="pf-btn"><input type="radio" name="price_range" value="moderate" <?php if(!empty($_GET['price_range']) && 'moderate' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$$</span></label>
                                            <label class="pf-btn"><input type="radio" name="price_range" value="skimming" <?php if(!empty($_GET['price_range']) && 'skimming' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$$$</span></label>
                                        </div>

                                    </div>
                                </div><!-- ends: .form-group -->


                                <div class="form-group">
                                    <label><?php _e('Filter by Ratings', ATBDP_TEXTDOMAIN);?></label>
                                    <select class="select-basic form-control">
                                        <option value=""><?php _e('Select Ratings', ATBDP_TEXTDOMAIN);?></option>
                                        <option name='search_by_rating' value="5" <?php if(!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>5 Star</option>
                                        <option name='search_by_rating' value="4" <?php if(!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>4 Star & Up</option>
                                        <option name='search_by_rating' value="3" <?php if(!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>3 Star & Up</option>
                                        <option name='search_by_rating' value="2" <?php if(!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>2 Star & Up</option>
                                        <option name='search_by_rating' value="1" <?php if(!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>1 Star & Up</option>
                                    </select>
                                </div><!-- ends: .form-group -->

                                <div class="form-group">
                                    <label>Open Now</label>
                                    <div class="check-btn">
                                        <div class="btn-checkbox">
                                            <label>
                                                <input type="checkbox" name="open_now" value="open_now" <?php if(!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) { echo "checked='checked'";}?>>
                                                <span><i class="fa fa-clock-o"></i><?php _e('Open Now', ATBDP_TEXTDOMAIN);?> </span>
                                            </label>
                                        </div>
                                    </div>
                                </div><!-- ends: .form-group -->

                                <div class="form-group ads-filter-tags">
                                    <label>Tags</label>
                                    <div class="bads-tags">
                                        <?php
                                        $terms = get_terms(ATBDP_TAGS);
                                        if(!empty($terms)) {
                                            foreach($terms as $term) {
                                                ?>
                                                <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                                    <input type="checkbox" class="custom-control-input" name="in_tag" value="<?php echo $term->term_id;?>" id="<?php echo $term->term_id;?>">
                                                    <span class="check--select"></span>
                                                    <label for="<?php echo $term->term_id;?>" class="custom-control-label"><?php echo $term->name;?></label>
                                                </div>
                                            <?php } }?>
                                    </div>
                                    <a href="#" class="more-less ad"><?php _e('Show More', ATBDP_TEXTDOMAIN);?></a>
                                </div><!-- ends: .form-control -->

                                <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                                    <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ? (int) $_GET['in_cat'] : 0 ); ?>
                                </div>

                                <div class="form-group">
                                    <div class="bottom-inputs">
                                        <div>

                                            <input type="text" name="website" placeholder="<?php _e('Website', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
                                        </div>
                                        <div>

                                            <input type="text" name="email" placeholder=" <?php _e('Email', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
                                        </div>
                                        <div>

                                            <input type="text" name="phone" placeholder="<?php _e('Phone Number', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
                                        </div>
                                        <div>

                                            <input type="text" name="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="<?php _e('Address', ATBDP_TEXTDOMAIN);?>"
                                                   class="form-control location-name">
                                        </div>
                                        <div>

                                            <input type="text" name="zip_code" placeholder=" <?php _e('Zip/Post Code', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="bdas-filter-actions">

                                    <a href="<?php echo get_permalink();?>" class="btn btn-outline btn-outline-primary btn-lg"><?php _e('Reset Filters', ATBDP_TEXTDOMAIN);?></a>

                                    <button type="submit" class="btn btn-primary btn-lg"><?php _e('Apply Filters', ATBDP_TEXTDOMAIN);?></button>

                                </div><!-- ends: .bdas-filter-actions -->
                            </form>
                        </div> <!--ads advanced -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="<?php echo is_directoria_active() ? 'container' : 'container-fluid'; ?>">
        <?php
        /**
         * @since 5.0
         * It fires before the listings columns
         */
        do_action('atbdp_before_grid_listings_loop');
        ?>
        <div class="row" <?php echo (get_directorist_option('grid_view_as', 'masonry_grid') !== 'masonry_grid') ? '' : 'data-uk-grid'; ?>>
            <?php
            if ($all_listings->have_posts()) {
                while ($all_listings->have_posts()) {
                    $all_listings->the_post();
                    $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                    $locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                    $featured = get_post_meta(get_the_ID(), '_featured', true);
                    $price = get_post_meta(get_the_ID(), '_price', true);
                    $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                    $atbd_listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                    $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                    $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                    $excerpt = get_post_meta(get_the_ID(), '_excerpt', true);
                    $tagline = get_post_meta(get_the_ID(), '_tagline', true);
                    $address = get_post_meta(get_the_ID(), '_address', true);
                    $phone_number = get_post_meta(get_the_Id(), '_phone', true);
                    $category = get_post_meta(get_the_Id(), '_admin_category_select', true);
                    $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
                    $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                    $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                    $display_title = get_directorist_option('display_title', 1);
                    $display_review = get_directorist_option('enable_review', 1);
                    $display_price = get_directorist_option('display_price', 1);
                    $display_category = get_directorist_option('display_category', 1);
                    $display_view_count = get_directorist_option('display_view_count', 1);
                    $display_author_image = get_directorist_option('display_author_image', 1);
                    $display_publish_date = get_directorist_option('display_publish_date', 1);
                    $display_contact_info = get_directorist_option('display_contact_info', 1);
                    $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
                    $display_popular_badge_cart = get_directorist_option('display_popular_badge_cart', 1);
                    $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
                    $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');
                    $enable_tagline = get_directorist_option('enable_tagline');
                    $enable_excerpt = get_directorist_option('enable_excerpt');
                    $address_location = get_directorist_option('address_location', 'location');
                    /*Code for Business Hour Extensions*/
                    $bdbh = get_post_meta(get_the_ID(), '_bdbh', true);
                    $enable247hour = get_post_meta(get_the_ID(), '_enable247hour', true);
                    $disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
                    $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                    $author_id = get_the_author_meta('ID');
                    $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                    $u_pro_pic = wp_get_attachment_image_src($u_pro_pic, 'thumbnail');
                    $avata_img = get_avatar($author_id, 32);
                    $thumbnail_cropping = get_directorist_option('thumbnail_cropping', 1);
                    $crop_width = get_directorist_option('crop_width', 360);
                    $crop_height = get_directorist_option('crop_height', 300);
                    $display_tagline_field = get_directorist_option('display_tagline_field', 0);
                    $display_pricing_field = get_directorist_option('display_pricing_field', 1);
                    $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
                    $display_address_field = get_directorist_option('display_address_field', 1);
                    $display_phone_field = get_directorist_option('display_phone_field', 1);
                    if (!empty($listing_prv_img)) {

                        if ($thumbnail_cropping) {

                            $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                        } else {
                            $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                        }

                    }
                    if (!empty($listing_img[0])) {
                        if ($thumbnail_cropping) {
                            $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];
                            $default_img = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];;
                        } else {
                            $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                        }

                    }

                    /*Code for Business Hour Extensions*/
                    ?>

                    <div class="atbdp_column">
                        <div class="atbd_single_listing atbd_listing_card <?php echo get_directorist_option('info_display_in_single_line', 0) ? 'atbd_single_line_card_info' : ''; ?>">
                            <article
                                    class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                <figure class="atbd_listing_thumbnail_area"
                                        style=" <?php echo empty(get_directorist_option('display_preview_image', 1)) ? 'display:none' : '' ?>">
                                    <div class="atbd_listing_image">
                                        <?php
                                        $disable_single_listing = get_directorist_option('disable_single_listing');
                                        if (empty($disable_single_listing)){
                                        ?>
                                        <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>">
                                            <?php
                                            }
                                            $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                            if (!empty($listing_prv_img)) {

                                                echo '<img src="' . esc_url($prv_image) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                            }
                                            if (!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                echo '<img src="' . esc_url($gallery_img) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                            }
                                            if (empty($listing_img[0]) && empty($listing_prv_img)) {

                                                echo '<img src="' . $default_image . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                            }

                                            if (empty($disable_single_listing)) {
                                                echo '</a>';
                                            }
                                            if (!empty($display_author_image)) {
                                                $author = get_userdata($author_id);
                                                ?>
                                                <div class="atbd_author">
                                                    <a href="<?= ATBDP_Permalink::get_user_profile_page_link($author_id); ?>"
                                                       data-toggle="tooltip" data-placement="top"
                                                       title="<?php echo $author->first_name . ' ' . $author->last_name; ?>"><?php if (empty($u_pro_pic)) {
                                                            echo $avata_img;
                                                        }
                                                        if (!empty($u_pro_pic)) { ?>
                                                            <img
                                                            src="<?php echo esc_url($u_pro_pic[0]); ?>"
                                                            alt="Author Image"><?php } ?>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                    </div>
                                    <?php
                                    $plan_hours = true;
                                    $u_badge_html = '<span class="atbd_upper_badge">';
                                    if (is_fee_manager_active()) {
                                        $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
                                    }
                                    if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
                                        //lets check is it 24/7
                                        $open = get_directorist_option('open_badge_text', __('Open Now', ATBDP_TEXTDOMAIN));
                                        if (!empty($enable247hour)) {
                                            $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">'.$open.'</span>';

                                        } else {
                                            $bh_statement = BD_Business_Hour()->show_business_open_close($business_hours);

                                            $u_badge_html .= $bh_statement;
                                         }
                                    }
                                    $u_badge_html .= '</span>';

                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_upper_badges', $u_badge_html);


                                    //Start lower badge
                                    $l_badge_html = '<span class="atbd_lower_badge">';

                                    if ($featured && !empty($display_feature_badge_cart)) {
                                        $l_badge_html .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
                                    }

                                    $popular_listing_id = atbdp_popular_listings(get_the_ID());
                                    $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                                    if ($popular_listing_id === get_the_ID()) {
                                        $l_badge_html .= $badge;
                                    }
                                    //print the new badge
                                    $l_badge_html .= new_badge();
                                    $l_badge_html .= '</span>';

                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_lower_badges', $l_badge_html);
                                    ?>

                                </figure>
                                <div class="atbd_listing_info">
                                    <?php if (!empty($display_title) || !empty($enable_tagline) || !empty($display_review) || !empty($display_price)) { ?>
                                        <div class="atbd_content_upper">
                                            <?php if (!empty($display_title)) { ?>
                                                <h4 class="atbd_listing_title">
                                                    <?php
                                                    if (empty($disable_single_listing)) {
                                                        ?>
                                                        <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                                        <?php
                                                    } else {
                                                        echo esc_html(stripslashes(get_the_title()));
                                                    } ?>

                                                </h4>
                                            <?php }
                                            if (!empty($tagline) && !empty($enable_tagline) && !empty($display_tagline_field)) {
                                                ?>
                                                <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                            <?php }

                                            /**
                                             * Fires after the title and sub title of the listing is rendered
                                             *
                                             *
                                             * @since 1.0.0
                                             */

                                            do_action('atbdp_after_listing_tagline');
                                            ?>
                                            <?php if (!empty($display_review) || !empty($display_price)) { ?>
                                                <div class="atbd_listing_meta">
                                                    <?php
                                                    $average = ATBDP()->review->get_average(get_the_ID());
                                                    ?>
                                                    <span class="atbd_meta atbd_listing_rating">
            <?php echo $average; ?><i class="fa fa-star"></i>
        </span>
                                                    <?php
                                                    $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
                                                    if (!empty($display_price) && !empty($display_pricing_field)) {
                                                        if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                                                            $output = atbdp_display_price_range($price_range);
                                                            echo $output;
                                                        } else {
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

                                            <?php }
                                            /**
                                             * @since 5.0
                                             * universal action to fire after the price
                                             */
                                            do_action('atbdp_listings_after_price');
                                            ?>
                                            <?php if (!empty($display_contact_info) || !empty($display_publish_date)) { ?>
                                                <div class="atbd_listing_data_list">
                                                    <ul>
                                                        <?php
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_before_location');

                                                        if (!empty($display_contact_info)) {
                                                            if (!empty($address) && 'contact' == $address_location && !empty($display_address_field)) { ?>
                                                                <li><p>
                                                                        <span class="fas fa-map-marker-alt"></span><?php echo esc_html(stripslashes($address)); ?>
                                                                    </p></li>
                                                            <?php } elseif (!empty($locs) && 'location' == $address_location) {

                                                                $numberOfCat = count($locs);
                                                                $output = array();
                                                                foreach ($locs as $loc) {
                                                                    $link = ATBDP_Permalink::atbdp_get_location_page($loc);
                                                                    $space = str_repeat(' ', 1);
                                                                    $output [] = "{$space}<a href='{$link}'>{$loc->name}</a>";
                                                                } ?>

                                                                <li>
                                                                    <p>

                                                    <span>
                                                    <?php echo "<span class='fas fa-map-marker-alt'></span>" . join(',', $output); ?>
                                                </span>
                                                                    </p>
                                                                </li>
                                                            <?php }
                                                            /**
                                                             * @since 4.7.6
                                                             */
                                                            do_action('atbdp_listings_before_phone');
                                                            ?>
                                                            <?php if (!empty($phone_number) && !empty($display_phone_field)) { ?>
                                                                <li><p>
                                                                        <span class="fa fa-phone"></span><?php echo esc_html(stripslashes($phone_number)); ?>
                                                                    </p></li>
                                                                <?php
                                                            }
                                                        }
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_before_post_date');

                                                        if (!empty($display_publish_date)) { ?>
                                                            <li><p><span class="fa fa-clock-o"></span><?php
                                                                    printf(__('Posted %s ago', ATBDP_TEXTDOMAIN), human_time_diff(get_the_time('U'), current_time('timestamp')));
                                                                    ?></p></li>
                                                        <?php }
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_after_post_date');
                                                        ?>
                                                    </ul>
                                                </div><!-- End atbd listing meta -->
                                                <?php
                                            }
                                            if (!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) { ?>
                                                <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 20))); ?></p>
                                            <?php } ?>
                                        </div><!-- end ./atbd_content_upper -->
                                    <?php } ?>
                                    <?php if (!empty($display_category) || !empty($display_view_count)) { ?>
                                        <div class="atbd_listing_bottom_content">
                                            <?php
                                            if (!empty($display_category)) {
                                                if (!empty($cats)) {
                                                    global $post;
                                                    $totalTerm = count($cats);


                                                    ?>
                                                    <div class="atbd_content_left">
                                                        <div class="atbd_listting_category">
                                                            <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>"><?php if ('none' != get_cat_icon($cats[0]->term_id)) { ?>
                                                                    <span class="fa fa-folder-open"></span><?php } ?><?php echo $cats[0]->name; ?>
                                                            </a>
                                                            <?php
                                                            if ($totalTerm > 1) {
                                                                ?>
                                                                <div class="atbd_cat_popup">
                                                                    <span>+<?php echo $totalTerm - 1; ?></span>
                                                                    <div class="atbd_cat_popup_wrapper">
                                                                        <?php
                                                                        $output = array();
                                                                        foreach (array_slice($cats, 1) as $cat) {
                                                                            $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                            $space = str_repeat(' ', 1);
                                                                            $output [] = "{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>";
                                                                        } ?>
                                                                        <span><?php echo join($output); ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } else {
                                                    ?>
                                                    <div class="atbd_content_left">
                                                        <div class="atbd_listting_category">
                                                            <a href=""><span
                                                                        class="fa fa-folder-open"></span><?php echo __('Uncategorized', ATBDP_TEXTDOMAIN); ?>
                                                            </a>
                                                        </div>
                                                    </div>

                                                <?php }
                                            } ?>
                                            <?php if (!empty($display_view_count)) { ?>
                                                <ul class="atbd_content_right">
                                                    <?php if (!empty($display_view_count)) { ?>
                                                        <li class="atbd_count"><span
                                                                    class="fa fa-eye"></span><?php echo !empty($post_view) ? $post_view : 0; ?>
                                                        </li> <?php } ?>


                                                    <li class="atbd_save">
                                                        <div id="atbdp-favourites-all-listing">
                                                            <input type="hidden" id="listing_ids"
                                                                   value="<?php echo get_the_ID(); ?>">
                                                            <?php
                                                            // do_action('wp_ajax_atbdp-favourites-all-listing', get_the_ID()); ?>
                                                        </div>
                                                    </li>
                                                </ul>
                                            <?php } ?>
                                        </div><!-- end ./atbd_listing_bottom_content -->
                                    <?php } ?>
                                </div>
                            </article>
                        </div>
                    </div>
                <?php }
                wp_reset_postdata();
            }else { ?>
                <p><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
            <?php }
            ?>

        </div>
        <?php
        /**
         * @since 5.0
         * to add custom html
         */
        do_action('atbdp_after_grid_listings_loop');
        ?>
    </div>
    <div class="row atbd_listing_pagination">
        <?php
        if (1 == $pagenation) {
            ?>
            <div class="col-md-12">
                <div class="">
                    <?php
                    $paged = !empty($paged) ? $paged : '';
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<style>
    .atbd_content_active #directorist.atbd_wrapper .atbdp_column {
        width: <?php echo $column_width;?>;
    }
</style>