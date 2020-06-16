<?php
$currency = get_directorist_option('g_currency', 'USD');
$c_symbol = atbdp_currency_symbol($currency);
?>
<div class="row">
    <div class="col-md-12">
        <!-- start search area -->
        <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form" class="atbd_ads-form">
            <!-- @todo; if the input fields break in different themes, use bootstrap form inputs then -->
            <div class="atbd_seach_fields_wrapper"<?php echo empty($search_border)?'style="border: none;"':'';?>>
               <?php /**
                * @since 5.10.0
                */
                do_action('atbdp_before_search_form');
                ?>
                <?php if('yes' == $text_field || 'yes' == $category_field || 'yes' == $location_field) { ?>
                    <div class="row atbdp-search-form">
                        <?php
                        $search_html = '';
                        if('yes' == $text_field) {
                            $search_html .= '<div class="single_search_field search_query">
                                    <input class="form-control search_fields" type="text" name="q"
                                    '. $require_text.'
                                           placeholder="'. esc_html($search_placeholder).'">
                                </div>';
                        }
                        if('yes' == $category_field) {
                            $search_html_cat      = '<div class="single_search_field search_category">';
                            $search_html_cat     .= '<select '.$require_cat.' name="in_cat" class="search_fields form-control" id="at_biz_dir-category">';
                            $search_html_cat     .= '<option value="">' . $search_category_placeholder . '</option>';
                            $search_html_cat     .= $categories_fields;
                            $search_html_cat     .= '</select>';
                            $search_html_cat     .= '</div>';

                            $search_html .=  apply_filters('atbdp_search_form_after_cat',$search_html_cat);
                        }

                        if('yes' == $location_field) {
                            if('listing_location' == $search_location_address) {
                                $search_html .= '<div class="single_search_field search_location">';
                                $search_html .= '<select ' . $require_loc . ' name="in_loc" class="search_fields form-control" id="at_biz_dir-location">';
                                $search_html .= '<option value="">' . $search_location_placeholder . '</option>';
                                $search_html .= $locations_fields;
                                $search_html .= '</select>';
                                $search_html .= ' </div>';
                            } else {
                                $select_listing_map = get_directorist_option('select_listing_map','google');
                                wp_enqueue_script('atbdp-geolocation');
                                wp_localize_script('atbdp-geolocation', 'adbdp_geolocation', array('select_listing_map'=> $select_listing_map));
                                $geo_loc = ('google' == $select_listing_map) ? '<span class="atbd_get_loc la la-crosshairs"></span>' : '<span class="atbd_get_loc la la-crosshairs"></span>';
                                $address       = !empty($_GET['address']) ? $_GET['address'] : '';
                                $address_label =  !empty($search_location_placeholder) ? sanitize_text_field($search_location_placeholder) : '';
                                $search_html .= '<div class="single_search_field atbdp_map_address_field">';
                                $search_html .= '<div class="atbdp_get_address_field"><input ' . $require_loc . ' type="text" id="address" autocomplete="off" name="address" value="'.$address.'" placeholder="'.$address_label.'" class="form-control location-name">'. $geo_loc .'</div>';
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

                <div class="ads-advanced">
                    <?php include 'adv-search.php';?>
                </div> <!--ads advanced -->



            </div>
            <!--More Filters  & Search Button-->

        </form>
    </div>
</div>
