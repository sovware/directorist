<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Payment_Receipt extends Custom_Widget_Base {
    public function __construct( $data = [], $args = null ) {
        $this->az_name = __( 'Payment Receipt', 'directorist' );
        $this->az_base = 'directorist_payment_receipt';
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
                'label'     => $this->az_texts['payment'],
            ],
            [
                'mode' => 'section_end',
            ],
        ];
        return $fields;
    }

    protected function render() {

        $shortcode = '[directorist_payment_receipt]';

        echo do_shortcode( $shortcode );
    }
}