<div class="wrap">
    <?php 
        $enable_multi_directory = get_directorist_option( 'enable_multi_directory', false );
        $enable_multi_directory = atbdp_is_truthy( $enable_multi_directory );
        
        if ( $enable_multi_directory ) : ?>
            <h1 class="wp-heading-inline"><?php _e( 'Add/Edit Listing Types', 'directorist' ) ?></h1>
            <hr class="wp-header-end">
        <?php endif;?> 
    <br>

    <div id="atbdp-cpt-manager">
        <cpt-manager />
    </div>
</div>