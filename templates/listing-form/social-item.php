<?php
$id = (array_key_exists('id', $args)) ? $args['id'] : 0;
?>

<div class="directorist-form-social-fields" id="socialID-<?php echo $id; ?>">
    <div class="directorist-form-group">
        <select name="social[<?php echo $id; ?>][id]" class="directorist-form-element">
            <?php foreach ( ATBDP()->helper->social_links() as $nameID => $socialName ) { ?>
                <option value='<?php echo esc_attr($nameID); ?>'> <?php echo esc_html($socialName); ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="directorist-form-group">
        <input type="url" name="social[<?php echo $id; ?>][url]" class="directorist-form-element directory_field atbdp_social_input" value="" placeholder="<?php esc_attr_e('eg. http://example.com', 'directorist'); ?>" required>
    </div>
    <div class="directorist-form-group directorist-form-social-fields__action">
        <span data-id="<?php echo $id; ?>" class="directorist-form-social-fields__remove dashicons dashicons-trash" title="<?php _e('Remove this item', 'directorist'); ?>"></span>
    </div>
</div>
