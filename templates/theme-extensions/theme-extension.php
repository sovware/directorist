<div id="directorist" class="wrap atbd_wrapper">
    <!-- atbdp-tab-nav-area
    ----------------------------->
    <div class="atbdp-tab-nav-area">
        <ul class="atbdp-tab-nav-menu">
            <!-- atbdp-tab-nav-menu__item -->
            <li class="atbdp-tab-nav-menu__item active">
                <a href="#" class="atbdp-tab-nav-menu__link" data-target="#my-themes-extensions">
                    <?php _e( 'My Themes and Extensions' ) ?>
                </a>
            </li>

            <!-- atbdp-tab-nav-menu__item -->
            <li class="atbdp-tab-nav-menu__item">
                <a href="#" class="atbdp-tab-nav-menu__link" data-target="#all-themes-extensions">
                    <?php _e( 'All Themes and Extensions' ) ?>
                </a>
            </li>
        </ul>
    </div>

    <!-- atbdp-tab-content-area
    ----------------------------->
    <div class="atbdp-tab-content-area">
        <!-- my-themes-extensions -->
        <div id="my-themes-extensions" class="atbdp-tab-content active">
            <?php ATBDP()->load_template('theme-extensions/my-themes-extensions'); ?>
        </div>
        
        <!-- all-themes-extensions -->
        <div id="all-themes-extensions" class="atbdp-tab-content">
            <?php ATBDP()->load_template('theme-extensions/all-themes-extensions'); ?>
        </div>
    </div>
</div>