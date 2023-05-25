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

<div>mobile area</div>

basic search
<div class='basic-search'>
    <?php foreach ($searchform->form_data[0]['fields'] as $field) : ?>

        <div class="directorist-advanced-filter__basic--element">
            <?php $searchform->field_template($field); ?>
        </div>

    <?php endforeach; ?>
</div>

Advance search
<div class='advance-search'>
    <?php foreach ($searchform->form_data[1]['fields'] as $field) : ?>

        <div class="directorist-form-group directorist-advanced-filter__advanced--element directorist-search-field-<?php echo esc_attr($field['widget_name']) ?>">
        <?php $searchform->field_template($field); ?>

    </div>
    <?php endforeach; ?>

</div>