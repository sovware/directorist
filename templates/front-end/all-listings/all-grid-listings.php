<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings               = !empty($all_listings) ? $all_listings : new WP_Query;
$is_disable_price           = get_directorist_option('disable_list_price');
$display_sortby_dropdown    = get_directorist_option('display_sort_by',1);
$display_viewas_dropdown    = get_directorist_option('display_view_as',1);
$pagenation                 = get_directorist_option('paginate_all_listings',1);
$display_header             = !empty($display_header) ? $display_header : '';
$header_title               = !empty($header_title) ? $header_title : '';
$header_sub_title           = !empty($header_sub_title) ? $header_sub_title : '';
$column_width = 100/$columns .'%';
?>


<div id="directorist" class="atbd_wrapper">
    <?php if( $display_header == 'yes'  ) { ?>
    <div class="header_bar">
        <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="atbd_generic_header">
                        <div class="atbd_generic_header_title">
                            <?php if(!empty($header_title)) {?>
                            <h3>
                                <?php echo esc_html($header_title); ?>
                            </h3>
                            <?php } ?>
                            <p>
                                <?php
                                echo esc_html($header_sub_title) . ' ';
                                if ($paginate){
                                    echo $all_listings->found_posts;
                                }else{
                                    echo count($all_listings->posts);
                                }
                                ?>
                            </p>
                        </div>
                        <?php if($display_viewas_dropdown || $display_sortby_dropdown) {?>
                            <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                <!-- Views dropdown -->
                                <?php if($display_viewas_dropdown) { ?>
                                    <div class="dropdown">
                                        <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php _e( "View as", ATBDP_TEXTDOMAIN ); ?> <span class="caret"></span>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <?php
                                            $views = atbdp_get_listings_view_options();

                                            foreach( $views as $value => $label ) {
                                                $active_class = ( $view == $value ) ? ' active' : '';
                                                printf( '<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg( 'view', $value ), $label );
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- Orderby dropdown -->
                                <?php
                                if($display_sortby_dropdown) {
                                    ?>
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
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">

        <div class="row" <?php echo (get_directorist_option('grid_view_as','masonry_grid') !== 'masonry_grid')?'':'data-uk-grid';?>>
            <?php
        if ( $all_listings->have_posts() ) {
            while ( $all_listings->have_posts() ) { $all_listings->the_post();
                $cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                $locs                           = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                $featured                       = get_post_meta(get_the_ID(), '_featured', true);
                $price                          = get_post_meta(get_the_ID(), '_price', true);
                $price_range                    = get_post_meta(get_the_ID(), '_price_range', true);
                $atbd_listing_pricing           = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                $listing_img                    = get_post_meta(get_the_ID(), '_listing_img', true);
                $listing_prv_img                = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                $excerpt                        = get_post_meta(get_the_ID(), '_excerpt', true);
                $tagline                        = get_post_meta(get_the_ID(), '_tagline', true);
                $address                        = get_post_meta(get_the_ID(), '_address', true);
                $phone_number                   = get_post_meta(get_the_Id(), '_phone', true);
                $category                       = get_post_meta(get_the_Id(), '_admin_category_select', true);
                $post_view                      = get_post_meta(get_the_Id(),'_atbdp_post_views_count',true);
                $hide_contact_info              = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                $disable_contact_info           = get_directorist_option('disable_contact_info', 0);
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
                $display_address_field          = get_directorist_option('display_address_field', 1);
                $display_phone_field            = get_directorist_option('display_phone_field', 1);
                if(!empty($listing_prv_img)) {

                    if($thumbnail_cropping) {

                        $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                    }else{
                        $prv_image   = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                    }

                }
                if(!empty($listing_img[0])) {
                    if( $thumbnail_cropping ) {
                        $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];
                        $default_img = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];;
                    }else{
                        $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                    }

                }

                /*Code for Business Hour Extensions*/
                ?>

                <div class="atbdp_column">
                    <div class="atbd_single_listing atbd_listing_card <?php echo get_directorist_option('info_display_in_single_line',0)?'atbd_single_line_card_info':'';?>">
                        <article class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                            <figure class="atbd_listing_thumbnail_area" style=" <?php echo empty(get_directorist_option('display_preview_image',1))?'display:none':''?>">
                                <div class="atbd_listing_image">
                                    <a href="<?php echo esc_url(get_post_permalink(get_the_ID()));?>">
                                        <?php
                                        $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                        if(!empty($listing_prv_img)){

                                            echo '<img src="'.esc_url($prv_image).'" alt="listing image">';

                                        } if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                                            echo '<img src="' . esc_url($gallery_img) . '" alt="listing image">';

                                        }if (empty($listing_img[0]) && empty($listing_prv_img)){

                                            echo '<img src="'.$default_image.'" alt="listing image">';

                                        }
                                        ?>
                                    </a>
                                    <?php if(!empty($display_author_image)) {
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
                                    if (is_fee_manager_active()){
                                        $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(),'_fm_plans', true));
                                    }
                                    if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)){
                                        //lets check is it 24/7
                                        if (!empty($enable247hour)) {
                                            $open =  get_directorist_option('open_badge_text',__('Open Now', ATBDP_TEXTDOMAIN));
                                            ?>
                                            <span class="atbd_upper_badge">
                                                        <span class="atbd_badge atbd_badge_open"><?php echo $open;?></span>
                                                    </span><!-- END /.atbd_upper_badge -->
                                            <?php
                                        }else {?>
                                            <span class="atbd_upper_badge">
                                                        <?php BD_Business_Hour()->show_business_open_close($business_hours); // show the business hour in an unordered list ?>
                                                    </span>
                                        <?php }
                                    }?>
                                    <span class="atbd_lower_badge">
                                                <?php

                                                if ($featured && !empty($display_feature_badge_cart)){ printf(
                                                    '<span class="atbd_badge atbd_badge_featured">%s</span>',
                                                    $feature_badge_text
                                                );}
                                                $count = !empty($count)?$count:5;
                                                $popular_listings = ATBDP()->get_popular_listings($count = 5);

                                                if ($popular_listings->have_posts() && !empty($display_popular_badge_cart)) {
                                                    foreach ($popular_listings->posts as $pop_post) {
                                                        if ($pop_post->ID == get_the_ID()){
                                                            echo '<span class="atbd_badge atbd_badge_popular">'. $popular_badge_text .'</span>';
                                                        }
                                                    }
                                                }
                                                //print the new badge
                                                echo new_badge();
                                                ?>
                                            </span>
                            </figure>
                            <div class="atbd_listing_info">
                                <?php if(!empty($display_title) || !empty($enable_tagline) || !empty($display_review) || !empty($display_price)) {?>
                                <div class="atbd_content_upper">
                                    <?php if(!empty($display_title)) { ?>
                                        <h4 class="atbd_listing_title">
                                            <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                        </h4>
                                    <?php } if(!empty($tagline) && !empty($enable_tagline) && !empty($display_tagline_field)) {
                                        ?>
                                        <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                    <?php } ?>
                                    <?php if(!empty($display_review) || !empty($display_price)) {?>
                                        <div class="atbd_listing_meta">
                                            <?php
                                            /**
                                             * Fires after the title and sub title of the listing is rendered
                                             *
                                             *
                                             * @since 1.0.0
                                             */

                                                do_action('atbdp_after_listing_tagline');

                                            if(!empty($display_price) && !empty($display_pricing_field)) {
                                                if(!empty($price_range)) {
                                                    $output = atbdp_display_price_range($price_range);
                                                    echo $output;
                                                }else{
                                                    atbdp_display_price($price, $is_disable_price);
                                                }
                                            }
                                            /**
                                             * Fires after the price of the listing is rendered
                                             *
                                             *
                                             * @since 3.1.0
                                             */
                                            do_action('atbdp_after_listing_price');
                                            ?>
                                        </div><!-- End atbd listing meta -->

                                    <?php } ?>
                                    <?php if(!empty($display_contact_info) || !empty($display_publish_date)) {?>
                                        <div class="atbd_listing_data_list">
                                            <ul>
                                                <?php
                                                if (!empty($display_contact_info)) {
                                                    if( !empty( $address ) && 'contact' == $address_location && !empty($display_address_field)) { ?>
                                                        <li><p><span class="fa fa-location-arrow"></span><?php echo esc_html(stripslashes($address));?></p></li>
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
                                                    <?php echo "<span class='fa fa-location-arrow'></span>" . join(',',$output);?>
                                                </span>
                                                            </p>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if( !empty( $phone_number ) && !empty($display_phone_field)) {?>
                                                        <li><p><span class="fa fa-phone"></span><?php echo esc_html(stripslashes($phone_number));?></p></li>
                                                        <?php
                                                    } }

                                                if(!empty($display_publish_date)) { ?>
                                                    <li><p><span class="fa fa-clock-o"></span><?php
                                                            printf( __( 'Posted %s ago', ATBDP_TEXTDOMAIN ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
                                                            ?></p></li>
                                                <?php } ?>
                                            </ul>
                                        </div><!-- End atbd listing meta -->
                                        <?php
                                    }
                                    if( !empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) { ?>
                                        <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, 20))); ?></p>
                                    <?php } ?>
                                </div><!-- end ./atbd_content_upper -->
                                <?php } ?>
                                <?php if(!empty($display_category) || !empty($display_view_count)) {?>
                                    <div class="atbd_listing_bottom_content">
                                        <?php
                                        if(!empty($display_category)) {
                                            if(!empty($cats) ) {
                                                global $post;
                                                $totalTerm = count($cats);


                                                ?>
                                                <div class="atbd_content_left">
                                                    <div class="atbd_listting_category">
                                                        <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]);?>"><?php if ('none' != get_cat_icon($cats[0]->term_id)){ ?>
                                                                <span class="fa fa-folder-open"></span><?php }?><?php  echo $cats[0]->name;?></a>
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
                                        <?php if(!empty($display_view_count)) {?>
                                            <ul class="atbd_content_right">
                                                <?php if(!empty($display_view_count)) {?>
                                                    <li class="atbd_count"><span class="fa fa-eye"></span><?php echo !empty($post_view) ? $post_view : 0 ;?></li> <?php } ?>


                                                <li class="atbd_save">
                                                    <div id="atbdp-favourites-all-listing">
                                                        <input type="hidden" id="listing_ids" value="<?php echo get_the_ID(); ?>">
                                                        <?php
                                                        // do_action('wp_ajax_atbdp-favourites-all-listing', get_the_ID()); ?>
                                                    </div>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div><!-- end ./atbd_listing_bottom_content -->
                                <?php } ?>
                            </div>
                        </article>
                    </div>
                </div>
            <?php }
            wp_reset_postdata();
        } else {?>
            <p><?php _e('No listing found.', ATBDP_TEXTDOMAIN); ?></p>
        <?php } ?>

        </div>

    </div>
    <div class="row atbd_listing_pagination">
        <?php
        if (1 == $pagenation){
            ?>
            <div class="col-md-12">
                <div class="">
                    <?php
                    $paged = !empty($paged)?$paged:'';
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<style>
    .atbd_content_active #directorist.atbd_wrapper .atbdp_column {
        width: <?php echo $column_width;?>;
    }
</style>