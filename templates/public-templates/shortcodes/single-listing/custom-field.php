<?php
if (!empty($has_field) && $plan_custom_field) {
    echo "string";
    ?>
    <div class="atbd_content_module atbd_custom_fields_contents">
        <div class="atbd_content_module_title_area">
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
                                <div style="margin-bottom: 10px;"><?php if ('color' == $field_type) {
                                        printf('<div class="atbd_field_type_color" style="background-color: %s;"></div>', $field_details);
                                    } elseif ($field_type === 'date') {
                                        $date_format = get_option( 'date_format' );
                                        echo date($date_format, strtotime($field_details));
                                    }elseif ($field_type === 'time') {
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
                                    } ?></div>
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