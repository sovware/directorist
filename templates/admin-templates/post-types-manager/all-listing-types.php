<div class="wrap">
    <h1 class="wp-heading-inline">Listing Types</h1>
    <a href="<?php echo $data['add_new_link']; ?>" class="page-title-action">Add Listing Type</a>
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">

    <form method="GET"> 
        <?php $data['post_types_list_table']->display() ?>
    </form>
</div>