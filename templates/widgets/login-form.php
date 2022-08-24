<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist">
    <?php
    if (isset($_GET['login']) && $_GET['login'] == 'failed') {
        printf('<p class="alert-danger">  <span class="' . esc_attr( atbdp_icon_type() ) . '-exclamation"></span>%s</p>', esc_html__(' Invalid username or password!', 'directorist'));
    }
    wp_login_form();
    wp_register();

	$sign_up_text = sprintf(__( "Don't have an account? <a href='%s'>Sign up</a>", 'directorist' ), ATBDP_Permalink::get_registration_page_link() );
    ?>
	<p><?php echo wp_kses_post( $sign_up_text );?></p>
</div>

