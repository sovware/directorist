<?php
$categories                           = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
$locations                            = get_terms(ATBDP_LOCATION, array('hide_empty' => 0));
$search_placeholder                   = get_directorist_option('search_placeholder', __('What are you looking for?', ATBDP_TEXTDOMAIN));
$search_category_placeholder          = get_directorist_option('search_category_placeholder', __('Select a category', ATBDP_TEXTDOMAIN));
$search_location_placeholder          = get_directorist_option('search_location_placeholder', __('Select a location', ATBDP_TEXTDOMAIN));
$show_popular_category       = get_directorist_option('show_popular_category', 1);
$show_connector              = get_directorist_option('show_connector', 1);
$search_border               = get_directorist_option('search_border', 1);

$connectors_title            = get_directorist_option('connectors_title', __('Or', ATBDP_TEXTDOMAIN));
$popular_cat_title           = get_directorist_option('popular_cat_title', __('Browse by popular categories', ATBDP_TEXTDOMAIN));
$popular_cat_num             = get_directorist_option('popular_cat_num', 10);


$default                     = get_template_directory_uri().'/images/home_page_bg.jpg';
$theme_home_bg_image         = get_theme_mod('directoria_home_bg');
$search_home_bg              = get_directorist_option('search_home_bg');
$display_more_filter_search  = get_directorist_option('search_more_filter',1);
$search_filters              = get_directorist_option('search_filters',array('reset_button','apply_button'));
$search_more_filters_fields  = get_directorist_option('search_more_filters_fields',array('search_price','search_price_range','search_rating','search_tag','search_custom_fields'));
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
                <?php
                if (!empty($search_bar_title || $search_bar_sub_title) && (!empty($show_title_subtitle))) { ?>
                    <div class="atbd_search_title_area">
                        <?php echo !empty($search_bar_title) ? '<h2 class="title">' . esc_html($search_bar_title) . '</h2>' : ''; ?>
                        <?php echo !empty($search_bar_sub_title) ? '<p class="sub_title">' . esc_html($search_bar_sub_title) . '</p>' : ''; ?>
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
                        <?php if('yes' == $text_field || 'yes' == $category_field || 'yes' == $location_field) { ?>
                            <div class="row atbdp-search-form">
                                <?php
                                $search_html = '';
                                if('yes' == $text_field) {
                                    $search_html .= '<div class="col-md-6 col-sm-12 col-lg-4">';

                                    $search_html .= '<div class="single_search_field search_query">
                                    <input class="form-control search_fields" type="text" name="q"
                                           placeholder="'. esc_html($search_placeholder).'">
                                </div>';
                                    $search_html .= '</div>';
                                }
                                if('yes' == $category_field) {
                                    $search_html .= '<div class="col-md-6 col-sm-12 col-lg-4">
                                <div class="single_search_field search_category">';
                                    $args = array(
                                        'show_option_none' =>  $search_category_placeholder,
                                        'taxonomy' => ATBDP_CATEGORY,
                                        'id' => 'cat-type',
                                        'option_none_value'  => '',
                                        'class' => 'form-control directory_field bdas-category-search',
                                        'name' => 'in_cat',
                                        'orderby' => 'name',
                                        'selected' => isset( $_GET['in_cat'] ) ? $_GET['in_cat'] : -1,
                                        'hierarchical' => true,
                                        'value_field'  => 'term_id',
                                        'depth' => 10,
                                        'show_count' => false,
                                        'hide_empty' => false,
                                        'echo' => false,
                                    );
                                    $search_html .= wp_dropdown_categories($args);
                                    $search_html .= '</div></div>';
                                }
                                if('yes' == $location_field) {
                                    $search_html .= '<div class="col-md-12 col-sm-12 col-lg-4">
                                <div class="single_search_field search_location">';
                                    $args = array(
                                        'show_option_none' =>  $search_location_placeholder,
                                        'taxonomy' => ATBDP_LOCATION,
                                        'id' => 'loc-type',
                                        'option_none_value'  => '',
                                        'class' => 'form-control directory_field',
                                        'name' => 'in_loc',
                                        'orderby' => 'name',
                                        'selected' => isset( $_GET['in_loc'] ) ? $_GET['in_loc'] : -1,
                                        'hierarchical' => true,
                                        'value_field'  => 'term_id',
                                        'depth' => 10,
                                        'show_count' => false,
                                        'hide_empty' => false,
                                        'echo' => false,
                                    );

                                    $search_html .= wp_dropdown_categories($args);

                                    $search_html .= ' </div></div>';
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
                    $html = '<div class="atbd_submit_btn_wrapper">';

                    if(('yes' == $more_filters_button) && ('yes' == $price_min_max_field || 'yes' == $price_range_field || 'yes' == $rating_field || 'yes' == $tag_field || 'yes' == $open_now_field || 'yes' == $custom_fields || 'yes' == $website_field || 'yes' == $email_field || 'yes' == $phone_field || 'yes' == $address_field || 'yes' == $zip_code_field)) {
                        $html .= '<button class="more-filter btn btn-outline btn-lg btn-outline-primary"><span class="fa fa-filter"></span>'.__($more_filters_text, ATBDP_TEXTDOMAIN).'</button>';
                    }
                    $html .= '<div class="atbd_submit_btn">';
                    $html .= '<button type="submit" class="btn btn-primary btn-lg btn_search">';
                    $html .= '<span class="fa fa-search"></span>'.__($search_button_text, ATBDP_TEXTDOMAIN).'';
                    $html .= '</button>';
                    $html .= '</div>';
                    $html .= '</div>';

                    /**
                     * @since 5.0
                     * It show the search button
                     */
                    echo apply_filters('atbdp_search_listing_button', $html);
                    if(!empty($display_more_filter_search)) {?>
                        <!--ads advance search-->
                        <?php
                        $filters_display = !empty($filters_display)?$filters_display:'';
                        ?>
                        <div class="<?php echo ('overlapping' === $filters_display)?'ads_float':'ads_slide'?>">
                            <div class="ads-advanced">
                                <?php if('yes' == $price_min_max_field || 'yes' == $price_range_field)  { ?>
                                    <div class="form-group ">
                                        <label class=""><?php _e('Price Range', ATBDP_TEXTDOMAIN);?></label>
                                        <div class="price_ranges">
                                            <?php if('yes' == $price_min_max_field) { ?>
                                                <div class="range_single">
                                                    <input type="text" name="price[0]" class="form-control" placeholder="Min Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
                                                </div>
                                                <div class="range_single">
                                                    <input type="text" name="price[1]" class="form-control" placeholder="Max Price" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                                                </div>
                                            <?php } ?>
                                            <?php if('yes' == $price_range_field) { ?>
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
                                <?php if('yes' == $rating_field) { ?>
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
                                <?php } ?>
                                <?php if('yes' == $open_now_field && in_array( 'directorist-business-hours/bd-business-hour.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) { ?>
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
                                <?php } ?>
                                <?php if('yes' == $tag_field) {
                                    $terms = get_terms(ATBDP_TAGS);
                                    if(!empty($terms)) {
                                        ?>
                                        <div class="form-group ads-filter-tags">
                                            <label><?php _e('Tags', ATBDP_TEXTDOMAIN);?></label>
                                            <div class="bads-custom-checks">
                                                <?php
                                                $rand = rand();
                                                foreach($terms as $term) {

                                                    ?>
                                                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                                        <input type="checkbox" class="custom-control-input" name="in_tag" value="<?php echo $term->term_id;?>" id="<?php echo $rand . $term->term_id;?>">
                                                        <span class="check--select"></span>
                                                        <label for="<?php echo $rand . $term->term_id;?>" class="custom-control-label"><?php echo $term->name;?></label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <a href="#" class="more-or-less sml"><?php _e('Show More', ATBDP_TEXTDOMAIN);?></a>
                                        </div><!-- ends: .form-control -->
                                    <?php } } ?>
                                <?php if('yes' == $custom_fields) { ?>
                                    <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                                        <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ? $_GET['in_cat'] : 0 ); ?>
                                    </div>
                                <?php } ?>
                                <?php if('yes' == $website_field  || 'yes' == $email_field || 'yes' == $phone_field || 'yes' == $address_field || 'yes' == $zip_code_field ) {?>
                                    <div class="form-group">
                                        <div class="bottom-inputs">
                                            <div>
                                                <?php if('yes' == $website_field) {?>
                                                <input type="text" name="website" placeholder="<?php _e('Website', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
                                            </div>
                                            <div>
                                                <?php } if('yes' == $email_field) {?>
                                                <input type="text" name="email" placeholder=" <?php _e('Email', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
                                            </div>
                                            <div>
                                                <?php } if('yes' == $phone_field) {?>
                                                <input type="text" name="phone" placeholder="<?php _e('Phone Number', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
                                            </div>
                                            <div>
                                                <?php } if('yes' == $address_field) {?>
                                                <input type="text" name="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="<?php _e('Address', ATBDP_TEXTDOMAIN);?>"
                                                       class="form-control location-name">
                                            </div>
                                            <div>
                                                <?php } if('yes' == $zip_code_field) {?>
                                                <input type="text" name="zip_code" placeholder=" <?php _e('Zip/Post Code', ATBDP_TEXTDOMAIN);?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php
                                if('yes' == $reset_filters_button || 'yes' == $apply_filters_button) {?>
                                    <div class="bdas-filter-actions">
                                        <?php if('yes' == $reset_filters_button) { ?>
                                            <button type="reset" class="btn btn-outline-primary btn-lg"><?php _e($reset_filters_text, ATBDP_TEXTDOMAIN);?></button>
                                        <?php } if('yes' == $apply_filters_button) {?>
                                            <button type="submit" class="btn btn-primary btn-lg"><?php _e($apply_filters_text, ATBDP_TEXTDOMAIN);?></button>
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

