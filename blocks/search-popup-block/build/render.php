<div class="directorist-search-popup-block">
    <div class="directorist-search-popup-block__button">
        <?php if('icon_only' === $attributes['styleDisplay'] ): ?>
        <?php elseif('button_only' === $attributes['styleDisplay'] ): ?>
            <?php echo $content; ?>
        <?php elseif('button_and_icon' === $attributes['styleDisplay'] ): ?>
            <?php echo $content; ?>
        <?php endif;?>
    </div>

    <?php include_once DIRECTORIST_SEARCH_POPUP_BLOCK_TEMPLATE_PATH . '/popup.php'; ?>

</div>