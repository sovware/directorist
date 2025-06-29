<div class="wrap">
    <?php
        $builder_data = base64_encode( json_encode( $data['directory_builder_data'] ) );

        /**
         * Fires before single directory edited header
         * @since 7.2.0
         */
        do_action( 'directorist_before_directory_type_edited' );

		if ( isset( $_GET['action'] ) && $_GET['action'] === 'add_new' ) {
			$label = __( 'Add New Directory', 'directorist' );
		} else {
			$label = __( 'Edit Directory', 'directorist' );
		}
		?>
    <div id="atbdp-cpt-manager" data-builder-data="<?php echo esc_attr( $builder_data ); ?>">
        <cpt-manager />
    </div>
</div>