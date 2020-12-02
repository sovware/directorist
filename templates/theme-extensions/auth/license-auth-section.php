<div class="atbdp-section atbdp-themes-extension-license-activation-section">
    <div class="atbdp-section-header">
        <h3 class="atbdp-section-title">
            <?php _e('My Licenses') ?>
        </h3>
    </div>

    <div id="atbdp-themes-extension-license-activation-content" class="atbdp-section-content atbdp-themes-extension-license-activation-content">
        <!-- atbdp-card-list -->
        <div class="atbdp-card-list">
            <!-- atbdp-card-list__item -->
            <div class="atbdp-card-list__item atbdp-accordion-toggle" data-parent="#atbdp-license-accordion-contents" data-target="#atbdp-directorist-license-content">
                <!-- atbdp-card -->
                <div class="atbdp-card">
                    <div class="atbdp-card-header">
                        <span class="atbdp-card-icon">
                            <span class="fab fa-dyalog"></span>
                        </span>
                    </div>

                    <div class="atbdp-card-body">
                        <h3 class="atbdp-card-title">Directorist License</h3>
                        <p class="atbdp-text-success">Active</p>
                    </div>
                </div>
            </div>

            <!-- atbdp-card-list__item -->
            <div class="atbdp-card-list__item atbdp-accordion-toggle" data-parent="#atbdp-license-accordion-contents" data-target="#atbdp-themeforest-license-content">
                <!-- atbdp-card -->
                <div class="atbdp-card">
                    <div class="atbdp-card-header">
                        <span class="atbdp-card-icon">
                            <span class="fas fa-laptop-code"></span>
                        </span>
                    </div>

                    <div class="atbdp-card-body">
                        <h3 class="atbdp-card-title">Themeforest License</h3>
                        <p class="atbdp-text-danger">Inactive</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- atbdp-license-accordion-contents -->
        <div id="atbdp-license-accordion-contents" class="atbdp-accordion-contents atbdp-license-accordion">
            <!-- atbdp-directorist-license-content -->
            <div id="atbdp-directorist-license-content" class="atbdp-accordion-content">
                <?php ATBDP()->load_template('theme-extensions/auth/directorist-license-login'); ?>
            </div>

            <!-- atbdp-themeforest-license-content -->
            <div id="atbdp-themeforest-license-content" class="atbdp-accordion-content">
                <?php ATBDP()->load_template('theme-extensions/auth/themeforest-license-login'); ?>
            </div>
        </div>
    </div>
</div>