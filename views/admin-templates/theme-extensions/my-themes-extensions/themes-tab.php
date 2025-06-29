<div id="atbdp-themes-tab" class="ext-wrapper et-contents__tab-item atbdp-tab__content">
    <h4><?php esc_html_e( 'Active Theme', 'directorist' )?></h4>
    <div class="theme-card-wrapper">
        <div class="theme-card">
            <figure>
                <?php 
                if ( ! empty( $args['current_active_theme_info']['thumbnail'] ) ): ?>
                <img src="<?php echo esc_url( $args['current_active_theme_info']['thumbnail'] ); ?>" alt="">
                <?php endif;?>

                <figcaption>
                    <div class="theme-title">
                        <h5><?php echo esc_html( $args['current_active_theme_info']['name'] ); ?></h5>
                        <span class="theme-version">v<?php echo esc_html( $args['current_active_theme_info']['version'] ); ?></span>
                    </div>
                    <div class="theme-action">
                        <a href="<?php echo esc_url( $args['current_active_theme_info']['customizer_link'] ); ?>" target="_blank" class="theme-action-btn btn-customize"><?php esc_html_e( 'Customize', 'directorist' )?></a>
                    </div>
                </figcaption>
            </figure>

            <?php if ( $args['current_active_theme_info']['has_update'] ): ?>
            <div class="theme-card__footer">
                <p class="theme-update theme-update--available"><?php esc_html_e( 'Update available', 'directorist' )?> <!-- <a hre="#" class="whats-new" data-target="whats-new-modal">What's new?</a> --></p>
                <a href="#" class="theme-update-btn" data-target="<?php echo esc_attr( $args['current_active_theme_info']['stylesheet'] ); ?>"><?php echo $args['is_beta'] ? 'Update Beta' : 'Update'; ?></a>
            </div>
            <?php endif;?>

            <div class="at-modal atm-fade" id="whats-new-modal">
                <div class="at-modal-content at-modal-lg">
                    <div class="atm-contents-inner">
                        <a href="" class="at-modal-close"><span aria-hidden="true">×</span></a>
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="whats-new-modal-label">Version: <span>6.5.3</span></h3>
                                </div>
                                <div class="modal-body">
                                    <div class="update-list update-list--new">
                                        <span class="update-badge update-badge--new">New</span>
                                        <ul class="update-list-items">
                                            <li>Add – Option to set custom redirection after login</li>
                                            <li>Add – Option to set custom redirection after login</li>
                                            <li>Add – Option to set custom redirection after login</li>
                                            <li>Add – Option to set custom redirection after login</li>
                                            <li>Add – Option to set custom redirection after login</li>
                                        </ul>
                                    </div><!-- ends: .update-list -->
                                    <div class="update-list update-list--fixed">
                                        <span class="update-badge update-badge--fixd">Fixed</span>
                                        <ul class="update-list-items">
                                            <li>Fixed – Option to set custom redirection after login</li>
                                            <li>Fixed – Option to set custom redirection after login</li>
                                            <li>Fixed – Option to set custom redirection after login</li>
                                        </ul>
                                    </div><!-- ends: .update-list -->
                                    <div class="update-list update-list--improved">
                                        <span class="update-badge update-badge--improved">Improved</span>
                                        <ul class="update-list-items">
                                            <li>Improved – Option to set custom redirection after login</li>
                                            <li>Improved – Option to set custom redirection after login</li>
                                            <li>Improved – Option to set custom redirection after login</li>
                                        </ul>
                                    </div><!-- ends: .update-list -->
                                    <div class="update-list update-list--removed">
                                        <span class="update-badge update-badge--removed">Removed</span>
                                        <ul class="update-list-items">
                                            <li>Removed – Option to set custom redirection after login</li>
                                            <li>Removed – Option to set custom redirection after login</li>
                                            <li>Removed – Option to set custom redirection after login</li>
                                        </ul>
                                    </div><!-- ends: .update-list -->
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary"><?php echo $args['is_beta'] ? 'Update Beta' : 'Update'; ?></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ( ! empty( $args['themes_available_in_subscriptions'] ) ): ?>
    <div class="available-themes-wrapper">
        <h4><?php esc_html_e( 'Available in your subscription', 'directorist' )?></h4>
        <div class="available-themes">
            <?php foreach ( $args['themes_available_in_subscriptions'] as $_theme_base => $_theme_args ): ?>
            <div class="available-theme-card">
                <figure>
                    <?php if ( ! empty( $_theme_args['thumbnail'] ) ): ?>
                    <img src="<?php echo esc_url( $_theme_args['thumbnail'] ); ?>" alt="">
                    <?php endif;?>

                    <figcaption>
                        <h5><?php echo esc_html( $_theme_args['name'] ); ?></h5>
                        <div class="theme-action">
                            <?php if ( $_theme_args['is_installed'] ): ?>
<?php if ( ! empty( $_theme_args['stylesheet'] ) ): ?>
                                    <a href="#" data-target="<?php echo esc_attr( $_theme_args['stylesheet'] ); ?>" class="theme-action-btn theme-activate-btn"><?php esc_html_e( 'Activate', 'directorist' )?></a>
                                <?php endif;?>

                                <?php if ( ! empty( $_theme_args['customizer_link'] ) ): ?>
                                    <a href="<?php echo esc_url( $_theme_args['customizer_link'] ); ?>" target="_blank" class="theme-action-btn theme-preview-btn"><?php esc_html_e( 'Live Preview', 'directorist' )?></a>
                                <?php endif;?>

                            <?php else: ?>
                                <a href="#" data-type="theme" data-key="<?php echo esc_attr( $_theme_base ) ?>" class="theme-action-btn file-install-btn">
                                    <i class="la la-download"></i>                                                                   <?php echo $args['is_beta'] ? 'Install Beta' : 'Install'; ?>
                                </a>

                                <?php if ( ! empty( $_theme_args['demo_link'] ) ): ?>
                                    <a href="<?php echo esc_url( $_theme_args['demo_link'] ); ?>" target="_blank" class="theme-action-btn theme-preview-btn"><?php esc_html_e( 'Demo', 'directorist' )?></a>
                                <?php endif;?>
<?php endif;?>
                        </div>
                    </figcaption>
                </figure>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <?php endif;?>
</div>