<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div id="atbdp__social">
    <label><?php echo wp_kses_post( $social_label_html );?></label>
    <div id="social_info_sortable_container">
        <input type="hidden" id="is_social_checked">
        <?php
        if ( !empty($socials) ) {
            foreach ($socials as $index => $social_info) {
                ?>

                <div class="row  atbdp_social_field_wrapper" id="socialID-<?php echo $index; ?>">

                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <select name="social[<?php echo esc_attr( $index ); ?>][id]" id="atbdp_social" class="form-control">
                                <?php foreach (ATBDP()->helper->social_links() as $nameID => $socialName) { ?>
                                    <option value="<?php echo esc_attr($nameID); ?>" <?php selected($nameID, $social_info['id']); ?>><?php echo esc_html($socialName); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <input type="url" name="social[<?php echo esc_attr( $index ); ?>][url]" class="form-control directory_field atbdp_social_input" value="<?php echo esc_url($social_info['url']); ?>" placeholder="<?php esc_attr_e('eg. http://example.com', 'directorist'); ?>">
                    </div>

                    <div class="col-md-3 col-sm-12">
                        <span data-id="<?php echo esc_attr( $index ); ?>" class="removeSocialField dashicons dashicons-trash" title="<?php esc_attr_e('Remove this item', 'directorist'); ?>"></span>
                        <span class="adl-move-icon dashicons dashicons-move"></span>
                    </div>

                </div>
                <?php
            }
        }
        ?>
    </div>

    <button type="button" class="btn btn-primary btn-sm" id="addNewSocial"> <span class="plus-sign">+</span><?php esc_html_e('Add New', 'directorist'); ?></button>
</div>