<div id="directorist" class="wrap atbd_wrapper">
    <!-- my-themes-extensions -->
    <div id="my-themes-extensions" class="atbdp-tab-content active">

        <?php
            if ( ! $args[ 'is_logged_in' ] ) {
                //ATBDP()->load_template('theme-extensions/auth/license-auth-section', $args );
            } else {
                ATBDP()->load_template('theme-extensions/statistics/statistics', $args);
                ATBDP()->load_template('theme-extensions/my-themes-extensions/my-themes-extensions', $args);
            }

            ATBDP()->load_template('theme-extensions/all-themes-extensions', $args);
         ?>
    </div>
</div>