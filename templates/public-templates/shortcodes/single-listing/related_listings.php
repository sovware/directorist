<?
/**
  * @package Directorist
  * @since 5.10.0
  */
do_action('atbdp_before_related_listing_start', $post);
if ($related_listings->have_posts()) {
    $templete = apply_filters('atbdp_related_listing_template', 'default');
    related_listing_slider($related_listings, $pagenation = null, $is_disable_price, $templete);
} 

do_action('atbdp_after_single_listing', $post, $listing_info);
?>
<script>
    jQuery(document).ready(function ($) {
        $('.related__carousel').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 300,
            slidesToShow: <?php echo $rel_listing_column;?>,
            slidesToScroll: 1,
            autoplay: true,
            rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: <?php echo $rel_listing_column;?>,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>