<?php
$container_fluid             = is_directoria_active() ? 'container' : 'container-fluid';
$username                    = get_directorist_option('reg_username','Username');
$password                    = get_directorist_option('reg_password','Password');
$display_password_reg        = get_directorist_option('display_password_reg',1);
$require_password            = get_directorist_option('require_password_reg',1);
$email                       = get_directorist_option('reg_email','Email');
$display_website             = get_directorist_option('display_website_reg',0);
$website                     = get_directorist_option('reg_website','Website');
$require_website             = get_directorist_option('require_website_reg',0);
$display_fname               = get_directorist_option('display_fname_reg',0);
$first_name                  = get_directorist_option('reg_fname','First Name');
$require_fname               = get_directorist_option('require_fname_reg',0);
$display_lname               = get_directorist_option('display_lname_reg',0);
$last_name                   = get_directorist_option('reg_lname','Last Name');
$require_lname               = get_directorist_option('require_lname_reg',0);
$display_bio                 = get_directorist_option('display_bio_reg',0);
$bio                         = get_directorist_option('reg_bio','About/bio');
$require_bio                 = get_directorist_option('require_bio_reg',0);
$reg_signup                  = get_directorist_option('reg_signup','Sign Up');
$display_login               = get_directorist_option('display_login',1);
$login_text                  = get_directorist_option('login_text',__('Already have an account? Please login', 'directorist'));
$login_url                   = ATBDP_Permalink::get_login_page_link();
$log_linkingmsg              = get_directorist_option('log_linkingmsg',__('Here', 'directorist'));
$terms_label                 = get_directorist_option('regi_terms_label', __('I agree with all', 'directorist'));
$terms_label_link            = get_directorist_option('regi_terms_label_link', __('terms & conditions', 'directorist'));
$t_C_page_link               = ATBDP_Permalink::get_terms_and_conditions_page_url();
$privacy_page_link           = ATBDP_Permalink::get_privacy_policy_page_url();
$privacy_label               = get_directorist_option('registration_privacy_label', __('I agree to the', 'directorist'));
$privacy_label_link          = get_directorist_option('registration_privacy_label_link', __('Privacy & Policy', 'directorist'));
?>
<div id="directorist" class="atbd_wrapper directorist">
    <div class="<?php echo apply_filters('atbdp_registration_container_fluid',$container_fluid) ?>">
        <div class="row">
            <div class="col-md-6 offset-md-3 mb-3">
                <div class="add_listing_title atbd_success_mesage">
                    <?php
                    $display_password = get_directorist_option('display_password_reg', 1);
                    if(!empty($_GET['registration_status']) && true == $_GET['registration_status']){
                        if (empty($display_password)){
                            ?>
                            <p style="padding: 20px" class="alert-success"><span class="fa fa-check"></span><?php _e(' Go to your inbox or spam/junk and get your password.', 'directorist'); ?>
                                <?php
                                printf(__(' Click %s to login.', 'directorist'), "<a href='" . ATBDP_Permalink::get_login_page_link() . "'><span style='color: red'> " . __('Here', 'directorist') . "</span></a>");
                                ?>
                            </p>
                            <?php
                        }else {
                            ?>
                            <!--registration succeeded, so show notification -->
                            <p style="padding: 20px" class="alert-success"><span
                                        class="fa fa-check"></span><?php _e(' Registration completed. Please check your email for confirmation.', 'directorist'); ?>
                                <?php
                                printf(__(' Or click %s to login.', 'directorist'), "<a href='" . ATBDP_Permalink::get_login_page_link() . "'><span style='color: red'> " . __('Here', 'directorist') . "</span></a>");
                                ?>
                            </p>
                            <?php
                        }

                    }
                    ?>
                    <!--Registration failed, so show notification.-->
                    <?php
                    $errors = !empty($_GET['errors']) ? $_GET['errors'] : '';
                    switch ($errors) {
                        case '1':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Registration failed. Please make sure you filed up all the necessary fields marked with <span style="color: red">*</span>', 'directorist'); ?></p><?php
                           break;
                        case '2':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Sorry, that email already exists!', 'directorist'); ?></p><?php
                            break;
                        case '3':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Username too short. At least 4 characters is required', 'directorist'); ?></p><?php
                            break;
                        case '4':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Sorry, that username already exists!', 'directorist'); ?></p><?php
                            break;
                        case '5':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Password length must be greater than 5', 'directorist'); ?></p><?php
                            break;
                        case '6':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Email is not valid', 'directorist'); ?></p><?php
                            break;
                        case '7':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Space is not allowed in username', 'directorist'); ?></p><?php
                            break;
                    }
                  ?>
                </div>
            </div>
                    <div class="col-md-6 offset-md-3">
                        <div class="directory_register_form_wrap">
                            <form action="<?php echo esc_url(get_the_permalink()); ?>" method="post">
                                <div class="form-group">
                                    <label for="username"><?php printf(__('%s', 'directorist'),$username); ?> <strong class="atbdp_make_str_red">*</strong></label>
                                    <input id="username" class="form-control" type="text" name="username" value="<?php echo ( isset( $_POST['username'] ) ? esc_attr($_POST['username']) : null ); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email"><?php printf(__('%s', 'directorist'),$email); ?> <strong class="atbdp_make_str_red">*</strong></label>
                                    <input id="email" class="form-control" type="text" name="email" value="<?php echo ( isset( $_POST['email']) ? $_POST['email'] : null ); ?>">
                                </div>
                                <?php if(!empty($display_password_reg)) {?>
                                    <div class="form-group">
                                        <label for="password">
                                            <?php printf(__('%s ', 'directorist'),$password);
                                            echo !empty($require_password) ? '<strong class="atbdp_make_str_red">*</strong>': '';
                                            ?></label>
                                        <input id="password" class="form-control" type="password" name="password" value="<?php echo ( isset( $_POST['password'] ) ? esc_attr($_POST['password']) : null ); ?>">
                                    </div>
                                <?php } ?>
                                <?php  if(!empty($display_fname)) {?>
                                <div class="form-group">
                                    <label for="fname"><?php printf(__('%s ', 'directorist'),$first_name);
                                        echo !empty($require_fname) ? '<strong class="atbdp_make_str_red">*</strong>': '';
                                    ?></label>
                                    <input id="fname" class="form-control" type="text" name="fname" value="<?php echo ( isset( $_POST['fname']) ? esc_attr($_POST['fname']) : null ); ?>">
                                </div>
                                <?php } if(!empty($display_lname)) {?>
                                <div class="form-group">
                                    <label for="lname"><?php printf(__('%s ', 'directorist'),$last_name);
                                        echo !empty($require_lname) ? '<strong class="atbdp_make_str_red">*</strong>': '';
                                    ?></label>
                                    <input class="form-control" id="lname" type="text" name="lname" value="<?php echo ( isset( $_POST['lname']) ? esc_attr($_POST['lname']) : null ); ?>">
                                </div>
                                <?php } if(!empty($display_website)) { ?>
                                    <div class="form-group">
                                        <label for="website"><?php printf(__('%s ', 'directorist'),$website);
                                            echo !empty($require_website) ? '<strong class="atbdp_make_str_red">*</strong>': '';
                                            ?></label>
                                        <input id="website" class="form-control" type="text" name="website" value="<?php echo ( isset( $_POST['website']) ? esc_url($_POST['website']) : null ); ?>">
                                    </div>
                                <?php } if(!empty($display_bio)) { ?>
                                <div class="form-group">
                                    <label for="bio"><?php printf(__('%s ', 'directorist'),$bio);
                                        echo !empty($require_bio) ? '<strong class="atbdp_make_str_red">*</strong>': '';
                                    ?></label>
                                    <textarea id="bio" class="form-control" name="bio" rows="10"><?php echo ( isset( $_POST['bio']) ? esc_textarea($_POST['bio']) : null ); ?></textarea>
                                </div>
                                <?php }
                                if (!empty(get_directorist_option('registration_privacy',1))) {
                                    ?>
                                    <div class="atbd_privacy_policy_area directory_regi_btn">
                                        <?php
                                        if (get_directorist_option('require_registration_privacy') == 1) {
                                            printf('<span class="atbdp_make_str_red"> *</span>');
                                        }
                                        ?>
                                        <input id="privacy_policy" type="checkbox"
                                               name="privacy_policy" <?php if (!empty($privacy_policy)) if ('on' == $privacy_policy) {
                                            echo 'checked';
                                        } ?>>
                                        <label for="privacy_policy"><?php echo esc_attr($privacy_label); ?>
                                            <a
                                                    style="color: red" target="_blank" href="<?php echo esc_url($privacy_page_link)?>" id=""
                                            ><?php echo esc_attr($privacy_label_link); ?></a></label>
                                    </div>

                                    <?php
                                }
                                if (!empty(get_directorist_option('regi_terms_condition',1))) {
                                    ?>
                                    <div class="atbd_term_and_condition_area directory_regi_btn">
                                        <?php
                                        if (get_directorist_option('require_regi_terms_conditions') == 1) {
                                            printf('<span class="atbdp_make_str_red"> *</span>');
                                        }
                                        ?>
                                        <input id="listing_t" type="checkbox"
                                               name="t_c_check" <?php if (!empty($t_c_check)) if ('on' == $t_c_check) {
                                            echo 'checked';
                                        } ?>>
                                        <label for="listing_t"><?php echo esc_attr($terms_label); ?>
                                            <a
                                                    style="color: red" target="_blank" href="<?php echo esc_url($t_C_page_link)?>" id=""
                                            ><?php echo esc_attr($terms_label_link); ?></a></label>
                                    </div>

                                    <?php
                                }
                                /*
                                 * @since 4.4.0
                                 */
                                do_action('atbdp_before_user_registration_submit');
                                ?>
                                <?php if(!$display_password_reg) {?>
                                <div class="directory_regi_btn">
                                    <p><?php _e('Password will be e-mailed to you.','directorist');?></p>
                                </div>
                                <?php } ?>
                                <div class="directory_regi_btn">
                                    <button type="submit" class="btn btn-primary" name="atbdp_user_submit"><?php printf(__('%s ', 'directorist'),$reg_signup); ?></button>
                                </div>
                                <?php if(!empty($display_login)) { ?>
                                <div class="directory_regi_btn">
                                 <p><?php echo $login_text; ?> <a href="<?php echo $login_url; ?>"><?php echo $log_linkingmsg;?></a></p>
                                </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>

        </div> <!--ends .row-->
    </div>
</div>

