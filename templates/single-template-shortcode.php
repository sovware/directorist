<?php
if ( !class_exists('ATBDP_Single_Templates') ) {
    class ATBDP_Single_Templates
    {
        public function __construct()
        {
            add_shortcode( 'directorist_listing_top_area', array( $this, 'directorist_listing_header' ));
            add_shortcode( 'directorist_listing_tags',array($this,'directorist_tags'));
            add_shortcode( 'directorist_listing_custom_fields',array($this,'directorist_custom_field'));
            add_shortcode( 'directorist_listing_video',array($this,'directorist_listing_video'));
            add_shortcode( 'directorist_listing_map',array($this,'directorist_listing_map'));
            add_shortcode( 'directorist_listing_contact_information',array($this,'directorist_listing_contact_information'));
            add_shortcode( 'directorist_listing_author_info', array($this,'directorist_listing_author_details'));
            add_shortcode( 'directorist_listing_contact_owner',array($this,'directorist_listing_contact_owner'));
            add_shortcode( 'directorist_listing_review',array($this,'directorist_listing_review'));
            add_shortcode( 'directorist_related_listings',array($this,'directorist_related_listings'));
        }

        // listing header area
        public function directorist_listing_header() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE )) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-header.php';
            }
            return ob_get_clean();
        }

        // listing custom fields area
        public function directorist_tags() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE )) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-tags.php';
            }
            return ob_get_clean();
        }

        // listing custom fields area
        public function directorist_custom_field() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE )) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-custom-fields.php';
            }
            return ob_get_clean();
        }


        //listing video area
        public function directorist_listing_video() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-video.php';
            }
            return ob_get_clean();
        }

        //listing map area
        public function directorist_listing_map() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-map.php';
            }
            return ob_get_clean();
        }

        //listing contact information area
        public function directorist_listing_contact_information() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-contact-info.php';
            }
            return ob_get_clean();
        }

        //listing author details
        public function directorist_listing_author_details() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-author-info.php';
            }
            return ob_get_clean();
        }

        //listing contact owner area
        public function directorist_listing_contact_owner() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-contact-owner.php';
                //do_action('atbdp_after_contact_listing_owner_section', $listing_id);
            }
            return ob_get_clean();
        }

        //listing review area
        public function directorist_listing_review() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-review.php';
            }
            return ob_get_clean();
        }

        //related listing area
        public function directorist_related_listings() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/related-listings.php';
            }
            return ob_get_clean();
        }


    } // end class
}//end class condition