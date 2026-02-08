<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.8.0
 */

$placeholder = $data['placeholder'] ?? '';
$data_max    = $data['max'] ?? '';
$data_new    = $data['allow_new'] ?? '';
$multiple    = $data['type'] === 'multiple' ? 'multiple' : '';

$lazy_load      = $data['lazy_load'];
$all_tags       = ( ! $lazy_load ) ? get_terms( ATBDP_TAGS, [ 'hide_empty' => 0 ] ) : [];


$current_terms = $listing_form->add_listing_terms( ATBDP_TAGS );

$current_ids = array_map(
    function( $term ) {
        return $term->term_id;
    }, $current_terms 
);

$current_labels = array_map(
    function( $term ) {
        return $term->name;
    }, $current_terms 
);

$current_ids_as_string    = implode( ',', $current_ids );
$current_labels_as_string = implode( ',', $current_labels );

// Get conditional logic data attributes using helper method
$conditional_logic_attr = $listing_form->get_conditional_logic_attributes( $data );

// Additional fallback check - try multiple locations for conditional logic data
if ( empty( $conditional_logic_attr ) ) {
    $conditional_logic = null;
    
    // Check conditional_logic_data first
    if ( ! empty( $data['conditional_logic_data'] ) ) {
        $conditional_logic = is_string( $data['conditional_logic_data'] ) 
            ? json_decode( $data['conditional_logic_data'], true ) 
            : $data['conditional_logic_data'];
    }
    // Check options.conditional_logic
    elseif ( ! empty( $data['options']['conditional_logic'] ) ) {
        $conditional_logic = $data['options']['conditional_logic'];
    }
    // Check direct conditional_logic key
    elseif ( ! empty( $data['conditional_logic'] ) ) {
        $conditional_logic = $data['conditional_logic'];
    }
    
    // If we found conditional logic and it's enabled, create attributes
    if ( ! empty( $conditional_logic ) && is_array( $conditional_logic ) && ! empty( $conditional_logic['enabled'] ) ) {
        $conditional_logic_attr = ' data-conditional-logic="' . esc_attr( wp_json_encode( $conditional_logic ) ) . '"';
        $conditional_logic_attr .= ' data-field-key="' . esc_attr( $data['field_key'] ?? '' ) . '"';
    }
}

?>

<div class="directorist-form-group directorist-form-tag-field"<?php echo $conditional_logic_attr; ?>>

    <?php $listing_form->field_label_template( $data, 'at_biz_dir-tags' ); ?>

    <select name="<?php echo esc_attr( $data['field_key'] ); ?>" class="directorist-form-element" id="at_biz_dir-tags" data-selected-id="<?php echo esc_attr( $current_ids_as_string ) ?>" data-selected-label="<?php echo esc_attr( $current_labels_as_string ) ?>"  data-placeholder="<?php echo esc_attr( $placeholder ); ?>" data-max="<?php echo esc_attr( $data_max ); ?>" data-allow_new="<?php echo esc_attr( $data_new ); ?>" <?php echo esc_attr( $multiple ); ?> <?php $listing_form->required( $data ); ?>>

        <?php
        if ( $data['type'] !== 'multiple' ) {
            echo '<option value="">' . esc_attr( $placeholder ) . '</option>';
        }

        if ( ! $lazy_load ) {
            foreach ( $all_tags as $tag ) {
                $current = in_array( $tag->term_id, $current_ids ) ? true : false;
                ?>
                <option <?php selected( $current, true, true ); ?> value='<?php echo esc_attr( $tag->name ); ?>'><?php echo esc_html( $tag->name ) ?></option>
                <?php
            }
        }
        ?>

    </select>

    <?php $listing_form->field_description_template( $data ); ?>

</div>