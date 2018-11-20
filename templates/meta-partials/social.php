<?php
/*
 * Template for showing Social Meta Info on Add listing page
 */
$social_info = (array_key_exists('social_info', $args)) ? $args['social_info'] : array(); ?>
    <label for="atbdp_social"><?php esc_html_e('Social Information:', ATBDP_TEXTDOMAIN); ?></label>
    <div id="social_info_sortable_container">
        <?php
        if ( !empty($social_info) ) {
            foreach ($social_info as $index => $socialInfo) { // eg. here, $socialInfo = ['id'=> 'facebook', 'url'=> 'http://fb.com']
                ?>
                <div class="row  atbdp_social_field_wrapper" id="socialID-<?= $index; ?>">
                    <!--Social ID-->
                    <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                            <select name="social[<?= $index; ?>][id]" id="atbdp_social" class="form-control">
                                <?php foreach (ATBDP()->helper->social_links() as $nameID => $socialName) { ?>
                                    <option value='<?= esc_attr($nameID); ?>' <?php selected($nameID, $socialInfo['id']); ?> >
                                        <?= esc_html($socialName); ?>
                                    </option>;
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!--Social URL-->
                    <div class="col-md-6 col-sm-12">
                        <input type="url" name="social[<?= $index; ?>][url]"
                               class="form-control directory_field atbdp_social_input"
                               value="<?= esc_url($socialInfo['url']); ?>" placeholder="eg. http://example.com">

                    </div>
                    <div class="col-md-3 col-md-12">
                    <span data-id="<?= $index; ?>" class="removeSocialField dashicons dashicons-trash"
                          title="<?php _e('Remove this item', ATBDP_TEXTDOMAIN); ?>"></span> <span class="adl-move-icon dashicons dashicons-move"></span>
                    </div>
                </div> <!--   ends .row   &  .atbdp_social_field_wrapper-->

                <?php
            }

        }?>
    </div> <!--    ends .social_info_sortable_container    -->

    <button type="button" class="btn btn-secondary btn-sm" id="addNewSocial"> <span class="plus-sign">+</span>
        <?php esc_html_e('Add New', ATBDP_TEXTDOMAIN); ?>
    </button>


