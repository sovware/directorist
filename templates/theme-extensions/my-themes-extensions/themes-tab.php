<div id="atbdp-themes-tab" class="ext-wrapper et-contents__tab-item atbdp-tab__content">
    <h4><?php _e( 'Active Theme', 'directorist' ) ?></h4>
    <div class="theme-card-wrapper">
        <div class="theme-card">
            <figure>
                <img src="<?php echo $args['active_theme']['thumbnail'] ?>" alt="">
                <figcaption>
                    <div class="theme-title">
                        <h5><?php echo $args['active_theme']['name'] ?></h5>
                        <span class="theme-version">v<?php echo $args['active_theme']['version'] ?></span>
                    </div>
                    <div class="theme-action">
                        <a href="<?php echo $args['active_theme']['customizer_link'] ?>" target="_blank" class="theme-action-btn btn-customize">Customize</a>
                    </div>
                </figcaption>
            </figure>

            <?php if ( $args['active_theme']['has_update'] ) : ?>
            <div class="theme-card__footer">
                <p class="theme-update theme-update--available">Update available <span class="whats-new">What's new?</span></p>
                <a href="#" class="theme-update-btn" data-target="<?php echo $args['active_theme']['stylesheet'] ?>">Update</a>
            </div>
            <?php endif; ?>
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