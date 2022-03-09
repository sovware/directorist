<?php
/**
 * @author  wpWax
 * @since   7.2
 * @version 7.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<section class="directory_wrapper single_area">
    <div class="notice_wrapper">
       <div class="directorist-alert directorist-alert-warning">
			<span class="fa fa-info-circle" aria-hidden="true"></span>
			<?php echo wp_kses_post( sprintf( __( 'You need to be logged in to view the content of this page. You can login <a href="%s"> Here</a>. Don\'t have an account? <a href="%s"> Sign Up</a>', 'directorist' ), ATBDP_Permalink::get_login_page_link(), ATBDP_Permalink::get_registration_page_link() ) );?>
		</div>
    </div>
</section>