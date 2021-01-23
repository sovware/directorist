<div class="et-wrapper">
    <div class="et-wrapper-head">
        <div class="et-header-title-area">
            <h3>My Themes and Extensions</h3>

            <div class="et-header-actions">
                <div class="et-auth-section atbdp-hide">
                    <form id="purchase-refresh-form" action="#" method="post">
                        <div class="et-auth-section-wrap">
                            <div class="atbdp-input-group atbdp-input-group-append">
                                <div class="atbdp-input-group-wrap">
                                    <input type="password" class="atbdp-form-control" placeholder="Confirm Password" id="password" name="password">
                                    <button type="submit" class="atbdp-btn atbdp-btn-primary">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>

                                <div class="atbdp-form-feedback"></div>
                            </div>
                            

                            <div class="atbdp-action-group">
                                <button class="atbdp-btn atbdp-btn-danger atbdp-mx-5 et-close-auth-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="atbdp-action-group">
                    <div class="atbdp-btn-wrapper purchase-refresh-btn-wrapper">
                        <a href="#" class="ext-action-btn purchase-refresh-btn atbdp-show">
                            <i class="la la-refresh"></i> <?php _e('Refresh Purchase', 'directorist') ?>
                        </a>
                    </div>

                    <div class="atbdp-btn-wrapper">
                        <a href="#" class="ext-action-btn subscriptions-logout-btn" data-hard-logout="<?php echo $args['hard_logout']; ?>">
                            <i class="fas fa-sign-out-alt"></i> <?php _e('Logout', 'directorist') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- <div class="et-search">
            <input type="text" placeholder="Search extensions and themes">
            <span class="la la-search"></span>
        </div> -->
    </div><!-- ends: .et-wrapper-head -->
    <div class="et-contents">
        <ul class="et-contents__tab-nav">
            <li class="et-contents__tab-nav-item atbdp-tab__nav-item active">
                <a href="#" class="atbdp-tab__nav-link" data-parent="#atbdp-themes-extensions-contents" data-target="#atbdp-extensions-tab">
                    Extensions
                </a>
            </li>

            <li class="et-contents__tab-nav-item atbdp-tab__nav-item">
                <a href="#" class="atbdp-tab__nav-link" data-parent="#atbdp-themes-extensions-contents" data-target="#atbdp-themes-tab">
                    Themes
                </a>
            </li>
        </ul>

        <div id="atbdp-themes-extensions-contents" class="et-contents__tab-contents atbdp-tab__contents">
            <?php ATBDP()->load_template('admin-templates/theme-extensions/my-themes-extensions/extensions-tab', $args); ?>
            <?php ATBDP()->load_template('admin-templates/theme-extensions/my-themes-extensions/themes-tab', $args); ?>
        </div>
    </div><!-- ends: .et-contents -->
</div><!-- ends: .et-wrapper -->