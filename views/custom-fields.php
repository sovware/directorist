<?php
/**
 * This template displays custom fields in the search form.
 */

// Start the Loop
$allow_decimal = get_directorist_option('allow_decimal', 1);
 if ( ! empty( $custom_fields ) ) : 
    // Prime caches to reduce future queries.
    if ( is_callable( '_prime_post_caches' ) ) {
        _prime_post_caches( $custom_fields );
    }

    $original_post = ! empty( $GLOBALS['post'] ) ? $GLOBALS['post'] : array();

    foreach ( $custom_fields as $field_id ) : 
        $GLOBALS['post'] = get_post( $field_id );
        setup_postdata( $GLOBALS['post'] );

        $field_meta = get_post_meta( $field_id );
        ?>
        
        <div class="form-group atbdp_cf_<?php echo $field_meta['type'][0];?>"><div>
        <?php
        $value = '';
        if ( isset( $_GET['custom_field'][ $field_id ] ) ) {
            $value = $_GET['custom_field'][ $field_id ];
        }
        if ( isset( $_POST['custom_field'][ $field_id ] ) ) {
            $value = $_POST['custom_field'][ $field_id ];
        }

        switch( $field_meta['type'][0] ) {
            case 'text' :
                printf( '<input type="text" name="custom_field[%d]" placeholder="%s" class="form-control" value="%s"/>', $field_id, get_the_title(), esc_attr( $value ) );
                echo '</div></div>';
                break;
            case 'number' :
                printf( '<input type="number" %s name="custom_field[%d]" placeholder="%s" class="form-control" value="%s"/>', !empty($allow_decimal)?'step="any"':'', $field_id, get_the_title(), esc_attr( $value ) );
                echo '</div></div>';
                break;
            case 'textarea' :
                printf( '<textarea name="custom_field[%d]" placeholder="%s" class="form-control" rows="%d">%s</textarea>', $field_id,get_the_title(), (int) $field_meta['rows'][0], esc_textarea( $value ) );
                echo '</div></div>';
                break;
            case 'url' :
                printf( '<input type="text" name="custom_field[%d]" placeholder="%s" class="form-control" value="%s"/>', $field_id,get_the_title(), esc_url( $value ) );
                echo '</div></div>';
                break;
            case 'select' :
                $choices = $field_meta['choices'][0];
                $choices = explode( "\n", trim( $choices ) );
                $label = apply_filters('atbdp_search_custom_field_select_label','<label>'. get_the_title() .'</label>');

                printf( '%s<div class="select-basic"><select name="custom_field[%d]" class="form-control">',$label, $field_id );

                    printf( '<option value="">%s</option>', '- '.__( 'Select an Option', 'directorist' ).' -' );

                foreach( $choices as $choice ) {
                    if( strpos( $choice, ':' ) !== false ) {
                        $_choice = explode( ':', $choice );
                        $_choice = array_map( 'trim', $_choice );

                        $_value  = $_choice[0];
                        $_label  = $_choice[1];
                    } else {
                        $_value  = trim( $choice );
                        $_label  = $_value;
                    }

                    $_selected = '';
                    if ( trim( $value ) == $_value ) $_selected = ' selected="selected"';

                    printf( '<option value="%s"%s>%s</option>', $_value, $_selected, $_label );
                }
                echo '</select></div>';
                echo '</div></div>';
                break;
            case 'checkbox' :
                $choices = $field_meta['choices'][0];
                $choices = explode( "\n", trim( $choices ) );

                $values = array_map( 'trim', (array) $value );

                echo '<label>'.get_the_title().'</label><div class="bads-custom-checks">';
                foreach( $choices as $choice ) {
                    if( strpos( $choice, ':' ) !== false ) {
                        $_choice = explode( ':', $choice );
                        $_choice = array_map( 'trim', $_choice );

                        $_value  = $_choice[0];
                        $_label  = $_choice[1];
                    } else {
                        $_value  = trim( $choice );
                        $_label  = $_value;
                    }
                    $_for = rand();
                    $_checked = '';

                    if( in_array( $_value, $values ) ) $_checked = ' checked="checked"';

                    printf( '<div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary"><input type="checkbox" name="custom_field[%d][]" id="%d" class="custom-control-input" value="%s"%s><span class="check--select"></span><label for="%d" class="custom-control-label">%s</label></div>', $field_id,$_for, $_value, $_checked,$_for, $_label );
                }
                echo '</div>';
                echo '<a href="#" class="more-or-less sml">'.__('Show More', 'directorist').'</a>';
                echo '</div></div>';
                break;
            case 'radio' :
                $choices = $field_meta['choices'][0];
                $choices = explode( "\n", trim( $choices ) );
                echo "<label>".get_the_title()."</label><div class='atbdp_custom_radios'>";
                foreach( $choices as $choice ) {
                    if( strpos( $choice, ':' ) !== false ) {
                        $_choice = explode( ':', $choice );
                        $_choice = array_map( 'trim', $_choice );

                        $_value  = $_choice[0];
                        $_label  = $_choice[1];
                    } else {
                        $_value  = trim( $choice );
                        $_label  = $_value;
                    }
                    $_for = rand();
                    $_checked = '';
                    if( trim( $value ) == $_value ) $_checked = ' checked="checked"';

                    printf( '<div class="custom-control custom-checkbox checkbox-outline checkbox-outline-primary"><input type="radio" class="custom-control-input" name="custom_field[%d]" id="%d" value="%s"%s><span class="radio--select"></span><label class="custom-control-label" for="%d">%s</label></div>', $field_id,$_for, $_value, $_checked,$_for, $_label );
                }
                echo "</div>";
                echo '</div></div>';
                break;
            case 'date' :
                printf( '<label>%s</label><input type="date" name="custom_field[%d]" class="form-control" value="%s"/>', get_the_title(),$field_id,  $value  );
                echo '</div></div>';
                break;
            case 'color' :
                printf( '<label>%s</label><input type="color" name="custom_field[%d]" class="search-color-field" value="%s"/>', get_the_title(), $field_id,  $value  );
                ?>
                <script>
                    jQuery(document).ready(function ($) {
                        $('.search-color-field').wpColorPicker().empty();
                        $('.wp-color-picker').empty();
                    });
                </script>
                <?php
        echo '</div></div>';
            break;
        case 'time' :
            printf( '<label>%s</label><input type="time" name="custom_field[%d]" class="form-control" value="%s"/>', get_the_title(), $field_id,  $value  );
            echo '</div></div>';
            break;
        }
        
    endforeach; 

    $GLOBALS['post'] = $original_post;
    wp_reset_postdata();

endif;
?>