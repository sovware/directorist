<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<?php if ($listings->display_title || $listings->enable_tagline || $listings->display_review || $listings->display_price): ?>
	<!-- listings-archive > loop > top-content -->
	<div class="atbd_content_upper">

		<?php do_action( "atbdp_{$listings->view}_view_before_title" );?>

		<?php if ($listings->display_title) { ?>
			<h4 class="atbd_listing_title"><?php echo wp_kses_post( $listings->loop_get_title() );?></h4>
			<?php
		}

        /**
         * @since 6.2.3
         */
        do_action( "atbdp_{$listings->view}_view_after_title" );

		if (!empty($listings->loop['tagline']) && $listings->enable_tagline && $listings->display_tagline_field) { ?>
			<p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($listings->loop['tagline'])); ?></p>
			<?php
		}

		/**
		 * Fires after the title and sub title of the listing is rendered
		 *
		 *
		 * @since 1.0.0
		 */

		do_action('atbdp_after_listing_tagline');

		if ($listings->display_review || $listings->display_price && (!empty($listings->loop['price']) || !empty($listings->loop['price_range']))) {
			$listings->loop_price_meta_template();
		}

	    if (!empty($listings->display_contact_info || $listings->display_publish_date || $listings->display_email || $listings->display_web_link)) {
	    	$listings->loop_data_list_template();
	    }

		if (!empty($listings->loop['excerpt']) && $listings->enable_excerpt && $listings->display_excerpt_field) { ?>
			<p class="atbd_excerpt_content">
				<?php echo esc_html(wp_trim_words($listings->loop['excerpt'], $listings->excerpt_limit));

				/**
				* @since 5.0.9
				*/
				do_action('atbdp_listings_after_exerpt');

				if ($listings->display_readmore ) { 
					printf('<a href="%s"> %s</a>', $listings->loop['permalink'], $listings->readmore_text);
				}
				?>
			</p>
			<?php
		}

	    /**
	     * @since 6.6
	     * @hooked Directorist_Listings::mark_as_favourite_button - 15
		 * @hooked CompareButton() || display_compare_btn_on_grid_listings || from extension- 10
	     */
	    do_action( "directorist_{$listings->view}_view_top_content_end", $listings );
		?>
	</div>
	
	<?php
endif;