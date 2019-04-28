<form id="login" class="ajax-auth" action="login" method="post">
    <h3>New to site? <a id="pop_signup" href="">Create an Account</a></h3>
    <hr />
    <h1>Login</h1>
    <p class="status"></p>
    <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
    <label for="username">Username</label>
    <input id="username" type="text" class="required" name="username">
    <label for="password">Password</label>
    <input id="password" type="password" class="required" name="password">
    <a class="text-link" href="<?php
    echo wp_lostpassword_url(); ?>">Lost password?</a>
    <input class="submit_button" type="submit" value="LOGIN">
    <a class="close" href="">(close)</a>
</form>

<form id="register" class="ajax-auth"  action="register" method="post">
    <h3>Already have an account? <a id="pop_login"  href="">Login</a></h3>
    <hr />
    <h1>Signup</h1>
    <p class="status"></p>
    <?php wp_nonce_field('ajax-register-nonce', 'signonsecurity'); ?>
    <label for="signonname">Username</label>
    <input id="signonname" type="text" name="signonname" class="required">
    <label for="email">Email</label>
    <input id="email" type="text" class="required email" name="email">
    <label for="signonpassword">Password</label>
    <input id="signonpassword" type="password" class="required" name="signonpassword" >
    <label for="password2">Confirm Password</label>
    <input type="password" id="password2" class="required" name="password2">
    <input class="submit_button" type="submit" value="SIGNUP">
    <a class="close" href="">(close)</a>
</form>


<?php
wp_enqueue_style('atbdp-ajax-style');
wp_enqueue_script('atbdp_validator');
wp_enqueue_script('ajax_login_auth_script');

wp_localize_script( 'ajax_login_auth_script', 'ajax_auth_object', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'redirecturl' => home_url(),
    'loadingmessage' => __('Sending user info, please wait...')
));
if (is_user_logged_in()) { ?>
    <a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
<?php } else { get_template_part('ajax', 'auth'); ?>
    <a class="login_button" id="show_login" href="">Login</a>
    <a class="login_button" id="show_signup" href="">Signup</a>
<?php } ?>















<script>

    jQuery(document).ready(function ($) {
        // Display form from link inside a popup
        $('#pop_login, #pop_signup').live('click', function (e) {
            formToFadeOut = $('form#register');
            formtoFadeIn = $('form#login');
            if ($(this).attr('id') == 'pop_signup') {
                formToFadeOut = $('form#login');
                formtoFadeIn = $('form#register');
            }
            formToFadeOut.fadeOut(500, function () {
                formtoFadeIn.fadeIn();
            })
            return false;
        });

        // Close popup
        $(document).on('click', '.login_overlay, .close', function () {
            $('form#login, form#register').fadeOut(500, function () {
                $('.login_overlay').remove();
            });
            return false;
        });

        // Show the login/signup popup on click
        $('#show_login, #show_signup').on('click', function (e) {
            $('body').prepend('<div class="login_overlay"></div>');
            if ($(this).attr('id') == 'show_login')
                $('form#login').fadeIn(500);
            else
                $('form#register').fadeIn(500);
            e.preventDefault();
        });

        // Perform AJAX login/register on form submit
        $('form#login, form#register').on('submit', function (e) {
            if (!$(this).valid()) return false;
            $('p.status', this).show().text(ajax_auth_object.loadingmessage);
            action = 'ajaxlogin';
            username = 	$('form#login #username').val();
            password = $('form#login #password').val();
            email = '';
            security = $('form#login #security').val();
            if ($(this).attr('id') == 'register') {
                action = 'ajaxregister';
                username = $('#signonname').val();
                password = $('#signonpassword').val();
                email = $('#email').val();
                security = $('#signonsecurity').val();
            }
            ctrl = $(this);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_auth_object.ajaxurl,
                data: {
                    'action': action,
                    'username': username,
                    'password': password,
                    'email': email,
                    'security': security
                },
                success: function (data) {
                    $('p.status', ctrl).text(data.message);
                    if (data.loggedin == true) {
                        document.location.href = ajax_auth_object.redirecturl;
                    }
                }
            });
            e.preventDefault();
        });

        // Client side form validation
        if (jQuery("#register").length)
            jQuery("#register").validate(
                {
                    rules:{
                        password2:{ equalTo:'#signonpassword'
                        }
                    }}
            );
        else if (jQuery("#login").length)
            jQuery("#login").validate();
    });
</script>
