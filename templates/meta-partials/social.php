<?php
/*
 * Template for showing Social Meta Info on Add listing page
 */
$social_info = (array_key_exists('social_info', $args)) ? $args['social_info'] : array(); ?>
    <label for="atbdp_social"><?php
        $social_label = get_directorist_option('social_label', __('Social Information', 'directorist'));
        esc_html_e($social_label.':', 'directorist');
        echo get_directorist_option('require_social_info')?'<span class="atbdp_make_str_red">*</span>':''; ?></label>
    <div id="social_info_sortable_container">
        <input type="hidden" id="is_social_checked">
        <?php
        if ( !empty($social_info) ) {
            foreach ($social_info as $index => $socialInfo) { // eg. here, $socialInfo = ['id'=> 'facebook', 'url'=> 'http://fb.com']
                ?>
                <div class="row  atbdp_social_field_wrapper" id="socialID-<?php echo $index; ?>">
                    <!--Social ID-->
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <select name="social[<?php echo $index; ?>][id]" id="atbdp_social" class="form-control">
                                <?php foreach (ATBDP()->helper->social_links() as $nameID => $socialName) { ?>
                                    <option value='<?php echo esc_attr($nameID); ?>' <?php selected($nameID, $socialInfo['id']); ?> >
                                        <?php echo esc_html($socialName); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!--Social URL-->
                    <div class="col-md-6 col-sm-12">
                        <input type="url" name="social[<?php echo $index; ?>][url]"
                               class="form-control directory_field atbdp_social_input"
                               value="<?php echo esc_url($socialInfo['url']); ?>" placeholder="<?php esc_attr_e('eg. http://example.com', 'directorist'); ?>">

                    </div>
                    <div class="col-md-3 col-sm-12">
                    <span data-id="<?php echo $index; ?>" class="removeSocialField dashicons dashicons-trash"
                          title="<?php _e('Remove this item', 'directorist'); ?>"></span>
                        <span class="adl-move-icon dashicons dashicons-move"></span>
                    </div>
                </div> <!--   ends .row   &  .atbdp_social_field_wrapper-->

                <?php
            }

        }?>
    </div> <!--    ends .social_info_sortable_container    -->

    <button type="button" class="btn btn-primary btn-sm" id="addNewSocial"> <span class="plus-sign">+</span>
        <?php esc_html_e('Add New', 'directorist'); ?>
    </button>


