<?php
$address_label               = get_directorist_option('address_label',__('Address','directorist'));
$fax_label                   = get_directorist_option('fax_label',__('Fax','directorist'));
$email_label                 = get_directorist_option('email_label',__('Email','directorist'));
$website_label               = get_directorist_option('website_label',__('Website','directorist'));
$tag_label                   = get_directorist_option('tag_label',__('Tag','directorist'));
$zip_label                   = get_directorist_option('zip_label',__('Zip','directorist'));
$listing_filters_icon        = get_directorist_option('listing_filters_icon',1);
$query_args = array(
    'parent'             => 0,
    'term_id'            => 0,
    'hide_empty'         => 0,
    'orderby'            => 'name',
    'order'              => 'asc',
    'show_count'         => 0,
    'single_only'        => 0,
    'pad_counts'         => true,
    'immediate_category' => 0,
    'active_term_id'     => 0,
    'ancestors'          => array()
);
$categories_fields = search_category_location_filter( $query_args, ATBDP_CATEGORY );
$locations_fields  = search_category_location_filter( $query_args, ATBDP_LOCATION );
$currency = get_directorist_option('g_currency', 'USD');
$c_symbol = atbdp_currency_symbol($currency);
if ($display_header == 'yes') { ?>
    <div class="atbd_header_bar">
        <div class="<?php echo !empty($header_container_fluid) ? $header_container_fluid : ''; ?>">
            <div class="row">
                <div class="col-md-12">

                    <div class="atbd_generic_header">
                        <?php
                        if ((!empty($listing_filters_button) && !empty($search_more_filters_fields)) || !empty($header_title)) { ?>
                            <div class="atbd_generic_header_title">
                                <?php if (!empty($listing_filters_button)) { ?>
                                    <a href="" class="more-filter btn btn-outline btn-outline-primary">
                                        <?php if(!empty($listing_filters_icon)) { ?>
                                        <span class="<?php atbdp_icon_type(true); ?>-filter"></span>
                                        <?php } ?>
                                        <?php echo $filters; ?>
                                    </a>
                                <?php }
                                /**
                                 * @since 5.4.0
                                 */
                                do_action('atbdp_after_filter_button_in_listings_header');
                                if (!empty($header_title)) {
                                    echo apply_filters('atbdp_total_listings_found_text',"<h3>{$header_title}</h3>", $header_title);
                                }
                                ?>
                            </div>
                            <?php
                        }
                        /**
                         * @since 5.4.0
                         */
                        do_action('atbdp_after_total_listing_found_in_listings_header', $header_title);

                         if ($display_viewas_dropdown || $display_sortby_dropdown) { ?>
                            <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                <!-- Views dropdown -->
                                <?php if ($display_viewas_dropdown) {
                                    $html = '<div class="atbd_dropdown">';
                                    $html .= '<a class="atbd_dropdown-toggle" href="#" id="dropdownMenuLink">
                                        ' . $view_as_text . '<span class="atbd_drop-caret"></span>
                                    </a>';
                                    $html .= '<div class="atbd_dropdown-menu" aria-labelledby="dropdownMenuLink">';
                                    $views = atbdp_get_listings_view_options($view_as_items);
                                    $view = !empty($view) ? $view : '';
                                    foreach ($views as $value => $label) {
                                        $active_class = ($view == $value) ? ' active' : '';
                                        $html .= sprintf('<a class="atbd_dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('view', $value), $label);

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
                                $sort_html = '';
                                if ($display_sortby_dropdown) {
                                    $sort_html .= '<div class="atbd_dropdown">
                                        <a class="atbd_dropdown-toggle" href="#"
                                           id="dropdownMenuLink2">' .
                                        $sort_by_text . ' <span class="atbd_drop-caret"></span>
                                        </a>';
                                    $sort_html .= '<div class="atbd_dropdown-menu atbd_dropdown-menu--lg"
                                             aria-labelledby="dropdownMenuLink2">';

                                    $options = atbdp_get_listings_orderby_options($sort_by_items);
                                    $sort_html .= '<form id="atbdp_sort" method="post" action="">';
                                    $current_order = !empty($current_order) ? $current_order : '';
                                    global $wp;
                                    $current_url =  home_url( $wp->request ) . '/';
                                    $pattern = '/page\\/[0-9]+\\//i';
                                    $actual_link = preg_replace($pattern, '', $current_url);
                                    foreach ($options as $value => $label) {
                                        $active_class = ($value == $current_order) ? ' active' : '';
                                        
                                        $sort_html .= sprintf('<a class="atbdp_sorting_item atbd_dropdown-item%s" data="%s">%s</a>', $active_class, add_query_arg( 'sort', $value, $actual_link ), $label);
                                    }
                                    $sort_html .= '</form>';
                                    $sort_html .= ' </div>';
                                    $sort_html .= ' </div>';
                                    /**
                                     * @since 5.4.0
                                     */
                                    echo apply_filters('atbdp_listings_header_sort_by_button', $sort_html);
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <!--ads advance search-->
                    <?php
                    $filters_display = !empty($filters_display) ? $filters_display : '';
                    $text_placeholder = !empty($text_placeholder) ? $text_placeholder : '';
                    $category_placeholder = !empty($category_placeholder) ? $category_placeholder : '';
                    $location_placeholder = !empty($location_placeholder) ? $location_placeholder : '';
                    $reset_filters_text = !empty($reset_filters_text) ? $reset_filters_text : 'Reset Filters';
                    $apply_filters_text = !empty($apply_filters_text) ? $apply_filters_text : 'Apply Filters';
                    ?>
                    <div class="<?php echo ('overlapping' === $filters_display) ? 'ads_float' : 'ads_slide' ?>">
                        <div class="ads-advanced">
                            <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form" class="atbd_ads-form">
                                <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border) ? ' style="border: none;"' : ''; ?>>
                                    <div class="row atbdp-search-form">
                                        <?php if (in_array('search_text', $search_more_filters_fields)) { ?>
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="single_search_field search_query">
                                                    <input class="form-control search_fields" type="text" name="q"
                                                           placeholder="<?php _e($text_placeholder, 'directorist'); ?>">
                                                </div>
                                            </div>
                                        <?php }
                                        if (in_array('search_category', $search_more_filters_fields)) {
                                            $slug = !empty($term_slug) ? $term_slug : '';
                                            $taxonomy_by_slug = get_term_by('slug', $slug, ATBDP_CATEGORY);
                                            if (!empty($taxonomy_by_slug)) {
                                                $taxonomy_id = $taxonomy_by_slug->term_taxonomy_id;
                                            }
                                            $selected = isset($_GET['in_cat']) ? $_GET['in_cat'] : -1;
                                            ?>
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="single_search_field search_category">
                                                    <select name="in_cat" id="cat-type" class="form-control directory_field bdas-category-search">
                                                        <option><?php echo $category_placeholder; ?></option>
                                                        <?php echo $categories_fields;?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php }
                                        if (in_array('search_location', $search_more_filters_fields)) {
                                            if('listing_location' == $listing_location_address) {
                                            $slug = !empty($term_slug) ? $term_slug : '';
                                            $location_by_slug = get_term_by('slug', $slug, ATBDP_LOCATION);
                                            if (!empty($location_by_slug)) {
                                                $location_id = $location_by_slug->term_taxonomy_id;
                                            }
                                            $loc_selected = isset($_GET['in_loc']) ? $_GET['in_loc'] : -1;
                                            ?>
                                            <div class="col-md-12 col-sm-12 col-lg-4">
                                                <div class="single_search_field search_location">
                                                    <select name="in_loc" id="loc-type" class="form-control directory_field bdas-category-location">
                                                        <option><?php echo $location_placeholder; ?></option>
                                                        <?php echo $locations_fields;?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php }else{
                                                $select_listing_map = get_directorist_option('select_listing_map','google');
                                                wp_enqueue_script('atbdp-geolocation');
                                                wp_localize_script('atbdp-geolocation', 'adbdp_geolocation', array('select_listing_map'=> $select_listing_map));
                                                $geo_loc = ('google' == $select_listing_map) ? '<span class="atbd_get_loc la la-crosshairs"></span>' : '<span class="atbd_get_loc la la-crosshairs"></span>';
                                                ?>
                                                <div class="col-md-6 col-sm-12 col-lg-4">
                                                    <div class="atbdp_map_address_field"><div class="atbdp_get_address_field">
                                                        <input type="text" name="address" id="address"
                                                               value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>"
                                                               placeholder="<?php echo !empty($location_placeholder) ? sanitize_text_field($location_placeholder) : __('location','directorist'); ?>"
                                                               autocomplete="off"
                                                               class="form-control location-name"><?php echo $geo_loc;?>
                                                        </div>
                                                        <div class="address_result" style="display: none">
                                                        </div>
                                                        <input type="hidden" id="cityLat" name="cityLat" value="<?php if (isset($_GET['cityLat'])) echo esc_attr($_GET['cityLat']); ?>" />
                                                        <input type="hidden" id="cityLng" name="cityLng" value="<?php if (isset($_GET['cityLng'])) echo esc_attr($_GET['cityLng']); ?>" />
                                                    </div>
                                                </div>
                                            <?php }
                                        } ?>
                                        <?php
                                        /**
                                         * @since 5.0
                                         */
                                        do_action('atbdp_search_field_after_location');

                                        ?>
                                    </div>
                                </div>
                                <?php if (in_array('search_price', $search_more_filters_fields) || in_array('search_price_range', $search_more_filters_fields)) { ?>
                                    <div class="form-group ">

                                        <label class=""><?php _e('Price Range', 'directorist'); ?></label>
                                        <div class="price_ranges">
                                            <?php if (in_array('search_price', $search_more_filters_fields)) { ?>
                                                <div class="range_single">
                                                    <input type="text" name="price[0]" class="form-control"
                                                           placeholder="<?php _e('Min Price', 'directorist'); ?>"
                                                           value="<?php if (isset($_GET['price'])) echo esc_attr($_GET['price'][0]); ?>">
                                                </div>
                                                <div class="range_single">
                                                    <input type="text" name="price[1]" class="form-control"
                                                           placeholder="<?php _e('Max Price', 'directorist'); ?>"
                                                           value="<?php if (isset($_GET['price'])) echo esc_attr($_GET['price'][1]); ?>">
                                                </div>
                                            <?php }
                                            if (in_array('search_price_range', $search_more_filters_fields)) { ?>
                                                <div class="price-frequency">
                                                    <label class="pf-btn"><input type="radio" name="price_range"
                                                                                 value="bellow_economy"<?php if (!empty($_GET['price_range']) && 'bellow_economy' == $_GET['price_range']) {
                                                            echo "checked='checked'";
                                                        } ?>><span><?php echo $c_symbol; ?></span></label>
                                                    <label class="pf-btn"><input type="radio" name="price_range"
                                                                                 value="economy" <?php if (!empty($_GET['price_range']) && 'economy' == $_GET['price_range']) {
                                                            echo "checked='checked'";
                                                        } ?>><span><?php echo $c_symbol,$c_symbol; ?></span></label>
                                                    <label class="pf-btn"><input type="radio" name="price_range"
                                                                                 value="moderate" <?php if (!empty($_GET['price_range']) && 'moderate' == $_GET['price_range']) {
                                                            echo "checked='checked'";
                                                        } ?>><span><?php echo $c_symbol,$c_symbol,$c_symbol; ?></span></label>
                                                    <label class="pf-btn"><input type="radio" name="price_range"
                                                                                 value="skimming" <?php if (!empty($_GET['price_range']) && 'skimming' == $_GET['price_range']) {
                                                            echo "checked='checked'";
                                                        } ?>><span><?php echo $c_symbol,$c_symbol,$c_symbol,$c_symbol; ?></span></label>
                                                </div>
                                            <?php } ?>
                                        </div>

                                    </div><!-- ends: .form-group -->
                                <?php } ?>
                                <?php if (in_array('search_rating', $search_more_filters_fields)) { ?>
                                    <div class="form-group">
                                        <label><?php _e('Filter by Ratings', 'directorist'); ?></label>
                                        <select name='search_by_rating' class="select-basic form-control">
                                            <option value=""><?php _e('Select Ratings', 'directorist'); ?></option>
                                            <option value="5" <?php if (!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) {
                                                echo "selected";
                                            } ?>><?php _e('5 Star', 'directorist'); ?></option>
                                            <option value="4" <?php if (!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) {
                                                echo "selected";
                                            } ?>><?php _e('4 Star & Up', 'directorist'); ?></option>
                                            <option value="3" <?php if (!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) {
                                                echo "selected";
                                            } ?>><?php _e('3 Star & Up', 'directorist'); ?></option>
                                            <option value="2" <?php if (!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) {
                                                echo "selected";
                                            } ?>><?php _e('2 Star & Up', 'directorist'); ?></option>
                                            <option value="1" <?php if (!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) {
                                                echo "selected";
                                            } ?>><?php _e('1 Star & Up', 'directorist'); ?></option>
                                        </select>
                                    </div><!-- ends: .form-group -->
                                <?php } ?>
                                <?php if ('map_api' == $listing_location_address && in_array('radius_search', $search_more_filters_fields)) {
                                    $default_radius_distance =  !empty($default_radius_distance) ? $default_radius_distance : 0;
                                    ?>
                                    <!--range slider-->
                                    <div class="form-group">
                                        <div class="atbdp-range-slider-wrapper">
                                            <span><?php _e('Radius Search', 'directorist'); ?></span>
                                            <div><div id="atbdp-range-slider"></div></div>
                                            <p class="atbd-current-value"></p>
                                        </div>
                                        <input type="hidden" class="atbdrs-value" name="miles" value="<?php echo $default_radius_distance;?>" />
                                    </div>
                                    <?php } ?>
                                <?php
                                if (in_array('search_open_now', $search_more_filters_fields) && in_array('directorist-business-hours/bd-business-hour.php', apply_filters('active_plugins', get_option('active_plugins')))) { ?>
                                    <div class="form-group">
                                        <label><?php _e('Open Now', 'directorist'); ?></label>
                                        <div class="check-btn">
                                            <div class="btn-checkbox">
                                                <label>
                                                    <input type="checkbox" name="open_now"
                                                           value="open_now" <?php if (!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) {
                                                        echo "checked='checked'";
                                                    } ?>>
                                                    <span><i class="fa fa-clock-o"></i><?php _e('Open Now', 'directorist'); ?> </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- ends: .form-group -->
                                <?php }
                                if (in_array('search_tag', $search_more_filters_fields)) {
                                    $listing_tags_field = get_directorist_option('listing_tags_field','all_tags');
                                    $category_slug = get_query_var('atbdp_category');
                                    $category = get_term_by('slug', $category_slug,ATBDP_CATEGORY);
                                    $category_id = !empty($category_slug) ? $category->term_id : '';
                                    $category_select = !empty($_GET['in_cat']) ? $_GET['in_cat'] : $category_id;

                                    $tag_args = array(
                                        'post_type'=> ATBDP_POST_TYPE,
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => ATBDP_CATEGORY,
                                                'terms'    => $category_select,
                                            )
                                        )
                                    );

                                    $tag_posts = ATBDP_Listings_Model::get_listings( $tag_args );

                                    if(!empty($tag_posts)) {
                                        foreach ($tag_posts as $tag_post) {
                                            $tag_id[] = $tag_post->ID;
                                        }
                                    }
                                    $tag_id = !empty($tag_id) ? $tag_id : '';

                                    
                                    if ( 'all_tags' == $listing_tags_field || empty( $category_select ) ) {
                                        $terms = ATBDP_Terms_Model::get_tags_term();
                                    } else {
                                        $terms  = wp_get_object_terms( $tag_id, ATBDP_TAGS );
                                    }

                                    if (!empty($terms)) {
                                        ?>
                                        <div class="form-group ads-filter-tags">
                                            <label><?php echo !empty($tag_label) ? $tag_label : __('Tags','directorist'); ?></label>
                                            <div class="bads-tags">
                                                <?php
                                                $rand = rand();
                                                foreach ($terms as $term) {
                                                    ?>
                                                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                                        <input type="checkbox" class="custom-control-input"
                                                               name="in_tag[]" value="<?php echo $term->term_id; ?>"
                                                               id="<?php echo $rand . $term->term_id; ?>" <?php if (!empty($_GET['in_tag']) && in_array($term->term_id,$_GET['in_tag'])) {
                                                            echo "checked";
                                                        } ?>>
                                                        <span class="check--select"></span>
                                                        <label for="<?php echo $rand . $term->term_id; ?>"
                                                               class="custom-control-label"><?php echo $term->name; ?></label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <a href="#"
                                               class="more-less ad"><?php _e('Show More', 'directorist'); ?></a>
                                        </div><!-- ends: .form-control -->
                                    <?php }
                                }
                                if (in_array('search_custom_fields', $search_more_filters_fields)) { ?>
                                    <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                                        <?php do_action('wp_ajax_atbdp_custom_fields_search', isset($_GET['in_cat']) ? $_GET['in_cat'] : 0); ?>
                                    </div>
                                <?php } ?>
                                <?php if (in_array('search_website', $search_more_filters_fields) || in_array('search_email', $search_more_filters_fields) || in_array('search_phone', $search_more_filters_fields) || in_array('search_address', $search_more_filters_fields) || in_array('search_zip_code', $search_more_filters_fields)) { ?>
                                    <div class="form-group">
                                        <div class="bottom-inputs">
                                            <?php if (in_array('search_website', $search_more_filters_fields)) { ?>
                                                <div>
                                                    <input type="text" name="website"
                                                           placeholder="<?php echo !empty($website_label) ? $website_label : __('Website','directorist'); ?>"
                                                           value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>"
                                                           class="form-control">
                                                </div>
                                            <?php }
                                            if (in_array('search_email', $search_more_filters_fields)) { ?>
                                                <div>
                                                    <input type="text" name="email"
                                                           placeholder="<?php echo !empty($email_label) ? $email_label : __('Email','directorist'); ?>"
                                                           value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>"
                                                           class="form-control">
                                                </div>
                                            <?php }
                                            if (in_array('search_phone', $search_more_filters_fields)) { ?>
                                                <div>
                                                    <input type="text" name="phone"
                                                           placeholder="<?php _e('Phone Number', 'directorist'); ?>"
                                                           value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>"
                                                           class="form-control">
                                                </div>
                                            <?php }
                                            if (in_array('search_fax', $search_more_filters_fields)) { ?>
                                                <div>
                                                    <input type="text" name="fax"
                                                           placeholder="<?php echo !empty($fax_label) ? $fax_label : __('Fax','directorist'); ?>"
                                                           value="<?php echo !empty($_GET['fax']) ? $_GET['fax'] : ''; ?>"
                                                           class="form-control">
                                                </div>
                                            <?php }
                                            if (in_array('search_zip_code', $search_more_filters_fields)) { ?>
                                                <div>
                                                    <input type="text" name="zip_code"
                                                           placeholder="<?php echo !empty($zip_label) ? $zip_label : __('Zip/Post Code','directorist'); ?>"
                                                           value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>"
                                                           class="form-control">
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="bdas-filter-actions">
                                    <?php if (in_array('reset_button', $filters_button)) { ?>
                                        <a href=""
                                                class="btn btn-outline btn-outline-primary btn-sm" id="atbdp_reset"><?php _e($reset_filters_text, 'directorist'); ?></a>
                                    <?php }
                                    if (in_array('apply_button', $filters_button)) { ?>
                                        <button type="submit"
                                                class="btn btn-primary btn-sm"><?php _e($apply_filters_text, 'directorist'); ?></button>
                                    <?php } ?>

                                </div><!-- ends: .bdas-filter-actions -->
                            </form>
                        </div> <!--ads advanced -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
