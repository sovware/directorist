<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * It creates roles for ATBDP and assign capability to those roles.
 * @since 3.0.0
 *
 * Class ATBDP_Roles
 */
class ATBDP_Roles {

     var $version = 4;

    public function __construct()
    {
        // Add custom ATBDP_Roles & Capabilities once only
        if (get_option('atbdp_roles_version') < $this->version){
            $this->add_caps();
            // Insert atbdp_roles_mapped option to the db to prevent mapping meta cap
            add_option( 'atbdp_roles_version', $this->version );
        }


        add_action('init', array($this, 'disable_admin_bar_for_subscribers'), 9);
        add_filter('wp_dropdown_users_args', array($this, 'add_subscribers_to_dropdown'), 10, 2 );


    }

    /**
     * @since 5.03
     */


    public function add_subscribers_to_dropdown( $query_args, $r ) {

        $query_args['who'] = '';
        return $query_args;

    }

    /**
     * @since 5.0.0
     * It restrict subscriber not to enter in wp admin bar
     */
    public function disable_admin_bar_for_subscribers(){
        if ( atbdp_logged_in_user() ):
            global $current_user;
            if( !empty( $current_user->caps['subscriber'] ) ):
                add_filter('show_admin_bar', '__return_false');
            endif;
            /*if (is_directoria_active() && !empty( $current_user->caps['administrator'] )):
                add_filter('show_admin_bar', '__return_false');
            endif;*/
        endif;
    }

    /**
     * It gets the WP Roles object.
     * 
     * @since 3.0.0
     * @access public
     * @return WP_Roles  It returns an object of WP_Roles Class.
     */
    public function getWpRoles() {
        global $wp_roles;

        if ( !empty($wp_roles) && is_object($wp_roles) ) {
            return $wp_roles;
        } else {
            if ( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
        }

        return $wp_roles;
    }

    /**
     * Add new capabilities.
     *
     * @since    3.0.0
     * @access   public
     */
    public function add_caps() {

        $wp_roles = $this->getWpRoles();

        if( is_object( $wp_roles ) ) {
            // Add all the core caps to the administrator so that he can do anything with our custom post types
            $custom_posts_caps = $this->get_core_caps(); // get caps array for our custom post(s)
            // Iterate over the array of post types and caps array and assign the cap to the administrator role.
            foreach( $custom_posts_caps as $single_post_caps ) {
                foreach( $single_post_caps as $cap ) {
                    $wp_roles->add_cap( 'administrator', $cap );
                }
            }

            /*lets add another capability to the admin to check him if he has cap to edit our settings, Though we can use default manage_options caps. However, if a shop manager has manage_options cap, we do not want to let him access to our plugin admin panel, we just want the admin to access the plugin's settings.*/
            $wp_roles->add_cap( 'administrator', 'manage_atbdp_options' );

            $custom_posts = array( 'at_biz_dir', 'atbdp_order' ); // we can add more custom post type here as we will work on the plugin eg. payment.
            // as author, contributor, and subscriber has the same caps, so lets loop over them and add the cap.
            $users_roles = apply_filters( 'atbdp_user_roles', array(
                'author', 
                'contributor', 
                'subscriber', 
                'shop_manager', 
                'customer', 
                'wcfm_vendor', 
                'seller', 
                'vendor', 
                'shop_vendor',
                'dc_vendor',
                'yith_vendor',
                ) );

            // Add the "editor" capabilities of all of our custom posts
            foreach ($custom_posts as $cp) {
                if(post_type_exists($cp)){
                $wp_roles->add_cap( 'editor', "edit_{$cp}s");
                $wp_roles->add_cap( 'editor', "edit_others_{$cp}s" );
                $wp_roles->add_cap( 'editor', "publish_{$cp}s");
                $wp_roles->add_cap( 'editor', "read_private_{$cp}s" );
                $wp_roles->add_cap( 'editor', "delete_{$cp}s");
                $wp_roles->add_cap( 'editor', "delete_private_{$cp}s" );
                $wp_roles->add_cap( 'editor', "delete_published_{$cp}s");
                $wp_roles->add_cap( 'editor', "delete_others_{$cp}s" );
                $wp_roles->add_cap( 'editor', "edit_private_{$cp}s");
                $wp_roles->add_cap( 'editor', "edit_published_{$cp}s" );
                }
            }

            // Add required capabilities of our post type to the author, contributor, and subscriber
            foreach ($users_roles as $users_role) {
                foreach ($custom_posts as $cp) {
                    if(post_type_exists($cp)){
                    $wp_roles->add_cap( $users_role, "edit_{$cp}s" );
                    $wp_roles->add_cap( $users_role, "publish_{$cp}s" );
                    $wp_roles->add_cap( $users_role, "delete_{$cp}s" );
                    $wp_roles->add_cap( $users_role, "delete_published_{$cp}s" );
                    $wp_roles->add_cap( $users_role, "edit_published_{$cp}s" );
                    }
                }
            }

        }
    }

    /**
     * Gets the core post type capabilities.
     *
     * @since    3.0.0
     * @access   public
     *
     * @return   array    $capabilities    Core post type capabilities.
     */
    public function get_core_caps() {

        $caps = array();

        $custom_posts = array( 'at_biz_dir', 'atbdp_order' ); // we can add more custom post type here as we will work on the plugin eg. payment.

        foreach( $custom_posts as $cp ) {
            if(post_type_exists($cp)){
                $caps[ $cp ] = array(

                    "edit_{$cp}",
                    "read_{$cp}",
                    "delete_{$cp}",
                    "edit_{$cp}s",
                    "edit_others_{$cp}s",
                    "publish_{$cp}s",
                    "read_private_{$cp}s",
                    "delete_{$cp}s",
                    "delete_private_{$cp}s",
                    "delete_published_{$cp}s",
                    "delete_others_{$cp}s",
                    "edit_private_{$cp}s",
                    "edit_published_{$cp}s",

                );
            }
        }

        return $caps;

    }

    /**
     * Filter a user's capabilities depending on specific context and/or privilege.
     *
     * @since    3.0.0
     * @access   public
     *
     * @param    array     $caps       Returns the user's actual capabilities.
     * @param    string    $cap        Capability name.
     * @param    int       $user_id    The user ID.
     * @param    array     $args       Adds the context to the cap. Typically the object ID.
     * @return   array                 Actual capabilities for meta capability.
     */
    public function meta_caps( $caps, $cap, $user_id, $args ) {

        $custom_posts = array( 'at_biz_dir', 'atbdp_order' );

        foreach ($custom_posts as $cp) {
            if(post_type_exists($cp)){
                if ( ! isset( $args[0] ) || $args[0] === $user_id ) {
                    break;
                }else{
                    // If editing, deleting, or reading a custom post from the above list, get the post and post type object.
                    if( "edit_{$cp}" == $cap || "delete_{$cp}" == $cap || "read_{$cp}" == $cap ) {
                        $post = get_post( $args[0] );
                        $post_type = get_post_type_object( $post->post_type );
                        // Set an empty array for the caps.
                        $caps = array();
                    }

                    // If editing a listing, assign the required capability.
                    if( "edit_{$cp}" == $cap ) {
                        if( $user_id == $post->post_author )
                            $caps[] = $post_type->cap->{'edit_'.$cp.'s'};
                        else
                            $caps[] = $post_type->cap->{'edit_others_'.$cp.'s'};
                    }

                    // If deleting a listing, assign the required capability.
                    else if( "delete_{$cp}" == $cap ) {
                        if( $user_id == $post->post_author )
                            $caps[] = $post_type->cap->{'delete_'.$cp.'s'};
                        else
                            $caps[] = $post_type->cap->{'delete_others_'.$cp.'s'};
                    }

                    // If reading a private listing, assign the required capability.
                    else if( "read_{$cp}" == $cap ) {
                        if( 'private' != $post->post_status )
                            $caps[] = 'read';
                        elseif ( $user_id == $post->post_author )
                            $caps[] = 'read';
                        else
                            $caps[] = $post_type->cap->{'read_private_'.$cp.'s'};
                    }
                }

            }
           
        }



        // Return the capabilities required by the user.
        return $caps;

    }

    /**
     * Remove core post type capabilities (called on uninstall).
     *
     * @since    3.0.0
     * @access   public
     */
    public function remove_caps() {

        global $wp_roles;

        if( class_exists( 'WP_Roles' ) ) {
            if( ! isset( $wp_roles ) ) {
                $wp_roles = new WP_Roles();
            }
        }

        if( is_object( $wp_roles ) ) {

            // Remove the "administrator" Capabilities
            $capabilities = $this->get_core_caps();

            foreach( $capabilities as $cap_group ) {
                foreach( $cap_group as $cap ) {
                    $wp_roles->remove_cap( 'administrator', $cap );
                }
            }

            $wp_roles->remove_cap( 'administrator', 'manage_atbdp_options' );

            $custom_posts = array( 'at_biz_dir', 'atbdp_order' ); // we can add more custom post type here as we will work on the plugin eg. payment.
            // as author, contributor, and subscriber has the same caps, so lets loop over them and add the cap.
            $users_roles = array('author', 'contributor', 'subscriber');


            // Remove the "editor" capabilities
            foreach ($custom_posts as $cp) {
                if(post_type_exists($cp)){
                $wp_roles->remove_cap( 'editor', "edit_{$cp}s");
                $wp_roles->remove_cap( 'editor', "edit_others_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "publish_{$cp}s");
                $wp_roles->remove_cap( 'editor', "read_private_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "delete_{$cp}s");
                $wp_roles->remove_cap( 'editor', "delete_private_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "delete_published_{$cp}s");
                $wp_roles->remove_cap( 'editor', "delete_others_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "edit_private_{$cp}s");
                $wp_roles->remove_cap( 'editor', "edit_published_{$cp}s" );
                }
            }

            // Remove the "editor" capabilities of all of our custom posts
            foreach ($custom_posts as $cp) {
                if(post_type_exists($cp)){
                $wp_roles->remove_cap( 'editor', "edit_{$cp}s");
                $wp_roles->remove_cap( 'editor', "edit_others_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "publish_{$cp}s");
                $wp_roles->remove_cap( 'editor', "read_private_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "delete_{$cp}s");
                $wp_roles->remove_cap( 'editor', "delete_private_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "delete_published_{$cp}s");
                $wp_roles->remove_cap( 'editor', "delete_others_{$cp}s" );
                $wp_roles->remove_cap( 'editor', "edit_private_{$cp}s");
                $wp_roles->remove_cap( 'editor', "edit_published_{$cp}s" );
                }
            }


            // Remove required capabilities of our post type to the author, contributor, and subscriber
            foreach ($users_roles as $users_role) {
                foreach ($custom_posts as $cp) {
                    if(post_type_exists($cp)){
                    $wp_roles->remove_cap( $users_role, "edit_{$cp}s" );
                    $wp_roles->remove_cap( $users_role, "publish_{$cp}s" );
                    $wp_roles->remove_cap( $users_role, "delete_{$cp}s" );
                    $wp_roles->remove_cap( $users_role, "delete_published_{$cp}s" );
                    $wp_roles->remove_cap( $users_role, "edit_published_{$cp}s" );
                    }
                }
            }

        }
    }
}
