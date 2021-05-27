<?php
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
  echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
  echo '</div>';
global $post;
$related_listings = ATBDP()->get_related_listings_widget($post, $sim_listing_num); ?>
<div class="atbd_categorized_listings">
  <ul class="listings">
    <?php
    foreach ($related_listings->posts as $related_listing) {

      // get only one parent or high level term object
      $top_category = ATBDP()->taxonomy->get_one_high_level_term($related_listing->ID, ATBDP_CATEGORY);
      $listing_img = get_post_meta($related_listing->ID, '_listing_img', true);
      $listing_prv_img = get_post_meta($related_listing->ID, '_listing_prv_img', true);
      $price = get_post_meta($related_listing->ID, '_price', true);
      $price_range = get_post_meta($related_listing->ID, '_price_range', true);
      $listing_pricing = get_post_meta($related_listing->ID, '_atbd_listing_pricing', true);
      $cats = get_the_terms($related_listing->ID, ATBDP_CATEGORY);
    ?>
      <li>
        <div class="atbd_left_img">
          <?php
          $disable_single_listing = get_directorist_option('disable_single_listing');
          if (empty($disable_single_listing)) {
          ?>
            <a href="<?php echo esc_url(get_post_permalink($related_listing->ID)); ?>">
            <?php
          }
          $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
          if (!empty($listing_prv_img)) {
            echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="listing image">';
          } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
            echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="listing image">';
          } else {
            echo '<img src="' . $default_image . '" alt="listing image">';
          }
          if (empty($disable_single_listing)) {
            echo '</a>';
          }
            ?>
        </div>
        <div class="atbd_right_content">
          <div class="cate_title">
            <h4>
              <?php
              if (empty($disable_single_listing)) {
              ?>
                <a href="<?php echo esc_url(get_post_permalink($related_listing->ID)); ?>"><?php echo esc_html($related_listing->post_title); ?></a>
              <?php
              } else {
                echo esc_html($related_listing->post_title);
              } ?>

            </h4>
            <?php if (!empty($price) && ('price' === $listing_pricing)) { ?>
              <span><?php atbdp_display_price($price); ?></span>

            <?php } else {
              $output = atbdp_display_price_range($price_range);
              echo $output;
            } ?>
          </div>

          <?php if (!empty($cats)) {
            $totalTerm = count($cats);
          ?>

            <p class="directory_tag">
              <span class="<?php atbdp_icon_type(true); ?>-tags"></span>
              <span>
                <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>">
                  <?php echo esc_html($cats[0]->name); ?>
                </a>
                <?php
                if ($totalTerm > 1) {
                ?>
                  <span class="atbd_cat_popup"> +<?php echo $totalTerm - 1; ?>
                    <span class="atbd_cat_popup_wrapper">
                      <?php
                      $output = array();
                      foreach (array_slice($cats, 1) as $cat) {
                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                        $space = str_repeat(' ', 1);
                        $output[] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                      } ?>
                      <span><?php echo join($output) ?></span>
                    </span>
                  </span>
                <?php } ?>

              </span>
            </p>
          <?php } ?>
        </div>
      </li>
    <?php
    }; ?>
  </ul>
</div>
<!--ends .categorized_listings-->


<?php echo $args['after_widget'];