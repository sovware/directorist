<div id="atbdp-themes-tab" class="ext-wrapper et-contents__tab-item atbdp-tab__content">
    <h4><?php _e( 'Active Theme', 'directorist' ) ?></h4>
    <div class="theme-card-wrapper">
        <div class="theme-card">
            <figure>
                <img src="<?php echo $args['active_theme']['thumbnail']; ?>" alt="">
                <figcaption>
                    <div class="theme-title">
                        <h5><?php echo $args['active_theme']['name']; ?></h5>
                        <span class="theme-version">v<?php echo $args['active_theme']['version']; ?></span>
                    </div>
                    <div class="theme-action">
                        <a href="<?php echo $args['active_theme']['customizer_link']; ?>" target="_blank" class="theme-action-btn btn-customize">Customize</a>
                    </div>
                </figcaption>
            </figure>

            <?php if ( $args['active_theme']['has_update'] ) : ?>
            <div class="theme-card__footer">
                <p class="theme-update theme-update--available">Update available <a hre="#" class="whats-new" data-target="whats-new-modal">What's new?</a></p>
                <a href="#" class="theme-update-btn" data-target="<?php echo $args['active_theme']['stylesheet'] ?>">Update</a>
            </div>
            <?php endif; ?>

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
                                    <button type="submit" class="btn btn-primary">Update Now</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ( ! empty( $args['all_purshased_themes'] ) ) : ?>
    <div class="available-themes-wrapper">
        <h4><?php _e( 'Available in your subscription', 'directorist' ) ?></h4>
        <div class="available-themes">
            <?php foreach( $args['all_purshased_themes'] as $purshased_theme_base => $purshased_theme ) : ?>
            <div class="available-theme-card">
                <figure>
                    <img src="<?php echo $purshased_theme['thumbnail'] ?>" alt="">
                    <figcaption>
                        <h5><?php echo $purshased_theme['name'] ?></h5>
                        <div class="theme-action">
                            <a href="#" data-target="<?php echo $purshased_theme['stylesheet'] ?>" class="theme-action-btn theme-activate-btn">Activate</a>
                            <a href="<?php echo $purshased_theme['customizer_link'] ?>" target="_blank" class="theme-action-btn theme-preview-btn">Live Preview</a>
                        </div>
                    </figcaption>
                </figure>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>