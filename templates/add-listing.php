<?php
if (!empty($args['listing_info'])) { extract($args['listing_info']); }
//@TODO: I will have to add a text area to get the content for the info window of the map later
$t = get_the_title();
$t = !empty($t)? $t : __('No Title', ATBDP_TEXTDOMAIN);
$tg = !empty($tagline)? esc_html($tagline) : '';
$ad = !empty($address)? esc_html($address) : '';
$image = (!empty($attachment_id[0])) ? "<img src='". esc_url(wp_get_attachment_image_url($attachment_id[0], 'thumbnail'))."'>": '';
$post_ID = $post->ID;
// grab social information
$disable_price = get_directorist_option('disable_list_price');
$currency = get_directorist_option('g_currency', 'USD');
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
                    <!--@todo; add toggle for the moto and excerpt later. -->
                    <div class="form-group">
                        <label for="atbdp_tagline"><?php esc_html_e('Tag-line/Motto', ATBDP_TEXTDOMAIN) ?></label>
                        <input type="text" id="atbdp_tagline" name="tagline" value="<?= !empty($tagline) ? esc_attr($tagline): ''; ?>" class="form-control directory_field" placeholder="<?= __('Your Organization\'s motto or tag-line', ATBDP_TEXTDOMAIN); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="atbdp_excerpt"><?php esc_html_e('Short Description/Excerpt(eg. Text shown on Image Hover in grid layout):', ATBDP_TEXTDOMAIN) ?></label>
                        <textarea name="excerpt" id="atbdp_excerpt"  class="form-control directory_field" cols="30" rows="5" placeholder="<?= __('Short Description or Excerpt', ATBDP_TEXTDOMAIN); ?>"><?= !empty($excerpt) ?esc_textarea( stripslashes($excerpt)): ''; ?></textarea>
                    </div>
                    <?php if (!$disable_price){ ?>
                        <div class="form-group">
                            <!--@todo; Add currency Name near price-->
                            <label for="price"><?php
                                /*Translator: % is the name of the currency such eg. USD etc.*/
                                printf(esc_html__('Price [%s] ( Optional---Leave it blank to hide it)', ATBDP_TEXTDOMAIN), $currency); ?></label>
                            <input type="text" id="price" name="price" value="<?= !empty($price) ? esc_attr($price): ''; ?>" class="form-control directory_field" placeholder="<?= __('Price of this listing. Eg. 100', ATBDP_TEXTDOMAIN); ?>"/>
                        </div>
                    <?php } ?>


                    <!--***********************************************************************
                    Run the custom field loop to show all published custom fields asign to form
                    **************************************************************************-->
                    <?php
                    // custom fields information
                    //// get all the custom field that has posted by admin ane return the field
                    $custom_fields  = new WP_Query( array(
                        'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'meta_key'       => 'associate',
                        'meta_value'     => 'form'
                    ) );
                    $fields = $custom_fields->posts;

                    foreach ($fields as $post){
                        setup_postdata($post);
                        $post_id = $post->ID;
                        $cf_required = get_post_meta($post_id, 'required', true);
                        if ($cf_required){
                            $required = '';
                        }
                        $post_meta = get_post_meta( $post_id );

                        ?>

                        <div class="form-group">
                            <label for=""><?php the_title(); ?><?php if($cf_required){echo '<span style="color: red"> *</span>'; }?></label>
                            <?php
                            if( isset( $post_meta[ $post->ID ] ) ) {
                                $value = $post_meta[0];
                            }

                            $cf_meta_default_val = $post_meta['default_value'];
                            if( isset( $post_id ) ) {
                                $cf_meta_default_val = $post_id[0];
                            }
                            $cf_meta_val = $post_meta['type'][0];
                            $cf_rows = $post_meta['rows'][0];
                            $cf_placeholder = $post_meta['placeholder'][0];
                            $value =  get_post_meta($post_ID, $post_id, true); ///store the value for the db

                            switch ($cf_meta_val){
                                case 'text' :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>',$post_id,$cf_placeholder, esc_attr( $value ) );
                                    echo '</div>';
                                    break;
                                case 'textarea' :
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);

                                    printf( '<textarea  class="form-control directory_field" name="custom_field[%d]" class="textarea" rows="%d" placeholder="%s">%s</textarea>', $post->ID, (int) $cf_rows,esc_attr( $cf_placeholder ), esc_textarea( $value ) );
                                    break;
                                case 'radio':
                                    $choices = get_post_meta($post_id, 'choices', true);
                                    $choices = explode( "\n", $choices );
                                    printf('<p style="font-style: italic">%s</p>', $value);
                                    echo '<ul class="atbdp-radio-list radio vertical">';
                                    foreach( $choices as $choice ) {
                                        if( strpos( $choice, ':' ) !== false ) {
                                            $_choice = explode( ':', $choice );
                                            $_choice = array_map( 'trim', $_choice );

                                            $_value  = $_choice[0];
                                            $_label  = $_choice[1];
                                        } else {
                                            $_value  = trim( $choice );
                                            $_label  = $_value;
                                        }

                                        $_checked = '';
                                        if( trim( $value ) == $_value ) $_checked = ' checked="checked"';

                                        printf( '<li><label><input type="radio" name="custom_field[%d]" value="%s"%s>%s</label></li>', $post->ID, $_value, $_checked, $_label );
                                    }
                                    echo '</ul>';
                                    break;

                                case 'select' :
                                    $choices = get_post_meta($post_id, 'choices', true);
                                    $choices = explode( "\n", $choices );
                                    printf('<p style="font-style: italic">%s</p>', get_post_meta($post_id, 'instructions', true));
                                    printf( '<select name="custom_field[%d]" class="form-control directory_field">', $post->ID );
                                    if( ! empty( $field_meta['allow_null'][0] ) ) {
                                        printf( '<option value="">%s</option>', '- '.__( 'Select an Option', 'advanced-classifieds-and-directory-pro' ).' -' );
                                    }
                                    foreach( $choices as $choice ) {
                                        if( strpos( $choice, ':' ) !== false ) {
                                            $_choice = explode( ':', $choice );
                                            $_choice = array_map( 'trim', $_choice );

                                            $_value  = $_choice[0];
                                            $_label  = $_choice[1];
                                        } else {
                                            $_value  = trim( $choice );
                                            $_label  = $_value;
                                        }

                                        $_selected = '';
                                        if( trim( $value ) == $_value ) $_selected = ' selected="selected"';

                                        printf( '<option value="%s"%s>%s</option>', $_value, $_selected, $_label );
                                    }
                                    echo '</select>';
                                    break;

                                case 'checkbox' :
                                    $choices = get_post_meta($post_id, 'choices', true);
                                    $choices = explode( "\n", $choices );

                                    $values = explode( "\n", $value );
                                    $values = array_map( 'trim', $values );
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    echo '<ul class="atbdp-checkbox-list checkbox vertical">';

                                    foreach( $choices as $choice ) {
                                        if( strpos( $choice, ':' ) !== false ) {
                                            $_choice = explode( ':', $choice );
                                            $_choice = array_map( 'trim', $_choice );

                                            $_value  = $_choice[0];
                                            $_label  = $_choice[1];
                                        } else {
                                            $_value  = trim( $choice );
                                            $_label  = $_value;
                                        }

                                        $_checked = '';
                                        if( in_array( $_value, $values ) ) $_checked = ' checked="checked"';

                                        printf( '<li><label><input type="hidden" name="custom_field[%s][]" value="" /><input type="checkbox" name="custom_field[%d][]" value="%s"%s>%s</label></li>', $post->ID, $post->ID, $_value, $_checked, $_label );
                                    }
                                    echo '</ul>';
                                    break;
                                case 'url'  :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_url( $value ) );
                                    echo '</div>';
                                    break;

                                case 'date'  :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="date" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_attr( $value ) );
                                    echo '</div>';
                                    break;

                                    case 'email'  :
                                    echo '<div>';
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="email" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_attr( $value ) );
                                    echo '</div>';
                                    break;

                                case 'color'  :
                                    echo '<div>';
                                    ?>
                                <script>
                                    jQuery(document).ready(function($){
                                        $('.my-color-field2').wpColorPicker();
                                    });
                                </script>
                            <?php
                                    printf('<p style="font-style: italic">%s</p>', $post_meta['instructions'][0]);
                                    printf( '<input type="color" name="custom_field[%d]" class="my-color-field2" value="%s" />', $post->ID, $value );
                                    echo '</div>';
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

                        <div class="form-group">
                            <label for="atbdp_select_cat"><?php esc_html_e('Select Category', ATBDP_TEXTDOMAIN) ?></label>
                            <?php
                            $current_val = esc_attr(get_post_meta($post_ID, '_admin_category_select', true) );
                            $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
                            echo '<select class="form-control directory_field" id="cat-type" name="admin_category_select">';
                            echo '<option>'.__( "--Select a Category--", ATBDP_TEXTDOMAIN ).'</option>';
                            foreach ($categories as $key => $cat_title){
                                $term_id = $cat_title->term_id;
                                printf( '<option value="%s" %s>%s</option>', $term_id, selected( $term_id, $current_val), $cat_title->name );
                            }
                            echo '</select>';
                                $term_id_selected = $current_val;
                            ?>
                            <input type="hidden" id="value_selected" value="<?php echo $term_id_selected?>">
                        </div>
                    </div>

                    <div  id="atbdp-custom-fields-list" data-post_id="<?php echo $post_ID; ?>">
                        <?php
                        $selected_category = !empty($selected_category) ? $selected_category : '';
                        do_action( 'wp_ajax_atbdp_custom_fields_listings', $post_ID, $selected_category ); ?>
                    </div>
                    <?php
                    if ($term_id_selected){
                        ?>
                        <div  id="atbdp-custom-fields-list-selected" data-post_id="<?php echo $post_ID; ?>">
                            <?php
                            $selected_category = !empty($selected_category) ? $selected_category : '';
                            do_action( 'wp_ajax_atbdp_custom_fields_listings_selected', $post_ID, $selected_category ); ?>
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
            do_action('3', 'add_listing_page_backend', $args['listing_info'], get_the_ID());
            ?>
</div>
<script>

    // Bias the auto complete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.

    jQuery(document).ready(function ($) {

        // Load custom fields of the selected category in the custom post type "atbdp_listings"
        $( '#cat-type' ).on( 'change', function() {
            $( '#atbdp-custom-fields-list' ).html( '<div class="spinner"></div>' );

            var data = {
                'action'  : 'atbdp_custom_fields_listings',
                'post_id' : $( '#atbdp-custom-fields-list' ).data('post_id'),
                'term_id' : $(this).val()
            };

            $.post( ajaxurl, data, function(response) {
                $( '#atbdp-custom-fields-list' ).html( response );
            });
            $('#atbdp-custom-fields-list-selected').hide();

        });
        var selected_cat = $('#value_selected').val();
        if(!selected_cat){

        }else{
            $(window).on("load", function () {
                $('#atbdp-custom-fields-list-selected').html('<div class="spinner"></div>');
                var data = {
                    'action': 'atbdp_custom_fields_listings_selected',
                    'post_id': $('#atbdp-custom-fields-list-selected').data('post_id'),
                    'term_id': selected_cat
                };
                $.post(ajaxurl, data, function (response) {
                    $('#atbdp-custom-fields-list-selected').html(response);
                });
            });
        }



    }); // ends jquery ready function.







</script>