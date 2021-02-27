<?php

// it handles directorist upgrade
class ATBDP_Upgrade
{
    public $notice_id = 'migrate_to_7';

    public $directorist = 'directorist/directorist-base.php';

    public function __construct()
    {
        if ( !is_admin() ) {
			return;
		}

        add_action('admin_init', array($this, 'configure_notices'));

        add_action('admin_notices', array($this, 'upgrade_notice'), 100);
        global $pagenow;
        if ( 'plugins.php' === $pagenow )
        {
          add_action( 'in_plugin_update_message-'. $this->directorist, array($this, 'directorist_plugin_update_notice'), 20, 2 );
        }
    }
  /**
    * Displays an update message for plugin list screens.
    * 
    * @param (array) $plugin_data
    * @param (object) $response
    * @return (string) $output
    */
    public function directorist_plugin_update_notice( $plugin_data, $response )
   {

       $new_version = $response->new_version;
       if( '7.0' == $new_version ){
           ob_start() ?>
            <div class="directorist-admin-notice-content">
                <span class="directorist-highlighted-text"><strong>Attention!</strong> This is a major upgrade that includes significant changes and improvements. Make sure you have a backup of your site before upgrading.</span>
                <div></div>
                <p class="directorist-update-label">Take a look at the notable features</p>
                <ul class="directorist-update-list">
                    <li>Multi directory</li>
                    <li>Custom form and layout builder</li>
                    <li>New settings panel</li>
                    <li>Templating</li>
                    <li>Admin debugger</li>
                </ul>
            </div>
           <?php
        $output = ob_get_clean();
        return print $output;
       }
   }

    public function upgrade_notice()
    {
        if (!current_user_can('administrator')) return;

        if( '7.0' !== ATBDP_VERSION ) return;

        if ( ( get_user_meta( get_current_user_id(), $this->notice_id, true ) ) ) return;

		$text = '';

		$link = 'https://directorist.com/features/';
        $membership_page = admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension');

        $wp_rollback = 'https://wordpress.org/plugins/wp-rollback/';

		$text .= sprintf( __( '<p style="margin:2px 0;">Congratulations! You are now using the latest version of Directorist with some cool <a href="%s" target="blank">new features</a>. If you are using any of our premium theme or extension, please update them from this <a href="%s">page</a> </p>', 'directorist' ), $link, $membership_page ) ;

		$text .= sprintf( __( '<p style="margin:2px 0;"><a href="%s">Everything is OK</a> | Rollback to 6.5.8 using this<a target="blank" style="color: red;" href="%s"> plugin</a></p>', 'directorist' ), add_query_arg( 'directorist-v7', 1 ), $wp_rollback ) ;

		$notice = '<div class="notice notice-warning is-dismissible directorist-plugin-updater-notice" style="font-weight:bold;padding-top: 5px;padding-bottom: 5px;">' . $text . '</div>';

		echo wp_kses_post( $notice );

    }

    public function configure_notices(){
        if ( isset( $_GET['directorist-v7'] ) ) {
			update_user_meta( get_current_user_id(), $this->notice_id, 1 );
		}
    }

}