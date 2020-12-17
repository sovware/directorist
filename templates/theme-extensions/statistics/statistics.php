<div class="row">
    <div class="col-md-8">
        <div class="atbdp-section-content atbdp-state-section-content">
            <ul class="atbdp-counter-list">
                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number atbdp-text-success">
                        <?php echo $args[ 'active_extensions' ] ?>
                    </span>
                    <span class="atbdp-counter-list__label">Active Extensions</span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number">
                        <?php echo count( $args[ 'installed_extensions' ] ); ?>
                    </span>
                    <span class="atbdp-counter-list__label">Available Extensions</span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number atbdp-text-success">
                        <?php echo $args[ 'active_themes' ] ?>
                    </span>
                    <span class="atbdp-counter-list__label">Active Theme</span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number">
                        <?php echo count( $args[ 'installed_themes' ] ); ?>
                    </span>
                    <span class="atbdp-counter-list__label">Available Theme</span>
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
                        <?php echo $args[ 'outdated_extensions' ]; ?>
                    </span>
                    <span class="atbdp-counter-list__label">Extensions updates Available</span>
                    <span class="atbdp-counter-list__actions">
                        <?php if ( ! empty( $args[ 'outdated_extensions' ] ) ) : ?>
                            <button class="button button-primary ext-update-btn">Update All</button>
                        <?php else: ?>
                            <span class="atbdp-text-muted">All up to date</span>
                        <?php endif ?>
                    </span>
                </li>

                <!-- atbdp-counter-list__item -->
                <li class="atbdp-counter-list__item">
                    <span class="atbdp-counter-list__number">
                        <?php echo $args[ 'outdated_themes' ]; ?>
                    </span>
                    <span class="atbdp-counter-list__label">Theme updates Available</span>
                    <span class="atbdp-counter-list__actions">
                        <?php if ( ! empty( $args[ 'outdated_themes' ] ) ) : ?>
                            <button class="button button-primary">Update All</button>
                        <?php else: ?>
                            <span class="atbdp-text-muted">All up to date</span>
                        <?php endif ?>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>