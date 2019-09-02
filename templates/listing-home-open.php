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
                                    '. $require_text.'
                                           placeholder="'. esc_html($search_placeholder).'">
                                </div>';
                            $search_html .= '</div>';
                        }
                        if('yes' == $category_field) {
                            $search_html .= '<div class="col-md-6 col-sm-12 col-lg-4">
                                <div class="single_search_field search_category">';
                            $search_html .= '<select '.$require_cat.' name="in_cat" class="search_fields form-control" id="at_biz_dir-category">';
                            $search_html .= '<option>' . $search_category_placeholder . '</option>';
                            $search_html .= $categories_fields;
                            $search_html .= '</select>';
                            $search_html .= '</div></div>';
                        }
                        if('yes' == $location_field) {
                            $search_html .= '<div class="col-md-12 col-sm-12 col-lg-4">
                                <div class="single_search_field search_location">';
                            $search_html .= '<select '.$require_loc.' name="in_loc" class="search_fields form-control" id="at_biz_dir-location">';
                            $search_html .= '<option>' . $search_location_placeholder . '</option>';
                            $search_html .= $locations_fields;
                            $search_html .= '</select>';
                            $search_html .= ' </div></div>';
                        }
                        /**
                         * @since 5.0
                         */
                        echo apply_filters('atbdp_search_form_fields', $search_html);

                        ?>

                    </div>
                <?php } ?>




                <div class="ads-advanced">
                    <?php if('yes' == $price_min_max_field || 'yes' == $price_range_field)  { ?>
                        <div class="form-group ">
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
                            <select name='search_by_rating' class="select-basic form-control">
                                <option value=""><?php _e('Select Ratings', 'directorist');?></option>
                                <option  value="5" <?php if(!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('5 Star', 'directorist');?></option>
                                <option value="4" <?php if(!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('4 Star & Up', 'directorist');?></option>
                                <option value="3" <?php if(!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('3 Star & Up', 'directorist');?></option>
                                <option value="2" <?php if(!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('2 Star & Up', 'directorist');?></option>
                                <option value="1" <?php if(!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) { echo "selected";}?>><?php _e('1 Star & Up', 'directorist');?></option>
                            </select>
                        </div><!-- ends: .form-group -->
                    <?php } ?>
                    <?php if('yes' == $open_now_field && in_array( 'directorist-business-hours/bd-business-hour.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) { ?>
                        <div class="form-group">
                            <div class="check-btn">
                                <div class="btn-checkbox">
                                    <label>
                                        <input type="checkbox" name="open_now" value="open_now" <?php if(!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) { echo "checked='checked'";}?>>
                                        <span><i class="fa fa-clock-o"></i><?php _e('Open Now', 'directorist');?> </span>
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
                                <label><?php echo !empty($tag_label) ? $tag_label : __('Tags','directorist'); ?></label>
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
                                <a href="#" class="more-or-less sml"><?php _e('Show More', 'directorist');?></a>
                            </div><!-- ends: .form-control -->
                        <?php } } ?>
                    <?php if('yes' == $custom_fields) { ?>
                        <div id="atbdp-custom-fields-search" class="form-group ads-filter-tags atbdp-custom-fields-search">
                            <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ? $_GET['in_cat'] : 0 ); ?>
                        </div>
                    <?php } ?>
                    <?php if('yes' == $website_field  || 'yes' == $email_field || 'yes' == $phone_field || 'yes' == $address_field || 'yes' == $zip_code_field ) {?>
                        <div class="form-group">
                            <div class="bottom-inputs">
                                <div>
                                    <?php if('yes' == $website_field) {?>
                                    <input type="text" name="website" placeholder="<?php echo !empty($website_label) ? $website_label : __('Website','directorist'); ?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
                                </div>
                                <div>
                                    <?php } if('yes' == $email_field) {?>
                                    <input type="text" name="email" placeholder="<?php echo !empty($email_label) ? $email_label : __('Email','directorist'); ?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
                                </div>
                                <div>
                                    <?php } if('yes' == $phone_field) {?>
                                    <input type="text" name="phone" placeholder="<?php _e('Phone Number', 'directorist');?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
                                </div>
                                <div>
                                    <?php } if('yes' == $fax) {?>
                                    <input type="text" name="fax" placeholder="<?php echo !empty($fax_label) ? $fax_label : __('Fax','directorist'); ?>" value="<?php echo !empty($_GET['fax']) ? $_GET['fax'] : ''; ?>" class="form-control">
                                </div>
                                <div>
                                    <?php } if('yes' == $address_field) {?>
                                    <input type="text" name="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="<?php echo !empty($address_label) ? $address_label : __('Address','directorist'); ?>"
                                           class="form-control location-name">
                                </div>
                                <div>
                                    <?php } if('yes' == $zip_code_field) {?>
                                    <input type="text" name="zip_code" placeholder="<?php echo !empty($zip_label) ? $zip_label : __('Zip/Post Code','directorist'); ?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php
                    if('yes' == $reset_filters_button || 'yes' == $apply_filters_button) {?>
                        <div class="bdas-filter-actions">
                            <?php if('yes' == $reset_filters_button) { ?>
                                <button type="reset" class="btn btn-outline-primary btn-lg"><?php _e($reset_filters_text, 'directorist');?></button>
                            <?php } if('yes' == $apply_filters_button) {?>
                                <button type="submit" class="btn btn-primary btn-lg"><?php _e($apply_filters_text, 'directorist');?></button>
                            <?php } ?>
                        </div><!-- ends: .bdas-filter-actions -->
                    <?php } ?>
                </div> <!--ads advanced -->



            </div>
            <!--More Filters  & Search Button-->

        </form>
    </div>
</div>