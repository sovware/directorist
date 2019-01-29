<?php
// This Walker is needed to match more than one selected value
class Willy_Walker_CategoryDropdown extends Walker_CategoryDropdown {
    public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        $pad = str_repeat('&nbsp;', $depth * 3);

        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters( 'list_cats', $category->name, $category );

        if ( isset( $args['value_field'] ) && isset( $category->{$args['value_field']} ) ) {
            $value_field = $args['value_field'];
        } else {
            $value_field = 'term_id';
        }

        $output .= "\t<option class=\"level-$depth\" value=\"" . esc_attr( $category->{$value_field} ) . "\"";

        // Type-juggling causes false matches, so we force everything to a string.
        if ( in_array( $category->{$value_field}, (array)$args['selected'], true ) )
            $output .= ' selected="selected"';
        $output .= '>';
        $output .= $pad.$cat_name;
        if ( $args['show_count'] )
            $output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
        $output .= "</option>\n";
    }
}
