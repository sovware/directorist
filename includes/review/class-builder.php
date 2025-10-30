<?php
/**
 * Review form builder data class.
 *
 * @package Directorist\Review
 * @since 8.0
 */
namespace Directorist\Review;
use ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Builder {
    protected $fields   = [];

    protected $cookies_consent = false;

    protected $gdpr_consent = false;

    protected $gdpr_consent_label = '';

    protected $rating_type;

    private static $instance    = null;

    public static function get( $data ) {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $data );
        }

        return self::$instance;
    }

    private function __construct( $data ) {
        $this->load_data( $data );

        $consent_field = $this->get_field_by_widget_name( 'consent' );

        if ( $consent_field ) {
            $this->cookies_consent    = (bool) $consent_field['enable_cookie_consent'];
            $this->gdpr_consent       = (bool) $consent_field['enable_gdpr_consent'];
            $this->gdpr_consent_label = ! empty( $consent_field['consent_label'] ) ? $consent_field['consent_label'] : sprintf(
                __( 'I have read and agree to the <a href="%s" target="_blank">Privacy Policy</a> and <a href="%s" target="_blank">Terms of Service</a>', 'directorist' ),
                esc_url( ATBDP_Permalink::get_privacy_policy_page_url() ),
                esc_url( ATBDP_Permalink::get_terms_and_conditions_page_url() )
            );
        }

        $this->rating_type = ! empty( $data['rating_type'] ) ? $data['rating_type'] : 'single';
    }

    public function load_data( $data ) {
        $this->fields = $data['fields'] ?? [];
    }

    /**
     * Get rating type.
     *
     * @return string
     */
    public function get_rating_type() {
        return $this->rating_type;
    }

    public function is_rating_type_single() {
        return $this->rating_type === 'single';
    }

    public function get_name_label( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'name', 'label', $default );
    }

    public function get_name_placeholder( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'name', 'placeholder', $default );
    }

    public function get_email_label( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'email', 'label', $default );
    }

    public function get_email_placeholder( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'email', 'placeholder', $default );
    }

    public function get_website_label( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'website', 'label', $default );
    }

    public function get_website_placeholder( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'website', 'placeholder', $default );
    }

    /**
     * Get the label for the comment field.
     *
     * @deprecated 8.0 Use get_comment_field_label() instead.
     * @param string $default Default label text if not set.
     * @return string The label for the comment field.
     */
    public function get_comment_label( $default = '' ) {
        _deprecated_function( __METHOD__, '8.0' );
        return $this->get_comment_field_label( $default );
    }

    /**
     * Get comment field label.
     *
     * @param string $default Default label text.
     * @return string
     */
    public function get_comment_field_label( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'comment_label', $default );
    }

    public function get_comment_placeholder( $default = '' ) {
        return $this->get_field_attr_by_widget_name( 'comment', 'placeholder', $default );
    }

    public function is_cookies_consent_active() {
        return (bool) $this->cookies_consent;
    }

    public function is_gdpr_consent() {
        return (bool) $this->gdpr_consent;
    }

    public function gdpr_consent_label() {
        return $this->gdpr_consent_label;
    }

    public function is_website_field_active() {
        return (bool) $this->get_field_attr_by_widget_name( 'website', 'enable', false );
    }

    protected function get_field_attr_by_widget_name( $widget_name, $attr = 'label', $default = false ) {
        $field = $this->get_field_by_widget_name( $widget_name );

        if ( ! $field ) {
            return $default;
        }

        return isset( $field[ $attr ] ) ? $field[ $attr ] : $default;
    }

    protected function get_field_by_widget_name( $widget_name, $default = null ) {
        $field_key = "review_{$widget_name}";

        foreach ( $this->fields as $field ) {
            if ( $field['widget_child_name'] !== $field_key ) {
                continue;
            }

            return $field;
        }

        return $default;
    }
}
