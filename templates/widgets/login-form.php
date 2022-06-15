<?php
/**
 * @author  wpWax
 * @since   7.3
 * @version 7.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist">
    <?php
    if (isset($_GET['login']) && $_GET['login'] == 'failed') {
		?>
		<p class="alert-danger"> <?php directorist_icon( 'las la-exclamation' ); ?><?php esc_html_e(' Invalid username or password!', 'directorist');?></p>
		<?php
    }
    wp_login_form();
    wp_register();
    printf(__('<p>Don\'t have an account? %s</p>', 'directorist'), "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign up', 'directorist') . "</a>");
    ?>
</div>

