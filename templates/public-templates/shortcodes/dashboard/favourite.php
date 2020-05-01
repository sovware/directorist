<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_tab_inner" id="saved_items">
    <div class="atbd_saved_items_wrapper">
        <table class="table table-bordered atbd_single_saved_item table-responsive-sm">
            <?php
            if ($fav_listings->have_posts()) {
                ?>
                <thead>
                <tr>
                    <th><?php _e('Listing Name', 'directorist') ?></th>
                    <th><?php _e('Category', 'directorist') ?></th>
                    <th><?php _e('Unfavourite', 'directorist') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($fav_listings->posts as $post) {
                    $title = !empty($post->post_title) ? $post->post_title : __('Untitled', 'directorist');
                    $cats = get_the_terms($post->ID, ATBDP_CATEGORY);
                    $category = get_post_meta($post->ID, '_admin_category_select', true);
                    $category_name = !empty($cats) ? $cats[0]->name : 'Uncategorized';
                    $category_icon = !empty($cats) ? esc_attr(get_cat_icon($cats[0]->term_id)) : atbdp_icon_type() . '-tags';

                    $icon_type = substr($category_icon, 0, 2);
                    $category_link = !empty($cats) ? esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])) : '#';
                    $post_link = esc_url(get_post_permalink($post->ID));

                    $listing_img = get_post_meta($post->ID, '_listing_img', true);
                    $listing_prv_img = get_post_meta($post->ID, '_listing_prv_img', true);
                    $crop_width = get_directorist_option('crop_width', 360);
                    $crop_height = get_directorist_option('crop_height', 300);
                    if (!empty($listing_prv_img)) {
                        $prv_image = atbdp_get_image_source($listing_prv_img, 'large');
                    }
                    if (!empty($listing_img[0])) {

                        $gallery_img = atbdp_get_image_source($listing_img[0], 'medium');
                    }

                    if (!empty($listing_prv_img)) {

                        $img_src = $prv_image;

                    }
                    if (!empty($listing_img[0]) && empty($listing_prv_img)) {

                        $img_src = $gallery_img;

                    }
                    if (empty($listing_img[0]) && empty($listing_prv_img)) {

                        $img_src = ATBDP_PUBLIC_ASSETS . 'images/grid.jpg';

                    }

                    printf(' <tr>
                <td class="thumb_title">
                    <div class="img_wrapper"><a href="%s">
                    <img
                                src="%s"
                                alt="%s">
                   
                    </a>
                    <h4><a href="%s">%s</a></h4>
                    </div>
                </td>

                <td class="saved_item_category">
                    <a href="%s"><span class="%s"></span>%s</a>
                </td>
                 <td class="remove_saved_item">
                   %s
                </td>
                

            </tr>',
                        $post_link, $img_src, $title, $post_link, $title, //first td
                        $category_link, ('la' === $icon_type) ? $icon_type . ' ' . $category_icon : 'fa ' . $category_icon, $category_name, // second td
                        atbdp_listings_mark_as_favourite($post->ID) // third td
                    );
                }
                ?>
                </tbody>
                <?php
            } else {
                printf('<p class="atbdp_nlf">%s</p>', __("Nothing found!", 'directorist'));
            }
            ?>
        </table>
    </div>
</div>