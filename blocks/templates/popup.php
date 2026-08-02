<div class="directorist-search-popup-block__popup">
    <button type="button" class="directorist-search-popup-block__form-close" aria-label="<?php esc_attr_e( 'Close search', 'directorist' ); ?>">
        <?php directorist_icon( 'la times' );?>
    </button>
    <div class="dspb-container">
        <div class="dspb-row">
            <div class="directorist-search-popup-block__form">
                <?php echo do_shortcode( '[directorist_search_listing more_filters_button="no" show_title_subtitle="no" show_popular_category="no"]' ); ?>
            </div>
        </div>
    </div>
</div>
