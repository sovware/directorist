<div id="directorist" class="directorist atbd_wrapper directory_search_area single_area ads-advaced--wrapper" style="background-image: url('<?php echo is_directoria_active() ? esc_url($search_home_bg_image) : esc_url($search_home_bg); ?>')">

    <div class="<?php echo apply_filters('atbdp_search_home_container_fluid', $container_fluid) ?>">
        <div class="row">
            <div class="col-md-12">

                <?php
                /**
                 * @since 5.0.8
                 */
                do_action('atbdp_search_listing_before_title');

                if (!empty($search_bar_title || $search_bar_sub_title) && (!empty($show_title_subtitle))) { ?>
                    <div class="atbd_search_title_area">

                        <?php if (!empty($search_bar_title)): ?>
                            <h2 class="title"><?php echo esc_html($search_bar_title); ?></h2>
                        <?php endif; ?>

                        <?php if (!empty($search_bar_sub_title)): ?>
                            <p class="sub_title"><?php echo esc_html($search_bar_sub_title); ?></p>
                        <?php endif; ?>
                        
                    </div>
                <?php } ?>

            </div>
        </div>
        <?php
        if ('always_open' == $filters_display) {
             include 'listing-home-open.php';
        } else { ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- start search area -->
                    <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" class="atbd_ads-form">
                        <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border) ? 'style="border: none;"' : ''; ?>>
                            <?php
                            /**
                             * @since 5.10.0
                             */
                            do_action('atbdp_before_search_form');
                            ?>
                            <?php if ('yes' == $text_field || 'yes' == $category_field || 'yes' == $location_field) { ?>
                                <div class="atbdp-search-form">
                                    <?php
                                    $search_html = '';
                                    if ('yes' == $text_field) {
                                        $search_html .= '<div class="single_search_field search_query">
                                    <input class="form-control search_fields" type="text" name="q"
                                    ' . $require_text . '
                                           placeholder="' . esc_html($search_placeholder) . '">
                                </div>';
                                    }
                                    if ('yes' == $category_field) {
                                        $search_html .= '<div class="single_search_field search_category">';
                                        $search_html .= '<select ' . $require_cat . ' name="in_cat" class="search_fields form-control" id="at_biz_dir-category">';
                                        $search_html .= '<option value="">' . $search_category_placeholder . '</option>';
                                        $search_html .= $categories_fields;
                                        $search_html .= '</select>';
                                        $search_html .= '</div>';
                                    }
                                    if ('yes' == $location_field) {
                                        if ('listing_location' == $search_location_address) {
                                            $search_html .= '<div class="single_search_field search_location">';
                                            $search_html .= '<select ' . $require_loc . ' name="in_loc" class="search_fields form-control" id="at_biz_dir-location">';
                                            $search_html .= '<option value="">' . $search_location_placeholder . '</option>';
                                            $search_html .= $locations_fields;
                                            $search_html .= '</select>';
                                            $search_html .= ' </div>';
                                        } else {
                                            $address = !empty($_GET['address']) ? $_GET['address'] : '';
                                            $select_listing_map = get_directorist_option('select_listing_map','google');
                                            wp_enqueue_script('atbdp-geolocation');
                                            wp_localize_script('atbdp-geolocation', 'adbdp_geolocation', array('select_listing_map'=> $select_listing_map));
                                            $geo_loc = ('google' == $select_listing_map) ? '<span class="atbd_get_loc la la-crosshairs"></span>' : '<span class="atbd_get_loc la la-crosshairs"></span>';
                                            $address_label = !empty($search_location_placeholder) ? sanitize_text_field($search_location_placeholder) : '';
                                            $search_html .= '<div class="single_search_field atbdp_map_address_field">';
                                            $search_html .= '<div class="atbdp_get_address_field"><input ' . $require_loc . ' type="text" id="address" name="address" autocomplete="off" value="' . $address . '" placeholder="' . $address_label . '" class="form-control location-name">'. $geo_loc .'</div>';
                                            $search_html .= '<div class="address_result" style="display: none">';
                                            $search_html .= '</div>';
                                            $search_html .= '<input type="hidden" id="cityLat" name="cityLat" value="" />';
                                            $search_html .= '<input type="hidden" id="cityLng" name="cityLng" value="" />';
                                            $search_html .= '</div>';
                                        }
                                    }
                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_search_form_fields', $search_html);

                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                        <!--More Filters  & Search Button-->
                        <?php
                        $more_filters_icon = !empty($display_more_filter_icon) ? '<span class="' . atbdp_icon_type() . '-filter"></span>' : '';
                        $search_button_icon = !empty($search_button_icon) ? '<span class="fa fa-search"></span>' : '';
                        $html = '';
                        if ('yes' == $more_filters_button || 'yes' == $search_button) {
                            $html .= '<div class="atbd_submit_btn_wrapper">';

                            if (('yes' == $more_filters_button) && ('yes' == $price_min_max_field || 'yes' == $price_range_field || 'yes' == $rating_field || 'yes' == $tag_field || 'yes' == $open_now_field || 'yes' == $custom_fields || 'yes' == $website_field || 'yes' == $email_field || 'yes' == $phone_field || 'yes' == $address_field || 'yes' == $zip_code_field)) {
                                $html .= '<a href="" class="more-filter btn btn-outline btn-lg btn-outline-primary">' . $more_filters_icon . '' . __($more_filters_text, 'directorist') . '</a>';
                            }
                            if ('yes' == $search_button) {
                                $html .= '<div class="atbd_submit_btn">';
                                $html .= '<button type="submit" class="btn btn-primary btn-lg btn_search">';
                                $html .= $search_button_icon . __($search_button_text, 'directorist') . '';
                                $html .= '</button>';
                                $html .= '</div>';
                            }
                            $html .= '</div>';
                        }

                        /**
                         * @since 5.0
                         * It show the search button
                         */
                        echo apply_filters('atbdp_search_listing_button', $html);
                        if (!empty($display_more_filter_search)) { ?>
                            <!--ads advance search-->
                            <?php
                            $filters_display = !empty($filters_display) ? $filters_display : '';
                            ?>
                            <div class="<?php echo ('overlapping' === $filters_display) ? 'ads_float' : 'ads_slide' ?>">
                                <div class="ads-advanced">
                                    <?php include 'adv-search.php';?>
                                </div> <!--ads advanced -->
                            </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <?php if (1 == $show_popular_category) {
                    /*@todo; let user decide what the popular category should be counted based on, and how to sort them*/
                    $args = array(
                        'type' => ATBDP_POST_TYPE,
                        'parent' => 0,          // Gets only top level categories
                        'orderby' => 'count',   // Orders the list by post count
                        'order' => 'desc',
                        'hide_empty' => 1,      // Hides categories with no posts
                        'number' => (int)$popular_cat_num,         // No of categories to return
                        'taxonomy' => ATBDP_CATEGORY,
                        'no_found_rows' => true, // Skip SQL_CALC_FOUND_ROWS for performance (no pagination).
                    );
                    $top_categories = get_categories(apply_filters('atbdp_top_category_argument', $args)); // do not show any markup if we do not have any category at all.
                    if (!empty($top_categories)) {
                        ?>
                        <div class="directory_home_category_area">
                            <?php
                            if ($show_connector == '1') {
                                ?>
                                <span><?php echo $connectors_title; ?></span>
                                <?php
                            }
                            ?>
                            <p><?php echo esc_html($popular_cat_title); ?></p>

                            <ul class="categories atbdp_listing_top_category">
                                <?php
                                foreach ($top_categories as $cat) {
                                    $icon = get_cat_icon($cat->term_id);
                                    $icon_type = substr($icon, 0, 2);
                                    ?>
                                    <li>
                                        <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cat); ?>">
                                            <span class="<?php echo ('la' === $icon_type) ? $icon_type . ' ' . $icon : 'fa ' . $icon; ?>"
                                                  aria-hidden="true"></span>
                                            <p><?php echo $cat->name; ?></p>
                                        </a>
                                    </li>

                                <?php }
                                ?>
                            </ul>
                            <?php
                            do_action('atbdp_search_after_popular_categories');
                            ?>
                        </div><!-- End category area -->
                    <?php }
                } ?>
            </div>
        </div>
    </div><!-- end directory_main_area -->
</div><!-- end search area container -->
<!-- end search section -->

