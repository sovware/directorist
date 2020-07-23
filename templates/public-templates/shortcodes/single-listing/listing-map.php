<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

if ( $show_map ) { ?>
	<div class="atbd_content_module">
		<div class="atbd_content_module_title_area">
			<div class="atbd_area_title">
				<h4>
					<span class="<?php atbdp_icon_type(true);?>-map atbd_area_icon"></span><?php echo esc_html( $listing_location_text ); ?>
				</h4>
			</div>
		</div>
		<div class="atbdb_content_module_contents">
			<?php
            /**
             * @since 5.10.0
             *
             */
            do_action('atbdp_single_listing_before_map');
            ?>
            <div id="gmap" class="atbd_google_map"></div>
        </div>
    </div>
    <?php
}