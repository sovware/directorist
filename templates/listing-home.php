<?php
$categories                           = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
$locations                            = get_terms(ATBDP_LOCATION, array('hide_empty' => 0));
// get search page title and sub title from the plugin settings page
$search_title                         = get_directorist_option('search_title', '');
$search_subtitle                      = get_directorist_option('search_subtitle', '');
$search_placeholder                   = get_directorist_option('search_placeholder', __('What are you looking for?', ATBDP_TEXTDOMAIN));
$search_category_placeholder          = get_directorist_option('search_category_placeholder', __('Select a category', ATBDP_TEXTDOMAIN));
$search_location_placeholder          = get_directorist_option('search_location_placeholder', __('Select a location', ATBDP_TEXTDOMAIN));
$show_popular_category       = get_directorist_option('show_popular_category', 1);
$show_connector              = get_directorist_option('show_connector', 1);
$search_border               = get_directorist_option('search_border', 1);

$connectors_title            = get_directorist_option('connectors_title', __('Or', ATBDP_TEXTDOMAIN));
$popular_cat_title           = get_directorist_option('popular_cat_title', __('Browse by popular categories', ATBDP_TEXTDOMAIN));
$popular_cat_num             = get_directorist_option('popular_cat_num', 10);
$display_category_field      = get_directorist_option('display_category_field', 1);
$display_location_field      = get_directorist_option('display_location_field', 1);
$display_text_field          = get_directorist_option('display_text_field', 1);
$search_listing_text          = get_directorist_option('search_listing_text',  __('Search Listing', ATBDP_TEXTDOMAIN));

$default                     = get_template_directory_uri().'/images/home_page_bg.jpg';
$theme_home_bg_image         = get_theme_mod('directoria_home_bg');
$search_home_bg              = get_directorist_option('search_home_bg');
$display_more_filter_search  = get_directorist_option('search_more_filter',1);
$search_price                = get_directorist_option('search_price',1);
$search_price_range          = get_directorist_option('search_price_range',0);
$search_rating               = get_directorist_option('search_rating',1);
$search_open_now             = get_directorist_option('search_open_now',0);
$search_custom_field         = get_directorist_option('search_custom_field',1);
$search_tag                  = get_directorist_option('search_tag',1);
$search_website              = get_directorist_option('search_website',1);
$search_email                = get_directorist_option('search_email',1);
$search_phone                = get_directorist_option('search_phone',1);
$search_adderess             = get_directorist_option('search_adderess',1);
$search_zip_code             = get_directorist_option('search_zip_code',1);
$search_reset_button         = get_directorist_option('search_reset_button',1);
$search_apply_button         = get_directorist_option('search_apply_button',1);
$search_fields               = get_directorist_option('search_tsc_fields',array('search_text','search_category','search_location'));
$search_filters              = get_directorist_option('search_filters',array('search_reset_filters','search_apply_filters'));
$search_more_filters_fields               = get_directorist_option('search_more_filters_fields',array('search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
$front_bg_image              = (!empty($theme_home_bg_image)) ? $theme_home_bg_image : $search_home_bg;
wp_enqueue_style( 'atbdp-search-style', ATBDP_PUBLIC_ASSETS . 'css/search-style.css');
?>
<!-- start search section -->
<div id="directorist" class="directorist atbd_wrapper directory_search_area single_area ads-advaced--wrapper"
     style="background-image: url('<?php echo (!empty($front_bg_image)) ? esc_url($front_bg_image) : esc_url($default); ?>')">
    <!-- start search area container -->
    <div class="<?php echo is_directoria_active() ? 'container' : 'container-fluid'; ?>">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($search_title) || !empty($search_subtitle)) { ?>
                    <div class="atbd_search_title_area">
                        <?php echo !empty($search_title) ? '<h2 class="title">' . esc_html($search_title) . '</h2>' : ''; ?>
                        <?php echo !empty($search_subtitle) ? '<p class="sub_title">' . esc_html($search_subtitle) . '</p>' : ''; ?>
                    </div><!--- end title area -->
                <?php } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- start search area -->
                <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
                    <!-- @todo; if the input fields break in different themes, use bootstrap form inputs then -->
                    <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border)?'style="border: none;"':'';?>>
                        <div class="row atbdp-search-form">
                            <?php

                            if(in_array( 'search_text', $search_fields )) {?>
                            <div class="col-md-6 col-sm-12 col-lg-4">
                                <div class="single_search_field search_query">
                                    <input class="form-control search_fields" type="text" name="q"
                                           placeholder="<?php echo esc_html($search_placeholder); ?>">
                                </div>
                            </div>
                            <?php } ?>
                            <?php if(in_array( 'search_category', $search_fields )) { ?>
                            <div class="col-md-6 col-sm-12 col-lg-4">
                                <div class="single_search_field search_category">
                                    <?php
                                    $args = array(
                                        'show_option_none' =>  $search_category_placeholder,
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
                            <?php }
                            if(in_array( 'search_location', $search_fields )) { ?>
                            <div class="col-md-12 col-sm-12 col-lg-4">
                                <div class="single_search_field search_location">
                                    <?php
                                    $args = array(
                                        'show_option_none' =>  $search_location_placeholder,
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
                            <?php }
                            /**
                             * @since 5.0
                             */
                            do_action('atbdp_search_field_after_location');

                            ?>
                        </div>
                    </div>

                    <!--More Filters  & Search Button-->
                        <?php
                        $html = '<div class="atbd_submit_btn_wrapper">';
                        if(!empty($display_more_filter_search)) {
                            $html .= '<button class="more-filter btn btn-outline btn-lg btn-outline-primary"><span class="fa fa-filter"></span>'.__('More Filters', ATBDP_TEXTDOMAIN).'</button>';
                             }
                        $html .= '<div class="atbd_submit_btn">';
                        $html .= '<button type="submit" class="btn btn-primary btn-lg btn_search">';
                        $html .= '<span class="fa fa-search"></span>'.__($search_listing_text, ATBDP_TEXTDOMAIN).'';
                        $html .= '</button>';
                        $html .= '</div>';
                        $html .= '</div>';
                        
                        /**
                         * @since 5.0
                         * It show the search button
                         */
                        echo apply_filters('atbdp_search_listing_button', $html);
                        ?>


                    <?php if(!empty($display_more_filter_search)) {?>
                    <!--ads advance search-->
                        <div class="ads_float">
                            <div class="ads-advanced">
                                <?php if(in_array( 'search_price', $search_more_filters_fields) ||in_array( 'search_price_range', $search_more_filters_fields) ) { ?>
                                    <div class="form-group ">
                                        <label class=""><?php _e('Price Range', ATBDP_TEXTDOMAIN);?></label>
                                        <div class="price_ranges">
                                            <?php if(in_array( 'search_price', $search_more_filters_fields )) { ?>
                                                <div class="range_single">
                                                    <input type="text" name="price[0]" class="form-control" placeholder="Min Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
                                                </div>
                                                <div class="range_single">
                                                    <input type="text" name="price[1]" class="form-control" placeholder="Max Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                                                </div>
                                            <?php } ?>
                                            <?php if(in_array( 'search_price_range', $search_more_filters_fields )) { ?>
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
                                        <select class="select-basic form-control">
                                            <option value=""><?php _e('Select Ratings', ATBDP_TEXTDOMAIN);?></option>
                                            <option name='search_by_rating' value="5" <?php if(!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>5 Star</option>
                                            <option name='search_by_rating' value="4" <?php if(!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>4 Star & Up</option>
                                            <option name='search_by_rating' value="3" <?php if(!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>3 Star & Up</option>
                                            <option name='search_by_rating' value="2" <?php if(!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>2 Star & Up</option>
                                            <option name='search_by_rating' value="1" <?php if(!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) { echo "checked='checked'";}?>>1 Star & Up</option>
                                        </select>
                                    </div><!-- ends: .form-group -->
                                <?php } ?>
                                <?php if(in_array( 'search_open_now', $search_more_filters_fields ) && in_array( 'directorist-business-hours/bd-business-hour.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) { ?>
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
                                <?php } ?>
                                <?php if(in_array( 'search_tag', $search_more_filters_fields )) {?>
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
                                <?php } ?>
                                <?php if(in_array( 'search_custom_fields', $search_more_filters_fields )) {?>
                                    <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                                        <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ? (int) $_GET['in_cat'] : 0 ); ?>
                                    </div>
                                <?php } ?>
                                <?php if(in_array( 'search_website', $search_more_filters_fields ) || in_array( 'search_email', $search_more_filters_fields ) || in_array( 'search_phone', $search_more_filters_fields ) || in_array( 'search_address', $search_more_filters_fields ) || in_array( 'search_zip_code', $search_more_filters_fields )) {?>
                                    <div class="form-group">
                                        <div class="bottom-inputs">
                                            <div>
                                                <?php if(in_array( 'search_website', $search_more_filters_fields )) {?>
                                                <input type="text" name="website" placeholder="<?php _e('Website', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
                                            </div>
                                            <div>
                                                <?php } if(in_array( 'search_email', $search_more_filters_fields )) {?>
                                                <input type="text" name="email" placeholder=" <?php _e('Email', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
                                            </div>
                                            <div>
                                                <?php } if(in_array( 'search_phone', $search_more_filters_fields )) {?>
                                                <input type="text" name="phone" placeholder="<?php _e('Phone Number', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
                                            </div>
                                            <div>
                                                <?php } if(in_array( 'search_address', $search_more_filters_fields )) {?>
                                                <input type="text" name="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="<?php _e('Address', ATBDP_TEXTDOMAIN);?>"
                                                       class="form-control location-name">
                                            </div>
                                            <div>
                                                <?php } if(in_array( 'search_zip_code', $search_more_filters_fields )) {?>
                                                <input type="text" name="zip_code" placeholder=" <?php _e('Zip/Post Code', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if(in_array( 'search_reset_filters', $search_filters ) || in_array( 'search_apply_filters', $search_filters )) {?>
                                    <div class="bdas-filter-actions">
                                        <?php if(in_array( 'search_reset_filters', $search_filters )) { ?>
                                            <a href="<?php echo get_permalink();?>" class="btn btn-outline-primary btn-sm"><?php _e('Reset Filters', ATBDP_TEXTDOMAIN);?></a>
                                        <?php } if(in_array( 'search_apply_filters', $search_filters )) {?>
                                            <button type="submit" class="btn btn-primary btn-sm"><?php _e('Apply Filters', ATBDP_TEXTDOMAIN);?></button>
                                        <?php } ?>
                                    </div><!-- ends: .bdas-filter-actions -->
                                <?php } ?>
                            </div> <!--ads advanced -->
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>


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
                    $top_categories = get_categories($args); // do not show any markup if we do not have any category at all.
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

                            <ul class="categories">
                                <?php
                                foreach ($top_categories as $cat) { ?>
                                    <li>
                                        <a href="<?= ATBDP_Permalink::atbdp_get_category_page($cat); ?>">
                                            <span class="fa <?= get_cat_icon($cat->term_id); ?>"
                                                  aria-hidden="true"></span>
                                            <p><?= $cat->name; ?></p>
                                        </a>
                                    </li>

                                <?php }
                                ?>
                            </ul>
                        </div><!-- End category area -->
                    <?php }
                } ?>
            </div>
        </div>
    </div><!-- end directory_main_area -->
</div><!-- end search area container -->
<!-- end search section -->

