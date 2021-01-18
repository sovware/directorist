<div class="row">
    <div class="col-md-8">
        <div class="atbdp-section-content atbdp-state-section-content">
            <ul class="atbdp-counter-list">
                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number atbdp-text-success">
                        <?php echo $args[ 'total_active_extensions' ] ?>
                    </span>
                    <span class="atbdp-counter-list__label"><?php _e( 'Active Extensions', 'directorist'); ?></span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number">
                        <?php echo $args[ 'total_available_extensions' ]; ?>
                    </span>
                    <span class="atbdp-counter-list__label"><?php _e( 'Available Extensions', 'directorist'); ?></span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number atbdp-text-success">
                        <?php echo $args[ 'total_active_themes' ] ?>
                    </span>
                    <span class="atbdp-counter-list__label"><?php _e( 'Active Theme', 'directorist'); ?></span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number">
                        <?php echo $args[ 'total_available_themes' ]; ?>
                    </span>
                    <span class="atbdp-counter-list__label"><?php _e( 'Available Theme', 'directorist'); ?></span>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-4">
        <div class="atbdp-section-content atbdp-state-section-content atbdp-state-vertical">
            <ul class="atbdp-counter-list atbdp-counter-list-vertical">
                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number atbdp-text-success">
                        <?php echo $args[ 'total_outdated_extensions' ]; ?>
                    </span>
                    
                    <span class="atbdp-counter-list__label"><?php _e( 'Extensions updates Available', 'directorist'); ?></span>
                    
                    <span class="atbdp-counter-list__actions">
                        <?php if ( ! empty( $args[ 'total_outdated_extensions' ] ) ) : ?>
                            <button class="button button-primary ext-update-btn"><?php _e( 'Update All', 'directorist'); ?></button>
                        <?php else: ?>
                            <span class="atbdp-text-muted"><?php _e( 'All up to date', 'directorist'); ?></span>
                        <?php endif ?>
                    </span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number">
                        <?php echo $args[ 'total_outdated_themes' ]; ?>
                    </span>
                    <span class="atbdp-counter-list__label"><?php _e( 'Theme updates Available', 'directorist'); ?></span>
                    <span class="atbdp-counter-list__actions">
                        <?php if ( ! empty( $args[ 'total_outdated_themes' ] ) ) : ?>
                            <button class="button button-primary"><?php _e( 'Update All', 'directorist'); ?></button>
                        <?php else: ?>
                            <span class="atbdp-text-muted"><?php _e( 'All up to date', 'directorist'); ?></span>
                        <?php endif ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>