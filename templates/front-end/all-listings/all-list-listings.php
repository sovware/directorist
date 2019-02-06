<?php
!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
?>
    <div id="directorist" class="atbd_wrapper">
        <?php
        listing_view_by_list($all_listings, $view, $current_order);
        ?>

    </div>
<?php
?>
<?php //get_footer(); ?>