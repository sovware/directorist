<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Forms {

    protected static $instance = null;

    public $add_listing_id;
    public $add_listing_post;

    public function __construct() {
        $this->add_listing_id   = get_query_var('atbdp_listing_id', 0);
        $this->add_listing_post = !empty($this->add_listing_id) ? get_post($this->add_listing_id) : '';
    }

    public static function instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function get_add_listing_id() {
        return $this->add_listing_id;
    }

    public function get_add_listing_post() {
        return $this->add_listing_post;
    }

    public function get_custom_fields_query() {
        $p_id    = $this->get_add_listing_id();
        $fm_plan = get_post_meta($p_id, '_fm_plans', true);

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
        $plan_custom_field = true;
        if (is_fee_manager_active()) {
            $plan_custom_field = is_plan_allowed_custom_fields($fm_plan);
        }
        if ($plan_custom_field) {
            $fields = $custom_fields->posts;
        } else {
            $fields = array();
        }

        return $fields;
    }

    public function get_custom_field_input($id, $value) {
        $post_id = $id;
        $cf_meta_val = get_post_meta($id, 'type', true);
        $cf_rows = get_post_meta($id, 'rows', true);
        $cf_placeholder = '';
        
        ob_start();

        switch ($cf_meta_val) {
            case 'text' :
                echo '<div>';
                printf('<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $id, $cf_placeholder, $value);
                echo '</div>';
                break;
            case 'number' :
                echo '<div>';
                printf('<input type="number" %s  name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', !empty($allow_decimal) ? 'step="any"' : '', $id, $cf_placeholder, $value);
                echo '</div>';
                break;
            case 'textarea' :
                echo '<div>';
                $row = ( (int)$cf_rows > 0 ) ? (int)$cf_rows : 1;
                printf('<textarea  class="form-control directory_field" name="custom_field[%d]" rows="%d" placeholder="%s">%s</textarea>', $id, $row, esc_attr($cf_placeholder), esc_textarea($value));
                echo '</div>';
                break;
            case 'radio':
                echo '<div>';
                $choices = get_post_meta($id, 'choices', true);
                $choices = explode("\n", $choices);
                echo '<ul class="atbdp-radio-list vertical">';
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

                    printf('<li><label><input type="radio" name="custom_field[%d]" value="%s"%s>%s</label></li>', $id, $_value, $_checked, $_label);
                }
                echo '</ul>';
                echo '</div>';
                break;

            case 'select' :
                echo '<div>';
                $choices = get_post_meta($id, 'choices', true);
                $choices = explode("\n", $choices);
                printf('<select name="custom_field[%d]" class="form-control directory_field">', $id);
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
                echo '</div>';
                break;

            case 'checkbox' :
                echo '<div>';
                $choices = get_post_meta($id, 'choices', true);
                $choices = explode("\n", $choices);

                $values = explode("\n", $value);
                $values = array_map('trim', $values);
                echo '<ul class="atbdp-checkbox-list vertical">';

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

                    printf('<li><label><input type="hidden" name="custom_field[%s][]" value="" /><input type="checkbox" name="custom_field[%d][]" value="%s"%s> %s</label></li>', $id, $id, $_value, $_checked, $_label);
                }
                echo '</ul>';
                echo '</div>';
                break;
            case 'url'  :
                echo '<div>';
                printf('<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $id, esc_attr($cf_placeholder), esc_url($value));
                echo '</div>';
                break;

            case 'date'  :
                echo '<div>';
                printf('<input type="date" name="custom_field[%d]" class="form-control directory_field" value="%s"/>', $id, esc_attr($value));
                echo '</div>';
                break;

            case 'email'  :
                echo '<div>';
                printf('<input type="email" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $id, esc_attr($cf_placeholder), esc_attr($value));
                echo '</div>';
                break;
            case 'color'  :
                echo '<div>';
                printf('<input type="text" name="custom_field[%d]" id="color_code2" class="my-color-field" value="%s"/>', $id, $value);
                echo '</div>';
                break;

            case 'time'  :
                echo '<div>';
                printf('<input type="time" name="custom_field[%d]" class="form-control directory_field" value="%s"/>', $id, esc_attr($value));
                echo '</div>';
                break;
            case 'file'  :
                require ATBDP_TEMPLATES_DIR . 'file-uploader.php';
                break;
        }

        return ob_get_clean();
    }

    public function get_plan_video() {
		$p_id    = $this->get_add_listing_id();
		$fm_plan = get_post_meta($p_id, '_fm_plans', true);

        $display_video_for   = get_directorist_option('display_video_for', 0);
        $display_video_field = get_directorist_option('display_video_field', 1);
        
        $plan_video = false;

        if (is_fee_manager_active()) {
            $plan_video = is_plan_allowed_listing_video($fm_plan);
        }
        elseif (empty($display_video_for) && !empty($display_video_field)) {
            $plan_video = true;
        }

        return $plan_video;
    }

    public function get_plan_slider() {
		$p_id    = $this->get_add_listing_id();
		$fm_plan = get_post_meta($p_id, '_fm_plans', true);

        $display_glr_img_for   = get_directorist_option('display_glr_img_for', 0);
        $display_gallery_field = get_directorist_option('display_gallery_field', 1);
        
        $plan_slider = false;

        if (is_fee_manager_active()) {
            $plan_slider = is_plan_allowed_slider($fm_plan);
        }
        elseif (empty($display_glr_img_for) && !empty($display_gallery_field)) {
            $plan_slider = true;
        }

        return $plan_slider;       
    }

    public function get_add_listing_image_title() {
        if ($this->get_plan_video() && $this->get_plan_slider()) {
            $title = __("Images & Video", 'directorist');
        }
        elseif ($this->get_plan_slider()) {
            $title =__("Images", 'directorist');
        }
        elseif ($this->get_plan_video()) {
           $title = __("Video", 'directorist');
        }
        else {
            $title = '';
        }

        return $title;
    }

    public function get_map_info_content() {
        $p_id = $this->get_add_listing_id();

        $tagline = get_post_meta($p_id, '_tagline', true);
        $address = get_post_meta($p_id, '_address', true);
        $listing_img = atbdp_get_listing_attachment_ids($p_id);
        $t = get_the_title();
        $t = !empty($t) ? esc_html($t) : __('No Title ', 'directorist');
        $tg = !empty($tagline) ? esc_html($tagline) : '';
        $ad = !empty($address) ? esc_html($address) : '';
        $image = (!empty($listing_img[0])) ? "<img src='" . esc_url(wp_get_attachment_image_url($listing_img[0], 'thumbnail')) . "'>" : '';
        /*build the markup for google map info window*/
        $info_content = "<div class='map_info_window'> <h3> {$t} </h3>";
        $info_content .= "<p> {$tg} </p>";
        $info_content .= $image; // add the image if available
        $info_content .= "<p> {$ad}</p></div>";
        return $info_content;
    }

    public function get_listing_info() {
        $listing_info = array();

        $p_id = $this->get_add_listing_id();

        if (!empty($p_id)) {
            $listing_info['never_expire'] = get_post_meta($p_id, '_never_expire', true);
            $listing_info['featured'] = get_post_meta($p_id, '_featured', true);
            $listing_info['listing_type'] = get_post_meta($p_id, '_listing_type', true);
            $listing_info['price'] = get_post_meta($p_id, '_price', true);
            $listing_info['videourl'] = get_post_meta($p_id, '_videourl', true);
            $listing_info['price_range'] = get_post_meta($p_id, '_price_range', true);
            $listing_info['atbd_listing_pricing'] = get_post_meta($p_id, '_atbd_listing_pricing', true);
            $listing_info['listing_status'] = get_post_meta($p_id, '_listing_status', true);
            $listing_info['tagline'] = get_post_meta($p_id, '_tagline', true);
            $listing_info['atbdp_post_views_count'] = get_post_meta($p_id, '_atbdp_post_views_count', true);
            $listing_info['excerpt'] = get_post_meta($p_id, '_excerpt', true);
            $listing_info['address'] = get_post_meta($p_id, '_address', true);
            $listing_info['phone'] = get_post_meta($p_id, '_phone', true);
            $listing_info['phone2'] = get_post_meta($p_id, '_phone2', true);
            $listing_info['fax'] = get_post_meta($p_id, '_fax', true);
            $listing_info['email'] = get_post_meta($p_id, '_email', true);
            $listing_info['website'] = get_post_meta($p_id, '_website', true);
            $listing_info['zip'] = get_post_meta($p_id, '_zip', true);
            $listing_info['social'] = get_post_meta($p_id, '_social', true);
            $listing_info['faqs'] = get_post_meta($p_id, '_faqs', true);
            $listing_info['manual_lat'] = get_post_meta($p_id, '_manual_lat', true);
            $listing_info['manual_lng'] = get_post_meta($p_id, '_manual_lng', true);
            $listing_info['hide_map'] = get_post_meta($p_id, '_hide_map', true);
            $listing_info['bdbh'] = get_post_meta($p_id, '_bdbh', true);
            $listing_info['enable247hour'] = get_post_meta($p_id, '_enable247hour', true);
            $listing_info['disable_bz_hour_listing'] = get_post_meta($p_id, '_disable_bz_hour_listing', true);
            $listing_info['hide_contact_info'] = get_post_meta($p_id, '_hide_contact_info', true);
            $listing_info['hide_contact_owner'] = get_post_meta($p_id, '_hide_contact_owner', true);
            $listing_info['expiry_date'] = get_post_meta($p_id, '_expiry_date', true);
            $listing_info['t_c_check'] = get_post_meta($p_id, '_t_c_check', true);
            $listing_info['privacy_policy'] = get_post_meta($p_id, '_privacy_policy', true);
            $listing_info['id_itself'] = $p_id;
        }

        return $listing_info;
    }
    
    public function render_shortcode_add_listing($atts) {
        ob_start();
        wp_enqueue_script('adminmainassets');

        $guest_submission = get_directorist_option('guest_listings', 0);
        if ( false === $guest_submission && ! atbdp_logged_in_user() ) {
            return ATBDP_Helper::guard( ['type' => 'auth'] );
        }

        $p_id = $this->get_add_listing_id();

        if (!empty($p_id)) {
            $listing = get_post($p_id);
            if ($listing->post_author != get_current_user_id() && !current_user_can('edit_others_at_biz_dirs')) {
                echo '<p class="error">' . __('You do not have permission to edit this listing', 'directorist') . '</p>';
                return ob_get_clean();
            }
        }

        $args = array(
            'p_id'               => $p_id,
            'listing_info'       => $this->get_listing_info(),
            'container_fluid'    => is_directoria_active() ? 'container' : 'container-fluid',
            'select_listing_map' => get_directorist_option('select_listing_map', 'google'),
            'display_map_for'    => get_directorist_option('display_map_for', 0),
            'display_map_field'  => get_directorist_option('display_map_field', 1),
            'manual_lat'         => get_post_meta($p_id, '_manual_lat', true),
            'manual_lng'         => get_post_meta($p_id, '_manual_lng', true),
            'default_latitude'   => get_directorist_option('default_latitude', '40.7127753'),
            'default_longitude'  => get_directorist_option('default_longitude', '-74.0059728'),
            'info_content'       => $this->get_map_info_content(),
            'map_zoom_level'     => get_directorist_option('map_zoom_level', 4),
            'marker_title'       => __('You can drag the marker to your desired place to place a marker', 'directorist'),
            'geocode_error_msg'  => __('Geocode was not successful for the following reason: ', 'directorist'),
            'map_icon'           => ATBDP_PUBLIC_ASSETS . 'images/map-icon.png',
        );

        $display_map = ( empty( $args['display_map_for'] ) && ! empty( $args['display_map_field'] ) ) ? true : false;
        
        if ( $display_map && 'openstreet' === $args['select_listing_map'] ) {
            wp_localize_script( 'atbdp-add-listing-osm', 'localized_data', $args );
            wp_enqueue_script( 'atbdp-add-listing-osm' );
        }

        if ( $display_map && 'google' === $args['select_listing_map'] ) {
            wp_localize_script( 'atbdp-add-listing-gmap', 'localized_data', $args );
            wp_enqueue_script( 'atbdp-add-listing-gmap' );
        }

        atbdp_get_shortcode_template( 'forms/add-listing', $args );

        return ob_get_clean();
    }

    public function render_shortcode_user_login() {
        ob_start();

        if ( atbdp_logged_in_user() ) {
            $error_message = sprintf(__('Login page is not for logged-in user. <a href="%s">Go to Dashboard</a>', 'directorist'), esc_url(ATBDP_Permalink::get_dashboard_page_link()));
            ATBDP()->helper->show_login_message(apply_filters('atbdp_login_page_loggedIn_msg', $error_message));

            return ob_get_clean();
        }

        atbdp_get_shortcode_template( 'forms/login' );

        return ob_get_clean();
    }

    public function render_shortcode_custom_registration() {
        ob_start();

        if ( !atbdp_logged_in_user() ) {

            $args = array(
                'parent' => 0,
                'container_fluid'             => is_directoria_active() ? 'container' : 'container-fluid',
                'username'                    => get_directorist_option('reg_username','Username'),
                'password'                    => get_directorist_option('reg_password','Password'),
                'display_password_reg'        => get_directorist_option('display_password_reg',1),
                'require_password'            => get_directorist_option('require_password_reg',1),
                'email'                       => get_directorist_option('reg_email','Email'),
                'display_website'             => get_directorist_option('display_website_reg',0),
                'website'                     => get_directorist_option('reg_website','Website'),
                'require_website'             => get_directorist_option('require_website_reg',0),
                'display_fname'               => get_directorist_option('display_fname_reg',0),
                'first_name'                  => get_directorist_option('reg_fname','First Name'),
                'require_fname'               => get_directorist_option('require_fname_reg',0),
                'display_lname'               => get_directorist_option('display_lname_reg',0),
                'last_name'                   => get_directorist_option('reg_lname','Last Name'),
                'require_lname'               => get_directorist_option('require_lname_reg',0),
                'display_bio'                 => get_directorist_option('display_bio_reg',0),
                'bio'                         => get_directorist_option('reg_bio','About/bio'),
                'require_bio'                 => get_directorist_option('require_bio_reg',0),
                'reg_signup'                  => get_directorist_option('reg_signup','Sign Up'),
                'display_login'               => get_directorist_option('display_login',1),
                'login_text'                  => get_directorist_option('login_text',__('Already have an account? Please login', 'directorist')),
                'login_url'                   => ATBDP_Permalink::get_login_page_link(),
                'log_linkingmsg'              => get_directorist_option('log_linkingmsg',__('Here', 'directorist')),
                'terms_label'                 => get_directorist_option('regi_terms_label', __('I agree with all', 'directorist')),
                'terms_label_link'            => get_directorist_option('regi_terms_label_link', __('terms & conditions', 'directorist')),
                't_C_page_link'               => ATBDP_Permalink::get_terms_and_conditions_page_url(),
                'privacy_page_link'           => ATBDP_Permalink::get_privacy_policy_page_url(),
                'privacy_label'               => get_directorist_option('registration_privacy_label', __('I agree to the', 'directorist')),
                'privacy_label_link'          => get_directorist_option('registration_privacy_label_link', __('Privacy & Policy', 'directorist')),
            );

            atbdp_get_shortcode_template( 'forms/registration', $args );
        }
        else {
            $error_message = sprintf(__('Registration page is only for unregistered user. <a href="%s">Go to Dashboard</a>', 'directorist'), esc_url(ATBDP_Permalink::get_dashboard_page_link()));
            ATBDP()->helper->show_login_message(apply_filters('atbdp_registration_page_registered_msg', $error_message));
        }

        return ob_get_clean();
    }

}