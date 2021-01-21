<?php
if (!empty($args['listing_info'])) {
    extract($args['listing_info']);
}
//@TODO: I will have to add a text area to get the content for the info window of the map later
$t = get_the_title();
$t = !empty($t) ? $t : __('No Title', 'directorist');
$tg = !empty($tagline) ? esc_html($tagline) : '';
$ad = !empty($address) ? esc_html($address) : '';
$image = (!empty($attachment_id[0])) ? "<img src='" . esc_url(wp_get_attachment_image_url($attachment_id[0], 'thumbnail')) . "'>" : '';
$post_ID = $post->ID;
// grab social information
$disable_price               = get_directorist_option('disable_list_price');
$currency                    = get_directorist_option('g_currency', 'USD');
$display_tagline_field       = get_directorist_option('display_tagline_field', 0);
$tagline_placeholder         = get_directorist_option('tagline_placeholder',__('Your Listing\'s motto or tag-line', 'directorist'));
$display_pricing_field       = get_directorist_option('display_pricing_field', 1);
$display_price_range_field   = get_directorist_option('display_price_range_field', 1);
$price_placeholder           = get_directorist_option('price_placeholder',__('Price of this listing. Eg. 100', 'directorist'));
$price_range_placeholder     = get_directorist_option('price_range_placeholder',__('Select Price Range', 'directorist'));
$excerpt_placeholder         = get_directorist_option('excerpt_placeholder',__('Short Description or Excerpt', 'directorist'));
$display_excerpt_field       = get_directorist_option('display_excerpt_field', 0);
$display_views_count         = apply_filters('atbdp_listing_form_view_count_field', get_directorist_option('display_views_count', 1));
$price_range_label           = get_directorist_option('price_range_label', __('Price Range', 'directorist'));
$c_symbol                    = atbdp_currency_symbol($currency);
$allow_decimal = get_directorist_option('allow_decimal', 1);
?>
<div id="directorist" class="directorist atbd_wrapper directory_wrapper">
    <?php
    /**
     * It fires before the listing tagline
     * @param string $type Page type.
     * @param array $args Current listing details.
     * @since 1.1.1
     **/
    do_action('atbdp_edit_before_tagline_fields', 'add_listing_page_backend', $args['listing_info']);
    ?>

    <div class="atbd_">
        <?php if (!empty($display_tagline_field)){ ?>
            <div class="form-group" id="atbdp_excerpt">
                <label for="atbdp_excerpt"><?php
                    $tagline_label = get_directorist_option('tagline_label', __('Tagline', 'directorist'));
                    esc_html_e($tagline_label.':', 'directorist');
                    ?></label>
                <input type="text" name="tagline"
                       id="has_tagline"
                       value="<?php echo !empty($tagline) ? esc_attr($tagline) : ''; ?>"
                       class="form-control directory_field"
                       placeholder="<?php echo esc_attr($tagline_placeholder); ?>"/>
            </div>
        <?php }?>
        <?php
        $price_range = !empty($price_range) ? $price_range : '';
        $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
        if (!empty($display_pricing_field || $display_price_range_field) ) { ?>
            <div class="form-group" id="atbd_pricing">
                <input type="hidden" id="atbd_listing_pricing" value="<?php echo $atbd_listing_pricing?>">
                <label for="#">
                    <?php
                    $pricing_label = get_directorist_option('pricing_label', __('Pricing', 'directorist'));
                    esc_html_e($pricing_label.':', 'directorist');
                    ?>
                </label>
                <div class="atbd_pricing_options">
                    <?php if (!empty($display_pricing_field)){ ?>
                    <label for="price_selected" data-option="price">
                        <input type="checkbox" value="price" id="price_selected" name="atbd_listing_pricing" <?php echo ('price' === $atbd_listing_pricing)?'checked':(('range' !== $atbd_listing_pricing)?'checked':'');?>>
                        <?php
                        $price_label = get_directorist_option('price_label', __('Price', 'directorist'));
                        $currency = get_directorist_option('g_currency', 'USD');
                        /*Translator: % is the name of the currency such eg. USD etc.*/
                        printf(esc_html__('%s [%s]', 'directorist'),$price_label, $currency); ?>
                    </label>
                    <?php }
                    if (!empty($display_price_range_field )){
                    if (!empty($display_pricing_field)){
                    ?>
                    <span class="bor"><?php _e('Or', 'directorist')?></span>
                    <?php }
                    ?>
                    <label for="price_range_selected" data-option="price_range">
                        <input type="checkbox" id="price_range_selected" value="range" name="atbd_listing_pricing" <?php echo ('range' === $atbd_listing_pricing)?'checked':'';?>>
                        <?php echo $price_range_label; ?>
                        <!--<p id='price_range_option'><?php /*echo __('Price Range', 'directorist'); */ ?></p></label>-->
                    </label>
                        <?php } ?>
                    <small> <?php _e('(Optional - Uncheck to hide pricing for this listing)', 'directorist')?></small>
                </div>
                <?php
                if (!empty($display_pricing_field)){
                    do_action('atbdp_add_listing_before_price_field', $post_ID);
                ?>
                <input type="hidden" id="pricerange_val" value="<?php echo $price_range;?>">
                <input type="number" <?php echo !empty($allow_decimal)?'step="any"':''; ?> id="price" name="price" value="<?php echo !empty($price) ? esc_attr($price) : ''; ?>"
                       class="form-control directory_field"
                       placeholder="<?php echo esc_attr($price_placeholder); ?>"/>
                <?php }
                if (!empty($display_price_range_field )){
                ?>
                <select class="form-control directory_field" id="price_range" style="display: none" name="price_range">
                    <option value=""><?php echo !empty($price_range_placeholder) ? esc_attr($price_range_placeholder) : ''; ?></option>
                    <option value="skimming" <?php selected($price_range, 'skimming'); ?>>
                        <?php echo __('Ultra High ', 'directorist').'('.$c_symbol,$c_symbol,$c_symbol,$c_symbol.')'; ?>
                    </option>
                    <option value="moderate" <?php selected($price_range, 'moderate'); ?>>
                        <?php echo __('Expensive ', 'directorist').'('.$c_symbol,$c_symbol,$c_symbol.')'; ?>
                    </option>
                    <option value="economy" <?php selected($price_range, 'economy'); ?>>
                        <?php echo __('Moderate ', 'directorist').'('.$c_symbol,$c_symbol.')'; ?>
                    </option>
                    <option value="bellow_economy" <?php selected($price_range, 'bellow_economy'); ?>>
                        <?php echo __('Cheap ', 'directorist').'('.$c_symbol.')'; ?>
                    </option>
                </select>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if(!empty($display_views_count)) { ?>
            <div class="form-group" id="atbdp_views_count">
                <label for="atbdp_views_count"><?php
                    $views_count_label = get_directorist_option('views_count_label', __('Views Count', 'directorist'));
                    esc_html_e($views_count_label.':', 'directorist'); ?></label>

                <input type="number" id="views_Count" name="atbdp_post_views_count"
                       value="<?php echo !empty($atbdp_post_views_count) ? esc_attr($atbdp_post_views_count) : ''; ?>" class="form-control directory_field"
                />
            </div>
        <?php } ?>
        <?php if (!empty($display_excerpt_field)){ ?>
            <div class="form-group" id="atbdp_excerpt_area">
                <label for="atbdp_excerpt"><?php
                    $excerpt_label = get_directorist_option('excerpt_label', __('Short Description/Excerpt', 'directorist'));
                    esc_html_e($excerpt_label.':', 'directorist'); ?></label>
                <input type="hidden" id="has_excerpt" value="<?php echo !empty($excerpt) ? esc_textarea(stripslashes($excerpt)) : ''; ?>">
                <textarea name="excerpt" id="atbdp_excerpt"
                          class="form-control directory_field" cols="30" rows="5"
                          placeholder="<?php echo esc_attr($excerpt_placeholder); ?>"><?php echo !empty($excerpt) ? esc_textarea(stripslashes($excerpt)) : ''; ?></textarea>
            </div>
        <?php }
        /**
         * @since 6.3
         */
        do_action('atbdp_add_listing_after_excerpt', $post_ID);
        ?>
        <!--***********************************************************************
        Run the custom field loop to show all published custom fields asign to form
        **************************************************************************-->
        <?php
        // custom fields information
        //// get all the custom field that has posted by admin ane return the field
        $custom_fields = array(
            'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            );
        $meta_queries = array();
        $meta_queries[] = array(
            'key' => 'associate',
            'value' => 'form',
            'compare' => 'LIKE'
        );
        $meta_queries = apply_filters('atbdp_custom_fields_meta_queries', $meta_queries);
        $count_meta_queries = count($meta_queries);
        if ($count_meta_queries) {
            $custom_fields['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
        }
        $custom_fields = new WP_Query( $custom_fields );

        $fields = $custom_fields->posts;
        foreach ($fields as $post) {
            setup_postdata($post);
            $post_id = $post->ID;
            $cf_required = get_post_meta($post_id, 'required', true);
            if ($cf_required) {
                $required = '';
            }
            $post_meta = get_post_meta($post_id);
            $instructions = get_post_meta(get_the_ID(), 'instructions', true);

            ?>

            <div class="form-group" id="atbdp_custom_field_area">
                <label for=""><?php the_title();
                if (!empty($instructions)){
                    ?>
                    <span class="atbd_tooltip" aria-label="<?php echo get_post_meta(get_the_ID(), 'instructions', true); ?>"><i class="fa fa-question-circle"></i></span>
                    <?php
                    }
                ?>
                </label>
                <?php
                if (isset($post_meta[$post->ID])) {
                    $value = $post_meta[0];
                }

                $cf_meta_default_val = $post_meta['default_value'];
                if (isset($post_id)) {
                    $cf_meta_default_val = $post_id;
                }
                $cf_meta_val = $post_meta['type'][0];
                $cf_rows = $post_meta['rows'][0];
                $cf_placeholder = '';
                $value = get_post_meta($post_ID, $post_id, true); ///store the value for the db

                switch ($cf_meta_val) {
                    case 'text' :
                        echo '<div>';
                        printf('<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post_id, $cf_placeholder, esc_attr($value));
                        echo '</div>';
                        break;
                    case 'number' :
                        echo '<div>';
                        printf('<input type="number" %s name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', !empty($allow_decimal)?'step="any"':'', $post_id, $cf_placeholder, esc_attr($value));
                        echo '</div>';
                        break;
                    case 'textarea' :
                        printf('<textarea  class="form-control directory_field" name="custom_field[%d]" class="textarea" rows="%d" placeholder="%s">%s</textarea>', $post->ID, (int)$cf_rows, esc_attr($cf_placeholder), esc_textarea($value));
                        break;
                    case 'radio':
                        $choices = get_post_meta($post_id, 'choices', true);
                        $choices = explode("\n", $choices);
                        echo '<ul class="atbdp-radio-list radio vertical">';
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
                            if (trim($value) == $_value) $_checked = ' checked="checked"';

                            printf('<li><label><input type="radio" name="custom_field[%d]" value="%s"%s>%s</label></li>', $post->ID, $_value, $_checked, $_label);
                        }
                        echo '</ul>';
                        break;

                    case 'select' :
                        $choices = get_post_meta($post_id, 'choices', true);
                        $choices = explode("\n", $choices);
                        printf('<select name="custom_field[%d]" class="form-control directory_field">', $post->ID);
                        if (!empty($field_meta['allow_null'][0])) {
                            printf('<option value="">%s</option>', '- ' . __('Select an Option', 'directorist') . ' -');
                        }
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

                            $_selected = '';
                            if (trim($value) == $_value) $_selected = ' selected="selected"';

                            printf('<option value="%s"%s>%s</option>', $_value, $_selected, $_label);
                        }
                        echo '</select>';
                        break;

                    case 'checkbox' :
                        $choices = get_post_meta($post_id, 'choices', true);
                        $choices = explode("\n", $choices);

                        $values = explode("\n", $value);
                        $values = array_map('trim', $values);
                        echo '<ul class="atbdp-checkbox-list checkbox vertical">';

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
                            if (in_array($_value, $values)) $_checked = ' checked="checked"';

                            printf('<li><label><input type="hidden" name="custom_field[%s][]" value="" /><input type="checkbox" name="custom_field[%d][]" value="%s"%s>%s</label></li>', $post->ID, $post->ID, $_value, $_checked, $_label);
                        }
                        echo '</ul>';
                        break;
                    case 'url'  :
                        echo '<div>';
                        printf('<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr($cf_placeholder), esc_url($value));
                        echo '</div>';
                        break;

                    case 'date'  :
                        echo '<div>';
                        printf('<input type="date" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr($cf_placeholder), esc_attr($value));
                        echo '</div>';
                        break;

                    case 'email'  :
                        echo '<div>';
                        printf('<input type="email" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr($cf_placeholder), esc_attr($value));
                        echo '</div>';
                        break;

                    case 'color'  :
                        echo '<div>';
                        ?>
                        <script>
                            jQuery(document).ready(function ($) {
                                $('.my-color-field2').wpColorPicker().empty();
                            });
                        </script>
                        <?php
                        printf('<input type="color" name="custom_field[%d]" class="my-color-field2" value="%s" />', $post->ID, $value);
                        echo '</div>';
                        break;
                    case 'time'  :
                        echo '<div>';
                        printf('<input type="time" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr($cf_placeholder), esc_attr($value));
                        echo '</div>';
                        break;
                    case 'file'  :
                        require ATBDP_VIEWS_DIR . 'file-uploader.php';
                        break;
                }
                ?>
            </div>
            <?php
        }
        wp_reset_postdata();
        ?>
        <!--***********************************************************************
         Run the custom field loop to show all published custom fields assign to Category
         **************************************************************************-->
        <div id="category_container">
            <!--@ Options for select the category.-->

            <div class="form-group" id="atbdp_custom_field_area">
                <?php
                $current_val = get_the_terms($post_ID, ATBDP_CATEGORY);;
                $ids = array();
                if (!empty($current_val)) {
                    foreach ($current_val as $single_val) {
                        $ids[] = $single_val->term_id;
                    }
                }
                ?>
                <input type="hidden" id="value_selected" value="<?php echo json_encode($ids); ?>">
            </div>
        </div>

        <div id="atbdp-custom-fields-list" data-post_id="<?php echo $post_ID; ?>">
            <?php
            $selected_category = !empty($selected_category) ? $selected_category : '';
            do_action('wp_ajax_atbdp_custom_fields_listings', $post_ID, $selected_category); ?>
        </div>
        <?php

        if ($ids) {
            ?>
            <div id="atbdp-custom-fields-list-selected" data-post_id="<?php echo $post_ID; ?>">
                <?php
                do_action('wp_ajax_atbdp_custom_fields_listings_selected', $post_ID, $ids); ?>
            </div>
            <?php
        }
        ?>
    </div>


    <?php
    /**
     * It fires after the google map preview area
     * @param string $type Page type.
     * @param array $listing_info Information of the current listing
     * @since 1.1.1
     **/
    do_action('atbdp_edit_after_googlemap_preview', 'add_listing_page_backend', 'listing_info', get_the_ID());
    ?>
</div>