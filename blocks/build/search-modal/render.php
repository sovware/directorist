<div class="directorist-search-popup-block">
    <button type="button" class="directorist-search-popup-block__button" aria-haspopup="dialog" aria-expanded="false">
        <?php
        if ( 'icon' === $attributes['styleDisplay'] ) {
            directorist_icon( 'fa fa-search' );
        } elseif ( 'text' === $attributes['styleDisplay'] ) {
            directorist_icon( 'fa fa-search' );
            echo wp_kses_post( $content );

        } elseif ( 'icon_and_text' === $attributes['styleDisplay'] ) {
            echo wp_kses_post( $content );

        }
        ?>
    </button>

    <?php include_once DIRECTORIST_BLOCK_TEMPLATE_PATH . '/popup.php'; ?>
</div>
