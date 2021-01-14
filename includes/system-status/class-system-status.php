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
        include ATBDP_INC_DIR . '/system-status/system-information/system-information.php';
        include ATBDP_INC_DIR . '/system-status/send-mail.php';
        include ATBDP_INC_DIR . '/system-status/custom-url.php';
    }

     /**
     * Add system status menu page
     *
     * @return void
     */
    public function status_menu() {
        add_submenu_page( 'edit.php?post_type=at_biz_dir', __( 'Help & Support', 'directorist' ),  __( 'Help & Support', 'directorist' ) , 'manage_options', 'directorist-status', array( $this, 'tools_page' ) );
    }
    
    public function tools_page() {
        include ATBDP_INC_DIR . '/system-status/template.php'; 
    }

    public function status_page() { ?>

        <div class='postbox'>
        <?php
        _e( 'Help & Support', 'directorist' );
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

        <div class='postbox'>
            <?php 
            include ATBDP_INC_DIR . '/system-status/warning.php';
            ?>
        </div>
        <?php
		
		//$this->custom_link();
		//$this->warning_message();
    }
    
}
new ATBDP_Status();