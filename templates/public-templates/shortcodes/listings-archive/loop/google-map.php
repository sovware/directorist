<div class="marker" data-latitude="<?php echo $ls_data['manual_lat']; ?>" data-longitude="<?php echo $ls_data['manual_lng']; ?>" data-icon="<?php echo ! empty( $cats ) ? $cat_icon : 'fa fa-map-marker'; ?>">
    <?php if ( ! $map_is_disabled ) { ?>
    <div class="map-info-wrapper">
        <input type="hidden" id="icon" value="fa fa-flag">
        <?php if ( ! empty( $display_image_map ) ) { ?>
        <div class="map-info-img">
            <?php if ( ! $disable_single_listing ) {
                echo "<a href='" . get_the_permalink() . "'>"; }

            if ( ! empty( $ls_data['listing_prv_img'] ) ) {
                echo '<img src="' . esc_url( $ls_data['prv_image'] ) . '" alt="' . esc_html( stripslashes( get_the_title() ) ) . '">'; }
            
            if ( ! empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) {
                echo '<img src="' . esc_url( $ls_data['gallery_img'] ) . '" alt="' . esc_html( stripslashes( get_the_title() ) ) . '">'; }
            
            if ( empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) {
                echo '<img src="' . $default_image . '" alt="' . esc_html( stripslashes( get_the_title() ) ) . '">'; }
            
            if ( ! $disable_single_listing ) {
                echo '</a>'; } ?>
        </div>
        <?php } ?>


        <?php if ( ! empty( $display_title_map ) || ! empty( $display_address_map ) || ! empty( $display_direction_map ) ) { ?>
        <div class="map-info-details">
            <?php if ( ! empty( $display_title_map ) ) { ?>
            <div class="atbdp-listings-title-block">
                <h3 class="atbdp-no-margin">
                    <?php if ( ! $disable_single_listing ): ?>
                        <a href="<?php the_permalink();?>"><?php the_title();?></a>
                    <?php else:
                        the_title();
                    endif;?>
                </h3>
            </div>
            <?php } ?>

            <?php if ( ! empty( $address ) ) { ?>
                <?php if ( ! empty( ! empty( $display_address_map ) ) ) { ?>
                <div class="map_addr"><span class="<?php atbdp_icon_type( true );?>-map-marker"></span> <a href="" class="map-info-link"><?php echo $ls_data['address']; ?></a></div>
                <?php } ?>

                <?php if ( ! empty( $display_direction_map ) ) { ?>
                <div class="map_get_dir">
                    <a href='http://www.google.com/maps?daddr=<?php echo $ls_data['manual_lat']; ?>,<?php echo $ls_data['manual_lng']; ?>' target='_blank'><?php _e( 'Get Direction', 'directorist' )?></a>
                    <span class="<?php atbdp_icon_type( true );?>-arrow-right"></span>
                </div>
            <?php }} ?>

            <?php do_action( 'atbdp_after_listing_content', $ls_data['post_id'], 'map' );?>
        </div>
        <?php } ?>
        
        <span id="iw-close-btn"><i class="la la-times"></i></span>
    </div>
    <?php }?>
</div>