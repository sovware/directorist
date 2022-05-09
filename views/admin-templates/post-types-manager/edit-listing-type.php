<div class="wrap">
    <?php 
        $enable_multi_directory = get_directorist_option( 'enable_multi_directory', false );
        $enable_multi_directory = atbdp_is_truthy( $enable_multi_directory );
        
        /**
         * Fires before single directory edited header
         * @since 7.2.0
         */
        do_action( 'directorist_before_directory_type_edited' );

        if ( $enable_multi_directory ) : ?>
            <h1 class="wp-heading-inline"><?php _e( 'Add/Edit Listing Types', 'directorist' ) ?></h1>
            <hr class="wp-header-end">
        <?php endif;?> 
    <br>

    <div id="atbdp-cpt-manager">
        <cpt-manager />
    </div>
</div>