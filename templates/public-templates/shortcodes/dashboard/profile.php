<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_tab_inner" id="profile">
    <form action="#" id="user_profile_form" method="post">
        <div class="row">
            <div class="col-md-3 col-sm-6 offset-sm-3 offset-md-0">
                <div class="user_pro_img_area">
                    <div id="user_profile_pic" class="ez-media-uploader" data-type="jpg, jpeg, png, gif" data-min-file-items="0" data-max-file-items="1" data-max-total-file-size="0" data-allow-multiple="0" data-show-alerts="false" data-show-file-size="false" data-featured="false" data-allow-sorting="false" data-show-info="false" data-uploader-type="avater">

                        <div class="ezmu__loading-section ezmu--show">
                            <span class="ezmu__loading-icon">
                                <span class="ezmu__loading-icon-img-bg"></span>
                            </span>
                        </div>
                        
                        <div class="ezmu__old-files">
                            <?php if ($u_pro_pic) { ?>
                                <span class="ezmu__old-files-meta" data-attachment-id="<?php echo esc_attr($u_pro_pic_id); ?>" data-url="<?php echo esc_url($u_pro_pic[0]); ?>" data-type="image"></span>
                            <?php } ?>
                        </div>

                        <div class="ezmu-dictionary">
                            <span class="ezmu-dictionary-label-select-files"><?php esc_html_e('Select', 'directorist'); ?></span>
                            <span class="ezmu-dictionary-label-add-more"><?php esc_html_e('Select', 'directorist'); ?></span>
                            <span class="ezmu-dictionary-label-change"><?php esc_html_e('Change', 'directorist'); ?></span>
                            <span class="ezmu-dictionary-alert-max-total-file-size"><?php esc_html_e('Max limit for total file size is __DT__', 'directorist'); ?></span>
                            <span class="ezmu-dictionary-alert-min-file-items"><?php esc_html_e('Min __DT__ file is required', 'directorist'); ?></span>
                            <span class="ezmu-dictionary-alert-max-file-items"><?php esc_html_e('Max limit for total file is __DT__', 'directorist'); ?></span>
                            <span class="ezmu-dictionary-info-max-total-file-size"><?php esc_html_e('Maximum allowed file size is __DT__', 'directorist'); ?></span>
                            <span class="ezmu-dictionary-info-type" data-show='0'></span>
                            <span class="ezmu-dictionary-info-min-file-items"><?php esc_html_e('Minimum __DT__ file is required', 'directorist'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="atbd_user_profile_edit">
                    <div class="profile_title">
                        <h4><?php esc_html_e('My Profile', 'directorist'); ?></h4>
                    </div>

                    <div class="user_info_wrap">

                        <input type="hidden" name="ID" value="<?php echo get_current_user_id(); ?>">

                        <div class="row row_fu_name">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name"><?php esc_html_e('Full Name', 'directorist'); ?></label>
                                    <input class="form-control" type="text" id="full_name" name="user[full_name]" value="<?php echo !empty($c_user->display_name) ? esc_attr($c_user->display_name) : ''; ?>" placeholder="<?php esc_html_e('Enter your full name', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_name"><?php esc_html_e('User Name', 'directorist'); ?></label>
                                    <input class="form-control" id="user_name" type="text" disabled="disabled" name="user[user_name]" value="<?php echo !empty($c_user->user_login) ? esc_attr($c_user->user_login) : ''; ?>"> <?php esc_html_e('(username can not be changed)', 'directorist'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row row_fl_name">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name"><?php esc_html_e('First Name', 'directorist'); ?></label>
                                    <input class="form-control" id="first_name" type="text" name="user[first_name]" value="<?php echo !empty($c_user->first_name) ? esc_attr($c_user->first_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name"><?php esc_html_e('Last Name', 'directorist'); ?></label>
                                    <input class="form-control" id="last_name" type="text" name="user[last_name]" value="<?php echo !empty($c_user->last_name) ? esc_attr($c_user->last_name) : ''; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row row_email_cell">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="req_email"><?php esc_html_e('Email (required)', 'directorist'); ?></label>
                                    <input class="form-control" id="req_email" type="text" name="user[user_email]" value="<?php echo !empty($c_user->user_email) ? esc_attr($c_user->user_email) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone"><?php esc_html_e('Cell Number', 'directorist'); ?></label>
                                    <input class="form-control" type="tel" id="phone" name="user[phone]" value="<?php echo esc_attr($u_phone); ?>" placeholder="<?php esc_attr_e('Enter your phone number', 'directorist'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row row_site_addr">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website"><?php esc_html_e('Website', 'directorist'); ?></label>
                                    <input class="form-control" id="website" type="text" name="user[website]" value="<?php echo !empty($u_website) ? esc_url($u_website) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address"><?php esc_html_e('Address', 'directorist'); ?></label>
                                    <input class="form-control" id="address" type="text" name="user[address]" value="<?php echo esc_attr($u_address); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row row_password">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="new_pass"><?php esc_html_e('New Password', 'directorist'); ?></label>
                                    <input id="new_pass" class="form-control" type="password" name="user[new_pass]" value="<?php echo !empty($new_pass) ? esc_attr($new_pass) : ''; ?>" placeholder="<?php esc_attr_e('Enter a new password', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_pass"><?php esc_html_e('Confirm New Password', 'directorist'); ?></label>
                                    <input id="confirm_pass" class="form-control" type="password" name="user[confirm_pass]" value="<?php echo !empty($confirm_pass) ? esc_attr($confirm_pass) : ''; ?>" placeholder="<?php esc_attr_e('Confirm your new password', 'directorist'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row row_socials">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook"><?php esc_html_e('Facebook', 'directorist'); ?></label>
                                    <p><?php esc_html_e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="facebook" class="form-control" type="url" name="user[facebook]" value="<?php echo esc_attr($facebook); ?>" placeholder="<?php esc_attr_e('Enter your facebook url', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter"><?php esc_html_e('Twitter', 'directorist'); ?></label>
                                    <p><?php esc_html_e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="twitter" class="form-control" type="url" name="user[twitter]" value="<?php echo esc_attr($twitter); ?>" placeholder="<?php esc_attr_e('Enter your twitter url', 'directorist'); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedIn"><?php esc_html_e('LinkedIn', 'directorist'); ?></label>
                                    <p><?php esc_html_e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="linkedIn" class="form-control" type="url" name="user[linkedIn]" value="<?php echo esc_attr($linkedIn); ?>" placeholder="<?php esc_attr_e('Enter linkedIn url', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube"><?php esc_html_e('Youtube', 'directorist'); ?></label>
                                    <p><?php esc_html_e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="youtube" class="form-control" type="url" name="user[youtube]" value="<?php echo esc_attr($youtube); ?>" placeholder="<?php esc_attr_e('Enter youtube url', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bio"><?php esc_html_e('About Author', 'directorist'); ?></label>
                                    <textarea class="wp-editor-area form-control" style="height: 200px" autocomplete="off" cols="40" name="user[bio]" id="bio"><?php echo esc_html($bio); ?></textarea>
                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary" id="update_user_profile"><?php esc_html_e('Save Changes', 'directorist'); ?></button>

                        <div id="pro_notice" style="padding: 20px"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>