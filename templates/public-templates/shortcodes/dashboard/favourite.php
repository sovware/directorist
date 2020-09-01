<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbd_tab_inner" id="saved_items">
    <div class="atbd_saved_items_wrapper">
        <table class="table table-bordered atbd_single_saved_item table-responsive-sm">
            <?php if (!empty($fav_listing_items)): ?>

                <thead>
                    <tr>
                        <th><?php esc_html_e('Listing Name', 'directorist') ?></th>
                        <th><?php esc_html_e('Category', 'directorist') ?></th>
                        <th><?php esc_html_e('Unfavourite', 'directorist') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($fav_listing_items as $item): ?>

                        <tr>
                            <td class="thumb_title">
                                <div class="img_wrapper">
                                    <a href="<?php echo esc_url($item['post_link']);?>"><img src="<?php echo esc_url($item['img_src']);?>" alt="<?php echo esc_attr($item['title']);?>"></a>
                                    <h4><a href="<?php echo esc_url($item['post_link']);?>"><?php echo esc_html($item['title']);?></a></h4>
                                </div>
                            </td>
                            <td class="saved_item_category">
                                <a href="<?php echo esc_url($item['category_link']);?>"><span class="<?php echo esc_attr($item['icon']);?>"></span><?php echo esc_html($item['category_name']);?></a>
                            </td>
                            <td class="remove_saved_item"><?php echo $item['mark_fav_html'];?></td>
                        </tr> 
                        
                    <?php endforeach; ?>
                </tbody>

            <?php else: ?>

                <tr><td><p class="atbdp_nlf"><?php esc_html_e('Nothing found!', 'directorist'); ?></p></td></tr>

            <?php endif; ?>
        </table>
    </div>
</div>