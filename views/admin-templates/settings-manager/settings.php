<div class="wrap">
    <?php
        /**
         * Fires before settings pane
         * @since 7.2.0
         */
        do_action( 'directorist_before_settings_panel_header' );
    ?>
    <div id="atbdp-settings-manager" class="atbdp-settings-manager" data-builder-data="<?php echo esc_attr( $data['settings_builder_data'] ); ?>">
        <span class="directorist_settings-panel-shade"></span>
        <settings-manager />
    </div>
</div>
