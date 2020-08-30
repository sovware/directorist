<?php
/*
 * Template Name: Directorist Single Listing Template
 */
get_header();
do_action( 'atbdp_before_main_content' );
$single_temp_max_width      = get_directorist_option('single_temp_max_width',1080);
$single_temp_padding_top    = get_directorist_option('single_temp_padding_top',30);
$single_temp_padding_bottom = get_directorist_option('single_temp_padding_bottom',50);
$single_temp_padding_left   = get_directorist_option('single_temp_padding_left',4);
$single_temp_padding_right  = get_directorist_option('single_temp_padding_right',4);
$single_temp_margin_top     = get_directorist_option('single_temp_margin_top',4);
$single_temp_margin_bottom  = get_directorist_option('single_temp_margin_bottom',50);
$single_temp_margin_left    = get_directorist_option('single_temp_margin_left',4);
$single_temp_margin_right   = get_directorist_option('single_temp_margin_right',4);
?>
    <style>
        #atbdp-wrapper{
            padding-top: <?php echo !empty($single_temp_padding_top) ? $single_temp_padding_top : 30;?>px;
            padding-bottom: <?php echo !empty($single_temp_padding_bottom) ? $single_temp_padding_bottom : 50;?>px;
            padding-left: <?php echo !empty($single_temp_padding_left) ? $single_temp_padding_left : 4;?>px;
            padding-right: <?php echo !empty($single_temp_padding_right) ? $single_temp_padding_right : 4;?>px;
            margin-top: <?php echo !empty($single_temp_margin_top) ? $single_temp_margin_top : 4;?>px;
            margin-bottom: <?php echo !empty($single_temp_margin_bottom) ? $single_temp_margin_bottom : 50;?>px;
            margin-left: <?php echo !empty($single_temp_margin_left) ? $single_temp_margin_left : 4;?>px;
            margin-right: <?php echo !empty($single_temp_margin_right) ? $single_temp_margin_right : 4;?>px;
        }
        #atbdp-main{
            max-width: <?php echo !empty($single_temp_max_width) ? $single_temp_max_width : 1080;?>px;
            width: 100%;
            margin: 0 auto;
        }
    </style>
    <div id="atbdp-wrapper">
        <div id="atbdp-main">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile; // end of the loop. ?>

        </div><!-- #content -->
    </div><!-- #primary -->

<?php
do_action( 'atbdp_before_footer' );
get_footer();
