<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_header_bar">
  <div class="<?php $listing->header_container_class();?>">
    <div class="row">
      <div class="col-md-12">
        <div class="atbd_generic_header">
          <?php if ($listing->has_listings_header()) { ?>
            <div class="atbd_generic_header_title">
              <?php if ($listing->has_filter_button()) { ?>

                <a href="#" class="more-filter btn btn-outline btn-outline-primary">
                  <?php if ($listing->has_filter_icon()) { ?>
                    <span class="<?php atbdp_icon_type(true); ?>-filter"></span>
                  <?php } ?>
                  <?php echo $filters; ?>
                </a>

              <?php }

              /**
               * @since 5.4.0
               */
              do_action('atbdp_after_filter_button_in_listings_header');
              if ($listing->has_header_title()) {
                echo apply_filters('atbdp_total_listings_found_text', "<h3>{$header_title}</h3>", $header_title);
              }
              ?>
            </div>
          <?php
          }


          /**
           * @since 5.4.0
           */
          do_action('atbdp_after_total_listing_found_in_listings_header', $header_title);

          if ($listing->has_listings_header_toolbar()) { ?>
            <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
              <!-- Views dropdown -->
              <?php if ($display_viewas_dropdown) {
                ob_start(); ?>
                <div class="atbd_dropdown">
                  <a class="atbd_dropdown-toggle" href="#" id="viewAsDropdownMenuLink">
                    <?php echo $view_as_text; ?>
                    <span class="atbd_drop-caret"></span>
                  </a>
                  <div class="atbd_dropdown-menu" aria-labelledby="viewAsDropdownMenuLink">
                    <?php foreach ($listing->get_view_as_link_list() as $key => $value) {?>
                    	<a class="atbd_dropdown-item<?php echo esc_attr($value['active_class']);?>" href="<?php echo esc_attr($value['link']);?>"><?php echo esc_html($value['label']);?></a>
                      <?php
                    } ?>
                  </div>
                </div>
                <?php
                $view_as_html = ob_get_clean();
                /**
                 * @since 5.0.0
                 * @package Directorist
                 * @param htmlUms $html it return the markup for list and grid
                 * @param string $view the shortcode attr view_as value
                 * @param array $views it return the views type array
                 *
                 */
                echo apply_filters('atbdp_listings_view_as', $view_as_html, $view, $views);
                ?>
              <?php } ?>


              <!-- Orderby dropdown -->
              <?php if ($display_sortby_dropdown) {
                ob_start(); ?>
                <div class="atbd_dropdown">
                  <a class="atbd_dropdown-toggle" href="#" id="sortByDropdownMenuLink">
                    <?php echo $view_as_text; ?>
                    <span class="atbd_drop-caret"></span>
                  </a>
                  <div class="atbd_dropdown-menu atbd_dropdown-menu--lg" aria-labelledby="sortByDropdownMenuLink">
                    <?php foreach ($listing->get_sort_by_link_list() as $key => $value) { ?>
                    	<a class="atbd_dropdown-item<?php echo esc_attr($value['active_class']);?>" href="<?php echo esc_attr($value['link']);?>"><?php echo esc_html($value['label']);?></a>
                      <?php
                    } ?>
                  </div>
                </div>
              <?php
                $sort_as_html = ob_get_clean();
                /**
                 * @since 5.4.0
                 */
                echo apply_filters('atbdp_listings_header_sort_by_button', $sort_as_html);
              } ?>
            </div>
          <?php } ?>
        </div>

        <!--ads advance search-->
        <div class="<?php $listing->filter_container_class(); ?>">
          <div class="ads-advanced">
            <form action="<?php atbdp_search_result_page_link(); ?>" class="atbd_ads-form">
              <div class="atbd_seach_fields_wrapper" <?php $listing->search_fields_wrapper_style(); ?>>
                <div class="row atbdp-search-form">
                  <?php if ($listing->has_search_field()) { ?>
                  <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="single_search_field search_query">
                      <input class="form-control search_fields" type="text" name="q" placeholder="<?php _e($text_placeholder, 'directorist'); ?>">
                    </div>
                  </div>
                  <?php }


                  if ($listing->has_category_field()) {
                  ?>
                  <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="single_search_field search_category">
                      <select name="in_cat" id="cat-type" class="form-control directory_field bdas-category-search">
                        <option><?php echo $category_placeholder; ?></option>
                        <?php echo $categories_fields; ?>
                      </select>
                    </div>
                  </div>
                  <?php }


                  if ($listing->location_field_type('listing_location')) {
                  ?>
                  <div class="col-md-12 col-sm-12 col-lg-4">
                    <div class="single_search_field search_location">
                      <select name="in_loc" id="loc-type" class="form-control directory_field bdas-category-location">
                        <option><?php echo $location_placeholder; ?></option>
                        <?php echo $locations_fields; ?>
                      </select>
                    </div>
                  </div>
                  <?php }


                  if (!$listing->location_field_type('listing_location')) {
                  	$geodata = $listing->geolocation_field_data();
                  ?>
                  <div class="col-md-6 col-sm-12 col-lg-4">
                    <div class="atbdp_map_address_field">
                      <div class="atbdp_get_address_field">
                        <input type="text" name="address" id="address" value="<?php echo $geodata['value']; ?>" placeholder="<?php echo $geodata['placeholder']; ?>" autocomplete="off" class="form-control location-name">
                        <?php echo $geodata['geo_loc']; ?>
                      </div>
                      <div class="address_result" style="display: none">
                      </div>
                      <input type="hidden" id="cityLat" name="cityLat" value="<?php echo $geodata['cityLat']; ?>" />
                      <input type="hidden" id="cityLng" name="cityLng" value="<?php echo $geodata['cityLng']; ?>" />
                    </div>
                  </div>
                  <?php }

                  /**
                   * @since 5.0
                   */
                  do_action('atbdp_search_field_after_location'); ?>
                </div>
              </div>


              <!-- has_any_price_field() -->
              <?php if ( $listing->has_any_price_field() ) { ?>
              <div class="form-group ">
                <label class=""><?php _e('Price Range', 'directorist'); ?></label>
                <div class="price_ranges">
                  <?php if ( $listing->has_price_field() ) {
                  	$price_field_data = $listing->price_field_data();
                    extract( $listing->price_field_data() )
                    ?>
                    <div class="range_single">
                      <input 
                        type="text" 
                        name="price[0]" 
                        class="form-control" 
                        placeholder="<?php _e('Min Price', 'directorist'); ?>" 
                        value="<?php echo $price_field_data['min_price_value']; ?>">
                    </div>

                    <div class="range_single">
                      <input 
                        type="text" 
                        name="price[1]" 
                        class="form-control" 
                        placeholder="<?php _e('Max Price', 'directorist'); ?>" 
                        value="<?php echo $price_field_data['max_price_value']; ?>">
                    </div>
                  <?php }


                  if ( $listing->has_price_range_field() ) {
                  	$price_range_field = $listing->price_range_field_data();
                    extract( $listing->price_range_field_data() );
                  ?>
                  <div class="price-frequency">
                    <label class="pf-btn">
                      <input
                        type="radio" 
                        name="price_range" 
                        value="bellow_economy"<?php echo $price_range_field['bellow_economy_value']; ?>>
                          <span><?php echo $c_symbol; ?></span>
                      </label>

                    <label class="pf-btn">
                      <input 
                        type="radio" 
                        name="price_range" 
                        value="economy" <?php echo $price_range_field['economy_value']; ?>>
                          <span><?php echo $c_symbol, $c_symbol; ?></span>
                    </label>

                    <label class="pf-btn">
                      <input 
                        type="radio" 
                        name="price_range" 
                        value="moderate" <?php echo $price_range_field['moderate_value']; ?>>
                          <span><?php echo $c_symbol, $c_symbol, $c_symbol; ?></span>
                    </label>

                    <label class="pf-btn">
                      <input 
                        type="radio" 
                        name="price_range"
                        value="skimming" <?php echo $price_range_field['skimming_value']; ?>>
                          <span><?php echo $c_symbol, $c_symbol, $c_symbol, $c_symbol; ?></span>
                      </label>
                  </div>
                  <?php } ?>
                </div>
              </div><!-- ends: .form-group -->
              <?php } ?>


              <?php if ( $listing->has_rating_field() ) { 
                extract( $listing->rating_field_data() );
              ?>
              <div class="form-group">
                <label><?php _e('Filter by Ratings', 'directorist'); ?></label>
                <select name='search_by_rating' class="select-basic form-control">
                  <?php
                    foreach ( $rating_options as $key => $option ) {
                      extract( $option );
                      echo "<option value='{$value}'{$selected}>{$label}</option>";
                    }
                  ?>
                </select>
              </div><!-- ends: .form-group -->
              <?php } ?>

              
              <?php if ( $listing->has_radius_search_field() ) {
                $default_radius_distance = !empty($default_radius_distance) ? $default_radius_distance : 0;
              ?>
                <!--range slider-->
                <div class="form-group">
                  <div class="atbdp-range-slider-wrapper">
                    <span><?php _e('Radius Search', 'directorist'); ?></span>
                    <div>
                      <div id="atbdp-range-slider"></div>
                    </div>
                    <p class="atbd-current-value"></p>
                  </div>
                  <input type="hidden" class="atbdrs-value" name="miles" value="<?php echo $default_radius_distance; ?>" />
                </div>
              <?php } ?>
              <?php


              if ( $listing->has_open_now_field() ) {
                extract( $listing->open_now_field_data() );
              ?>
              <div class="form-group">
                <label><?php _e('Open Now', 'directorist'); ?></label>
                <div class="check-btn">
                  <div class="btn-checkbox">
                    <label>
                      <input 
                        type="checkbox" 
                        name="open_now" 
                        value="open_now"<?php echo $checked ?>>
                      <span><i class="fa fa-clock-o"></i><?php _e('Open Now', 'directorist'); ?> </span>
                    </label>
                  </div>
                </div>
              </div><!-- ends: .form-group -->
              <?php }
              if ( $listing->has_tag_field() && $listing->tag_field_data() ) {
                extract( $listing->tag_field_data() );
                ?>
                  <div class="form-group ads-filter-tags">
                    <label><?php echo !empty($tag_label) ? $tag_label : __('Tags', 'directorist'); ?></label>
                    <div class="bads-tags">
                      <?php
                      $rand = rand();
                      foreach ($terms as $term) {
                      ?>
                        <div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary">
                          <input type="checkbox" class="custom-control-input" name="in_tag[]" value="<?php echo $term->term_id; ?>" id="<?php echo $rand . $term->term_id; ?>" <?php if (!empty($_GET['in_tag']) && in_array($term->term_id, $_GET['in_tag'])) {
                                                                                                                                                                                  echo "checked";
                                                                                                                                                                                } ?>>
                          <span class="check--select"></span>
                          <label for="<?php echo $rand . $term->term_id; ?>" class="custom-control-label"><?php echo $term->name; ?></label>
                        </div>
                      <?php } ?>
                    </div>
                    <a href="#" class="more-less ad"><?php _e('Show More', 'directorist'); ?></a>
                  </div><!-- ends: .form-control -->
                <?php
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
                        <input type="text" name="website" placeholder="<?php echo !empty($website_label) ? $website_label : __('Website', 'directorist'); ?>" value="<?php echo !empty($_GET['website']) ? $_GET['website'] : ''; ?>" class="form-control">
                      </div>
                    <?php }
                    if (in_array('search_email', $search_more_filters_fields)) { ?>
                      <div>
                        <input type="text" name="email" placeholder="<?php echo !empty($email_label) ? $email_label : __('Email', 'directorist'); ?>" value="<?php echo !empty($_GET['email']) ? $_GET['email'] : ''; ?>" class="form-control">
                      </div>
                    <?php }
                    if (in_array('search_phone', $search_more_filters_fields)) { ?>
                      <div>
                        <input type="text" name="phone" placeholder="<?php _e('Phone Number', 'directorist'); ?>" value="<?php echo !empty($_GET['phone']) ? $_GET['phone'] : ''; ?>" class="form-control">
                      </div>
                    <?php }
                    if (in_array('search_fax', $search_more_filters_fields)) { ?>
                      <div>
                        <input type="text" name="fax" placeholder="<?php echo !empty($fax_label) ? $fax_label : __('Fax', 'directorist'); ?>" value="<?php echo !empty($_GET['fax']) ? $_GET['fax'] : ''; ?>" class="form-control">
                      </div>
                    <?php }
                    if (in_array('search_zip_code', $search_more_filters_fields)) { ?>
                      <div>
                        <input type="text" name="zip_code" placeholder="<?php echo !empty($zip_label) ? $zip_label : __('Zip/Post Code', 'directorist'); ?>" value="<?php echo !empty($_GET['zip_code']) ? $_GET['zip_code'] : ''; ?>" class="form-control">
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
              <div class="bdas-filter-actions">
                <?php if (in_array('reset_button', $filters_button)) { ?>
                  <a href="" class="btn btn-outline btn-outline-primary btn-sm" id="atbdp_reset"><?php _e($reset_filters_text, 'directorist'); ?></a>
                <?php }
                if (in_array('apply_button', $filters_button)) { ?>
                  <button type="submit" class="btn btn-primary btn-sm"><?php _e($apply_filters_text, 'directorist'); ?></button>
                <?php } ?>

              </div><!-- ends: .bdas-filter-actions -->
            </form>
          </div>
          <!--ads advanced -->
        </div>
      </div>
    </div>
  </div>
</div>