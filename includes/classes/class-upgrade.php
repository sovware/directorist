<?php

// it handles directorist upgrade
class ATBDP_Upgrade
{

    public function __construct()
    {
       // add_action('admin_notices', array($this, 'upgrade_notice'), 100);
       global $pagenow;
        if ( 'plugins.php' === $pagenow )
        {
            // Better update message
            $hook = "in_plugin_update_message-directorist/directorist-base.php";
           // add_action( $hook, array($this, 'your_update_message_cb'), 20, 2 );
        }
    }

    public /**
    * Displays an update message for plugin list screens.
    * Shows only the version updates from the current until the newest version
    * 
    * @param (array) $plugin_data
    * @param (object) $r
    * @return (string) $output
    */
   function your_update_message_cb( $plugin_data, $r )
   {
       // readme contents
    //    $data       = file_get_contents( 'https://plugins.trac.wordpress.org/browser/directorist/trunk/readme.txt' );
   
       $new_version = $plugin_data['new_version'];
       if( '6.5.7' == $new_version ){
           ob_start() ?>
            <div>
                <strong style="color: red;">**Attention Please**</strong>
                <p>There are some significant changes in this new version, please have</p>
                <p>Changes...</p>
                <p>Changes...</p>
                <p>Changes...</p>
                <p>Changes...</p>
            </div>
           <?php
        $output = ob_get_clean();
        return print $output;
       }
   }

    public function upgrade_notice()
    {
        if (!current_user_can('administrator')) return false;
        

    }

}