<?php
$id = (array_key_exists('id', $args)) ? $args['id'] : 0;
?>

<div class="atbdp_social_field_wrapper" id="socialID-<?php echo $id; ?>">
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <select name="social[<?php echo $id; ?>][id]" class="form-control">
                <?php foreach ( ATBDP()->helper->social_links() as $nameID => $socialName ) { ?>
                    <option value='<?php echo esc_attr($nameID); ?>'> <?php echo esc_html($socialName); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <input type="url" name="social[<?php echo $id; ?>][url]" class="form-control directory_field atbdp_social_input" value="" placeholder="<?php esc_attr_e('eg. http://example.com', 'directorist'); ?>" required>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <span data-id="<?php echo $id; ?>" class="removeSocialField dashicons dashicons-trash" title="<?php _e('Remove this item', 'directorist'); ?>"></span>
    </div>
</div>
