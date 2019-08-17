<?php
global $post;
$listing_id = $post->ID;
$fm_plan = get_post_meta($listing_id, '_fm_plans', true);
$cats = get_the_terms($post->ID, ATBDP_CATEGORY);
$custom_section_lable = get_directorist_option('custom_section_lable', __('Details', 'directorist'));
// make main column size 12 when sidebar or submit widget is active @todo; later make the listing submit widget as real widget instead of hard code
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
$category_ids = array();
if (!empty($cats)) {
    foreach ($cats as $single_val) {
        $category_ids[] = $single_val->term_id;
    }
}
$c_args = array(
    'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
    'posts_per_page' => -1,
    'post_status' => 'publish',

);
$custom_fields = new WP_Query($c_args);
$custom_fields_posts = $custom_fields->posts;
$has_field_value = array();
$has_field_ids = array();
foreach ($custom_fields_posts as $custom_fields_post) {
    $id = $custom_fields_post->ID;
    $fields = get_post_meta($id, 'associate', true);
    //lets match if the field is associated with a category and the category is selected
    if ('form' != $fields){
        $fields_id_with_cat = get_post_meta($id, 'category_pass', true);
        if (in_array($fields_id_with_cat, $category_ids)){
            $has_field_details = get_post_meta($listing_id, $custom_fields_post->ID, true);
            if (!empty($has_field_details)){
                $has_field_ids[] = $id;
            }
            $has_field_value[] = $has_field_details;
        }

    }else{
        $has_field_details = get_post_meta($listing_id, $custom_fields_post->ID, true);
        if (!empty($has_field_details)){
            $has_field_ids[] = $id;
        }
        $has_field_value[] = $has_field_details;
    }

}
wp_reset_postdata();
$has_field = join($has_field_value);
$plan_custom_field = true;
if (is_fee_manager_active()) {
    $plan_custom_field = is_plan_allowed_custom_fields($fm_plan);
}
if (!empty($has_field) && $plan_custom_field) {
    ?>
    <div class="atbd_content_module atbd_custom_fields_contents">
        <div class="atbd_content_module__tittle_area">
            <div class="atbd_area_title">
                <h4>
                    <span class="<?php atbdp_icon_type(true); ?>-bars atbd_area_icon"></span><?php _e($custom_section_lable, 'directorist') ?>
                </h4>
            </div>
        </div>
        <div class="atbdb_content_module_contents">
            <ul class="atbd_custom_fields">
                <!--  get data from custom field-->
                <?php
                foreach ($has_field_ids as $id) {
                    $field_id = $id;
                    $field_details = get_post_meta($listing_id, $field_id, true);
                    $has_field_value[] = $field_details;

                    $field_title = get_the_title($field_id);
                    $field_type = get_post_meta($field_id, 'type', true);
                    if (!empty($field_details)) {
                        ?>
                        <li>
                            <div class="atbd_custom_field_title">
                                <p><?php echo esc_attr($field_title); ?></p></div>
                            <div class="atbd_custom_field_content">
                                <p><?php if ('color' == $field_type) {
                                        printf('<div class="atbd_field_type_color" style="background-color: %s;"></div>', $field_details);
                                    } elseif ($field_type === 'time') {
                                        echo date('h:i A', strtotime($field_details));
                                    } elseif ($field_type === 'url') {
                                        printf('<a href="%s" target="_blank">%s</a>', esc_url($field_details), esc_url($field_details));
                                    } elseif ($field_type === 'file') {
                                        $done = str_replace('|||', '', $field_details);
                                        $name_arr = explode('/', $done);
                                        $filename = end($name_arr);
                                        printf('<a href="%s" target="_blank" download>%s</a>', esc_url($done), $filename);
                                    } elseif ($field_type === 'checkbox') {
                                        $choices = get_post_meta($field_id, 'choices', true);
                                        $choices = explode("\n", $choices);
                                        $values = explode("\n", $field_details);
                                        $values = array_map('trim', $values);
                                        $output = array();
                                        foreach ($choices as $choice) {
                                            if (strpos($choice, ':') !== false) {
                                                $_choice = explode(':', $choice);
                                                $_choice = array_map('trim', $_choice);

                                                $_value = $_choice[0];
                                                $_label = $_choice[1];
                                            } else {
                                                $_value = trim($choice);
                                                $_label = $_value;
                                            }
                                            $_checked = '';
                                            if (in_array($_value, $values)) {
                                                $space = str_repeat(' ', 1);
                                                $output[] = "{$space}$_value";
                                            }
                                        }
                                        echo join(',', $output);

                                    } else {
                                        $content = apply_filters('get_the_content', $field_details);
                                        echo do_shortcode(wpautop($content));
                                        //echo esc_attr($field_details);
                                    } ?></p>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div><!-- end .atbd_custom_fields_contents -->
<?php }
wp_reset_query();