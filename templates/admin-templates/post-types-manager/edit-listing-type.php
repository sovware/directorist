<div class="wrap">
    <h1 class="wp-heading-inline">Add/Edit Listing Types</h1>
    <hr class="wp-header-end">
    <?php atbdp_show_flush_alerts( ['page' => 'edit-listing-type'] ); ?>
    <br>

    
    <div id="atbdp-cpt-manager">
        <cpt-manager
            id="<?php echo $data['id']; ?>"
            settings='<?php echo $data['settings']; ?>' 
            fields='<?php echo $data['fields']; ?>'
            form-fields='<?php echo $data['form_fields']; ?>'
        />
    </div>
</div>