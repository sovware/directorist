<?php

// it handles directorist upgrade
class ATBDP_Upgrade
{
    public $upgrade_notice_id       = 'migrate_to_7';

    public $legacy_notice_id        = 'directorist_legacy_template';

    public $directorist_notices     = [];

    public $directorist_migration   = [];

    public function __construct()
    {
        if ( !is_admin() ) return;

        add_action('admin_init', array($this, 'configure_notices'));

        add_action('admin_notices', array($this, 'upgrade_notice'), 100);

        add_action('admin_notices', array($this, 'legacy_depricated_notice'), 101);

    }

    public function legacy_depricated_notice()
    {
        if (! current_user_can('administrator')) return;

        if( ! directorist_legacy_mode() ) return;

        if ( ! empty( $this->directorist_notices[ $this->legacy_notice_id ] ) ) return;

		$text = '';

		$link = 'https://directorist.com/contact/';

		$text .= sprintf( __( '<p class="directorist__notice_new"><span>Deprication Notice!</span> You are using the legacy version of Directorist templates. It is highly recommended to use the non-legacy version since we will stop providing support for it in the next version. If you are facing any issues with the non-legacy template then please contact <a target="_blank" href="%s">support</a> </p>', 'directorist' ), $link );

		$text .= sprintf( __( '<p class=""><a href="%s" class="">Dismiss</a></p>', 'directorist' ), add_query_arg( 'directorist-depricated-notice', 1 ) );

		$notice = '<div class="notice notice-error is-dismissible directorist-plugin-updater-notice">' . $text . '</div>';

		echo wp_kses_post( $notice );

    }

    public function upgrade_notice()
    {
        if (!current_user_can('administrator')) return;

        if( '7.0' !== ATBDP_VERSION ) return;

        if ( get_user_meta( get_current_user_id(), $this->upgrade_notice_id, true ) || ! empty( $this->directorist_migration[ $this->upgrade_notice_id ] ) ) return;

		$text = '';

		$link = 'https://directorist.com/blog/directorist-7-0-released/';
        $membership_page = admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension');

        $wp_rollback = 'https://wordpress.org/plugins/wp-rollback/';

		$text .= sprintf( __( '<p class="directorist__notice_new"><span>Congratulations!</span> You are now using the latest version of Directorist with some cool <a href="%s" target="blank">new features</a>. If you are using any of our premium theme or extension, please update them from this <a href="%s">page</a> </p>', 'directorist' ), $link, $membership_page );

		$text .= sprintf( __( '<p class="directorist__notice_new_action"><a href="%s" class="directorist__notice_new__btn">Continue using Directorist 7.0 </a><a target="blank" href="%s"> Roll back to v6.5.8</a></p>', 'directorist' ), add_query_arg( 'directorist-v7', 1 ), $wp_rollback );

		$notice = '<div class="notice notice-warning is-dismissible directorist-plugin-updater-notice" style="font-weight:bold;padding-top: 5px;padding-bottom: 5px;">' . $text . '</div>';

		echo wp_kses_post( $notice );

    }

    public function configure_notices(){

        $this->directorist_notices      = get_option( 'directorist_notices' );

        $this->directorist_migration    = get_option( 'directorist_migration' );

        if ( isset( $_GET['directorist-v7'] ) ) {
            $this->directorist_migration[ $this->upgrade_notice_id ] = 1;
            update_option( 'directorist_migration', $this->directorist_migration );
		}

        if ( isset( $_GET['directorist-depricated-notice'] ) ) {
            $this->directorist_notices[ $this->legacy_notice_id ] = 1;
            update_option( 'directorist_notices', $this->directorist_notices );

		}
    }

}