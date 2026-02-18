<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$fields = $searchform->get_basic_fields();
?>

<div class="directorist-search-adv-filter directorist-advanced-filter">
    <?php if ( ! empty( $fields ) ) : ?>

            <?php foreach ( $fields as $key => $field ) { ?>
                <div class="directorist-search-modal__input <?php echo $key === 'title' ? 'directorist-search-query' : ''; ?> <?php echo $key === 'radius_search' ? 'directorist-radius-search' : ''; ?>">
                    <?php $searchform->field_template( $field ); ?>
                </div>
            <?php } ?>
            
        <?php endif; ?>

        <div class="directorist-search-form-action__modal">
            <button type="submit" class="directorist-btn directorist-btn-light directorist-search-form-action__modal__btn-search">

                <?php directorist_icon( 'las la-search' ); ?>

                <?php echo esc_html( $searchform->search_button_text );?>

            </button>
        </div>
    </div>
</div>