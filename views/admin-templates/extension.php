<div id="directorist" class="directorist atbd_wrapper attc_extension_page">
    <div class="attc_extension_wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 attc_extension_wrapper__heading">
                    <h3><?php esc_html_e('Themes', 'directorist'); ?></h3>
                </div>
                <?php if ( ! function_exists('directoria_setup') ) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <div class="single_extension single_thm">
                        <img src="https://directorist.com/wp-content/uploads/2017/08/directoria_theme_prev-1-360x230.jpg"
                             alt="Directoria">

                        <div class="extension_detail">
                            <h4 class="ext_title"><a href="https://directorist.com/product/directoria" target="_blank">
                                <?php esc_html_e(' Directoria ', 'directorist'); ?></a></h4>

                            <div class="btn_wrapper">
                                <a href="https://demo.directorist.com/plugin/demo-one/" target="_blank"
                                   class="btn demo btn-primary"><?php esc_html_e('View demo', 'directorist'); ?></a>
                                <a href="https://directorist.com/product/directoria" target="_blank"
                                   class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if ( ! function_exists('dlist_setup') ) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <div class="single_extension single_thm">
                        <img src="https://directorist.com/wp-content/uploads/2019/08/dlist_prev_img-1-360x230.jpg"
                                alt="Direo">

                        <div class="extension_detail">
                            <h4 class="ext_title"><a href="https://directorist.com/product/dlist" target="_blank">
                                    <?php esc_html_e(' dList ', 'directorist'); ?></a></h4>

                            <div class="btn_wrapper">
                                <a href="https://demo.directorist.com/theme/dlist/" target="_blank"
                                    class="btn demo btn-primary"><?php esc_html_e('View demo', 'directorist'); ?></a>
                                <a href="https://directorist.com/product/dlist" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if ( ! function_exists('dservice_setup') ) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <div class="single_extension single_thm">
                        <img src="https://directorist.com/wp-content/uploads/2019/11/DService-360x230.jpg" alt="dService">

                        <div class="extension_detail">
                            <h4 class="ext_title">
                                <a href="http://directorist.com/product/dservice" target="_blank">
                                    <?php esc_html_e(' dService ', 'directorist'); ?>
                                </a>
                            </h4>

                            <div class="btn_wrapper">
                                <a href="https://demo.directorist.com/theme/dservice/" target="_blank"
                                   class="btn demo btn-primary"><?php esc_html_e('View demo', 'directorist'); ?></a>
                                <a href="http://directorist.com/product/dservice" target="_blank"
                                   class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="col-lg-12 attc_extension_wrapper__heading atcc_pt_40">
                    <h2><?php esc_html_e('Extensions', 'directorist'); ?></h3>
                </div>
                <?php 
                
                if (!class_exists('SWBDPCoupon')) {
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                        <!--Google reCAPTCHA-->
                        <div class="single_extension">
                            <img src="https://directorist.com/wp-content/uploads/2020/11/Coupon.png"
                                 alt="Coupon">

                            <div class="extension_detail ext_d">
                                <a href="https://directorist.com/product/directorist-coupon" target="_blank"
                                   class="ext_title">
                                    <h4><?php esc_html_e(' Coupon ', 'directorist'); ?></h4></a>
                                <p><?php esc_html_e('Create & offer unlimited coupon to increase your revenue.', 'directorist'); ?></p>

                                <div class="btn_wrapper">
                                    <a href="https://directorist.com/product/directorist-coupon" target="_blank"
                                       class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if ( ! class_exists('SWBDPAdsManager') ) {
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                        <!--Google reCAPTCHA-->
                        <div class="single_extension">
                            <img src="https://directorist.com/wp-content/uploads/2020/11/Ads-Manager.jpg"
                                 alt="Ads Manager">

                            <div class="extension_detail ext_d">
                                <a href="https://directorist.com/product/directorist-adverts-manager" target="_blank"
                                   class="ext_title">
                                    <h4><?php esc_html_e(' Ads Manager ', 'directorist'); ?></h4></a>
                                <p><?php esc_html_e('Create & sell unlimited ads to monetize your directory.', 'directorist'); ?></p>

                                <div class="btn_wrapper">
                                    <a href="https://directorist.com/product/directorist-adverts-manager" target="_blank"
                                       class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                
                if (!class_exists('ATBDP_Pricing_Plans')) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Google reCAPTCHA-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2019/02/fee_manager_prev-2-360x230.jpg"
                                alt="Fee Manager">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-pricing-plans" target="_blank"
                                class="ext_title">
                                <h4><?php esc_html_e(' Pricing Plans ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('Create & sell unlimited pricing plans to monetize your directory.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-pricing-plans" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('DWPP_Pricing_Plans')) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--WooCommerce Fee Manager-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2019/02/woocommerce_fee_manager_prev-2-360x230.jpg"
                                alt="WooCommerce Pricing Plans">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-woocommerce-pricing-plans" target="_blank"
                                class="ext_title"><h4>
                                    <?php esc_html_e(' WooCommerce Pricing Plans ', 'directorist'); ?> </h4></a>
                            <p><?php esc_html_e('Create & sell unlimited pricing plans to monetize your directory using WooCommerce.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-woocommerce-pricing-plans"
                                    target="_blank" class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if ( ! class_exists('Directorist_Stripe_Gateway')) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Stripe Gateway-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2017/11/directorist_stripe_ext_prev_img-2-360x230.jpg"
                            alt="Stripe">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-stripe" target="_blank" class="ext_title">
                                <h4><?php esc_html_e(' Stripe Payment Gateway ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('You can accept payment via Stripe using this payment gateway extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-stripe" target="_blank"
                                class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('Directorist_Paypal_Gateway')) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Paypal Gateway-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2018/04/directorist_paypal_ext_prev_img-2-360x230.jpg"
                            alt="PayPal">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-paypal" target="_blank" class="ext_title">
                                <h4>
                                    <?php esc_html_e(' PayPal Standard Payment Gateway ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('You can accept payment via PayPal using this payment gateway extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-paypal" target="_blank"
                                class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('DCL_Base')){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--WooCommerce Fee Manager-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2019/03/claim_listing_prev-2-360x230.jpg"
                                alt="WooCommerce Fee Manager">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-claim-listing" target="_blank"
                                class="ext_title"><h4>
                                    <?php esc_html_e(' Claim Listing ', 'directorist'); ?>  </h4></a>
                            <p><?php esc_html_e('Monetize your directory allowing business owners to claim their listing using this extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-claim-listing" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('Directorist_Live_Chat')) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--WooCommerce Fee Manager-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2020/04/Live_Chat-360x230.jpg"
                                alt="Live Chat">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-live-chat" target="_blank"
                                class="ext_title"><h4>
                                    <?php esc_html_e(' Live Chat ', 'directorist'); ?>  </h4></a>
                            <p><?php esc_html_e('It allows the visitors to contact business owners immediately and easily.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-live-chat" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('BD_Business_Hour')){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Business hour-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2017/11/directorist_business_hour_ext_prev_img-2-360x230.jpg"
                                alt="Business Hours">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-business-hour/" target="_blank"
                                class="ext_title"><h4><?php esc_html_e(' Business Hours ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('You can show Business hours / opening hours of a listing by this extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-business-hour/" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('Directorist_Mark_as_Sold')){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--WooCommerce Fee Manager-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/edd/2020/04/mark_as_sold_prev-360x230.jpg"
                                alt="Mark as Sold">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-mark-as-sold" target="_blank"
                                class="ext_title"><h4>
                                    <?php esc_html_e(' Mark as Sold ', 'directorist'); ?>  </h4></a>
                            <p><?php esc_html_e('It allows listing authors to show visitors if a particular item is sold or not.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-mark-as-sold" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('ATDListingCompare') ) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--WooCommerce Fee Manager-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2020/07/Compare-Listings-360x230.png"
                                alt="Compare Listing">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/compare-listing/" target="_blank"
                                class="ext_title"><h4>
                                    <?php esc_html_e(' Compare Listing ', 'directorist'); ?>  </h4></a>
                            <p><?php esc_html_e('Use compare listings to view listings side by side and compare its features.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/compare-listing/" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if ( !class_exists('Directorist_Featured_Labels') && 0 ){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--WooCommerce Fee Manager-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/edd/2020/05/rank_featured_listings-360x230.png"
                                alt="Rank Featured Listings">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/rank-featured-listings/" target="_blank"
                                class="ext_title"><h4>
                                    <?php esc_html_e(' Rank Featured Listings ', 'directorist'); ?>  </h4></a>
                            <p><?php esc_html_e('Rank Featured Listings is used to rank your featured listing items on your directory website.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/rank-featured-listings/" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('Post_Your_Need')) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Business hour-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/edd/2019/12/post_your_need_prev-360x230.jpg"
                                alt="Post Your Need">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-post-your-need/" target="_blank"
                                class="ext_title"><h4><?php esc_html_e(' Post Your Need ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('Add new feature for your users where they can post for any kind of need or service that they want.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-post-your-need/" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('BD_Map_View')){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Business hour-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/edd/2019/12/listings_with_map_prev-360x230.jpg"
                                alt="Listings With Map">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-listings-with-map/" target="_blank"
                                class="ext_title"><h4><?php esc_html_e(' Listings With Map ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('It adds new layout view that is listings and map side by side with advanced search/filters.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-listings-with-map/" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('Directorist_Social_Login')){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Business hour-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/edd/2019/12/social_login_prev-360x230.jpg"
                                alt="Directorist Social Login">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-social-login/" target="_blank"
                                class="ext_title"><h4><?php esc_html_e(' Directorist Social Login ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('It lets your visitors register and login to your site using their social profiles (Facebook and Google).', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-social-login/" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('BD_Google_Recaptcha')) { ?>

                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Google reCAPTCHA-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2018/10/directorist_google_recaptcha_ext_prev_img-2-360x230.jpg"
                                alt="Google reCAPTCHA">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/google-recaptcha" target="_blank"
                                class="ext_title">
                                <h4><?php esc_html_e(' Google reCAPTCHA ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('Protect your directory from spam entries with Google reCAPTCHA.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/google-recaptcha" target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('BD_Slider_Carousel')){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--WooCommerce Fee Manager-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2019/02/listing_slider_carousal_prev-2-360x230.jpg"
                                alt="Listings Slider">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-listings-slider-carousel"
                                target="_blank" class="ext_title"><h4>
                                    <?php esc_html_e(' Listings Slider and Carousel ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('Display listings slider anywhere on your website using this extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-listings-slider-and-carousel"
                                    target="_blank"
                                    class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('BD_Gallery')){ ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Gallery-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2019/02/image_gallery_prev-2-360x230.jpg"
                            alt="Image Gallery">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-image-gallery" target="_blank"
                            class="ext_title"><h4>
                                    <?php esc_html_e(' Image Gallery ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('Display listing images gallery on single listing page using this extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-image-gallery" target="_blank"
                                class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } if (!class_exists('Listings_fAQs')) { ?>
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--FAQs-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/2019/02/faqs_prev-2-360x230.jpg"
                            alt="Listing FAQs">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-listing-faqs" target="_blank"
                            class="ext_title"><h4>
                                    <?php esc_html_e(' Listing FAQs ', 'directorist'); ?></h4></a>
                            <p><?php esc_html_e('You can easily display listing\'s Frequently Asked Questions on each listing by using this extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-listing-faqs" target="_blank"
                                class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                    <!--Reservation-->
                    <div class="single_extension">
                        <img src="https://directorist.com/wp-content/uploads/edd/2020/04/booking_prev-360x230.png"
                            alt="WooCommerce Fee Manager">

                        <div class="extension_detail ext_d">
                            <a href="https://directorist.com/product/directorist-booking/" target="_blank"
                            class="ext_title"><h4>
                                    <?php esc_html_e(' Booking ( Reservation & Appointment ) ', 'directorist'); ?>   </h4></a>
                            <p><?php esc_html_e('Add a reservation/booking system on your directory using this extension.', 'directorist'); ?></p>

                            <div class="btn_wrapper">
                                <a href="https://directorist.com/product/directorist-booking/" target="_blank"
                                class="btn get btn-success"><?php esc_html_e('Get It Now', 'directorist'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>