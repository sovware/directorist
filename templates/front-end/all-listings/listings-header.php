<?php if( $display_header == 'yes'  ) { ?>
    <div class="header_bar">
        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if(!empty($header_title) && !empty($listing_filters_button) && !empty($search_more_filters_fields)) {?>
                        <h3 class="header_bar_title">
                            <?php echo $header_title; ?>
                        </h3>
                    <?php } ?>
                    <div class="atbd_generic_header">
                        <?php
                        if(!empty($listing_filters_button) && !empty($search_more_filters_fields)) {?>
                            <div class="atbd_generic_header_title">
                                <button class="more-filter btn btn-outline btn-outline-primary"><span class="fa fa-filter"></span> <?php echo $filters;?></button>
                            </div>
                        <?php } elseif((!empty($header_title) && empty($listing_filters_button)) || empty($search_more_filters_fields)) { ?>
                            <h3>
                                <?php echo $header_title; ?>
                            </h3>
                        <?php } ?>
                        <?php if ($display_viewas_dropdown || $display_sortby_dropdown) { ?>
                            <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                <!-- Views dropdown -->
                                <?php if ($display_viewas_dropdown) {
                                    $html = '<div class="dropdown">';
                                    $html .= '<a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . __("View as", ATBDP_TEXTDOMAIN) . '<span class="caret"></span>
                                    </a>';
                                    $html .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                                    $views = atbdp_get_listings_view_options($view_as_items);
                                    $view = !empty($view)?$view:'';
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

                                            $current_order = !empty($current_order)?$current_order:'';
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
                    <?php
                    $filters_display = !empty($filters_display)?$filters_display:'';
                    $text_placeholder = !empty($text_placeholder)?$text_placeholder:'';
                    $category_placeholder = !empty($category_placeholder)?$category_placeholder:'';
                    $location_placeholder = !empty($location_placeholder)?$location_placeholder:'';
                    $reset_filters_text   = !empty($reset_filters_text)?$reset_filters_text:'Reset Filters';
                    $apply_filters_text   = !empty($apply_filters_text)?$apply_filters_text:'Apply Filters';
                    ?>
                    <div class="<?php echo ('overlapping' === $filters_display)?'ads_float':'ads_slide'?>">
                        <div class="ads-advanced">
                            <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
                                <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border)?'style="border: none;"':'';?>>
                                    <div class="row atbdp-search-form">
                                        <?php   if(in_array( 'search_text', $search_more_filters_fields )) { ?>
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="single_search_field search_query">
                                                    <input class="form-control search_fields" type="text" name="q"
                                                           placeholder="<?php _e($text_placeholder, ATBDP_TEXTDOMAIN); ?>">
                                                </div>
                                            </div>
                                        <?php } if(in_array( 'search_category', $search_more_filters_fields )) {
                                            $slug = !empty($term_slug) ? $term_slug : '';
                                            $taxonomy_by_slug = get_term_by('slug',$slug,ATBDP_CATEGORY);
                                            if(!empty($taxonomy_by_slug)){
                                                $taxonomy_id = $taxonomy_by_slug->term_taxonomy_id;
                                            }
                                            $selected = isset( $_GET['in_cat'] ) ?  $_GET['in_cat'] : -1;
                                            ?>
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="single_search_field search_category">
                                                    <?php
                                                    $args = array(
                                                        'show_option_none' =>  __($category_placeholder, ATBDP_TEXTDOMAIN),
                                                        'taxonomy' => ATBDP_CATEGORY,
                                                        'id' => 'cat-type',
                                                        'option_none_value'  => '',
                                                        'class' => 'form-control directory_field bdas-category-search',
                                                        'name' => 'in_cat',
                                                        'orderby' => 'name',
                                                        'selected' => !empty($taxonomy_id) ? $taxonomy_id : $selected,
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
                                        <?php } if(in_array( 'search_location', $search_more_filters_fields )) {
                                            $slug = !empty($term_slug) ? $term_slug : '';
                                            $location_by_slug = get_term_by('slug',$slug,ATBDP_LOCATION);
                                            if(!empty($location_by_slug)){
                                                $location_id = $location_by_slug->term_taxonomy_id;
                                            }
                                            $loc_selected = isset( $_GET['in_loc'] ) ?  $_GET['in_loc'] : -1;
                                            ?>
                                            <div class="col-md-12 col-sm-12 col-lg-4">
                                                <div class="single_search_field search_location">
                                                    <?php
                                                    $args = array(
                                                        'show_option_none' =>  __($location_placeholder, ATBDP_TEXTDOMAIN),
                                                        'taxonomy' => ATBDP_LOCATION,
                                                        'id' => 'cat-type',
                                                        'option_none_value'  => '',
                                                        'class' => 'form-control directory_field',
                                                        'name' => 'in_loc',
                                                        'orderby' => 'name',
                                                        'selected' => !empty($location_id) ? $location_id : $loc_selected,
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
                                        <?php } ?>
                                        <?php
                                        /**
                                         * @since 5.0
                                         */
                                        do_action('atbdp_search_field_after_location');

                                        ?>
                                    </div>
                                </div>
                                <?php if(in_array( 'search_price', $search_more_filters_fields) || in_array( 'search_price_range', $search_more_filters_fields) ) { ?>
                                    <div class="form-group ">

                                        <label class=""><?php _e('Price Range', ATBDP_TEXTDOMAIN);?></label>
                                        <div class="price_ranges">
                                            <?php if(in_array( 'search_price', $search_more_filters_fields)) { ?>
                                                <div class="range_single">
                                                    <input type="text" name="price[0]" class="form-control" placeholder="<?php _e('Min Price', ATBDP_TEXTDOMAIN);?>" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
                                                </div>
                                                <div class="range_single">
                                                    <input type="text" name="price[1]" class="form-control" placeholder="<?php _e('Max Price', ATBDP_TEXTDOMAIN);?>" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                                                </div>
                                            <?php } if(in_array( 'search_price_range', $search_more_filters_fields )) {?>
                                                <div class="price-frequency">
                                                    <label class="pf-btn"><input type="radio" name="price_range" value="bellow_economy"<?php if(!empty($_GET['price_range']) && 'bellow_economy' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$</span></label>
                                                    <label class="pf-btn"><input type="radio" name="price_range" value="economy" <?php if(!empty($_GET['price_range']) && 'economy' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$</span></label>
                                                    <label class="pf-btn"><input type="radio" name="price_range" value="moderate" <?php if(!empty($_GET['price_range']) && 'moderate' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$$</span></label>
                                                    <label class="pf-btn"><input type="radio" name="price_range" value="skimming" <?php if(!empty($_GET['price_range']) && 'skimming' == $_GET['price_range']) { echo "checked='checked'";}?>><span>$$$$</span></label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div><!-- ends: .form-group -->
                                <?php } ?>
                                <?php if(in_array( 'search_rating', $search_more_filters_fields )) { ?>
                                    <div class="form-group">
                                        <label><?php _e('Filter by Ratings', ATBDP_TEXTDOMAIN);?></label>
                                        <select name='search_by_rating' class="select-basic form-control">
                                            <option value=""><?php _e('Select Ratings', ATBDP_TEXTDOMAIN);?></option>
                                            <option  value="5" <?php if(!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('5 Star', ATBDP_TEXTDOMAIN);?></option>
                                            <option value="4" <?php if(!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('4 Star & Up', ATBDP_TEXTDOMAIN);?></option>
                                            <option value="3" <?php if(!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('3 Star & Up', ATBDP_TEXTDOMAIN);?></option>
                                            <option value="2" <?php if(!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('2 Star & Up', ATBDP_TEXTDOMAIN);?></option>
                                            <option value="1" <?php if(!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('1 Star & Up', ATBDP_TEXTDOMAIN);?></option>
                                        </select>
                                    </div><!-- ends: .form-group -->
                                <?php } if(in_array( 'search_open_now', $search_more_filters_fields ) && in_array( 'directorist-business-hours/bd-business-hour.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) { ?>
                                    <div class="form-group">
                                        <label><?php _e('Open Now', ATBDP_TEXTDOMAIN);?></label>
                                        <div class="check-btn">
                                            <div class="btn-checkbox">
                                                <label>
                                                    <input type="checkbox" name="open_now" value="open_now" <?php if(!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) { echo "checked='checked'";}?>>
                                                    <span><i class="fa fa-clock-o"></i><?php _e('Open Now', ATBDP_TEXTDOMAIN);?> </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- ends: .form-group -->
                                <?php } if(in_array( 'search_tag', $search_more_filters_fields )) {
                                    $terms = get_terms(ATBDP_TAGS);
                                    if(!empty($terms)) {
                                        ?>
                                        <div class="form-group ads-filter-tags">
                                            <label><?php _e('Tags', ATBDP_TEXTDOMAIN);?></label>
                                            <div class="bads-tags">
                                                <?php
                                                $rand = rand();
                                                foreach($terms as $term) {
                                                    ?>
                                                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                                        <input type="checkbox" class="custom-control-input" name="in_tag" value="<?php echo $term->term_id;?>" id="<?php echo $rand . $term->term_id;?>" <?php if(!empty($_GET['in_tag']) && $term->term_id == $_GET['in_tag']) { echo "checked";}?>>
                                                        <span class="check--select"></span>
                                                        <label for="<?php echo $rand . $term->term_id;?>" class="custom-control-label"><?php echo $term->name;?></label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <a href="#" class="more-less ad"><?php _e('Show More', ATBDP_TEXTDOMAIN);?></a>
                                        </div><!-- ends: .form-control -->
                                    <?php } } if(in_array( 'search_custom_fields', $search_more_filters_fields )) { ?>
                                    <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                                        <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ?  $_GET['in_cat'] : 0 ); ?>
                                    </div>
                                <?php } ?>
                                <?php if(in_array( 'search_website', $search_more_filters_fields ) || in_array( 'search_email', $search_more_filters_fields ) || in_array( 'search_phone', $search_more_filters_fields ) || in_array( 'search_address', $search_more_filters_fields ) || in_array( 'search_zip_code', $search_more_filters_fields )) {?>
                                    <div class="form-group">
                                        <div class="bottom-inputs">
                                            <?php if(in_array( 'search_website', $search_more_filters_fields )) {?>
                                                <div>
                                                    <input type="text" name="website" placeholder="<?php _e('Website', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
                                                </div>
                                            <?php } if(in_array( 'search_email', $search_more_filters_fields )) {?>
                                                <div>
                                                    <input type="text" name="email" placeholder=" <?php _e('Email', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
                                                </div>
                                            <?php } if(in_array( 'search_phone', $search_more_filters_fields )) {?>
                                                <div>
                                                    <input type="text" name="phone" placeholder="<?php _e('Phone Number', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
                                                </div>
                                            <?php } if(in_array( 'search_address', $search_more_filters_fields )) {?>
                                                <div>
                                                    <input type="text" name="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="<?php _e('Address', ATBDP_TEXTDOMAIN);?>"
                                                           class="form-control location-name">
                                                </div>
                                            <?php } if(in_array( 'search_zip_code', $search_more_filters_fields )) {?>
                                                <div>
                                                    <input type="text" name="zip_code" placeholder=" <?php _e('Zip/Post Code', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="bdas-filter-actions">
                                    <?php if(in_array( 'reset_button', $filters_button )) { ?>
                                        <button type="reset" class="btn btn-outline btn-outline-primary btn-sm"><?php _e($reset_filters_text, ATBDP_TEXTDOMAIN);?></button>
                                    <?php } if(in_array( 'apply_button', $filters_button )) { ?>
                                        <button type="submit" class="btn btn-primary btn-sm"><?php _e($apply_filters_text, ATBDP_TEXTDOMAIN);?></button>
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