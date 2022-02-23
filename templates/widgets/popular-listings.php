<?php
/**
 * @author  wpWax
 * @since   7.2.0
 * @version 7.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$count = !empty($instance['pop_listing_num']) ? $instance['pop_listing_num'] : 5;

$popular_listings = ATBDP()->get_popular_listings($count);

if ($popular_listings->have_posts()) { ?>
    <div class="atbd_categorized_listings">
        <ul class="listings">
            <?php foreach ($popular_listings->posts as $pop_post) {
                // get only one parent or high level term object
                $top_category = ATBDP()->taxonomy->get_one_high_level_term($pop_post->ID, ATBDP_CATEGORY);
                $listing_img = get_post_meta($pop_post->ID, '_listing_img', true);
                $listing_prv_img = get_post_meta($pop_post->ID, '_listing_prv_img', true);
                $cats = get_the_terms($pop_post->ID, ATBDP_CATEGORY);
                $post_link = get_the_permalink( $pop_post->ID );
                ?>
                <li>
                    <div class="atbd_left_img">
                        <?php
                        $disable_single_listing = get_directorist_option('disable_single_listing');
                        if (empty($disable_single_listing)){
                        ?>
                        <a href="<?php echo esc_url( $post_link ); ?>">
                            <?php
                            }
                            $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
                            if (!empty($listing_prv_img)) {
                                echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="' . esc_html($pop_post->post_title) . '">';
                            } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="' . esc_html($pop_post->post_title) . '">';
                            } else {
                                echo '<img src="' . $default_image . '" alt="' . esc_html($pop_post->post_title) . '">';
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
                                    <a href="<?php echo esc_url($post_link); ?>"><?php echo esc_html($pop_post->post_title); ?></a>
                                    <?php
                                } else {
                                    echo esc_html($pop_post->post_title);
                                } ?>
                            </h4>
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
                                        <span class="atbd_cat_popup">  +<?php echo $totalTerm - 1; ?>
                                            <span class="atbd_cat_popup_wrapper">
                                                            <?php
                                                            $output = array();
                                                            foreach (array_slice($cats, 1) as $cat) {
                                                                $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                $space = str_repeat(' ', 1);
                                                                $output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                            } ?>
                                                <span><?php echo join($output) ?></span>
                                                        </span>
                                                    </span>
                                    <?php } ?>

                                </span>
                            </p>
                        <?php }
                        ATBDP()->show_static_rating($pop_post);
                        ?>
                    </div>
                </li>
            <?php } // ends the loop
            ?>

        </ul>
    </div> <!--ends .categorized_listings-->
    <?php
}