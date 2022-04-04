<div class="wrap">
    <?php
        /**
         * Fires before settings pane
         * @since 7.2.0
         */
        do_action( 'directorist_before_settings_panel_header' );
    ?>
    <div id="atbdp-settings-manager" class="atbdp-settings-manager">
        <div class="atbdp-settings-manager__top">
            <h4 class="atbdp-settings-manager__title">
                <span><?php _e( 'Settings', 'directorist' ) ?></span>
                <span class="directorist_settings-trigger">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </h4>
            
            <ul class="directorist_builder-links">
                <li>
                    <a href="https://directorist.com/documentation/" target="_blank">
                        <i class="la la-file"></i>
                        <span class="link-text"><?php _e( 'Documentation', 'directorist' ); ?></span>
                    </a>
                </li>
                <li>
                    <a href="https://directorist.com/dashboard/#support" target="_blank" class="directorist_alert-warning">
                        <i class="la la-question-circle"></i>
                        <span class="link-text"><?php _e( 'Support', 'directorist' ); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        
        <span class="directorist_settings-panel-shade"></span>
        <settings-manager />
    </div>
</div>