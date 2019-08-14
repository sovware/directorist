<?php
global $post;
$title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Popular Listings', 'directorist');
$pop_listing_num = !empty($instance['pop_listing_num']) ? $instance['pop_listing_num'] : 5;
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
echo '</div>';
ATBDP()->show_popular_listing($pop_listing_num, $post->id);


echo $args['after_widget'];