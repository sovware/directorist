<?php
global $wp_query;
!empty($args['data']) ? extract($args['data']) : array();
$listings               = !empty($listings) ? $listings : array();
$is_disable_price       = get_directorist_option('disable_list_price');
$search_listing_columns = get_directorist_option('search_listing_columns',3);
$display_header         = get_directorist_option('search_header',1);
$display_sortby         = get_directorist_option('search_sort_by',1);
$header_title           = get_directorist_option('search_header_title',__('Search Result: ', ATBDP_TEXTDOMAIN));
$header_sub_title       = get_directorist_option('search_header_sub_title',__('Total Listing Found: ', ATBDP_TEXTDOMAIN));
// Pagination fix
$temp_query             = $wp_query;
$wp_query               = NULL;
$wp_query               = $listings;
/*build appropriate search result text to display based on different situation*/
$in_s_string_text       = !empty($s_string) ? sprintf(__('Search Result for: "%s"', ATBDP_TEXTDOMAIN), $s_string) : $header_title;
$in_tag_text            = !empty($in_tag) ? sprintf(__(' from "%s" Tag', ATBDP_TEXTDOMAIN), $in_tag) : '';
$in_cat_text            = !empty($in_cat) ? sprintf(__(' from "%s" Category', ATBDP_TEXTDOMAIN), $in_cat) : '';
$in_loc_text            = !empty($in_loc) ? sprintf(__(' in "%s" Location', ATBDP_TEXTDOMAIN), $in_loc) : '';
$column_width           = 100/$search_listing_columns .'%';
?>
    <div id="directorist" class="atbd_wrapper directorist directory_wrapper search_area">
        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
            <?php if ( !empty($display_header) ) {?>
            <div class="header_bar">
                <div class="row">
                    <div class="col-md-12">
                        <div class="atbd_generic_header">
                            <div class="atbd_generic_header_title">
                                <h3>
                                    <?php
                                    // show appropriate text for the search
                                    if (!empty($in_cat) || !empty($in_tag) || !empty($in_loc)){
                                        echo $in_s_string_text, $in_tag_text, $in_cat_text, $in_loc_text;
                                    }else{
                                        if (!empty($s_string)){
                                            printf(__('%s for: %s from All categories and locations', ATBDP_TEXTDOMAIN),$header_title, '<span>'."'$s_string'".'</span>');
                                        }else{
                                            _e('Showing Result from all categories and locations.', ATBDP_TEXTDOMAIN);
                                        }
                                    }

                                    ?>
                                </h3>
                                <p>
                                    <?php
                                    echo $header_sub_title . ' ';
                                    if ($paginate){
                                        echo $listings->found_posts;
                                    }else{
                                        echo count($listings->posts);
                                    }
                                    ?>
                                </p>
                            </div>

                            <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                <?php if (!empty($display_sortby)) {?>
                                <!-- Orderby dropdown -->
                                <div class="dropdown">
                                    <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php _e( "Sort by", ATBDP_TEXTDOMAIN ); ?> <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                        <?php
                                        $options = atbdp_get_listings_orderby_options();

                                        foreach( $options as $value => $label ) {
                                            $active_class = ( $value == $current_order ) ? ' active' : '';
                                            printf( '<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg( 'sort', $value ), $label );
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!--maybe we should removed the parent container so that it can match perfectly -->
            <div class="row" <?php echo (get_directorist_option('grid_view_as','masonry_grid') !== 'masonry_grid')?'':'data-uk-grid';?>>
                <?php
                if ( count($listings->posts) ) {
                    while ( $listings->have_posts() ) {
                        $listings->the_post();
                        $l_ID                           = get_the_ID(); // cache it, save several functions calls.
                        $cats                           =  get_the_terms($l_ID, ATBDP_CATEGORY);
                        $locs                           =  get_the_terms($l_ID, ATBDP_LOCATION);
                        $address                        = get_post_meta(get_the_ID(), '_address', true);
                        $phone_number                   = get_post_meta(get_the_Id(), '_phone', true);
                        $featured                       = get_post_meta($l_ID, '_featured', true);
                        $price                          = get_post_meta($l_ID, '_price', true);
                        $price_range                    = get_post_meta($l_ID, '_price_range', true);
                        $atbd_listing_pricing            = get_post_meta($l_ID, '_atbd_listing_pricing', true);

                        /*@todo; As listings on search page and the all listing page, and user dashboard is nearly the same, so try to refactor them to a function later using some condition to show some extra fields on the listing on user dashboard*/
                        $listing_img                    = get_post_meta(get_the_ID(), '_listing_img', true);
                        $listing_prv_img                = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                        $excerpt                        = get_post_meta(get_the_ID(), '_excerpt', true);
                        $tagline                        = get_post_meta(get_the_ID(), '_tagline', true);
                        $hide_contact_info              = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                        $post_view                      = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
                        $disable_contact_info           = get_directorist_option('disable_contact_info', 0);
                        $category                       = get_post_meta(get_the_Id(), '_admin_category_select', true);
                        $display_title                  = get_directorist_option('display_title',1);
                        $display_review                 = get_directorist_option('enable_review',1);
                        $display_price                  = get_directorist_option('display_price',1);
                        $display_category               = get_directorist_option('display_category',1);
                        $display_view_count             = get_directorist_option('display_view_count',1);
                        $display_author_image           = get_directorist_option('display_author_image',1);
                        $display_publish_date           = get_directorist_option('display_publish_date',1);
                        $display_contact_info           = get_directorist_option('display_contact_info',1);
                        $display_feature_badge_cart     = get_directorist_option('display_feature_badge_cart',1);
                        $display_popular_badge_cart     = get_directorist_option('display_popular_badge_cart',1);
                        $popular_badge_text             = get_directorist_option('popular_badge_text','Popular');
                        $feature_badge_text             = get_directorist_option('feature_badge_text','Featured');
                        $pagination                     = get_directorist_option('paginate_search_results', 1);
                        $enable_tagline                 = get_directorist_option('enable_tagline');
                        $enable_excerpt                 = get_directorist_option('enable_excerpt');
                        $address_location               = get_directorist_option('address_location','location');
                        /*Code for Business Hour Extensions*/
                        $bdbh                           = get_post_meta(get_the_ID(), '_bdbh', true);
                        $enable247hour                  = get_post_meta(get_the_ID(), '_enable247hour', true);
                        $disable_bz_hour_listing        = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
                        $business_hours                 = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                        $author_id                      = get_the_author_meta( 'ID' );
                        $u_pro_pic                      = get_user_meta($author_id, 'pro_pic', true);
                        $u_pro_pic                      = wp_get_attachment_image_src($u_pro_pic, 'thumbnail');
                        $avata_img                      = get_avatar($author_id, 32);
                        $thumbnail_cropping             = get_directorist_option('thumbnail_cropping',1);
                        $crop_width                     = get_directorist_option('crop_width', 360);
                        $crop_height                    = get_directorist_option('crop_height', 300);
                        $display_tagline_field          = get_directorist_option('display_tagline_field', 0);
                        $display_pricing_field          = get_directorist_option('display_pricing_field', 1);
                        $display_excerpt_field          = get_directorist_option('display_excerpt_field', 0);
                        $display_short_desc_for         = get_directorist_option('display_short_desc_for', 'none');
                        $display_address_field          = get_directorist_option('display_address_field', 1);
                        $display_address_for            = get_directorist_option('display_address_for', 'admin_users');
                        $display_phone_field            = get_directorist_option('display_phone_field', 1);
                        $display_phone_for              = get_directorist_option('display_phone_for', 'admin_users');
                        if(!empty($listing_prv_img)) {

                            if($thumbnail_cropping) {

                                $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];
                            }else{
                                $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                            }

                        }
                        if(!empty($listing_img[0])) {
                            if( $thumbnail_cropping ) {
                                $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];

                            }else{
                                $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                            }

                        }
                        /*Code for Business Hour Extensions*/
                        ?>
                        <div class="atbdp_column">
                            <div class="atbd_single_listing atbd_listing_card <?php echo get_directorist_option('info_display_in_single_line',0)?'atbd_single_line_card_info':'';?>">
                                <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure class="atbd_listing_thumbnail_area" style=" <?php echo empty(get_directorist_option('display_preview_image'))?'display:none':''?>">
                                        <div class="atbd_listing_image">
                                            <?php
                                            $disable_single_listing = get_directorist_option('disable_single_listing');
                                            if (empty($disable_single_listing)){
                                            ?>
                                            <a href="<?php echo esc_url(get_post_permalink(get_the_ID()));?>">
                                                <?php
                                                }
                                                 $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                                 if(!empty($listing_prv_img)){

                                                            echo '<img src="'.esc_url($prv_image).'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                                        } if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                                                            echo '<img src="' . esc_url($gallery_img) . '" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                                        }if (empty($listing_img[0]) && empty($listing_prv_img)){

                                                            echo '<img src="'.$default_image.'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                                        }
                                                if (empty($disable_single_listing)){
                                                    echo '</a>';
                                                } if(!empty($display_author_image)) {
                                                $author = get_userdata($author_id);
                                                ?>
                                                <div class="atbd_author">
                                                    <a href="<?= ATBDP_Permalink::get_user_profile_page_link($author_id); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $author->first_name.' '.$author->last_name;?>"><?php if (empty($u_pro_pic)) {echo $avata_img;} if (!empty($u_pro_pic)) { ?>
                                                            <img
                                                            src="<?php echo esc_url($u_pro_pic[0]); ?>"
                                                            alt="Author Image"><?php } ?>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                            <?php
                                            $plan_hours = true;
                                            $u_badge_html = '<span class="atbd_upper_badge">';
                                            if (is_fee_manager_active()) {
                                                $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
                                            }
                                            if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
                                                //lets check is it 24/7.
                                                if ('2.2.6'>BDBH_VERSION){
                                                    ?>
                                                    <style>
                                                        .atbd_badge_close, .atbd_badge_open{
                                                            position: absolute;
                                                            left: 15px;
                                                            top: 15px;
                                                        }
                                                    </style>
                                                    <?php
                                                }
                                                $open = get_directorist_option('open_badge_text', __('Open Now', ATBDP_TEXTDOMAIN));
                                                if (!empty($enable247hour)) {
                                                    $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">'.$open.'</span>';

                                                } else {
                                                    $bh_statement = BD_Business_Hour()->show_business_open_close($business_hours);

                                                    $u_badge_html .= $bh_statement;
                                                }
                                            }
                                            $u_badge_html .= '</span>';

                                            /**
                                             * @since 5.0
                                             */
                                            echo apply_filters('atbdp_upper_badges', $u_badge_html);


                                            //Start lower badge
                                            $l_badge_html = '<span class="atbd_lower_badge">';

                                            if ($featured && !empty($display_feature_badge_cart)) {
                                                $l_badge_html .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
                                            }

                                            $popular_listing_id = atbdp_popular_listings(get_the_ID());
                                            $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                                            if ($popular_listing_id === get_the_ID()) {
                                                $l_badge_html .= $badge;
                                            }
                                            //print the new badge
                                            $l_badge_html .= new_badge();
                                            $l_badge_html .= '</span>';

                                            /**
                                             * @since 5.0
                                             */
                                            echo apply_filters('atbdp_lower_badges', $l_badge_html);
                                            ?>
                                    </figure>

                                    <div class="atbd_listing_info">
                                        <div class="atbd_content_upper">
                                            <?php if(!empty($display_title)) {?>
                                            <h4 class="atbd_listing_title">
                                                <?php
                                                if (empty($disable_single_listing)){
                                                    ?>
                                                    <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                                    <?php
                                                }else{
                                                    echo esc_html(stripslashes(get_the_title()));
                                                }?>
                                            </h4>
                                            <?php } if(!empty($tagline) && !empty($enable_tagline) && !empty($display_tagline_field)) { ?>
                                            <p class="atbd_listing_tagline"><?= esc_html(stripslashes($tagline)); ?></p>
                                            <?php } /**
                                             * Fires after the title and sub title of the listing is rendered
                                             *
                                             *
                                             * @since 1.0.0
                                             */
                                            do_action('atbdp_after_listing_tagline');
                                            ?>

                                            <?php
                                            $meta_html = '';
                                            if (!empty($display_review) || !empty($display_price)) { ?>

                                                <?php
                                                $meta_html .= '<div class="atbd_listing_meta">';
                                                $average = ATBDP()->review->get_average(get_the_ID());
                                                $meta_html .= '<span class="atbd_meta atbd_listing_rating">'.$average.'<i class="fa fa-star"></i>
        </span>';
                                                $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
                                                if (!empty($display_price) && !empty($display_pricing_field)) {
                                                    if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                                                        $output = atbdp_display_price_range($price_range);
                                                        $meta_html .= $output;
                                                    } else {
                                                        $meta_html .=  atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                                                    }
                                                }
                                                /**
                                                 * Fires after the price of the listing is rendered
                                                 *
                                                 *
                                                 * @since 3.1.0
                                                 */
                                                do_action('atbdp_after_listing_price');
                                                $meta_html .= '</div>';
                                            }
                                            /**
                                             * @since 5.0
                                             * universal action to fire after the price
                                             */
                                            echo apply_filters('atbdp_listings_review_price',$meta_html);
                                            ?>
                                            <?php if(!empty($display_contact_info) || !empty($display_publish_date)) { ?>
                                                <div class="atbd_listing_data_list">
                                                    <ul>

                                                        <?php
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_before_location');

                                                        if (!empty($display_contact_info)) {
                                                            if( !empty( $address ) && 'contact' == $address_location && !empty($display_address_field) ) { ?>
                                                                <li><p><span class="fa fa-map-marker"></span><?php echo esc_html(stripslashes($address));?></p></li>
                                                            <?php } elseif(!empty($locs) && 'location' == $address_location) {

                                                                $numberOfCat = count($locs);
                                                                $output = array();
                                                                foreach ($locs as $loc) {
                                                                    $link = ATBDP_Permalink::atbdp_get_location_page($loc);
                                                                    $space = str_repeat(' ', 1);
                                                                    $output []= "{$space}<a href='{$link}'>{$loc->name}</a>";
                                                                }?>
                                                                <li>
                                                                    <p>

                                                    <span>
                                                    <?php echo "<span class='fa fa-map-marker'></span>" . join(',',$output);?>
                                                </span>
                                                                    </p>
                                                                </li>
                                                            <?php }
                                                            /**
                                                             * @since 4.7.6
                                                             */
                                                            do_action('atbdp_listings_before_phone');
                                                            ?>
                                                            <?php if( !empty( $phone_number ) && !empty($display_phone_field)) {?>
                                                                <li><p><span class="fa fa-phone"></span><?php echo esc_html(stripslashes($phone_number));?></p></li>
                                                                <?php
                                                            } }
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_before_post_date');

                                                        if(!empty($display_publish_date)) { ?>
                                                            <li><p><span class="fa fa-clock-o"></span><?php
                                                                    printf( __( 'Posted %s ago', ATBDP_TEXTDOMAIN ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
                                                                    ?></p></li>
                                                        <?php }
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_after_post_date');

                                                        ?>
                                                    </ul>
                                                </div><!-- End atbd listing meta -->
                                                <?php
                                            } if(!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field))  {?>
                                            <p><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 30))); ?></p>
                                            <?php } ?>
                                            <?php /* @todo: deleted the read more link */ ?>
                                        </div><!-- end ./atbd_content_upper -->
                                        <?php if(!empty($display_view_count) || !empty($display_category)) {?>
                                        <div class="atbd_listing_bottom_content">
                                            <?php
                                            if(!empty($display_category)) {
                                                if(!empty($cats) ) {
                                                    $totalTerm = count($cats);
                                                    ?>
                                                    <div class="atbd_content_left">
                                                        <div class="atbd_listting_category">
                                                            <a href="<?php echo esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0]));?>"><?php if ('none' != get_cat_icon($cats[0]->term_id)){ ?>
                                                                    <span class="fa fa-folder-open"></span> <?php }?><?php  echo $cats[0]->name;?></a>
                                                                    <?php
                                                           if ($totalTerm>1){
                                                            ?>
                                                            <span class="atbd_cat_popup">  +<?php echo $totalTerm-1; ?>
                                                                <span class="atbd_cat_popup_wrapper">
                                                                    <?php
                                                                    $output = array();
                                                                    foreach (array_slice($cats,1) as $cat) {
                                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                        $space = str_repeat(' ', 1);
                                                                        $output []= "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                                         }?>
                                                                    <span><?php echo join($output);?></span>
                                                                </span>
                                                            </span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php }else{
                                                    ?>
                                                    <div class="atbd_content_left">
                                                        <div class="atbd_listting_category">
                                                            <a href=""><span class="fa fa-folder-open"></span><?php  echo __('Uncategorized', ATBDP_TEXTDOMAIN);?>
                                                            </a>
                                                        </div>
                                                    </div>

                                                <?php    } } ?>
                                            <ul class="atbd_content_right">
                                                <?php if(!empty($display_view_count)) {?>
                                                <li class="atbd_count"><span class="fa fa-eye"></span><?php echo !empty($post_view) ? $post_view : 0 ;?></li>
                                                <?php } ?>
                                                <!--<li class="atbd_save"><span class="fa fa-heart"></span></li>-->
                                            </ul>
                                        </div><!-- end ./atbd_listing_bottom_content -->
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                        </div>

                    <?php }
                    } else {?>
                            <p><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if(!empty($pagination)){
                        the_posts_pagination(
                            array('mid_size'  => 2,
                                'prev_text' => '<span class="fa fa-chevron-left"></span>',
                                'next_text' => '<span class="fa fa-chevron-right"></span>',
                            ));
                    }
                    //atbdp_pagination($listings, $paged);
                    wp_reset_postdata();
                    $wp_query   = NULL;
                    $wp_query   = $temp_query;
                    ?>
                </div>
            </div>
        </div>
    </div>
<style>
    .atbd_content_active #directorist.atbd_wrapper .atbdp_column {
        width: <?php echo $column_width;?>;
    }
</style>
<script>
    (function($){
        var $this = $("#directorist.atbd_wrapper");
        var size = $this.width();
        if(size <= 600){
            $this.addClass("size-xs");
        }
    })(jQuery);
</script>
<?php include __DIR__.'/style.php'; ?>
