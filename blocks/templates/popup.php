<?php $search_popup_id = isset( $search_popup_id ) ? $search_popup_id : 'directorist-search-popup'; ?>

<div id="<?php echo esc_attr( $search_popup_id ); ?>" class="directorist-search-popup-block__popup" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search listings', 'directorist' ); ?>" aria-hidden="true">
    <button type="button" class="directorist-search-popup-block__form-close" aria-label="<?php esc_attr_e( 'Close search dialog', 'directorist' ); ?>">
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
