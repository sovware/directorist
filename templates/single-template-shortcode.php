<?php
if ( !class_exists('ATBDP_Single_Templates') ) {
    class ATBDP_Single_Templates
    {
        public function __construct()
        {
            add_shortcode( 'directorist_listing_header', array( $this, 'directorist_listing_header' ));
            add_shortcode( 'directorist_listing_custom_field',array($this,'directorist_custom_field'));
            add_shortcode( 'directorist_listing_video',array($this,'directorist_listing_video'));
            add_shortcode( 'directorist_listing_map',array($this,'directorist_listing_map'));
            add_shortcode( 'directorist_listing_contact_information',array($this,'directorist_listing_contact_information'));
            add_shortcode( 'directorist_listing_contact_owner',array($this,'directorist_listing_contact_owner'));
            add_shortcode( 'directorist_listing_review',array($this,'directorist_listing_review'));
            add_shortcode( 'directorist_related_listings',array($this,'directorist_related_listings'));

        }

        // listing header area
        public function directorist_listing_header() {
            if(is_singular(ATBDP_POST_TYPE )) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-header.php';
            }
        }

        // listing custom fields area
        public function directorist_custom_field() {
            if(is_singular(ATBDP_POST_TYPE )) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-custom-fields.php';
            }
        }

        //listing video area
        public function directorist_listing_video() {
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-video.php';
            }
        }

        //listing map area
        public function directorist_listing_map() {
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-map.php';
            }
        }

        //listing contact information area
        public function directorist_listing_contact_information() {
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-contact-info.php';
            }
        }

        //listing contact owner area
        public function directorist_listing_contact_owner() {
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-contact-owner.php';
            }
        }

        //listing review area
        public function directorist_listing_review() {
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-review.php';
            }
        }

        //related listing area
        public function directorist_related_listings() {
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/related-listings.php';
            }
        }

    } // end class
}//end class condition