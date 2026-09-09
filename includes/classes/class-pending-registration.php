<?php
/**
 * Pending frontend registration management.
 *
 * @package Directorist
 * @since 8.9.3
 */

namespace Directorist;

use ATBDP_Permalink;
use WP_Error;
use WP_User;

defined( 'ABSPATH' ) || exit;

/**
 * Defers WordPress user creation until an email address is verified.
 */
class Pending_Registration {
    const SCHEMA_VERSION  = '1.0.0';
    const SCHEMA_OPTION   = 'directorist_pending_registrations_db_version';
    const CLEANUP_HOOK    = 'directorist_cleanup_pending_registrations';
    const TOKEN_QUERY_ARG = 'directorist_pending_registration_token';

    /**
     * Pending registrations table name.
     *
     * @var string
     */
    private $table_name;

    /**
     * Register hooks.
     */
    public function __construct() {
        global $wpdb;

        $this->table_name = $wpdb->prefix . 'directorist_pending_registrations';

        add_action( 'init', [ $this, 'maybe_install_schema' ], 5 );
        add_action( 'init', [ $this, 'schedule_cleanup' ], 20 );
        add_action( self::CLEANUP_HOOK, [ $this, 'cleanup_expired' ] );
        add_action( 'template_redirect', [ $this, 'handle_verification_request' ], 5 );
    }

    /**
     * Create or update the private pending-registration table when required.
     *
     * @param bool $verify_table Whether to confirm that the versioned table exists.
     */
    public function maybe_install_schema( $verify_table = false ) {
        global $wpdb;

        if ( self::SCHEMA_VERSION === get_option( self::SCHEMA_OPTION ) ) {
            if ( ! $verify_table || $this->table_name === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $this->table_name ) ) ) ) {
                return;
            }
        }

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();
        $sql             = "CREATE TABLE {$this->table_name} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_login varchar(60) NOT NULL,
            user_email varchar(100) NOT NULL,
            password_hash varchar(255) NOT NULL,
            profile_data longtext NOT NULL,
            token_hmac char(64) NOT NULL,
            ip_hash char(64) NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'pending',
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            last_sent_at datetime NOT NULL,
            expires_at datetime NOT NULL,
            verified_at datetime DEFAULT NULL,
            resend_count int(10) unsigned NOT NULL DEFAULT 0,
            PRIMARY KEY  (id),
            UNIQUE KEY user_login (user_login),
            UNIQUE KEY user_email (user_email),
            UNIQUE KEY token_hmac (token_hmac),
            KEY status_expires (status, expires_at),
            KEY ip_created (ip_hash, created_at)
        ) {$charset_collate};";

        dbDelta( $sql );

        if ( $this->table_name === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $this->table_name ) ) ) ) {
            update_option( self::SCHEMA_OPTION, self::SCHEMA_VERSION, false );
        }
    }

    /**
     * Schedule daily expired-record cleanup.
     */
    public function schedule_cleanup() {
        if ( ! wp_next_scheduled( self::CLEANUP_HOOK ) ) {
            wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', self::CLEANUP_HOOK );
        }
    }

    /**
     * Delete records after their verification window has expired.
     */
    public function cleanup_expired() {
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$this->table_name} WHERE expires_at < %s", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                current_time( 'mysql', true )
            )
        );
    }

    /**
     * Reserve a frontend registration and send its verification email.
     *
     * @param array $registration Sanitized registration fields, including password.
     * @return array|WP_Error Pending record details or an error.
     */
    public function create( array $registration ) {
        global $wpdb;

        $this->maybe_install_schema( true );

        $user_login = sanitize_user( $registration['user_login'], true );
        $user_email = sanitize_email( $registration['user_email'] );

        $rate_limit = $this->check_rate_limits( $user_email );
        if ( is_wp_error( $rate_limit ) ) {
            return $rate_limit;
        }

        $this->record_request( $user_email );

        if ( 4 > strlen( $user_login ) || 60 < strlen( $user_login ) || ! is_email( $user_email ) ) {
            return new WP_Error( 'registration_invalid', __( 'The username or email is not valid.', 'directorist' ) );
        }

        if ( username_exists( $user_login ) || email_exists( $user_email ) ) {
            return new WP_Error( 'registration_exists', __( 'This username or email is already registered.', 'directorist' ) );
        }

        $existing = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE user_login = %s OR user_email = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $user_login,
                $user_email
            ),
            ARRAY_A
        );

        if ( $existing && 'pending' === $existing['status'] && strtotime( $existing['expires_at'] . ' UTC' ) > time() ) {
            return new WP_Error( 'registration_pending', __( 'A verification request is already pending. Please check your email or use the resend link.', 'directorist' ) );
        }

        if ( $existing ) {
            $wpdb->delete( $this->table_name, [ 'id' => (int) $existing['id'] ], [ '%d' ] );
        }

        $raw_token        = $this->generate_token();
        $now              = current_time( 'mysql', true );
        $profile_data     = $registration;
        $password         = $profile_data['password'];
        $generated        = ! empty( $profile_data['generated_password'] );
        $verification_url = $this->get_verification_url( $raw_token );

        unset( $profile_data['password'], $profile_data['user_login'], $profile_data['user_email'] );

        $inserted = $wpdb->insert(
            $this->table_name,
            [
                'user_login'    => $user_login,
                'user_email'    => $user_email,
                'password_hash' => wp_hash_password( $password ),
                'profile_data'  => wp_json_encode( $profile_data ),
                'token_hmac'    => $this->hash_token( $raw_token ),
                'ip_hash'       => $this->get_ip_hash(),
                'status'        => 'pending',
                'created_at'    => $now,
                'updated_at'    => $now,
                'last_sent_at'  => $now,
                'expires_at'    => gmdate( 'Y-m-d H:i:s', time() + DAY_IN_SECONDS ),
                'verified_at'   => null,
                'resend_count'  => 0,
            ],
            [ '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' ]
        );

        if ( ! $inserted ) {
            return new WP_Error( 'registration_storage_failed', __( 'The registration could not be saved. Please try again.', 'directorist' ) );
        }

        $pending_id = (int) $wpdb->insert_id;

        $mail_data = [
            'user_login'         => $user_login,
            'user_email'         => $user_email,
            'first_name'         => isset( $registration['first_name'] ) ? $registration['first_name'] : '',
            'last_name'          => isset( $registration['last_name'] ) ? $registration['last_name'] : '',
            'generated_password' => $generated,
        ];

        if ( ! ATBDP()->email->send_pending_user_confirmation_email( $mail_data, $verification_url ) ) {
            $wpdb->update(
                $this->table_name,
                [ 'status' => 'failed', 'updated_at' => current_time( 'mysql', true ) ],
                [ 'id' => $pending_id, 'status' => 'pending' ],
                [ '%s', '%s' ],
                [ '%d', '%s' ]
            );

            return new WP_Error( 'registration_email_failed', __( 'The verification email could not be sent. Please try again later.', 'directorist' ) );
        }

        return [
            'id'         => $pending_id,
            'user_email' => $user_email,
        ];
    }

    /**
     * Resend a pending verification email with a rotated token.
     *
     * @param string $email Pending email address.
     * @return true|WP_Error True on success or an error.
     */
    public function resend( $email ) {
        global $wpdb;

        $this->maybe_install_schema( true );

        $email = sanitize_email( $email );

        $rate_limit = $this->check_rate_limits( $email );
        if ( is_wp_error( $rate_limit ) ) {
            return $rate_limit;
        }

        $this->record_request( $email );

        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE user_email = %s LIMIT 1", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $email
            ),
            ARRAY_A
        );

        if ( ! $row || 'pending' !== $row['status'] || strtotime( $row['expires_at'] . ' UTC' ) <= time() ) {
            return new WP_Error( 'registration_not_pending', __( 'No active verification request was found.', 'directorist' ) );
        }

        if ( strtotime( $row['last_sent_at'] . ' UTC' ) > time() - ( 5 * MINUTE_IN_SECONDS ) ) {
            return new WP_Error( 'registration_resend_cooldown', __( 'Please wait five minutes before requesting another verification email.', 'directorist' ) );
        }

        $profile_data = json_decode( $row['profile_data'], true );
        if ( ! is_array( $profile_data ) ) {
            $profile_data = [];
        }

        $raw_token        = $this->generate_token();
        $token_hmac       = $this->hash_token( $raw_token );
        $previous_hmac    = $row['token_hmac'];
        $previous_sent_at = $row['last_sent_at'];
        $now              = current_time( 'mysql', true );

        $updated = $wpdb->update(
            $this->table_name,
            [
                'token_hmac'   => $token_hmac,
                'updated_at'   => $now,
                'last_sent_at' => $now,
                'expires_at'   => gmdate( 'Y-m-d H:i:s', time() + DAY_IN_SECONDS ),
                'resend_count' => (int) $row['resend_count'] + 1,
            ],
            [ 'id' => (int) $row['id'], 'token_hmac' => $previous_hmac, 'status' => 'pending' ],
            [ '%s', '%s', '%s', '%s', '%d' ],
            [ '%d', '%s', '%s' ]
        );

        if ( 1 !== $updated ) {
            return new WP_Error( 'registration_resend_failed', __( 'The verification email could not be resent. Please try again.', 'directorist' ) );
        }

        $mail_data = [
            'user_login'         => $row['user_login'],
            'user_email'         => $row['user_email'],
            'first_name'         => isset( $profile_data['first_name'] ) ? $profile_data['first_name'] : '',
            'last_name'          => isset( $profile_data['last_name'] ) ? $profile_data['last_name'] : '',
            'generated_password' => ! empty( $profile_data['generated_password'] ),
        ];

        if ( ! ATBDP()->email->send_pending_user_confirmation_email( $mail_data, $this->get_verification_url( $raw_token ) ) ) {
            $wpdb->update(
                $this->table_name,
                [ 'token_hmac' => $previous_hmac, 'last_sent_at' => $previous_sent_at ],
                [ 'id' => (int) $row['id'], 'token_hmac' => $token_hmac, 'status' => 'pending' ],
                [ '%s', '%s' ],
                [ '%d', '%s', '%s' ]
            );

            return new WP_Error( 'registration_email_failed', __( 'The verification email could not be sent. Please try again later.', 'directorist' ) );
        }

        return true;
    }

    /**
     * Exchange a raw pending token for one WordPress user.
     *
     * @param string $raw_token Raw token from the email URL.
     * @return array|WP_Error Verification result or an error.
     */
    public function verify( $raw_token ) {
        global $wpdb;

        if ( ! is_string( $raw_token ) || ! preg_match( '/\A[a-f0-9]{64}\z/', $raw_token ) ) {
            return new WP_Error( 'invalid_token', __( 'This verification link is invalid.', 'directorist' ) );
        }

        $this->maybe_install_schema( true );
        $token_hmac = $this->hash_token( $raw_token );

        $wpdb->query( 'START TRANSACTION' );

        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE token_hmac = %s LIMIT 1 FOR UPDATE", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $token_hmac
            ),
            ARRAY_A
        );

        if ( ! $row ) {
            $wpdb->query( 'ROLLBACK' );
            return new WP_Error( 'invalid_token', __( 'This verification link is invalid or has already been used.', 'directorist' ) );
        }

        if ( 'pending' !== $row['status'] ) {
            $wpdb->query( 'ROLLBACK' );
            return new WP_Error( 'used_token', __( 'This verification link has already been used.', 'directorist' ) );
        }

        if ( strtotime( $row['expires_at'] . ' UTC' ) <= time() ) {
            $wpdb->update( $this->table_name, [ 'status' => 'expired' ], [ 'id' => (int) $row['id'] ], [ '%s' ], [ '%d' ] );
            $wpdb->query( 'COMMIT' );
            return new WP_Error( 'expired_token', __( 'This verification link has expired.', 'directorist' ) );
        }

        $claimed = $wpdb->update(
            $this->table_name,
            [ 'status' => 'processing', 'updated_at' => current_time( 'mysql', true ) ],
            [ 'id' => (int) $row['id'], 'status' => 'pending' ],
            [ '%s', '%s' ],
            [ '%d', '%s' ]
        );

        if ( 1 !== $claimed ) {
            $wpdb->query( 'ROLLBACK' );
            return new WP_Error( 'used_token', __( 'This verification link has already been used.', 'directorist' ) );
        }

        if ( username_exists( $row['user_login'] ) || email_exists( $row['user_email'] ) ) {
            $wpdb->update( $this->table_name, [ 'status' => 'failed' ], [ 'id' => (int) $row['id'] ], [ '%s' ], [ '%d' ] );
            $wpdb->query( 'COMMIT' );
            return new WP_Error( 'registration_conflict', __( 'That username or email is no longer available.', 'directorist' ) );
        }

        $profile_data = json_decode( $row['profile_data'], true );
        if ( ! is_array( $profile_data ) ) {
            $profile_data = [];
        }

        $password_filter = static function ( $data, $update, $user_id, $userdata ) use ( $row ) {
            if ( ! $update && isset( $userdata['user_login'], $userdata['user_email'] ) && $row['user_login'] === $userdata['user_login'] && $row['user_email'] === $userdata['user_email'] ) {
                $data['user_pass'] = $row['password_hash'];
            }

            return $data;
        };

        $user_hook_priority = has_action( 'user_register', [ ATBDP()->user, 'action_user_register' ] );
        if ( false !== $user_hook_priority ) {
            remove_action( 'user_register', [ ATBDP()->user, 'action_user_register' ], $user_hook_priority );
        }

        $creation_error = null;

        try {
            add_filter( 'wp_pre_insert_user_data', $password_filter, 10, 4 );

            $user_id = wp_insert_user(
                [
                    'user_login'  => $row['user_login'],
                    'user_email'  => $row['user_email'],
                    'user_pass'   => wp_generate_password( 32, true, true ),
                    'user_url'    => isset( $profile_data['website'] ) ? $profile_data['website'] : '',
                    'first_name'  => isset( $profile_data['first_name'] ) ? $profile_data['first_name'] : '',
                    'last_name'   => isset( $profile_data['last_name'] ) ? $profile_data['last_name'] : '',
                    'description' => isset( $profile_data['bio'] ) ? $profile_data['bio'] : '',
                    'role'        => 'subscriber',
                ]
            );
        } catch ( \Throwable $throwable ) {
            $creation_error = new WP_Error( 'registration_creation_failed', __( 'The account could not be created. Please try again.', 'directorist' ) );
        } finally {
            remove_filter( 'wp_pre_insert_user_data', $password_filter, 10 );

            if ( false !== $user_hook_priority ) {
                add_action( 'user_register', [ ATBDP()->user, 'action_user_register' ], $user_hook_priority );
            }
        }

        if ( $creation_error ) {
            $wpdb->query( 'ROLLBACK' );
            $wpdb->update( $this->table_name, [ 'status' => 'failed' ], [ 'id' => (int) $row['id'] ], [ '%s' ], [ '%d' ] );
            return $creation_error;
        }

        if ( is_wp_error( $user_id ) || ! $user_id ) {
            $wpdb->update( $this->table_name, [ 'status' => 'failed' ], [ 'id' => (int) $row['id'] ], [ '%s' ], [ '%d' ] );
            $wpdb->query( 'COMMIT' );
            return $user_id;
        }

        $verified_at = current_time( 'mysql', true );
        $completed   = $wpdb->update(
            $this->table_name,
            [ 'status' => 'verified', 'updated_at' => $verified_at, 'verified_at' => $verified_at ],
            [ 'id' => (int) $row['id'], 'status' => 'processing' ],
            [ '%s', '%s', '%s' ],
            [ '%d', '%s' ]
        );

        if ( 1 !== $completed ) {
            $wpdb->query( 'ROLLBACK' );
            clean_user_cache( $user_id );
            return new WP_Error( 'registration_creation_failed', __( 'The account could not be created. Please try again.', 'directorist' ) );
        }

        $wpdb->query( 'COMMIT' );

        do_action( 'atbdp_user_registration_completed', $user_id );
        update_user_meta( $user_id, '_atbdp_privacy', isset( $profile_data['privacy_policy'] ) ? $profile_data['privacy_policy'] : '' );
        update_user_meta( $user_id, '_user_type', isset( $profile_data['user_type'] ) ? $profile_data['user_type'] : '' );
        update_user_meta( $user_id, '_atbdp_terms_and_conditions', isset( $profile_data['terms_and_conditions'] ) ? $profile_data['terms_and_conditions'] : '' );

        wp_new_user_notification( $user_id, null, 'admin' );
        ATBDP()->email->custom_wp_new_user_notification_email( $user_id );

        return [
            'user_id'            => (int) $user_id,
            'user_email'         => $row['user_email'],
            'generated_password' => ! empty( $profile_data['generated_password'] ),
        ];
    }

    /**
     * Process pending verification links before rendering the account page.
     */
    public function handle_verification_request() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- The one-time HMAC token authenticates this request.
        if ( empty( $_GET[ self::TOKEN_QUERY_ARG ] ) ) {
            return;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- The one-time HMAC token authenticates this request.
        $raw_token = sanitize_text_field( wp_unslash( $_GET[ self::TOKEN_QUERY_ARG ] ) );
        $result    = $this->verify( $raw_token );

        if ( is_wp_error( $result ) ) {
            wp_safe_redirect(
                ATBDP_Permalink::get_signin_signup_page_link(
                    [ 'pending_registration_error' => $result->get_error_code() ]
                )
            );
            exit;
        }

        $query_args = [ 'pending_registration_verified' => 1 ];

        if ( $result['generated_password'] ) {
            $user = get_user_by( 'id', $result['user_id'] );
            $key  = $user instanceof WP_User ? get_password_reset_key( $user ) : new WP_Error( 'invalid_user' );

            if ( is_wp_error( $key ) ) {
                $query_args = [ 'pending_registration_error' => 'set_password_failed' ];
            } else {
                $query_args = [
                    'user'                          => base64_encode( $result['user_email'] ),
                    'key'                           => $key,
                    'password_reset'                => 1,
                    'pending_registration_verified' => 1,
                ];
            }
        }

        wp_safe_redirect( ATBDP_Permalink::get_signin_signup_page_link( $query_args ) );
        exit;
    }

    /**
     * Get a safe message for a verification result code.
     *
     * @param string $code Result code.
     * @return string
     */
    public function get_verification_error_message( $code ) {
        $messages = [
            'invalid_token'                => __( 'This verification link is invalid or has already been used.', 'directorist' ),
            'used_token'                   => __( 'This verification link has already been used.', 'directorist' ),
            'expired_token'                => __( 'This verification link has expired. Please register again.', 'directorist' ),
            'registration_conflict'        => __( 'That username or email is no longer available. Please register again.', 'directorist' ),
            'registration_creation_failed' => __( 'The account could not be created. Please register again.', 'directorist' ),
            'set_password_failed'          => __( 'Your account was verified, but the password form could not be opened. Please use password recovery.', 'directorist' ),
            'registration_not_pending'     => __( 'No active verification request was found.', 'directorist' ),
            'registration_resend_cooldown' => __( 'Please wait five minutes before requesting another verification email.', 'directorist' ),
            'registration_rate_limited'    => __( 'Too many registration requests. Please try again in one hour.', 'directorist' ),
            'registration_resend_failed'   => __( 'The verification email could not be resent. Please try again.', 'directorist' ),
            'registration_email_failed'    => __( 'The verification email could not be sent. Please try again later.', 'directorist' ),
        ];

        return isset( $messages[ $code ] ) ? $messages[ $code ] : $messages['invalid_token'];
    }

    /**
     * Convert a pending-registration error to a legacy form error number.
     *
     * @param WP_Error $error Registration error.
     * @return int
     */
    public function get_legacy_error_code( WP_Error $error ) {
        $map = [
            'registration_pending'        => 10,
            'registration_rate_limited'   => 11,
            'registration_email_failed'   => 12,
            'registration_storage_failed' => 12,
            'registration_exists'         => 4,
        ];

        return isset( $map[ $error->get_error_code() ] ) ? $map[ $error->get_error_code() ] : 12;
    }

    /**
     * Generate a cryptographically random URL token.
     *
     * @return string
     */
    private function generate_token() {
        try {
            return bin2hex( random_bytes( 32 ) );
        } catch ( \Exception $exception ) {
            return hash( 'sha256', wp_generate_password( 64, true, true ) . microtime( true ) );
        }
    }

    /**
     * HMAC a raw token before persistence.
     *
     * @param string $token Raw token.
     * @return string
     */
    private function hash_token( $token ) {
        return hash_hmac( 'sha256', $token, wp_salt( 'auth' ) );
    }

    /**
     * Create a salted, non-reversible client IP identifier.
     *
     * @return string
     */
    private function get_ip_hash() {
        $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
        $ip = apply_filters( 'directorist_registration_ip_address', $ip );

        return hash_hmac( 'sha256', (string) $ip, wp_salt( 'nonce' ) );
    }

    /**
     * Check one-hour registration request limits.
     *
     * @param string $email Registration email.
     * @return true|WP_Error
     */
    private function check_rate_limits( $email ) {
        $keys = $this->get_rate_limit_keys( $email );

        if ( (int) get_transient( $keys['ip'] ) >= 5 || (int) get_transient( $keys['email'] ) >= 3 ) {
            return new WP_Error( 'registration_rate_limited', __( 'Too many registration requests. Please try again in one hour.', 'directorist' ) );
        }

        return true;
    }

    /**
     * Record a registration or resend request for one hour.
     *
     * @param string $email Registration email.
     */
    private function record_request( $email ) {
        foreach ( $this->get_rate_limit_keys( $email ) as $key ) {
            set_transient( $key, (int) get_transient( $key ) + 1, HOUR_IN_SECONDS );
        }
    }

    /**
     * Build privacy-safe transient keys for rate limiting.
     *
     * @param string $email Registration email.
     * @return array
     */
    private function get_rate_limit_keys( $email ) {
        return [
            'ip'    => 'directorist_reg_ip_' . substr( $this->get_ip_hash(), 0, 32 ),
            'email' => 'directorist_reg_email_' . substr( hash_hmac( 'sha256', strtolower( $email ), wp_salt( 'nonce' ) ), 0, 32 ),
        ];
    }

    /**
     * Build the pending verification URL.
     *
     * @param string $raw_token Raw token.
     * @return string
     */
    private function get_verification_url( $raw_token ) {
        return ATBDP_Permalink::get_signin_signup_page_link(
            [ self::TOKEN_QUERY_ARG => $raw_token ]
        );
    }
}
