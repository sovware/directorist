<?php
/**
 * @author AazzTech
 */
class ATBDP_Send_Mail
{

    public function __construct() {
        add_action( 'wp_ajax_send_system_info', array( $this, 'send_system_info' ) );
    }

    public function send_system_info() {
		$user = wp_get_current_user();
		$email = isset( $_POST['email'] ) ? $_POST['email'] : '';
		$subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
		$system_info = isset( $_POST['system_info'] ) ?  $_POST['system_info']  : '';
		$to = ! empty( $email ) ? sanitize_email( $email ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
		if( ! empty( $system_info ) ) {
			$message .=   $this->system_info();
		}
		$message  = atbdp_email_html( $subject, $message );
		$headers = "From: {$user->display_name} <{$user->user_email}>\r\n";
		$headers .= "Reply-To: {$user->user_email}\r\n";

		// return true or false, based on the result
		$send_email =  ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;

		if( $send_email ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

    public function system_info() {
		ob_start();
		new ATBDP_System_Info();
		return ob_get_clean();
	}

    public function send_email_to() {
        if ( ! current_user_can( 'manage_options' ) ) {
                return;
        }
		?>
        <h3><span><?php _e( 'Send to:', 'directorist' ); ?></span></h3>

        <div class="inside">

            <div id="atbdp-email-response"></div>

            <form id="atbdp-send-system-info" method="post" enctype="multipart/form-data" action="<?php echo esc_url( self_admin_url( 'admin-ajax.php' ) ); ?>">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="atbdp-email-address">
                                <?php _e( 'Email Address', 'directorist' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="email" name="email" id="atbdp-email-address" class="regular-text" placeholder="<?php _e( 'user@email.com', 'directorist'); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="atbdp-email-subject">
                                <?php _e( 'Subject', 'directorist' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" name="subject" id="atbdp-email-subject" class="regular-text" placeholder="<?php _e( 'Subject', 'directorist'); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="atbdp-email-message">
                                <?php _e( 'Additional Message', 'directorist' ); ?>
                            </label>
                        </th>
                        <td>
                            <textarea name="message" id="atbdp-email-message" class="large-text" rows="10" cols="50" placeholder="<?php _e( 'Enter additional message here.', 'directorist' ); ?>"></textarea>
                            <p class="description">
                            <input type="checkbox" name='atbdp_system_info' id='atbdp_system_info' checked>
                                <?php _e(
                                    'Your system information will be attached automatically to this email.',
                                    'directorist'
                                ) ?>
                            </p>

                        </td>
                    </tr>
                </table>
                <p class='system_info_success'></p>
                <?php submit_button( __( 'Send Email', 'directorist' ), 'secondary', 'submit', TRUE, array( 'id' => 'atbdp-send-system-info-submit' ) ) ?>
            </form>

        </div><!-- .inside -->
		<?php
		do_action( 'atbdp_tools_email_system_info_after' );
    
    }
}