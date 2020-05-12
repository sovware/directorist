<?php

// it handles directorist upgrade
class ATBDP_Upgrade
{

    public function __construct()
    {
       // add_action('admin_notices', array($this, 'upgrade_notice'), 100);
    }

    public function upgrade_notice()
    {
        if (!current_user_can('administrator')) return false;
        

    }

}