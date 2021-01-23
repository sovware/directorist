<?php
echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
echo '</div>';
?>
<div class="atbdp atbdp-widget-categories">
    <?php if( 'dropdown' == $query_args['template'] ) : ?>
        <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
            <input type="hidden" name="q" placeholder="">
            <select name="in_cat" id="at_biz_dir-category" onchange="this.form.submit()">
                <?php echo $categories; ?>
            </select>
        </form>
    <?php else :
        echo $categories;
    endif; ?>
</div>

<?php


echo $args['after_widget'];