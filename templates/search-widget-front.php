<div class="atbdp search-area default-ad-search">
    <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>">
        <?php if(!empty($search_by_text_field)) { ?>
            <div class="form-group">
                <input type="text" name="q" placeholder="What are you looking for?" value="<?php echo !empty($_GET['q']) ? $_GET['q'] : ''; ?>" class="form-control">
            </div><!-- ends: .form-group -->
        <?php } ?>
        <?php if(!empty($search_by_location)) { ?>
            <div class="form-group">
                <?php
                bdas_dropdown_terms( array(
                    'show_option_none'   => '-- '.__( 'Select a Location', ATBDP_TEXTDOMAIN ).' --',
                    'option_none_value'  => -1,
                    'taxonomy'           => 'at_biz_dir-location',
                    'name' 			     => 'in_loc',
                    'class'              => 'form-control bdas-location-search select-basic',
                    'orderby'            => 'date',
                    'order'              => 'ASC',
                    'selected'           => isset( $_GET['in_loc'] ) ? $_GET['in_loc'] : -1,
                ) );
                ?>
            </div>
        <?php } ?>
        <?php if(!empty($search_by_category)) {?>
            <div class="form-group">
                <?php
                bdas_dropdown_terms( array(
                    'show_option_none'   => '-- '.__( 'Select a category', ATBDP_TEXTDOMAIN ).' --',
                    'option_none_value'  => -1,
                    'taxonomy'           => 'at_biz_dir-category',
                    'name' 			     => 'in_cat',
                    'class'              => 'form-control bdas-category-search select-basic',
                    'orderby'            => 'date',
                    'order'              => 'ASC',
                    'selected'           => isset( $_GET['in_cat'] ) ? $_GET['in_cat'] : -1,
                ) );
                ?>
            </div>
        <?php } ?>
        <?php if(!empty($search_by_custom_fields)) { ?>
            <div id="atbdp-custom-fields-search" class="atbdp-custom-fields-search">
                <?php do_action( 'wp_ajax_atbdp_custom_fields_search', isset( $_GET['in_cat'] ) ? $_GET['in_cat'] : 0 ); ?>
            </div>
        <?php } ?>
        <?php if(!empty($search_by_price)) {?>

            <div class="form-group ">
                <label><?php _e( 'Price Range', ATBDP_TEXTDOMAIN ); ?></label>
                <div class="price_ranges">
                    <div>
                        <input type="text" name="price[0]" class="form-control" placeholder="<?php _e( 'min', ATBDP_TEXTDOMAIN ); ?>" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][0] ); ?>">
                    </div>
                    <div>
                        <input type="text" name="price[1]" class="form-control" placeholder="<?php _e( 'max', ATBDP_TEXTDOMAIN ); ?>" value="<?php if( isset( $_GET['price'] ) ) echo esc_attr( $_GET['price'][1] ); ?>">
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if(!empty($search_by_price_range)) {?>
            <div class="form-group">
                <div class="select-basic">
                    <select name="price_range" class="form-control">
                        <option value="none">Price Range</option>
                        <option value="skimming" <?php if(!empty($_GET['price_range']) && 'skimming' == $_GET['price_range']) { echo 'selected';}?>>Ultra High ($$$$)</option>
                        <option value="moderate" <?php if(!empty($_GET['price_range']) && 'moderate' == $_GET['price_range']) { echo 'selected';}?>>Expensive ($$$)</option>
                        <option value="economy" <?php if(!empty($_GET['price_range']) && 'economy' == $_GET['price_range']) { echo 'selected';}?>>Moderate ($$)</option>
                        <option value="bellow_economy" <?php if(!empty($_GET['price_range']) && 'bellow_economy' == $_GET['price_range']) { echo 'selected';}?> >Cheap ($)</option>
                    </select>
                </div>
            </div><!-- ends: .form-group -->
        <?php } ?>
        <?php if(!empty($search_by_open_now) && in_array( 'directorist-business-hours/bd-business-hour.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {?>
            <div class="check-btn">
                <div class="btn-checkbox active-color-secondary">
                    <label>
                        <input type="checkbox" name="open_now" value="open_now" <?php if(!empty($_GET['open_now']) && 'open_now' == $_GET['open_now']) { echo 'checked';}?>><span class="text-success"><i
                                    class="fa fa-clock-o"></i> Open Now</span>
                    </label>
                </div>
            </div>
        <?php } ?>
        <?php if(!empty($search_by_website)) { ?>
            <div class="form-group">
                <input type="text" name="website" placeholder="Website" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
            </div><!-- ends: .form-group -->
        <?php } ?>
        <?php if(!empty($search_by_email)) { ?>
            <div class="form-group">
                <input type="text" name="email" placeholder="Email" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
            </div><!-- ends: .form-group -->
        <?php } ?>
        <?php if(!empty($search_by_phone)) { ?>
            <div class="form-group">
                <input type="text" name="phone" placeholder="Phone Number" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
            </div><!-- ends: .form-group -->
        <?php } ?>
        <?php if(!empty($search_by_address)) { ?>
            <div class="form-group">
                <div class="position-relative">
                    <input type="text" name="address" value="<?php echo !empty($_GET['address']) ? $_GET['address'] : ''; ?>" placeholder="Google Address"
                           class="form-control location-name">
                </div>
            </div><!-- ends: .form-group -->
        <?php } ?>
        <?php if(!empty($search_by_zip_code)) { ?>
            <div class="form-group">
                <div class="position-relative">
                    <input type="text" name="zip_code" placeholder="Zip/Post Code"
                           value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                </div>
            </div><!-- ends: .form-group -->
        <?php } ?>
        <?php if(!empty($search_by_tag)) {
            $terms = get_terms(ATBDP_TAGS);
            ?>

            <div class="form-group filter-checklist">
                <label>Filter by Tags</label>
                <div class="checklist-items">
                    <?php
                    if(!empty($terms)) {
                        foreach($terms as $term) {
                            ?>
                            <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                                <input type="checkbox" class="custom-control-input" id="<?php echo $term->term_id;?>" name="in_tag" value="<?php echo $term->term_id;?>" <?php if(!empty($_GET['in_tag']) && $term->term_id == $_GET['in_tag']) { echo "checked";}?>>
                                <span class="check--select"></span>
                                <label class="custom-control-label" for="<?php echo $term->term_id;?>"><?php echo $term->name;?></label>
                            </div>
                        <?php } } ?>
                </div>
            </div><!-- ends: .filter-checklist -->
        <?php } ?>
        <?php if(!empty($search_by_review)) { ?>
            <div class="form-group filter-checklist">
                <label>Filter by Ratings</label>
                <div class="sort-rating">
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="5" name="search_by_rating" class="custom-control-input" id="customCheck7" <?php if(!empty($_GET['search_by_rating']) && '5' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck7">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="4" name="search_by_rating" class="custom-control-input" id="customCheck8" <?php if(!empty($_GET['search_by_rating']) && '4' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck8">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="3" name="search_by_rating" class="custom-control-input" id="customCheck9" <?php if(!empty($_GET['search_by_rating']) && '3' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck9">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="2" name="search_by_rating" class="custom-control-input" id="customCheck10" <?php if(!empty($_GET['search_by_rating']) && '2' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck10">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" value="1" name="search_by_rating" class="custom-control-input" id="customCheck11" <?php if(!empty($_GET['search_by_rating']) && '1' == $_GET['search_by_rating']) { echo 'checked';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck11">
                            <span class="active"><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                        <input type="radio" name="search_by_rating" value="0" class="custom-control-input" id="customCheck12" <?php if(!empty($_GET['search_by_rating']) && '0' == $_GET['search_by_rating']) { echo 'none';}?>>
                        <span class="radio--select"></span>
                        <label class="custom-control-label" for="customCheck12">
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                            <span><i class="fa fa-star"></i></span>
                        </label>
                    </div>
                </div>
            </div><!-- ends: .filter-checklist -->
        <?php } ?>
        <div class="form-group submit_btn">
            <button type="reset" class="btn btn-default"><?php _e( 'Reset', ATBDP_TEXTDOMAIN ); ?></button>
            <button type="submit" class="btn btn-primary btn-block btn-icon icon-right"><?php _e( 'Search Listings', ATBDP_TEXTDOMAIN ); ?></button>
        </div>
    </form><!-- ends: form -->
</div><!-- ends: .default-ad-search -->