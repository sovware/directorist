<?php
$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Title', ATBDP_TEXTDOMAIN);
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_submit_item_title', $title)) . $args['after_title'];
echo '</div>';
?>
    <div class="directorist">
        <a href="<?= (is_fee_manager_active())?esc_url(ATBDP_Permalink::get_fee_plan_page_link()):esc_url(ATBDP_Permalink::get_add_listing_page_link()); ?>"
           class="<?= atbdp_directorist_button_classes(); ?>"><?php _e('Submit New Listing', ATBDP_TEXTDOMAIN); ?></a>
    </div>
<?php


echo $args['after_widget'];