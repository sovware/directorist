<?php
/**
 * @author AazzTech
 */
class ATBDP_Status
{
    public $send_mail;
    public $custom_url;
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'status_menu' ), 60 );
        $this->include();
        $this->send_mail = new ATBDP_Send_Mail();
        $this->custom_url = new ATBDP_Custom_Url();
    }

    public function include() {
        include ATBDP_INC_DIR . '/system-status/system-info.php';
        include ATBDP_INC_DIR . '/system-status/send-mail.php';
        include ATBDP_INC_DIR . '/system-status/custom-url.php';
    }

     /**
     * Add system status menu page
     *
     * @return void
     */
    public function status_menu() {
        add_submenu_page( 'edit.php?post_type=at_biz_dir', __( 'Directorist status', 'directorist' ),  __( 'Status', 'directorist' ) , 'manage_options', 'directorist-status', array( $this, 'status_page' ) );
    }
    
    public function status_page() { ?>

        <div class='postbox'>
        <?php
        _e( 'System Status', 'directorist' );
        new ATBDP_System_Info();
       ?>
        </div>

        <div class='postbox'>
        <?php 
            $this->send_mail->send_email_to();
        ?>
        </div>

        <div class='postbox'>
        <?php 
            $this->custom_url->custom_link();
        ?>
        </div>
        <?php
		
		//$this->custom_link();
		//$this->warning_message();
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
new ATBDP_Status();