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
        $nonce = isset( $_POST['_nonce'] ) ? $_POST['_nonce'] : '';
        if ( ! wp_verify_nonce( $nonce, '_debugger_email_nonce' ) ) {
            die ( 'huh!');
        }
		$user = wp_get_current_user();
        $email = isset( $_POST['email'] ) ? $_POST['email'] : '';
        $sender_email = isset( $_POST['sender_email'] ) ? $_POST['sender_email'] : '';
		$subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
		$system_info_url = isset( $_POST['system_info_url'] ) ?  $_POST['system_info_url']  : '';
		$to = ! empty( $email ) ? sanitize_email( $email ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
		if( ! empty( $system_info_url ) ) {
            $message .= '<div><a href="'.$system_info_url.'">';
            $message .=   $system_info_url;
            $message .= '</a></div>';
		}
		$message  = atbdp_email_html( $subject, $message );
		$headers = "From: {$user->display_name} <{$sender_email}>\r\n";
		$headers .= "Reply-To: {$sender_email}\r\n";

		// return true or false, based on the result
		$send_email =  ATBDP()->email->send_mail($to, $subject, $message, $headers) ? true : false;

		if( $send_email ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

    public function system_info() {
        include_once  ATBDP_INC_DIR . '/system-status/system-info.php'; 
		ob_start();
		new ATBDP_System_Info_Email_Link();
		return ob_get_clean();
	}

    public function send_email_to() {
        if ( ! current_user_can( 'manage_options' ) ) {
                return;
        }
        $token = get_transient( 'system_info_remote_token' );
		$url   = $token ? home_url() . '/?atbdp-system-info=' . $token : '';
        $user = wp_get_current_user();
		?>
        <div class="card atbds_card">
            <div class="card-head">
                <h4><?php _e( 'Support', 'directorist' ); ?></h4>
            </div>
            <div class="card-body">
                <div class="atbds_content__tab">
                    <div class="atbds_supportForm">
                         <form id="atbdp-send-system-info" method="post" enctype="multipart/form-data" action="<?php echo esc_url( self_admin_url( 'admin-ajax.php' ) ); ?>">
                            <div class="atbds_form-row">
                                <label><?php _e( 'Sender Email Address', 'directorist' ); ?></label>
                                <input type="email" name="sender_email" id="atbdp-sender-address" placeholder="<?php _e( 'user@email.com', 'directorist'); ?>" value="<?php echo $user->user_email; ?>">
                            </div>
                            <div class="atbds_form-row">
                                <label><?php _e( 'Receiver Email Address', 'directorist' ); ?></label>
                                <input type="email" name="email" id="atbdp-email-address" placeholder="<?php _e( 'user@email.com', 'directorist'); ?>" value="support@aazztech.com">
                            </div>
                            <div class="atbds_form-row">
                                <label><?php _e( 'Subject', 'directorist' ); ?></label>
                                <input type="text" name="subject" id="atbdp-email-subject" placeholder="<?php _e( 'Subject', 'directorist'); ?>"/>
                            </div>
                            <div class="atbds_form-row">
                                <label><?php _e( 'Additional Message', 'directorist' ); ?></label>
                                <textarea name="message" id="atbdp-email-message"></textarea>
                            </div>
                            <div class="atbds_form-row">
                                <label><?php _e( 'Remote Viewing Url', 'directorist' ); ?></label>
                                <input type="text" name="system-info" id="atbdp-system-info-url" placeholder="" value="<?php echo ! empty( $url ) ? $url : ''; ?>">
                            </div>
                            <div class="atbds_form-row">
                            <p class='system_info_success'></p>
                            <input type="hidden" name='_email_nonce' id='atbdp_email_nonce' value='<?php echo wp_create_nonce( '_debugger_email_nonce' ); ?>' />
                                <button class="atbds_btn atbds_btnPrimary" id="atbdp-send-system-info-submit"><?php _e( 'Send Mail', 'directorist' ); ?></button>
                            </div>
                        </form>
                    </div><!-- ends: .atbds_supportForm -->
                </div>
            </div>
        </div>
		<?php
		do_action( 'atbdp_tools_email_system_info_after' );
    
    }
}