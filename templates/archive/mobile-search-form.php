<?php
/**
 * @author  wpWax
 * @since   7.7.0
 * @version 7.7.0
 */
?>

<?php
    if ( is_numeric( $searchform->listing_type ) ) {
        $term         = get_term_by( 'id', $searchform->listing_type, ATBDP_TYPE );
        $listing_type = $term->slug;
    }
?>

<div class="directorist-all-listing-btn">
    <div class="directorist-all-listing-btn__back">
		<a href="#" class="directorist-btn__back">
			<?php directorist_icon( 'las la-arrow-left' ); ?>
		</a>
	</div>

    <div class="directorist-all-listing-btn__basic">
		<a href="#" class="directorist-btn directorist-btn-light directorist-modal-btn directorist-modal-btn--basic">
			<?php directorist_icon( 'las la-search' ); ?> Search
		</a>
	</div>

    <div class="directorist-all-listing-btn__advanced">
        <a href="#" class="directorist-modal-btn directorist-modal-btn--advanced">
            <?php directorist_icon( 'fas fa-sliders-h' ); ?>
        </a>
    </div>
</div>
<div class="directorist-all-listing-modal">
    <form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" class="directorist-search-form" data-atts="<?php echo esc_attr( $searchform->get_atts_data() ); ?>">
        <div class="directorist-search-modal directorist-search-modal--advanced">
            <div class="directorist-search-modal__overlay"></div>
            <div class="directorist-search-adv-filter directorist-advanced-filter directorist-search-modal__contents">
                <div class="directorist-advanced-filter__wrapper">
                    <div class="directorist-search-modal__contents__header">
                        <h3 class="directorist-search-modal__contents__title">More Filters</h3>
                        <button class="directorist-search-modal__contents__btn directorist-search-modal__contents__btn--close">
                            <?php directorist_icon( 'fas fa-times' ); ?>
                        </button>
                        <span class="directorist-search-modal__minimizer"></span>
                    </div>
                    <div class="directorist-search-modal__contents__body">
                        <?php foreach ( $searchform->form_data[1]['fields'] as $field ): ?>

                            <?php if (  ! in_array( $field['field_key'], $searchform->assign_to_category()['custom_field_key'] ) ) { ?>

                                <div class="directorist-form-group directorist-advanced-filter__advanced--element directorist-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>

                            <?php } ?>

                            <?php endforeach; ?>
                    </div>
                    <div class="directorist-search-modal__contents__footer">
                        <?php $searchform->buttons_template(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="directorist-search-modal directorist-search-modal--basic">
            <div class="directorist-search-modal__overlay"></div>
            <div class="directorist-search-adv-filter directorist-advanced-filter directorist-search-modal__contents">
                <div class="directorist-advanced-filter__wrapper">
                    <span class="directorist-search-modal__minimizer"></span>
                    <div class="directorist-search-modal__contents__body">
                        <?php foreach ( $searchform->form_data[0]['fields'] as $field ){ ?>
                            <div class="directorist-search-modal__input">
                                <div class="directorist-search-modal__input__btn directorist-search-modal__input__btn--back">
                                    <?php directorist_icon( 'fas long-arrow-alt-left' ); ?>
                                </div>
                                <?php $searchform->field_template( $field ); ?>
                                <div class="directorist-search-modal__input__btn directorist-search-modal__input__btn--clear">
                                    <?php directorist_icon( 'fas fa-times-circle' ); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <button type="submit" class="directorist-btn directorist-btn-white directorist-search-form-action__modal__btn-search">

                            <?php directorist_icon( 'las la-search' ); ?> Search

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>