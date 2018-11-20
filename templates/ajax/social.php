<?php
$id = (array_key_exists('id', $args)) ? $args['id'] : 0;
?>


<div class="directorist row atbdp_social_field_wrapper" id="socialID-<?= $id; ?>">
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <select name="social[<?= $id; ?>][id]" class="form-control">
                <?php foreach ( ATBDP()->helper->social_links() as $nameID => $socialName ) { ?>
                    <option value='<?= esc_attr($nameID); ?>'> <?= esc_html($socialName); ?></option>;
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <input type="url" name="social[<?= $id; ?>][url]" class="form-control directory_field atbdp_social_input" value="" placeholder="eg. http://example.com" required>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <span data-id="<?= $id; ?>" class="removeSocialField dashicons dashicons-trash" title="<?php _e('Remove this item', ATBDP_TEXTDOMAIN); ?>"></span>
        <span class="adl-move-icon dashicons dashicons-move"></span>
    </div>
</div>
