<div class="atbdp_column">
  <div class="atbd_single_listing atbd_listing_card <?php echo get_directorist_option('info_display_in_single_line', 0) ? 'atbd_single_line_card_info' : '';
                                                    echo esc_html($listing_preview_img_class); ?>">
    <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
      <figure class="atbd_listing_thumbnail_area">
        <div class="atbd_listing_image">
          <?php
          $thumbnail_link_attr = " " . apply_filters('grid_view_thumbnail_link_add_attr', '');
          $thumbnail_link_attr = trim($thumbnail_link_attr);

          $title_link_attr = " " . apply_filters('grid_view_title_link_add_attr', '');
          $title_link_attr = trim($title_link_attr);

          if ('yes' == $listing_preview_img) {

            $disable_single_listing = get_directorist_option('disable_single_listing');
            if (empty($disable_single_listing)) {
          ?>
              <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>" <?php echo $thumbnail_link_attr; ?>>
              <?php
            }
            the_thumbnail_card();

            if (empty($disable_single_listing)) {
              echo '</a>';
            }
          }

          if (!empty($display_author_image)) {
            $author = get_userdata($author_id);
            $class = !empty($author->first_name && $author->last_name) ? 'atbd_tooltip' : '';
              ?>
              <div class="atbd_author">
                <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link($author_id); ?>" aria-label="<?php echo $author->first_name . ' ' . $author->last_name; ?>" class="<?php echo $class; ?>">
                  <?php if (empty($u_pro_pic)) {
                    echo $avatar_img;
                  }
                  if (!empty($u_pro_pic)) { ?>
                    <img src="<?php echo esc_url($u_pro_pic[0]); ?>" alt="Author Image"><?php } ?>
                </a>
              </div>
            <?php } ?>
        </div>

        <?php
        $plan_hours = true;
        $u_badge_html = '<span class="atbd_upper_badge bh_only">';
        if (is_fee_manager_active()) {
          $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
        }
        if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
          //lets check is it 24/7

          if ('2.2.6' > BDBH_VERSION) {
        ?>
            <style>
              .atbd_badge_close,
              .atbd_badge_open {
                position: absolute;
                left: 15px;
                top: 15px;
              }
            </style>
        <?php
          }
          $open = get_directorist_option('open_badge_text', __('Open Now', 'directorist'));
          if (!empty($enable247hour)) {
            $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">' . $open . '</span>';
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
        if ($popular_listing_id === get_the_ID() && !empty($display_popular_badge_cart)) {
          $l_badge_html .= $badge;
        }
        //print the new badge
        $l_badge_html .= new_badge();
        $l_badge_html .= '</span>';

        /**
         * @since 5.0
         */
        echo apply_filters('atbdp_grid_lower_badges', $l_badge_html);
        if (!empty($display_mark_as_fav)) {
          echo atbdp_listings_mark_as_favourite($listing_id);
        }
        ?>
      </figure>
      <div class="atbd_listing_info">
        <?php if (!empty($display_title) || !empty($enable_tagline) || !empty($display_review) || !empty($display_price)) { ?>
          <div class="atbd_content_upper">
            <?php if (!empty($display_title)) { ?>
              <h4 class="atbd_listing_title">
                <?php
                if (empty($disable_single_listing)) {
                  echo '<a href="' . esc_url(get_post_permalink(get_the_ID())) . '"' . $title_link_attr . '>' . esc_html(stripslashes(get_the_title())) . '</a>';
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
            <?php
            $meta_html = '';
            if (!empty($display_review) || (!empty($display_price) && (!empty($price) || !empty($price_range)))) { ?>
            <?php
              $meta_html .= '<div class="atbd_listing_meta">';
              $average = ATBDP()->review->get_average(get_the_ID());
              if (!empty($display_review)) {
                $meta_html .= '<span class="atbd_meta atbd_listing_rating">' . $average . '<i class="' . atbdp_icon_type() . '-star"></i></span>';
              }
              $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
              if (!empty($display_price) && !empty($display_pricing_field)) {
                if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                  $output = atbdp_display_price_range($price_range);
                  $meta_html .= $output;
                } else {
                  $meta_html .= apply_filters('atbdp_listing_card_price', atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false));
                }
              }
              /**
               * Fires after the price of the listing is rendered
               *
               *
               * @since 3.1.0
               */
              do_action('atbdp_after_listing_price');
              $meta_html .= '</div>';
            }
            /**
             * @since 5.0
             * universal action to fire after the price
             */
            echo apply_filters('atbdp_listings_review_price', $meta_html);
            ?>

            <?php if (!empty($display_contact_info || $display_publish_date || $display_email || $display_web_link)) { ?>
              <div class="atbd_listing_data_list">
                <ul>
                  <?php
                  /**
                   * @since 4.7.6
                   */
                  do_action('atbdp_listings_before_location');

                  if (!empty($display_contact_info)) {
                    if (!empty($address) && 'contact' == $address_location && !empty($display_address_field)) { ?>
                      <li>
                        <p>
                          <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span>
                          <?php echo esc_html(stripslashes($address)); ?>
                        </p>
                      </li>
                    <?php } elseif (!empty($locs) && 'location' == $address_location) {
                      $local_names = array();
                      foreach ($locs as $term) {
                        $local_names[$term->term_id] = $term->parent == 0 ? $term->slug : $term->slug;
                        ksort($local_names);
                        $locals = array_reverse($local_names);
                      }
                      $output = array();
                      $link = array();
                      foreach ($locals as $location) {
                        $term = get_term_by('slug', $location, ATBDP_LOCATION);
                        $link = ATBDP_Permalink::atbdp_get_location_page($term);
                        $space = str_repeat(' ', 1);
                        $output[] = "{$space}<a href='{$link}'>{$term->name}</a>";
                      }
                    ?>
                      <li>
                        <p>
                          <span>
                            <?php
                            echo "<span class='" . atbdp_icon_type() . "-map-marker'></span>" . join(',', $output); ?>
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
                      <li>
                        <p>
                          <span class="<?php atbdp_icon_type(true); ?>-phone"></span><a href="tel:<?php echo esc_html(stripslashes($phone_number)); ?>"><?php echo esc_html(stripslashes($phone_number)); ?></a>

                        </p>
                      </li>
                    <?php
                    }
                  }
                  /**
                   * @since 4.7.6
                   */
                  do_action('atbdp_listings_before_post_date');

                  if (!empty($display_publish_date)) { ?>
                    <li>
                      <p>
                        <span class="<?php atbdp_icon_type(true); ?>-clock-o"></span>
                        <?php
                            $publish_date_format = get_directorist_option('publish_date_format', 'time_ago');
                            if ('time_ago' === $publish_date_format) {
                              printf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp')));
                            } else {
                              echo get_the_date();
                            }
                        ?>
                      </p>
                    </li>
                  <?php }
                  /**
                   * @since 4.7.6
                   */
                  do_action('atbdp_listings_after_post_date');

                  if (!empty($email && $display_email)) :
                    echo '<li><p><span class="' . atbdp_icon_type() . '-envelope"></span><a target="_top" href="mailto:' . $email . '">' . $email . '</a></p></li>';
                  endif;
                  if (!empty($web && $display_web_link)) :
                  ?>
                    <li>
                      <p>
                        <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                        <a target="_blank" href="<?php echo esc_url($web); ?>" <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($web); ?></a>
                      </p>
                    </li>
                  <?php
                  endif;
                  ?>
                </ul>
              </div><!-- End atbd listing meta -->
            <?php
            }
            if (!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) {
              $excerpt_limit = get_directorist_option('excerpt_limit', 20);
              $display_readmore = get_directorist_option('display_readmore', 0);
              $readmore_text = get_directorist_option('readmore_text', __('Read More', 'directorist'));
            ?>
              <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, $excerpt_limit)));
                                              /**
                                               * @since 5.0.9
                                               */
                                              do_action('atbdp_listings_after_exerpt');
                                              if (!empty($display_readmore)) {
                                              ?><a href="<?php the_permalink(); ?>"><?php printf(__(' %s', 'directorist'), $readmore_text); ?></a></p>
          <?php }
                                            } ?>
          </div><!-- end ./atbd_content_upper -->
        <?php }
        $catViewCount = '';
        if (!empty($display_category) || !empty($display_view_count)) {
          $catViewCount .= '<div class="atbd_listing_bottom_content">';
          if (!empty($display_category)) {
            if (!empty($cats)) {
              $totalTerm = count($cats);
              $catViewCount .= '<div class="atbd_content_left">';
              $catViewCount .= '<div class="atbd_listing_category">';
              $catViewCount .= '<a href="' . ATBDP_Permalink::atbdp_get_category_page($cats[0]) . '">';
              $catViewCount .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
              $catViewCount .= $cats[0]->name;
              $catViewCount .= '</a>';
              if ($totalTerm > 1) {
                $totalTerm = $totalTerm - 1;
                $catViewCount .= '<div class="atbd_cat_popup">';
                $catViewCount .= '<span>+' . $totalTerm . '</span>';
                $catViewCount .= '<div class="atbd_cat_popup_wrapper">';
                $output = array();
                foreach (array_slice($cats, 1) as $cat) {
                  $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                  $space = str_repeat(' ', 1);
                  $output[] = "{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>";
                }
                $catViewCount .= '<span>' . join($output) . '</span>';
                $catViewCount .= '</div>';
                $catViewCount .= '</div>';
              }
              $catViewCount .= '</div>';
              $catViewCount .= '</div>';
            } else {
              $catViewCount .= '<div class="atbd_content_left">';
              $catViewCount .= '<div class="atbd_listing_category">';
              $catViewCount .= '<a href="">';
              $catViewCount .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
              $catViewCount .= __('Uncategorized', 'directorist');
              $catViewCount .= '</a>';
              $catViewCount .= '</div>';
              $catViewCount .= '</div>';
            }
          }
          if (!empty($display_view_count)) {
            $fotter_right = '<ul class="atbd_content_right">';
            $fotter_right .= '<li class="atbd_count">';
            $fotter_right .= '<span class="' . atbdp_icon_type() . '-eye"></span>';
            $fotter_right .= !empty($post_view) ? $post_view : 0;
            $fotter_right .= '</li>';
            $fotter_right .= '</ul>';
            $catViewCount .= apply_filters('atbdp_grid_footer_right_html', $fotter_right);
          }

          $catViewCount .= '</div>'; //end ./atbd_listing_bottom_content

        }
        echo apply_filters('atbdp_listings_grid_cat_view_count', $catViewCount);

        /**
         * @param mixed $footer_html
         * @since
         * @package Directorist
         */
        //apply_filters('atbdp_listings_footer_content')
        ?>
      </div>
    </article>
  </div>
</div>