<!-- Themes and Extensions -->
<div class="extension-theme-wrapper">
    <?php if ( ! empty( $args['all_active_extensions'] ) ) : ?>
    <div class="et-column">
        <h2><?php _e( 'Extensions', 'directorist' ) ?></h2>

        <?php foreach( $args['all_active_extensions'] as $extension_key => $extension ) : ?>
        <div class="et-card">
            <div class="et-card__image">
                <img src="<?php echo $extension['thumbnail'] ?>" alt="">
            </div><!-- ends: .et-card__image -->
            <div class="et-card__details">
                <h3><?php echo $extension['name'] ?></h3>
                <p><?php echo $extension['description'] ?></p>
                <ul>
                    <li><a href="<?php echo $extension['link'] ?>" class="et-card__btn et-card__btn--primary"><?php _e( 'View Details', 'directorist' ) ?></a></li>
                    <li><a href="<?php echo $extension['link'] ?>" class="et-card__btn et-card__btn--secondary"><?php _e( 'Get It Now', 'directorist' ) ?></a></li>
                </ul>
            </div>
        </div><!-- ends: .et-card -->
        <?php endforeach; ?>
    </div><!-- ends: .et-column -->
    <?php endif; ?>
    
    <?php if ( ! empty( $args['all_active_themes'] ) ) : ?>
    <div class="et-column">
        <h2><?php _e( 'Themes', 'directorist' ) ?></h2>

        <?php foreach( $args['all_active_themes'] as $theme_key => $theme ) : ?>
        <div class="et-card">
            <div class="et-card__image">
                <img src="<?php echo $theme['thumbnail'] ?>" alt="">
            </div><!-- ends: .et-card__image -->
            <div class="et-card__details">
                <h3><?php echo $theme['name'] ?></h3>
                <p><?php echo $theme['description'] ?></p>
                <ul>
                    <li><a href="<?php echo $theme['link'] ?>" class="et-card__btn et-card__btn--primary"><?php _e( 'View Details', 'directorist' ) ?></a></li>
                    <li><a href="<?php echo $theme['link'] ?>" class="et-card__btn et-card__btn--secondary"><?php _e( 'Get It Now', 'directorist' ) ?></a></li>
                </ul>
            </div>
        </div><!-- ends: .et-card -->
        <?php endforeach; ?>
    </div><!-- ends: .et-column -->
    <?php endif; ?>
</div><!-- ends: .theme-extension-wrapper -->