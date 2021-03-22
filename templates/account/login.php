<?php
use \Directorist\Helper;
?>
<div class="directorist-login-wrapper directorist-w-100">
    <div class="<?php Helper::directorist_container_fluid(); ?>">
        <div class="<?php Helper::directorist_row(); ?>">
            <div class="directorist-col-md-6 directorist-offset-md-3">
                <div class="atbdp_login_form_shortcode">
                    <?php
                        // start recovery stuff
                        $recovery = isset( $_GET['user'] ) ? $_GET['user'] : '';
                        $key      = isset( $_GET['key'] ) ? $_GET['key'] : '';

                        if ( ! empty( $recovery ) ) {
                            $user = get_user_by( 'email', $recovery );
                            if ( isset( $_POST['directorist_reset_password'] ) && 'true' == $_POST['directorist_reset_password'] ) {
                                $password_1 = isset( $_POST['password_1'] ) ? $_POST['password_1'] : '';
                                $password_2 = isset( $_POST['password_2'] ) ? $_POST['password_2'] : '';

                                if (  ( $password_1 === $password_2 ) && ! empty( $password_1 && $password_2 ) ) {
                                    $update_user = wp_update_user( [
                                        'ID'        => $user->ID,
                                        'user_pass' => $password_2,
                                    ] );
                                if ( $update_user ) {
                                    ?><p class="atbd_reset_success"><?php echo esc_html__( 'Password changed successfully!', 'directorist' );
                ?>
                                        <a href="<?php echo esc_url( ATBDP_Permalink::get_login_page_url() ) ?>"><?php echo esc_html__( ' Login', 'directorist' );
                ?></a>
                                    </p><?php
                                            }
                                                } elseif ( empty( $password_1 || $password_2 ) ) {
                                                    ?><p class="atbd_reset_warning"><?php echo esc_html__( 'Fields are required!', 'directorist' ); ?></p><?php
} else {
            ?><p class="atbd_reset_error"><?php echo esc_html__( 'Password not matched!', 'directorist' ); ?></p><?php
    }
        }

        $db_key = get_user_meta( $user->ID, '_atbdp_recovery_key', true );
        if ( $key === $db_key ) {
            do_action( 'directorist_before_reset_password_form' );
        ?>
                            <form method="post" class="directorist-ResetPassword lost_reset_password">
                                <p><?php echo apply_filters( 'directorist_reset_password_message', esc_html__( 'Enter a new password below.', 'directorist' ) ); ?></p>
                                <p>
                                    <label for="password_1"><?php esc_html_e( 'New password', 'directorist' );?> &nbsp;<span class="required">*</span></label>
                                    <input type="password" class="directorist-Input directorist-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" />
                                </p>
                                <p>
                                    <label for="password_2">
                                        <?php esc_html_e( 'Re-enter new password', 'directorist' );?>&nbsp;
                                        <span class="required">*</span>
                                    </label>
                                    <input type="password" class="directorist-Input directorist-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" />
                                </p>

                                <div class="clear"></div>

                                <?php do_action( 'directorist_resetpassword_form' );?>

                                <p class="directorist-form-row form-row">
                                    <input type="hidden" name="directorist_reset_password" value="true" />
                                    <button type="submit" class="btn btn-primary" value="<?php esc_attr_e( 'Save', 'directorist' );?>"><?php esc_html_e( 'Save', 'directorist' );?></button>
                                </p>

                            <?php wp_nonce_field( 'reset_password', 'directorist-reset-password-nonce' );
                                    } else {
                                    ?>
                                <p><?php echo apply_filters( 'directorist_reset_password_link_exp_message', esc_html__( 'Sorry! The link is invalid.', 'directorist' ) ); ?></p>
                            <?php }
                                } else {
                                    $redirection = ATBDP_Permalink::get_login_redirection_page_link();
                                    $data        = [
                                        'ajax_url'            => admin_url( 'admin-ajax.php' ),
                                        'redirect_url'        => $redirection ? $redirection : ATBDP_Permalink::get_dashboard_page_link(),
                                        'loading_message'     => esc_html__( 'Sending user info, please wait...', 'directorist' ),
                                        'login_error_message' => esc_html__( 'Wrong username or password.', 'directorist' ),
                                    ];
                                    wp_localize_script( 'directorist-main-script', 'ajax_login_object', $data );

                                    $log_username        = get_directorist_option( 'log_username', __( 'Username or Email Address', 'directorist' ) );
                                    $log_password        = get_directorist_option( 'log_password', __( 'Password', 'directorist' ) );
                                    $display_rememberMe  = get_directorist_option( 'display_rememberme', 1 );
                                    $log_rememberMe      = get_directorist_option( 'log_rememberme', __( 'Remember Me', 'directorist' ) );
                                    $log_button          = get_directorist_option( 'log_button', __( 'Log In', 'directorist' ) );
                                    $display_recpass     = get_directorist_option( 'display_recpass', 1 );
                                    $recpass_text        = get_directorist_option( 'recpass_text', __( 'Recover Password', 'directorist' ) );
                                    $recpass_desc        = get_directorist_option( 'recpass_desc', __( 'Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'directorist' ) );
                                    $recpass_username    = get_directorist_option( 'recpass_username', __( 'E-mail:', 'directorist' ) );
                                    $recpass_placeholder = get_directorist_option( 'recpass_placeholder', __( 'eg. mail@example.com', 'directorist' ) );
                                    $recpass_button      = get_directorist_option( 'recpass_button', __( 'Get New Password', 'directorist' ) );
                                    $reg_text            = get_directorist_option( 'reg_text', __( "Don't have an account?", 'directorist' ) );
                                    $reg_url             = ATBDP_Permalink::get_registration_page_link();
                                    $reg_linktxt         = get_directorist_option( 'reg_linktxt', __( 'Sign Up', 'directorist' ) );
                                    $display_signup      = get_directorist_option( 'display_signup', 1 );
                                ?>
                            <form action="#" id="login" method="POST">
                                <div class="directorist-form-group directorist-mb-15">
                                    <label for="username"><?php echo $log_username; ?></label>
                                    <input type="text" class="directorist-form-element" id="username" name="username">
                                </div>

                                <div class="directorist-form-group directorist-mb-15">
                                    <label for="password"><?php echo $log_password; ?></label>
                                    <input type="password" id="password" autocomplete="off" name="password" class="directorist-form-element">
                                </div>

                                <div class="directorist-form-group atbd_login_btn_wrapper directorist-mb-15">
                                    <button class="directorist-btn directorist-btn-block directorist-btn-primary" type="submit" value="<?php echo $log_button; ?>" name="submit" /><?php echo $log_button; ?></button>
                                    <?php wp_nonce_field( 'ajax-login-nonce', 'security' );?>
                                </div>
                                <p class="status"></p>

                                <div class="keep_signed directorist-checkbox directorist-mb-15">
                                    <?php
                                        if ( $display_rememberMe ) {
                                            ?>
                                        <input type="checkbox" id="keep_signed_in" value="1" name="keep_signed_in" checked>
                                        <label for="keep_signed_in" class="directorist-checkbox__label not_empty">
                                            <?php echo $log_rememberMe; ?>
                                        </label>
                                </div>
                                <?php
                                        }
                                            if ( $display_recpass ) {
                                                printf( __( '<p>%s</p>', 'directorist' ), "<a href='' class='atbdp_recovery_pass'> " . __( $recpass_text, 'directorist' ) . '</a>' );
                                            }
                                        ?>
                            </form>

                            <div class="atbd_social_login">
                                <?php do_action( 'atbdp_before_login_form_end' );?>
                            </div>

                            <?php if ( ! empty( $display_signup ) ) {?>
                                <p>
                                    <?php echo $reg_text; ?>
                                    <a href="<?php echo $reg_url; ?>"><?php echo $reg_linktxt; ?></a>
                                </p>
                            <?php }
                                    //stuff to recover password start
                                    $error = $success = '';
                                    // check if we're in reset form
                                    if ( isset( $_POST['action'] ) && 'reset' == $_POST['action'] ) {
                                        $email = trim( $_POST['user_login'] );
                                        if ( empty( $email ) ) {
                                            $error = __( 'Enter an e-mail address..', 'directorist' );
                                        } else if ( ! is_email( $email ) ) {
                                            $error = __( 'Invalid e-mail address.', 'directorist' );
                                        } else if ( ! email_exists( $email ) ) {
                                            $error = __( 'There is no user registered with that email address.', 'directorist' );
                                        } else {
                                            $random_password = wp_generate_password( 22, false );
                                            $user            = get_user_by( 'email', $email );
                                            $update_user     = update_user_meta( $user->ID, '_atbdp_recovery_key', $random_password );
                                            // if  update user return true then lets send user an email containing the new password
                                            if ( $update_user ) {
                                                $subject = esc_html__( '	Password Reset Request', 'directorist' );
                                                //$message = esc_html__('Your new password is: ', 'directorist') . $random_password;

                                                $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
                                                $message   = __( 'Someone has requested a password reset for the following account:', 'directorist' ) . '<br>';
                                                /* translators: %s: site name */
                                                $message .= sprintf( __( 'Site Name: %s', 'directorist' ), $site_name ) . '<br>';
                                                /* translators: %s: user login */
                                                $message .= sprintf( __( 'User: %s', 'directorist' ), $user->user_login ) . '<br>';
                                                $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'directorist' ) . '<br>';
                                                $message .= __( 'To reset your password, visit the following address:', 'directorist' ) . '<br>';
                                                $link = [
                                                    'key'  => $random_password,
                                                    'user' => $email,
                                                ];
                                                $message .= '<a href="' . esc_url( add_query_arg( $link, ATBDP_Permalink::get_login_page_url() ) ) . '">' . esc_url( add_query_arg( $link, ATBDP_Permalink::get_login_page_url() ) ) . '</a>';

                                                $message = atbdp_email_html( $subject, $message );

                                                $headers[] = 'Content-Type: text/html; charset=UTF-8';
                                                $mail      = wp_mail( $email, $subject, $message, $headers );
                                                if ( $mail ) {
                                                    $success = __( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox.', 'directorist' );
                                                } else {
                                                    $error = __( 'Password updated! But something went wrong sending email.', 'directorist' );
                                                }
                                            } else {
                                                $error = __( 'Oops something went wrong updating your account.', 'directorist' );
                                            }
                                        }

                                        if ( ! empty( $error ) ) {
                                            echo '<div class="message"><p class="error"><strong>' . __( 'ERROR:', 'directorist' ) . '</strong> ' . $error . '</p></div>';
                                        }

                                        if ( ! empty( $success ) ) {
                                            echo '<div class="error_login"><p class="success">' . $success . '</p></div>';
                                        }

                                }?>
                            <div id="recover-pass-modal" class="directorist-mt-15">
                                <form method="post">
                                    <fieldset class="directorist-form-group">
                                        <p><?php printf( __( '%s', 'directorist' ), $recpass_desc );?></p>
                                        <label for="reset_user_login"><?php printf( __( '%s', 'directorist' ), $recpass_username );?></label>
                                        <?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';?>
                                        <input type="text" class="directorist-form-element" name="user_login" id="reset_user_login" value="<?php echo $user_login; ?>" placeholder="<?php echo $recpass_placeholder ?>" required />
                                        <p>
                                            <input type="hidden" name="action" value="reset" />
                                            <button type="submit" class="directorist-btn directorist-btn-primary" id="submit"><?php printf( __( '%s', 'directorist' ), $recpass_button );?></button>
                                        </p>
                                    </fieldset>
                                </form>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>