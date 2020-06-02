<div class="atbdp_column <?php echo $grid_col_class ?>">
  <div class="atbd_single_listing atbd_listing_card <?php $template->listing_card_class() ?>">
    <article class="atbd_single_listing_wrapper <?php $template->listing_wrapper_class(); ?>">
      <figure class="atbd_listing_thumbnail_area">
        <div class="atbd_listing_image">
          <?php
          if ( $show_preview_image ) {
            if ( ! $single_listing_is_disabled ) { ?>
            <a href="<?php echo $listings_link; ?>" <?php echo $listings_link_attr; ?>>
            <?php }
            atbdp_thumbnail_card();

            if ( ! $single_listing_is_disabled ) {
              echo '</a>';
            }
          }

          if (!empty($display_author_image)) {
              ?>
              <div class="atbd_author">
                <a href="<?php echo $author_link; ?>" aria-label="<?php echo $author_full_name; ?>" class="<?php echo $author_link_class; ?>">
                  <?php if (empty($u_pro_pic)) {
                    echo $avatar_img;
                  }
                  if (!empty($u_pro_pic)) { ?>
                    <img src="<?php echo esc_url($u_pro_pic[0]); ?>" alt="Author Image"><?php } ?>
                </a>
              </div>
            <?php } ?>
        </div>
        
        <span class="atbd_upper_badge bh_only">
        <?php
          /**
           * @since 5.0
		       * @hooked Directorist_Template_Hooks::business_hours_badge - 10
           */

          echo apply_filters('atbdp_upper_badges', '');
        ?>
        </span>

        <span class="atbd_lower_badge">
        <?php
          /**
           * @since 5.0
           * @hooked Directorist_Template_Hooks::featured_badge_list_view - 10
           * @hooked Directorist_Template_Hooks::popular_badge - 15
           * @hooked Directorist_Template_Hooks::new_listing_badge - 20
           */
          
          echo apply_filters('atbdp_grid_lower_badges', '');
        ?>
        </span>
        
        <?php
          /**
           * @since 7.0
		       * @hooked Directorist_Template_Hooks::mark_as_favourite_button - 10
           */
          
          do_action('atbdp_listing_thumbnail_area');
        ?>
      </figure>
      <div class="atbd_listing_info">
        <?php if (!empty($display_title) || !empty($enable_tagline) || !empty($display_review) || !empty($display_price)) { ?>
          <div class="atbd_content_upper">
            <?php if (!empty($display_title)) { ?>
              <h4 class="atbd_listing_title">
                <?php
                if ( ! $single_listing_is_disabled  ) {
                  echo '<a href="' . $listings_link . '"' . $listings_link_attr . '>' . $listings_title . '</a>';
                } else {
                  echo $listings_title;
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

            if (!empty($display_review) || (!empty($display_price) && (!empty($price) || !empty($price_range)))) {
              echo atbdp_get_price_meta_html( compact('atbd_listing_pricing', 'display_price', 'display_pricing_field', 'price_range', 'price', 'is_disable_price') );
            }
            
            if (!empty($display_contact_info || $display_publish_date || $display_email || $display_web_link)) {
              atbdp_get_shortcode_template( 'global/data-list', compact( 'display_contact_info', 'address', 'address_location', 'display_address_field', 'locs', 'phone_number', 'display_phone_field', 'display_publish_date', 'email', 'display_email', 'web', 'display_web_link', 'web', 'use_nofollow' ) );
            }

            if (!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) {
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

        if ( ! empty( $display_category ) || ! empty( $display_view_count ) ) { ob_start() ?>
          <div class="atbd_listing_bottom_content">
            <?php if ( ! empty( $display_category ) && ! empty( $cats ) ) { $totalTerm = count($cats); ?>
              <div class="atbd_content_left">
                <div class="atbd_listing_category">
                  <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]) ?>">
                    <span class="<?php echo atbdp_icon_type() ?>-tags"></span>
                      <?php echo $cats[0]->name; ?>
                  </a>

                  <?php if ($totalTerm > 1) { $totalTerm = $totalTerm - 1; ?>
                  <div class="atbd_cat_popup">
                    <span>+<?php echo $totalTerm; ?></span>
                    <div class="atbd_cat_popup_wrapper">
                      <span>
                        <?php foreach (array_slice($cats, 1) as $cat) {
                          $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                          $space = str_repeat(' ', 1);

                          echo"{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>"; 
                        } ?>
                      </span>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>

            <?php if ( ! empty( $display_category ) && empty( $cats ) ) { ?>
              <div class="atbd_content_left">
                <div class="atbd_listing_category">
                  <a href="./">
                    <span class="<?php atbdp_icon_type(); ?>-tags"></span>
                    <?php _e('Uncategorized', 'directorist'); ?>
                  </a>
                </div>
              </div>
            <?php } ?>

            <?php if (!empty($display_view_count)) { ob_start(); ?>
              <ul class="atbd_content_right">
                <li class="atbd_count">
                <span class="<?php echo atbdp_icon_type() ?>-eye"></span>
                  <?php echo ( ! empty($post_view) ) ? $post_view : 0; ?>
                </li>
              </ul>
            <?php }
            echo apply_filters('atbdp_grid_footer_right_html', ob_get_clean() );
            ?> 
          </div>
        <?php 
          echo apply_filters('atbdp_listings_grid_cat_view_count', ob_get_clean() );
        }

        /**
         * @param mixed $footer_html
         * @since
         * @package Directorist
         */
        apply_filters('atbdp_listings_footer_content', '');
        ?>
      </div>
    </article>
  </div>
</div>