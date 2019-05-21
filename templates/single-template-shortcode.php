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
            add_shortcode( 'directorist_sidebar',array($this,'directorist_sidebar'));

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

        //listing contact owner area
        public function directorist_listing_contact_owner() {
            ob_start();
            if(is_singular(ATBDP_POST_TYPE)) {
                include ATBDP_TEMPLATES_DIR . 'front-end/single-listing/listing-contact-owner.php';
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

        public function directorist_sidebar() { ?>

<?php
        }

    } // end class
}//end class condition