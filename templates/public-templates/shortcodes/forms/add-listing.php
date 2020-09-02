<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div id="directorist" class="directorist atbd_wrapper atbd_add_listing_wrapper">
	<div class="<?php echo apply_filters('atbdp_add_listing_container_fluid', $container_fluid) ?>">
		<form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" id="add-listing-form">
			<fieldset>

				<?php do_action('atbdb_before_add_listing_from_frontend');?>

				<div class="atbdp-form-fields">
					<?php
                    /**
                     * @since 6.6
                     * @hooked Directorist_Listing_Forms > add_listing_title_template - 10
                     */
                    do_action( 'directorist_add_listing_title' );

                    /*
                     * @since 4.0.4
                     */
                    do_action('atbdp_listing_form_after_add_listing_title', $listing_info);
                    
                    ATBDP()->listing->add_listing->show_nonce_field();
                    ?>

                    <input type="hidden" name="add_listing_form" value="1">
                    <input type="hidden" name="listing_id" value="<?php echo !empty($p_id) ? esc_attr($p_id) : ''; ?>">

                    <?php
                    /**
                     * @since 1.1.1
                     **/
                    do_action('atbdp_edit_before_title_fields', 'add_listing_page_frontend');
                    ?>

                    <div class="atbdb_content_module">
                    	<?php
                        /**
                         * @since 6.6
                         * @hooked Directorist_Listing_Forms > add_listing_general_template - 10
                         * @hooked Directorist_Listing_Forms > add_listing_contact_template - 15
                         * @hooked Directorist_Listing_Forms > add_listing_map_template - 20
                         * @hooked Directorist_Listing_Forms > add_listing_image_template - 25
                         * @hooked Directorist_Listing_Forms > add_listing_submit_template - 30
                         */
                        do_action( 'directorist_add_listing_contents');
                        ?>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>