<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.7.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-card__body">
  <div class="directorist-widget-authentication">
    <?php
      if (isset($_GET['login']) && $_GET['login'] == 'failed') {
      ?>
      <p class="alert-danger"> <?php directorist_icon( 'las la-exclamation' ); ?><?php esc_html_e(' Invalid username or password!', 'directorist');?></p>
      <?php
      }
      wp_login_form();
      wp_register();

    $sign_up_text = sprintf(__( "Don't have an account? <a href='%s'>Sign up</a>", 'directorist' ), ATBDP_Permalink::get_registration_page_link() );
      ?>
    <p><?php echo wp_kses_post( $sign_up_text );?></p>
  </div>
</div>

