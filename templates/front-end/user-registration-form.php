<?php
$container_fluid             = is_directoria_active() ? 'container' : 'container-fluid';
?>
<div id="directorist" class="atbd_wrapper directorist">
    <div class="<?php echo apply_filters('atbdp_registration_container_fluid',$container_fluid) ?>">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="add_listing_title atbd_success_mesage">

                    <h2><?php _e('Register', ATBDP_TEXTDOMAIN); ?></h2>
                    <?php if(!empty($_GET['success']) && true == $_GET['success']){ ?>
                        <!--registration succeeded, so show notification -->
                        <p style="padding: 20px" class="alert-success"><span class="fa fa-check"></span><?php _e(' Registration completed. Please check your email for confirmation.', ATBDP_TEXTDOMAIN); ?>
                            <?php
                            printf(__(' Or click %s to login.', ATBDP_TEXTDOMAIN), "<a href='".ATBDP_Permalink::get_login_page_link()."'><span style='color: red'> ". __('Here', ATBDP_TEXTDOMAIN)."</span></a>");
                            ?>

                        </p>
                        <?php
                        exit();
                    }
                    ?>
                    <!--Registration failed, so show notification.-->
                    <?php
                    $errors = !empty($_GET['errors']) ? $_GET['errors'] : '';
                    switch ($errors) {
                        case '1':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Registration failed. Please make sure you filed up all the necessary fields marked with <span style="color: red">*</span>', ATBDP_TEXTDOMAIN); ?></p><?php
                           break;
                        case '2':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Sorry, that email already exists!', ATBDP_TEXTDOMAIN); ?></p><?php
                            break;
                        case '3':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Username too short. At least 4 characters is required', ATBDP_TEXTDOMAIN); ?></p><?php
                            break;
                        case '4':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Sorry, that username already exists!', ATBDP_TEXTDOMAIN); ?></p><?php
                            break;
                        case '5':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Password length must be greater than 5', ATBDP_TEXTDOMAIN); ?></p><?php
                            break;
                        case '6':
                            ?> <p style="padding: 20px" class="alert-danger"> <span class="fa fa-exclamation-triangle"></span> <?php _e('Email is not valid', ATBDP_TEXTDOMAIN); ?></p><?php
                            break;

                    }
                  ?>
                </div>
            </div>
                    <div class="col-md-8 offset-md-2">
                        <div class="directory_register_form_wrap">
                            <form action="<?= esc_url(get_the_permalink()); ?>" method="post">
                                <div class="form-group">
                                    <label for="username"><?php _e('Username', ATBDP_TEXTDOMAIN); ?> <strong>*</strong></label>
                                    <input id="username" class="form-control" type="text" name="username" value="<?= ( isset( $_POST['username'] ) ? esc_attr($_POST['username']) : null ); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="password"><?php _e('Password', ATBDP_TEXTDOMAIN); ?> <strong>*</strong></label>
                                    <input id="password" class="form-control" type="password" name="password" value="<?= ( isset( $_POST['password'] ) ? esc_attr($_POST['password']) : null ); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="email"><?php _e('Email', ATBDP_TEXTDOMAIN); ?> <strong>*</strong></label>
                                    <input id="email" class="form-control" type="text" name="email" value="<?= ( isset( $_POST['email']) ? $_POST['email'] : null ); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="website"><?php _e('Website', ATBDP_TEXTDOMAIN); ?></label>
                                    <input id="website" class="form-control" type="text" name="website" value="<?= ( isset( $_POST['website']) ? esc_url($_POST['website']) : null ); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="fname"><?php _e('First Name', ATBDP_TEXTDOMAIN); ?></label>
                                    <input id="fname" class="form-control" type="text" name="fname" value="<?= ( isset( $_POST['fname']) ? esc_attr($_POST['fname']) : null ); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="lname"><?php _e('Last Name', ATBDP_TEXTDOMAIN); ?></label>
                                    <input class="form-control" id="lname" type="text" name="lname" value="<?= ( isset( $_POST['lname']) ? esc_attr($_POST['lname']) : null ); ?>">
                                </div>


                                <div class="form-group">
                                    <label for="bio"><?php _e('About/bio', ATBDP_TEXTDOMAIN); ?></label>
                                    <textarea id="bio" class="form-control" name="bio" rows="10"><?= ( isset( $_POST['bio']) ? esc_textarea($_POST['bio']) : null ); ?></textarea>

                                </div>
                                <?php
                                /*
                                 * @since 4.4.0
                                 */
                                do_action('atbdp_before_user_registration_submit');
                                ?>

                                <div class="directory_regi_btn">
                                    <button type="submit" class="btn btn-primary btn-lg" name="atbdp_user_submit"><?php _e('Sign Up', ATBDP_TEXTDOMAIN); ?></button>
                                </div>

                                <div class="directory_regi_btn">
                                 <p>
                                     <?php
                                     printf(__('Already have an account? Please login %s.', ATBDP_TEXTDOMAIN), "<a href='".ATBDP_Permalink::get_login_page_link()."'><span style='color: red'> ". __('Here', ATBDP_TEXTDOMAIN)."</span></a>");
                                     ?>
                                 </p>
                                </div>

                            </form>
                        </div>
                    </div>

        </div> <!--ends .row-->
    </div>
</div>

