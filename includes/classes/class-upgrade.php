<?php
// it handles directorist upgrade
class ATBDP_Upgrade{

    public function __construct()
    {
        add_action('admin_notices', array( $this, 'upgrade_notice' ), 100 );
    }

    public function upgrade_notice() {
        if (!current_user_can('administrator')) return false;
        /**
         * @since 6.2.3
         * Add notice for extension users
         */
        $extensions = array(
          'ATBDP_Pricing_Plans' => array(
              'version' => '1.5.3',
              'name' => 'atpp',
              'base' => 'directorist-pricing-plans/directorist-pricing-plans.php',
          ),
            'DWPP_Pricing_Plans' => array(
              'version' => '1.2.4',
              'name' => 'dwpp',
              'base' => 'directorist-woocommerce-pricing-plans/directorist-woocommerce-pricing-plans.php',
          ),
            'BD_Google_Recaptcha' => array(
                'version' => '1.1.4',
                'name' => 'recaptcha',
                'base' => 'directorist-google-recaptcha/directorist-google-recaptcha.php',
            ),
            'BD_Gallery' => array(
                'version' => '1.1.6',
                'name' => 'gallery',
                'base' => 'directorist-gallery/bd-directorist-gallery.php',
            ),
          'Post_Your_Need' => array(
              'version' => '1.0.2',
              'name' => 'need',
              'base' => 'directorist-post-your-need/directorist-post-your-need.php',
          ),
            'BD_Business_Hour' => array(
              'version' => '2.3.2',
              'name' => 'hours',
              'base' => 'directorist-business-hours/bd-business-hour.php',
          ),
        );
        if ((!function_exists('direo_setup') && !function_exists('dlist_setup') && !function_exists('dservice_setup') && !function_exists('drestaurant_setup') && !function_exists('findbiz_setup'))){
            foreach ($extensions as $class => $data){
                if (class_exists($class)){
                    $response = $data['name'].'-true';
                    $extension_link = '<a class="atbdp-update-extension" data-update-info="'.$response.'" href="#">'.__('Update Now', 'directorist').'</a>';
                    if ( $data['version'] > atbdp_get_plugin_data($data['base'])['Version'] ){
                        echo '<div id="message" class="notice notice-info" style="display: flex; background: #f7bdc7;  justify-content: space-between;"><p>';
                        printf(__('There is a new version of <b>%s</b> available. %s', 'directorist'),atbdp_get_plugin_data($data['base'])['Name'], $extension_link);
                        echo '</p><p><a href="#"></a></p></div>';
                    }
                }
            }

        }
        $themes = array(
            'direo' => array(
                'version' => '1.6.4',
            ),
            'dlist' => array(
                'version' => '1.2.6',
            ),
            'dservice' => array(
                'version' => '1.0.1',
            ),
        );
        $current_theme = wp_get_theme()->get_stylesheet();
        $version = wp_get_theme()[ 'Version' ];
        foreach ($themes as $theme => $data){
            $response = $theme.'-true';
            $link = '<a class="atbdp-update-extension" data-update-info="'.$response.'" href="#">'.__('Update Now', 'directorist').'</a>';
           if (($theme === $current_theme) && ($version <= $data['version'])) {
               echo '<div id="message" class="notice notice-info" style="display: flex; background: #f7bdc7;  justify-content: space-between;"><p>';
               printf(__('There is a new version of <b>%s</b> available. %s', 'directorist'),ucwords($theme), $link);
               echo '</p><p><a href="#"></a></p></div>';
           }
        }

    }

}