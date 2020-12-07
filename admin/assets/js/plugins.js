jQuery(document).ready(function($) {
        $('[data-slug="directorist"]').after('<tr class="directorist-extensions"></tr>');
        $('.directorist-extensions').append(
                $(
                        '<td colspan="4"><div class="ext-all-wrapper"><table><tbody class="de-list"></tbody></table></div></td>'
                )
        );
        const tbody = $('.directorist-extensions').find('.de-list');
        const extWrapper = $('.directorist-extensions').find('.ext-all-wrapper');
        $(extWrapper).append(
                '<div class="ext-more"><a href="" class="ext-more-link">Click to view directorist all modules</a></div>'
        );
        const moreLink = $('.directorist-extensions').find('.ext-more-link');

        $(tbody).append(
                $(`
        [data-slug="directorist-business-hour"],
        [data-slug="directorist-ads-manager"],
        [data-slug="directorist-claim-listing"],
        [data-slug="directorist-compare-listing"],
        [data-slug="directorist-coupon"],
        [data-slug="directorist-listings-faqs"],
        [data-slug="directorist-live-chat"],
        [data-slug="directorist-mark-as-sold"],
        [data-slug="directorist-paypal-payment-gateway"],
        [data-slug="directorist-post-your-need"],
        [data-slug="directorist-pricing-plans"],
        [data-slug="directorist-social-login"],
        [data-slug="directorist-stripe-payment-gateway"],
        [data-slug="directorist-woocommerce-pricing-plans"],
        [data-slug="directorist-booking"],
        [data-slug="directorist-google-recaptcha"],
        [data-slug="directorist-listings-slider-carousel"],
        [data-slug="directorist-listings-with-map"],
        [data-slug="directorists"]
        `)
        );

        if ($(extWrapper).innerHeight() > 250) {
                $(extWrapper).addClass('ext-height-fix');
        }
        $(moreLink).on('click', function(e) {
                e.preventDefault();
                if ($(extWrapper).hasClass('ext-height-fix')) {
                        $(extWrapper)
                                .animate({ height: '100%' }, 'fast')
                                .removeClass('ext-height-fix');
                } else {
                        $(extWrapper)
                                .animate({ height: '250px' }, 'fast')
                                .addClass('ext-height-fix');
                }
        });
});
