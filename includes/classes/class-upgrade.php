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
          add_action( 'in_plugin_update_message-directorist/directorist-base.php', array($this, 'directorist_plugin_update_notice'), 20, 2 );
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
        if (!current_user_can('administrator')) return false;
        

    }

}