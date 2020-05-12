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

			<div class="<?php echo apply_filters('atbdp_single_listing_title_class', 'atbd_listing_title');?>">
				<?php
	            /**
	             * @since 5.0.5
	             */
				echo apply_filters('atbdp_listing_title', sprintf('<h2>%s</h2>', $p_title));
				
				do_action('atbdp_single_listing_after_title', $listing_id);
				?>
			</div>

			<?php
            if (!empty($tagline) && !empty($display_tagline_field)) {
	            /**
	             * @since 5.0.5
	             */
                echo apply_filters('atbdp_listing_tagline', sprintf('<p class="atbd_single_listing_tagline">%s</p>', $tagline));
            }

            /**
             * Fires after the title and sub title of the listing is rendered on the single listing page
             *
             * @since 1.0.0
             */
            do_action('atbdp_after_listing_tagline');

            echo apply_filters('atbdp_listing_content', sprintf('<div class="about_detail">%s</div>', $content));
            ?>
        </div>
    </div>
</div> <!-- end .atbd_listing_details -->
<?php do_action('atbdp_after_single_listing_details_section'); ?>