<div class="wrap">
    <h1 class="wp-heading-inline">Add/Edit Listing Types</h1>
    <a href="<?php echo admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=add_new'); ?>" class="page-title-action">Add new directory</a>
    <hr class="wp-header-end">
    <?php atbdp_show_flush_alerts( ['page' => 'edit-listing-type'] ); ?>
    <br>

    <div id="atbdp-cpt-manager">
        <cpt-manager />
    </div>
</div>