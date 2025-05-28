<div class="directorist-search-popup-block">
    <div class="directorist-search-popup-block__button">
        <?php
        if ( 'icon' === $attributes['styleDisplay'] ) {
            directorist_icon( 'fa fa-search' );
        } elseif ( 'text' === $attributes['styleDisplay'] ) {
            directorist_icon( 'fa fa-search' );
            echo $content;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        } elseif ( 'icon_and_text' === $attributes['styleDisplay'] ) {
            echo $content;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        }
        ?>
    </div>

    <?php include_once DIRECTORIST_BLOCK_TEMPLATE_PATH . '/popup.php'; ?>
</div>
