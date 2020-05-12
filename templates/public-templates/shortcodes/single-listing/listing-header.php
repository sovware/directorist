<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

$listing = new Directorist_Single_Listing();

/**
 * @since 5.0
 */
do_action('atbdp_before_listing_section');
?>
<div class="atbd_content_module atbd_listing_details atbdp_listing_ShortCode <?php do_action('atbdp_single_listing_details_class')?>">
    <div class="atbd_content_module_title_area">

        <?php if (!empty($listing_details_text)): ?>
            <div class="atbd_area_title">
                <h4><span class="<?php atbdp_icon_type(true); ?>-file-text atbd_area_icon"></span><?php _e($listing_details_text, 'directorist') ?></h4>
            </div>
        <?php endif; ?>

		<?php $listing->the_header_actions(); ?>
    </div>
    <div class="atbdb_content_module_contents">

        <?php $listing->the_slider(); ?>

        <div class="atbd_listing_detail">

        	<?php $listing->the_header_meta(); ?>

			<?php
            $class = apply_filters('atbdp_single_listing_title_class', 'atbd_listing_title');
            echo '<div class="'.$class.'">';
            $title_html = '<h2>';
            $title_html .= esc_html($p_title);
            $title_html .= '</h2>';
            /**
             * @since 5.0.5
             */
            echo apply_filters('atbdp_listing_title', $title_html);
            /**
             * It fires after the listing title
             */
            do_action('atbdp_single_listing_after_title', $listing_id);
            echo '</div>';
            $tagline_html = '';
            if (!empty($tagline) && !empty($display_tagline_field)) {
                $tagline_html .= '<p class="atbd_single_listing_tagline">' . $tagline . '</p>';
            }
            /**
             * @since 5.0.5
             */
            echo apply_filters('atbdp_listing_tagline', $tagline_html);
            /**
             * Fires after the title and sub title of the listing is rendered on the single listing page
             *
             * @since 1.0.0
             */
            do_action('atbdp_after_listing_tagline');
            $listing_content = '<div class="about_detail">';
            /*
             * Automatic embedding done by WP by hooking to the_content filter
             * As we are outputting the data on the content filter before them, therefore it is our duty to parse the embed using the WP_Embed object manually.
             * Here run_shortcode() will parse [embed]url[embed]
             * and autoembed() will parse any embeddable url like https://youtube.com/?v=vidoecode etc.
             * then do_shortcode() will parse the rest of the shortcodes
             * */
            $post_object = get_post(get_the_ID());
            $content = apply_filters('get_the_content', $post_object->post_content);
            $listing_content = '';
            if (!empty($content)) {
                $listing_content = '<div class="about_detail">';
                $listing_content .= do_shortcode(wpautop($content));
                $listing_content .= '</div>';
            }
            echo apply_filters('atbdp_listing_content', $listing_content);
            ?>
        </div>
    </div>
</div> <!-- end .atbd_listing_details -->
<?php do_action('atbdp_after_single_listing_details_section'); ?>