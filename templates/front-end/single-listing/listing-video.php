<?php
global $post;
$listing_id               = $post->ID;
$fm_plan                  = get_post_meta($listing_id, '_fm_plans', true);
$listing_info['videourl'] = get_post_meta($post->ID, '_videourl', true);
extract($listing_info);
$enable_video_url         = get_directorist_option('atbd_video_url', 1);
$video_label              = get_directorist_option('atbd_video_title', __('Video', ATBDP_TEXTDOMAIN));
$main_col_size            = is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12';
$display_video_for        = get_directorist_option('display_video_for', 'admin_users');
?>

            <?php
            if ($enable_video_url && !empty($videourl) && 'none' != $display_video_for) { ?>
                <div class="atbd_content_module atbd_custom_fields_contents">
                    <div class="atbd_content_module__tittle_area">
                        <div class="atbd_area_title">
                            <h4>
                                <span class="fa fa-video-camera atbd_area_icon"></span><?php _e($video_label, ATBDP_TEXTDOMAIN) ?>
                            </h4>
                        </div>
                    </div>

                    <div class="atbdb_content_module_contents">
                        <iframe class="atbd_embeded_video embed-responsive-item"
                                src="<?php echo esc_attr(ATBDP()->atbdp_parse_videos($videourl)) ?>"
                                allowfullscreen></iframe>
                    </div>
                </div><!-- end .atbd_custom_fields_contents -->
            <?php } ?>
            <?php do_action('atbdp_after_video_gallery'); ?>

