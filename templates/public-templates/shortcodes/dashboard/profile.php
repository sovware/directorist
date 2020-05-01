<div class="atbd_tab_inner" id="profile">
    <form action="#" id="user_profile_form" method="post">
        <div class="row">
            <div class="col-md-3 col-sm-6 offset-sm-3 offset-md-0">
                <div class="user_pro_img_area">
                    <div class="user_img" id="profile_pic_container">
                        <div class="cross" id="remove_pro_pic"><span
                                    class="fa fa-times"></span>
                        </div>
                        <div class="choose_btn">
                            <input type="hidden" name="user[pro_pic]" id="pro_pic"
                                   value="<?php echo !empty($u_pro_pic_id) ? esc_attr($u_pro_pic_id) : ''; ?>">
                            <label for="pro_pic"
                                   id="upload_pro_pic"><?php _e('Change', 'directorist'); ?></label>
                        </div> <!--ends .choose_btn-->
                        <img src="<?php echo !empty($u_pro_pic) ? esc_url($u_pro_pic[0]) : esc_url(ATBDP_PUBLIC_ASSETS . 'images/no-image.jpg'); ?>"
                             id="pro_img" alt="">

                    </div> <!--ends .user_img-->
                </div> <!--ends .user_pro_img_area-->
            </div> <!--ends .col-md-4-->

            <div class="col-md-9">
                <div class="atbd_user_profile_edit">
                    <div class="profile_title">
                        <h4><?php _e('My Profile', 'directorist'); ?></h4>
                    </div>

                    <div class="user_info_wrap">
                        <!--hidden inputs-->
                        <input type="hidden" name="ID"
                               value="<?php echo get_current_user_id(); ?>">
                        <!--Full name-->
                        <div class="row row_fu_name">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name"><?php _e('Full Name', 'directorist'); ?></label>
                                    <input class="form-control" type="text"
                                           name="user[full_name]"
                                           value="<?php echo !empty($c_user->display_name) ? esc_attr($c_user->display_name) : ''; ?>"
                                           placeholder="<?php _e('Enter your full name', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_name"><?php _e('User Name', 'directorist'); ?></label>
                                    <input class="form-control" id="user_name" type="text"
                                           disabled="disabled" name="user[user_name]"
                                           value="<?php echo !empty($c_user->user_login) ? esc_attr($c_user->user_login) : ''; ?>"> <?php _e('(username can not be changed)', 'directorist'); ?>
                                </div>
                            </div>
                        </div> <!--ends .row-->
                        <!--First Name-->
                        <div class="row row_fl_name">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name"><?php _e('First Name', 'directorist'); ?></label>
                                    <input class="form-control" id="first_name" type="text"
                                           name="user[first_name]"
                                           value="<?php echo !empty($c_user->first_name) ? esc_attr($c_user->first_name) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="last_name"><?php _e('Last Name', 'directorist'); ?></label>
                                    <input class="form-control" id="last_name" type="text"
                                           name="user[last_name]"
                                           value="<?php echo !empty($c_user->last_name) ? esc_attr($c_user->last_name) : ''; ?>">
                                </div>
                            </div>
                        </div> <!--ends .row-->
                        <!--Email-->
                        <div class="row row_email_cell">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="req_email"><?php _e('Email (required)', 'directorist'); ?></label>
                                    <input class="form-control" id="req_email" type="text"
                                           name="user[user_email]"
                                           value="<?php echo !empty($c_user->user_email) ? esc_attr($c_user->user_email) : ''; ?>"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone"><?php _e('Cell Number', 'directorist'); ?></label>
                                    <input class="form-control" type="tel"
                                           name="user[phone]"
                                           value="<?php echo !empty($u_phone) ? esc_attr($u_phone) : ''; ?>"
                                           placeholder="<?php _e('Enter your phone number', 'directorist'); ?>">
                                </div>
                            </div>
                        </div> <!--ends .row-->
                        <!--Website-->
                        <div class="row row_site_addr">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website"><?php _e('Website', 'directorist'); ?></label>
                                    <input class="form-control" id="website" type="text"
                                           name="user[website]"
                                           value="<?php echo !empty($u_website) ? esc_url($u_website) : ''; ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address"><?php _e('Address', 'directorist'); ?></label>
                                    <input class="form-control" id="address" type="text"
                                           name="user[address]"
                                           value="<?php echo !empty($u_address) ? esc_attr($u_address) : ''; ?>">
                                </div>
                            </div>
                        </div> <!--ends .row-->


                        <div class="row row_password">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="new_pass"><?php _e('New Password', 'directorist'); ?></label>
                                    <input id="new_pass" class="form-control"
                                           type="password"
                                           name="user[new_pass]"
                                           value="<?php echo !empty($new_pass) ? esc_attr($new_pass) : ''; ?>"
                                           placeholder="<?php _e('Enter a new password', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_pass"><?php _e('Confirm New Password', 'directorist'); ?></label>
                                    <input id="confirm_pass" class="form-control"
                                           type="password"
                                           name="user[confirm_pass]"
                                           value="<?php echo !empty($confirm_pass) ? esc_attr($confirm_pass) : ''; ?>"
                                           placeholder="<?php _e('Confirm your new password', 'directorist'); ?>">
                                </div>
                            </div>
                        </div><!--ends .row-->
                        <!--social info-->
                        <div class="row row_socials">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook"><?php _e('Facebook', 'directorist'); ?></label>
                                    <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="facebook" class="form-control" type="url"
                                           name="user[facebook]"
                                           value="<?php echo !empty($facebook) ? esc_attr($facebook) : ''; ?>"
                                           placeholder="<?php _e('Enter your facebook url', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter"><?php _e('Twitter', 'directorist'); ?></label>
                                    <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="twitter" class="form-control" type="url"
                                           name="user[twitter]"
                                           value="<?php echo !empty($twitter) ? esc_attr($twitter) : ''; ?>"
                                           placeholder="<?php _e('Enter your twitter url', 'directorist'); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedIn"><?php _e('LinkedIn', 'directorist'); ?></label>
                                    <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="linkedIn" class="form-control" type="url"
                                           name="user[linkedIn]"
                                           value="<?php echo !empty($linkedIn) ? esc_attr($linkedIn) : ''; ?>"
                                           placeholder="<?php _e('Enter linkedIn url', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube"><?php _e('Youtube', 'directorist'); ?></label>
                                    <p><?php _e('Leave it empty to hide', 'directorist') ?></p>
                                    <input id="youtube" class="form-control" type="url"
                                           name="user[youtube]"
                                           value="<?php echo !empty($youtube) ? esc_attr($youtube) : ''; ?>"
                                           placeholder="<?php _e('Enter youtube url', 'directorist'); ?>">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bio"><?php _e('About Author', 'directorist'); ?></label>
                                    <textarea class="wp-editor-area form-control"
                                              style="height: 200px" autocomplete="off"
                                              cols="40"
                                              name="user[bio]"
                                              id="bio"><?php echo !empty($bio) ? esc_attr($bio) : ''; ?></textarea>
                                </div>
                            </div>
                        </div><!--ends social info .row-->


                        <button type="submit" class="btn btn-primary"
                                id="update_user_profile"><?php _e('Save Changes', 'directorist'); ?></button>

                        <div id="pro_notice" style="padding: 20px"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>