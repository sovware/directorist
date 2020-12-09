<div id="directorist" class="wrap atbd_wrapper">
    <!-- my-themes-extensions -->
    <div id="my-themes-extensions" class="atbdp-tab-content active">

        <?php 
            if ( ! $args[ 'has_purchased_products' ] ) {
                ATBDP()->load_template('theme-extensions/auth/license-auth-section', $args );
            } else {
                ATBDP()->load_template('theme-extensions/statistics/statistics', $args);
                ATBDP()->load_template('theme-extensions/my-themes-extensions/my-themes-extensions', $args);
            }

            ATBDP()->load_template('theme-extensions/all-themes-extensions', $args);
         ?>
    </div>
</div>

<!-- <div class="">
    <h3>Activating your products</h3>
    
    <div class="atbdp-checklist-section atbdp-extensions-list">
        <h4>Extensions</h4>
        <ul class="atbdp-check-lists">
            <li class="atbdp-check-list-item">
                <span class="atbdp-check-list-icon atbdp-danger">
                    <span class="fas fa-times"></span>
                </span>
                Name
            </li>
        </ul>
    </div>
</div> -->