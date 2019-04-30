<?php
if( $query_args['immediate_category'] ) {

    $term_slug = get_query_var( ATBDP_LOCATION );

    if( '' != $term_slug ) {
        $term = get_term_by( 'slug', $term_slug, ATBDP_LOCATION );
        $query_args['active_term_id'] = $term->term_id;

        $query_args['ancestors'] = get_ancestors( $query_args['active_term_id'], ATBDP_LOCATION );
        $query_args['ancestors'][] = $query_args['active_term_id'];
        $query_args['ancestors'] = array_unique( $query_args['ancestors'] );
    }

}

if( 'dropdown' == $query_args['template'] ) {
    $categories = $this->dropdown_locations( $query_args );
} else {
    $categories = $this->list_locations( $query_args );
}
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
echo '</div>';
?>

    <div class="atbdp atbdp-widget-categories">
        <?php if( 'dropdown' == $query_args['template'] ) : ?>
            <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
                <input type="hidden" name="q" placeholder="">
                <select id="at_biz_dir-location" name="in_loc" onchange="this.form.submit()">
                    <?php echo $categories; ?>
                </select>
            </form>
        <?php else :
            echo $categories;
        endif; ?>
    </div>

<?php


echo $args['after_widget'];
