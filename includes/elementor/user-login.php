<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_User_Login extends Custom_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        $this->az_name = __( 'Login', 'directorist' );
        $this->az_base = 'directorist_user_login';
        parent::__construct( $data, $args );
    }

    public function az_fields() {
        $fields = [
            [
                'mode'    => 'section_start',
                'id'      => 'sec_general',
                'label'   => __( 'General', 'directorist' ),
            ],
            [
                'type'      => Controls_Manager::HEADING,
                'id'        => 'sec_heading',
                'label'     => __( 'This widget works only in Login page. It has no additional elementor settings.', 'directorist' ),
            ],
            [
                'mode' => 'section_end',
            ],
        ];
        return $fields;
    }

    protected function render() {

        $shortcode = '[directorist_user_login]';

        echo do_shortcode( $shortcode );
    }
}