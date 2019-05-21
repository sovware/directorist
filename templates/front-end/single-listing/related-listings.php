<?php
global $post;
$listing_id    = $post->ID;
$fm_plan       = get_post_meta($listing_id, '_fm_plans', true);
$listing_info['never_expire'] = get_post_meta($post->ID, '_never_expire', true);
$listing_info['featured'] = get_post_meta($post->ID, '_featured', true);
$listing_info['price'] = get_post_meta($post->ID, '_price', true);
$listing_info['price_range'] = get_post_meta($post->ID, '_price_range', true);
$listing_info['atbd_listing_pricing'] = get_post_meta($post->ID, '_atbd_listing_pricing', true);
$listing_info['videourl'] = get_post_meta($post->ID, '_videourl', true);
$listing_info['listing_status'] = get_post_meta($post->ID, '_listing_status', true);
$listing_info['tagline'] = get_post_meta($post->ID, '_tagline', true);
$listing_info['excerpt'] = get_post_meta($post->ID, '_excerpt', true);
$listing_info['address'] = get_post_meta($post->ID, '_address', true);
$listing_info['phone'] = get_post_meta($post->ID, '_phone', true);
$listing_info['email'] = get_post_meta($post->ID, '_email', true);
$listing_info['website'] = get_post_meta($post->ID, '_website', true);
$listing_info['zip'] = get_post_meta($post->ID, '_zip', true);
$listing_info['social'] = get_post_meta($post->ID, '_social', true);
$listing_info['faqs'] = get_post_meta($post->ID, '_faqs', true);
$listing_info['manual_lat'] = get_post_meta($post->ID, '_manual_lat', true);
$listing_info['manual_lng'] = get_post_meta($post->ID, '_manual_lng', true);
$listing_info['hide_map'] = get_post_meta($post->ID, '_hide_map', true);
$listing_info['listing_img'] = get_post_meta($post->ID, '_listing_img', true);
$listing_info['listing_prv_img'] = get_post_meta($post->ID, '_listing_prv_img', true);
$listing_info['hide_contact_info'] = get_post_meta($post->ID, '_hide_contact_info', true);
$listing_info['hide_contact_owner'] = get_post_meta($post->ID, '_hide_contact_owner', true);
$listing_info['expiry_date'] = get_post_meta($post->ID, '_expiry_date', true);
extract($listing_info);
$main_col_size = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
?>

<section id="directorist" class="directorist atbd_wrapper">
    <div class="row">
        <div class="<?php echo esc_attr($main_col_size); ?> col-md-12 atbd_col_left">
            <?php
            do_action('atbdp_after_single_listing', $post, $listing_info);
            ?>
        </div>
    </div>
</section>